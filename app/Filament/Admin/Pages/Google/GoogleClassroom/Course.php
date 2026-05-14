<?php

namespace App\Filament\Admin\Pages\Google\GoogleClassroom;

use App\Models\GSuiteUser;
use App\Services\GoogleService;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Pages\Page;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Components\EmptyState;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class Course extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-academic-cap';
    protected static string | \UnitEnum | null $navigationGroup = 'Google';
    protected static ?string $navigationLabel = 'Course';

    protected static ?string $slug = 'google/classroom/course/{courseId?}';

    protected static bool $shouldRegisterNavigation = false;

    public ?string $courseId = null;
    public ?array $course = null;
    public array $teachers = [];
    public array $students = [];
    public array $domainUsers = [];
    public string $rosterTab = 'teachers';

    /**
     * @var array<string, string>
     */
    protected array $resolvedEmailsByUserId = [];

    protected ?\Google\Service\Directory $directoryServiceInstance = null;

    public string $new_announcement_text = '';
    public string $new_assignment_title = '';
    public string $new_assignment_description = '';

    public function mount(?string $courseId = null): void
    {
        $this->courseId = $courseId;
        $this->loadDomainUsers();

        if (filled($this->courseId)) {
            $this->loadCourseMeta($this->courseId);
            $this->loadCourseRoster($this->courseId);
        }
    }

    public function content(Schema $schema): Schema
    {
        return $schema->schema([
            EmptyState::make('No course selected')
                ->description('Open a course from the Courses page.')
                ->icon('heroicon-o-academic-cap')
                ->visible(fn (): bool => blank($this->courseId)),

            Section::make('Course Info')
                ->description(fn (): ?string => $this->courseId ? ('Course ID: ' . $this->courseId) : null)
                ->visible(fn (): bool => filled($this->courseId))
                ->schema([
                    Text::make(fn (): string => (string) ($this->course['name'] ?? 'Selected Course'))
                        ->weight('bold'),
                    Text::make(fn (): string => ! empty($this->course['section']) ? ('Section: ' . $this->course['section']) : 'Section: -')
                        ->color('gray'),
                    Actions::make([
                        Action::make('backToCourses')
                            ->label('Back to Courses')
                            ->icon('heroicon-m-arrow-left')
                            ->color('gray')
                            ->url(fn (): string => Courses::getUrl()),
                        Action::make('syncRoster')
                            ->label('Sync roster')
                            ->icon('heroicon-m-arrow-path')
                            ->color('gray')
                            ->action(fn () => $this->syncRosterToLocal()),
                    ]),
                ]),

            Tabs::make('Manage People')
                ->livewireProperty('rosterTab')
                ->visible(fn (): bool => filled($this->courseId))
                ->tabs([
                    'teachers' => Tabs\Tab::make('Teachers')
                        ->icon('heroicon-m-user-circle')
                        ->schema([
                            EmbeddedTable::make(),
                        ]),
                    'students' => Tabs\Tab::make('Students')
                        ->icon('heroicon-m-users')
                        ->schema([
                            EmbeddedTable::make(),
                        ]),
                ]),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('addTeachersHeader')
                ->label('Add Teachers')
                ->icon('heroicon-m-user-plus')
                ->color('primary')
                ->visible(fn (): bool => filled($this->courseId) && $this->rosterTab === 'teachers')
                ->schema([
                    Textarea::make('emails')
                        ->label('Teacher emails/userIds')
                        ->required()
                        ->rows(8)
                        ->helperText('Enter one or many values separated by new lines, commas, or spaces.'),
                ])
                ->modalHeading('Add Teachers')
                ->modalSubmitActionLabel('Add')
                ->action(fn (array $data) => $this->bulkAddPeople('teachers', (string) ($data['emails'] ?? ''))),
            Action::make('addStudentsHeader')
                ->label('Add Students')
                ->icon('heroicon-m-user-plus')
                ->color('primary')
                ->visible(fn (): bool => filled($this->courseId) && $this->rosterTab === 'students')
                ->schema([
                    Textarea::make('emails')
                        ->label('Student emails/userIds')
                        ->required()
                        ->rows(8)
                        ->helperText('Enter one or many values separated by new lines, commas, or spaces.'),
                ])
                ->modalHeading('Add Students')
                ->modalSubmitActionLabel('Add')
                ->action(fn (array $data) => $this->bulkAddPeople('students', (string) ($data['emails'] ?? ''))),
            Action::make('announcement')
                ->label('Create Announcement')
                ->icon('heroicon-m-megaphone')
                ->color('gray')
                ->visible(fn (): bool => filled($this->courseId))
                ->schema([
                    TextInput::make('text')->label('Announcement text')->required(),
                ])
                ->action(function (array $data): void {
                    $this->new_announcement_text = (string) ($data['text'] ?? '');
                    $this->createAnnouncement();
                }),
            Action::make('assignment')
                ->label('Create Assignment')
                ->icon('heroicon-m-document-plus')
                ->color('gray')
                ->visible(fn (): bool => filled($this->courseId))
                ->schema([
                    TextInput::make('title')->label('Title')->required(),
                    TextInput::make('description')->label('Description (optional)'),
                ])
                ->action(function (array $data): void {
                    $this->new_assignment_title = (string) ($data['title'] ?? '');
                    $this->new_assignment_description = (string) ($data['description'] ?? '');
                    $this->createAssignment();
                }),
        ];
    }

    public function table(Table $table): Table
    {
        $activeRole = $this->rosterTab === 'students' ? 'students' : 'teachers';
        $recordLabel = $activeRole === 'students' ? 'Student' : 'Teacher';

        return $table
            ->records(fn (): array => collect($activeRole === 'students' ? $this->students : $this->teachers)
                ->map(fn (array $person): array => [
                    'key' => (string) ($person['userId'] ?? ''),
                    'userId' => (string) ($person['userId'] ?? ''),
                    'name' => (string) ($person['name'] ?? ''),
                    'email' => (string) ($person['email'] ?? ''),
                ])
                ->values()
                ->all())
            ->columns([
                TextColumn::make('name')
                    ->label($recordLabel)
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('userId')
                    ->label('User ID')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordAction('remove')
            ->actions([
                Action::make('remove')
                    ->label('Remove')
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (array $record): bool => $this->removeRosterRecord((string) ($record['userId'] ?? ''))),
            ])
            ->bulkActions([
                BulkAction::make('removeSelected')
                    ->label('Remove selected')
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (Collection $records): bool => $this->removeBulkRosterRecords($records)),
            ])
            ->paginated(false)
            ->selectable()
            ->emptyStateHeading('No ' . str($recordLabel)->plural()->lower() . ' found')
            ->emptyStateDescription('There are no ' . str($recordLabel)->plural()->lower() . ' in this course yet.')
            ->emptyStateIcon($activeRole === 'students' ? 'heroicon-o-users' : 'heroicon-o-user');
    }

    // Teacher/Student actions are handled from page header actions.

    protected function loadDomainUsers(): void
    {
        $this->domainUsers = GSuiteUser::query()
            ->whereNotNull('email')
            ->pluck('email')
            ->filter()
            ->values()
            ->toArray();
    }

    protected function loadCourseMeta(string $courseId): void
    {
        try {
            $service = new GoogleService();
            $classroom = $service->classroomService();
            $course = $classroom->courses->get($courseId);

            $this->course = [
                'id' => $course->getId(),
                'name' => $course->getName(),
                'section' => $course->getSection(),
                'ownerId' => (string) ($course->getOwnerId() ?? ''),
                'ownerEmail' => $this->resolveRosterEmail(
                    userId: (string) ($course->getOwnerId() ?? ''),
                    emailFromProfile: null,
                ),
            ];
        } catch (\Exception $e) {
            Notification::make()->title('Failed to load course')->danger()->body($e->getMessage())->send();
        }
    }

    protected function loadCourseRoster(string $courseId): void
    {
        try {
            $service = new GoogleService();
            $classroom = $service->classroomService();

            $this->teachers = [];
            $this->students = [];

            $teacherResponse = $classroom->courses_teachers->listCoursesTeachers($courseId, ['pageSize' => 200]);
            foreach ($teacherResponse->getTeachers() ?? [] as $teacher) {
                $profile = $teacher->getProfile();
                $profileArr = $profile ? json_decode(json_encode($profile), true) : null;

                $this->teachers[] = [
                    'userId' => $teacher->getUserId(),
                    'name' => $teacher->getProfile()?->getName()?->getFullName()
                        ?? data_get($profileArr, 'name.fullName'),
                    'email' => $this->resolveRosterEmail(
                        userId: (string) $teacher->getUserId(),
                        emailFromProfile: $teacher->getProfile()?->getEmailAddress()
                        ?? data_get($profileArr, 'emailAddress')
                    ),
                ];
            }

            $studentResponse = $classroom->courses_students->listCoursesStudents($courseId, ['pageSize' => 500]);
            foreach ($studentResponse->getStudents() ?? [] as $student) {
                $profile = $student->getProfile();
                $profileArr = $profile ? json_decode(json_encode($profile), true) : null;

                $this->students[] = [
                    'userId' => $student->getUserId(),
                    'name' => $student->getProfile()?->getName()?->getFullName()
                        ?? data_get($profileArr, 'name.fullName'),
                    'email' => $this->resolveRosterEmail(
                        userId: (string) $student->getUserId(),
                        emailFromProfile: $student->getProfile()?->getEmailAddress()
                        ?? data_get($profileArr, 'emailAddress')
                    ),
                ];
            }

            $this->flushCachedTableRecords();
        } catch (\Exception $e) {
            Notification::make()->title('Failed to load roster')->danger()->body($e->getMessage())->send();
        }
    }

    public function updatedRosterTab(): void
    {
        $this->flushCachedTableRecords();
    }

    public function syncRosterToLocal(): void
    {
        if (! $this->courseId) {
            Notification::make()->title('Select a course')->danger()->send();
            return;
        }

        try {
            $service = new GoogleService();
            $classroom = $service->classroomService();

            $teacherResponse = $classroom->courses_teachers->listCoursesTeachers($this->courseId, ['pageSize' => 500]);
            foreach ($teacherResponse->getTeachers() ?? [] as $teacher) {
                $email = $this->resolveRosterEmail(
                    userId: (string) $teacher->getUserId(),
                    emailFromProfile: $teacher->getProfile()?->getEmailAddress(),
                );

                if (! $email) {
                    continue;
                }

                $appUser = \App\Models\User::where('email', $email)->first();
                GSuiteUser::updateOrCreate(['email' => $email], ['user_id' => $appUser?->id]);
            }

            $studentResponse = $classroom->courses_students->listCoursesStudents($this->courseId, ['pageSize' => 500]);
            foreach ($studentResponse->getStudents() ?? [] as $student) {
                $email = $this->resolveRosterEmail(
                    userId: (string) $student->getUserId(),
                    emailFromProfile: $student->getProfile()?->getEmailAddress(),
                );

                if (! $email) {
                    continue;
                }

                $appUser = \App\Models\User::where('email', $email)->first();
                GSuiteUser::updateOrCreate(['email' => $email], ['user_id' => $appUser?->id]);
            }

            Notification::make()->title('Roster synced')->success()->send();
        } catch (\Exception $e) {
            Notification::make()->title('Sync failed')->danger()->body($e->getMessage())->send();
        }
    }


    public function createAnnouncement(): void
    {
        if (! $this->courseId || blank($this->new_announcement_text)) {
            Notification::make()->title('Announcement text required')->danger()->send();
            return;
        }

        try {
            $service = new GoogleService();
            $classroom = $service->classroomService();

            $classroom->courses_announcements->create($this->courseId, new \Google\Service\Classroom\Announcement([
                'text' => $this->new_announcement_text,
            ]));

            $this->new_announcement_text = '';
            Notification::make()->title('Announcement created')->success()->send();
        } catch (\Exception $e) {
            Notification::make()->title('Create announcement failed')->danger()->body($e->getMessage())->send();
        }
    }

    public function createAssignment(): void
    {
        if (! $this->courseId || blank($this->new_assignment_title)) {
            Notification::make()->title('Assignment title required')->danger()->send();
            return;
        }

        try {
            $service = new GoogleService();
            $classroom = $service->classroomService();

            $classroom->courses_courseWork->create($this->courseId, new \Google\Service\Classroom\CourseWork([
                'title' => $this->new_assignment_title,
                'description' => $this->new_assignment_description ?: null,
                'workType' => 'ASSIGNMENT',
            ]));

            $this->new_assignment_title = '';
            $this->new_assignment_description = '';
            Notification::make()->title('Assignment created')->success()->send();
        } catch (\Exception $e) {
            Notification::make()->title('Create assignment failed')->danger()->body($e->getMessage())->send();
        }
    }

    protected function bulkAddPeople(string $role, string $rawIdentifiers): void
    {
        if (! $this->courseId) {
            Notification::make()->title('Select a course')->danger()->send();
            return;
        }

        $identifiers = $this->parseIdentifiers($rawIdentifiers);

        if ($identifiers === []) {
            Notification::make()->title('Provide at least one email/userId')->danger()->send();
            return;
        }

        $successCount = 0;
        $failed = [];

        $service = new GoogleService();
        $classroom = $service->classroomService();

        foreach ($identifiers as $identifier) {
            try {
                if ($role === 'teachers') {
                    $classroom->courses_teachers->create($this->courseId, new \Google\Service\Classroom\Teacher([
                        'userId' => $identifier,
                    ]));
                } else {
                    $classroom->courses_students->create($this->courseId, new \Google\Service\Classroom\Student([
                        'userId' => $identifier,
                    ]));
                }

                $successCount++;
            } catch (\Exception $e) {
                $failed[] = $identifier . ': ' . $e->getMessage();
            }
        }

        if ($successCount > 0) {
            $this->loadCourseRoster($this->courseId);
            $this->dispatch('course-roster-updated', courseId: $this->courseId);
        }

        $this->sendBulkResultNotification(
            action: 'added',
            role: $role,
            successCount: $successCount,
            failed: $failed,
        );
    }

    protected function bulkRemovePeople(string $role, string $rawIdentifiers): void
    {
        $identifiers = $this->parseIdentifiers($rawIdentifiers);

        if ($identifiers === []) {
            Notification::make()->title('Provide at least one userId/email')->danger()->send();
            return;
        }

        $this->bulkRemovePeopleWithResult(
            role: $role,
            identifiers: $identifiers,
            shouldNotifySingle: false,
        );
    }

    protected function removeRosterRecord(string $userId): bool
    {
        if (blank($userId)) {
            return false;
        }

        return $this->bulkRemovePeopleWithResult(
            role: $this->rosterTab === 'students' ? 'students' : 'teachers',
            identifiers: [$userId],
            shouldNotifySingle: true,
        );
    }

    protected function removeBulkRosterRecords(Collection $records): bool
    {
        $identifiers = $records
            ->map(fn (array $record): string => (string) ($record['userId'] ?? ''))
            ->filter(fn (string $userId): bool => filled($userId))
            ->unique()
            ->values()
            ->all();

        if ($identifiers === []) {
            Notification::make()->title('No records selected')->danger()->send();

            return false;
        }

        return $this->bulkRemovePeopleWithResult(
            role: $this->rosterTab === 'students' ? 'students' : 'teachers',
            identifiers: $identifiers,
            shouldNotifySingle: false,
        );
    }

    /**
     * @param  array<int, string>  $identifiers
     */
    protected function bulkRemovePeopleWithResult(string $role, array $identifiers, bool $shouldNotifySingle): bool
    {
        if (! $this->courseId) {
            Notification::make()->title('Select a course')->danger()->send();

            return false;
        }

        $successCount = 0;
        $failed = [];

        $service = new GoogleService();
        $classroom = $service->classroomService();

        foreach ($identifiers as $identifier) {
            $error = $this->removeCoursePerson(
                classroom: $classroom,
                role: $role,
                identifier: $identifier,
            );

            if ($error === null) {
                $successCount++;

                continue;
            }

            $failed[] = $identifier . ': ' . $error;
        }

        if ($successCount > 0) {
            $this->loadCourseRoster($this->courseId);
            $this->dispatch('course-roster-updated', courseId: $this->courseId);
        }

        if ($shouldNotifySingle && ($successCount === 1) && (count($failed) === 0)) {
            Notification::make()
                ->title($role === 'teachers' ? 'Teacher removed' : 'Student removed')
                ->success()
                ->send();

            return true;
        }

        $this->sendBulkResultNotification(
            action: 'removed',
            role: $role,
            successCount: $successCount,
            failed: $failed,
        );

        return $successCount > 0;
    }

    /**
     * @return array<int, string>
     */
    protected function parseIdentifiers(string $rawIdentifiers): array
    {
        return collect(preg_split('/[\s,;]+/', $rawIdentifiers) ?: [])
            ->map(fn (string $value): string => trim($value))
            ->filter(fn (string $value): bool => filled($value))
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @param  array<int, string>  $failed
     */
    protected function sendBulkResultNotification(string $action, string $role, int $successCount, array $failed): void
    {
        $label = $role === 'teachers' ? 'teacher(s)' : 'student(s)';
        $failedCount = count($failed);
        $actionBase = match ($action) {
            'added' => 'add',
            'removed' => 'remove',
            default => $action,
        };

        if (($successCount > 0) && ($failedCount === 0)) {
            Notification::make()
                ->title("Successfully {$action} {$successCount} {$label}")
                ->success()
                ->send();

            return;
        }

        if (($successCount === 0) && ($failedCount > 0)) {
            Notification::make()
                ->title("Failed to {$actionBase} {$label}")
                ->danger()
                ->body(implode("\n", array_slice($failed, 0, 5)))
                ->send();

            return;
        }

        Notification::make()
            ->title("Partially {$action} {$label}: {$successCount} succeeded, {$failedCount} failed")
            ->warning()
            ->body(implode("\n", array_slice($failed, 0, 5)))
            ->send();
    }

    protected function removeCoursePerson(\Google\Service\Classroom $classroom, string $role, string $identifier): ?string
    {
        if (blank($identifier) || blank($this->courseId)) {
            return 'Invalid identifier.';
        }

        try {
            $this->deleteCoursePersonByIdentifier($classroom, $role, $identifier);

            return null;
        } catch (\Throwable $error) {
            $retryIdentifier = $this->resolveRetryIdentifier($role, $identifier);

            if (filled($retryIdentifier) && ($retryIdentifier !== $identifier)) {
                try {
                    $this->deleteCoursePersonByIdentifier($classroom, $role, $retryIdentifier);

                    return null;
                } catch (\Throwable $retryError) {
                    $error = $retryError;
                }
            }

            if ($this->isNotFoundError($error)) {
                // Already removed or no longer enrolled.
                return null;
            }

            if ($role === 'students' && $this->isUnenrollPermissionError($error)) {
                if ($this->removeStudentWithPrivilegedSubjects($identifier)) {
                    return null;
                }

                return 'Permission denied to unenroll this student. Use a delegated account that is a course teacher/owner or Classroom admin.';
            }

            if ($this->isPermissionDeniedError($error)) {
                return 'Permission denied by Google Classroom API for this operation.';
            }

            return $this->formatGoogleApiError($error);
        }
    }

    protected function deleteCoursePersonByIdentifier(\Google\Service\Classroom $classroom, string $role, string $identifier): void
    {
        if ($role === 'teachers') {
            $classroom->courses_teachers->delete($this->courseId, $identifier);

            return;
        }

        $classroom->courses_students->delete($this->courseId, $identifier);
    }

    protected function resolveRetryIdentifier(string $role, string $identifier): ?string
    {
        if (str($identifier)->contains('@')) {
            return null;
        }

        $source = $role === 'teachers' ? $this->teachers : $this->students;

        $person = collect($source)
            ->first(fn (array $item): bool => ((string) ($item['userId'] ?? '')) === $identifier);

        if (! is_array($person)) {
            return null;
        }

        $email = is_string($person['email'] ?? null) ? trim((string) $person['email']) : null;

        return filled($email) && str($email)->contains('@') ? $email : null;
    }

    protected function isNotFoundError(\Throwable $error): bool
    {
        if ((int) $error->getCode() === 404) {
            return true;
        }

        $message = strtolower($error->getMessage());

        return str_contains($message, 'not found') || str_contains($message, 'notfound');
    }

    protected function isPermissionDeniedError(\Throwable $error): bool
    {
        if ((int) $error->getCode() === 403) {
            return true;
        }

        $message = strtolower($error->getMessage());

        return str_contains($message, 'permission_denied') || str_contains($message, 'forbidden');
    }

    protected function isUnenrollPermissionError(\Throwable $error): bool
    {
        $message = strtolower($error->getMessage());

        return str_contains($message, 'usercannotunenrollfromcourse')
            || str_contains($message, 'cannot unenroll from the course');
    }

    protected function formatGoogleApiError(\Throwable $error): string
    {
        $message = trim($error->getMessage());
        $decoded = json_decode($message, true);

        if (is_array($decoded)) {
            $apiMessage = (string) data_get($decoded, 'error.message', 'Unknown Google API error');
            $status = (string) data_get($decoded, 'error.status', '');

            return filled($status) ? ($apiMessage . ' [' . $status . ']') : $apiMessage;
        }

        return filled($message) ? $message : 'Unknown error';
    }

    protected function removeStudentWithPrivilegedSubjects(string $identifier): bool
    {
        if (blank($this->courseId)) {
            return false;
        }

        $subjects = collect([
            is_string($this->course['ownerEmail'] ?? null) ? trim((string) $this->course['ownerEmail']) : null,
            ...collect($this->teachers)
                ->map(fn (array $teacher): ?string => is_string($teacher['email'] ?? null) ? trim((string) $teacher['email']) : null)
                ->all(),
        ])
            ->filter(fn (?string $email): bool => filled($email) && str($email)->contains('@'))
            ->unique()
            ->values();

        foreach ($subjects as $subjectEmail) {
            try {
                $classroom = (new GoogleService((string) $subjectEmail))->classroomService();
                $classroom->courses_students->delete($this->courseId, $identifier);

                return true;
            } catch (\Throwable $error) {
                if ($this->isNotFoundError($error)) {
                    return true;
                }

                continue;
            }
        }

        return false;
    }

    protected function resolveRosterEmail(string $userId, ?string $emailFromProfile): string
    {
        $emailFromProfile = is_string($emailFromProfile) ? trim($emailFromProfile) : null;

        if (filled($emailFromProfile) && str($emailFromProfile)->contains('@')) {
            return $emailFromProfile;
        }

        if (array_key_exists($userId, $this->resolvedEmailsByUserId)) {
            return $this->resolvedEmailsByUserId[$userId];
        }

        $email = null;

        if (str($userId)->contains('@')) {
            $email = GSuiteUser::query()
                ->where('email', $userId)
                ->value('email') ?? $userId;
        }

        if (blank($email)) {
            $email = $this->resolveEmailFromDirectory($userId);
        }

        $resolved = filled($email) ? (string) $email : $userId;
        $this->resolvedEmailsByUserId[$userId] = $resolved;

        return $resolved;
    }

    protected function resolveEmailFromDirectory(string $userId): ?string
    {
        try {
            $directory = $this->directoryService();

            if (! $directory) {
                return null;
            }

            $user = $directory->users->get($userId, [
                'projection' => 'BASIC',
            ]);

            return $user->getPrimaryEmail();
        } catch (\Throwable) {
            return null;
        }
    }

    protected function directoryService(): ?\Google\Service\Directory
    {
        if ($this->directoryServiceInstance) {
            return $this->directoryServiceInstance;
        }

        try {
            $service = new GoogleService();
            $this->directoryServiceInstance = $service->directoryService();

            return $this->directoryServiceInstance;
        } catch (\Throwable) {
            return null;
        }
    }
}

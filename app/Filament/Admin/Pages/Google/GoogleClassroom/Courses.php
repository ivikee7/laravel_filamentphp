<?php

namespace App\Filament\Admin\Pages\Google\GoogleClassroom;

use App\Models\GSuiteUser;
use App\Services\GoogleService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Resources\Concerns\HasTabs;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

class Courses extends Page implements HasTable
{
    use HasTabs;
    use InteractsWithTable;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-academic-cap';
    protected static string | \UnitEnum | null $navigationGroup = 'Google';
    protected static ?string $navigationLabel = 'Courses';

    protected static ?string $slug = 'google/classroom/courses';

    public array $courses = [];

    /**
     * @var array<string, string>
     */
    protected array $resolvedOwnersByUserId = [];

    protected ?\Google\Service\Directory $directoryServiceInstance = null;

    public function mount(): void
    {
        $this->loadCourses();
        $this->loadDefaultActiveTab();
    }

    public function content(Schema $schema): Schema
    {
        return $schema->schema([
            $this->getTabsContentComponent(),
            EmbeddedTable::make(),
        ]);
    }

    public function getTabs(): array
    {
        return [
            'active' => Tab::make('Active')
                ->badge(fn (): int => $this->countCoursesByState('ACTIVE'))
                ->badgeColor('success'),
            'archived' => Tab::make('Archived')
                ->badge(fn (): int => $this->countCoursesByState('ARCHIVED'))
                ->badgeColor('warning'),
            'provisioned' => Tab::make('Provisioned')
                ->badge(fn (): int => $this->countCoursesByState('PROVISIONED'))
                ->badgeColor('gray'),
            'declined' => Tab::make('Declined')
                ->badge(fn (): int => $this->countCoursesByState('DECLINED'))
                ->badgeColor('danger'),
            'all' => Tab::make('All')
                ->badge(fn (): int => count($this->courses)),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createCourse')
                ->label('Create Course')
                ->icon('heroicon-m-plus')
                ->color('primary')
                ->schema([
                    TextInput::make('name')
                        ->label('Course name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('section')
                        ->label('Section')
                        ->maxLength(255),
                    TextInput::make('descriptionHeading')
                        ->label('Description heading')
                        ->maxLength(255),
                    Textarea::make('description')
                        ->label('Description')
                        ->rows(4),
                    TextInput::make('room')
                        ->label('Room')
                        ->maxLength(255),
                    Select::make('courseState')
                        ->label('State')
                        ->options([
                            'ACTIVE' => 'Active',
                            'ARCHIVED' => 'Archived',
                            'PROVISIONED' => 'Provisioned',
                            'DECLINED' => 'Declined',
                        ])
                        ->default('ACTIVE')
                        ->required(),
                    TextInput::make('ownerEmail')
                        ->label('Primary teacher / owner email (optional)')
                        ->email()
                        ->maxLength(255)
                        ->helperText('If owner transfer is not allowed, the user will still be added as a teacher.'),
                ])
                ->modalHeading('Create Course')
                ->modalSubmitActionLabel('Create')
                ->action(function (array $data): void {
                    try {
                        $service = new GoogleService();
                        $classroom = $service->classroomService();

                        $course = new \Google\Service\Classroom\Course([
                            'name' => trim((string) ($data['name'] ?? '')),
                            'section' => filled($data['section'] ?? null) ? $data['section'] : null,
                            'descriptionHeading' => filled($data['descriptionHeading'] ?? null) ? trim((string) $data['descriptionHeading']) : null,
                            'description' => filled($data['description'] ?? null) ? trim((string) $data['description']) : null,
                            'room' => filled($data['room'] ?? null) ? trim((string) $data['room']) : null,
                            'courseState' => (string) ($data['courseState'] ?? 'ACTIVE'),
                        ]);

                        $created = $classroom->courses->create($course);

                        $ownerWarning = $this->assignOwnerOrTeacher(
                            classroom: $classroom,
                            courseId: (string) $created->getId(),
                            ownerEmail: trim((string) ($data['ownerEmail'] ?? '')),
                        );

                        $this->loadCourses();

                        Notification::make()
                            ->title('Course created')
                            ->success()
                            ->body('ID: ' . $created->getId())
                            ->send();

                        if (filled($ownerWarning)) {
                            Notification::make()
                                ->title('Owner update note')
                                ->warning()
                                ->body($ownerWarning)
                                ->send();
                        }
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Create course failed')
                            ->danger()
                            ->body($e->getMessage())
                            ->send();
                    }
                }),
        ];
    }

    public function table(Table $table): Table
    {
        $activeState = match ($this->activeTab) {
            'active' => 'ACTIVE',
            'archived' => 'ARCHIVED',
            'provisioned' => 'PROVISIONED',
            'declined' => 'DECLINED',
            default => null,
        };

        return $table
            ->records(fn (): array => collect($this->courses)
                ->filter(fn (array $course): bool => $activeState === null || (($course['courseState'] ?? null) === $activeState))
                ->map(fn (array $course): array => [
                    'key' => (string) ($course['id'] ?? ''),
                    'id' => (string) ($course['id'] ?? ''),
                    'name' => (string) ($course['name'] ?? 'Untitled course'),
                    'owner' => (string) ($course['owner'] ?? '-'),
                    'ownerId' => (string) ($course['ownerId'] ?? ''),
                    'section' => (string) ($course['section'] ?? ''),
                    'descriptionHeading' => (string) ($course['descriptionHeading'] ?? ''),
                    'description' => (string) ($course['description'] ?? ''),
                    'room' => (string) ($course['room'] ?? ''),
                    'courseState' => (string) ($course['courseState'] ?? ''),
                ])
                ->values()
                ->all())
            ->columns([
                TextColumn::make('name')->label('Course')->searchable(),
                TextColumn::make('owner')->label('Owner')->searchable()->placeholder('-'),
                TextColumn::make('section')->placeholder('-'),
                TextColumn::make('courseState')->label('State')->badge()->toggleable(),
                TextColumn::make('ownerId')->label('Owner ID')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('id')->label('Course ID')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordAction('openCourse')
            ->recordActionsPosition(RecordActionsPosition::AfterColumns)
            ->recordActionsColumnLabel('Actions')
            ->recordActions([
                ActionGroup::make([
                    Action::make('openCourse')
                        ->label('Open')
                        ->icon('heroicon-m-arrow-right')
                        ->color('primary')
                        ->action(function (array $record): void {
                            $this->redirect(Course::getUrl(['courseId' => (string) ($record['id'] ?? '')]));
                        }),
                    Action::make('editCourse')
                        ->label('Edit')
                        ->icon('heroicon-m-pencil-square')
                        ->color('gray')
                        ->schema([
                            TextInput::make('name')
                                ->label('Course name')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('section')
                                ->label('Section')
                                ->maxLength(255),
                            TextInput::make('descriptionHeading')
                                ->label('Description heading')
                                ->maxLength(255),
                            Textarea::make('description')
                                ->label('Description')
                                ->rows(4),
                            TextInput::make('room')
                                ->label('Room')
                                ->maxLength(255),
                                Select::make('courseState')
                                    ->label('State')
                                    ->options([
                                        'ACTIVE' => 'Active',
                                        'ARCHIVED' => 'Archived',
                                        'PROVISIONED' => 'Provisioned',
                                        'DECLINED' => 'Declined',
                                    ])
                                    ->required(),
                            TextInput::make('ownerEmail')
                                ->label('Primary teacher / owner email')
                                ->email()
                                ->maxLength(255)
                                ->helperText('Optional. If provided, system will try owner transfer. If transfer is not allowed, it will still add this user as a teacher.'),
                        ])
                        ->fillForm(fn (array $record): array => [
                            'name' => (string) ($record['name'] ?? ''),
                            'section' => (string) ($record['section'] ?? ''),
                            'descriptionHeading' => (string) ($record['descriptionHeading'] ?? ''),
                            'description' => (string) ($record['description'] ?? ''),
                            'room' => (string) ($record['room'] ?? ''),
                                'courseState' => (string) ($record['courseState'] ?? 'ACTIVE'),
                            'ownerEmail' => str((string) ($record['owner'] ?? ''))->contains('@') ? (string) ($record['owner'] ?? '') : '',
                        ])
                        ->modalHeading('Edit Course')
                        ->modalSubmitActionLabel('Save')
                        ->action(fn (array $record, array $data): bool => $this->updateCourse((string) ($record['id'] ?? ''), $data)),
                    Action::make('archiveCourse')
                        ->label('Archive')
                        ->icon('heroicon-m-archive-box')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(fn (array $record): bool => $this->setCourseState((string) ($record['id'] ?? ''), 'ARCHIVED')),
                    Action::make('activateCourse')
                        ->label('Activate')
                        ->icon('heroicon-m-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn (array $record): bool => $this->setCourseState((string) ($record['id'] ?? ''), 'ACTIVE')),
                    Action::make('deleteCourse')
                        ->label('Delete')
                        ->icon('heroicon-m-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn (array $record): bool => $this->deleteCourse((string) ($record['id'] ?? ''))),
                ])
                    ->label('Actions')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->color('gray'),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->paginated(false)
            ->emptyStateHeading('No courses found')
            ->emptyStateDescription('No courses found in the selected tab.')
            ->emptyStateIcon('heroicon-o-academic-cap');
    }


    protected function loadCourses(): void
    {
        try {
            $service = new GoogleService();
            $classroom = $service->classroomService();

            $pageToken = null;
            $courses = [];

            do {
                $params = ['pageSize' => 100];

                if ($pageToken) {
                    $params['pageToken'] = $pageToken;
                }

                $res = $classroom->courses->listCourses($params);

                foreach ($res->getCourses() ?? [] as $course) {
                    $ownerId = (string) ($course->getOwnerId() ?? '');

                    $courses[] = [
                        'id' => $course->getId(),
                        'name' => $course->getName(),
                        'owner' => $this->resolveCourseOwner($ownerId),
                        'ownerId' => $ownerId,
                        'section' => $course->getSection(),
                        'descriptionHeading' => $course->getDescriptionHeading(),
                        'description' => $course->getDescription(),
                        'room' => $course->getRoom(),
                        'courseState' => $course->getCourseState(),
                    ];
                }

                $pageToken = $res->getNextPageToken();
            } while ($pageToken);

            $this->courses = $courses;
            $this->flushCachedTableRecords();
        } catch (\Exception $e) {
            Notification::make()->title('Failed to load courses')->danger()->body($e->getMessage())->send();
        }
    }

    protected function setCourseState(string $courseId, string $state): bool
    {
        try {
            $service = new GoogleService();
            $classroom = $service->classroomService();

            $course = new \Google\Service\Classroom\Course([
                'courseState' => $state,
            ]);

            $classroom->courses->patch($courseId, $course, [
                'updateMask' => 'courseState',
            ]);

            $this->loadCourses();
            Notification::make()->title('Course updated')->success()->send();

            return true;
        } catch (\Exception $e) {
            Notification::make()->title('Course update failed')->danger()->body($e->getMessage())->send();

            return false;
        }
    }

    protected function deleteCourse(string $courseId): bool
    {
        try {
            $service = new GoogleService();
            $classroom = $service->classroomService();

            $classroom->courses->delete($courseId);
            $this->loadCourses();

            Notification::make()->title('Course deleted')->success()->send();

            return true;
        } catch (\Exception $e) {
            Notification::make()->title('Delete failed')->danger()->body($e->getMessage())->send();

            return false;
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function updateCourse(string $courseId, array $data): bool
    {
        if (blank($courseId)) {
            Notification::make()->title('Invalid course')->danger()->send();

            return false;
        }

        try {
            $service = new GoogleService();
            $classroom = $service->classroomService();

            $course = new \Google\Service\Classroom\Course([
                'name' => trim((string) ($data['name'] ?? '')),
                'section' => filled($data['section'] ?? null) ? trim((string) $data['section']) : null,
                'descriptionHeading' => filled($data['descriptionHeading'] ?? null) ? trim((string) $data['descriptionHeading']) : null,
                'description' => filled($data['description'] ?? null) ? trim((string) $data['description']) : null,
                'room' => filled($data['room'] ?? null) ? trim((string) $data['room']) : null,
                'courseState' => (string) ($data['courseState'] ?? 'ACTIVE'),
            ]);

            $classroom->courses->patch($courseId, $course, [
                'updateMask' => 'name,section,descriptionHeading,description,room,courseState',
            ]);

            $ownerEmail = trim((string) ($data['ownerEmail'] ?? ''));
            $ownerTransferWarning = $this->assignOwnerOrTeacher(
                classroom: $classroom,
                courseId: $courseId,
                ownerEmail: $ownerEmail,
            );

            $this->loadCourses();

            $notification = Notification::make()
                ->title('Course updated')
                ->success();

            if (filled($ownerTransferWarning)) {
                $notification
                    ->warning()
                    ->body($ownerTransferWarning);
            }

            $notification->send();

            return true;
        } catch (\Exception $e) {
            Notification::make()->title('Update failed')->danger()->body($e->getMessage())->send();

            return false;
        }
    }

    protected function resolveCourseOwner(string $ownerId): string
    {
        if (blank($ownerId)) {
            return '-';
        }

        if (array_key_exists($ownerId, $this->resolvedOwnersByUserId)) {
            return $this->resolvedOwnersByUserId[$ownerId];
        }

        if (str($ownerId)->contains('@')) {
            $resolved = GSuiteUser::query()->where('email', $ownerId)->value('email') ?? $ownerId;
            $this->resolvedOwnersByUserId[$ownerId] = $resolved;

            return $resolved;
        }

        $email = $this->resolveOwnerEmailFromDirectory($ownerId);
        $resolved = filled($email) ? (string) $email : $ownerId;
        $this->resolvedOwnersByUserId[$ownerId] = $resolved;

        return $resolved;
    }

    protected function resolveOwnerEmailFromDirectory(string $ownerId): ?string
    {
        try {
            $directory = $this->directoryService();

            if (! $directory) {
                return null;
            }

            $user = $directory->users->get($ownerId, [
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

    protected function countCoursesByState(string $state): int
    {
        return collect($this->courses)
            ->where('courseState', $state)
            ->count();
    }

    protected function assignOwnerOrTeacher(\Google\Service\Classroom $classroom, string $courseId, string $ownerEmail): ?string
    {
        if (blank($ownerEmail)) {
            return null;
        }

        try {
            $ownerCourse = new \Google\Service\Classroom\Course([
                'ownerId' => $ownerEmail,
            ]);

            $classroom->courses->patch($courseId, $ownerCourse, [
                'updateMask' => 'ownerId',
            ]);

            return null;
        } catch (\Throwable) {
            try {
                $classroom->courses_teachers->create($courseId, new \Google\Service\Classroom\Teacher([
                    'userId' => $ownerEmail,
                ]));

                return 'Owner transfer is not allowed for this account. User was added as teacher.';
            } catch (\Throwable $addTeacherError) {
                if ($this->isAlreadyExistsError($addTeacherError)) {
                    // User is already a teacher in this course, so treat this as success.
                    return null;
                }

                return 'Could not set owner/teacher: ' . $addTeacherError->getMessage();
            }
        }
    }

    protected function isAlreadyExistsError(\Throwable $error): bool
    {
        if ((int) $error->getCode() === 409) {
            return true;
        }

        $message = strtolower($error->getMessage());

        return str_contains($message, 'already exists')
            || str_contains($message, 'already_exists')
            || str_contains($message, 'requested entity already exists');
    }
}

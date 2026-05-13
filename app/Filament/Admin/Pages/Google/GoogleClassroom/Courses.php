<?php

namespace App\Filament\Admin\Pages\Google\GoogleClassroom;

use App\Services\GoogleService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class Courses extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-academic-cap';
    protected static string | \UnitEnum | null $navigationGroup = 'Google';
    protected static ?string $navigationLabel = 'Courses';

    protected static ?string $slug = 'google/classroom/courses';

    public array $courses = [];

    public function mount(): void
    {
        $this->loadCourses();
    }

    public function content(Schema $schema): Schema
    {
        return $schema->schema([
            EmbeddedTable::make(),
        ]);
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
                    TextInput::make('primary_teacher_email')
                        ->label('Primary teacher email (optional)')
                        ->email()
                        ->maxLength(255),
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
                        ]);

                        $created = $classroom->courses->create($course);

                        if (filled($data['primary_teacher_email'] ?? null)) {
                            $teacher = new \Google\Service\Classroom\Teacher([
                                'userId' => $data['primary_teacher_email'],
                            ]);
                            $classroom->courses_teachers->create($created->getId(), $teacher);
                        }

                        $this->loadCourses();

                        Notification::make()
                            ->title('Course created')
                            ->success()
                            ->body('ID: ' . $created->getId())
                            ->send();
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
        return $table
            ->records(fn (): array => collect($this->courses)
                ->map(fn (array $course): array => [
                    'key' => (string) ($course['id'] ?? ''),
                    'id' => (string) ($course['id'] ?? ''),
                    'name' => (string) ($course['name'] ?? 'Untitled course'),
                    'section' => (string) ($course['section'] ?? ''),
                    'courseState' => (string) ($course['courseState'] ?? ''),
                ])
                ->values()
                ->all())
            ->columns([
                TextColumn::make('name')->label('Course')->searchable(),
                TextColumn::make('section')->placeholder('-'),
                TextColumn::make('courseState')->label('State')->badge()->toggleable(),
                TextColumn::make('id')->label('Course ID')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordAction('openCourse')
            ->recordActions([
                Action::make('openCourse')
                    ->label('Open')
                    ->icon('heroicon-m-arrow-right')
                    ->color('primary')
                    ->action(function (array $record): void {
                        $this->redirect(Course::getUrl(['courseId' => (string) ($record['id'] ?? '')]));
                    }),
                ActionGroup::make([
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
            ->emptyStateDescription('Create your first course using the Create Course button.')
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
                    $courses[] = [
                        'id' => $course->getId(),
                        'name' => $course->getName(),
                        'section' => $course->getSection(),
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
}

<?php

namespace App\Filament\Admin\Resources\Transport\TransportAssignments\Schemas;

use App\Models\TransportStoppage;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class TransportAssignmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('User')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->getSearchResultsUsing(function (?string $search) {
                        // Filament may call this with null when `preload()` is enabled,
                        // so accept a nullable string and normalize to an empty string.
                        $search = trim($search ?? '');

                        // `with()` expects each relation as a separate array entry. A previous
                        // comma-included string caused Eloquent to look for a relation named
                        // "gSuiteUser, student" which doesn't exist on the User model.
                        $query = User::query()->with([
                            'gSuiteUser',
                            'student.classAssignment.class',
                            'student.classAssignment.section',
                        ]);

                        if ($search === '') {
                            // preload: return recent active users (limit 50)
                            $results = (clone $query)->where('is_active', 1)->orderBy('name')->limit(50)->get();
                        } else {
                            $words = preg_split('/\s+/', $search);

                            foreach ($words as $word) {
                                $w = "%{$word}%";
                                $query->where(function (Builder $q) use ($w, $word) {
                                        // Use ORs between searchable fields. Previously `whereRelation(...)`
                                        // and `where('name', ...)` were chained which required all those
                                        // conditions to be true (AND) and prevented matching by name.
                                        $q->where('id', 'like', $w)
                                                ->orWhereRelation('gSuiteUser', 'email', 'like', $w)
                                                ->orWhere('name', 'like', $w)
                                                ->orWhere('father_name', 'like', $w)
                                                ->orWhere('mother_name', 'like', $w)
                                                ->orWhereHas('student.classAssignment.class', function (Builder $q2) use ($w) {
                                                    $q2->where('name', 'like', $w);
                                                })
                                                ->orWhereHas('student.classAssignment.section', function (Builder $q2) use ($w) {
                                                    $q2->where('name', 'like', $w);
                                                });

                                    // support searching active/inactive words against is_active boolean
                                    $lower = strtolower($word);
                                    if (in_array($lower, ['active', 'inactive', '1', '0'], true)) {
                                        $val = ($lower === 'active' || $lower === '1') ? 1 : 0;
                                        $q->orWhere('is_active', $val);
                                    }
                                });
                            }

                            $results = $query->limit(50)->get();
                        }

                        return $results->mapWithKeys(function (User $u) {
                            $className = $u->student?->classAssignment?->class?->name ?? '';
                            $sectionName = $u->student?->classAssignment?->section?->name ?? '';
                            $status = $u->is_active ? 'Active' : 'Inactive';
                            $labelParts = array_filter([
                                $u->name,
                                'Father: ' . ($u->father_name ?? '-'),
                                'Mother: ' . ($u->mother_name ?? '-'),
                                trim(($className . ' ' . $sectionName)) ?: null,
                                $status,
                            ]);
                            $label = implode(' — ', $labelParts);
                            return [$u->id => $label];
                        })->toArray();
                    })
                    ->getOptionLabelUsing(function (?int $value) {
                        if (! $value) return null;
                        $u = User::with(['student.classAssignment.class','student.classAssignment.section'])->find($value);
                        if (! $u) return null;
                        $className = $u->student?->classAssignment?->class?->name ?? '';
                        $sectionName = $u->student?->classAssignment?->section?->name ?? '';
                        $status = $u->is_active ? 'Active' : 'Inactive';
                        $labelParts = array_filter([
                            $u->name,
                            'Father: ' . ($u->father_name ?? '-'),
                            'Mother: ' . ($u->mother_name ?? '-'),
                            trim(($className . ' ' . $sectionName)) ?: null,
                            $status,
                        ]);
                        return implode(' — ', $labelParts);
                    }),

                Select::make('transport_route_id')
                    ->relationship('transportRoute', 'name')
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->preload()
                    ->afterStateUpdated(fn ($state, $set) => $set('transport_stoppage_id', null)),
                Select::make('transport_stoppage_id')
                    ->label('Stoppage')
                    ->options(function ($get) {
                        $routeId = $get('transport_route_id');
                        if (! $routeId) return [];

                        return TransportStoppage::where('transport_route_id', $routeId)
                            ->orderBy('order')
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->searchable()
                    ->required()
                    ->preload()
                    ->reactive()
                    ->default(null),
                TextInput::make('contact_number')->nullable(),
                DatePicker::make('date_of_join')->nullable(),
                TextInput::make('remarks')->nullable(),
            ]);
    }
}

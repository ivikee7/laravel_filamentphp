<?php

namespace App\Filament\Admin\Resources\ActivityLogs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ActivityLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Activity Details')
                    ->schema([
                        Group::make()
                            ->schema([
                                TextEntry::make('id')->label('ID'),
                                TextEntry::make('description')->label('Description'),
                            ])->columns(2),

                        Group::make()
                            ->schema([
                                TextEntry::make('log_name')->label('Log name'),
                                TextEntry::make('created_at')->label('Created At')->dateTime(),
                            ])->columns(2),

                        Group::make()
                            ->schema([
                                RepeatableEntry::make('properties_comparison')
                                    ->label('Comparison')
                                    ->hiddenLabel()
                                    ->table([
                                        RepeatableEntry\TableColumn::make('Key'),
                                        RepeatableEntry\TableColumn::make('Old'),
                                        RepeatableEntry\TableColumn::make('New'),
                                        RepeatableEntry\TableColumn::make('Changed'),
                                    ])
                                    ->schema([
                                        TextEntry::make('Key')->hiddenLabel(),
                                        TextEntry::make('Old')->hiddenLabel(),
                                        TextEntry::make('New')->hiddenLabel(),
                                        TextEntry::make('Changed')->hiddenLabel(),
                                    ])
                                    ->getStateUsing(function ($record) {
                                        $props = $record->properties ?? null;
                                        $old = $props['old'] ?? null;
                                        $attributes = $props['attributes'] ?? null;

                                        try {
                                            $flatOld = $old ? ActivityLogInfolist::flattenProperties($old) : [];
                                            $flatNew = $attributes ? ActivityLogInfolist::flattenProperties($attributes) : [];

                                            $allKeys = array_unique(array_merge(array_keys($flatOld), array_keys($flatNew)));
                                            sort($allKeys);

                                            $rows = [];
                                            foreach ($allKeys as $k) {
                                                $left = array_key_exists($k, $flatOld) ? $flatOld[$k] : 'null';
                                                $right = array_key_exists($k, $flatNew) ? $flatNew[$k] : 'null';
                                                $changed = ($left !== $right) ? 'yes' : '';
                                                $rows[] = ['Key' => $k, 'Old' => $left, 'New' => $right, 'Changed' => $changed];
                                            }

                                            // Place changed rows first for easier scanning
                                            usort($rows, function ($a, $b) {
                                                if ($a['Changed'] === $b['Changed']) {
                                                    return strcmp($a['Key'], $b['Key']);
                                                }
                                                return ($a['Changed'] === 'yes') ? -1 : 1;
                                            });

                                            return $rows;
                                        } catch (\Throwable $e) {
                                            return [];
                                        }
                                    }),
                            ])->columns(1)->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Causer')
                    ->schema([
                        TextEntry::make('causer.name')->label('Name')->placeholder('-'),
                        TextEntry::make('causer_type')->label('Causer Type'),
                        TextEntry::make('causer_id')->label('Causer ID'),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),

                Section::make('Subject')
                    ->schema([
                        TextEntry::make('subject_type')->label('Subject Type'),
                        TextEntry::make('subject_id')->label('Subject ID'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Meta')
                    ->schema([
                        TextEntry::make('properties->ip')->label('IP Address'),
                        TextEntry::make('properties->user_agent')->label('User Agent')->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Flatten nested arrays/objects into a single-level associative array
     * where keys are the dot-notated path and values are stringified.
     *
     * @param mixed $data
     * @param string $prefix
     * @return array
     */
    private static function flattenProperties($data, string $prefix = ''): array
    {
        $result = [];

        if (is_object($data)) {
            $data = (array) $data;
        }

        if (!is_array($data)) {
            return [$prefix ?: 'value' => (string) $data];
        }

        foreach ($data as $key => $value) {
            $composedKey = $prefix === '' ? (string) $key : $prefix . '.' . $key;

            if (is_array($value) || is_object($value)) {
                $child = self::flattenProperties($value, $composedKey);
                $result = array_merge($result, $child);
            } else {
                if (is_bool($value)) {
                    $value = $value ? 'true' : 'false';
                } elseif ($value === null) {
                    $value = 'null';
                }

                $result[$composedKey] = (string) $value;
            }
        }

        return $result;
    }
}

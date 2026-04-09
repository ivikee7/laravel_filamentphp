<?php

namespace App\Filament\Admin\Resources\ActivityLogs\Pages;

use App\Filament\Admin\Resources\ActivityLogs\ActivityLogResource;
use Filament\Resources\Pages\ViewRecord;

class ViewActivityLog extends ViewRecord
{
    protected static string $resource = ActivityLogResource::class;

    // Optionally add header actions (none for logs) or customize the view

    /**
     * Return comparison rows for the current record for use in the blade view.
     * Each row: ['key' => string, 'old' => mixed, 'new' => mixed, 'changed' => bool]
     */
    public function comparisonRows(): array
    {
        $properties = $this->record->properties ?? [];
        $attributes = $properties['attributes'] ?? [];
        $old = $properties['old'] ?? [];

        $flatten = function ($array, $prefix = '') use (&$flatten) {
            $result = [];
            if (!is_array($array) && !is_object($array)) {
                return $prefix === '' ? [] : [$prefix => $array];
            }
            if (is_object($array)) $array = (array) $array;
            foreach ($array as $k => $v) {
                $key = $prefix === '' ? $k : $prefix . '.' . $k;
                if (is_array($v) || is_object($v)) {
                    $result = array_merge($result, $flatten($v, $key));
                } else {
                    $result[$key] = $v;
                }
            }
            return $result;
        };

        $flatNew = $flatten($attributes);
        $flatOld = $flatten($old);

        $allKeys = array_unique(array_merge(array_keys($flatNew), array_keys($flatOld)));
        sort($allKeys);

        $rows = [];
        foreach ($allKeys as $key) {
            $left = array_key_exists($key, $flatOld) ? $flatOld[$key] : null;
            $right = array_key_exists($key, $flatNew) ? $flatNew[$key] : null;
            $changed = ($left !== $right);
            $rows[] = ['key' => $key, 'old' => $left, 'new' => $right, 'changed' => $changed];
        }

        usort($rows, fn($a, $b) => ($b['changed'] <=> $a['changed']) ?: strcmp($a['key'], $b['key']));

        return $rows;
    }
}


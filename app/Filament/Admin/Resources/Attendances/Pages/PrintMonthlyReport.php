<?php

namespace App\Filament\Admin\Resources\Attendances\Pages;

use App\Filament\Admin\Resources\Attendances\AttendanceResource;
use App\Models\User;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PrintMonthlyReport extends Page
{
    protected static string $resource = AttendanceResource::class;

    protected string $view = 'filament.admin.resources.attendances.pages.print-monthly-report';

    protected static string $layout = 'layouts.print';

    public $getData;
    public $print_data;
    public array $printColumns = [];
    public array $printColumnKeys = [];
    public array $printRecords = [];
    public ?string $start_date = null;
    public ?string $end_date = null;

    public function mount(Request $request): void
    {
        // Prefer query params (printRecords opens URL with query params)
        $userIds = $request->query('user_ids');
        $from = $request->query('from_date') ?: null;
        $to = $request->query('to_date') ?: null;

        if ($userIds) {
            // Build print data from query
            $ids = is_array($userIds) ? $userIds : explode(',', $userIds);
            $users = User::whereIn('id', $ids)
                ->with(['roles', 'attendances' => function ($q) use ($from, $to) {
                    if ($from) $q->whereDate('created_at', '>=', $from);
                    if ($to) $q->whereDate('created_at', '<=', $to);
                }])->get();

            // Build columns: id, name, role, then date columns between from/to
            $this->start_date = $from;
            $this->end_date = $to;

            $this->printColumns = ['ID', 'Name', 'Role'];
            $dates = [];
            if ($from && $to) {
                $start = Carbon::parse($from)->startOfDay();
                $end = Carbon::parse($to)->endOfDay();
                while ($start <= $end) {
                    $this->printColumns[] = $start->format('d-m-Y');
                    $dates[] = $start->toDateString();
                    $start->addDay();
                }
            }

            // Build records
            foreach ($users as $user) {
                $row = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->roles->pluck('name')->join(', '),
                ];
                foreach ($dates as $date) {
                    $att = $user->attendances->filter(fn($a) => Carbon::parse($a->created_at)->toDateString() === $date)->sortBy('created_at');
                    if ($att->isEmpty()) {
                        $row[$date] = '-';
                    } else {
                        $row[$date] = $att->first()->created_at->format('H:i') . '\n' . $att->last()->created_at->format('H:i');
                    }
                }
                $this->printRecords[] = $row;
            }

            return;
        }

        // Fallback: load from session
        $getData = Session::get('print_data');
        if ($getData && is_array($getData)) {
            // Expecting array with keys: start_date, end_date, columns, column_keys, records
            $data = $getData;
            $this->start_date = $data['start_date'] ?? null;
            $this->end_date = $data['end_date'] ?? null;
            $this->printColumns = $data['columns'] ?? [];
            $this->printColumnKeys = $data['column_keys'] ?? [];
            $this->printRecords = $data['records'] ?? [];
        }
    }
}

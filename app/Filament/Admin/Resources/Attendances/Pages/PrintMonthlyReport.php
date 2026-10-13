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
        $userIds = $request->query('user_ids');
        $from = $request->query('from_date') ?: null;
        $to = $request->query('to_date') ?: null;

        if ($userIds) {
            $ids = is_array($userIds) ? $userIds : explode(',', $userIds);
            $users = User::whereIn('id', $ids)
                ->with(['roles', 'attendances' => function ($q) use ($from, $to) {
                    if ($from) $q->whereDate('created_at', '>=', $from);
                    if ($to) $q->whereDate('created_at', '<=', $to);
                }])->get();

            $this->start_date = $from ? Carbon::parse($from)->format('d-m-Y') : null;
            $this->end_date = $to ? Carbon::parse($to)->format('d-m-Y') : null;

            // Define columns and corresponding row keys explicitly
            $this->printColumns = ['ID', 'Name', 'Role', 'Class', 'Section'];
            $this->printColumnKeys = ['id', 'name', 'role', 'class', 'section'];

            $dates = [];
            if ($from && $to) {
                $start = Carbon::parse($from)->startOfDay();
                $end = Carbon::parse($to)->endOfDay();
                while ($start <= $end) {
                    $this->printColumns[] = $start->format('d-m');
                    $dates[] = $start->toDateString();
                    $this->printColumnKeys[] = $start->toDateString();
                    $start->addDay();
                }
            }

            foreach ($users as $user) {
                $row = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->roles->pluck('name')->join(', '),
                    'class' => data_get($user, 'student.classAssignment.class.name', '-'),
                    'section' => data_get($user, 'student.classAssignment.section.name', '-'),
                ];
                foreach ($dates as $date) {
                    $att = $user->attendances->filter(fn($a) => Carbon::parse($a->created_at)->toDateString() === $date)->sortBy('created_at');
                    if ($att->isEmpty()) {
                        $row[$date] = '-';
                    } else {
                        $row[$date] = $att->first()->created_at->format('H:i') . "\n" . $att->last()->created_at->format('H:i');
                    }
                }
                $this->printRecords[] = $row;
            }

            return;
        }

        // Fallback: load from session[cite: 40]
        $getData = Session::get('print_data');
        if ($getData && is_array($getData)) {
            $this->start_date = $getData['start_date'] ?? null;
            $this->end_date = $getData['end_date'] ?? null;
            $this->printColumns = $getData['columns'] ?? [];
            $this->printColumnKeys = $getData['column_keys'] ?? [];
            $this->printRecords = $getData['records'] ?? [];
        }
    }
}

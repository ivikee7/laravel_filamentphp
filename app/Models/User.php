<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Althinect\FilamentSpatieRolesPermissions\Concerns\HasSuperAdmin;
use App\Models\Admission;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasSuperAdmin, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'father_name',
        'mother_name',
        'primary_contact_number',
        'secondary_contact_number',
        'address',
        'city',
        'state',
        'pin_code',
        'avatar',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->creator_id = auth()->id();
            }
        });
        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updater_id = auth()->id();
            }
        });
    }

    function admission(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    protected function formattedAttendance(): Attribute
    {
        return Attribute::make(
            get: function () {
                $attendanceData = [];
                $selectedMonth = request()->query('month', now()->month);
                $selectedYear = request()->query('year', now()->year);
                $daysInMonth = Carbon::create($selectedYear, $selectedMonth)->daysInMonth;

                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $date = Carbon::create($selectedYear, $selectedMonth, $day)->toDateString();

                    $records = $this->attendances->whereBetween('created_at', [
                        "{$date} 00:00:00",
                        "{$date} 23:59:59"
                    ]);

                    $entries = [
                        // 'entredInBus' => [],
                        'entredInCampus' => [],
                        'exitFromCampus' => [],
                        // 'exitFromBus' => [],
                    ];

                    foreach ($records as $record) {
                        $time = Carbon::parse($record->created_at)->format('H:i');
                        if (isset($entries[$record->type])) {
                            $entries[$record->type][] = $time;
                        }
                    }

                    $attendanceData[$date] = '';
                    // if (!empty($entries['entredInBus'])) {
                    //     $attendanceData[$date] .= '🚌 In Bus: ' . implode(', ', $entries['entredInBus']) . '<br>';
                    // }
                    if (!empty($entries['entredInCampus'])) {
                        $attendanceData[$date] .= '' . implode(', ', $entries['entredInCampus']) . '<br>';
                    }
                    if (!empty($entries['exitFromCampus'])) {
                        $attendanceData[$date] .= '' . implode(', ', $entries['exitFromCampus']) . '<br>';
                    }
                    // if (!empty($entries['exitFromBus'])) {
                    //     $attendanceData[$date] .= '🚍 Exit Bus: ' . implode(', ', $entries['exitFromBus']);
                    // }
                }

                return $attendanceData;
            }
        );
    }
}

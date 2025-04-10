<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Althinect\FilamentSpatieRolesPermissions\Concerns\HasSuperAdmin;
use App\Models\Admission;
use Carbon\Carbon;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
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
        'official_email',
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
        'blood_group',
        'created_at',
        'updated_at',
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

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->where('is_active', true)->exists();
        // Example: Allow users with the "admin" role
        // return $this->hasRole(['Super Admin', 'Owner', 'Admin', 'Teacher']); // Replace 'admin' with your role
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

    function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function currentStudent(): HasOne
    {
        return $this->hasOne(Student::class)->latestOfMany();
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function financialDetails()
    {
        return $this->hasOne(UserFinancialDetail::class);
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

    public function getCurrentClassSectionAttribute(): ?string
    {
        // Only for students
        if (!$this->hasRole('Student')) {
            return null;
        }

        $student = $this->students()->latest()->first();

        if (!$student || !$student->currentClassAssignment) {
            return null;
        }

        $class = optional($student->currentClassAssignment->class)->name;
        $section = optional($student->currentClassAssignment->section)->name;

        return $class && $section ? "$class / $section" : null;
    }

    public function getCurrentClassAttribute()
    {
        if (!$this->hasRole('Student')) {
            return null;
        }

        $student = $this->students()->latest()->first();

        if (!$student) return null;

        $class = optional($student->currentClassAssignment)->class?->name;

        return $class ? "$class" : null;
    }

    public function getCurrentSectionAttribute()
    {
        if (!$this->hasRole('Student')) {
            return null;
        }

        $student = $this->students()->latest()->first();

        if (!$student) return null;

        $section = optional($student->currentClassAssignment)->section?->name;

        return $section ? "$section" : null;
    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Althinect\FilamentSpatieRolesPermissions\Concerns\HasSuperAdmin;
use App\Models\Admission;
use Carbon\Carbon;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar
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
        'date_of_birth',
        'official_email',
        'date_of_birth',
        'aadhaar_number',
        'place_of_birth',
        'mother_tongue',
        'notes',
        'termination_date',
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
        'blood_group_id',
        'gender_id',
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

    public function getFilamentAvatarUrl(): ?string
    {
        if ($this->avatar) {
            return Storage::url($this->avatar);
        }

        return null;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
            }
        });
        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
        static::deleting(function ($model) {
            if (Auth::check()) {
                $model->deleted_by = Auth::id();
                $model->saveQuietly();
            }
        });
        static::restoring(function ($model) {
            $model->deleted_by = null;
            $model->saveQuietly();
        });
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }

    public function bloodGroup(): BelongsTo
    {
        return $this->belongsTo(BloodGroup::class, 'blood_group_id');
    }

    public function gSuiteUser(): HasOne
    {
        return $this->hasOne(GSuiteUser::class, 'user_id');
    }


    function admission(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function currentStudent()
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


    public function products(): HasMany
    {
        $className = $this->student?->currentClassAssignment?->class?->className;

        return $className
            ? $className->products()
            : (new Product)->newQuery()->whereRaw('1 = 0');
    }

    public function cart(): HasMany
    {
        return $this->hasMany(Cart::class, 'user_id');
    }

    public function invoices():HasMany{
        return $this->hasMany(Invoice::class, 'user_id');
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

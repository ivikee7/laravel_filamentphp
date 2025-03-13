<?php

namespace App\Models\School;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdmissionClass extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'creator_id',
        'updater_id',
    ];

    // relationship
    function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updater_id');
    }
    function admissions(): HasMany
    {
        return $this->hasMany(Admission::class);
    }
}

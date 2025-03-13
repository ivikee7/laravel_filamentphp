<?php

namespace App\Models\School;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'user_id',
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
    function session(): BelongsTo
    {
        return $this->belongsTo(AcadamicSession::class, 'acadamic_session_id');
    }
    function class(): BelongsTo
    {
        return $this->belongsTo(AdmissionClass::class, 'class_id');
    }
    function section(): BelongsTo
    {
        return $this->belongsTo(AdmissionSection::class, 'section_id');
    }
    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

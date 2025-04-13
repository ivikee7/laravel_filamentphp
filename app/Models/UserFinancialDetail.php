<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class UserFinancialDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'basic_salary',
        'house_allowance',
        'transport_allowance',
        'other_allowances',
        'tax_deduction',
        'loan_deduction',
        'bank_name',
        'account_number',
        'ifsc_code',
        'provident_fund_contribution',
        'pf_account_number',
        'esi_number',
        'notes',
        'creator_id',
        'updater_id',
    ];

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

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

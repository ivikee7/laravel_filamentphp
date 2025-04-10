<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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

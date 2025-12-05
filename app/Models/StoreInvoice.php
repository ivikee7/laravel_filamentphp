<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class StoreInvoice extends Model
{
    use softDeletes;

    protected $fillable = [
        'user_id',
        'store_id',
        'subtotal_amount',
        'discount_amount',
        'total_amount',
        'remarks',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function storeProducts(): HasMany
    {
        return $this->hasMany(StoreProduct::class, 'store_invoice_id');
    }

    public function storeInvoiceItems(): hasMany
    {
        return $this->hasMany(StoreInvoiceItem::class, 'store_invoice_id');
    }


    public function storeInvoiceTransactions(): HasMany
    {
        return $this->hasMany(StoreInvoiceTransaction::class, 'store_invoice_id');
    }

    protected function totalPaidAmount(): Attribute
    {
        return Attribute::get(function () {
            return $this->storeInvoiceTransactions()->sum('amount');
        });
    }

    protected function totalDueAmount(): Attribute
    {
        return Attribute::get(function () {
            $subtotalAmount = $this->subtotal_amount ?? 0; // Good practice to handle nulls

            // FIX: Access as a property (snake_case), do not call as a function ()
            $totalPaidAmount = $this->total_paid_amount;

            $discountAmount = $this->discount_amount ?? 0;

            return $subtotalAmount - $totalPaidAmount - $discountAmount;
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Invoice extends Model
{
    use SoftDeletes;

    protected $guarded = []; // Adjust as needed
    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'sub_total' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function invoiceable(): MorphTo
    {
        return $this->morphTo();
    }


    protected $dates = ['deleted_at'];

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

        // Auto invoice number
        static::creating(function ($invoice) {
            // Only generate if not already set
            if (empty($invoice->invoice_number)) {
                $lastInvoice = static::orderBy('id', 'desc')->first();
                $lastNumber = $lastInvoice ? intval(substr($lastInvoice->invoice_number, -5)) : 0;
                $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
                $invoice->invoice_number = 'INV-' . now()->format('Ymd') . '-' . $newNumber;
            }
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



    public function payments()
    {
        return $this->hasMany(InvoicePayment::class);
    }

    public function totalPaid(): float
    {
        return $this->payments()->where('status', 'completed')->sum('amount');
    }

    public function balanceDue(): float
    {
        return $this->total - $this->totalPaid();
    }
}

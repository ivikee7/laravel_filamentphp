<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Store extends Model
{
    use softDeletes;

    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'pin_code',
        'phone',
        'email',
        'is_active',
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

    public function storeInvoices(): HasMany
    {
        return $this->hasMany(StoreInvoice::class, 'store_id');
    }

    public function storeProducts(): HasMany
    {
        return $this->hasMany(StoreProduct::class, 'store_id');
    }

    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(
            Student::class,
            Product::class,
            'store_id',           // Foreign key on the products table...
            'id',                 // Local key on the students table...
            'id',                 // Local key on the stores table...
            'class_id'            // Foreign key on the products table...
        )->whereHas('classAssignment.class.studentClassAssignments.class.className.products', function ($query) {
            $query->where('store_id', $this->id);
        });
    }

    public function storeIntransactionCounters(): HasManyThrough
    {
        return $this->hasManyThrough(
            StoreInvoiceTransaction::class,  // Related model
            StoreInvoice::class,     // Through model
            'store_id',              // Foreign key on through model
            'store_invoice_id',   // Foreign key on related model
            'id',                   // Local key on countries
            'id'              // Local key on users
        );
    }

    public function counters(): HasMany
    {
        return $this->hasMany(User::class, 'created_by');
    }
}

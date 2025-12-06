<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class StoreCart extends Model
{

    protected $fillable = [
        'user_id',
        'store_product_id',
        'quantity',
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
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function storeProduct(): BelongsTo
    {
        return $this->belongsTo(StoreProduct::class);
    }

    public function getProductTotalAttribute(): float
    {
        $price = $this->storeProduct->price ?? 0;
        $quantity = $this->quantity ?? 0;
        return (float)$price * (int)$quantity;
    }

    public function getGrandTotalAttribute($store_id, $user_id, $academicYear_id): float
    {
        $productTotal = self::query()->with('storeProduct')
            ->withWhereRelation('storeProduct', 'store_id', $store_id)
            ->withWhereRelation('storeProduct', 'academic_year_id', $academicYear_id)
            ->withWhereRelation('storeProduct', 'store_id', $store_id);
        $productTotalPrice = [];
        foreach ($productTotal->get() as $product) {
            $price = $product->storeProduct->price ?? 0;
            $quantity = $product->storeProduct->quantity ?? 0;
            $productTotalPrice = (float)$price * (int)$quantity;
        }
        return dd($productTotalPrice);
    }
}

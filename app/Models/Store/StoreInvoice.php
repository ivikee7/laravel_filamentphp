<?php

namespace App\Models\Store;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreInvoice extends Model
{
    use SoftDeletes;

    protected $fillable = [];

    // relationship
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updater_id', 'id');
    }
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}

<?php

namespace App\Models\Store;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreInvoiceItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
    ];

    // relationship
    public function creatorName(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
    public function updatorName(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updater_id', 'id');
    }
}

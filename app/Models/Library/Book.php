<?php

namespace App\Models\Library;

use App\Models\Classes;
use App\Models\Language;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Book extends Model
{
    use SoftDeletes;

    protected $table = 'library_books';

    protected $fillable = [
        'title',
        'edition',
        'price',
        'pages',
        'isbn_number',
        'purchased_at',
        'published_at',
        'notes',
        'author_id',
        'publisher_id',
        'category_id',
        'location_id',
        'language_id',
        'class_id',
        'subject_id',
        'supplier_id',
        'accession_number',
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

    public function author(): BelongsTo
    {
        return $this->belongsTo(BookAuthor::class);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(BookPublisher::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BookCategory::class);
    }
    public function location(): BelongsTo
    {
        return $this->belongsTo(BookLocation::class);
    }
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class);
    }
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(BookSupplier::class);
    }

    public function borrows()
    {
        return $this->hasMany(BookBorrow::class);
    }
    public function scopeOnlyAvailable($query)
    {
        return $query->whereDoesntHave('borrows', function ($q) {
            $q->whereNull('received_at');
            $q->whereNull('received_by');
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use SoftDeletes, \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'title',
        'author',
        'publisher',
        'year',
        'isbn',
        'cover_image',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(BookItem::class);
    }

    // Hitung stok tersedia
    public function getAvailableStockAttribute(): int
    {
        return $this->items()->where('status', 'available')->count();
    }
}
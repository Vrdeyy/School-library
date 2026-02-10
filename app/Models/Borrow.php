<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Borrow extends Model
{
    protected $fillable = [
        'user_id',
        'book_item_id',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
        'approved_by',
        'notes',
    ];

    protected $casts = [
        'borrow_date' => 'datetime',
        'due_date' => 'datetime',
        'return_date' => 'datetime',
    ];



    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bookItem(): BelongsTo
    {
        return $this->belongsTo(BookItem::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scope: pending approval
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope: approved (sedang dipinjam)
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Scope: active (belum benar-benar dikembalikan/selesai)
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'approved', 'returning']);
    }

    // Cek apakah sudah lewat due date
    public function getIsOverdueAttribute(): bool
    {
        if (!in_array($this->status, ['approved', 'returning']) || !$this->due_date) {
            return false;
        }
        
        return $this->due_date->isPast();
    }
}

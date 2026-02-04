<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BookItem extends Model
{
    use SoftDeletes;

    use HasFactory;
    
    protected $fillable = [
        'book_id',
        'code',
        'qr_signature',
        'status',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function borrows(): HasMany
    {
        return $this->hasMany(Borrow::class);
    }

    // Borrow yang aktif (bukan returned/rejected)
    public function activeBorrow(): HasOne
    {
        return $this->hasOne(Borrow::class)
            ->whereIn('status', ['pending', 'approved']);
    }

    // Scope: hanya yang available
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    // Generate QR Signature (HMAC)
    public function generateQrSignature(): string
    {
        $secret = config('app.qr_secret', config('app.key'));
        $signature = hash_hmac('sha256', $this->id . $this->code, $secret);
        
        $this->update(['qr_signature' => $signature]);
        
        return $signature;
    }

    // Verify QR Signature
    public static function verifyQrSignature(string $payload): ?self
    {
        // Format: id:code:signature
        $parts = explode(':', $payload);
        if (count($parts) !== 3) {
            return null;
        }

        [$id, $code, $signature] = $parts;
        
        $secret = config('app.qr_secret', config('app.key'));
        $expectedSignature = hash_hmac('sha256', $id . $code, $secret);

        if (!hash_equals($expectedSignature, $signature)) {
            return null;
        }

        return self::where('id', $id)->where('code', $code)->first();
    }

    // Get QR Payload untuk di-encode
    public function getQrPayloadAttribute(): string
    {
        if (!$this->qr_signature) {
            $this->generateQrSignature();
        }
        
        return "{$this->id}:{$this->code}:{$this->qr_signature}";
    }
}
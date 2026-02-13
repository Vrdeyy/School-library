<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'kelas',
        'jurusan',
        'angkatan',
        'id_pengenal_siswa',
        'pin',
        'is_suspended',
        'borrow_limit',
        'qr_signature',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'pin', 
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_suspended' => 'boolean',
            'borrow_limit' => 'integer',
        ];
    }

    /**
     * Get all borrows for the user.
     */
    public function borrows(): HasMany
    {
        return $this->hasMany(Borrow::class);
    }

    /**
     * Get currently active borrows (pending or approved).
     */
    public function activeBorrows(): HasMany
    {
        return $this->hasMany(Borrow::class)->active();
    }

    /**
     * Check if the user is eligible to borrow books.
     */
    public function canBorrow(): bool
    {
        if ($this->is_suspended) {
            return false;
        }

        return $this->activeBorrows()->count() < $this->borrow_limit;
    }

    /**
     * Check if the user is an administrator.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Determine if the user can access the Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }

    /**
     * Generate QR Signature (HMAC) untuk User.
     */
    public function generateQrSignature(): string
    {
        $secret = config('app.qr_secret', config('app.key'));
        $signature = hash_hmac('sha256', $this->id . $this->id_pengenal_siswa, $secret);
        
        $this->update(['qr_signature' => $signature]);
        
        return $signature;
    }

    /**
     * Verify QR Signature dan return User jika valid.
    public static function verifyQrSignature(string $payload): ?self
    {
        // Try signed format first: id:id_pengenal_siswa:signature
        $parts = explode(':', $payload);
        if (count($parts) === 3) {
            [$id, $id_pengenal_siswa, $signature] = $parts;
            
            // Only proceed if signature component is not empty
            if (!empty($signature)) {
                $secret = config('app.qr_secret', config('app.key'));
                $expectedSignature = hash_hmac('sha256', $id . $id_pengenal_siswa, $secret);
 
                if (hash_equals($expectedSignature, $signature)) {
                    return self::where('id', $id)->where('id_pengenal_siswa', $id_pengenal_siswa)->first();
                }
            }
        }
 
        // Search by exact ID Pengenal Siswa FIRST to avoid ID collision
        $user = self::where('id_pengenal_siswa', $payload)->first();
        if ($user) return $user;

        // Last fallback: search by numeric ID if strictly numeric
        if (is_numeric($payload)) {
            return self::find($payload);
        }

        return null;
    }


    /**
     * Get QR Payload untuk di-encode ke QR Code.
     */
    public function getQrPayloadAttribute(): string
    {
        if (!$this->qr_signature) {
            $this->generateQrSignature();
        }
        
        return "{$this->id}:{$this->id_pengenal_siswa}:{$this->qr_signature}";
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class QrCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'uri',
        'user_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($qrCode) {
            if (empty($qrCode->uuid)) {
                $qrCode->uuid = Str::uuid();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all scans for this QR code.
     */
    public function scans(): HasMany
    {
        return $this->hasMany(QrScan::class);
    }

    /**
     * Get the total number of scans for this QR code.
     */
    public function getScanCountAttribute(): int
    {
        return $this->scans()->count();
    }

    /**
     * Get the scan count for today.
     */
    public function getTodayScanCountAttribute(): int
    {
        return $this->scans()
            ->whereDate('scanned_at', now())
            ->count();
    }

    /**
     * Get the scan count for the last in front validate  days.
     */
    public function getCountScanByDays(int $days): int
    {
        is_numeric($days) || $days = 7;

        return $this->scans()
            ->where('scanned_at', '>=', now()->subDays($days))
            ->count();
    }
}

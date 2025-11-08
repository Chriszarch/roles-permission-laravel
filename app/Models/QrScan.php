<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrScan extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'scanned_at' => 'datetime',
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'qr_code_id',
        'ip_address',
        'user_agent',
        'referer',
        'scanned_at',
    ];

    /**
     * Get the QR code that was scanned.
     */
    public function qrCode()
    {
        return $this->belongsTo(QrCode::class);
    }
}

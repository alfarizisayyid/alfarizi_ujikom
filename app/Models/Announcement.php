<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'created_by',
        'audience',
        'published_at',
        'is_active',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Get the user who created the announcement.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Check if announcement is published.
     */
    public function isPublished(): bool
    {
        return $this->published_at !== null && $this->published_at <= now();
    }

    /**
     * Get the target audience label.
     */
    public function getAudienceLabel(): string
    {
        return match ($this->audience) {
            'all' => 'Semua Pengguna',
            'registered' => 'Pendaftar Terdaftar',
            'accepted' => 'Pendaftar Diterima',
            'rejected' => 'Pendaftar Ditolak',
            'schedule_participants' => 'Peserta Jadwal Seleksi',
            default => 'Unknown',
        };
    }
}

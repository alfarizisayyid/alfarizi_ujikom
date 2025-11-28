<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'birth_date',
        'gender',
        'phone',
        'email',
        'address',
        'city',
        'province',
        'postal_code',
        'ktp_number',
        'ktp_expiry',
        'education_level',
        'institution',
        'graduation_year',
        'status',
        'rejection_reason',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'ktp_expiry' => 'date',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the user who owns the registration.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the documents for the registration.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(RegistrationDocument::class);
    }

    /**
     * Get the schedules this registration is participating in.
     */
    public function schedules()
    {
        return $this->belongsToMany(SelectionSchedule::class, 'schedule_participants', 'registration_id', 'schedule_id');
    }

    /**
     * Get the notifications for this registration.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the reviewer user.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Check if registration is pending review.
     */
    public function isPendingReview(): bool
    {
        return $this->status === 'pending_review';
    }

    /**
     * Check if registration is accepted.
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if registration is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}

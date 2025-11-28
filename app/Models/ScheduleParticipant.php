<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'registration_id',
        'status',
        'notes',
    ];

    /**
     * Get the schedule for the participant.
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(SelectionSchedule::class);
    }

    /**
     * Get the registration for the participant.
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Get the user for the participant.
     */
    public function user()
    {
        return $this->registration->user();
    }

    /**
     * Check if participant attended.
     */
    public function attended(): bool
    {
        return $this->status === 'attended';
    }
}

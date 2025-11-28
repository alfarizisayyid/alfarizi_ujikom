<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SelectionSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'stage',
        'schedule_date',
        'start_time',
        'end_time',
        'location',
        'capacity',
        'notes',
        'status',
    ];

    protected $casts = [
        'schedule_date' => 'date',
    ];

    /**
     * Get the registrations participating in this schedule.
     */
    public function registrations(): BelongsToMany
    {
        return $this->belongsToMany(
            Registration::class,
            'schedule_participants',
            'schedule_id',
            'registration_id'
        )->withPivot('status', 'notes', 'created_at', 'updated_at');
    }

    /**
     * Get the schedule participants.
     */
    public function participants()
    {
        return $this->hasMany(ScheduleParticipant::class, 'schedule_id');
    }

    /**
     * Get the number of scheduled participants.
     */
    public function getParticipantCount(): int
    {
        return $this->participants()
            ->where('status', '!=', 'absent')
            ->where('status', '!=', 'postponed')
            ->count();
    }

    /**
     * Check if schedule is full.
     */
    public function isFull(): bool
    {
        if (!$this->capacity) {
            return false;
        }

        return $this->getParticipantCount() >= $this->capacity;
    }

    /**
     * Check if schedule is ongoing.
     */
    public function isOngoing(): bool
    {
        return $this->status === 'ongoing';
    }

    /**
     * Check if schedule is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}

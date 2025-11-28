<?php

/**
 * Helper Functions untuk Sistem Pendaftaran Polisi
 */

/**
 * Get status badge class
 */
function getStatusBadgeClass($status)
{
    return match ($status) {
        'draft' => 'bg-secondary',
        'submitted', 'pending_review' => 'bg-warning',
        'accepted' => 'bg-success',
        'rejected' => 'bg-danger',
        default => 'bg-info',
    };
}

/**
 * Get status label
 */
function getStatusLabel($status)
{
    return ucfirst(str_replace('_', ' ', $status));
}

/**
 * Format file size
 */
function formatFileSize($bytes)
{
    if ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    }
    return $bytes . ' B';
}

/**
 * Get education level label
 */
function getEducationLabel($level)
{
    return config('polisi.education_levels.' . $level, $level);
}

/**
 * Get selection stage label
 */
function getStageLabel($stage)
{
    return config('polisi.selection_stages.' . $stage, $stage);
}

/**
 * Check if user is admin
 */
function isAdmin()
{
    return auth()->check() && auth()->user()->isAdmin();
}

/**
 * Check if user is regular user
 */
function isUser()
{
    return auth()->check() && auth()->user()->isUser();
}

/**
 * Get notification count for user
 */
function getUnreadNotificationCount()
{
    if (!auth()->check()) {
        return 0;
    }
    return auth()->user()->notifications()
        ->where('is_read', false)
        ->count();
}

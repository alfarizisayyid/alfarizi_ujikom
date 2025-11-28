<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Notification;
use App\Models\Registration;
use App\Models\SelectionSchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        $stats = [
            'total_registrations' => Registration::count(),
            'pending_review' => Registration::where('status', 'pending_review')->count(),
            'accepted' => Registration::where('status', 'accepted')->count(),
            'rejected' => Registration::where('status', 'rejected')->count(),
            'upcoming_schedules' => SelectionSchedule::where('schedule_date', '>=', now())->count(),
        ];

        $recentRegistrations = Registration::latest('submitted_at')
            ->whereNotNull('submitted_at')
            ->limit(10)
            ->get();

        $upcomingSchedules = SelectionSchedule::where('schedule_date', '>=', now())
            ->orderBy('schedule_date')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentRegistrations', 'upcomingSchedules'));
    }
}

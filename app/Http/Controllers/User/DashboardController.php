<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index(): View
    {
        $user = Auth::user();
        $registration = $user->registration;
        $unreadNotifications = $user->notifications()
            ->where('is_read', false)
            ->count();

        return view('user.dashboard', compact('registration', 'unreadNotifications'));
    }
}

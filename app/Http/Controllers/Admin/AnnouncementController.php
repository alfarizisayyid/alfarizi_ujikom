<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Notification;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    /**
     * Display list of all announcements.
     */
    public function index(): View
    {
        $announcements = Announcement::latest()->paginate(15);
        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create(): View
    {
        return view('admin.announcements.create');
    }

    /**
     * Store a newly created announcement.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'audience' => 'required|in:all,registered,accepted,rejected,schedule_participants',
            'publish_immediately' => 'boolean',
            'published_at' => 'nullable|date_format:Y-m-d H:i',
        ]);

        $publishedAt = null;
        if ($request->boolean('publish_immediately') || empty($validated['published_at'])) {
            $publishedAt = now();
        } else {
            $publishedAt = $validated['published_at'];
        }

        $announcement = Announcement::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'audience' => $validated['audience'],
            'created_by' => Auth::user()->id,
            'published_at' => $publishedAt,
            'is_active' => true,
        ]);

        // Send notifications based on audience
        $this->sendNotifications($announcement);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dibuat.');
    }

    /**
     * Show announcement details.
     */
    public function show(Announcement $announcement): View
    {
        return view('admin.announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing an announcement.
     */
    public function edit(Announcement $announcement): View
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    /**
     * Update the announcement.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'audience' => 'required|in:all,registered,accepted,rejected,schedule_participants',
            'is_active' => 'boolean',
        ]);

        $announcement->update($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    /**
     * Delete an announcement.
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }

    /**
     * Send notifications based on announcement audience.
     */
    private function sendNotifications(Announcement $announcement)
    {
        $query = Registration::query();

        switch ($announcement->audience) {
            case 'registered':
                $query->where('status', '!=', 'draft');
                break;
            case 'accepted':
                $query->where('status', 'accepted');
                break;
            case 'rejected':
                $query->where('status', 'rejected');
                break;
            case 'schedule_participants':
                $query->whereHas('schedules');
                break;
            // 'all' - no filter needed
        }

        $registrations = $query->get();

        foreach ($registrations as $registration) {
            Notification::create([
                'user_id' => $registration->user_id,
                'registration_id' => $registration->id,
                'title' => $announcement->title,
                'message' => $announcement->content,
                'type' => 'announcement',
            ]);
        }
    }
}

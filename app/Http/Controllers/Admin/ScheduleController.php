<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SelectionSchedule;
use App\Models\ScheduleParticipant;
use App\Models\Notification;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    /**
     * Display list of all schedules.
     */
    public function index(): View
    {
        $schedules = SelectionSchedule::latest('schedule_date')->paginate(15);
        return view('admin.schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new schedule.
     */
    public function create(): View
    {
        return view('admin.schedules.create');
    }

    /**
     * Store a newly created schedule.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stage' => 'required|in:interview,physical_test,psychological_test,medical_test,final_selection',
            'schedule_date' => 'required|date|after:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $schedule = SelectionSchedule::create($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal seleksi berhasil dibuat.');
    }

    /**
     * Show schedule details.
     */
    public function show(SelectionSchedule $schedule): View
    {
        $schedule->load('participants');
        $participants = $schedule->participants()
            ->with('registration.user')
            ->paginate(20);

        return view('admin.schedules.show', compact('schedule', 'participants'));
    }

    /**
     * Show the form for editing a schedule.
     */
    public function edit(SelectionSchedule $schedule): View
    {
        return view('admin.schedules.edit', compact('schedule'));
    }

    /**
     * Update the schedule.
     */
    public function update(Request $request, SelectionSchedule $schedule)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stage' => 'required|in:interview,physical_test,psychological_test,medical_test,final_selection',
            'schedule_date' => 'required|date|after:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
            'status' => 'required|in:planned,ongoing,completed,cancelled',
        ]);

        $schedule->update($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal seleksi berhasil diperbarui.');
    }

    /**
     * Add participants to a schedule.
     */
    public function addParticipants(Request $request, SelectionSchedule $schedule)
    {
        $validated = $request->validate([
            'registration_ids' => 'required|array',
            'registration_ids.*' => 'exists:registrations,id',
        ]);

        foreach ($validated['registration_ids'] as $registrationId) {
            // Check if already added
            $exists = $schedule->participants()
                ->where('registration_id', $registrationId)
                ->exists();

            if (!$exists) {
                ScheduleParticipant::create([
                    'schedule_id' => $schedule->id,
                    'registration_id' => $registrationId,
                    'status' => 'scheduled',
                ]);

                // Send notification
                $registration = Registration::find($registrationId);
                Notification::create([
                    'user_id' => $registration->user_id,
                    'registration_id' => $registrationId,
                    'title' => 'Jadwal Seleksi',
                    'message' => 'Anda telah dijadwalkan untuk tahap seleksi: ' . $schedule->title . ' pada ' . $schedule->schedule_date->format('d-m-Y H:i'),
                    'type' => 'schedule',
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Peserta berhasil ditambahkan.']);
    }

    /**
     * Update participant status.
     */
    public function updateParticipantStatus(Request $request, ScheduleParticipant $participant)
    {
        $validated = $request->validate([
            'status' => 'required|in:scheduled,attended,absent,postponed',
            'notes' => 'nullable|string',
        ]);

        $participant->update($validated);

        return response()->json(['success' => true, 'message' => 'Status peserta berhasil diperbarui.']);
    }

    /**
     * Delete a schedule.
     */
    public function destroy(SelectionSchedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal seleksi berhasil dihapus.');
    }
}

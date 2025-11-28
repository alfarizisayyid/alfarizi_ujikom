<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    /**
     * Display list of all registrations.
     */
    public function index(): View
    {
        $registrations = Registration::with('user')
            ->latest('submitted_at')
            ->paginate(15);

        $statusFilter = request('status');
        if ($statusFilter) {
            $registrations = Registration::where('status', $statusFilter)
                ->with('user')
                ->latest('submitted_at')
                ->paginate(15);
        }

        return view('admin.registrations.index', compact('registrations'));
    }

    /**
     * Show registration details.
     */
    public function show(Registration $registration): View
    {
        $registration->load('user', 'documents', 'reviewer');
        return view('admin.registrations.show', compact('registration'));
    }

    /**
     * Show the form for verifying a registration.
     */
    public function verify(Registration $registration): View
    {
        $registration->load('user', 'documents');
        return view('admin.registrations.verify', compact('registration'));
    }

    /**
     * Accept a registration.
     */
    public function accept(Request $request, Registration $registration)
    {
        $registration->status = 'accepted';
        $registration->reviewed_at = now();
        $registration->reviewed_by = Auth::user()->id;
        $registration->save();

        // Send notification to user
        Notification::create([
            'user_id' => $registration->user_id,
            'registration_id' => $registration->id,
            'title' => 'Pendaftaran Diterima',
            'message' => 'Selamat! Pendaftaran Anda telah diterima. Silakan tunggu jadwal seleksi berikutnya.',
            'type' => 'acceptance',
        ]);

        return redirect()->route('admin.registrations.index')
            ->with('success', 'Pendaftaran diterima dan notifikasi telah dikirim.');
    }

    /**
     * Reject a registration.
     */
    public function reject(Request $request, Registration $registration)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $registration->status = 'rejected';
        $registration->rejection_reason = $request->rejection_reason;
        $registration->reviewed_at = now();
        $registration->reviewed_by = Auth::user()->id;
        $registration->save();

        // Send notification to user
        Notification::create([
            'user_id' => $registration->user_id,
            'registration_id' => $registration->id,
            'title' => 'Pendaftaran Ditolak',
            'message' => 'Mohon maaf, pendaftaran Anda ditolak. Alasan: ' . $request->rejection_reason,
            'type' => 'rejection',
        ]);

        return redirect()->route('admin.registrations.index')
            ->with('success', 'Pendaftaran ditolak dan notifikasi telah dikirim.');
    }

    /**
     * Update document verification status.
     */
    public function updateDocumentStatus(Request $request)
    {
        $validated = $request->validate([
            'document_id' => 'required|exists:registration_documents,id',
            'status' => 'required|in:verified,rejected',
            'notes' => 'nullable|string|max:500',
        ]);

        $document = \App\Models\RegistrationDocument::findOrFail($validated['document_id']);
        $document->verification_status = $validated['status'];
        $document->verification_notes = $validated['notes'];
        $document->verified_at = now();
        $document->verified_by = Auth::user()->id;
        $document->save();

        return response()->json([
            'success' => true,
            'message' => 'Status dokumen berhasil diperbarui.',
        ]);
    }

    /**
     * Download a document.
     */
    public function downloadDocument(\App\Models\RegistrationDocument $document)
    {
        return \Illuminate\Support\Facades\Storage::disk('private')
            ->download($document->file_path, $document->original_filename);
    }
}

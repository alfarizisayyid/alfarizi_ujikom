<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\RegistrationDocument;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    /**
     * Display the registration form.
     */
    public function create(): View
    {
        $registration = Auth::user()->registration;
        return view('user.registration.form', compact('registration'));
    }

    /**
     * Store or update the registration form.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'ktp_number' => 'required|string|max:20|unique:registrations,ktp_number,' . (Auth::user()->registration?->id ?? 'NULL'),
            'ktp_expiry' => 'required|date|after:today',
            'education_level' => 'required|string|max:100',
            'institution' => 'required|string|max:255',
            'graduation_year' => 'required|digits:4',
        ]);

        $registration = Auth::user()->registration ?? new Registration();
        $registration->user_id = Auth::user()->id;
        $registration->fill($validated);
        $registration->save();

        return redirect()->route('user.registration.documents', $registration)
            ->with('success', 'Data pribadi berhasil disimpan. Silakan unggah dokumen yang diperlukan.');
    }

    /**
     * Display the documents upload form.
     */
    public function documentsForm(): View|RedirectResponse
    {
        $registration = Auth::user()->registration;
        if (!$registration) {
            return redirect()->route('user.registration.create')
                ->with('error', 'Silakan isi data pribadi terlebih dahulu.');
        }

        $documents = $registration->documents;
        $requiredDocuments = ['ktp', 'ijazah', 'foto'];

        return view('user.registration.documents', compact('registration', 'documents', 'requiredDocuments'));
    }

    /**
     * Upload document.
     */
    public function uploadDocument(Request $request)
    {
        $registration = Auth::user()->registration;
        if (!$registration) {
            return response()->json(['error' => 'Registration not found'], 404);
        }

        $validated = $request->validate([
            'document_type' => 'required|string|max:50',
            'file' => 'required|file|max:5120|mimes:pdf,jpg,jpeg,png,doc,docx', // Max 5MB
        ]);

        $file = $request->file('file');
        $documentType = $validated['document_type'];

        // Check if document already exists
        $existingDoc = $registration->documents()
            ->where('document_type', $documentType)
            ->first();

        // Delete old file if exists
        if ($existingDoc) {
            Storage::disk('private')->delete($existingDoc->file_path);
            $existingDoc->delete();
        }

        // Store file in private storage
        $path = $file->store("registrations/{$registration->id}/documents", 'private');

        // Save document record
        $document = new RegistrationDocument([
            'registration_id' => $registration->id,
            'document_type' => $documentType,
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'verification_status' => 'pending',
        ]);
        $document->save();

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil diunggah.',
            'document' => $document,
        ]);
    }

    /**
     * Delete document.
     */
    public function deleteDocument(RegistrationDocument $document)
    {
        if ($document->registration->user_id !== Auth::user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        Storage::disk('private')->delete($document->file_path);
        $document->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Submit registration.
     */
    public function submit(Request $request)
    {
        $registration = Auth::user()->registration;
        if (!$registration) {
            return redirect()->route('user.registration.create')
                ->with('error', 'Silakan isi data pribadi terlebih dahulu.');
        }

        // Check if all required documents are uploaded
        $requiredDocuments = ['ktp', 'ijazah', 'foto'];
        $uploadedTypes = $registration->documents->pluck('document_type')->toArray();
        $missingDocs = array_diff($requiredDocuments, $uploadedTypes);

        if (!empty($missingDocs)) {
            return back()->with('error', 'Silakan unggah semua dokumen yang diperlukan: ' . implode(', ', $missingDocs));
        }

        // Update registration status
        $registration->status = 'submitted';
        $registration->submitted_at = now();
        $registration->save();

        return redirect()->route('user.dashboard')
            ->with('success', 'Pendaftaran berhasil disubmit. Menunggu verifikasi admin.');
    }

    /**
     * Show registration status.
     */
    public function status(): View
    {
        $registration = Auth::user()->registration;
        $notifications = Auth::user()->notifications()->latest()->paginate(10);

        return view('user.registration.status', compact('registration', 'notifications'));
    }
}

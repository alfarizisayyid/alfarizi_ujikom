@extends('layouts.app')

@section('title', 'Verifikasi Pendaftar - Sistem Pendaftaran Polisi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 sidebar">
            <div class="p-3">
                <h5 class="text-white mb-4"><i class="bi bi-shield-check"></i> Admin Panel</h5>
                <nav class="nav flex-column">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    <a href="{{ route('admin.registrations.index') }}" class="nav-link active"><i class="bi bi-file-text"></i> Kelola Pendaftar</a>
                    <a href="{{ route('admin.schedules.index') }}" class="nav-link"><i class="bi bi-calendar"></i> Jadwal Seleksi</a>
                    <a href="{{ route('admin.announcements.index') }}" class="nav-link"><i class="bi bi-megaphone"></i> Pengumuman</a>
                </nav>
            </div>
        </div>

        <div class="col-md-9 main-content">
            <h1 class="h3 mb-4"><i class="bi bi-check-circle"></i> Verifikasi Pendaftar</h1>

            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header"><i class="bi bi-person"></i> {{ $registration->full_name }}</div>
                        <div class="card-body">
                            <h6>Data Pribadi:</h6>
                            <table class="table table-sm"><tr><th>Email</th><td>{{ $registration->user->email }}</td></tr><tr><th>Telepon</th><td>{{ $registration->phone }}</td></tr><tr><th>KTP</th><td>{{ $registration->ktp_number }}</td></tr></table>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header"><i class="bi bi-file-earmark"></i> Verifikasi Dokumen</div>
                        <div class="card-body">
                            @forelse($registration->documents as $doc)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h6>{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}</h6>
                                        <small class="text-muted d-block">{{ $doc->original_filename }}</small>
                                        <div class="mt-2">
                                            <button class="btn btn-sm btn-outline-primary" onclick="downloadDoc({{ $doc->id }})"><i class="bi bi-download"></i> Download</button>
                                            <span class="badge badge-status-{{ $doc->verification_status }} ms-2">{{ ucfirst($doc->verification_status) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-info">Tidak ada dokumen.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header"><i class="bi bi-action"></i> Aksi Verifikasi</div>
                        <div class="card-body">
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">Keputusan</label>
                                    <div class="btn-group d-flex" role="group">
                                        <input type="radio" name="decision" id="accept" value="accept" class="btn-check">
                                        <label class="btn btn-outline-success w-50" for="accept">Terima</label>
                                        <input type="radio" name="decision" id="reject" value="reject" class="btn-check">
                                        <label class="btn btn-outline-danger w-50" for="reject">Tolak</label>
                                    </div>
                                </div>

                                <div id="rejectReasonDiv" style="display:none;">
                                    <label for="rejectionReason" class="form-label">Alasan Penolakan</label>
                                    <textarea id="rejectionReason" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="mt-3">
                                    <button type="button" class="btn btn-success w-100" onclick="submitVerification()">
                                        <i class="bi bi-check-circle"></i> Simpan Verifikasi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('input[name="decision"]').forEach(el => {
    el.addEventListener('change', function() {
        document.getElementById('rejectReasonDiv').style.display = this.value === 'reject' ? 'block' : 'none';
    });
});

function downloadDoc(docId) {
    window.location.href = '/admin/documents/' + docId + '/download';
}

function submitVerification() {
    const decision = document.querySelector('input[name="decision"]:checked');
    if (!decision) {
        alert('Silakan pilih keputusan!');
        return;
    }

    if (decision.value === 'accept') {
        if (confirm('Verifikasi pendaftaran ini?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.registrations.accept", $registration) }}';
            form.innerHTML = '@csrf';
            document.body.appendChild(form);
            form.submit();
        }
    } else {
        const reason = document.getElementById('rejectionReason').value;
        if (!reason) {
            alert('Silakan isi alasan penolakan!');
            return;
        }
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.registrations.reject", $registration) }}';
        form.innerHTML = '@csrf<input type="hidden" name="rejection_reason" value="' + reason + '">';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection

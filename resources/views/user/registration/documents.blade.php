@extends('layouts.app')

@section('title', 'Upload Dokumen - Sistem Pendaftaran Polisi')

@section('styles')
<style>
    .document-upload-zone {
        border: 2px dashed #003366;
        border-radius: 0.5rem;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        background-color: #f8f9fa;
    }

    .document-upload-zone:hover,
    .document-upload-zone.dragover {
        background-color: #e7f3ff;
        border-color: #0066b3;
    }

    .document-item {
        padding: 1rem;
        border-left: 4px solid #003366;
        margin-bottom: 0.5rem;
    }

    .progress-bar-animated {
        animation: progress-bar-stripes 1s linear infinite;
    }
</style>
@endsection

@section('content')
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('user.dashboard') }}">
            <i class="bi bi-shield-check"></i> Polisi Pendaftaran
        </a>
        <div class="d-flex ms-auto">
            <span class="text-white">{{ Auth::user()->name }}</span>
        </div>
    </div>
</nav>

<div class="container mt-4 mb-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3">
                <i class="bi bi-file-earmark"></i> Upload Dokumen
            </h1>
            <small class="text-muted">Tahap 2: Upload Dokumen Persyaratan</small>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong><i class="bi bi-exclamation-circle"></i> Terjadi Kesalahan!</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="{{ route('user.registration.create') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-1-circle"></i> Data Pribadi
                </a>
                <a href="{{ route('user.registration.documents') }}" class="list-group-item list-group-item-action active">
                    <i class="bi bi-2-circle"></i> Dokumen
                </a>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <i class="bi bi-info-circle"></i> Dokumen Wajib
                </div>
                <div class="card-body small">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-check2"></i>
                            <strong>KTP</strong>
                            @if($documents->where('document_type', 'ktp')->first())
                                <span class="badge bg-success">✓</span>
                            @else
                                <span class="badge bg-warning">Belum</span>
                            @endif
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check2"></i>
                            <strong>Ijazah</strong>
                            @if($documents->where('document_type', 'ijazah')->first())
                                <span class="badge bg-success">✓</span>
                            @else
                                <span class="badge bg-warning">Belum</span>
                            @endif
                        </li>
                        <li>
                            <i class="bi bi-check2"></i>
                            <strong>Foto</strong>
                            @if($documents->where('document_type', 'foto')->first())
                                <span class="badge bg-success">✓</span>
                            @else
                                <span class="badge bg-warning">Belum</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-cloud-upload"></i> Upload Dokumen
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label for="document_type" class="form-label">Jenis Dokumen *</label>
                        <select class="form-select" id="document_type" required>
                            <option value="">-- Pilih Jenis Dokumen --</option>
                            <optgroup label="Wajib">
                                <option value="ktp">KTP (Kartu Tanda Penduduk)</option>
                                <option value="ijazah">Ijazah / Sertifikat Pendidikan</option>
                                <option value="foto">Foto (3x4, background putih)</option>
                            </optgroup>
                            <optgroup label="Tambahan">
                                <option value="surat_kesehatan">Surat Keterangan Kesehatan</option>
                                <option value="sertifikat_prestasi">Sertifikat Prestasi</option>
                                <option value="dokumen_lain">Dokumen Lain</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="document-upload-zone" id="uploadZone">
                        <i class="bi bi-cloud-upload" style="font-size: 2rem; color: #003366;"></i>
                        <p class="mt-2 mb-0">
                            <strong>Klik atau Seret File</strong><br>
                            <small class="text-muted">File maksimal 5MB (PDF, JPG, PNG, DOC, DOCX)</small>
                        </p>
                        <input type="file" id="fileInput" style="display: none;" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                    </div>

                    <div id="uploadProgress" class="mt-3" style="display: none;">
                        <div class="progress">
                            <div class="progress-bar progress-bar-animated" id="progressBar" role="progressbar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="bi bi-file-text"></i> Dokumen yang Diunggah
                </div>
                <div class="card-body">
                    @if($documents->count() > 0)
                        @foreach($documents as $doc)
                            <div class="document-item">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h6 class="mb-1">
                                            <i class="bi bi-file"></i>
                                            <strong>{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}</strong>
                                        </h6>
                                        <small class="text-muted">
                                            {{ $doc->original_filename }} • {{ number_format($doc->file_size / 1024, 2) }} KB
                                        </small>
                                        <br>
                                        <small>
                                            Status:
                                            @if($doc->verification_status === 'pending')
                                                <span class="badge bg-warning">Menunggu Verifikasi</span>
                                            @elseif($doc->verification_status === 'verified')
                                                <span class="badge bg-success">Terverifikasi</span>
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </small>
                                        @if($doc->verification_notes)
                                            <br>
                                            <small class="text-danger">
                                                Catatan: {{ $doc->verification_notes }}
                                            </small>
                                        @endif
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <button class="btn btn-sm btn-outline-primary" onclick="downloadDocument({{ $doc->id }})">
                                            <i class="bi bi-download"></i> Download
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteDocument({{ $doc->id }})">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle"></i> Belum ada dokumen yang diunggah.
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-4">
                @if($registration->status === 'draft' && $documents->count() >= 3)
                    <form method="POST" action="{{ route('user.registration.submit') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-send"></i> Submit Pendaftaran
                        </button>
                    </form>
                @elseif($documents->count() < 3)
                    <button class="btn btn-success btn-lg" disabled title="Upload minimal 3 dokumen wajib terlebih dahulu">
                        <i class="bi bi-send"></i> Submit Pendaftaran
                    </button>
                @else
                    <button class="btn btn-success btn-lg" disabled>
                        <i class="bi bi-check-circle"></i> Sudah Disubmit
                    </button>
                @endif

                <a href="{{ route('user.dashboard') }}" class="btn btn-secondary btn-lg">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="footer">
    <p>&copy; 2025 Sistem Pendaftaran Polisi Indonesia. Semua hak dilindungi.</p>
</div>

@endsection

@section('scripts')
<script>
    const uploadZone = document.getElementById('uploadZone');
    const fileInput = document.getElementById('fileInput');
    const documentTypeSelect = document.getElementById('document_type');
    const uploadProgress = document.getElementById('uploadProgress');
    const progressBar = document.getElementById('progressBar');

    // Click to upload
    uploadZone.addEventListener('click', () => {
        if (!documentTypeSelect.value) {
            alert('Silakan pilih jenis dokumen terlebih dahulu!');
            return;
        }
        fileInput.click();
    });

    // Drag and drop
    uploadZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadZone.classList.add('dragover');
    });

    uploadZone.addEventListener('dragleave', () => {
        uploadZone.classList.remove('dragover');
    });

    uploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('dragover');
        if (e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            uploadFile();
        }
    });

    // File selection
    fileInput.addEventListener('change', uploadFile);

    function uploadFile() {
        if (!fileInput.files.length || !documentTypeSelect.value) {
            alert('Silakan pilih file dan jenis dokumen!');
            return;
        }

        const formData = new FormData();
        formData.append('document_type', documentTypeSelect.value);
        formData.append('file', fileInput.files[0]);
        formData.append('_token', '{{ csrf_token() }}');

        uploadProgress.style.display = 'block';
        progressBar.style.width = '0%';

        fetch('{{ route("user.registration.upload") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            uploadProgress.style.display = 'none';
            if (data.success) {
                alert('Dokumen berhasil diunggah!');
                fileInput.value = '';
                documentTypeSelect.value = '';
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            uploadProgress.style.display = 'none';
            console.error('Error:', error);
            alert('Terjadi kesalahan saat upload');
        });
    }

    function deleteDocument(docId) {
        if (!confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
            return;
        }

        fetch('/registration/documents/' + docId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Dokumen berhasil dihapus!');
                location.reload();
            }
        });
    }

    function downloadDocument(docId) {
        window.location.href = '/admin/documents/' + docId + '/download';
    }
</script>
@endsection

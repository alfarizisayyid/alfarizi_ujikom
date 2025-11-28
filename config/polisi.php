<?php

/**
 * Konfigurasi Aplikasi Sistem Pendaftaran Polisi
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Dokumen
    |--------------------------------------------------------------------------
    */
    'documents' => [
        'max_size' => 5120, // KB
        'allowed_types' => ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'],
        'allowed_mimes' => ['application/pdf', 'image/jpeg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
        'required_types' => ['ktp', 'ijazah', 'foto'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Status Pendaftaran
    |--------------------------------------------------------------------------
    */
    'registration_status' => [
        'draft' => 'Draft',
        'submitted' => 'Disubmit',
        'pending_review' => 'Menunggu Review',
        'accepted' => 'Diterima',
        'rejected' => 'Ditolak',
    ],

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Tahap Seleksi
    |--------------------------------------------------------------------------
    */
    'selection_stages' => [
        'interview' => 'Wawancara',
        'physical_test' => 'Tes Fisik',
        'psychological_test' => 'Tes Psikologi',
        'medical_test' => 'Tes Medis',
        'final_selection' => 'Seleksi Final',
    ],

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Tipe Notifikasi
    |--------------------------------------------------------------------------
    */
    'notification_types' => [
        'status_update' => 'Update Status',
        'announcement' => 'Pengumuman',
        'schedule' => 'Jadwal Seleksi',
        'rejection' => 'Penolakan',
        'acceptance' => 'Penerimaan',
    ],

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Tingkat Pendidikan
    |--------------------------------------------------------------------------
    */
    'education_levels' => [
        'SMA' => 'SMA/Sederajat',
        'D1' => 'Diploma 1',
        'D2' => 'Diploma 2',
        'D3' => 'Diploma 3',
        'S1' => 'Sarjana',
        'S2' => 'Magister',
    ],

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Email
    |--------------------------------------------------------------------------
    */
    'mail' => [
        'from_name' => 'Sistem Pendaftaran Polisi',
        'send_notifications' => true,
    ],

];

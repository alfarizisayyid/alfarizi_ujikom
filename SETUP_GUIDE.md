# Sistem Pendaftaran Polisi - Laravel

Sistem web pendaftaran calon anggota Kepolisian Negara Republik Indonesia yang dibangun dengan Laravel 11, MySQL, dan Bootstrap 5.

## Fitur Utama

### Untuk Calon Pendaftar (User)
- ✅ Registrasi akun dan login
- ✅ Pengisian formulir pendaftaran lengkap (data pribadi, alamat, identitas, pendidikan)
- ✅ Upload dokumen persyaratan (KTP, ijazah, foto, dan dokumen lainnya)
- ✅ Tracking status verifikasi berkas real-time
- ✅ Melihat jadwal seleksi yang dijadwalkan
- ✅ Menerima notifikasi status (menunggu, diterima, ditolak)

### Untuk Admin
- ✅ Dashboard dengan statistik lengkap
- ✅ Kelola data pendaftar (lihat, verifikasi, tolak)
- ✅ Verifikasi dokumen individual dengan catatan
- ✅ Manajemen jadwal seleksi (buat, edit, hapus)
- ✅ Tambah peserta ke jadwal seleksi
- ✅ Buat dan kirim pengumuman ke grup pendaftar
- ✅ Sistem notifikasi otomatis

## Struktur Database

### Tables:
1. **users** - Data pengguna dengan role (user/admin)
2. **registrations** - Data pendaftaran lengkap pendaftar
3. **registration_documents** - Dokumen yang diunggah pendaftar
4. **selection_schedules** - Jadwal tahap seleksi
5. **schedule_participants** - Peserta dalam jadwal seleksi
6. **announcements** - Pengumuman dari admin
7. **notifications** - Notifikasi untuk pengguna

## Keamanan

- ✅ CSRF Protection
- ✅ Password Hashing (Bcrypt)
- ✅ Input Validation & Sanitasi
- ✅ Role-Based Access Control (RBAC)
- ✅ Private File Storage untuk dokumen
- ✅ Database Query Protection (Eloquent ORM)
- ✅ Authentication & Authorization Middleware

## Instalasi

### Prerequisites
- PHP >= 8.2
- Composer
- MySQL 5.7+
- Node.js (opsional, untuk development)

### Steps:

1. **Clone atau Extract Proyek**
```bash
cd c:\xampp\htdocs\laravelalfa
```

2. **Install Dependencies**
```bash
composer install
```

3. **Setup Environment**
```bash
copy .env.example .env
```

Edit `.env` dan sesuaikan konfigurasi:
```
APP_URL=http://localhost
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=polisi_pendaftaran
DB_USERNAME=root
DB_PASSWORD=
```

4. **Generate Application Key**
```bash
php artisan key:generate
```

5. **Buat Database**
Buat database baru di MySQL:
```sql
CREATE DATABASE polisi_pendaftaran CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

6. **Jalankan Migrations**
```bash
php artisan migrate
```

7. **Seed Database (Opsional)**
Buat admin dan sample users:
```bash
php artisan db:seed --class=AdminSeeder
```

8. **Create Storage Link**
```bash
php artisan storage:link
```

9. **Buat Folder Private Storage**
```bash
mkdir storage/app/private
```

10. **Jalankan Server**
```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

## Akun Default

### Admin:
- Email: `admin@polisi.com`
- Password: `admin123`

### Sample Users:
- Email: `user1@example.com` hingga `user5@example.com`
- Password: `password123`

## Penggunaan

### Sebagai Calon Pendaftar:

1. Daftar akun baru di halaman `/register`
2. Login dengan akun yang telah dibuat
3. Isi formulir pendaftaran (Tahap 1: Data Pribadi)
4. Upload dokumen persyaratan (Tahap 2: Dokumen)
5. Submit pendaftaran
6. Pantau status di dashboard dan notifikasi

### Sebagai Admin:

1. Login dengan akun admin
2. Dashboard menampilkan statistik pendaftar
3. Kelola Pendaftar:
   - Lihat list semua pendaftar
   - Klik "Verifikasi" untuk melihat detail dan dokumen
   - Terima atau tolak pendaftaran
4. Kelola Jadwal:
   - Buat jadwal seleksi baru
   - Tambah peserta ke jadwal
   - Ubah status peserta (scheduled, attended, absent, postponed)
5. Kelola Pengumuman:
   - Buat pengumuman untuk grup target
   - Pilih sasaran (semua, registered, accepted, dll)

## Struktur Folder

```
laravelalfa/
├── app/
│   ├── Models/              # Model Eloquent
│   │   ├── User.php
│   │   ├── Registration.php
│   │   ├── RegistrationDocument.php
│   │   ├── SelectionSchedule.php
│   │   ├── ScheduleParticipant.php
│   │   ├── Announcement.php
│   │   └── Notification.php
│   ├── Http/
│   │   ├── Controllers/     # Controllers
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php
│   │   │   │   └── RegisterController.php
│   │   │   ├── User/
│   │   │   │   ├── DashboardController.php
│   │   │   │   └── RegistrationController.php
│   │   │   └── Admin/
│   │   │       ├── DashboardController.php
│   │   │       ├── RegistrationController.php
│   │   │       ├── ScheduleController.php
│   │   │       └── AnnouncementController.php
│   │   └── Middleware/      # Middleware Custom
│   │       ├── EnsureUserIsAdmin.php
│   │       └── EnsureUserIsRegular.php
├── database/
│   ├── migrations/          # Database Migrations
│   └── seeders/             # Database Seeders
├── resources/
│   └── views/               # Blade Templates
│       ├── layouts/
│       │   └── app.blade.php
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── user/
│       │   ├── dashboard.blade.php
│       │   └── registration/
│       │       ├── form.blade.php
│       │       ├── documents.blade.php
│       │       └── status.blade.php
│       └── admin/
│           ├── dashboard.blade.php
│           ├── registrations/
│           ├── schedules/
│           └── announcements/
├── routes/
│   └── web.php              # Web Routes
├── config/
│   └── filesystems.php      # File Storage Config
└── storage/
    └── app/
        └── private/         # Private file storage
```

## API Routes

### Authentication
- `POST /login` - Login user
- `POST /register` - Register user baru
- `POST /logout` - Logout user

### User Routes (Protected)
- `GET /dashboard` - User dashboard
- `GET /registration` - Form pendaftaran
- `POST /registration` - Submit pendaftaran
- `GET /registration/documents` - Upload dokumen
- `POST /registration/documents/upload` - Upload file
- `POST /registration/submit` - Finalkan pendaftaran
- `GET /registration/status` - Lihat status

### Admin Routes (Protected)
- `GET /admin` - Admin dashboard
- `GET /admin/registrations` - List pendaftar
- `GET /admin/registrations/{id}` - Detail pendaftar
- `GET /admin/registrations/{id}/verify` - Verifikasi pendaftar
- `POST /admin/registrations/{id}/accept` - Terima pendaftar
- `POST /admin/registrations/{id}/reject` - Tolak pendaftar
- `GET /admin/schedules` - List jadwal
- `POST /admin/schedules` - Buat jadwal
- `PUT /admin/schedules/{id}` - Update jadwal
- `POST /admin/schedules/{id}/participants` - Tambah peserta
- `GET /admin/announcements` - List pengumuman
- `POST /admin/announcements` - Buat pengumuman

## Validasi Input

### Registration Form:
- Nama lengkap: Required, max 255 karakter
- Email: Required, unique, email format
- Telepon: Required, max 20 karakter
- Tanggal lahir: Required, date format
- KTP number: Required, unique
- KTP expiry: Required, date after today
- Pendidikan: Required
- Institusi: Required
- Tahun lulus: Required, 4 digit

### Document Upload:
- File size: Max 5MB
- File type: PDF, JPG, JPEG, PNG, DOC, DOCX
- Document type: KTP, ijazah, foto (wajib) + opsional lainnya

## Troubleshooting

### 1. Error "SQLSTATE[HY000]: General error: 1030"
**Solusi:** Pastikan database sudah dibuat dan konfigurasi `.env` benar

### 2. File upload tidak berfungsi
**Solusi:** Jalankan `php artisan storage:link` dan pastikan folder `storage/app/private` ada

### 3. Middleware error
**Solusi:** Pastikan middleware sudah di-register di `bootstrap/app.php`

### 4. Permission denied saat create storage link
**Solusi:** Jalankan dengan privileges yang sesuai atau manual buat symlink

## Support & Development

Untuk development lebih lanjut:

1. **Generate CRUD Resources:**
```bash
php artisan make:model ModelName -mcr
```

2. **Create Migration:**
```bash
php artisan make:migration table_name
```

3. **Create Middleware:**
```bash
php artisan make:middleware MiddlewareName
```

4. **Tinker (Interactive Shell):**
```bash
php artisan tinker
```

## Lisensi

Proprietary - Pemerintah Republik Indonesia

## Kontak

Untuk pertanyaan atau laporan bug, silakan hubungi bagian IT.

---

**Version:** 1.0.0  
**Last Updated:** November 25, 2025  
**Built with:** Laravel 11 • MySQL • Bootstrap 5

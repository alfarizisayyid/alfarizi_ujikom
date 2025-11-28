<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

---

# 👮 Sistem Pendaftaran Kepolisian (Police Recruitment System)

Aplikasi web untuk manajemen pendaftaran dan seleksi calon anggota kepolisian, dibangun dengan Laravel 11 dan Tailwind CSS.

## 📋 Database Structure - Entity Relationship Diagram

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                          TABEL_USER (users)                                  │
├─────────────────────────────────────────────────────────────────────────────┤
│ PK  id                          INT AUTO_INCREMENT                           │
│     name                        VARCHAR(255)                                 │
│     email                       VARCHAR(255) UNIQUE                          │
│     email_verified_at           TIMESTAMP NULL                               │
│     password                    VARCHAR(255)                                 │
│     remember_token              VARCHAR(100) NULL                            │
│     created_at                  TIMESTAMP                                    │
│     updated_at                  TIMESTAMP                                    │
└─────────────────────────────────────────────────────────────────────────────┘
                                      ▲
                                      │ 1
                                      │
              ┌───────────────────────┴───────────────────────┐
              │                                               │
              │ N                                             │ N
              │                                               │
┌──────────────────────────────────────────────────────────┐  │
│        TABEL_REGISTRATIONS (registrations)               │  │
├──────────────────────────────────────────────────────────┤  │
│ PK  id                          INT AUTO_INCREMENT       │  │
│ FK  user_id                     INT (users)              │  │
│     full_name                   VARCHAR(255)             │  │
│     birth_date                  DATE                     │  │
│     gender                      ENUM(male, female)       │  │
│     phone                       VARCHAR(20)              │  │
│     email                       VARCHAR(255) UNIQUE      │  │
│     address                     TEXT                     │  │
│     city                        VARCHAR(100)             │  │
│     province                    VARCHAR(100)             │  │
│     postal_code                 VARCHAR(10)              │  │
│     ktp_number                  VARCHAR(20) UNIQUE       │  │
│     ktp_expiry                  DATE                     │  │
│     education_level             VARCHAR(100)             │  │
│     institution                 VARCHAR(255)             │  │
│     graduation_year             YEAR                     │  │
│     status                      ENUM(draft,              │  │
│                                 submitted,pending_review,│  │
│                                 accepted,rejected)       │  │
│     rejection_reason            TEXT NULL                │  │
│     submitted_at                TIMESTAMP NULL           │  │
│     reviewed_at                 TIMESTAMP NULL           │  │
│ FK  reviewed_by                 INT (users) NULL         │  │
│     created_at                  TIMESTAMP                │  │
│     updated_at                  TIMESTAMP                │  │
└──────────────────────────────────────────────────────────┘  │
        │                                                      │
        │ 1                                                    │
        │                                                      │
        │ N                                                    │
        │                                                      │
        └───┬─────────────────────────────────────────────────┘
            │
            │ FK(registration_id) │ FK(reviewed_by)
            │
            ▼
┌──────────────────────────────────────────────────────────┐
│   TABEL_REGISTRATION_DOCUMENTS (registration_documents)  │
├──────────────────────────────────────────────────────────┤
│ PK  id                          INT AUTO_INCREMENT       │
│ FK  registration_id             INT (registrations)      │
│     document_name               VARCHAR(255)             │
│     document_type               VARCHAR(100)             │
│     file_path                   VARCHAR(255)             │
│     uploaded_at                 TIMESTAMP                │
│     status                      ENUM(pending, verified,  │
│                                 rejected) DEFAULT pending │
│     created_at                  TIMESTAMP                │
│     updated_at                  TIMESTAMP                │
└──────────────────────────────────────────────────────────┘
            │
            │
            └────────────────────────────────────────────────┐
                                                             │
                                                             │ N
                                                             │
┌──────────────────────────────────────────────────────────┐ │
│   TABEL_SCHEDULE_PARTICIPANTS (schedule_participants)   │ │
├──────────────────────────────────────────────────────────┤ │
│ PK  id                          INT AUTO_INCREMENT       │ │
│ FK  schedule_id                 INT (selection_schedules)│ │
│ FK  registration_id             INT (registrations)  ◄──┼─┘
│     status                      ENUM(scheduled, attended,│
│                                 absent, postponed)       │
│     notes                       TEXT NULL                │
│     created_at                  TIMESTAMP                │
│     updated_at                  TIMESTAMP                │
│ UQ  UNIQUE(schedule_id,registration_id)                 │
└──────────────────────────────────────────────────────────┘
            │
            │ 1
            │
            │ N
            │
            └──────────────────────────────┐
                                           │
┌──────────────────────────────────────────────────────────┐
│   TABEL_SELECTION_SCHEDULES (selection_schedules)        │
├──────────────────────────────────────────────────────────┤
│ PK  id                          INT AUTO_INCREMENT       │
│     title                       VARCHAR(255)             │
│     description                 TEXT NULL                │
│     stage                       ENUM(interview,          │
│                                 physical_test,           │
│                                 psychological_test,      │
│                                 medical_test,            │
│                                 final_selection)         │
│     schedule_date               DATETIME                 │
│     start_time                  TIME                     │
│     end_time                    TIME                     │
│     location                    VARCHAR(255) NULL        │
│     capacity                    INT NULL                 │
│     notes                       TEXT NULL                │
│     status                      ENUM(planned, ongoing,   │
│                                 completed, cancelled)    │
│     created_at                  TIMESTAMP                │
│     updated_at                  TIMESTAMP                │
└──────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────┐
│        TABEL_ANNOUNCEMENTS (announcements)               │
├──────────────────────────────────────────────────────────┤
│ PK  id                          INT AUTO_INCREMENT       │
│     title                       VARCHAR(255)             │
│     content                     TEXT                     │
│     status                      ENUM(draft, published)   │
│     published_at                TIMESTAMP NULL           │
│     created_at                  TIMESTAMP                │
│     updated_at                  TIMESTAMP                │
└──────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────┐
│       TABEL_NOTIFICATIONS (notifications)                │
├──────────────────────────────────────────────────────────┤
│ PK  id                          INT AUTO_INCREMENT       │
│ FK  user_id                     INT (users)              │
│     type                        VARCHAR(100)             │
│     title                       VARCHAR(255)             │
│     message                     TEXT                     │
│     is_read                     BOOLEAN DEFAULT false    │
│     created_at                  TIMESTAMP                │
│     updated_at                  TIMESTAMP                │
└──────────────────────────────────────────────────────────┘
```

### 📊 Database Statistics

| Metrik | Jumlah |
|--------|--------|
| **Total Tables** | 10 |
| **Relationships** | 7 |
| **Recruitment Tables** | 6 |
| **System Tables** | 4 |
| **Primary Keys** | All tables (INT AUTO_INCREMENT) |
| **Foreign Keys** | 5 |

### 🔗 Relasi Antar Tabel

| From | To | Type | Deskripsi |
|------|-----|------|-----------|
| **users** | registrations | 1:N | Satu pengguna bisa memiliki banyak pendaftaran |
| **users** | notifications | 1:N | Satu pengguna menerima banyak notifikasi |
| **users** | sessions | 1:N | Satu pengguna memiliki banyak sesi |
| **registrations** | registration_documents | 1:N | Satu pendaftaran memiliki banyak dokumen |
| **registrations** | schedule_participants | 1:N | Satu pendaftaran bisa ikut banyak jadwal |
| **selection_schedules** | schedule_participants | 1:N | Satu jadwal memiliki banyak peserta |
| **registrations ↔ selection_schedules** | via schedule_participants | M:N | Relasi many-to-many |

---

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

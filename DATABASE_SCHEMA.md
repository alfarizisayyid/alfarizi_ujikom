# Database Schema Documentation

## Database: polisi_pendaftaran

### Overview
- **Total Tables:** 9 (including system tables)
- **Character Set:** utf8mb4
- **Collation:** utf8mb4_unicode_ci
- **Engine:** InnoDB (default)

---

## Table: users

### Purpose
Authentication dan role management untuk semua user (admin dan calon pendaftar).

### Columns
| Column | Type | Nullable | Key | Default | Notes |
|--------|------|----------|-----|---------|-------|
| id | bigint | No | PK | auto_increment | User identifier |
| name | varchar(255) | No | - | - | Full name |
| email | varchar(255) | No | UQ | - | Unique email |
| email_verified_at | timestamp | Yes | - | NULL | Email verification |
| password | varchar(255) | No | - | - | Bcrypt hashed |
| role | enum('user','admin') | No | - | 'user' | User role |
| is_active | tinyint | No | - | 1 | Active status |
| last_login_at | timestamp | Yes | - | NULL | Last login time |
| remember_token | varchar(100) | Yes | - | NULL | Remember me token |
| created_at | timestamp | No | - | CURRENT_TIMESTAMP | Created date |
| updated_at | timestamp | No | - | CURRENT_TIMESTAMP on update | Updated date |

### Indexes
- `PRIMARY KEY (id)`
- `UNIQUE KEY uk_email (email)`

### Relationships
- `hasOne` Registration
- `hasMany` Notification
- `hasMany` Announcement (as creator)

### Sample Data
```sql
INSERT INTO users VALUES (
  1, 'Admin User', 'admin@polisi.com', NULL, 
  '$2y$12$...', 'admin', 1, '2025-01-15 10:30:00', 
  NULL, '2025-01-01 08:00:00', '2025-01-15 10:30:00'
);
```

---

## Table: registrations

### Purpose
Menyimpan data lengkap calon pendaftar polisi.

### Columns
| Column | Type | Nullable | Key | Default | Notes |
|--------|------|----------|-----|---------|-------|
| id | bigint | No | PK | auto_increment | Registration ID |
| user_id | bigint | No | FK | - | Reference to users |
| full_name | varchar(255) | No | - | - | Full name |
| birth_date | date | No | - | - | Date of birth |
| birth_place | varchar(255) | No | - | - | Place of birth |
| gender | enum('male','female') | No | - | - | Gender |
| phone | varchar(20) | No | - | - | Phone number |
| email | varchar(255) | No | - | - | Email address |
| address | text | No | - | - | Full address |
| city | varchar(100) | No | - | - | City |
| province | varchar(100) | No | - | - | Province |
| postal_code | varchar(10) | No | - | - | Postal code |
| ktp_number | varchar(16) | No | - | - | KTP/ID number |
| ktp_expiry | date | No | - | - | KTP expiry date |
| education_level | enum('sma','diploma','bachelor','master') | No | - | - | Education level |
| institution | varchar(255) | No | - | - | School/University |
| graduation_year | year | No | - | - | Graduation year |
| status | enum('draft','submitted','pending','accepted','rejected') | No | - | 'draft' | Application status |
| rejection_reason | text | Yes | - | NULL | Rejection reason if rejected |
| submitted_at | timestamp | Yes | - | NULL | Submission date |
| reviewed_at | timestamp | Yes | - | NULL | Review date |
| reviewed_by | bigint | Yes | FK | NULL | Admin reviewer |
| created_at | timestamp | No | - | CURRENT_TIMESTAMP | Created date |
| updated_at | timestamp | No | - | CURRENT_TIMESTAMP | Updated date |

### Indexes
- `PRIMARY KEY (id)`
- `FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE`
- `FOREIGN KEY (reviewed_by) REFERENCES users(id)`

### Relationships
- `belongsTo` User
- `hasMany` RegistrationDocument
- `belongsToMany` SelectionSchedule (through schedule_participants)
- `hasMany` Notification

### Enum Values
**status:**
- `draft` - Form belum selesai
- `submitted` - Form submitted, tunggu verifikasi
- `pending` - Sedang direview admin
- `accepted` - Diterima
- `rejected` - Ditolak

---

## Table: registration_documents

### Purpose
Menyimpan dokumen yang diunggah oleh calon pendaftar.

### Columns
| Column | Type | Nullable | Key | Default | Notes |
|--------|------|----------|-----|---------|-------|
| id | bigint | No | PK | auto_increment | Document ID |
| registration_id | bigint | No | FK | - | Reference to registrations |
| document_type | varchar(50) | No | - | - | Type of document |
| file_path | varchar(255) | No | - | - | Path in storage |
| original_filename | varchar(255) | No | - | - | Original file name |
| mime_type | varchar(100) | No | - | - | MIME type |
| file_size | bigint | No | - | - | File size in bytes |
| verification_status | enum('pending','verified','rejected') | No | - | 'pending' | Verification status |
| verification_notes | text | Yes | - | NULL | Notes from verifier |
| verified_at | timestamp | Yes | - | NULL | Verification date |
| verified_by | bigint | Yes | FK | NULL | Admin verifier |
| created_at | timestamp | No | - | CURRENT_TIMESTAMP | Upload date |
| updated_at | timestamp | No | - | CURRENT_TIMESTAMP | Updated date |

### Indexes
- `PRIMARY KEY (id)`
- `FOREIGN KEY (registration_id) REFERENCES registrations(id) ON DELETE CASCADE`
- `FOREIGN KEY (verified_by) REFERENCES users(id)`

### Relationships
- `belongsTo` Registration
- `belongsTo` User (as verifier)

### File Storage Path
```
storage/app/private/registrations/{registration_id}/documents/
  - {document_type}_{timestamp}_{original_filename}
```

### Enum Values
**verification_status:**
- `pending` - Belum diverifikasi
- `verified` - Terverifikasi
- `rejected` - Ditolak

---

## Table: selection_schedules

### Purpose
Menyimpan jadwal tahap seleksi (tes tulis, wawancara, tes kesehatan, dll).

### Columns
| Column | Type | Nullable | Key | Default | Notes |
|--------|------|----------|-----|---------|-------|
| id | bigint | No | PK | auto_increment | Schedule ID |
| title | varchar(255) | No | - | - | Schedule title |
| description | text | Yes | - | NULL | Description |
| stage | enum('written_test','physical_test','interview','medical_check','final') | No | - | - | Selection stage |
| schedule_date | date | No | - | - | Date of schedule |
| start_time | time | No | - | - | Start time |
| end_time | time | No | - | - | End time |
| location | varchar(255) | No | - | - | Location |
| capacity | int | No | - | 100 | Max participants |
| notes | text | Yes | - | NULL | Additional notes |
| status | enum('draft','scheduled','ongoing','completed') | No | - | 'draft' | Schedule status |
| created_at | timestamp | No | - | CURRENT_TIMESTAMP | Created date |
| updated_at | timestamp | No | - | CURRENT_TIMESTAMP | Updated date |

### Indexes
- `PRIMARY KEY (id)`
- `INDEX idx_stage (stage)`
- `INDEX idx_schedule_date (schedule_date)`
- `INDEX idx_status (status)`

### Relationships
- `belongsToMany` Registration (through schedule_participants)
- `hasMany` ScheduleParticipant

---

## Table: schedule_participants

### Purpose
Pivot table untuk menghubungkan peserta dengan jadwal seleksi.

### Columns
| Column | Type | Nullable | Key | Default | Notes |
|--------|------|----------|-----|---------|-------|
| id | bigint | No | PK | auto_increment | Participant ID |
| schedule_id | bigint | No | FK | - | Reference to schedules |
| registration_id | bigint | No | FK | - | Reference to registrations |
| status | enum('scheduled','attended','absent','postponed') | No | - | 'scheduled' | Participation status |
| notes | text | Yes | - | NULL | Notes |
| created_at | timestamp | No | - | CURRENT_TIMESTAMP | Created date |
| updated_at | timestamp | No | - | CURRENT_TIMESTAMP | Updated date |

### Indexes
- `PRIMARY KEY (id)`
- `FOREIGN KEY (schedule_id) REFERENCES selection_schedules(id) ON DELETE CASCADE`
- `FOREIGN KEY (registration_id) REFERENCES registrations(id) ON DELETE CASCADE`
- `UNIQUE KEY uk_schedule_registration (schedule_id, registration_id)`

### Enum Values
**status:**
- `scheduled` - Peserta terjadwal
- `attended` - Peserta hadir
- `absent` - Peserta tidak hadir
- `postponed` - Peserta ditunda

---

## Table: announcements

### Purpose
Menyimpan pengumuman yang dibuat oleh admin.

### Columns
| Column | Type | Nullable | Key | Default | Notes |
|--------|------|----------|-----|---------|-------|
| id | bigint | No | PK | auto_increment | Announcement ID |
| title | varchar(255) | No | - | - | Announcement title |
| content | longtext | No | - | - | Announcement content |
| created_by | bigint | No | FK | - | Admin creator |
| audience | varchar(50) | No | - | 'all' | Target audience |
| published_at | timestamp | Yes | - | NULL | Publish date |
| is_active | tinyint | No | - | 1 | Active status |
| created_at | timestamp | No | - | CURRENT_TIMESTAMP | Created date |
| updated_at | timestamp | No | - | CURRENT_TIMESTAMP | Updated date |

### Indexes
- `PRIMARY KEY (id)`
- `FOREIGN KEY (created_by) REFERENCES users(id)`
- `INDEX idx_is_active (is_active)`

### Relationships
- `belongsTo` User (as creator)

### Audience Options
- `all` - Semua user
- `registered` - User yang sudah register
- `accepted` - Pendaftar yang diterima
- `rejected` - Pendaftar yang ditolak
- `schedule_participants` - Peserta jadwal aktif

---

## Table: notifications

### Purpose
Menyimpan notifikasi yang dikirim ke user.

### Columns
| Column | Type | Nullable | Key | Default | Notes |
|--------|------|----------|-----|---------|-------|
| id | bigint | No | PK | auto_increment | Notification ID |
| user_id | bigint | No | FK | - | Reference to users |
| registration_id | bigint | Yes | FK | NULL | Reference to registration |
| title | varchar(255) | No | - | - | Notification title |
| message | text | No | - | - | Notification message |
| type | varchar(50) | No | - | - | Notification type |
| is_read | tinyint | No | - | 0 | Read status |
| read_at | timestamp | Yes | - | NULL | Read date |
| created_at | timestamp | No | - | CURRENT_TIMESTAMP | Created date |

### Indexes
- `PRIMARY KEY (id)`
- `FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE`
- `FOREIGN KEY (registration_id) REFERENCES registrations(id) ON DELETE CASCADE`
- `INDEX idx_user_is_read (user_id, is_read)`
- `INDEX idx_type (type)`

### Relationships
- `belongsTo` User
- `belongsTo` Registration

### Notification Types
- `status_update` - Perubahan status pendaftaran
- `announcement` - Pengumuman umum
- `schedule` - Informasi jadwal
- `rejection` - Penolakan pendaftaran
- `acceptance` - Penerimaan pendaftaran

---

## System Tables (Laravel Default)

### cache
- Menyimpan cache data
- Used by cache system

### cache_locks
- Locking mechanism untuk cache

### failed_jobs
- Menyimpan job yang gagal
- For queue system

### jobs
- Job queue data
- For background jobs

### migrations
- Track migration history
- Laravel internal

### personal_access_tokens
- API token management (jika diperlukan)
- Untuk Sanctum

---

## Database Views (Optional)

Belum ada view yang dibuat. Jika diperlukan untuk reporting:

### View: registration_status_summary
```sql
CREATE VIEW registration_status_summary AS
SELECT 
  status,
  COUNT(*) as count,
  DATE(created_at) as date
FROM registrations
GROUP BY status, DATE(created_at);
```

### View: schedule_participants_count
```sql
CREATE VIEW schedule_participants_count AS
SELECT 
  s.id,
  s.title,
  COUNT(sp.id) as participant_count,
  s.capacity,
  (s.capacity - COUNT(sp.id)) as available_slots
FROM selection_schedules s
LEFT JOIN schedule_participants sp ON s.id = sp.schedule_id
GROUP BY s.id;
```

---

## Database Constraints

### Cascade Deletes
- `registrations.user_id` → `users.id`
- `registration_documents.registration_id` → `registrations.id`
- `schedule_participants.schedule_id` → `selection_schedules.id`
- `schedule_participants.registration_id` → `registrations.id`
- `notifications.user_id` → `users.id`
- `notifications.registration_id` → `registrations.id`

### Foreign Key References (No Action)
- `registration_documents.verified_by` → `users.id`
- `registrations.reviewed_by` → `users.id`
- `announcements.created_by` → `users.id`

---

## Indexes Strategy

### Write Optimization (Insertion/Update)
- Minimal indexes on frequently updated tables
- `users`: Only unique email
- `registrations`: Only necessary FKs

### Read Optimization (Selection)
```sql
-- Frequent queries optimization
CREATE INDEX idx_registrations_status 
  ON registrations(status);
CREATE INDEX idx_registrations_user 
  ON registrations(user_id);
CREATE INDEX idx_notifications_user_read 
  ON notifications(user_id, is_read);
CREATE INDEX idx_schedule_participants_schedule 
  ON schedule_participants(schedule_id);
```

---

## Query Examples

### Get registration with all documents and schedule
```sql
SELECT r.*, 
       GROUP_CONCAT(rd.id) as document_ids,
       GROUP_CONCAT(sp.schedule_id) as schedule_ids
FROM registrations r
LEFT JOIN registration_documents rd ON r.id = rd.registration_id
LEFT JOIN schedule_participants sp ON r.id = sp.registration_id
WHERE r.id = 1
GROUP BY r.id;
```

### Count pending documents by registration
```sql
SELECT 
  r.id,
  r.full_name,
  COUNT(rd.id) as total_docs,
  SUM(CASE WHEN rd.verification_status = 'pending' THEN 1 ELSE 0 END) as pending_docs
FROM registrations r
LEFT JOIN registration_documents rd ON r.id = rd.registration_id
GROUP BY r.id;
```

### Get users with unread notifications
```sql
SELECT u.id, u.name, COUNT(n.id) as unread_count
FROM users u
LEFT JOIN notifications n ON u.id = n.user_id AND n.is_read = 0
WHERE n.id IS NOT NULL
GROUP BY u.id;
```

### Get schedule with participant details
```sql
SELECT 
  s.id,
  s.title,
  s.schedule_date,
  COUNT(sp.id) as participant_count,
  (s.capacity - COUNT(sp.id)) as available_slots
FROM selection_schedules s
LEFT JOIN schedule_participants sp ON s.id = sp.schedule_id
WHERE s.status IN ('scheduled', 'ongoing')
GROUP BY s.id
HAVING available_slots > 0;
```

---

## Backup Strategy

### Daily Backup Command
```bash
mysqldump -u root polisi_pendaftaran > backup_$(date +%Y%m%d_%H%M%S).sql
```

### Automated Backup (Cron)
```bash
0 2 * * * /usr/bin/mysqldump -u root polisi_pendaftaran > /backups/polisi_$(date +\%Y\%m\%d).sql
```

### Restore from Backup
```bash
mysql -u root polisi_pendaftaran < backup_20250115_100000.sql
```

---

## Performance Tuning

### Recommended Settings for Production
```sql
-- Set query timeout
SET SESSION max_execution_time=30000; -- 30 seconds

-- Increase buffer pool for large tables
SET GLOBAL innodb_buffer_pool_size = 1073741824; -- 1GB

-- Enable query cache (if small dataset)
SET GLOBAL query_cache_type = 1;
SET GLOBAL query_cache_size = 134217728; -- 128MB
```

---

## Schema Version
- Version: 1.0.0
- Created: November 25, 2025
- Database: polisi_pendaftaran
- Engine: InnoDB
- Charset: utf8mb4

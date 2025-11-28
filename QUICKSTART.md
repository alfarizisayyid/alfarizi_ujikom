# Quick Start Guide - Sistem Pendaftaran Polisi

## ⚡ Langkah-Langkah Cepat (5 Menit)

### Langkah 1: Setup Environment
```bash
cd c:\xampp\htdocs\laravelalfa

# Copy environment file
copy .env.example .env

# Generate key
php artisan key:generate
```

### Langkah 2: Database Setup
```bash
# Buat database baru di MySQL
mysql -u root -e "CREATE DATABASE polisi_pendaftaran CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Edit .env file dan set:
# DB_DATABASE=polisi_pendaftaran
# DB_USERNAME=root
# DB_PASSWORD=(kosong jika default XAMPP)

# Run migrations
php artisan migrate

# Seed initial data
php artisan db:seed --class=AdminSeeder
```

### Langkah 3: Storage Setup
```bash
# Create storage link
php artisan storage:link

# Create private directory
mkdir storage\app\private
```

### Langkah 4: Run Server
```bash
php artisan serve
```

### Langkah 5: Access Application
```
http://localhost:8000
```

---

## 📱 Login Test Credentials

### Admin
```
Email: admin@polisi.com
Password: admin123
```

### Regular User
```
Email: user1@example.com
Password: password123

Alternative:
user2@example.com - password123
user3@example.com - password123
user4@example.com - password123
user5@example.com - password123
```

---

## 🎯 Testing Workflow

### As Admin:
1. Login dengan `admin@polisi.com`
2. Lihat dashboard dengan statistik
3. Go to **Registrations** → verifikasi dokumen user
4. Accept atau reject aplikasi
5. Go to **Schedules** → create jadwal baru
6. Add peserta ke schedule
7. Go to **Announcements** → buat pengumuman
8. Lihat notifikasi terkirim ke user

### As User:
1. Register akun baru ATAU login dengan `user1@example.com`
2. Fill registration form (tahap 1)
3. Upload documents (tahap 2)
4. Submit registration
5. View status page untuk lihat progress
6. Check notifikasi untuk updates

---

## 🔧 Troubleshooting

### Error: "Class not found"
```bash
composer dump-autoload
```

### Error: "SQLSTATE[HY000]"
- Check MySQL running
- Verify DB credentials di `.env`
- Pastikan database sudah created

### Error: "Storage link already exists"
- Delete jika sudah ada: `rmdir public/storage`
- Jalankan ulang: `php artisan storage:link`

### Error: "Permission denied"
```bash
# Set proper permissions
icacls storage /grant:r %username%:F /t
icacls bootstrap/cache /grant:r %username%:F /t
```

### File upload tidak jalan
- Check `storage/app/private` exists
- Verify `config/filesystems.php` configured
- Clear cache: `php artisan cache:clear`

---

## 📁 Folder Structure Quick Ref

```
laravelalfa/
├── app/
│   ├── Http/Controllers/
│   │   ├── Auth/          ← Login/Register controllers
│   │   ├── User/          ← User dashboard & registration
│   │   └── Admin/         ← Admin features
│   ├── Models/            ← Database models
│   ├── Middleware/        ← Auth middleware
│   └── Helpers/           ← Helper functions
│
├── database/
│   ├── migrations/        ← Database structure
│   └── seeders/           ← Test data
│
├── resources/views/       ← All pages
│   ├── auth/              ← Login/Register pages
│   ├── user/              ← User pages
│   └── admin/             ← Admin pages
│
├── routes/
│   └── web.php            ← All URL routes
│
├── config/
│   └── filesystems.php    ← File storage config
│
└── storage/
    └── app/private/       ← Uploaded documents
```

---

## 🔗 URL Routes

### Public
```
http://localhost:8000/login           - Login page
http://localhost:8000/register        - Register page
http://localhost:8000/logout          - Logout
```

### User (after login as user)
```
http://localhost:8000/dashboard                    - Dashboard
http://localhost:8000/registration                 - Form page
http://localhost:8000/registration/documents       - Upload page
http://localhost:8000/registration/status          - Status page
```

### Admin (after login as admin)
```
http://localhost:8000/admin                         - Dashboard
http://localhost:8000/admin/registrations           - List registrations
http://localhost:8000/admin/registrations/{id}      - Detail
http://localhost:8000/admin/registrations/{id}/verify - Verify

http://localhost:8000/admin/schedules               - List schedules
http://localhost:8000/admin/schedules/create        - Create schedule
http://localhost:8000/admin/schedules/{id}          - Detail
http://localhost:8000/admin/schedules/{id}/edit     - Edit schedule

http://localhost:8000/admin/announcements           - List announcements
http://localhost:8000/admin/announcements/create    - Create announcement
http://localhost:8000/admin/announcements/{id}/edit - Edit announcement
```

---

## 💾 Database Reset

Jika ingin reset semua data:

```bash
# Reset migrations
php artisan migrate:reset

# Run migrations lagi
php artisan migrate

# Seed data baru
php artisan db:seed --class=AdminSeeder
```

---

## 🛠️ Common Commands

```bash
# Clear all cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Refresh database (dangerous - clears all data!)
php artisan migrate:fresh --seed

# Create new migration
php artisan make:migration migration_name

# Create new model
php artisan make:model ModelName -m

# Create new controller
php artisan make:controller ControllerName

# Create new seeder
php artisan make:seeder SeederName

# Check routes
php artisan route:list

# Run tests
php artisan test
```

---

## 📋 Checklist Sebelum Go Live

- [ ] Database created dan migrate berhasil
- [ ] Seeding data complete
- [ ] Storage link created
- [ ] Admin login works
- [ ] User registration works
- [ ] File upload works
- [ ] Admin verification works
- [ ] Schedule creation works
- [ ] Announcement sending works
- [ ] All pages responsive
- [ ] No console errors
- [ ] No database errors

---

## 🎓 Learning Resources

**Laravel Documentation:**
- Models & Relationships: https://laravel.com/docs/11.x/eloquent-relationships
- Authentication: https://laravel.com/docs/11.x/authentication
- Middleware: https://laravel.com/docs/11.x/middleware
- File Storage: https://laravel.com/docs/11.x/filesystem
- Blade Templates: https://laravel.com/docs/11.x/blade

**Useful Commands for Development:**
```bash
# Start development server with watch
php artisan serve --host=localhost --port=8000

# Generate IDE helper for autocomplete
composer require --dev barryvdh/laravel-ide-helper
php artisan ide-helper:generate

# Monitor database queries
composer require --dev barryvdh/laravel-debugbar
```

---

## 💡 Tips & Tricks

### View All Routes
```bash
php artisan route:list | grep -E 'admin|dashboard|registration'
```

### Test Email in Development
Use Laravel's log mail driver. Edit `.env`:
```
MAIL_DRIVER=log
```

### Debug SQL Queries
Add this to enable query logging:
```php
DB::enableQueryLog();
// ... your code ...
dd(DB::getQueryLog());
```

### Quick Admin Access
Keep this tab open in browser:
```
http://localhost:8000/login
Email: admin@polisi.com
Password: admin123
```

---

## 📞 Support Resources

If you encounter issues:

1. **Check Laravel logs:**
   ```
   storage/logs/laravel.log
   ```

2. **Check MySQL logs:**
   ```
   XAMPP Control Panel → MySQL → Logs
   ```

3. **Browser console:**
   - Press F12
   - Check Console tab for JS errors

4. **Database check:**
   ```bash
   php artisan tinker
   >>> User::all()
   >>> Registration::count()
   ```

---

## 🚀 Next Steps After Setup

1. **Customize branding:**
   - Edit `resources/views/layouts/app.blade.php`
   - Update logo and colors

2. **Add email notifications:**
   - Create Mail classes
   - Update controllers to send emails

3. **Setup backups:**
   - Configure database backups
   - Setup automatic migrations

4. **Monitor logs:**
   - Setup log aggregation
   - Monitor error rates

5. **Scale database:**
   - Add indexes for frequently queried columns
   - Consider partitioning large tables

---

## ✅ Status: Ready to Go!

Your Laravel police recruitment registration system is now ready to deploy!

**Total Setup Time:** ~5-10 minutes
**All Features:** ✅ Implemented
**Security:** ✅ Configured
**Documentation:** ✅ Complete

Happy coding! 🎉

# API & Endpoint Documentation

## Authentication Endpoints

### Register User
```
POST /register
Content-Type: application/x-www-form-urlencoded

Parameters:
- name (required, string, max:255)
- email (required, email, unique:users)
- password (required, string, min:8, confirmed)
- password_confirmation (required, string)

Response: Redirect to /dashboard (302)
```

### Login
```
POST /login
Content-Type: application/x-www-form-urlencoded

Parameters:
- email (required, email)
- password (required, string)
- remember (optional, checkbox)

Response:
- Success: Redirect to /dashboard or /admin (302)
- Error: Redirect back with errors (302)
```

### Logout
```
POST /logout
Authorization: Session required

Response: Redirect to /login (302)
```

---

## User Endpoints

All user endpoints require authentication with `auth.user` middleware.

### Dashboard
```
GET /dashboard
Authorization: Session (User)

Response: HTML Dashboard view
- Shows registration status
- Unread notification count
- Quick action links
```

### Registration - Form Display
```
GET /registration
Authorization: Session (User)

Response: HTML Registration form (Step 1)
- Fields for personal data
- Form action to POST /registration
```

### Registration - Store Personal Data
```
POST /registration
Authorization: Session (User)
Content-Type: application/x-www-form-urlencoded

Parameters:
- full_name (required, string, max:255)
- birth_date (required, date)
- gender (required, in:male,female)
- phone (required, string, regex:/^([0-9\s\-\+\(\)]*)$/)
- email (required, email)
- address (required, string)
- city (required, string)
- province (required, string)
- postal_code (required, regex:/^[0-9]{5}$/)
- ktp_number (required, regex:/^[0-9]{16}$/)
- ktp_expiry (required, date, after:today)
- education_level (required, in:sma,diploma,bachelor,master)
- institution (required, string)
- graduation_year (required, numeric, digits:4)

Response: JSON
{
  "success": true,
  "message": "Data tersimpan, lanjut ke upload dokumen",
  "redirect": "/registration/documents"
}
```

### Documents Upload Form
```
GET /registration/documents
Authorization: Session (User)

Response: HTML Upload form
- Shows existing documents
- Drag-drop upload interface
- File validation display
```

### Upload Document (AJAX)
```
POST /registration/documents/upload
Authorization: Session (User)
Content-Type: multipart/form-data

Parameters:
- file (required, file, max:5120, mimes:pdf,jpg,png,doc,docx)
- document_type (required, in:ktp,ijazah,foto,other)

Response: JSON
{
  "success": true,
  "document_id": 123,
  "file_name": "ktp_john_doe.pdf",
  "file_size": "2.5 MB",
  "upload_time": "just now"
}

Error Response:
{
  "success": false,
  "message": "File too large or invalid format"
}
```

### Delete Document (AJAX)
```
DELETE /registration/documents/{document_id}
Authorization: Session (User)

Response: JSON
{
  "success": true,
  "message": "Document deleted"
}
```

### Submit Registration
```
POST /registration/submit
Authorization: Session (User)

Response: JSON
{
  "success": true,
  "message": "Pendaftaran submitted",
  "status": "submitted"
}

Error if:
- No personal data saved
- No documents uploaded
- User already submitted
```

### View Registration Status
```
GET /registration/status
Authorization: Session (User)

Response: HTML Status page
- Shows current status (draft/submitted/pending/accepted/rejected)
- Document verification status
- Scheduled selection dates
- Notifications list (paginated)
```

---

## Admin Endpoints

All admin endpoints require authentication with `auth.admin` middleware.

### Admin Dashboard
```
GET /admin
Authorization: Session (Admin)

Response: HTML Dashboard
- Total registered users count
- Pending registrations count
- Accepted registrations count
- Rejected registrations count
- Recent registrations table (5 latest)
- Upcoming schedules table
```

### List Registrations
```
GET /admin/registrations
Authorization: Session (Admin)

Query Parameters:
- page (optional, numeric, default: 1)
- status (optional, in: draft,submitted,pending,accepted,rejected)
- search (optional, string - search by name/email)
- per_page (optional, numeric, default: 15)

Response: HTML Table
- Paginated list of registrations
- Filter by status
- Search capability
- Action buttons (view, verify, accept, reject)
```

### View Registration Detail
```
GET /admin/registrations/{registration_id}
Authorization: Session (Admin)

Response: HTML Detail page
- All personal information
- Document list with status
- Verification history
- Action buttons (accept/reject)
```

### Verification Form
```
GET /admin/registrations/{registration_id}/verify
Authorization: Session (Admin)

Response: HTML Form
- Document review interface
- Verification notes input
- Accept/Reject buttons
```

### Accept Registration
```
POST /admin/registrations/{registration_id}/accept
Authorization: Session (Admin)
Content-Type: application/x-www-form-urlencoded

Parameters:
- notes (optional, string, max:500)

Response: JSON
{
  "success": true,
  "message": "Registration accepted",
  "status": "accepted"
}

Side effects:
- Registration status set to 'accepted'
- Notification sent to user
- User can now be added to schedules
```

### Reject Registration
```
POST /admin/registrations/{registration_id}/reject
Authorization: Session (Admin)
Content-Type: application/x-www-form-urlencoded

Parameters:
- rejection_reason (required, string, max:500)

Response: JSON
{
  "success": true,
  "message": "Registration rejected",
  "status": "rejected"
}

Side effects:
- Registration status set to 'rejected'
- Rejection reason stored
- Notification sent to user with reason
```

### Update Document Verification
```
POST /admin/documents/{document_id}/status
Authorization: Session (Admin)
Content-Type: application/x-www-form-urlencoded

Parameters:
- verification_status (required, in: pending,verified,rejected)
- verification_notes (optional, string, max:500)

Response: JSON
{
  "success": true,
  "message": "Document status updated"
}
```

### Download Document
```
GET /admin/documents/{document_id}/download
Authorization: Session (Admin)

Response: File download (binary)
- Secure file download from private storage
- Proper headers set for file type
- Original filename preserved
```

---

## Schedule Management Endpoints

### List Schedules
```
GET /admin/schedules
Authorization: Session (Admin)

Query Parameters:
- page (optional, numeric, default: 1)
- status (optional, in: draft,scheduled,ongoing,completed)
- stage (optional, in: written_test,physical_test,interview,medical_check,final)
- per_page (optional, numeric, default: 15)

Response: HTML Table
- Paginated list of schedules
- Filter by status and stage
- Participant count display
- Action buttons
```

### Create Schedule Form
```
GET /admin/schedules/create
Authorization: Session (Admin)

Response: HTML Form
- All input fields for schedule creation
```

### Create Schedule
```
POST /admin/schedules
Authorization: Session (Admin)
Content-Type: application/x-www-form-urlencoded

Parameters:
- title (required, string, max:255)
- description (optional, string, max:1000)
- stage (required, in: written_test,physical_test,interview,medical_check,final)
- schedule_date (required, date, after:today)
- start_time (required, time)
- end_time (required, time, after:start_time)
- location (required, string, max:255)
- capacity (required, numeric, min:1, max:10000)
- notes (optional, string, max:500)

Response: JSON
{
  "success": true,
  "message": "Schedule created",
  "schedule_id": 123,
  "redirect": "/admin/schedules/123"
}

Validation:
- end_time must be after start_time
- schedule_date must be future date
- capacity must be positive number
```

### View Schedule Detail
```
GET /admin/schedules/{schedule_id}
Authorization: Session (Admin)

Response: HTML Detail page
- Schedule information
- Participants table (paginated)
- Participant status for each
- Add participants button
- Delete schedule button
```

### Edit Schedule Form
```
GET /admin/schedules/{schedule_id}/edit
Authorization: Session (Admin)

Response: HTML Form
- Pre-filled schedule data
- All editable fields
```

### Update Schedule
```
PUT /admin/schedules/{schedule_id}
Authorization: Session (Admin)
Content-Type: application/x-www-form-urlencoded

Parameters: (same as create)

Response: JSON
{
  "success": true,
  "message": "Schedule updated"
}
```

### Delete Schedule
```
DELETE /admin/schedules/{schedule_id}
Authorization: Session (Admin)

Response: JSON
{
  "success": true,
  "message": "Schedule deleted"
}

Restriction:
- Cannot delete if has participants
- Must remove participants first
```

### Add Participants to Schedule
```
POST /admin/schedules/{schedule_id}/participants
Authorization: Session (Admin)
Content-Type: application/x-www-form-urlencoded

Parameters:
- registration_ids[] (array of registration IDs to add)

Response: JSON
{
  "success": true,
  "added_count": 15,
  "message": "Participants added",
  "notification_sent": true
}

Restrictions:
- Only accepted registrations
- Cannot exceed schedule capacity
- Auto-sends notifications to added participants
```

### Update Participant Status
```
PUT /admin/schedules/participants/{participant_id}
Authorization: Session (Admin)
Content-Type: application/x-www-form-urlencoded

Parameters:
- status (required, in: scheduled,attended,absent,postponed)
- notes (optional, string, max:500)

Response: JSON
{
  "success": true,
  "message": "Participant status updated"
}
```

### Remove Participant from Schedule
```
DELETE /admin/schedules/participants/{participant_id}
Authorization: Session (Admin)

Response: JSON
{
  "success": true,
  "message": "Participant removed from schedule"
}
```

---

## Announcement Management Endpoints

### List Announcements
```
GET /admin/announcements
Authorization: Session (Admin)

Query Parameters:
- page (optional, numeric, default: 1)
- per_page (optional, numeric, default: 15)

Response: HTML Table
- Paginated list of announcements
- Creation date and status
- Action buttons (view, edit, delete)
```

### Create Announcement Form
```
GET /admin/announcements/create
Authorization: Session (Admin)

Response: HTML Form
- Title, content inputs
- Audience selection
- Publish now/later option
```

### Create Announcement
```
POST /admin/announcements
Authorization: Session (Admin)
Content-Type: application/x-www-form-urlencoded

Parameters:
- title (required, string, max:255)
- content (required, string, max:2000)
- audience (required, in: all,registered,accepted,rejected,schedule_participants)
- is_active (required, boolean)
- published_at (optional, datetime)

Response: JSON
{
  "success": true,
  "announcement_id": 123,
  "notifications_sent": 45,
  "message": "Announcement created and notifications sent"
}

Side effects:
- Automatic notification creation for target audience
- Notifications sent immediately if is_active = true
```

### View Announcement
```
GET /admin/announcements/{announcement_id}
Authorization: Session (Admin)

Response: HTML Detail page
- Full announcement content
- Audience information
- Recipients count
- Edit/Delete buttons
```

### Edit Announcement Form
```
GET /admin/announcements/{announcement_id}/edit
Authorization: Session (Admin)

Response: HTML Form
- Pre-filled announcement data
```

### Update Announcement
```
PUT /admin/announcements/{announcement_id}
Authorization: Session (Admin)
Content-Type: application/x-www-form-urlencoded

Parameters: (same as create)

Response: JSON
{
  "success": true,
  "message": "Announcement updated",
  "notifications_updated": true
}
```

### Delete Announcement
```
DELETE /admin/announcements/{announcement_id}
Authorization: Session (Admin)

Response: JSON
{
  "success": true,
  "message": "Announcement deleted"
}
```

---

## Error Responses

### 400 Bad Request
```json
{
  "success": false,
  "message": "Validation error message",
  "errors": {
    "field_name": ["Error message 1", "Error message 2"]
  }
}
```

### 401 Unauthorized
```json
{
  "message": "Unauthenticated.",
  "redirect": "/login"
}
```

### 403 Forbidden
```json
{
  "message": "This action is unauthorized."
}
```

### 404 Not Found
```json
{
  "message": "Resource not found"
}
```

### 500 Server Error
```json
{
  "message": "Server error occurred",
  "error": "Error details (only in debug mode)"
}
```

---

## Status Enums

### Registration Status
- `draft` - Form not completed
- `submitted` - Form submitted, waiting verification
- `pending` - Under admin review
- `accepted` - Approved by admin
- `rejected` - Rejected by admin

### Document Verification Status
- `pending` - Awaiting verification
- `verified` - Document verified and approved
- `rejected` - Document rejected

### Schedule Status
- `draft` - Schedule created but not active
- `scheduled` - Schedule active and participants added
- `ongoing` - Schedule is currently happening
- `completed` - Schedule completed

### Participant Status
- `scheduled` - Participant assigned to schedule
- `attended` - Participant attended
- `absent` - Participant absent
- `postponed` - Participant's participation postponed

### Selection Stage
- `written_test` - Written test stage
- `physical_test` - Physical fitness test
- `interview` - Interview stage
- `medical_check` - Medical examination
- `final` - Final decision stage

### Notification Type
- `status_update` - Registration status changed
- `announcement` - General announcement
- `schedule` - Schedule participant notification
- `rejection` - Registration rejected
- `acceptance` - Registration accepted

---

## Rate Limiting

No rate limiting currently implemented. Consider adding for production:

```bash
# In app/Http/Middleware/ThrottleRequests.php
protected $limits = [
    'api' => '60,1', // 60 requests per minute
];
```

---

## CORS & Security Headers

CORS is not enabled. If building API version:

```php
// In bootstrap/app.php
->withMiddleware(function (Middleware $middleware) {
    $middleware->use(\Illuminate\Http\Middleware\HandleCors::class);
})
```

---

## Pagination

Default pagination: 15 items per page

Customizable via query parameter:
```
?per_page=50
```

Max allowed per page: Can be customized in respective controller.

---

## Search & Filtering

### Registration Search
- By full_name (partial match)
- By email (exact match)
- By status (exact match)

### Implementation
```php
// In RegistrationController::index()
$query->where('full_name', 'like', "%{$search}%")
      ->orWhere('email', $search);
```

---

## API Testing

### Using cURL
```bash
curl -X POST http://localhost:8000/login \
  -d "email=admin@polisi.com&password=admin123" \
  -L
```

### Using Postman
1. Set Base URL: `http://localhost:8000`
2. Set Cookie jar enabled
3. First request to `/login` to get session
4. Follow requests with session cookie

### Using Insomnia
```
Variable: {{base_url}} = http://localhost:8000
Then use: {{base_url}}/admin/registrations
```

---

## Documentation Version
- Version: 1.0.0
- Last Updated: November 25, 2025
- Framework: Laravel 11
- Auth: Session-based

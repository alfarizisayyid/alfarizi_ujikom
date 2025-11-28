<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Pendaftaran Polisi')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #003366;
            --secondary-color: #004d80;
            --accent-color: #0066b3;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.3rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            margin-bottom: 1rem;
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-bottom: none;
            padding: 1rem;
        }

        .badge-status-pending {
            background-color: #ffc107;
            color: #000;
        }

        .badge-status-accepted {
            background-color: #28a745;
        }

        .badge-status-rejected {
            background-color: #dc3545;
        }

        .badge-status-submitted {
            background-color: #17a2b8;
        }

        .form-label {
            font-weight: 500;
            color: #333;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 102, 179, 0.25);
        }

        .sidebar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            padding-top: 1rem;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1rem;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255,255,255,0.1);
            border-left: 3px solid #ffc107;
            padding-left: calc(1rem - 3px);
        }

        .main-content {
            padding: 2rem;
        }

        .alert {
            border-radius: 0.5rem;
            border: none;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 102, 179, 0.05);
        }

        .document-item {
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .footer {
            background-color: var(--primary-color);
            color: white;
            padding: 2rem;
            margin-top: 3rem;
            text-align: center;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
            }

            .card {
                margin-bottom: 0.5rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert:not(.alert-persistent)');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
    @yield('scripts')
</body>
</html>

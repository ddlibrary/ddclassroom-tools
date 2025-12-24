<!doctype html>
<html lang="en" dir="ltr">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Backup Management - {{ config('app.name') }}</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .backup-card {
            transition: transform 0.2s;
        }
        .backup-card:hover {
            transform: translateY(-2px);
        }
        .file-size {
            color: #6c757d;
            font-size: 0.9em;
        }
    </style>
</head>

<body style="padding:20px;" dir="ltr">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Backup Management</h1>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(count($backups) > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Available Backups ({{ count($backups) }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Size</th>
                                    <th>Disk</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($backups as $index => $backup)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $backup['date']->format('Y-m-d H:i:s') }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $backup['date']->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <span class="file-size">{{ number_format($backup['size'] / 1024 / 1024, 2) }} MB</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $backup['disk'] }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('backup.download', ['disk' => $backup['disk'], 'path' => urlencode($backup['path'])]) }}"
                                               class="btn btn-sm btn-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                                    <path d="M.5 9.9a.5.5 0 0 1 .5.5h2.5a.5.5 0 0 1 0 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h.5a.5.5 0 0 1 0 1H3v5h.5a.5.5 0 0 1 .5.5zM5 11.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1H5.5a.5.5 0 0 1-.5-.5zm11-4v-7a1 1 0 0 0-1-1H9.5a.5.5 0 0 0 0 1H15v7a.5.5 0 0 1-1 0zm-1 4a.5.5 0 0 1 1 0v2a1 1 0 0 1-1 1h-7a1 1 0 0 1-1-1v-2a.5.5 0 0 1 1 0v2h7v-2z"/>
                                                    <path d="M9.5 0H5a1.5 1.5 0 0 0 0 3h.5a.5.5 0 0 0 0-1H5a.5.5 0 0 1 0-1h4.5a.5.5 0 0 0 0-1zm-3 8a.5.5 0 0 0-1 0v5a.5.5 0 0 0 1 0V8zm5 0a.5.5 0 0 0-1 0v5a.5.5 0 0 0 1 0V8zM8 5a.5.5 0 0 0-1 0v8a.5.5 0 0 0 1 0V5z"/>
                                                </svg>
                                                Download
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info" role="alert">
                <h4 class="alert-heading">No Backups Found</h4>
                <p>There are no backup files available. You can create a backup by running:</p>
                <code>php artisan backup:run</code>
            </div>
        @endif
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log #{{ $log->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; color: #333; margin: 40px; }
        a { color: #0a58ca; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .level { padding: 6px 10px; border-radius: 4px; color: #fff; font-size: 12px; }
        .level.INFO { background: #198754; }
        .level.ERROR { background: #dc3545; }
        .level.WARNING { background: #ffc107; color: #000; }
        pre { background: #f0f0f0; padding: 15px; border-radius: 6px; overflow-x: auto; font-size: 13px; }
    </style>
</head>
<body>
    <a href="{{ route('channel.logs.index') }}">← Back to Logs</a>

    <div class="card">
        <div class="header">
            <h2>Log #{{ $log->id }}</h2>
            <span class="level {{ $log->level }}">{{ $log->level }}</span>
        </div>

        <p><strong>Message:</strong> {{ $log->message }}</p>
        <p><strong>Created at:</strong> {{ $log->created_at }}</p>

        <h3>Context</h3>
        <pre>{{ json_encode(json_decode($log->context, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
    </div>
</body>
</html>

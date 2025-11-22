<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Logs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            color: #333;
            margin: 40px;
        }

        h1 {
            font-size: 22px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 6px;
            overflow: hidden;
            font-size: 14px;
        }

        th,
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background: #f0f0f0;
            font-weight: 600;
            font-size: 13px;
        }

        tr:hover {
            background: #f9f9f9;
        }

        a {
            color: #0a58ca;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
        }

        .pagination a,
        .pagination span {
            margin: 0 5px;
            text-decoration: none;
            color: #0a58ca;
        }

        .pagination .active {
            font-weight: bold;
            color: #000;
        }

        /* Level badge styles */
        .level {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            white-space: nowrap;
            line-height: 1.4;
        }

        .level.INFO {
            background: #198754;
            color: #fff;
        }

        .level.ERROR {
            background: #dc3545;
            color: #fff;
        }

        .level.WARNING {
            background: #ffc107;
            color: #000;
        }

        .level.DEBUG {
            background: #6c757d;
            color: #fff;
        }

        .level.CRITICAL {
            background: #b02a37;
            color: #fff;
        }

        /* Filter form layout */
        .filter-form {
            background: white;
            padding: 12px 16px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            font-size: 14px;
        }

        .filter-form form {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .filter-form label {
            font-size: 13px;
            margin-right: 4px;
        }

        .filter-form input,
        .filter-form select {
            padding: 5px 8px;
            font-size: 13px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .filter-form button {
            background: #0a58ca;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }

        .filter-form button:hover {
            background: #084298;
        }

        .filter-form a {
            color: #555;
            font-size: 13px;
            text-decoration: none;
        }

        .filter-form a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <h1>System Logs</h1>

    <div class="filter-form">

        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">

            <!-- LEFT SIDE: FILTER FORM -->
            <form method="GET" action="{{ route('channel.logs.index') }}" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">

                <label for="level">Level:</label>
                <select name="level" id="level">
                    <option value="">All</option>
                    @foreach ($levels as $level)
                    <option value="{{ $level }}" {{ request('level') === $level ? 'selected' : '' }}>
                        {{ ucfirst(strtolower($level)) }}
                    </option>
                    @endforeach
                </select>

                <label for="from">From:</label>
                <input type="date" id="from" name="from" value="{{ request('from') }}">

                <label for="to">To:</label>
                <input type="date" id="to" name="to" value="{{ request('to') }}">

                <label for="q">Search:</label>
                <input type="text" id="q" name="q" placeholder="Message contains..." value="{{ request('q') }}"
                    style="width:180px;">

                <button type="submit">Filter</button>

                <a href="{{ route('channel.logs.index') }}" style="margin-left:10px;">Reset</a>
            </form>

            <!-- RIGHT SIDE: CLEAR LOGS -->
            <form action="{{ route('channel.logs.clear') }}" method="POST"
                onsubmit="return confirm('Are you sure you want to clear all logs?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                    style="
                background:#dc3545;
                color:white;
                border:none;
                padding:8px 14px;
                border-radius:4px;
                cursor:pointer;
                font-size:13px;">
                    Clear Logs
                </button>
            </form>

        </div>

    </div>



    <table>
        <thead>
            <tr>
                <th style="width: 60px;">ID</th>
                <th style="width: 90px;">Level</th>
                <th>Message</th>
                <th style="width: 180px;">Created</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr>
                <td>{{ $log->id }}</td>
                <td><span class="level {{ $log->level }}">{{ strtoupper($log->level) }}</span></td>
                <td>
                    <a href="{{ route('channel.logs.show', $log->id) }}">
                        {{ \Illuminate\Support\Str::limit($log->message, 80) }}
                    </a>
                </td>
                <td>{{ $log->created_at }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center; padding:15px;">No logs found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $logs->onEachSide(1)->links('simple-pagination') }}
    </div>
</body>

</html>
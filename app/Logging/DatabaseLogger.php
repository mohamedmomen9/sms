<?php

namespace App\Logging;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class DatabaseLogger extends AbstractProcessingHandler
{
    protected function write(LogRecord $record): void
    {
        try {
            if (Schema::hasTable('channel_logs')) {
                DB::table('channel_logs')->insert([
                    'level' => $record['level_name'],
                    'message' => $record['message'],
                    'context' => json_encode($record['context']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $path = storage_path('logs/' . date('Y') . '/' . date('m') . '/' . date('d'));

                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                Log::channel('db')->build([
                    'driver' => 'single',
                    'path' => "$path/laravel.log",
                    'level' => env('LOG_LEVEL', 'debug'),
                ])->log($record['level_name'], $record['message'], $record['context']);
            }
        } catch (\Throwable $e) {
            try {
                Log::channel('db')->build([
                    'driver' => 'single',
                    'path' => storage_path('logs/fallback.log'),
                ])->error('Database logging failed: ' . $e->getMessage());
            } catch (\Throwable $inner) {
            }
        }
    }
}

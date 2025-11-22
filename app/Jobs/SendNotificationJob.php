<?php

namespace App\Jobs;

use App\Services\FirebaseNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $title;
    protected $data;
    protected $body;
    protected $deviceToken;
    protected $imageUrl;

    public $tries = 3;
    public $timeout = 120;
    public $retryUntil = 3600;

    public function __construct($deviceToken, $title, $body, $imageUrl = null, $data = [])
    {
        $this->deviceToken = $deviceToken;
        $this->title = $title;
        $this->body = $body;
        $this->data = $data;
        $this->imageUrl = $imageUrl;
    }

    public function handle(FirebaseNotificationService $fcmService)
    {
        $fcmService->sendNotification($this->deviceToken, $this->title, $this->body, $this->imageUrl, $this->data);
    }
}

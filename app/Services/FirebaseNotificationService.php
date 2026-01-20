<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\ConnectionException;

class FirebaseNotificationService
{
    private $fcmUrl;
    private $accessToken;
    private $firebaseCredentials;

    public function __construct()
    {
        $this->firebaseCredentials = json_decode(file_get_contents(storage_path('app/firebase-service-account.json')), true);
        $this->accessToken = $this->getAccessToken();
        $this->fcmUrl = 'https://fcm.googleapis.com/v1/projects/' . env('FIREBASE_PROJECT_ID') . '/messages:send';
    }

    private function generateJWT()
    {
        $now = Carbon::now();
        $privateKey = $this->firebaseCredentials['private_key'];
        $clientEmail = $this->firebaseCredentials['client_email'];

        $payload = [
            'iat' => $now->timestamp,
            'exp' => $now->addMinutes(60)->timestamp,
            'aud' => 'https://oauth2.googleapis.com/token',
            'iss' => $clientEmail,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
        ];

        return JWT::encode($payload, $privateKey, 'RS256');
    }

    private function getAccessToken()
    {
        $jwt = $this->generateJWT();

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        return $response->json()['access_token'];
    }

    public function sendNotification($deviceToken, $title, $body, $imageUrl = null, $data = [])
    {
        try {
            $notification = [
                'message' => [
                    'token' => $deviceToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                        'image' => $imageUrl,
                    ],
                    'data' => $data,
                ],
            ];

            $response = Http::timeout(30)
                ->connectTimeout(10)
                ->retry(3, 100)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->fcmUrl, $notification);

            return $response->json();
        } catch (ConnectionException $e) {
            Log::channel('db')->error('FCM Connection Failed', [
                'error' => $e->getMessage(),
                'token' => $deviceToken,
            ]);
            return ['error' => 'Connection timeout'];
        } catch (RequestException $e) {
            Log::channel('db')->error('FCM Request Failed', [
                'error' => $e->getMessage(),
                'response' => $e->response?->json(),
                'token' => $deviceToken,
            ]);
            return ['error' => 'Request failed', 'message' => $e->response?->json()];
        } catch (\Exception $e) {
            Log::channel('db')->error('FCM Unexpected Error', [
                'error' => $e->getMessage(),
                'token' => $deviceToken,
            ]);
            return ['error' => 'Unexpected error'];
        }
    }

    public function sendNotificationToAll($title, $body, $imageUrl = null, $data = [])
    {
        try {
            $topic = 'all';
            $notification = [
                'message' => [
                    'topic' => $topic,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                        'image' => $imageUrl,
                    ],
                    'data' => $data,
                ],
            ];
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])
                ->post($this->fcmUrl, $notification);

            return $response->json();
        } catch (RequestException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}

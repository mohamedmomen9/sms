<?php

namespace Modules\Students\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    protected $tokenData;

    public function __construct($resource, $tokenData = null)
    {
        parent::__construct($resource);
        $this->tokenData = $tokenData;
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'student_id' => $this->student_id,
            'role' => 'student',
            'tokens' => [
                'access_token' => $this->tokenData['access_token'] ?? null,
                'refresh_token' => $this->tokenData['refresh_token'] ?? null,
                'token_type' => $this->tokenData['token_type'] ?? 'Bearer',
                'expires_in' => $this->tokenData['expires_in'] ?? null,
            ],
        ];
    }
}

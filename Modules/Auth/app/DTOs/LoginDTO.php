<?php

namespace Modules\Auth\DTOs;

class LoginDTO
{
    public function __construct(
        public string $username,
        public string $password,
        public ?string $deviceName = 'unknown_device',
        public string $role
    ) {}
}

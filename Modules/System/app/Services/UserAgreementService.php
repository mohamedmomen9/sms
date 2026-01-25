<?php

namespace Modules\System\Services;

use Modules\System\Models\UserAgreement;
use Illuminate\Database\Eloquent\Model;

class UserAgreementService
{
    public function hasAccepted(Model $user, string $type): bool
    {
        return UserAgreement::hasAccepted($user, $type);
    }

    public function accept(Model $user, string $type): UserAgreement
    {
        return UserAgreement::accept($user, $type);
    }
}

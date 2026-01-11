<?php

namespace App\Policies;

use Modules\Users\Models\User;

class UserPolicy extends BasePolicy
{
    protected string $key = 'user';
    
    public function delete(User $user, $model): bool
    {
        if ($user->id === $model->id) {
            return false;
        }
        
        return parent::delete($user, $model);
    }
}

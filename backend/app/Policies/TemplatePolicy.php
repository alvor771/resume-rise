<?php

namespace App\Policies;

use App\Models\Template;
use App\Models\User;

class TemplatePolicy
{
    public function before(User $user, string $ability): bool|null
    {
        // Allow admins everything
        if ($user->role === 'admin') {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, Template $template): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Template $template): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Template $template): bool
    {
        return $user->role === 'admin';
    }
}

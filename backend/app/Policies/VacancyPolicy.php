<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Auth\Access\HandlesAuthorization;

class VacancyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Anyone can view vacancies
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Vacancy $vacancy): bool
    {
        if ($vacancy->is_active && $vacancy->published_at && $vacancy->published_at <= now()) {
            return true; // Active and published vacancies are visible to everyone
        }

        return $user && ($user->isAdmin() || 
               ($user->isCompany() && $user->id === $vacancy->company->user_id));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isCompany();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Vacancy $vacancy): bool
    {
        return $user->isAdmin() || 
               ($user->isCompany() && $user->id === $vacancy->company->user_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Vacancy $vacancy): bool
    {
        return $user->isAdmin() || 
               ($user->isCompany() && $user->id === $vacancy->company->user_id);
    }

    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user, Vacancy $vacancy): bool
    {
        return $user->isAdmin() || 
               ($user->isCompany() && $user->id === $vacancy->company->user_id);
    }
}

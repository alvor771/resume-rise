<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Company;
use App\Models\Vacancy;
use App\Models\Template;
use App\Policies\UserPolicy;
use App\Policies\CompanyPolicy;
use App\Policies\VacancyPolicy;
use App\Policies\TemplatePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Company::class => CompanyPolicy::class,
        Vacancy::class => VacancyPolicy::class,
        Template::class => TemplatePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // You can also define additional Gates here if needed
        Gate::define('admin-only', function (User $user) {
            return $user->role === 'admin';
        });
    }
}

<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CompanyAndVacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * @return void
     */
    public function run()
    {
        // Create admin user if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'admin',
            ]
        );

        // Create some company users
        $companyUsers = User::factory()
            ->count(5)
            ->create([
                'role' => 'company',
                'password' => Hash::make('password'),
            ]);

        // Create companies for each company user
        $companyUsers->each(function ($user) {
            // Each company user can have 1-3 companies
            $companies = Company::factory()
                ->count(rand(1, 3))
                ->create([
                    'user_id' => $user->id,
                ]);

            // Create vacancies for each company
            $companies->each(function ($company) {
                // Each company can have 2-10 vacancies
                Vacancy::factory()
                    ->count(rand(2, 10))
                    ->create([
                        'company_id' => $company->id,
                    ]);
            });
        });

        // Create some additional companies managed by admin
        Company::factory()
            ->count(10)
            ->create([
                'user_id' => $admin->id,
            ])
            ->each(function ($company) {
                // Each admin company can have 1-5 vacancies
                Vacancy::factory()
                    ->count(rand(1, 5))
                    ->create([
                        'company_id' => $company->id,
                    ]);
            });

        $this->command->info('Successfully seeded companies and vacancies!');
        $this->command->info('Admin login: admin@example.com / password');
        $this->command->info('Company user logins: company[1-5]@example.com / password');
    }
}

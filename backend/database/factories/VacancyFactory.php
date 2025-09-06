<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VacancyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vacancy::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->jobTitle();
        $employmentTypes = array_keys(Vacancy::getEmploymentTypes());
        $experienceLevels = array_keys(Vacancy::getExperienceLevels());
        
        return [
            'company_id' => Company::factory(),
            'title' => $title,
            //'slug' => Str::slug($title) . '-' . $this->faker->unique()->randomNumber(5),
            'description' => $this->faker->paragraphs(3, true),
            'requirements' => $this->faker->paragraphs(5, true),
            //'responsibilities' => $this->faker->paragraphs(5, true),
            'employment_type' => $this->faker->randomElement($employmentTypes),
            'experience_level' => $this->faker->randomElement($experienceLevels),
            //'min_salary' => $this->faker->optional(0.7)->numberBetween(30000, 80000), // 70% chance of having a min salary
            /* 'max_salary' => function (array $attributes) {
                if (isset($attributes['min_salary'])) {
                    return $this->faker->numberBetween($attributes['min_salary'], $attributes['min_salary'] + 50000);
                }
                return null;
            }, */
            'location' => $this->faker->city() . ', ' . $this->faker->country(),
            //'remote_available' => $this->faker->boolean(30), // 30% chance of remote work
            'expires_at' => $this->faker->dateTimeBetween('+1 month', '+1 year'),
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
            //'is_published' => $this->faker->boolean(80), // 80% chance of being published
            'published_at' => $this->faker->dateTimeBetween('-6 months', 'now')
        ];
    }

    /**
     * Indicate that the vacancy is active.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => true,
            ];
        });
    }

    /**
     * Indicate that the vacancy is inactive.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }

    /**
     * Indicate that the vacancy is published.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function published()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_published' => true,
                'published_at' => now(),
            ];
        });
    }

    /**
     * Indicate that the vacancy is unpublished.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unpublished()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_published' => false,
                'published_at' => null,
            ];
        });
    }

    /**
     * Indicate that the vacancy is expired.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function expired()
    {
        return $this->state(function (array $attributes) {
            return [
                'expires_at' => $this->faker->dateTimeBetween('-6 months', '-1 day'),
            ];
        });
    }

    /**
     * Indicate the company of the vacancy.
     *
     * @param  int  $companyId
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forCompany($companyId)
    {
        return $this->state(function (array $attributes) use ($companyId) {
            return [
                'company_id' => $companyId,
            ];
        });
    }
}

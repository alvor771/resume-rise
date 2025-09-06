<?php

namespace Database\Factories;

use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Template>
 */
class TemplateFactory extends Factory
{
    protected $model = Template::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        $key = Str::slug($name);

        return [
            'name' => ucfirst($name),
            'key' => $key,
            'description' => $this->faker->sentence(),
            'category' => $this->faker->randomElement(['modern', 'classic', 'creative', 'minimal']),
            'version' => '1.0.0',
            'thumbnail' => $this->faker->imageUrl(640, 480, 'business', true),
            'content' => json_encode([
                'sections' => ['summary', 'experience', 'education', 'skills'],
                'styles' => ['primary' => '#111827', 'accent' => '#2563eb']
            ]),
            'is_active' => true,
            'is_public' => true,
            'is_premium' => $this->faker->boolean(20),
            'price' => $this->faker->randomElement([0, 4.99, 9.99]),
            'author_id' => User::query()->inRandomOrder()->value('id') ?? null,
        ];
    }
}

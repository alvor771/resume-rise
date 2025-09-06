<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vacancy extends Model
{
    use HasFactory;

    const EMPLOYMENT_TYPE_FULL_TIME = 'full-time';
    const EMPLOYMENT_TYPE_PART_TIME = 'part-time';
    const EMPLOYMENT_TYPE_CONTRACT = 'contract';
    const EMPLOYMENT_TYPE_FREELANCE = 'freelance';
    const EMPLOYMENT_TYPE_INTERNSHIP = 'internship';

    const EXPERIENCE_LEVEL_INTERN = 'intern';
    const EXPERIENCE_LEVEL_JUNIOR = 'junior';
    const EXPERIENCE_LEVEL_MID = 'mid';
    const EXPERIENCE_LEVEL_SENIOR = 'senior';
    const EXPERIENCE_LEVEL_LEAD = 'lead';
    const EXPERIENCE_LEVEL_EXECUTIVE = 'executive';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'title',
        'slug',
        'description',
        'requirements',
        'employment_type',
        'experience_level',
        'location',
        'is_remote',
        'salary_min',
        'salary_max',
        'salary_currency',
        'is_active',
        'published_at',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_remote' => 'boolean',
        'salary_min' => 'float',
        'salary_max' => 'float',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($vacancy) {
            if (empty($vacancy->slug)) {
                $vacancy->slug = \Illuminate\Support\Str::slug($vacancy->title);
                
                // Make sure the slug is unique
                $count = static::where('slug', 'LIKE', "{$vacancy->slug}%")->count();
                if ($count > 0) {
                    $vacancy->slug = "{$vacancy->slug}-{$count}";
                }
            }
        });

        static::updating(function ($vacancy) {
            if ($vacancy->isDirty('title') && empty($vacancy->slug)) {
                $vacancy->slug = \Illuminate\Support\Str::slug($vacancy->title);
                
                // Make sure the slug is unique
                $count = static::where('slug', 'LIKE', "{$vacancy->slug}%")
                    ->where('id', '!=', $vacancy->id)
                    ->count();
                if ($count > 0) {
                    $vacancy->slug = "{$vacancy->slug}-{$count}";
                }
            }
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'is_remote' => false,
        'is_active' => true,
        'salary_currency' => 'USD',
    ];

    /**
     * Get the company that owns the vacancy.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Scope a query to only include active vacancies.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Scope a query to only include published vacancies.
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->active();
    }

    /**
     * Get the employment types.
     */
    public static function getEmploymentTypes(): array
    {
        return [
            self::EMPLOYMENT_TYPE_FULL_TIME => 'Full Time',
            self::EMPLOYMENT_TYPE_PART_TIME => 'Part Time',
            self::EMPLOYMENT_TYPE_CONTRACT => 'Contract',
            self::EMPLOYMENT_TYPE_FREELANCE => 'Freelance',
            self::EMPLOYMENT_TYPE_INTERNSHIP => 'Internship',
        ];
    }

    /**
     * Get the experience levels.
     */
    public static function getExperienceLevels(): array
    {
        return [
            self::EXPERIENCE_LEVEL_INTERN => 'Intern',
            self::EXPERIENCE_LEVEL_JUNIOR => 'Junior',
            self::EXPERIENCE_LEVEL_MID => 'Mid Level',
            self::EXPERIENCE_LEVEL_SENIOR => 'Senior',
            self::EXPERIENCE_LEVEL_LEAD => 'Lead',
            self::EXPERIENCE_LEVEL_EXECUTIVE => 'Executive',
        ];
    }
}

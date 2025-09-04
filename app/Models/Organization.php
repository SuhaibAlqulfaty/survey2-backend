<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'logo',
        'website',
        'industry',
        'size',
        'country',
        'timezone',
        'settings',
        'credit_balance',
        'plan',
        'plan_expires_at',
        'is_active'
    ];

    protected $casts = [
        'settings' => 'array',
        'credit_balance' => 'decimal:2',
        'plan_expires_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($organization) {
            // Only set slug if the column exists in the database
            try {
                if (empty($organization->slug) && \Schema::hasColumn('organizations', 'slug')) {
                    $organization->slug = \Illuminate\Support\Str::slug($organization->name);
                    
                    // Ensure uniqueness
                    $originalSlug = $organization->slug;
                    $counter = 1;
                    while (static::where('slug', $organization->slug)->exists()) {
                        $organization->slug = $originalSlug . '-' . $counter;
                        $counter++;
                    }
                }
            } catch (\Exception $e) {
                // If slug column doesn't exist, continue without setting it
                \Log::info('Slug column not found in organizations table, skipping slug generation');
            }
        });
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function surveys()
    {
        return $this->hasMany(Survey::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}

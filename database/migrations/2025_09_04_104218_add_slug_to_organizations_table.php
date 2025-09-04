<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            if (!Schema::hasColumn('organizations', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('name');
            }
        });
        
        // Update existing organizations to have slugs
        $organizations = \App\Models\Organization::whereNull('slug')->get();
        foreach ($organizations as $organization) {
            $slug = \Illuminate\Support\Str::slug($organization->name);
            
            // Ensure uniqueness
            $originalSlug = $slug;
            $counter = 1;
            while (\App\Models\Organization::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $organization->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            if (Schema::hasColumn('organizations', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }
};

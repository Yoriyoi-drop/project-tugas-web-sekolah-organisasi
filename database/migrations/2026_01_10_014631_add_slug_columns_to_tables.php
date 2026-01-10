<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add slug to posts
        Schema::table('posts', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
        });

        // Add slug to organizations
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        // Add slug to activities
        Schema::table('activities', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
        });

        // Populate existing records
        $this->populateSlugs('posts', 'title');
        $this->populateSlugs('organizations', 'name');
        $this->populateSlugs('activities', 'title');

        // Make slug non-nullable and unique after population
        Schema::table('posts', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
        Schema::table('activities', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }

    private function populateSlugs($table, $sourceColumn)
    {
        $records = \Illuminate\Support\Facades\DB::table($table)->get();
        foreach ($records as $record) {
            $slug = \Illuminate\Support\Str::slug($record->$sourceColumn);
            // Ensure uniqueness simply for existing data if needed, but for now assuming uniqueness of title/name or appending ID
            $originalSlug = $slug;
            $count = 1;
            while (\Illuminate\Support\Facades\DB::table($table)->where('slug', $slug)->where('id', '!=', $record->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
            
            \Illuminate\Support\Facades\DB::table($table)
                ->where('id', $record->id)
                ->update(['slug' => $slug]);
        }
    }
};

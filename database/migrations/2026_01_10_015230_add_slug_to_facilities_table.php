<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        // Populate existing records
        $records = \Illuminate\Support\Facades\DB::table('facilities')->get();
        foreach ($records as $record) {
            $slug = \Illuminate\Support\Str::slug($record->name);
            $originalSlug = $slug;
            $count = 1;
            while (\Illuminate\Support\Facades\DB::table('facilities')->where('slug', $slug)->where('id', '!=', $record->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
            
            \Illuminate\Support\Facades\DB::table('facilities')
                ->where('id', $record->id)
                ->update(['slug' => $slug]);
        }

        Schema::table('facilities', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};

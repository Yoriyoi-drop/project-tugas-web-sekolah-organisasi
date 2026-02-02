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
        Schema::table('organization_activities', function (Blueprint $table) {
            // Kita tidak mengubah sifat kolom, cukup isi data yang hilang
            // Tapi karena kita sudah menambahkan nilai di test, ini seharusnya tidak diperlukan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organization_activities', function (Blueprint $table) {
            //
        });
    }
};

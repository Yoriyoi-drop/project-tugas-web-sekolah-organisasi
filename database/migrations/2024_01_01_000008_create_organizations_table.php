<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('tagline')->nullable();
            $table->text('description');
            $table->string('icon');
            $table->string('color')->default('primary');
            $table->json('tags')->nullable();
            $table->json('programs')->nullable();
            $table->json('leadership')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->index(['is_active', 'order']);
            $table->index('type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('organizations');
    }
}

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('excerpt');
            $table->longText('content');
            $table->string('icon')->nullable();
            $table->string('category');
            $table->string('color')->default('primary');
            $table->string('author')->default('Admin');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            $table->index(['is_published', 'is_featured']);
            $table->index(['category', 'is_published']);
            $table->index('published_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
}

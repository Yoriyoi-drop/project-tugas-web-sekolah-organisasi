<?php

namespace Tests\Unit;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_has_fillable_attributes()
    {
        $fillable = ['name'];
        $this->assertEquals($fillable, (new Category())->getFillable());
    }

    public function test_create_category()
    {
        $category = Category::create([
            'name' => 'Technology'
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Technology'
        ]);

        $this->assertEquals('Technology', $category->name);
    }

    public function test_update_category()
    {
        $category = Category::factory()->create([
            'name' => 'Technology'
        ]);

        $updated = $category->update([
            'name' => 'Science'
        ]);

        $this->assertTrue($updated);
        $this->assertDatabaseHas('categories', [
            'name' => 'Science'
        ]);
        $this->assertDatabaseMissing('categories', [
            'name' => 'Technology'
        ]);
    }

    public function test_delete_category()
    {
        $category = Category::factory()->create([
            'name' => 'Technology'
        ]);

        $deleted = $category->delete();

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('categories', [
            'name' => 'Technology'
        ]);
    }
}
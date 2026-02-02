<?php

namespace Tests\Unit;

use App\Models\Ability;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AbilityModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_ability_has_fillable_attributes()
    {
        $fillable = ['name', 'slug', 'description'];
        $this->assertEquals($fillable, (new Ability())->getFillable());
    }

    public function test_ability_belongs_to_many_roles()
    {
        $ability = Ability::create([
            'name' => 'Edit Posts',
            'slug' => 'edit-posts',
            'description' => 'Allow editing posts'
        ]);
        $role = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Administrator role'
        ]);

        $ability->roles()->attach($role);

        $this->assertTrue($ability->roles->contains($role));
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $ability->roles);
    }

    public function test_create_ability()
    {
        $ability = Ability::create([
            'name' => 'Edit Posts',
            'slug' => 'edit-posts',
            'description' => 'Allow editing posts'
        ]);

        $this->assertDatabaseHas('abilities', [
            'name' => 'Edit Posts',
            'slug' => 'edit-posts',
            'description' => 'Allow editing posts'
        ]);

        $this->assertEquals('Edit Posts', $ability->name);
        $this->assertEquals('edit-posts', $ability->slug);
        $this->assertEquals('Allow editing posts', $ability->description);
    }

    public function test_update_ability()
    {
        $ability = Ability::create([
            'name' => 'Edit Posts',
            'slug' => 'edit-posts',
            'description' => 'Allow editing posts'
        ]);

        $updated = $ability->update([
            'name' => 'Delete Posts',
            'slug' => 'delete-posts',
            'description' => 'Allow deleting posts'
        ]);

        $this->assertTrue($updated);
        $this->assertDatabaseHas('abilities', [
            'name' => 'Delete Posts',
            'slug' => 'delete-posts',
            'description' => 'Allow deleting posts'
        ]);
        $this->assertDatabaseMissing('abilities', [
            'name' => 'Edit Posts'
        ]);
    }

    public function test_delete_ability()
    {
        $ability = Ability::create([
            'name' => 'Edit Posts',
            'slug' => 'edit-posts',
            'description' => 'Allow editing posts'
        ]);

        $deleted = $ability->delete();

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('abilities', [
            'name' => 'Edit Posts'
        ]);
    }
}
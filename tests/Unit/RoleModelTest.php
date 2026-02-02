<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Models\User;
use App\Models\Ability;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleModelTest extends TestCase
{
    use RefreshDatabase;

    private function createRole($overrides = [])
    {
        $role = new Role();
        $role->name = $overrides['name'] ?? 'Test Role';
        $role->slug = $overrides['slug'] ?? 'test-role';
        $role->description = $overrides['description'] ?? 'Test description';
        $role->save();
        
        return $role;
    }

    public function test_role_has_fillable_attributes()
    {
        $data = [
            'name' => 'Administrator',
            'slug' => 'administrator',
            'description' => 'System administrator role'
        ];

        $role = Role::create($data);

        $this->assertEquals($data['name'], $role->name);
        $this->assertEquals($data['slug'], $role->slug);
        $this->assertEquals($data['description'], $role->description);
    }

    public function test_role_belongs_to_many_users()
    {
        $role = $this->createRole();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $role->users()->attach([$user1->id, $user2->id]);

        $this->assertCount(2, $role->users);
        $this->assertInstanceOf(User::class, $role->users->first());
        $this->assertTrue($role->users->contains($user1));
        $this->assertTrue($role->users->contains($user2));
    }

    public function test_role_belongs_to_many_abilities()
    {
        $role = $this->createRole();
        $ability1 = new Ability();
        $ability1->name = 'Edit Posts';
        $ability1->slug = 'edit-posts';
        $ability1->description = 'Can edit posts';
        $ability1->save();
        
        $ability2 = new Ability();
        $ability2->name = 'Delete Posts';
        $ability2->slug = 'delete-posts';
        $ability2->description = 'Can delete posts';
        $ability2->save();

        $role->abilities()->attach([$ability1->id, $ability2->id]);

        $this->assertCount(2, $role->abilities);
        $this->assertInstanceOf(Ability::class, $role->abilities->first());
        $this->assertTrue($role->abilities->contains($ability1));
        $this->assertTrue($role->abilities->contains($ability2));
    }

    public function test_has_ability_method_with_existing_ability()
    {
        $role = $this->createRole();
        $ability = new Ability();
        $ability->name = 'Edit Posts';
        $ability->slug = 'edit-posts';
        $ability->description = 'Can edit posts';
        $ability->save();

        $role->abilities()->attach($ability->id);

        $this->assertTrue($role->hasAbility('edit-posts'));
    }

    public function test_has_ability_method_with_non_existing_ability()
    {
        $role = $this->createRole();
        $ability = new Ability();
        $ability->name = 'Edit Posts';
        $ability->slug = 'edit-posts';
        $ability->description = 'Can edit posts';
        $ability->save();

        $role->abilities()->attach($ability->id);

        $this->assertFalse($role->hasAbility('delete-posts'));
    }

    public function test_has_ability_method_with_no_abilities()
    {
        $role = $this->createRole();

        $this->assertFalse($role->hasAbility('edit-posts'));
    }

    public function test_role_can_be_created()
    {
        $role = $this->createRole();

        $this->assertInstanceOf(Role::class, $role);
        $this->assertDatabaseHas('roles', ['id' => $role->id]);
    }

    public function test_role_can_be_found()
    {
        $role = $this->createRole();
        
        $found = Role::find($role->id);
        
        $this->assertInstanceOf(Role::class, $found);
        $this->assertEquals($role->id, $found->id);
    }

    public function test_role_can_be_updated()
    {
        $role = $this->createRole(['name' => 'Original Name']);
        
        $role->name = 'Updated Name';
        $role->save();
        
        $this->assertEquals('Updated Name', $role->fresh()->name);
    }

    public function test_role_can_be_deleted()
    {
        $role = $this->createRole();
        
        $role->delete();
        
        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }

    public function test_role_query_scopes()
    {
        $this->createRole(['name' => 'Admin', 'slug' => 'admin']);
        $this->createRole(['name' => 'User', 'slug' => 'user']);
        $this->createRole(['name' => 'Moderator', 'slug' => 'moderator']);

        $roles = Role::where('name', 'like', '%Ad%')->get();

        $this->assertCount(1, $roles);
        $this->assertEquals('Admin', $roles->first()->name);
    }

    public function test_role_mass_assignment()
    {
        $data = [
            'name' => 'Test Role',
            'slug' => 'test-role',
            'description' => 'Test description'
        ];
        
        $role = new Role($data);
        
        $this->assertEquals('Test Role', $role->name);
        $this->assertEquals('test-role', $role->slug);
        $this->assertEquals('Test description', $role->description);
    }

    public function test_role_user_relationship_pivot()
    {
        $role = $this->createRole();
        $user = User::factory()->create();

        $role->users()->attach($user->id, ['created_at' => now(), 'updated_at' => now()]);

        $this->assertTrue($role->users->contains($user));
    }

    public function test_role_ability_relationship_pivot()
    {
        $role = $this->createRole();
        $ability = new Ability();
        $ability->name = 'Edit Posts';
        $ability->slug = 'edit-posts';
        $ability->description = 'Can edit posts';
        $ability->save();

        $role->abilities()->attach($ability->id, ['created_at' => now(), 'updated_at' => now()]);

        $this->assertTrue($role->abilities->contains($ability));
    }

    public function test_role_multiple_abilities_check()
    {
        $role = $this->createRole();
        
        $ability1 = new Ability();
        $ability1->name = 'Edit Posts';
        $ability1->slug = 'edit-posts';
        $ability1->description = 'Can edit posts';
        $ability1->save();
        
        $ability2 = new Ability();
        $ability2->name = 'Delete Posts';
        $ability2->slug = 'delete-posts';
        $ability2->description = 'Can delete posts';
        $ability2->save();
        
        $ability3 = new Ability();
        $ability3->name = 'Publish Posts';
        $ability3->slug = 'publish-posts';
        $ability3->description = 'Can publish posts';
        $ability3->save();

        $role->abilities()->attach([$ability1->id, $ability2->id]);

        $this->assertTrue($role->hasAbility('edit-posts'));
        $this->assertTrue($role->hasAbility('delete-posts'));
        $this->assertFalse($role->hasAbility('publish-posts'));
    }

    public function test_role_with_no_users()
    {
        $role = $this->createRole();

        $this->assertCount(0, $role->users);
        $this->assertEmpty($role->users->toArray());
    }

    public function test_role_with_no_abilities()
    {
        $role = $this->createRole();

        $this->assertCount(0, $role->abilities);
        $this->assertEmpty($role->abilities->toArray());
    }

    public function test_role_unique_slug_constraint()
    {
        $this->createRole(['slug' => 'admin']);
        
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        $this->createRole(['slug' => 'admin']);
    }

    public function test_role_search_by_name()
    {
        $this->createRole(['name' => 'Administrator', 'slug' => 'administrator']);
        $this->createRole(['name' => 'Super Administrator', 'slug' => 'super-administrator']);
        $this->createRole(['name' => 'User', 'slug' => 'user']);

        $adminRoles = Role::where('name', 'like', '%Administrator%')->get();

        $this->assertCount(2, $adminRoles);
    }

    public function test_role_search_by_description()
    {
        $this->createRole(['description' => 'Can manage all system settings', 'slug' => 'manager', 'name' => 'Manager']);
        $this->createRole(['description' => 'Can view system settings', 'slug' => 'viewer', 'name' => 'Viewer']);
        $this->createRole(['description' => 'Basic user permissions', 'slug' => 'basic', 'name' => 'Basic']);

        $managerRoles = Role::where('description', 'like', '%manage%')->get();

        $this->assertCount(1, $managerRoles);
    }

    public function test_role_ability_sync()
    {
        $role = $this->createRole();
        
        $ability1 = new Ability();
        $ability1->name = 'Edit Posts';
        $ability1->slug = 'edit-posts';
        $ability1->description = 'Can edit posts';
        $ability1->save();
        
        $ability2 = new Ability();
        $ability2->name = 'Delete Posts';
        $ability2->slug = 'delete-posts';
        $ability2->description = 'Can delete posts';
        $ability2->save();
        
        $ability3 = new Ability();
        $ability3->name = 'Publish Posts';
        $ability3->slug = 'publish-posts';
        $ability3->description = 'Can publish posts';
        $ability3->save();

        // Initial abilities
        $role->abilities()->attach([$ability1->id, $ability2->id]);
        $this->assertTrue($role->hasAbility('edit-posts'));
        $this->assertTrue($role->hasAbility('delete-posts'));

        // Sync to new abilities
        $role->abilities()->sync([$ability2->id, $ability3->id]);
        
        $this->assertFalse($role->hasAbility('edit-posts'));
        $this->assertTrue($role->hasAbility('delete-posts'));
        $this->assertTrue($role->hasAbility('publish-posts'));
    }

    public function test_role_user_sync()
    {
        $role = $this->createRole();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        // Initial users
        $role->users()->attach([$user1->id, $user2->id]);
        $this->assertTrue($role->users->contains($user1));
        $this->assertTrue($role->users->contains($user2));

        // Sync to new users
        $role->users()->sync([$user2->id, $user3->id]);
        
        // Refresh the relationship
        $role->load('users');
        
        $this->assertFalse($role->users->contains($user1));
        $this->assertTrue($role->users->contains($user2));
        $this->assertTrue($role->users->contains($user3));
    }
}

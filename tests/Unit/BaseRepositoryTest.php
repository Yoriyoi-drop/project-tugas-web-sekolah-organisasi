<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

// Membuat kelas turunan dari BaseRepository untuk keperluan pengujian
class TestBaseRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}

class BaseRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $repository;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->repository = new TestBaseRepository($this->user);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_all_returns_all_models()
    {
        User::factory()->count(3)->create();
        
        $result = $this->repository->all();
        
        $this->assertCount(4, $result); // 3 baru + 1 dari setUp
        $this->assertEquals(get_class($result), 'Illuminate\Database\Eloquent\Collection');
    }

    public function test_find_returns_model_by_id()
    {
        $found = $this->repository->find($this->user->id);
        
        $this->assertInstanceOf(User::class, $found);
        $this->assertEquals($this->user->id, $found->id);
    }

    public function test_find_returns_null_if_not_found()
    {
        $result = $this->repository->find(999999);
        
        $this->assertNull($result);
    }

    public function test_findOrFail_returns_model_by_id()
    {
        $found = $this->repository->findOrFail($this->user->id);
        
        $this->assertInstanceOf(User::class, $found);
        $this->assertEquals($this->user->id, $found->id);
    }

    public function test_create_creates_new_model()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ];
        
        $created = $this->repository->create($data);
        
        $this->assertInstanceOf(User::class, $created);
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    public function test_update_updates_existing_model()
    {
        $newData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ];
        
        $updated = $this->repository->update($this->user->id, $newData);
        
        $this->assertInstanceOf(User::class, $updated);
        $this->assertEquals('Updated Name', $updated->name);
        $this->assertEquals('updated@example.com', $updated->email);
        
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    public function test_delete_removes_model()
    {
        $result = $this->repository->delete($this->user->id);
        
        $this->assertTrue($result);
        $this->assertDatabaseMissing('users', [
            'id' => $this->user->id,
        ]);
    }

    public function test_paginate_returns_paginator()
    {
        User::factory()->count(20)->create();
        
        $result = $this->repository->paginate(10);
        
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertCount(10, $result->items());
        $this->assertEquals(21, $result->total()); // 20 baru + 1 dari setUp
    }

    public function test_where_filters_results()
    {
        $user1 = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
        ]);
        
        $user2 = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
        ]);
        
        $result = $this->repository->where('name', 'John Doe')->get();
        
        $this->assertCount(1, $result);
        $this->assertEquals('John Doe', $result->first()->name);
    }

    public function test_whereIn_filters_results()
    {
        $user1 = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
        ]);
        
        $user2 = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
        ]);
        
        $user3 = User::create([
            'name' => 'Bob Smith',
            'email' => 'bob@example.com',
            'password' => bcrypt('password'),
        ]);
        
        $ids = [$user1->id, $user2->id];
        $result = $this->repository->whereIn('id', $ids)->get();
        
        $this->assertCount(2, $result);
        $this->assertTrue($result->contains($user1));
        $this->assertTrue($result->contains($user2));
        $this->assertFalse($result->contains($user3));
    }

    public function test_orderBy_sorts_results()
    {
        $user1 = User::create([
            'name' => 'Zachary',
            'email' => 'zach@example.com',
            'password' => bcrypt('password'),
        ]);

        $user2 = User::create([
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'password' => bcrypt('password'),
        ]);

        $result = $this->repository->orderBy('name', 'asc')->get()->sortBy('name');

        $this->assertEquals('Alice', $result->first()->name);
        $this->assertEquals('Zachary', $result->last()->name);
    }
}
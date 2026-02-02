<?php

namespace Tests\Unit;

use App\Models\Facility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FacilityModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_facility_has_fillable_attributes()
    {
        $fillable = [
            'name',
            'slug',
            'icon',
            'image',
            'description',
            'category',
            'capacity',
            'location',
            'status',
            'features',
            'contact_person',
            'operating_hours',
            'order',
            'is_active',
        ];
        $this->assertEquals($fillable, (new Facility())->getFillable());
    }

    public function test_facility_has_casts()
    {
        $facility = Facility::create([
            'name' => 'Meeting Room A',
            'description' => 'A spacious meeting room',
            'category' => 'meeting',
            'capacity' => 20,
            'location' => 'Building A, Floor 2',
            'status' => 'available',
            'features' => ['projector', 'whiteboard'],
            'contact_person' => 'John Doe',
            'operating_hours' => '08:00-17:00',
            'order' => 1,
            'is_active' => true,
        ]);

        $this->assertIsBool($facility->is_active);
        $this->assertTrue($facility->is_active);
        
        $this->assertIsArray($facility->features);
        $this->assertEquals(['projector', 'whiteboard'], $facility->features);
        
        $this->assertIsInt($facility->capacity);
        $this->assertEquals(20, $facility->capacity);
        
        $this->assertIsInt($facility->order);
        $this->assertEquals(1, $facility->order);
    }

    public function test_active_scope()
    {
        Facility::create([
            'name' => 'Active Facility',
            'description' => 'An active facility',
            'category' => 'meeting',
            'is_active' => true,
        ]);

        Facility::create([
            'name' => 'Inactive Facility',
            'description' => 'An inactive facility',
            'category' => 'meeting',
            'is_active' => false,
        ]);

        $activeFacilities = Facility::active()->get();
        
        $this->assertCount(1, $activeFacilities);
        $this->assertEquals('Active Facility', $activeFacilities->first()->name);
    }

    public function test_ordered_scope()
    {
        Facility::create([
            'name' => 'Third Facility',
            'description' => 'Third facility',
            'category' => 'meeting',
            'order' => 3,
            'is_active' => true,
        ]);

        Facility::create([
            'name' => 'First Facility',
            'description' => 'First facility',
            'category' => 'meeting',
            'order' => 1,
            'is_active' => true,
        ]);

        Facility::create([
            'name' => 'Second Facility',
            'description' => 'Second facility',
            'category' => 'meeting',
            'order' => 2,
            'is_active' => true,
        ]);

        $orderedFacilities = Facility::ordered()->get();
        
        $this->assertCount(3, $orderedFacilities);
        $this->assertEquals('First Facility', $orderedFacilities[0]->name);
        $this->assertEquals('Second Facility', $orderedFacilities[1]->name);
        $this->assertEquals('Third Facility', $orderedFacilities[2]->name);
    }

    public function test_slug_generation_on_create()
    {
        $facility = Facility::create([
            'name' => 'Conference Room',
            'description' => 'A conference room',
            'category' => 'meeting',
            'is_active' => true,
        ]);

        $this->assertNotNull($facility->slug);
        $this->assertStringContainsString('conference-room-', $facility->slug);
        $this->assertEquals(5, strlen(substr($facility->slug, strrpos($facility->slug, '-') + 1)));
    }

    public function test_slug_regeneration_on_update()
    {
        $facility = Facility::create([
            'name' => 'Original Name',
            'description' => 'Original description',
            'category' => 'meeting',
            'is_active' => true,
        ]);

        $originalSlug = $facility->slug;

        $facility->update([
            'name' => 'Updated Name'
        ]);

        $this->assertNotEquals($originalSlug, $facility->fresh()->slug);
        $this->assertStringContainsString('updated-name-', $facility->fresh()->slug);
    }

    public function test_slug_not_regenerated_when_name_unchanged()
    {
        $facility = Facility::create([
            'name' => 'Original Name',
            'description' => 'Original description',
            'category' => 'meeting',
            'is_active' => true,
        ]);

        $originalSlug = $facility->slug;

        $facility->update([
            'description' => 'Updated description'
        ]);

        $this->assertEquals($originalSlug, $facility->fresh()->slug);
    }

    public function test_get_route_key_name()
    {
        $facility = new Facility();
        $this->assertEquals('slug', $facility->getRouteKeyName());
    }

    public function test_create_facility()
    {
        $facility = Facility::create([
            'name' => 'Test Facility',
            'description' => 'A test facility',
            'category' => 'meeting',
            'capacity' => 10,
            'location' => 'Building A',
            'status' => 'available',
            'features' => ['wifi', 'projector'],
            'contact_person' => 'John Doe',
            'operating_hours' => '09:00-18:00',
            'order' => 1,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('facilities', [
            'name' => 'Test Facility',
            'category' => 'meeting',
            'is_active' => true,
        ]);

        $this->assertEquals('Test Facility', $facility->name);
        $this->assertEquals('meeting', $facility->category);
        $this->assertTrue($facility->is_active);
    }

    public function test_update_facility()
    {
        $facility = Facility::create([
            'name' => 'Old Name',
            'description' => 'Old description',
            'category' => 'meeting',
            'is_active' => true,
        ]);

        $updated = $facility->update([
            'name' => 'New Name',
            'is_active' => false
        ]);

        $this->assertTrue($updated);
        $this->assertDatabaseHas('facilities', [
            'name' => 'New Name',
            'is_active' => false
        ]);
        $this->assertDatabaseMissing('facilities', [
            'name' => 'Old Name'
        ]);
    }

    public function test_delete_facility()
    {
        $facility = Facility::create([
            'name' => 'To Be Deleted',
            'description' => 'This will be deleted',
            'category' => 'meeting',
            'is_active' => true,
        ]);

        $deleted = $facility->delete();

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('facilities', [
            'name' => 'To Be Deleted'
        ]);
    }
}
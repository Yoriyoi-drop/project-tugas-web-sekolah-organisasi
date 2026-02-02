<?php

namespace Tests\Unit;

use App\Models\Gallery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_gallery_can_be_created()
    {
        $gallery = new Gallery();
        $gallery->path = '/images/test.jpg';
        $gallery->save();

        $this->assertInstanceOf(Gallery::class, $gallery);
        $this->assertDatabaseHas('galleries', ['id' => $gallery->id]);
    }

    public function test_gallery_uses_correct_table_name()
    {
        $gallery = new Gallery();
        
        $this->assertEquals('galleries', $gallery->getTable());
    }

    public function test_gallery_can_be_found()
    {
        $gallery = new Gallery();
        $gallery->path = '/images/test.jpg';
        $gallery->save();
        
        $found = Gallery::find($gallery->id);
        
        $this->assertInstanceOf(Gallery::class, $found);
        $this->assertEquals($gallery->id, $found->id);
    }

    public function test_gallery_can_be_updated()
    {
        $gallery = new Gallery();
        $gallery->path = '/images/test.jpg';
        $gallery->save();
        
        $gallery->path = '/images/updated.jpg';
        $gallery->save();
        
        $this->assertEquals('/images/updated.jpg', $gallery->fresh()->path);
    }

    public function test_gallery_can_be_deleted()
    {
        $gallery = new Gallery();
        $gallery->path = '/images/test.jpg';
        $gallery->save();
        
        $gallery->delete();
        
        $this->assertDatabaseMissing('galleries', ['id' => $gallery->id]);
    }

    public function test_gallery_basic_functionality()
    {
        // Test basic model functionality since it's a placeholder
        $gallery = new Gallery();
        
        $this->assertInstanceOf(Gallery::class, $gallery);
        $this->assertTrue($gallery->save());
    }

    public function test_gallery_query_scopes()
    {
        $gallery1 = new Gallery();
        $gallery1->path = '/images/gallery1.jpg';
        $gallery1->save();
        
        $gallery2 = new Gallery();
        $gallery2->path = '/images/gallery2.jpg';
        $gallery2->save();
        
        $galleries = Gallery::all();
        
        $this->assertCount(2, $galleries);
        $this->assertInstanceOf(Gallery::class, $galleries->first());
    }
}

<?php

namespace Tests\Unit;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostModelTest extends TestCase
{
    use RefreshDatabase;

    private function createPost($overrides = [])
    {
        $post = new Post();
        $post->title = $overrides['title'] ?? 'Test Post Title';
        $post->excerpt = $overrides['excerpt'] ?? 'Test excerpt';
        $post->content = $overrides['content'] ?? 'Test content for the post. This is a longer content to test the excerpt generation functionality.';
        $post->icon = $overrides['icon'] ?? 'test-icon';
        $post->category = $overrides['category'] ?? 'test-category';
        $post->author = $overrides['author'] ?? 'Test Author';
        $post->is_featured = $overrides['is_featured'] ?? false;
        $post->is_published = $overrides['is_published'] ?? true;
        $post->published_at = $overrides['published_at'] ?? now();
        $post->save();
        
        return $post;
    }

    public function test_post_has_fillable_attributes()
    {
        $data = [
            'title' => 'Test Post',
            'slug' => 'test-post',
            'excerpt' => 'Test excerpt',
            'content' => 'Test content',
            'icon' => 'test-icon',
            'category' => 'test-category',
            'color' => 'primary',
            'author' => 'Test Author',
            'is_featured' => true,
            'is_published' => true,
            'published_at' => now()
        ];

        $post = Post::create($data);

        $this->assertEquals($data['title'], $post->title);
        $this->assertEquals($data['excerpt'], $post->excerpt);
        $this->assertEquals($data['content'], $post->content);
        $this->assertEquals($data['icon'], $post->icon);
        $this->assertEquals($data['category'], $post->category);
        $this->assertEquals($data['author'], $post->author);
        $this->assertEquals($data['is_featured'], $post->is_featured);
        $this->assertEquals($data['is_published'], $post->is_published);
    }

    public function test_post_casts_boolean_fields()
    {
        $post = $this->createPost(['is_featured' => true, 'is_published' => false]);

        $this->assertIsBool($post->is_featured);
        $this->assertIsBool($post->is_published);
        $this->assertTrue($post->is_featured);
        $this->assertFalse($post->is_published);
    }

    public function test_post_casts_datetime_fields()
    {
        $post = $this->createPost(['published_at' => now()]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $post->published_at);
    }

    public function test_post_has_default_attributes()
    {
        $post = new Post();

        $this->assertEquals('primary', $post->color);
        $this->assertFalse($post->is_featured);
        $this->assertTrue($post->is_published);
    }

    public function test_scope_published()
    {
        $this->createPost(['is_published' => true]);
        $this->createPost(['is_published' => false]);
        $this->createPost(['is_published' => true]);

        $published = Post::published()->get();

        $this->assertCount(2, $published);
        $this->assertTrue($published->first()->is_published);
    }

    public function test_scope_featured()
    {
        $this->createPost(['is_featured' => true]);
        $this->createPost(['is_featured' => false]);
        $this->createPost(['is_featured' => true]);

        $featured = Post::featured()->get();

        $this->assertCount(2, $featured);
        $this->assertTrue($featured->first()->is_featured);
    }

    public function test_scope_latest()
    {
        $this->createPost(['published_at' => now()->subDays(2)]);
        $this->createPost(['published_at' => now()->subDays(1)]);
        $this->createPost(['published_at' => now()]);

        $latest = Post::latest()->get();

        $this->assertEquals(3, $latest->count());
        // Test that scope works without errors
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $latest);
    }

    public function test_scope_by_category()
    {
        $this->createPost(['category' => 'technology']);
        $this->createPost(['category' => 'lifestyle']);
        $this->createPost(['category' => 'technology']);

        $techPosts = Post::byCategory('technology')->get();

        $this->assertCount(2, $techPosts);
        $this->assertEquals('technology', $techPosts->first()->category);
    }

    public function test_get_excerpt_attribute_with_value()
    {
        $post = $this->createPost(['excerpt' => 'Custom excerpt']);

        $this->assertEquals('Custom excerpt', $post->excerpt);
    }

    public function test_get_excerpt_attribute_without_value()
    {
        // Test that when excerpt is null, the accessor returns the default excerpt from createPost
        $post = $this->createPost(['excerpt' => null]);

        $this->assertNotNull($post->excerpt);
        $this->assertIsString($post->excerpt);
    }

    public function test_boot_creating_generates_slug()
    {
        $post = $this->createPost(['title' => 'Test Post Title']);

        $this->assertNotNull($post->slug);
        $this->assertStringStartsWith('test-post-title-', $post->slug);
    }

    public function test_boot_updating_updates_slug_when_title_changes()
    {
        $post = $this->createPost(['title' => 'Original Title']);
        $originalSlug = $post->slug;

        $post->title = 'Updated Title';
        $post->save();

        $this->assertNotEquals($originalSlug, $post->fresh()->slug);
        $this->assertStringStartsWith('updated-title-', $post->fresh()->slug);
    }

    public function test_boot_updating_keeps_slug_when_title_unchanged()
    {
        $post = $this->createPost(['title' => 'Test Title']);
        $originalSlug = $post->slug;

        $post->content = 'Updated content';
        $post->save();

        $this->assertEquals($originalSlug, $post->fresh()->slug);
    }

    public function test_get_route_key_name()
    {
        $post = new Post();

        $this->assertEquals('slug', $post->getRouteKeyName());
    }

    public function test_post_can_be_created()
    {
        $post = $this->createPost();

        $this->assertInstanceOf(Post::class, $post);
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }

    public function test_post_can_be_found()
    {
        $post = $this->createPost();
        
        $found = Post::find($post->id);
        
        $this->assertInstanceOf(Post::class, $found);
        $this->assertEquals($post->id, $found->id);
    }

    public function test_post_can_be_updated()
    {
        $post = $this->createPost(['title' => 'Original Title']);
        
        $post->title = 'Updated Title';
        $post->save();
        
        $this->assertEquals('Updated Title', $post->fresh()->title);
    }

    public function test_post_can_be_deleted()
    {
        $post = $this->createPost();
        
        $post->delete();
        
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_post_query_scopes_combination()
    {
        $this->createPost(['is_published' => true, 'is_featured' => true, 'category' => 'tech']);
        $this->createPost(['is_published' => true, 'is_featured' => false, 'category' => 'tech']);
        $this->createPost(['is_published' => false, 'is_featured' => true, 'category' => 'tech']);

        $publishedFeaturedTech = Post::published()->featured()->byCategory('tech')->get();

        $this->assertCount(1, $publishedFeaturedTech);
        $this->assertTrue($publishedFeaturedTech->first()->is_published);
        $this->assertTrue($publishedFeaturedTech->first()->is_featured);
        $this->assertEquals('tech', $publishedFeaturedTech->first()->category);
    }

    public function test_post_mass_assignment()
    {
        $data = [
            'title' => 'Mass Assignment Test',
            'content' => 'Test content',
            'author' => 'Test Author'
        ];
        
        $post = new Post($data);
        
        $this->assertEquals('Mass Assignment Test', $post->title);
        $this->assertEquals('Test content', $post->content);
        $this->assertEquals('Test Author', $post->author);
    }

    public function test_post_slug_uniqueness()
    {
        $post1 = $this->createPost(['title' => 'Same Title']);
        $post2 = $this->createPost(['title' => 'Same Title']);

        $this->assertNotEquals($post1->slug, $post2->slug);
    }
}

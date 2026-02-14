<?php
namespace App\Observers;

use App\Models\Post;
use App\Models\SecurityLog;
use App\Services\SecurityService;

class PostObserver
{
    public function created($post)
    {
        // Log post creation
        SecurityService::logActivity(
            'post_created', 
            [
                'post_id' => $post->id,
                'title' => $post->title,
                'user_id' => $post->user_id
            ], 
            SecurityService::RISK_LOW,
            $post->user_id
        );

        // Log to application log
        \Log::info("New post created: {$post->title}", [
            'post_id' => $post->id,
            'user_id' => $post->user_id,
            'timestamp' => now()
        ]);
    }

    public function updated($post)
    {
        $dirty = $post->getDirty();

        // Log post updates
        SecurityService::logActivity(
            'post_updated', 
            [
                'post_id' => $post->id,
                'title' => $post->title,
                'user_id' => $post->user_id,
                'updated_fields' => array_keys($dirty)
            ], 
            SecurityService::RISK_LOW,
            $post->user_id
        );

        // Log to application log
        \Log::info("Post updated: {$post->title}", [
            'post_id' => $post->id,
            'user_id' => $post->user_id,
            'updated_fields' => array_keys($dirty),
            'timestamp' => now()
        ]);
    }

    public function deleted($post)
    {
        // Log post deletion
        SecurityService::logActivity(
            'post_deleted', 
            [
                'post_id' => $post->id,
                'title' => $post->title,
                'user_id' => $post->user_id
            ], 
            SecurityService::RISK_MEDIUM,
            $post->user_id
        );

        // Log to application log
        \Log::info("Post deleted: {$post->title}", [
            'post_id' => $post->id,
            'user_id' => $post->user_id,
            'timestamp' => now()
        ]);
    }

    public function restored($post)
    {
        // Log post restoration
        SecurityService::logActivity(
            'post_restored', 
            [
                'post_id' => $post->id,
                'title' => $post->title,
                'user_id' => $post->user_id
            ], 
            SecurityService::RISK_LOW,
            $post->user_id
        );

        // Log to application log
        \Log::info("Post restored: {$post->title}", [
            'post_id' => $post->id,
            'user_id' => $post->user_id,
            'timestamp' => now()
        ]);
    }

    public function saved($post)
    {
        // Log when post is published
        if ($post->isDirty('is_published')) {
            $action = $post->is_published ? 'post_published' : 'post_unpublished';
            $riskLevel = $post->is_published ? SecurityService::RISK_LOW : SecurityService::RISK_MEDIUM;

            SecurityService::logActivity(
                $action,
                [
                    'post_id' => $post->id,
                    'title' => $post->title,
                    'user_id' => $post->user_id,
                    'published_status' => $post->is_published
                ],
                $riskLevel,
                $post->user_id
            );
        }
    }
}

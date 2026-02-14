<?php
namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewPostPublished extends Notification implements ShouldQueue
{
    use Queueable;

    protected $post;

    public function __construct($post = null)
    {
        $this->post = $post;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        if (!$this->post) {
            return (new MailMessage)
                ->subject('Pemberitahuan Umum')
                ->line('Ada pemberitahuan baru di sistem.');
        }

        return (new MailMessage)
            ->subject('Artikel Baru - ' . $this->post->title)
            ->line('Artikel baru telah dipublikasikan:')
            ->line('Judul: ' . $this->post->title)
            ->line('Deskripsi: ' . str_limit(strip_tags($this->post->content), 100))
            ->action('Baca Artikel', url('/blog/' . $this->post->id))
            ->line('Terima kasih telah mengikuti blog kami.');
    }

    public function toDatabase($notifiable)
    {
        if (!$this->post) {
            return [
                'title' => 'Pemberitahuan Umum',
                'message' => 'Ada pemberitahuan baru di sistem.',
                'created_at' => now(),
            ];
        }

        return [
            'title' => 'Artikel Baru Dipublikasikan',
            'message' => 'Artikel "' . $this->post->title . '" telah dipublikasikan',
            'post_id' => $this->post->id,
            'post_title' => $this->post->title,
            'post_excerpt' => str_limit(strip_tags($this->post->content), 100),
            'created_at' => now(),
        ];
    }

    public function toBroadcast($notifiable)
    {
        if (!$this->post) {
            return [
                'title' => 'Pemberitahuan Umum',
                'message' => 'Ada pemberitahuan baru di sistem.',
                'created_at' => now(),
            ];
        }

        return [
            'title' => 'Artikel Baru Dipublikasikan',
            'message' => 'Artikel "' . $this->post->title . '" telah dipublikasikan',
            'post_id' => $this->post->id,
            'post_title' => $this->post->title,
            'created_at' => now(),
        ];
    }

    public function toArray($notifiable)
    {
        if (!$this->post) {
            return [
                'title' => 'Pemberitahuan Umum',
                'message' => 'Ada pemberitahuan baru di sistem.',
                'created_at' => now(),
            ];
        }

        return [
            'title' => 'Artikel Baru Dipublikasikan',
            'message' => 'Artikel "' . $this->post->title . '" telah dipublikasikan',
            'post_id' => $this->post->id,
            'post_title' => $this->post->title,
            'post_excerpt' => str_limit(strip_tags($this->post->content), 100),
            'created_at' => now(),
        ];
    }
}

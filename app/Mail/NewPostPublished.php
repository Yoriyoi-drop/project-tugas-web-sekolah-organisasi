<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewPostPublished extends Mailable
{
    use Queueable, SerializesModels;

    public $post;

    /**
     * Create a new message instance.
     */
    public function __construct($post)
    {
        $this->post = $post;
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        return $this->subject('Artikel Baru: ' . ($this->post->title ?? ''))
                    ->view('emails.new-post-published')
                    ->with([
                        'post' => $this->post,
                    ]);
    }
}

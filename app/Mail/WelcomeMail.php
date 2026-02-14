<?php
namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Selamat Datang di ' . config('app.name'))
                    ->view('emails.welcome')
                    ->with([
                        'userName' => $this->user->name,
                        'appName' => config('app.name'),
                        'email' => $this->user->email,
                    ]);
    }
}

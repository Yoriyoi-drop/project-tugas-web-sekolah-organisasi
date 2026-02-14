<?php
namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactFormMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $contact;
    public $type;

    public function __construct(Contact $contact, $type = 'admin_notification')
    {
        $this->contact = $contact;
        $this->type = $type;
    }

    public function build()
    {
        if ($this->type === 'confirmation') {
            return $this->subject('Konfirmasi Pesan Anda Telah Diterima')
                        ->view('emails.contact_confirmation')
                        ->with([
                            'contact' => $this->contact,
                            'appName' => config('app.name'),
                        ]);
        } else {
            return $this->subject('Pesan Baru dari Formulir Kontak: ' . $this->contact->subject)
                        ->view('emails.contact_notification')
                        ->with([
                            'contact' => $this->contact,
                            'appName' => config('app.name'),
                        ]);
        }
    }
}

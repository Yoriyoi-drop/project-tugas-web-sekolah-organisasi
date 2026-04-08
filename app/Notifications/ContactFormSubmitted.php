<?php
namespace App\Notifications;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Str;

class ContactFormSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Formulir Kontak Baru - ' . $this->contact->subject)
            ->line('Sebuah formulir kontak baru telah dikirim:')
            ->line('Nama: ' . $this->contact->name)
            ->line('Email: ' . $this->contact->email)
            ->line('Subjek: ' . $this->contact->subject)
            ->line('Pesan: ' . Str::limit($this->contact->message, 100))
            ->action('Lihat Pesan', url('/admin/messages/' . $this->contact->id))
            ->line('Terima kasih telah menggunakan formulir kontak kami.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Formulir Kontak Baru',
            'message' => 'Pesan baru dari ' . $this->contact->name . ' (' . $this->contact->email . ')',
            'contact_id' => $this->contact->id,
            'sender_name' => $this->contact->name,
            'sender_email' => $this->contact->email,
            'subject' => $this->contact->subject,
            'message_preview' => Str::limit($this->contact->message, 100),
            'created_at' => now(),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return [
            'title' => 'Formulir Kontak Baru',
            'message' => 'Pesan baru dari ' . $this->contact->name,
            'contact_id' => $this->contact->id,
            'sender_name' => $this->contact->name,
            'sender_email' => $this->contact->email,
            'subject' => $this->contact->subject,
            'created_at' => now(),
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Formulir Kontak Baru',
            'message' => 'Pesan baru dari ' . $this->contact->name . ' (' . $this->contact->email . ')',
            'contact_id' => $this->contact->id,
            'sender_name' => $this->contact->name,
            'sender_email' => $this->contact->email,
            'subject' => $this->contact->subject,
            'message_preview' => Str::limit($this->contact->message, 100),
            'created_at' => now(),
        ];
    }
}

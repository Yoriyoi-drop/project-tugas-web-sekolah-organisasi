<?php
namespace App\Notifications;

use App\Models\PPDB;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PPDBSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ppdb;

    public function __construct(PPDB $ppdb)
    {
        $this->ppdb = $ppdb;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Pendaftaran PPDB Baru - ' . $this->ppdb->name)
            ->line('Seorang calon siswa baru telah mendaftar melalui PPDB.')
            ->line('Nama: ' . $this->ppdb->name)
            ->line('Email: ' . $this->ppdb->email)
            ->line('Telepon: ' . $this->ppdb->phone)
            ->line('Sekolah Asal: ' . $this->ppdb->previous_school)
            ->action('Lihat Pendaftaran', url('/admin/ppdb/' . $this->ppdb->id))
            ->line('Terima kasih telah menggunakan sistem PPDB kami.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Pendaftaran PPDB Baru',
            'message' => 'Calon siswa ' . $this->ppdb->name . ' telah mendaftar melalui PPDB',
            'ppdb_id' => $this->ppdb->id,
            'user_name' => $this->ppdb->name,
            'email' => $this->ppdb->email,
            'previous_school' => $this->ppdb->previous_school,
            'created_at' => now(),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return [
            'title' => 'Pendaftaran PPDB Baru',
            'message' => 'Calon siswa ' . $this->ppdb->name . ' telah mendaftar melalui PPDB',
            'ppdb_id' => $this->ppdb->id,
            'user_name' => $this->ppdb->name,
            'created_at' => now(),
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Pendaftaran PPDB Baru',
            'message' => 'Calon siswa ' . $this->ppdb->name . ' telah mendaftar melalui PPDB',
            'ppdb_id' => $this->ppdb->id,
            'user_name' => $this->ppdb->name,
            'email' => $this->ppdb->email,
            'previous_school' => $this->ppdb->previous_school,
            'created_at' => now(),
        ];
    }
}

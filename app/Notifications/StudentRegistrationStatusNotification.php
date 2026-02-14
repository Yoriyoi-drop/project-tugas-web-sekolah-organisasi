<?php

namespace App\Notifications;

use App\Models\StudentRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentRegistrationStatusNotification extends Notification
{
    use Queueable;

    protected $registration;
    protected $status;
    protected $additionalInfo;

    /**
     * Create a new notification instance.
     *
     * @param StudentRegistration $registration
     * @param string $status
     * @param string|null $additionalInfo
     */
    public function __construct(StudentRegistration $registration, string $status, string $additionalInfo = null)
    {
        $this->registration = $registration;
        $this->status = $status;
        $this->additionalInfo = $additionalInfo;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $subject = '';
        $greeting = "Halo {$this->registration->name},";
        $line = '';
        $actionText = '';
        $actionUrl = '';

        switch ($this->status) {
            case 'approved':
                $subject = 'Pendaftaran Akun Siswa Disetujui - MA NU Nusantara';
                $line = 'Kami dengan senang hati memberitahu Anda bahwa pendaftaran akun siswa Anda telah disetujui.';
                
                if ($this->additionalInfo) {
                    $line .= " Password sementara Anda adalah: <strong>{$this->additionalInfo}</strong>";
                }
                
                $line .= ' Silakan login ke sistem pembelajaran menggunakan email dan password yang telah dibuat.';
                $actionText = 'Login ke Sistem';
                $actionUrl = url('/login');
                break;
            
            case 'rejected':
                $subject = 'Pendaftaran Akun Siswa Ditolak - MA NU Nusantara';
                $greeting = "Halo {$this->registration->name},";
                $line = 'Mohon maaf, pendaftaran akun siswa Anda telah ditolak.';
                
                if ($this->additionalInfo) {
                    $line .= " Alasan penolakan: {$this->additionalInfo}";
                }
                
                $line .= ' Jika Anda merasa ini adalah kesalahan, silakan hubungi admin sekolah untuk informasi lebih lanjut.';
                break;
            
            default:
                $subject = 'Update Status Pendaftaran Akun Siswa - MA NU Nusantara';
                $line = "Status pendaftaran akun Anda telah diperbarui menjadi: {$this->status}";
                break;
        }

        return (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line($line)
            ->when($actionUrl && $actionText, function ($mailMessage) use ($actionUrl, $actionText) {
                return $mailMessage->action($actionText, $actionUrl);
            })
            ->line('Terima kasih telah mendaftar di sistem pembelajaran MA NU Nusantara.')
            ->salutation('Hormat Kami, Admin MA NU Nusantara');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'registration_id' => $this->registration->id,
            'status' => $this->status,
            'additional_info' => $this->additionalInfo,
            'sent_at' => now(),
        ];
    }
}
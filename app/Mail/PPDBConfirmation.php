<?php
namespace App\Mail;

use App\Models\PPDB;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PPDBConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $ppdb;

    public function __construct(PPDB $ppdb)
    {
        $this->ppdb = $ppdb;
    }

    public function build()
    {
        return $this->subject('Konfirmasi Pendaftaran PPDB - ' . $this->ppdb->name)
                    ->view('emails.ppdb_confirmation')
                    ->with([
                        'ppdb' => $this->ppdb,
                        'appName' => config('app.name'),
                        'registrationNumber' => $this->ppdb->registration_number,
                    ]);
    }
}

<?php

namespace App\Mail;

use App\Models\Protocol;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class ProtocolMail extends Mailable
{
    use Queueable, SerializesModels;

    public $protocol;

    public function __construct(Protocol $protocol)
    {
        $this->protocol = $protocol;
    }

    public function build()
    {
        // 🔥 PDF erzeugen
        $pdf = Pdf::loadView('pdf.protocol', [
            'protocol' => $this->protocol
        ]);

        $fileName = 'Protokoll_' . str_replace(' ', '_', $this->protocol->title) . '.pdf';

        $mail = $this->subject('Protokoll: ' . $this->protocol->title)
            ->view('emails.protocol')
            ->attachData(
                $pdf->output(),
                $fileName,
                ['mime' => 'application/pdf']
            );

        // 🔥 Original-Anhänge zusätzlich anhängen
        if (!empty($this->protocol->attachments)) {

            foreach ($this->protocol->attachments as $file) {

                $path = storage_path('app/public/' . $file);

                if (file_exists($path)) {
                    $mail->attach($path);
                }
            }
        }

        return $mail;
    }
}
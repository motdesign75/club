<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Protocol;

class ProtocolMail extends Mailable
{
    use Queueable, SerializesModels;

    public Protocol $protocol;

    /**
     * Create a new message instance.
     */
    public function __construct(Protocol $protocol)
    {
        // Richtig: Teilnehmer heißen participants, nicht attendees
        $this->protocol = $protocol->load('participants');
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject('📄 Protokoll: ' . $this->protocol->title)
                    ->view('emails.protocol')
                    ->with([
                        'protocol' => $this->protocol,
                    ]);
    }
}

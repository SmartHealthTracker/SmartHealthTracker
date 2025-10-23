<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Participation;

class ParticipationStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $participation;

    public function __construct(Participation $participation)
    {
        $this->participation = $participation;
    }

    public function build()
    {
        return $this->subject('Participation Status Update')
                    ->markdown('emails.participation.status')
                    ->with([
                        'user' => $this->participation->user->name,
                        'challenge' => $this->participation->challenge->name,
                        'status' => ucfirst($this->participation->status),
                    ]);
    }
}

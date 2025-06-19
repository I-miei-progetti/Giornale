<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CareerRequestMail extends Mailable
{
    use Queueable, SerializesModels;
    public $info;

    public function __construct($info)
    {
        $this->info=$info;
    }

   
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nuova richiesta di lavoro ricevuta',
        );
    }


    public function content(): Content
    {
        return new Content(
            view: 'mail.career-request-mail',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function build()
{
    return $this->view('mail.career-request-mail')
        ->with(['info' => $this->info])
        ->attach(public_path('image/logo_scritto.png'), [
            'as' => 'logo.png',
            'mime' => 'image/png',
        ])
        ->withSwiftMessage(function ($message) {
            $message->embed(public_path('image/logo_scritto.png'));
        });
}
}

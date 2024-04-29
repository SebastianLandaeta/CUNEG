<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use App\Models\Curso;
use App\Models\Participante;

class CertificacionMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $curso;

    public $participante;

    public function __construct($curso,$participante) {
        $this->curso=$curso;
        $this->participante=$participante;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(env('MAIL_FROM_ADDRESS'),'CUNEG'),
            subject: 'Certificacion de "'. $this->curso->nombre. '".'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {

        return new Content(
            view: 'mailCertificado'
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */

    public function attachments(): array
    {
        return [
            Attachment::fromPath('certificados/curso_'.$this->curso->id.'/certificado'.$this->participante->cedula.'.pdf')
            ->as('certificado.pdf')
            
        ];
    }
}

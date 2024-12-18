<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewEnviarMail extends Mailable
{
    use Queueable, SerializesModels;

    private mixed $mailData;

    /**
     * Create a new message instance.
     */
    public function __construct($mailData)
    {
        $auxiliar = $mailData[0];
        setlocale(LC_TIME, 'es_ES.UTF-8', 'Spanish_Spain.UTF-8', 'es_ES', 'Spanish');
        foreach ($auxiliar as $key => $prestamo) {
            $mailData[0][$key]->fecha = $this->formatearFecha($prestamo->fecha);
        }
        $this->mailData = $mailData;
    }

    function formatearFecha($fecha)
    {
        $dateTime = new \DateTime($fecha);
        $anioActual = date('Y');

             // Formatear la fecha

        $AMPM = $dateTime->format('g:ia');
        if ($dateTime->format('Y') == $anioActual) {
            // Formato: 10 de diciembre 12:32pm
            $formato = strftime('%e de %B ', $dateTime->getTimestamp());
        } else {
            // Formato: 10 de diciembre 2023 12:32pm
            $formato = strftime('%e de %B %Y ', $dateTime->getTimestamp());
        }
        $formato .= $AMPM;
        return $formato;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Estado Medios Audiovisuales ' . date('d - M - y'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.EstadomediosEnviar2',
            with: [
                'mailData' => $this->mailData,  // Pasa los datos a la vista
            ],
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
}

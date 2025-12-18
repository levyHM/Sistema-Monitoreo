<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificacionTest extends Mailable
{
    use Queueable, SerializesModels;

    public $solucion;
    public $productos;

    /**
     * Create a new message instance.
     */
    public function __construct($solucion)
    {
        $this->solucion = $solucion;
        $this->productos = $solucion->soluciones; // relación con productos
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notificacion Test',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email',
            with: [
                'solucion' => $this->solucion,
                'productos' => $this->productos
            ]
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

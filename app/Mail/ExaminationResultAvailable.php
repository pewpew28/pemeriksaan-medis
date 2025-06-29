<?php

namespace App\Mail;

use App\Models\Examination;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ExaminationResultAvailable extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $examination;

    /**
     * Create a new message instance.
     */
    public function __construct(Examination $examination)
    {
        $this->examination = $examination;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hasil Pemeriksaan Laboratorium Tersedia',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.examination-result-available',
            with: [
                'examination' => $this->examination,
                'patient' => $this->examination->patient,
                'serviceItem' => $this->examination->serviceItem,
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
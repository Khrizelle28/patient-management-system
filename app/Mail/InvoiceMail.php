<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public $type;

    /**
     * Create a new message instance.
     */
    public function __construct($data, string $type)
    {
        $this->data = $data;
        $this->type = $type; // 'appointment' or 'order'
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->type === 'appointment'
            ? 'Payment Invoice - Appointment Confirmation'
            : 'Payment Invoice - Order Confirmation';

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $view = $this->type === 'appointment'
            ? 'emails.invoice-appointment'
            : 'emails.invoice-order';

        return new Content(
            view: $view,
            with: [
                'data' => $this->data,
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
        $pdfView = $this->type === 'appointment'
            ? 'pdf.appointment-invoice'
            : 'pdf.order-invoice';

        $fileName = $this->type === 'appointment'
            ? 'appointment-invoice-'.$this->data['invoice_number'].'.pdf'
            : 'order-invoice-'.$this->data['invoice_number'].'.pdf';

        $pdf = Pdf::loadView($pdfView, ['data' => $this->data]);

        return [
            Attachment::fromData(fn () => $pdf->output(), $fileName)
                ->withMime('application/pdf'),
        ];
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $invoice;

    /**
     * Create a new message instance.
     */
    public function __construct($order, $invoice)
    {
        $this->order = $order;
        $this->invoice = $invoice;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $type = $this->invoice->invoice_type === 'factura' ? 'Factura' : 'Boleta';
        return new Envelope(
            subject: "Tu {$type} Electrónica - {$this->invoice->invoice_number}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        $settings = \App\Models\Setting::pluck('value', 'key')->all();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('billing.invoice_pdf', [
            'invoice_type' => $this->invoice->invoice_type,
            'invoice_number' => $this->invoice->invoice_number,
            'date' => $this->invoice->created_at->format('d/m/Y H:i'),
            'customer_name' => $this->invoice->customer_name,
            'customer_document' => $this->invoice->customer_document_number,
            'customer_address' => $this->invoice->customer_address,
            'items' => $this->order->items,
            'subtotal' => $this->invoice->subtotal,
            'tax' => $this->order->tax ?: ($this->order->total / 1.18 * 0.18),
            'total' => $this->invoice->total,
            'settings' => $settings,
        ]);

        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(fn () => $pdf->output(), "{$this->invoice->invoice_number}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}

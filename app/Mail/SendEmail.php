<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
     public $viewName;
    public $viewData;
    public $fromEmail;
    public $fromName;
    public $subjectLine;

    public function __construct(string $viewName, array $viewData = [], ?string $subject = null, ?string $fromEmail = null, ?string $fromName = null)
    {
        $this->viewName   = $viewName;
        $this->viewData   = $viewData;
        $this->subjectLine = $subject;
        $this->fromEmail  = $fromEmail;
        $this->fromName   = $fromName;

    }

    /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Send Email',
    //     );
    // }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    // public function attachments(): array
    // {
    //     return [];
    // }

    public function build()
    {
        $mail = $this->view($this->viewName)->with($this->viewData);

        if ($this->subjectLine) {
            $mail->subject($this->subjectLine);
        }

        if ($this->fromEmail) {
            $mail->from($this->fromEmail, $this->fromName);
        }

        return $mail;
    }


}

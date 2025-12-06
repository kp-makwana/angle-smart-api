<?php

namespace App\Mail\Auth;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ResetPasswordMail extends Mailable implements ShouldQueue
{
  public string $url;

  /**
   * Create a new message instance.
   */
  public function __construct(string $url)
  {
    $this->url = $url;
  }

  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope
  {
    return new Envelope(
      subject: 'Reset Your Password'
    );
  }

  /**
   * Get the message content definition.
   */
  public function content(): Content
  {
    return new Content(
      view: 'emails.reset-password',
      with: [
        'url' => $this->url,
      ]
    );
  }

  /**
   * Get the attachments for the message.
   *
   * @return array
   */
  public function attachments(): array
  {
    return [];
  }
}

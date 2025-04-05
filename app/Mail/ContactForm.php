<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactForm extends Mailable
{
    use Queueable, SerializesModels;

    protected $name;
    protected $email;
    protected $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $email, $message)
    {
        $this->name = sanitize($name);
        $this->email = $email;
        $this->message = sanitize($message);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('hello@livethemes.co')
                    ->markdown('emails.contact-form')
                    ->with([
                        'name' => $this->name,
                        'email' => $this->email,
                        'message' => $this->message
                    ]);
    }
}

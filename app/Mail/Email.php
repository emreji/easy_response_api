<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Email extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $address = 'hello@easyresponse.com';
        $name = 'Easy Response';

        return $this->view('emails.test')
            ->from($address, $name)
            ->cc($address, $name)
            ->replyTo("lizareji23@gmail.com", $name)
            ->subject($this->data['event']->name)
            ->with([
                'event' => $this->data['event']
                ]);
    }
}

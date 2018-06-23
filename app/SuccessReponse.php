<?php

namespace App;

class SuccessReponse
{
    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }
}

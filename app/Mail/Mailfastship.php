<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Mailfastship extends Mailable
{
    //protected $email;
    public $email;
    use Queueable, SerializesModels;

    //public function __construct(Email $email)
    public function __construct()
    {
        $this->email = 'cs@fastship.co';//$email;
    }

    public function build()
    {
        //return $this->view('view.name');
        //return $this->view('test_mail');
        //return $this->view('test_mail',['name'=>'Fastship'])->to('thachie@mousework.com')->from('cs@fastship.co');
        return $this->view('test_mail');
    }
}

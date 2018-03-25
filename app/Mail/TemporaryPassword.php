<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TemporaryPassword extends Mailable
{
    use Queueable, SerializesModels;

    protected $temporary_password;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($temporary_password)
    {
        $this->temporary_password = $temporary_password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $from    = env('APP_EMAIL');

        $subject = config('app.name') . ": Temporary Password";

        return $this->view('emails.Users.temporary_password')
                    ->from($from)
                    ->subject($subject)
                    ->with([
                        'temporary_password' => $this->temporary_password['temporary_password']
                    ]);
    }
}

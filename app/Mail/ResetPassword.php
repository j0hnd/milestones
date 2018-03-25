<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    protected $reset_info;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reset_info)
    {
        $this->reset_info = $reset_info;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $from    = env('APP_EMAIL');
        
        $subject = config('app.name') . ": Reset Password";

        return $this->view('emails.Users.reset_password')
                    ->from($from)
                    ->subject($subject)
                    ->with([
                        'reset_link' => $this->reset_info['reset_link']
                    ]);
    }
}

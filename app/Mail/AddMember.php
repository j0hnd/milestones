<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddMember extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user_info)
    {
        $this->user = $user_info;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $from    = env('APP_EMAIL');

        $subject = config('app.name') . ": Login Credentials";

        return $this->view('emails.Users.add_member')
                    ->from($from)
                    ->subject($subject)
                    ->with([
                        'email'              => $this->user['email'],
                        'temporary_password' => $this->user['temporary_password'],
                        'url'                => $this->user['redirect_to']
                    ]);
    }
}

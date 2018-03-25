<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyChanges extends Mailable
{
    use Queueable, SerializesModels;

    protected $changes;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($changes)
    {
        $this->changes = $changes;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $from    = env('APP_EMAIL');

        $subject = config('app.name') . ": Changes made in the project details";

        return $this->view('emails.Common.notify_changes')
                    ->from($from)
                    ->subject($subject)
                    ->with(['project_name' => $this->changes['project_name'], 'changes' => $this->changes['changes']]);
    }
}

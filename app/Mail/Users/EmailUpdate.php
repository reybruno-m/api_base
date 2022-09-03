<?php

namespace App\Mail\Users;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $emailConfirmationUrl;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        $baseUrl    = env('APP_FRONTEND_URL', ''); //http://localhost:8000/
        $method     = "api/auth/validate/";
        $params     = $this->user->uuid;

        $this->emailConfirmationUrl = $baseUrl.$method.$params;

        return $this
            ->subject('Confirmá tu email.')
            ->markdown('emails.users.update_email');
    }
}
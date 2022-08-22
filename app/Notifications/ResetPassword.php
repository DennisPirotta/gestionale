<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Reset Password')
            ->line('Hai ricevuto questa email in seguito alla una richiesta di reimpostazione della password per il tuo account.')
            ->action('Reset Password', url('password/reset', $this->token))
            ->line('Se non hai richiesto di reimpostare la password, non sono necessarie ulteriori azioni.');

    }
}
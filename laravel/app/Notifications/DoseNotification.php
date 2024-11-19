<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DoseNotification extends Notification
{
    use Queueable;

    protected $message;

    /**
     * Cria uma nova instância da notificação.
     *
     * @param string $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Determina os canais de entrega da notificação.
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail']; // Ou outros canais, como 'database', 'sms', etc.
    }

    /**
     * Configura a mensagem para envio por e-mail.
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Lembrete de Medicação')
            ->line($this->message)
            ->line('Por favor, tome sua medicação no horário correto.');
    }
}

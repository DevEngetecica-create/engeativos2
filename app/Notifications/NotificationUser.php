<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificationUser extends Notification
{
    use Queueable;

    private $email;
    Private $menssagem; 
    private $nome_usuario;
    private $link;
    
  
    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public function __construct($email, $nome_usuario, $menssagem, $link )
    {
        $this->email = $email;
        $this->menssagem = $menssagem;          
        $this->nome_usuario = $nome_usuario;
        $this->link = $link; 

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Olá ". $this->nome_usuario)
            ->greeting('Seja bem vindo ao SGA-Engeativos')
            ->line("" . $this->menssagem)
            ->action('Acessar', env('APP_URL'));           
    }
    

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

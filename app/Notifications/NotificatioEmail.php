<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificatioEmail extends Notification
{
    use Queueable;

    private $email;
    Private $menssagem;   
    private $link;
    private $title;
    
  
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $email, $menssagem, $link )
    {
        $this->email = $email;
        $this->menssagem = $menssagem;
        $this->link = $link;       
        $this->title = $title;


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
            ->subject("Transferência entre obras -  {$this->title}")
            ->greeting('Olá, '.$this->email)
            ->line("" . $this->menssagem)
            ->action('Verificar', env('APP_URL') . $this->link);
           
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

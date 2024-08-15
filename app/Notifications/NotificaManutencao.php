<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificaManutencao extends Notification
{
    use Queueable;

    private $email;
    private $id_manutencao;
    private $nomeFerramenta;
    private $method;
    Private $menssagem;
  
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($email, $id_manutencao, $menssagem)
    {
        $this->email = $email;       
        $this->id_manutencao = $id_manutencao;
        $this->menssagem = $menssagem;

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
            ->subject('Mensagem TESTE.......Solicitação de Manutenção')
            ->greeting('Olá, '.$this->email)
            ->line($this->menssagem)
            ->action("Verificar retirada", env('APP_URL') . "/admin/ativo/externo/manutencao/detalhes/{$this->id_manutencao}");
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

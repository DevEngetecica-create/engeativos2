<?php



namespace App\Notifications;



use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificationCadFuncionario extends Notification implements ShouldQueue

{

    use Queueable;

    private $email;
    private $name;
    private $funcionario_nome;
    private $funcao;
    private $obra;
    private $url;
    private $mensagem_arquivo;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public function __construct($email, $name, $funcionario_nome, $funcao, $obra, $url, $mensagem_arquivo)
    {

        $this->email = $email;
        $this->name = $name;
        $this->funcionario_nome = $funcionario_nome;
        $this->funcao = $funcao;
        $this->obra = $obra;
        $this->url = $url;
        $this->mensagem_arquivo = $mensagem_arquivo;
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
        $imageUrl_logo_engetecnica = 'https://sga-engeativos.com.br/build/images/icones/logo_engetecnica.svg'; // URL da sua imagem
        $imageUrl_logo_engeativos = 'https://sga-engeativos.com.br/build/images/icones/logo_engeativos.png'; // URL da sua imagem

        return (new MailMessage)
            ->subject('Novo Funcionário Cadastrado')
                ->view('components.emails.emailCadatroFuncionario', [

                    'email' => $this->email,
                    'name' => $this->name,
                    'funcionario_nome' => $this->funcionario_nome,
                    'funcao' => $this->funcao,
                    'obra' => $this->obra,
                    'url' => $this->url,
                    'mensagem_arquivo' => $this->mensagem_arquivo,
                    'imageUrl_logo_engetecnica' => $imageUrl_logo_engetecnica,
                    'imageUrl_logo_engeativos' => $imageUrl_logo_engeativos,

                ]);
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

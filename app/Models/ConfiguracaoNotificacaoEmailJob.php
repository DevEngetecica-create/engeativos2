<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ConfiguracaoNotificacaoEmailJob extends Model
{

    protected $table = 'jobs_notificatios_emails';

    protected $fillable = [
        'id_grupo',
        'id_usuarios',
        'status'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function noti_email()
    {
        return $this->belongsTo(ConfiguracaoNotificacaoEmail::class, 'id_grupo');
    }
  
}


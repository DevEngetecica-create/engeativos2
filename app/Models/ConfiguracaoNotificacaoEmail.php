<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracaoNotificacaoEmail extends Model
{
    protected $table = 'notifications_email';

    protected $casts = [
        'id_usuario' => 'array', // Adicione esta linha para cast automático
    ];

    protected $fillable = [
        'nome_grupo',
        'id_usuario',
        'id_status',
        'id_obra'
       
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function status()
    {
        return $this->belongsTo(AtivoExernoStatus::class, 'id_status');
    }
    
    public function obra()
    {
        return $this->belongsTo(CadastroObra::class, 'id_obra');
    }
}

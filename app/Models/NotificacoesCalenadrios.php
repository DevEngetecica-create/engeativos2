<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class NotificacoesCalenadrios extends Model
{
    use SoftDeletes;
    use HasFactory;
    
    protected $table = "events";
    
    protected $fillable = ['title', 'color', 'start', 'end', 'obs', 'user_id', 'id_item', 'nome_modulo', 'url', 'id_obra'];

    public function obra()
    {
        return $this->belongsTo(CadastroObra::class, 'id_obra');
    }

    public function anexos()
    {
        return $this->belongsTo(AnexoFuncionario::class, 'id_item');  
    }
   
}

/* Venc. do doc. modulos.png do funcionário VITOR DE CARVALHO TAKIGUCHI vence neste dia. */
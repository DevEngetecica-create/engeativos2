<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FerramentalRetiradaAutenticar extends Model
{
    use HasFactory;

    protected $table = "ativos_ferramental_retirada_autenticar";
    protected $fillable = [
        "id_retirada",
        "id_usuario",
        "id_funcionario",
        "termo_responsabilidade"
    ];

    public function obra()
    {
        return $this->belongsTo(CadastroObra::class, 'id_obra', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    public function funcionario()
    {
        return $this->belongsTo(CadastroFuncionario::class, 'id_funcionario', 'id');
    }

    public function situacao()
    {
        return $this->belongsTo(FerramentalRetiradaStatus::class, 'status', 'id');
    }

    public function status()
    {
        return $this->belongsTo(AtivosExternosStatus::class, 'status', 'id');
    }


    
}

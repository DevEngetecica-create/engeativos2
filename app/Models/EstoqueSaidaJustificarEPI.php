<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstoqueSaidaJustificarEPI extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $table = "funcionario_funcao_epi_justificar";

    protected $fillable = [
        'id_funcionario',
        'id_funcao',
        'id_produto',
        'usuario',
        'justificar_epi'
    ];

    public function produto()
    {
        return $this->belongsTo(Estoque::class, 'id_produto');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'email', 'usuario');
    }

    public function funcionario()
    {
        return $this->belongsTo(CadastroFuncionario::class, 'id_funcionario');
    }
}

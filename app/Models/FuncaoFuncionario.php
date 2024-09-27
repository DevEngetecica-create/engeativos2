<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuncaoFuncionario extends Model
{
    use HasFactory;

    protected $table = 'funcao_funcionarios';

    protected $fillable = [
        'codigo',
        'funcao',
        'id_setor'
    ];

    public function funcionarios()
    {
        return $this->hasMany(CadastroFuncionario::class, 'id_funcao');
    }

    public function setor()
    {
        return $this->belongsTo(CadastroFuncionarioSetor::class, 'id_setor');
    }
}

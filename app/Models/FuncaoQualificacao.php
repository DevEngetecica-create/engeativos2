<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FuncaoQualificacao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'funcionarios_funcao_qualificacoes';

    protected $fillable = [
        'id_funcao',
        'nome_qualificacao',
        'tempo_validade',
        'situacao'
    ];

    public function funcao()
    {
        return $this->belongsTo(FuncaoFuncionario::class, 'id_funcao');
    }    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FuncionarioQualificacao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'funcionarios_qualificacoes';

    protected $fillable = [
        'id_funcionario',
        'id_funcao',
        'id_qualificacao',
        'tempo_validade',
        'situacao',
        'usuario'
    ];

    public function qualificacoes()
    {
        return $this->belongsTo(FuncaoQualificacao::class, 'id_qualificacao');
    }

    /*public function anexo_funcionarios()
    {
        return $this->hasMany(AnexoFuncionario::class, 'id_funcionario', 'id_funcionario')
                    ->whereColumn('id_funcao', 'funcionarios_qualificacoes.id_funcao')
                    ->whereColumn('id_qualificacao', 'funcionarios_qualificacoes.id_qualificacao');
    }*/
}

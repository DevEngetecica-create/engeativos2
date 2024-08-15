<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnexoFuncionario extends Model
{
    use HasFactory;

    protected $table = "anexos_funcionarios";

    protected $fillable = [
        'id_funcionario',
        'id_funcao',
        'id_qualificacao',
        'usuario_cad',
        'arquivo',
        'nome_arquivo',
        'data_conclusao',
        'data_validade_doc',
        'data_aprovacao',
        'situacao_doc',
        'usuario_aprov',
        'usuario_reprov',
        'observacoes'
    ];

    public function situacoes()
    {
        return $this->belongsTo(Situacao::class, 'situacao_doc');
    }

    public function funcionario_qualificacao()
    {
        return $this->belongsTo(FuncionarioQualificacao::class, 'id_funcionario', 'id_funcionario')
                    ->whereColumn('id_funcao', 'id_funcao')
                    ->whereColumn('id_qualificacao', 'id_qualificacao');
    }
    
    public function qualificacao()
    {
        return $this->belongsTo(FuncaoQualificacao::class, 'id_qualificacao');
    }
}

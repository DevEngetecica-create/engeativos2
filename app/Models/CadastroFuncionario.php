<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CadastroFuncionario extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "funcionarios";
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'matricula',
        'id_obra',
        'id_setor',
        'password',
        'data_altera_password',
        'nome',
        'data_nascimento',
        'cpf',
        'rg',
        'id_funcao',
        'cep',
        'endereco',
        'numero',
        'bairro',
        'cidade',
        'estado',
        'email',
        'celular',
        'nome_mae',
        'pis',
        'estado_civil',
        'dependentes',
        'data_adminssao',
        'data_demissao',
        'situacao',
        'imagem_usuario',
    ];

    public function obra()
    {
        return $this->belongsTo(CadastroObra::class, 'id_obra', 'id');
    }

    public function usuario_vinculo()
    {
        return $this->belongsTo(CadastroUsuariosVinculo::class, 'id_funcionario', 'id');
    }

    public function funcao()
    {
        return $this->belongsTo(FuncaoFuncionario::class, 'id_funcao');
    }

    public function setor()
    {
        return $this->belongsTo(CadastroFuncionarioSetor::class, 'id_setor');
    }

    public function qualificacoes()
    {
        return $this->hasMany(FuncionarioQualificacao::class, 'id_funcionario', 'id');
    }

    public function anexo_funcionarios()
    {
        return $this->hasManyThrough(AnexoFuncionario::class, FuncionarioQualificacao::class, 'id_funcionario', 'id_funcionario', 'id', 'id_funcionario')
                    ->whereColumn('funcionarios_qualificacoes.id_funcao', 'anexos_funcionarios.id_funcao')
                    ->whereColumn('funcionarios_qualificacoes.id_qualificacao', 'anexos_funcionarios.id_qualificacao');
    }
}

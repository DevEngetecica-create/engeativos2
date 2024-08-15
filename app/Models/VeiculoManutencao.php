<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VeiculoManutencao extends Model
{
    use HasFactory;

    use SoftDeletes;

    //protected $dates = ['deleted_at'];

    protected $fillable = [
        'veiculo_id',
        'fornecedor_id',
        'servico_id',
        'id_usuario',
        'id_obra',
        'tipo',
        'quilometragem_atual',
        'quilometragem_nova',
        'horimetro_atual',
        'horimetro_proximo',
        'data_de_execucao',
        'data_de_vencimento',
        'descricao',
        'valor_do_servico',
        'situacao',
        'id_usuario_aprovador'
    ];

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'veiculo_id');
    }

    public function fornecedor()
    {
        return $this->belongsTo(CadastroFornecedor::class, 'fornecedor_id');
    }

    public function servico()
    {
        return $this->belongsTo(Servico::class, 'servico_id');
    }

    public function funcionario()
    {
        return $this->belongsTo(CadastroFuncionario::class, 'id_usuario_aprovador');
    }

    public function obra()
    {
        return $this->belongsTo(CadastroFuncionario::class, 'id_obra');
    }

    public function situacoes()

    {
        return $this->belongsTo(AtivoExernoStatus::class, 'situacao');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }



}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class VeiculoLocacao extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    protected $table = "veiculos_locacaos";

    protected $fillable = [
        'id_obra',
        'veiculo_id',
        'tipo_veiculo',
        'id_obraDestino',
        'id_funcionario',
        'data_inicio',
        'data_fim'
    ];

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'veiculo_id');
    }

    public function fornecedor()
    {
        return $this->belongsTo(CadastroFornecedor::class);
    }

    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }
    
    public function obra()
    {

        return $this->belongsTo(CadastroObra::class, 'id_obra');

    }
    
    public function obraDestino()
    {

        return $this->belongsTo(CadastroObra::class, 'id_obraDestino');

    }
    
    public function manutencaos()
    {
        return $this->hasMany(VeiculoManutencao::class, 'veiculo_id');
    }
    
    
    public function funcionarios()
    {

        return $this->belongsTo(CadastroFuncionario::class, 'id_funcionario');

    }
    
}

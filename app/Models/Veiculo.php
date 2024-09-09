<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Veiculo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'obra_id',
        'idCategoria',
        'idSubCategoria',
        'id_preventiva',
        'user_create',
        'user_edit',
        'tipo',
        'marca',
        'modelo',
        'ano',
        'veiculo',
        'valor_fipe',
        'valor_aquisicao',
        'codigo_fipe',
        'fipe_mes_referencia',
        'mes_aquisicao',
        'codigo_da_maquina',
        'placa',
        'renavam',
        'horimetro_inicial',
        'quilometragem_inicial',
        'observacao',
        'situacao',
        'imagem',
        'usuario'
    ];

    public function tipos()
    {
        return $this->belongsTo(TiposVeiculos::class, 'tipo', 'id');
    }   

    public function manutencoes()
    {
        return $this->hasMany(VeiculoManutencao::class, 'veiculo_id');
    }

    public function quilometragem()
    {
        return $this->hasMany(VeiculoQuilometragem::class, 'veiculo_id');
    }
    
    
    public function categorias()
    {
        return $this->belongsTo(VeiculoCategoria::class, 'idCategoria');
    }
   
    public function checkli_preventiva()
    {
        return $this->belongsTo(CheckListManutPreventiva::class, 'id_veiculo');
    }    
   
    public function documentosLegais()
    {
        return $this->hasMany(VeiculosDocsLegais::class, 'id_veiculo');
    }
    
    public function documentosTecnicos()
    {
        return $this->hasMany(VeiculosDocsTecnicos::class, 'id_veiculo');
    }

    public function seguros()
    {
        return $this->hasMany(VeiculoSeguro::class, 'veiculo_id');
    }

    public function ipvas()
    {
        return $this->hasMany(VeiculoIpva::class, 'veiculo_id');
    }

    public function abastecimento()
    {
        return $this->belongsTo(VeiculoAbastecimento::class, 'veiculo_id');
    }
    
    
}
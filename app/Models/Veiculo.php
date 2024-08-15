<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Veiculo extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = 'veiculos';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'obra_id',
        'idCategoria',
        'idSubCategoria',
        //'periodo_inicial',
        //'periodo_final',
        'tipo',
        'marca',
        'modelo',
        'ano',
        'veiculo',
        'valor_fipe',
        'codigo_fipe',
        'fipe_mes_referencia',
        'codigo_da_maquina',
        'placa',
        'renavam',
        'horimetro_inicial',
        'quilometragem_inicial',
        'observacao',
        'situacao',
        'usuario',
        'usuario_update'
    ];
    
    public static function obterDados()
    {
        $valorVeiculos = Veiculo::select('tipo', DB::raw('SUM(CAST(REPLACE(valor_fipe, ".", "") AS DECIMAL(13, 2))) as sumtotalVeiculos'))
        ->groupBy('tipo')
        ->get();

        
        if ($valorVeiculos->count()) {

            
            $incI = 0;
            foreach($valorVeiculos AS $arrKey => $arrData){
                $somaVeiculos[$incI] = $arrData;
               
                $incI++;
            }
            
            //Convert array to json form...
            return json_encode($somaVeiculos);
          
            foreach (($valorVeiculos) as $chave => $key) {
                
                
                $dadosVeiculos = json_decode($key, true);
                
                //dd($valorVeiculos);
                $somaVeiculos = [];
                $incI = 0;
                foreach ($dadosVeiculos as $chaveValoresVeiculo => $valor) {
                    $somaVeiculos[$chaveValoresVeiculo] = $valor['tipo'];
                    $somaVeiculos[$chaveValoresVeiculo] = $valor['sumtotalVeiculos'];
                    $incI++;
                }

               
            }

            return $somaVeiculos;

        } else {

            return null;

        }
    }
    
    public function quilometragens()
    {
        return $this->hasMany(VeiculoQuilometragem::class, 'veiculo_id');
    
    }
    public function horimetro()
    {
        return $this->hasMany(VeiculoHorimetro::class, 'veiculo_id');
    }

    public function abastecimentos()
    {
        return $this->hasMany(VeiculoAbastecimento::class);
    }

    public function depreciacaos()
    {
        return $this->hasMany(VeiculoDepreciacao::class);
    }

    public function manutencaos()
    {
        return $this->hasMany(VeiculoManutencao::class, 'veiculo_id');
    }

    public function seguros()
    {
        return $this->hasMany(VeiculoSeguro::class);
    }

    public function ipvas()
    {
        return $this->hasMany(VeiculoIpva::class);
    }

    public function obra()
    {
        return $this->belongsTo(CadastroObra::class, 'obra_id');
    }
    
    public function categorias()
    {
        return $this->belongsTo(VeiculoCategoria::class, 'idCategoria');
    }
    
    public function subCategorias()
    {
        return $this->belongsTo(VeiculoSubCategoria::class, 'idSubCategoria');
    }


}

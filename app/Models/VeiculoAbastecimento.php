<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VeiculoAbastecimento extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'veiculo_abastecimentos';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'veiculo_id',
        'id_funcionario',
        'user_create',
        'user_edit',
        'data_abastecimento',
        'bandiera',
        'combustivel',
        'km_inicial',
        'km_final',
        'quantidade',  
        'valor_do_litro',              
        'valor_total',
        'nome_anexo',
        'arquivo',
    ]; 

   

    public function funcionario()
    {
        return $this->belongsTo(CadastroFuncionario::class, 'id_funcionario'); 
    }

    public function fornecedor()
    {
        return $this->belongsTo(CadastroFuncionario::class, 'fornecedor'); 
    }

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'veiculo_id'); 
    }

    /* public function quilometragem()
    {
        return $this->hasMany(VeiculoQuilometragem::class, 'veiculo_id'); 
    } */

}

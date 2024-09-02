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
        'usuario',
        'fornecedor',
        'combustivel',
        'quilometragem',
        'horimetro',
        'valor_do_litro',
        'quantidade',
        'data_cadastro',
        'valor_total',
        'nome_anexo',
        'arquivo',
    ]; 
}

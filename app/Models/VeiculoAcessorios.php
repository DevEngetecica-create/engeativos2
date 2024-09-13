<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VeiculoAcessorios extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'veiculos_acessorios';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'obra_id',
        'veiculo_id',
        'nome_acessorio',
        'valor',
        'ano_aquisicao',
        'n_serie'
    ];
}

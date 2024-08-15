<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VeiculoTacografo extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = 'veiculos_tacografo';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'veiculo_id',
        'descricao',
        'data_da_emissao',
        'data_do_vencimento',        
        'observacao'
    ];

  
    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'veiculo_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VeiculoSeguro extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "veiculo_seguros";
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'veiculo_id',
        'nome_seguradora',
        'carencia_inicial',
        'carencia_final',
        'valor'
    ];

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'veiculo_id'); // Ajuste 'veiculo_id' se necessário
    }
}

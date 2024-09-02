<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VeiculoHorimetro extends Model
{
    use HasFactory;

    protected $table = "veiculo_horimetros";

    protected $fillable = 
        [
            'veiculo_id', 
            'horimetro_atual', 
            'horimetro_novo'
        ];
}
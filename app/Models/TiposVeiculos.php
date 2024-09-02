<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TiposVeiculos extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "tipos_veiculos";

    protected $fillable = [
        'nome_tipo_veiculo',
        'tipo_veiculo',
        'user_create',
        'user_edit',
        'situacao'
    ];

}

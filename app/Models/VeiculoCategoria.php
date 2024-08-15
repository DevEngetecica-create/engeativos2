<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;;

class VeiculoCategoria extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "veiculos_categorias";
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'nomeCategoria',
        'statusCategoria',
    
    ];

}

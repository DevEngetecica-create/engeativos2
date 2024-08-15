<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class VeiculoSubCategoria extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "veiculos_subCategorias";
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'nomeSubCategoria',
        'statusSubCategoria',
    ];

    public function categorias()
    {
        return $this->belongsTo(VeiculoCategoria::class, 'id_categoria');
    }

}

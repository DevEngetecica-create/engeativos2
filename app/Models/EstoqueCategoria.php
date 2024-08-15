<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstoqueCategoria extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "estoque_categorias";
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'color',    
    ];

    public function subcategorias()
    {
        return $this->hasMany(EstoqueSubCategoria::class);
    }

}

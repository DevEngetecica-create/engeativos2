<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;;

class EstoqueSubCategoria extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "estoque_subcategorias";
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'color',
        'category_id'   
    ];

    public function produtos()
    {
        return $this->hasMany(Estoque::class);
    }
    public function categorias()
    {
        return $this->belongsTo(EstoqueCategoria::class, 'category_id');
    }

}

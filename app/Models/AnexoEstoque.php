<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class AnexoEstoque extends Model
{
    use HasFactory;

    protected $table = "anexos_estoque";

    protected $fillable = [
        'id_produto',
        'nome_arquivo',
        'usuario',
    ];

    public function produto()
    {
        return $this->belongsTo(Estoque::class, 'id_produto');
    }
}

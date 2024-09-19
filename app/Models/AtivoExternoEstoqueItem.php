<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;


class AtivoExternoEstoqueItem extends Model
{
    use HasFactory;

    protected $table = "ativos_externos_estoque_item";

    protected $fillable = ['quantidade_em_transito'];

    // static function getEstoqueItemLista($termo = null)
    // {

    //     $query = DB::table('ativos_externos_estoque_item')
    //     ->select(
    //         "ativos_externos_estoque_item.*",
    //         "ativos_externos.titulo"
    //     )
    //         ->join(
    //             'ativos_externos',
    //             'ativos_externos.id',
    //             '=',
    //             'ativos_externos_estoque_item.id_ativo_externo'
    //         );

    //     if ($termo) {
    //         $query->where('ativos_externos.titulo', 'LIKE', '%' . $termo . '%');
    //     }

    //     return $query->get();
    // }
}

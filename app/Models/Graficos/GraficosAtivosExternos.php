<?php

namespace App\Models\Graficos;

use App\Models\AtivoExternoEstoque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GraficosAtivosExternos extends Model

{
    public static function countStatus()
    {

        if (Session::get('obra')['id'] == null) {

            $countStatus = AtivoExternoEstoque::select(
                'ativos_externos_status.id AS idStatus',
                'ativos_externos_status.titulo',
                'ativos_externos_status.classe',
                'ativos_externos_estoque.status AS statusAtivo'
            )
                ->selectRaw('COUNT(ativos_externos_estoque.status) AS totalStatus')
                ->join('ativos_externos_status', 'ativos_externos_estoque.status', '=', 'ativos_externos_status.id')
                ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
                ->whereNull('ativos_externos_estoque.deleted_at')
                ->groupBy('ativos_externos_status.id', 'ativos_externos_status.titulo', 'ativos_externos_status.classe', 'ativos_externos.status', 'ativos_externos_estoque.status')
                ->get();
        } else {

            $countStatus = AtivoExternoEstoque::select(
                'ativos_externos_status.id AS idStatus',
                'ativos_externos_status.titulo',
                'ativos_externos_status.classe',
                'ativos_externos_estoque.status AS statusAtivo'
            )
                ->selectRaw('COUNT(ativos_externos_estoque.status) AS totalStatus')
                ->join('ativos_externos_status', 'ativos_externos_estoque.status', '=', 'ativos_externos_status.id')
                ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
                ->whereNull('ativos_externos_estoque.deleted_at')
                ->where('id_obra', Session::get('obra')['id'])
               ->groupBy('ativos_externos_status.id', 'ativos_externos_status.titulo', 'ativos_externos_status.classe', 'ativos_externos.status', 'ativos_externos_estoque.status')
                ->get();
        }


        return $countStatus;
    }
    public static function statusForaOperacao()
    {
        $listaAtivos = AtivoExternoEstoque::where('id_obra', 4)
            ->with('ativo_externo', 'configuracao', 'obra', 'situacao')
            ->where('status', 9)
            ->get();

        return $listaAtivos;
    }

    public static function valorTotalAtivos()
    {

        if (Session::get('obra')['id'] == null) {

            $valorTotalAtivos = AtivoExternoEstoque::select(DB::raw('SUM(CAST(valor AS DECIMAL(13, 2))) as somaValorTotalFerramentasObra'))
                ->whereNull('ativos_externos_estoque.deleted_at')
                ->get();
        } else {

            $valorTotalAtivos = AtivoExternoEstoque::select(DB::raw('SUM(CAST(valor AS DECIMAL(13, 2))) as somaValorTotalFerramentasObra'))
                ->whereNull('ativos_externos_estoque.deleted_at')
                ->where('id_obra', Session::get('obra')['id'])
                ->get();
        }

        return $valorTotalAtivos;
    }

    public static function totalAtivos()
    {
        if (Session::get('obra')['id'] !== null) {

            $totalAtivos = AtivoExternoEstoque::selectRaw('COUNT(id) AS totalAtivos')
                ->whereNull('ativos_externos_estoque.deleted_at')
                ->where('status', '!=', 9)
                ->where('id_obra', Session::get('obra')['id'])
                ->get();
        } else {

            $totalAtivos = AtivoExternoEstoque::selectRaw('COUNT(id) AS totalAtivo')
                ->whereNull('ativos_externos_estoque.deleted_at')
                ->where('status', '!=', 9)
                ->get();
        }

        return $totalAtivos;
    }
}

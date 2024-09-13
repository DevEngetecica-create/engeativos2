<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Log, Session};
use App\Models\{CadastroObra, ConfiguracaoModulo};
use App\Traits\{Api, FuncoesAdaptadas};

class ApiController extends Controller
{
    /* Traits */
    use api, FuncoesAdaptadas;

    /* Selecionar Obra */
    public function selecionar_obra(Request $Request)
    {
        if (Session::get('usuario_vinculo')['id_nivel'] <= 2) {

            Log::info('Trocou para obra ' . $Request->id_obra);

            $obra = CadastroObra::find($Request->id_obra);
            $Request->session()->put(
                'obra',
                $obra
            );

            echo "alterado";
        } else {
            echo "erro";
        }
    }
}

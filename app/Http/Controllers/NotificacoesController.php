<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

use App\Models\{
    Notification,
    CadastroObra
};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class NotificacoesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function notifications(Request $request)
    {
        $notifications = $request->user()->notifications;
        return response()->json(compact('notifications'));
    }

    public function index(Request $request)
    {
        $id_obra = Session::get('obra')['id'] ?? null;

        if (!empty($id_obra)) {
            $obra = CadastroObra::find($id_obra);
        } else {
            $obra = [
                "codigo_obra" => "Todas as obras"
            ];
        }

        $obra = (object) $obra;

        // Obter a data atual
        $hoje = Carbon::now();

        // Calcular a data de dois dias atrás
        $doisDiasAtras = $hoje->copy()->subDays(5);

        // Buscar notificações com condições adicionais
        $notificacoeslist = Notification::with("obra")
            ->where('id_obra', $id_obra)
            
            ->orderBy('created_at', 'desc')
            ->paginate(14);

        return view('pages.notificacoes.list', compact('notificacoeslist', 'id_obra', 'obra'));
    }

    public function getNotifications(Request $request)
    {
        $id_obra = $request->id_obra;

        // Obter a data atual
        $hoje = Carbon::now();
        $doisDiasAtras = $hoje->copy()->subDays(2);

        if (!empty($id_obra)) {
            $notificacoesNaoLidas = Notification::with("obra")
                ->where('id_obra', $id_obra)
                ->where('status', '!=', 'read')
                ->whereBetween('updated_at', [$doisDiasAtras, $hoje])
                ->orWhereNull('updated_at')
                ->orderBy('created_at', 'desc')
                ->get();

            $totalNotificacoesNaoLidas = Notification::with("obra")
                ->where('id_obra', $id_obra)
                ->where('status', 'unread')
                ->whereBetween('updated_at', [$doisDiasAtras, $hoje])
                ->count();
        } else {
            $notificacoesNaoLidas = Notification::where('status', '!=', 'read')
                ->whereBetween('updated_at', [$doisDiasAtras, $hoje])
                ->orWhereNull('updated_at')
                ->orderBy('created_at', 'desc')
                ->get();

            $totalNotificacoesNaoLidas = Notification::where('status', '!=', 'read')
                ->whereBetween('updated_at', [$doisDiasAtras, $hoje])
                ->orWhereNull('updated_at')
                ->count();
        }

        return response()->json([
            'notificacoes' => $notificacoesNaoLidas,
            'totalnotificacoes' => $totalNotificacoesNaoLidas
        ]);
    }

    public function read(Request $request, $id)
    {
        $notificacoes_lidas = Notification::find($id);

        $notificacoes_lidas->update([
            'status' => 'read',
        ]);

        return back();
    }

    public function show(Request $request, $id)
    {
        $notificacoes_show = Notification::with("obra")->find($id);
        
        //dd($id);

        return response()->json([
            'menssagem' => $notificacoes_show,
        ]);
    }
}

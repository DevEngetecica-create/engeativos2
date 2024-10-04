<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotificacoesCalenadrios; // Modelo do seu evento
use Carbon\Carbon;

class NotificacoesCalenadriosController extends Controller
{
    public function index(Request $request)
    {
        // Sua lógica para a visão do calendário
        return view('pages.calendario.index');
    }

    public function getEvents()
    {
        $events = NotificacoesCalenadrios::select('id', 'title', 'color', 'start', 'end', 'obs', 'user_id', 'client_id')->get();

        $calendar_events = [];
        foreach ($events as $event) {
            $calendar_events[] = [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start,
                'end' => $event->end,
                'className' => $event->color, // Use 'color' como classe CSS
                'description' => $event->obs,
                'user_id' => $event->user_id,
                'client_id' => $event->client_id,
                // Adicione outros campos necessários
            ];
        }

        return response()->json($calendar_events);
    }

    public function store(Request $request)
    {
        $event = new NotificacoesCalenadrios();
        $event->title = $request->title;
        $event->start = $request->start;
        $event->end = $request->end;
        $event->color = $request->className;
        $event->obs = $request->description;
        $event->user_id = $request->user_id;
        $event->client_id = $request->client_id;
        $event->save();

        return response()->json(['success' => true, 'event_id' => $event->id]);
    }

    public function update(Request $request, $id)
    {
        $event = NotificacoesCalenadrios::find($id);
        $event->title = $request->title;
        $event->start = $request->start;
        $event->end = $request->end;
        $event->color = $request->className;
        $event->obs = $request->description;
        // Atualize outros campos conforme necessário
        $event->save();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $event = NotificacoesCalenadrios::find($id);
        $event->delete();

        return response()->json(['success' => true]);
    }
}

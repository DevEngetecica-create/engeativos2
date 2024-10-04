<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateConfigRequest;
use App\Models\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ConfigController extends Controller
{

    public function edit()
    {
        $config = Config::find(1);

        return view('pages.configuracoes.index', compact('config'));
    }

    public function update(UpdateConfigRequest $request)
    {
        if (! $save = Config::find(1)) {

            Config::create($request->validated());

            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog .' | STORE CONFIGURACAO BÁSICA');

            return redirect()->route('config.edit')->with('success', 'Registro atualizado com sucesso.');
        }

        $data = $request->validated();
        $data = $save->update($data);

        if ($data) {
            $userLog = Auth::user()->email;
            Log::channel('main')->info($userLog .' | EDIT CONFIGURACAO BÁSICA');

            return redirect()->route('config.edit')->with('success', 'Registro atualizado com sucesso.');
        }
    }

}

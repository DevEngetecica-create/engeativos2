<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ConfiguracaoController extends Controller
{

    public function index()
    {
        return view('pages.configuracoes.index');
    }

}

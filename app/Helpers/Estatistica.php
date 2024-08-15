<?php

namespace App\Helpers;

use App\Models\CadastroEmpresa;
use App\Models\CadastroFornecedor;
use App\Models\CadastroFuncionario;
use App\Models\CadastroObra;
use App\Models\User;

use Carbon\Carbon;

class Estatistica
{
    public static function users()
    {
        return User::count();
    }

    public static function empresas()
    {
        return CadastroEmpresa::where('status', 'Ativo')->count();
    }

    public static function fornecedores()
    {
        return CadastroFornecedor::where('status', 'Ativo')->count();
    }

    public static function funcionarios($obra)
    {
        if(is_int($obra) && $obra > 0) {
            return CadastroFuncionario::where('status', 'Ativo')->where('id_obra', $obra)->count();
        } else {
            return CadastroFuncionario::where('status', 'Ativo')->count();
        }
    }


    public static function obras()
    {
        return CadastroObra::where('status_obra', 'Ativo')->count();
    }

    public static function aniversariantes($obra)
    {
        $now = Carbon::now();

        if(is_int($obra) && $obra > 0) {
            return CadastroFuncionario::where('id_obra', $obra)->whereMonth('data_nascimento', $now->month)->get();
        } else {
            return CadastroFuncionario::whereMonth('data_nascimento', $now->month)->get();
        }

    }
}

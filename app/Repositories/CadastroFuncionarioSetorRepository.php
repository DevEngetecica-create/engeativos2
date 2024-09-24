<?php

namespace App\Repositories;

use App\Interfaces\CadastroFuncionarioSetorRepositoryInterface;
use App\Models\CadastroFuncionarioSetor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CadastroFuncionarioSetorRepository implements CadastroFuncionarioSetorRepositoryInterface
{
    public function getAll()
    {
        return CadastroFuncionarioSetor::all();
    }

    public function getById($id)
    {
        return CadastroFuncionarioSetor::findOrFail($id);
    }

    public function create(array $data)
    {
        $setor = new CadastroFuncionarioSetor;

        $setor->nome_setor = $data['nome_setor'];
        $setor->user_create = Auth::user()->email;

        $setor->save();

        Log::info('Setor criado', ['setor' => $data['nome_setor']]);
        return $setor;
    }

    public function update($id, array $data)
    {
        $setor = CadastroFuncionarioSetor::findOrFail($id);
        $setor->nome_setor = $data['nome_setor'];
        $setor->user_edit = Auth::user()->email;

        $setor->update();

        Log::info('Setor atualizado', ['setor' =>  $data['nome_setor']]);
        return $setor;
    }

    public function delete($id)
    {
        $setor = CadastroFuncionarioSetor::findOrFail($id);
        $setor->delete();
        Log::info('Setor deletetado', ['setor' => $setor->nome_setor]);
        return $setor;
    }

    public function search($keyword)
    {
        return CadastroFuncionarioSetor::where('nome_setor', 'like', "%$keyword%")->get();
    }

    public function paginate($perPage)
    {
        return CadastroFuncionarioSetor::paginate($perPage);
    }
}
<?php

namespace App\Repositories;

use App\Interfaces\VeiculoAbastecimentoRepositoryInterface;
use App\Models\VeiculoAbastecimento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VeiculoAbastecimentoRepository implements VeiculoAbastecimentoRepositoryInterface
{
    public function getAll()
    {
        return VeiculoAbastecimento::all();
    }

    public function getById($id)
    {
        return VeiculoAbastecimento::findOrFail($id);
    }

    public function create(array $data)
    {

        $valor_do_litro = $this->formatCurrency($data['valor_do_litro']);
        $valor_total = $this->formatCurrency($data['valor_total']);

        $arquivo = $data['arquivo'];

        if ($arquivo) {

            if ($data['nome_anexo']) {

                $nome_arquivo = $data['nome_anexo'];

            } else {

                $nome_arquivo = $arquivo->getClientOriginalName();
            }

            $caminho_arquivo = 'uploads/abastecimentos/' . $data['veiculo_id'];

            // Armazena o novo arquivo
            $arquivo->storeAs($caminho_arquivo, $nome_arquivo, 'public');
        }

        $abastecimento = new VeiculoAbastecimento;
        $abastecimento->veiculo_id = $data['veiculo_id'];
        $abastecimento->id_funcionario = $data['id_funcionario'];
        $abastecimento->user_create = Auth::user()->email;
        $abastecimento->fornecedor = $data['fornecedor'];
        $abastecimento->combustivel = $data['combustivel'];
        $abastecimento->quilometragem = $data['quilometragem_nova'];
       // $abastecimento->horimetro = $data['horimetro'];
        $abastecimento->valor_do_litro = $valor_do_litro;
        $abastecimento->quantidade = $data['quantidade'];
        $abastecimento->data_cadastro = $data['data_cadastro'];
        $abastecimento->valor_total = $valor_total;
        $abastecimento->arquivo = $nome_arquivo ?? "";

        $abastecimento->save();

        Log::info('Abastecimento criado', ['abastecimento' => $abastecimento]);

        return $abastecimento;
    }

    public function update($id, array $data)
    {
        $abastecimento = VeiculoAbastecimento::findOrFail($id);
        $abastecimento->update($data);
        Log::info('Abastecimento atualizado', ['abastecimento' => $abastecimento]);
        return $abastecimento;
    }

    public function delete($id)
    {
        $abastecimento = VeiculoAbastecimento::findOrFail($id);
        $abastecimento->delete();
        Log::info('Abastecimento deletado', ['abastecimento' => $abastecimento]);
        return $abastecimento;
    }

    public function search($keyword)
    {
        return VeiculoAbastecimento::where('fornecedor', 'like', "%$keyword%")
            ->orWhere('combustivel', 'like', "%$keyword%")
            ->get();
    }

    public function paginate($perPage)
    {
        return VeiculoAbastecimento::paginate($perPage);
    }

    private function formatCurrency($value, $isTotal = false)
    {
        if ($isTotal) {
            $value = str_replace('.', '', $value);
        }
        return str_replace(',', '.', $value);
    }
}

<?php

namespace App\Repositories;

use App\Interfaces\VeiculoAbastecimentoRepositoryInterface;
use App\Models\VeiculoAbastecimento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        $abastecimento->bandeira = $data['bandeira'];
        $abastecimento->combustivel = $data['combustivel'];
        $abastecimento->km_inicial = $data['km_inicial'];
        $abastecimento->km_final = $data['km_final'];
        $abastecimento->valor_do_litro = $valor_do_litro;
        $abastecimento->quantidade = $data['quantidade'];
        $abastecimento->data_abastecimento = $data['data_abastecimento'];
        $abastecimento->valor_total = $valor_total;
        $abastecimento->arquivo = $nome_arquivo ?? "";

        $abastecimento->save();

        Log::info('Abastecimento criado', ['abastecimento' => $abastecimento]);

        return $abastecimento;
    }

    public function update($id, array $data)
    {
        $abastecimento = VeiculoAbastecimento::findOrFail($id);

        $valor_do_litro = $this->formatCurrency($data['valor_do_litro']);
        $valor_total = $this->formatCurrency($data['valor_total']); 
        $arquivos = $data['arquivo'] ?? null; // Verifica se o campo 'arquivo' está presente

        // Define $image_name como a imagem existente por padrão
        $nome_arquivo = $abastecimento->arquivo;

        if ($arquivos) { // Verifica se o arquivo foi enviado

            $nome_arquivo = $arquivos->getClientOriginalName() ?? $data['nome_anexo'];
            $caminho_arquivo = 'uploads/manutencao/' . $data['veiculo_id'];

            // Verifica se o arquivo já existe e o exclui antes de salvar o novo
            if (Storage::disk('public')->exists($caminho_arquivo)) {
                Storage::disk('public')->delete($caminho_arquivo);
            }
            // Armazena o novo arquivo
            $arquivos->storeAs($caminho_arquivo, $nome_arquivo, 'public');
        }

        $abastecimento->veiculo_id = $data['veiculo_id'];
        $abastecimento->id_obra = $data['id_obra'];
        $abastecimento->id_funcionario = $data['id_funcionario'];
        $abastecimento->user_edit = Auth::user()->email;        
        $abastecimento->bandeira = $data['bandeira'];
        $abastecimento->combustivel = $data['combustivel'];
        $abastecimento->km_inicial = $data['km_inicial'];
        $abastecimento->km_final = $data['km_final'];
        $abastecimento->valor_do_litro = $valor_do_litro;
        $abastecimento->quantidade = $data['quantidade'];
        $abastecimento->data_abastecimento = $data['data_abastecimento'];
        $abastecimento->valor_total = $valor_total;
        $abastecimento->arquivo = $nome_arquivo;

        $abastecimento->save();

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

<?php

namespace App\Repositories;

use App\Interfaces\VeiculoManutencaoRepositoryInterface;
use App\Models\VeiculoImagens;
use App\Models\VeiculoManutencao;
use App\Models\VeiculoManutencaoImagens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VeiculoManutencaoRepository implements VeiculoManutencaoRepositoryInterface
{
    public function getAll()
    {
        return VeiculoManutencao::all();
    }

    public function getById($id)
    {
        return VeiculoManutencao::findOrFail($id);
    }

    public function create(array $data, $arquivos)
    {
        $manutencao = VeiculoManutencao::create($data);

        $manutencao->user_create = Auth::user()->email;

        if ($arquivos) {

            $nome_arquivo = $arquivos->getClientOriginalName();
            $caminho_arquivo = 'uploads/manutencao';

            // Armazena o novo arquivo
            $arquivos->storeAs($caminho_arquivo, $nome_arquivo, 'public');
        }

        Log::info('Manutenção criada', ['manutencao' => $manutencao]);

        return $manutencao;
    }

    public function update($id, array $data, $arquivos)
    {
        $manutencao = VeiculoManutencao::findOrFail($id);
        $manutencao->user_edit = Auth::user()->email;

        if ($arquivos) {

            $nome_arquivo = $data['descricao'] ?? $arquivos->getClientOriginalName();

            $caminho_arquivo = 'uploads/manutencao';

            // Armazena o novo arquivo
            $arquivos->storeAs($caminho_arquivo, $nome_arquivo, 'public');
        }

        $manutencao->update($data);

        Log::info('Manutenção atualizada', ['manutencao' => $manutencao]);

        return $manutencao;
    }

    public function upload($id, array $data, $arquivos)
    {
        $manutencao = VeiculoManutencao::findOrFail($id);

        if ($arquivos) {

            $nome_arquivo = $arquivos->getClientOriginalName();
            $caminho_arquivo = 'uploads/manutencao/'. $manutencao->arquivo;

            // Verifica se o arquivo já existe e o exclui antes de salvar o novo
            if (Storage::disk('public')->exists($caminho_arquivo)) {
                Storage::disk('public')->delete($caminho_arquivo);
            }
            // Atualiza o campo de nome de arquivo no banco de dados
            $manutencao->user_edit = Auth::user()->email;
            $manutencao->arquivo = $nome_arquivo;
    
            $caminho_arquivo = 'uploads/manutencao';
            // Armazena o novo arquivo
            $arquivos->storeAs($caminho_arquivo, $nome_arquivo, 'public');
    
            $manutencao->save();
    
        }
        
        Log::info('Manutenção atualizada', ['manutencao' => $manutencao]);

        return $manutencao;
    }

    public function delete($id)
    {
        $manutencao = VeiculoManutencao::findOrFail($id);
        $manutencao->delete();
        Log::info('Manutenção deletada', ['manutencao' => $manutencao]);
        return $manutencao;
    }

    public function search($keyword)
    {
        return VeiculoManutencao::where('descricao', 'like', "%$keyword%")
            ->orWhere('tipo', 'like', "%$keyword%")
            ->get();
    }

    public function paginate($perPage)
    {
        return VeiculoManutencao::paginate($perPage);
    }

    public function storeImage($id, $imagens)
    {

        foreach ($imagens as $imagem) {

            $image_name = $imagem->getClientOriginalName();


            $veiculoImagem = new VeiculoManutencaoImagens();
            $veiculoImagem->manutencao_id = $id;
            $veiculoImagem->nome_imagem = $image_name;  //Aqui salvamos apenas o nome da imagem
            $veiculoImagem->save();

            $imagem->move(public_path("imagens/veiculos/manutencoes/" . $id), $image_name);
        }

        return true;
    }


    public function updateImage($id, $imagem, $data)
    {
        // Encontra o registro da imagem no banco de dados
        $veiculoImagem = VeiculoManutencaoImagens::findOrFail($data['id_imagem']);


        if ($imagem) {
            // Defina o caminho completo da imagem antiga
            $imagePath = public_path("imagens/veiculos/manutencoes/" . $data['manutencao_id'] . "/" . $veiculoImagem->nome_imagem);

            // Verifique se o arquivo existe e, se sim, exclua-o
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Salva a nova imagem
            $imageName = $data['descricao'] ?? $imagem->getClientOriginalName();

            $imagem->move(public_path("imagens/veiculos/manutencoes/" . $data['manutencao_id'] . "/"), $imageName);

            // Atualiza o nome da imagem e a descrição no banco de dados
            $veiculoImagem->nome_imagem = $imageName;
            $veiculoImagem->descricao = $data['descricao'];
        } else {
            // Se não houver nova imagem, apenas atualize a descrição
            $veiculoImagem->descricao = $data['descricao'];
        }

        // Salva as mudanças no banco de dados
        $veiculoImagem->save();

        return true;
    }


    public function deleteImage($id)
    {
        $veiculoImagem = VeiculoManutencaoImagens::findOrFail($id);

        // Defina o caminho completo da imagem
        $imagePath = public_path("imagens/veiculos/manutencoes/" . $veiculoImagem->id . "/" . $veiculoImagem->nome_imagem);

        // Verifique se o arquivo existe e, se sim, exclua-o
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Exclua o registro do banco de dados
        $veiculoImagem->delete();
    }

    public function download($id)
    {

        try {

            $manutencao_arquivo = VeiculoManutencao::findOrFail($id);

            // Caminho do arquivo no storage público
            $path = "uploads/manutencao/" . $manutencao_arquivo->arquivo;

            // Verifica se o arquivo existe no disco 'public'
            if (Storage::disk('public')->exists($path)) {

                return Storage::disk('public')->download($path);

            } else {

                throw new \Exception('Arquivo não encontrado.');
            }
        } catch (\Exception $e) {

            Log::error('Erro ao fazer o download: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao fazer o download');
        }
    }
}

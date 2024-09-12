<?php

namespace App\Repositories;

use App\Interfaces\VeiculoRepositoryInterface;
use App\Interfaces\VeiculosDocsLegaisRepositoryInterface;
use App\Interfaces\VeiculosDocsTecnicosRepositoryInterface;
use App\Interfaces\VeiculoQuilometragemRepositoryInterface;
use App\Models\Veiculo;
use App\Models\VeiculoImagens;
use App\Model\Log;
use App\Models\VeiculoSubCategoria;
use Flasher\Laravel\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VeiculoRepository implements VeiculoRepositoryInterface
{
    private $docs_legais;
    private $docs_tecnicos;
    private $km;

    public function __construct(
        VeiculosDocsLegaisRepositoryInterface $docs_legais,
        VeiculosDocsTecnicosRepositoryInterface $docs_tecnicos,
        VeiculoQuilometragemRepositoryInterface $km
    ) {
        $this->docs_legais = $docs_legais;
        $this->docs_tecnicos = $docs_tecnicos;
        $this->km = $km;
    }

    public function getAll()
    {
        return Veiculo::orderBy('id', 'desc')->get();
    }

    public function getById($id)
    {
        return Veiculo::findOrFail($id);
    }

    public function create(array $data, $imagem)
    {
        if ($imagem != "") {
            $image_name = $imagem->getClientOriginalName();
        }

        // Converte '126.173,00' para '126173.00'
        $data['valor_fipe'] = str_replace(['.', ','], ['', '.'], $data['valor_fipe']);
        $data['valor_aquisicao'] = str_replace(['.', ','], ['', '.'], $data['valor_aquisicao']);

        // dd($data);

        $veiculo = new Veiculo;

        $veiculo->obra_id = $data['obra_id'];
        $veiculo->idCategoria = $data['idCategoria'];
        $veiculo->idSubCategoria = $data['idSubCategoria'];
        $veiculo->id_preventiva = $data['id_preventiva'];
        $veiculo->user_create = Auth::user()->email;
        $veiculo->tipos = $data['tipo'];
        $veiculo->marca = $data['marca_nome'];
        $veiculo->modelo = $data['modelo_nome'];
        $veiculo->ano = $data['ano'];
        $veiculo->veiculo = $data['veiculo'];
        $veiculo->valor_fipe = $data['valor_fipe'];
        $veiculo->valor_aquisicao = $data['valor_aquisicao'];
        $veiculo->codigo_fipe = $data['codigo_fipe'];
        $veiculo->fipe_mes_referencia = $data['fipe_mes_referencia'];
        $veiculo->mes_aquisicao = $data['mes_aquisicao'];
        $veiculo->placa = $data['placa'];
        $veiculo->quilometragem_inicial = $data['quilometragem_inicial'];
        $veiculo->observacao = $data['observacao'];
        $veiculo->situacao = $data['situacao'];
        $veiculo->imagem = $image_name ?? "";

        $veiculo->save();

        //Passar o array $veiculo com os dados salvos
        $this->docs_legais->store($veiculo->toArray());
        $this->km->create($veiculo->toArray());


        if ($imagem != "") {
            $image_name = $imagem->getClientOriginalName();
            $imagem->move(public_path("imagens/veiculos/" . $veiculo->id), $image_name);
        }

        return true; // Retorna true após salvar todos os registros

    }

    public function update($id, array $data)
    {
        $veiculo = Veiculo::findOrFail($id);

        // Verifica se uma nova imagem foi enviada
        $imagem = $data['imagem'] ?? null; // Verifica se o campo 'imagem' está presente

        // Define $image_name como a imagem existente por padrão
        $image_name = $veiculo->imagem;

        if ($imagem) { // Verifica se o arquivo foi enviado
            $image_name = $imagem->getClientOriginalName(); // Obtém o nome da nova imagem

            // Define o caminho completo da imagem antiga
            $imagePath = 'uploads/veiculos/' . $id . '/' . $veiculo->imagem;

            // Verifica se o arquivo existe e, se sim, exclui-o
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Armazena o novo arquivo
            $imagem->move(public_path("imagens/veiculos/" . $id), $image_name);
        }

        // Converte '126.173,00' para '126173.00'
        $data['valor_fipe'] = str_replace(['.', ','], ['', '.'], $data['valor_fipe']);
        $data['valor_aquisicao'] = str_replace(['.', ','], ['', '.'], $data['valor_aquisicao']);

        // Atualiza os dados do veículo
        $veiculo->obra_id = $data['obra_id'];
        $veiculo->idCategoria = $data['idCategoria'];
        $veiculo->idSubCategoria = $data['idSubCategoria'];
        $veiculo->id_preventiva = $data['id_preventiva'];
        $veiculo->user_edit = Auth::user()->email;
        $veiculo->tipo = $data['tipo'];
        $veiculo->marca = $data['marca_nome'];
        $veiculo->modelo = $data['modelo_nome'];
        $veiculo->ano = $data['ano'];
        $veiculo->veiculo = $data['veiculo'];
        $veiculo->valor_fipe = $data['valor_fipe'];
        $veiculo->valor_aquisicao = $data['valor_aquisicao'];
        $veiculo->codigo_fipe = $data['codigo_fipe'];
        $veiculo->fipe_mes_referencia = $data['fipe_mes_referencia'];
        $veiculo->mes_aquisicao = $data['mes_aquisicao'];
        $veiculo->placa = $data['placa'];
        $veiculo->quilometragem_inicial = $data['quilometragem_inicial'];
        $veiculo->observacao = $data['observacao'];
        $veiculo->situacao = $data['situacao'];

        // Atualiza a imagem do veículo
        $veiculo->imagem = $image_name;

        // Salva as alterações no banco de dados
        $veiculo->save();

        return true;
    }


    public function delete($id)
    {
        return Veiculo::destroy($id);
    }

    public function search($perPage, $keyword)
    {
        //dd("1");
        return Veiculo::where('veiculo', 'like', "%$keyword%")
            ->orWhere('placa', 'like', "%$keyword%")
            ->orWhere('tipo', 'like', "%$keyword%")
            ->orWhere('marca', 'like', "%$keyword%")
            ->orWhere('modelo', 'like', "%$keyword%")
            ->paginate($perPage);
    }

    public function paginate($perPage, $keyword)
    {

        return Veiculo::where(function ($query) use ($keyword) {
            $query->where('veiculo', 'like', "%$keyword%")
                ->orWhere('placa', 'like', "%$keyword%")
                ->orWhere('tipo', 'like', "%$keyword%")
                ->orWhere('marca', 'like', "%$keyword%")
                ->orWhere('modelo', 'like', "%$keyword%");
        })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }


    public function storeImage($id, $imagens)
    {
        foreach ($imagens as $imagem) {

            $image_name = $imagem->getClientOriginalName();

            $imagem->move(public_path("imagens/veiculos/" . $id), $image_name);

            $veiculoImagem = new VeiculoImagens();
            $veiculoImagem->veiculo_id = $id;
            $veiculoImagem->imagens = $image_name;  // Aqui salvamos apenas o nome da imagem
            $veiculoImagem->save();
        }

        return true;
    }


    public function updateImage($id, $imagem, $data)
    {
        // Encontra o registro da imagem no banco de dados
        $veiculoImagem = VeiculoImagens::findOrFail($data['id_imagem']);

        if ($imagem) {

            // Defina o caminho completo da imagem antiga
            $imagePath = public_path("imagens/veiculos/" . $data['veiculo_id'] . "/" . $veiculoImagem->imagens);

            // Verifique se o arquivo existe e, se sim, exclua-o
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Salva a nova imagem
            $imageName = $imagem->getClientOriginalName();
            $imagem->move(public_path("imagens/veiculos/" . $data['veiculo_id'] . "/"), $imageName);

            // Atualiza o nome da imagem e a descrição no banco de dados
            $veiculoImagem->imagens = $imageName;
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
        $veiculoImagem = VeiculoImagens::findOrFail($id);

        // Defina o caminho completo da imagem
        $imagePath = public_path("imagens/veiculos/" . $veiculoImagem->veiculo_id . "/" . $veiculoImagem->imagens);

        // Verifique se o arquivo existe e, se sim, exclua-o
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Exclua o registro do banco de dados
        $veiculoImagem->delete();
    }

    public function download($id)
    {
        // Implementar lógica para download de imagem
    }
}

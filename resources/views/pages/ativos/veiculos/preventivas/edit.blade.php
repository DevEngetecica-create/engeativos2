@extends('dashboard')

<style>
    #titulo {
        overflow: hidden;
        top: 8%;
        background-color: white;
        z-index: 1000;
        padding-bottom: 50px;
    }

    #div_acoes {
        overflow: hidden;
        background-color: white;
        z-index: 1000;

    }

    .main {
        padding-top: 16px;
        height: calc(90vh - 75px);
        overflow-y: auto;
        overflow-x: hidden;
    }
</style>

@section('content')
    <div class="container-fluid">
        <div class="row mb-3" id="titulo">
            <div class="col-sm-12 col-xl-3 col-xxl-3">
                <h4 class="mb-5">Editar Manutenção Preventiva</h4>
            </div>
            <div class="col-sm-12 col-xl-2 col-xxl-2">
                <ul class="list-group">
                    <li class="list-group-item active form-control-sm"><small>Legenda da situação</small></li>
                    <li class="list-group-item form-control-sm py-1"><small>&#9899; Obrigatória</small></li>
                    <li class="list-group-item form-control-sm py-1"><small>&#9673; Executar conforme condição</small></li>
                    <li class="list-group-item form-control-sm py-1"><small>&#9650; Conferir/Verificar</small></li>
                </ul>
            </div>
        </div>

        <div id="formulario">
            <form action="{{ route('veiculo.manut_preventiva.update', $preventiva->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="container">
                    <div class="row shadow mb-4" id="div_acoes">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="tipo_veiculo">Tipo de Veículo</label>
                                <select class="form-control-sm form-select" id="tipo_veiculo" name="tipo_veiculo" required>
                                    <option value="">Selecione</option>
                                    <option value="1"
                                        {{ old('tipo_veiculo', $preventiva->tipo_veiculo) == '1' ? 'selected' : '' }}>Carro
                                    </option>
                                    <option value="2"
                                        {{ old('tipo_veiculo', $preventiva->tipo_veiculo) == '2' ? 'selected' : '' }}>Moto
                                    </option>
                                    <option value="3"
                                        {{ old('tipo_veiculo', $preventiva->tipo_veiculo) == '3' ? 'selected' : '' }}>
                                        Caminhão</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="form-group">
                                <label for="nome_preventiva">Nome da Preventiva</label>
                                <input type="text" name="nome_preventiva" id="nome_preventiva" class="form-control form-control-sm" value="{{ old('nome_preventiva', $preventiva->nome_preventiva) }}" required>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label>Ações</label>
                                <div>
                                    <a class="listar-ativos-adicionar" id="listar-ativos-adicionar">
                                        <span class="btn btn-primary text-white py-1 px-2 rounded mx-2"><i class="fa fa-plus"></i></span>
                                    </a>
                                    <a class="listar-ativos-remover" id="listar-ativos-remover">
                                        <span class="btn btn-warning text-white py-1 px-2 rounded"><i lass="fa fa-minus"></i></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="main" id="listar-ativos-linha">
                        @foreach (json_decode($preventiva->nome_servico) as $key => $nome_servico)
                            <div class="row template-row mt-2 mb-1">
                                <div class="col-7">
                                    <div class="form-group">
                                        <label for="nome_servico">Implemento</label>
                                        <input type="text" name="nome_servico[]" id="nome_servico"
                                            class="form-control form-control-sm" value="{{ $nome_servico }}" required>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="situacao">Situação</label>
                                        <select name="situacao[]" id="situacao" class="form-control form-control-sm"
                                            required>
                                            <option value="1"
                                                {{ json_decode($preventiva->situacao)[$key] == '1' ? 'selected' : '' }}>&#9899;</option>
                                            <option value="2"
                                                {{ json_decode($preventiva->situacao)[$key] == '2' ? 'selected' : '' }}>&#9673;</option>
                                            <option value="3"
                                                {{ json_decode($preventiva->situacao)[$key] == '3' ? 'selected' : '' }}>&#9650;</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-1">
                                    <div class="form-group">
                                        <label for="periodo">Período</label>
                                        <input type="text" name="periodo[]" id="periodo"
                                            class="form-control form-control-sm"
                                            value="{{ json_decode($preventiva->periodo)[$key] }}" required>
                                    </div>
                                </div>
                                <div class="col-1">
                                    <div class="form-group">
                                        <label for="tipo">Tipo</label>
                                        <select name="tipo[]" id="tipo" class="form-control form-control-sm" required>
                                            <option value="km"
                                                {{ json_decode($preventiva->tipo)[$key] == 'km' ? 'selected' : '' }}>Horas
                                            </option>
                                            <option value="hr"
                                                {{ json_decode($preventiva->tipo)[$key] == 'hr' ? 'selected' : '' }}>
                                                Quilometros</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <template id="listar-ativos-template">
                        <div class="row template-row mt-4">
                            <div class="col-7">
                                <div class="form-group">
                                    <label for="nome_servico">Nome do Serviço</label>
                                    <input type="text" name="nome_servico[]" id="nome_servico"
                                        class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="situacao">Situação</label>
                                    <select name="situacao[]" id="situacao" class="form-control form-control-sm" required>
                                        <option selected>Selecionar</option>
                                        <option value="1">&#9899; Obrigatória</option>
                                        <option value="2">&#9673; Executar conforme condição</option>
                                        <option value="3">&#9650; Conferir/Verificar</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-1">
                                <div class="form-group">
                                    <label for="periodo">Período</label>
                                    <input type="text" name="periodo[]" id="periodo"
                                        class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="col-1">
                                <div class="form-group">
                                    <label for="tipo">Tipo</label>
                                    <select name="tipo[]" id="tipo" class="form-control form-control-sm" required>
                                        <option selected>Selecionar</option>
                                        <option value="km">Horas</option>
                                        <option value="hr">Quilometros</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </template>

                    <button type="submit" class="btn btn-success mt-3">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>

    <script>
        $(document).ready(function() {
            $('.listar-ativos-remover').on("click", function() {
                $(".template-row:last").remove();
            });

            $('.listar-ativos-adicionar').click(function() {
                $('#listar-ativos-linha').append($('#listar-ativos-template').html());
            });
        });
    </script>
@endsection

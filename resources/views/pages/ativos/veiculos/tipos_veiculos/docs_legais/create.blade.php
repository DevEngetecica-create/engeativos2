@extends('dashboard')

@section('content')
    <div class="card m-5">
        <div class="card-body m-3">
            <div class="card-header mb-3">

                <h1>Cadastar Documento Legal do Tipo de Veículo</h1>
            </div>

            <form action="{{ route('docs_legais.store') }}" method="POST">
                @csrf
                <div class="row mb-5">
                    <div class="col-3">
                        <label>Tipo de Veículo</label>
                        @foreach ($tipo_veiculo as $tipo)
                            @if ($tipo->id == $tipo_veiculo_id)
                                <!-- Right Ribbon -->
                                <div class="card ribbon-box border shadow-none right mb-lg-0 material-shadow"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Este campo é bloqueado porque você esta cadastrando Documentos para o tipo de Veículo -> {{ $tipo->nome_tipo_veiculo }}">
                                    <div class="card-body">
                                        <div class="ribbon ribbon-info round-shape">Bloqueado para edição</div>

                                        <input type="hidden" name="tipo_veiculo" value="{{ $tipo->id }}">
                                        {{ $tipo->nome_tipo_veiculo }}
                                    </div>
                                </div>                               
                            @endif
                        @endforeach

                    </div>

                    <div class="col-2">
                        <div class="form-group">
                            <label>Adicionar ou Remover Campos</label>
                            <div>
                                <a class="listar-ativos-adicionar" id="listar-ativos-adicionar">
                                    <span class="btn btn-primary text-white py-1 px-2 rounded mx-2"><i
                                            class="mdi mdi-plus mdi-18px"></i></span>
                                </a>
                                <a class="listar-ativos-remover" id="listar-ativos-remover">
                                    <span class="btn btn-warning text-white py-1 px-2 rounded"><i
                                            class="mdi mdi-minus mdi-18px"></i></span>
                                </a>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-5">
                        <label>Nome Documento</label>
                        <input class="form-control form-control-sm" type="text" name="nome_documento[]" required>
                    </div>

                    <div class="col-2">
                        <label>Validade (em meses)</label>
                        <input class="form-control form-control-sm" type="number" name="validade[]" required>
                    </div>
                </div>

                <div id="listar-ativos-linha"></div>

                <template id="listar-ativos-template">
                    <div class="row template-row mt-3">
                        <div class="col-5">
                            <label>Nome Documento</label>
                            <input class="form-control form-control-sm" type="text" name="nome_documento[]" required>
                        </div>

                        <div class="col-2">
                            <label>Validade (em meses)</label>
                            <input class="form-control form-control-sm" type="number" name="validade[]" required>
                        </div>
                    </div>

                </template>

                <button class="btn btn-primary btn-ms mt-4" type="submit">Salvar</button>

            </form>

        </div>
    </div>
@endsection

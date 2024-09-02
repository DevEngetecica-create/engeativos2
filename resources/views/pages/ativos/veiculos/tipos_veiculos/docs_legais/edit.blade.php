@extends('dashboard')

@section('content')
    <div class="card m-5">
        <div class="card-body m-3">
            <div class="card-header mb-3">
                <h1>Editar Documento Legal do Tipo de Veículo</h1>
            </div>

            <form action="{{ route('docs_legais.update', $doc->id) }}" method="POST">
                @csrf
                @method("PUT")
                <div class="row mb-5">
                    <div class="col-3">
                        <label>Tipo de Veículo</label>
                        @foreach ($tipo_veiculo as $tipo)
                            @if ($tipo->id == $doc->tipo_veiculo)
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
                </div>

                <div class="row">
                    <div class="col-5">
                        <label>Nome Documento</label>
                        <input class="form-control form-control-sm" type="text" name="nome_documento" value="{{ $doc->nome_documento }}">
                    </div>

                    <div class="col-3">
                        <label>Validade (em meses)</label>
                        <input class="form-control form-control-sm" type="number" name="validade" value="{{ $doc->validade }}">
                    </div>
                </div>

                <button class="btn btn-primary btn-ms mt-4" type="submit">Salvar</button>

            </form>

        </div>
    </div>
@endsection



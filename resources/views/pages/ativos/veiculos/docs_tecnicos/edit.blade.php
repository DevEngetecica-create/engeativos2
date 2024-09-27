@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">

                <h1>Editar Documento Técnico</h1>
                <form action="{{ route('veiculo_docs_tecnico.update', $doc->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row mb-5">

                        <div class="col-3">
                            <label>Tipo de Veículo</label>

                            @foreach ($tipo_veiculo as $tipo)
                                @if ($tipo->id == $doc->tipo_veiculo)
                                    <input type="hidden" name="tipo_veiculo" value="{{ $tipo->id }}">
                                    <input type="text" class="form-control" value="{{ $tipo->nome_tipo_veiculo }}" readonly>
                                @endif
                            @endforeach

                        </div>

                        <div class="col-2">
                            <div class="form-group">
                                <label>Ações</label>
                                <div>
                                    <a class="listar-ativos-adicionar" id="listar-ativos-adicionar">
                                        <span class="btn btn-primary text-white py-1 px-2 rounded mx-2"><i
                                                class="fa fa-plus"></i></span>
                                    </a>
                                    <a class="listar-ativos-remover" id="listar-ativos-remover">
                                        <span class="btn btn-warning text-white py-1 px-2 rounded"><i
                                                class="fa fa-minus"></i></span>
                                    </a>
                                </div>
                            </div>
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
    </div>
@endsection

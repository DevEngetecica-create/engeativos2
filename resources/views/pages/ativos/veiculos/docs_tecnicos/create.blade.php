@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h1>Cadastrar Documento Legal</h1>
                <form action="{{ route('docs_legais.store') }}" method="POST">
                    @csrf
                    <div class="row mb-5">

                        
                        <div class="col-3">
                            <label>Tipo de Veículo</label>

                            @foreach($tipo_veiculo as $tipo)
                            
                                @if($tipo->id == $tipo_veiculo_id)                              
                                    <input type="hidden" name="tipo_veiculo" value="{{$tipo->id}}">
                                    <input type="text"  class="form-control" value="{{$tipo->nome_tipo_veiculo}}" readonly>
                                @endif
                            @endforeach
                        </div>

                        <div class="col-2">
                            <div class="form-group">
                                <label>Ações</label>
                                <div>
                                    <a class="listar-ativos-adicionar" id="listar-ativos-adicionar">
                                        <span class="btn btn-primary text-white py-1 px-2 rounded mx-2"><i class="fa fa-plus"></i></span>
                                    </a>
                                    <a class="listar-ativos-remover" id="listar-ativos-remover">
                                        <span class="btn btn-warning text-white py-1 px-2 rounded"><i class="fa fa-minus"></i></span>
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

                        <div class="col-3">
                            <label>Validade (em meses)</label>
                            <input class="form-control form-control-sm" type="number" name="validade[]" required>
                        </div>
                    </div>

                    <div id="listar-ativos-linha"></div>

                    <template id="listar-ativos-template">
                        <div class="row template-row mt-4">
                            <div class="col-5">
                                <label>Nome Documento</label>
                                <input class="form-control form-control-sm" type="text" name="nome_documento[]" required>
                            </div>

                            <div class="col-3">
                                <label>Validade (em meses)</label>
                                <input class="form-control form-control-sm" type="number" name="validade[]" required>
                            </div>
                        </div>

                    </template>

                    <button class="btn btn-primary btn-ms mt-4" type="submit">Cadastrar</button>
                    
                </form>

            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>

    <script>
        $(document).ready(function() {
            $('.listar-ativos-remover').on("click", function() {
                $(".template-row:last").remove();
            });

            $('.listar-ativos-adicionar').click(function() {
                $('#listar-ativos-linha').append($('#listar-ativos-template').html());
                $(".template:last").select2();
            });



            $('.listar-epis-remover').on("click", function() {
                $(".template-row-epis:last").remove();
            });

            $('.listar-epis-adicionar').click(function() {
                $('#listar-epis-linha').append($('#listar-epis-template').html());
                $(".template:last").select2();
            });
        });
    </script>
@endsection

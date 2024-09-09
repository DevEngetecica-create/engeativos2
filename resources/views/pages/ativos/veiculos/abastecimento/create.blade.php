@extends('dashboard')
@section('title', 'Veículo')
@section('content')

<div class="card shadow-sm">
    <div class="card-body">
        
    
    <div class="row mt-4">
        <div class="col-8 breadcrumb-item active" aria-current="page">
            <h3 class="page-title text-left">
                
                @if ($veiculo->tipo == 'maquinas')
                    <span class="page-title-icon bg-gradient-primary me-2">
                        <i class="mdi mdi-gas-station mdi-36px"></i>
                    </span> 
                    
                    Abastecimento da máquina  <i class="mdi mdi-arrow-right-thin mdi-36px"></i>  <small class="font-weight-bold">{{ $veiculo->marca }} | {{ $veiculo->modelo }} | {{ $veiculo->veiculo }}</small>
                @else
                    <span class="page-title-icon bg-gradient-primary me-2">
                        <i class="mdi mdi-gas-station mdi-36px"></i>
                    </span> 
                
                    Abastecimento do veículo  <i class="mdi mdi-arrow-right-thin mdi-36px"></i>  <small class="font-weight-bold">{{ $veiculo->marca }} | {{ $veiculo->modelo }} | {{ $veiculo->veiculo }}</small>
                    
                @endif
            </h3>
        </div>

    </div>

    <hr>
    


        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body p-5">
        
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Ops!</strong><br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
        
                        <form method="post" action="{{ route('ativo.veiculo.abastecimento.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="jumbotron mb-4">
                                <input type="hidden" name="tipo" value="{{$veiculo->tipo}}">
                                <span class="font-weight-bold bg-success p-1 px-3 rounded text-white">{{ $veiculo->marca }} | {{ $veiculo->modelo }} | {{ $veiculo->veiculo }}</span>
                            </div>
        
                            <div class="row my-4">
                                <div class="col-md-2">
                                    <label class="form-label" for="fornecedor_id">Data do abastecimento</label>
                                    <input type="date" class="form-control" id="data_cadastro" name="data_cadastro">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="fornecedor_id">Local do abastecimento</label>
                                    <input type="text" class="form-control" id="fornecedor" name="fornecedor">
                                </div>
        
                               
        
                               {{--  @php
                                $ultimoHorimetro = $veiculo->horimetro->last() ?? 0;
                                @endphp --}}
        
                                @php
                                $ultimaQuilometragem = $veiculo->quilometragem()->max('quilometragem_nova');

                                
                                @endphp
        
                                @if($veiculo->tipo =="maquinas")
        
                                <div class="col-md-2">
                                    <label class="form-label" for="horimetro_atual">hr Atual</label>
                                    <input class="form-control" id="horimetro_atual" name="horimetro_atual" type="text" value="{{ $ultimoHorimetro->horimetro_atual ?? 0}}" step="any" readonly>
                                </div>
        
                                <div class="col-md-2">
                                    <label class="form-label" for="horimetro_novo">hr do abastecimento</label>
                                    <input class="form-control" id="horimetro_novo" name="horimetro_novo" type="number" step="any" min="{{$ultimoHorimetro->horimetro_novo ?? 0}}">
                                </div>
        
                                @else
        
        
                                <div class="col-md-1">
                                    <label class="form-label" for="quilometragem_atual">km Atual</label>
                                    <input class="form-control" id="quilometragem_atual" name="quilometragem_atual" type="text" value="{{ $ultimaQuilometragem->quilometragem_nova ?? old('quilometragem_atual') }}" step="any" readonly>
                                </div>
        
                                <div class="col-md-2">
                                    <label class="form-label" for="quilometragem_nova">km do abastecimento</label>
                                    <input class="form-control" id="quilometragem_nova" name="quilometragem_nova" type="number" step="any" min="{{ $ultimaQuilometragem->quilometragem_nova ?? 0 }}">
                                </div>
                                @endif
        
        
                                <div class="col-md-2">
                                    <label class="form-label" for="combustivel">Tipo de Combustível</label>
                                    <select class="form-select" id="combustivel" name="combustivel">
                                        <option value="">Selecione</option>
                                        <option value="etanol_alcool" {{ old('combustivel') == 'etanol_alcool' ? 'selected' : '' }}>Etanol/Alcool</option>
                                        <option value="gasolina" {{ old('combustivel') == 'gasolina' ? 'selected' : '' }}>Gasolina</option>
                                        <option value="diesel" {{ old('combustivel') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                                        <option value="gnv" {{ old('combustivel') == 'gnv' ? 'selected' : '' }}>GNV</option>
                                    </select>
                                </div>
        
                            </div>
        
                            <div class="row mt-3">
        
                                <div class="col-md-2">
                                    <label class="form-label" for="quantidade">Quantidade</label>
                                    <input class="form-control" id="quantidade" name="quantidade" type="te" value="{{ old('quantidade') }}" step="any">
                                </div>
        
                                <div class="col-md-2">
                                    <label class="form-label" for="valor_do_litro">Valor do litro</label>
                                    <input class="form-control" id="valor_do_litro" name="valor_do_litro" type="text" value="{{ old('valor_do_litro') }}" step="any">
                                </div>
        
                                <div class="col-md-2">
                                    <label class="form-label" for="valor_total">Valor total</label>
                                    <input class="form-control" id="valor_total" name="valor_total" type="text" value="{{ old('valor_total') }}" step="any" readonly>
                                </div>
        
                                <div class="col-md-5">
                                    <label class="form-label" for="valor_total">Motorista</label>
                                    <select class="form-select select2" id="id_funcionario" name="id_funcionario">
                                        <option value="">Selecione</option>
                                        @foreach ($funcionarios as $funcionario)
                                        <option value="{{ $funcionario->id }}" @php if(old('id_funcionario', @$store->id_funcionario) == $funcionario->id) echo "selected"; @endphp>
                                            {{ $funcionario->matricula }} - {{ $funcionario->nome }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
        
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="data_de_pagamento">Nome do arquivo</label>
                                    <input class="form-control" id="nome_anexo" name="nome_anexo" type="text" value="{{ old('nome_anexo') }}">
                                </div>
        
                                <div class="col-md-5">
                                    <label class="form-label" for="data_de_pagamento">Inserir arquivo(s)</label>
                                    <input class="form-control" id="arquivo" name="arquivo" type="file" value="{{ old('arquivo') }}">
                                    <span>Extensões permitidas: 'png,' 'jpg', 'jpeg', 'gif', 'pdf', 'excel', 'arquivo compactado'.<span>
                                </div>
                            </div>
        
                            <div class="col-12 mt-5">
                                <input name="veiculo_id" type="hidden" value="{{ $veiculo->id }}">
                                <button class="btn btn-primary btn-md font-weight-medium" type="submit">Salvar</button>
        
                                <a href="{{ url('admin/ativo/veiculo') }}">
                                    <button class="btn btn-warning btn-md font-weight-medium mx-4" type="button">Cancelar</button>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha256-Kg2zTcFO9LXOc7IwcBx1YeUBJmekycsnTsq2RuFHSZU=" crossorigin="anonymous"></script>

<script>
    /*  $(document).ready(function($) {
        
    }); */
</script>
<script>
    $(document).ready(function($) {


        $('#valor_do_litro, #quantidade').on('input', function() {
            // Obtemos os valores dos inputs valor_do_litro e quantidade
            var valor_do_litro = $('#valor_do_litro').val(); // Obtém o valor sem a máscara

            var quantidade = $('#quantidade').val(); // Obtém o valor sem a máscara


            // Calculamos a multiplicação
            var resultado = (valor_do_litro * quantidade);


            // Formatamos o resultado em formato de moeda brasileira
            var resultadoFormatado = resultado.toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            });

            // Exibimos o resultado no input com ID "resultado"
            $('#valor_total').val(resultadoFormatado.replace('R$ ', ''));
        });


    });
</script>

@endsection
@extends('dashboard')
@section('title', 'Veiculo-Abastecimento')
@section('content')

<div class="card shadow-sm">
    <div class="card-body">
        
    
    <div class="row ">
        <div class="col-8 breadcrumb-item active" aria-current="page">
            <h3 class="page-title text-left">
                
                @if ($veiculo->tipo == 'maquinas')
                    <span class="page-title-icon bg-gradient-primary me-2">
                        <i class="mdi mdi-gas-station mdi-36px"></i>
                    </span> 
                    
                    Abastecimento da máquina  <i class="mdi mdi-arrow-right-thin mdi-36px"></i>  <small class="font-weight-bold">{{ $abastecimento->veiculo->marca }} | {{ $abastecimento->veiculo->modelo }} | {{ $$abastecimento->veiculo->veiculo }}</small>
                @else
                    <span class="page-title-icon bg-gradient-primary me-2">
                        <i class="mdi mdi-gas-station mdi-36px"></i>
                    </span> 
                
                    Abastecimento do veículo  <i class="mdi mdi-arrow-right-thin mdi-36px"></i>  <small class="font-weight-bold">{{ $abastecimento->veiculo->marca }} | {{ $abastecimento->veiculo->modelo }} | {{ $abastecimento->veiculo->veiculo }}</small>
                    
                @endif
            </h3>
        </div>
    </div>

    <hr> 

        <div class="container">
            <div class="row">
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

                    <form method="post" action="{{route('ativo.veiculo.abastecimento.update', $abastecimento->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('put')                        
                      
                            <div class="jumbotron ">
                                <input type="hidden" name="tipo" value="{{$veiculo->tipo}}">
                            </div>
        
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <label class="form-label" for="data_abastecimento">Data do abastecimento</label>
                                    <input type="date" class="form-control form-control-sm" id="data_abastecimento" name="data_abastecimento" value="{{$abastecimento->data_abastecimento}}" required>
                                </div>

                                <div class="col-md-5">
                                    <label class="form-label" for="bandeira">Obra</label>
                                    <input type="text" class="form-control form-control-sm bg-light" id="bandeira" name="bandeira" value="{{ session()->get('obra')->codigo_obra }}" readonly title="Campo bloqueado">
                                    <input type="hidden" class="form-control " name="id_obra" value="{{ session()->get('obra')['id']}}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="bandeira">Bandeira do posto</label>
                                    <input type="text" class="form-control form-control-sm" id="bandeira" name="bandeira" value="{{ $abastecimento->bandeira}}" required>
                                </div>                                
                            </div>

                            <div class="row">        
                                <div class="col-md-2">
                                    <label class="form-label" for="km_inicial">km Atual</label>
                                    <input class="form-control form-control-sm bg-light"  id="km_inicial" name="km_inicial" type="text" value="{{$abastecimento->km_inicial}}" step="any" readonly title="Campo bloqueado">
                                </div>
        
                                <div class="col-md-2">
                                    <label class="form-label" for="km_final">km do abastecimento</label>
                                    <input class="form-control form-control-sm" id="km_final" name="km_final" type="number" step="any" min="{{$abastecimento->km_final}}"  value="{{$abastecimento->km_final}}" required>
                                </div>
                              
        
                                <div class="col-md-4">
                                    <label class="form-label" for="combustivel">Tipo de Combustível</label>
                                    <select class="form-select form-control-sm" id="combustivel" name="combustivel" required>
                                        <option value="">Selecione</option>
                                        <option value="etanol" {{ $abastecimento->combustivel == 'etanol' ? 'selected' : '' }}>Etanol/Alcool</option>
                                        <option value="gasolina" {{$abastecimento->combustivel == 'gasolina' ? 'selected' : '' }}>Gasolina</option>
                                        <option value="diesel" {{ $abastecimento->combustivel == 'diesel' ? 'selected' : '' }}>Diesel</option>
                                        <option value="gnv" {{ $abastecimento->combustivel == 'gnv' ? 'selected' : '' }}>GNV</option>
                                    </select>
                                </div>
        
                            </div>
        
                            <div class="row mt-3">
        
                                <div class="col-md-2">
                                    <label class="form-label" for="quantidade">Quantidade</label>
                                    <input class="form-control form-control-sm" id="quantidade" name="quantidade" type="text" value="{{$abastecimento->quantidade}}" step="any" required>
                                </div>
        
                                <div class="col-md-2">
                                    <label class="form-label" for="valor_do_litro">Valor do litro</label>
                                    <input class="form-control form-control-sm" id="valor_do_litro" name="valor_do_litro" type="text" value="{{$abastecimento->valor_total}}" step="any" required>
                                </div>
        
                                <div class="col-md-2">
                                    <label class="form-label" for="valor_total">Valor total</label>
                                    <input class="form-control form-control-sm" id="valor_total" name="valor_total" type="text" value="{{$abastecimento->valor_total}}" step="any" readonly required>
                                </div>
        
                                <div class="col-md-5">
                                    <label class="form-label" for="valor_total">Motorista</label>
                                    <select class="form-select select2 form-control-sm" id="id_funcionario" name="id_funcionario" required>
                                        <option value="">Selecione</option>
                                        @foreach ($funcionarios as $funcionario)
                                        <option value="{{ $funcionario->id }}" {{($abastecimento->id_funcionario == $funcionario->id ? "selected" : '')}}>
                                            {{ $funcionario->matricula }} - {{ $funcionario->nome }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
        
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="data_de_pagamento">Nome do arquivo</label>
                                    <input class="form-control form-control-sm" id="nome_anexo" name="nome_anexo" type="text" value="{{$abastecimento->nome_anexo}}">
                                </div>
        
                                <div class="col-md-7">
                                    <label class="form-label" for="data_de_pagamento">Inserir arquivo(s)</label>
                                    <input class="form-control form-control-sm" id="arquivo" name="arquivo" type="file">
                                    <span>Extensões permitidas: 'png,' 'jpg', 'jpeg', 'gif', 'pdf'<span>
                                </div>
                            </div>
        
                            <div class="col-12 mt-5">
                                <input name="veiculo_id" type="hidden" value="{{ $abastecimento->veiculo->id }}">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha256-Kg2zTcFO9LXOc7IwcBx1YeUBJmekycsnTsq2RuFHSZU=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function($) {
        $('#valor_do_litro').mask('000.000.000.000.000,00', { reverse: true });
        $('#quantidade').mask('000.000.000.000.000,00', { reverse: true });

        $('#valor_do_litro, #quantidade').on('input', function() {
            var valor_do_litro = parseFloat($('#valor_do_litro').cleanVal()) || 0;
            var quantidade = parseFloat($('#quantidade').cleanVal()) || 0;
            var resultado = (valor_do_litro * quantidade) / 100;

            var resultadoFormatado = resultado.toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            });

            $('#valor_total').val(resultadoFormatado.replace('R$ ', ''));
        });
    });
</script>

@endsection

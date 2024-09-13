@extends('dashboard')
@section('title', 'Veículo')
@section('content')

<div class="card shadow-sm">
    <div class="card-body">
        <div class="row mt-4">
            <div class="col-6 breadcrumb-item active" aria-current="page">
                <h3 class="page-title text-left">
                    <span class="page-title-icon bg-gradient-primary me-2">
                        <i class="mdi mdi-gas-station mdi-24px"></i>
                    </span>
                    Abastecimento {{ $abastecimento->veiculo->tipo == 'maquinas' ? 'da Máquina' : 'do Veículo' }}
                </h3>
            </div>

            <div class="col-4 active m-2">
                <h5 class="page-title text-left m-0">
                    <span>Edição <i class="mdi mdi-check icon-sm text-primary align-middle"></i></span>
                </h5>
            </div>
        </div>
        <hr>
        
        <div class="row p-2">
            <div class="card ">
                <div class="card-body p-5 pt-3">
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
                        
                         <div class="jumbotron mb-4">
                            <input type="hidden" name="tipo" value="{{$veiculo->tipo}}">
                            <span class="font-weight-bold bg-success p-1 px-3 rounded text-white">{{ $abastecimento->veiculo->marca  }} | {{ $abastecimento->veiculo->modelo }} | {{ $abastecimento->veiculo->veiculo }}</span>
                        </div>
                      
                        <div class="row mt-5">
                            <div class="col-md-5">
                                <label class="form-label" for="fornecedor_id">Local do abastecimento</label>
                                <input type="text" class="form-control" id="fornecedor" name="fornecedor" value="{{ $abastecimento->fornecedor ?? old('fornecedor') }}">
                            </div>
                            
                            <input type="hidden" name="tipo" value="{{ @$abastecimento->veiculo->tipo }}">
                            
                            <div class="col-md-1">
                                @php
                                $ultimaQuilometragem = $veiculo->quilometragens->last();
                                @endphp

                                @if($veiculo->tipo == "maquinas")
                                    
                                    <label class="form-label" for="horimetro_novo">Horimetro</label>
                                    <input class="form-control" id="horimetro" name="horimetro" type="number" value="{{ $abastecimento->horimetro?? old('horimetro') }}" step="any" min="{{ $abastecimento->horimetro}}">
                                @else
                                
                                    <label class="form-label" for="quilometragem_atual">Quilometragem</label>
                                    <input class="form-control" id="quilometragem" name="quilometragem" type="number" value="{{ $abastecimento->quilometragem ?? old('quilometragem') }}" step="any" min="0">
                                @endif
                            </div>

                            <div class="col-md-2">
                                <label class="form-label" for="combustivel">Tipo de Combustível</label>
                                <select class="form-select" id="combustivel" name="combustivel">
                                    <option value="etanol_alcool" {{ $abastecimento->combustivel == 'etanol_alcool' ? 'selected' : '' }}>Etanol/Álcool</option>
                                    <option value="gasolina" {{ $abastecimento->combustivel == 'gasolina' ? 'selected' : '' }}>Gasolina</option>
                                    <option value="diesel" {{ $abastecimento->combustivel == 'diesel' ? 'selected' : '' }}>Diesel</option>
                                    <option value="gnv" {{ $abastecimento->combustivel == 'gnv' ? 'selected' : '' }}>GNV</option>
                                </select>
                            </div>

                            

                            <div class="col-md-4">
                                <label class="form-label" for="valor_total">Motorista</label>
                                <select class="form-select select2" id="id_funcionario" name="id_funcionario">
                                    <option value="">Selecione</option>
                                    @foreach ($funcionarios as $funcionario)
                                        <option value="{{ $funcionario->id }}" {{$abastecimento->id_funcionario == $funcionario->id ? 'selected' : '' }}>
                                            {{ $funcionario->matricula }} - {{ $funcionario->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-2">
                                    <label class="form-label" for="fornecedor_id">Data do abastecimento</label>
                                    <input type="date" class="form-control" id="data_cadastro" name="data_cadastro" value="{{ $abastecimento->data_cadastro ?? old('data_cadastro') }}" >
                                </div>
                                
                            <div class="col-md-2">
                                <label class="form-label" for="quantidade">Quantidade</label>
                                <input class="form-control" id="quantidade" name="quantidade" type="number" value="{{ $abastecimento->quantidade ?? old('quantidade') }}" step="any">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label" for="valor_do_litro">Valor do litro</label>
                                <input class="form-control" id="valor_do_litro" name="valor_do_litro" type="text" value="{{ $abastecimento->valor_do_litro ?? old('valor_do_litro') }}" step="any">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label" for="valor_total">Valor total</label>
                                <input class="form-control" id="valor_total" name="valor_total" type="text" value="{{ Tratamento::FormatBrMoeda($abastecimento->valor_total) ?? old('valor_total') }}" step="any" readonly>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label class="form-label" for="nome_anexo">Nome do arquivo</label>
                                <input class="form-control" id="nome_anexo" name="nome_anexo" type="text" value="{{ $abastecimento->nome_anexo ?? old('nome_anexo') }}">
                            </div>

                            <div class="col-md-8">
                                <label class="form-label" for="arquivo">Inserir arquivo(s)</label>
                                <input class="form-control" id="arquivo" name="arquivo" type="file" value="{{ old('arquivo') }}">
                                <span>Extensões permitidas: 'png,' 'jpg', 'jpeg', 'gif', 'pdf', 'excel', 'arquivo compactado'.<span>
                            </div>
                        </div>

                        <div class="col-12 mt-5">
                            <input name="veiculo_id" type="hidden" value="{{ $abastecimento->veiculo->id }}">
                            <button class="btn btn-primary btn-md font-weight-medium" type="submit">Salvar</button>

                            <a href="{{ url('admin/ativo/veiculo') }}">
                                <button class="btn btn-warning btn-md font-weight-medium" type="button">Cancelar</button>
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

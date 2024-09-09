@extends('dashboard')
@section('title', 'Veículo')
@section('content')

<div class="container mt-5">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="page-header my-3">
                    <h3 class="page-title">
                        <span class="page-title-icon bg-gradient-primary">
                            <i class="mdi mdi-piggy-bank-outline mdi-36px"></i>
                        </span>
                        @if ($veiculo->tipos->tipo_veiculo == 'maquinas')
                            Seguro da Máquina ->  <small>{{ $veiculo->marca }} | {{ $veiculo->modelo }} | {{ $veiculo->veiculo }}</small>
                        @else
                            Seguro do Veículo ->  <small>{{ $veiculo->marca }} | {{ $veiculo->modelo }} | {{ $veiculo->veiculo }}</small>
                        @endif
                    </h3>                        
                </div>

                <hr>
                    <form method="post" action="{{ route('ativo.veiculo.seguro.store') }}">
                        @csrf
                      
                        <div class="row mt-3" id="divHorimetro">
                            <div class="col-md-8">
                                <label class="form-label" for="nome_seguradora">Nome da Operadora de Seguro</label>
                                <input class="form-control" id="nome_seguradora" name="nome_seguradora" type="text" value="{{ old('nome_seguradora') }}">
                            </div>
                        </div>
                    
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label class="form-label" for="carencia_inicial">Carência Inicial</label>
                                <input class="form-control" id="carencia_inicial" name="carencia_inicial" type="date" value="{{ old('carencia_inicial') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="carencia_final">Carência Final</label>
                                <input class="form-control" id="carencia_final" name="carencia_final" type="date" value="{{ old('carencia_final') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="valor">Valor</label>
                                
                                <div class="d-flex">
                                    <span class="pr-2" style="margin-top: 10px; font-size:18px; margin-right: 8px">R$ </span>
                                    <input class="form-control" id="valor" name="valor" type="text" value="{{ old('valor') }}">
                                </div>
                               
                            </div>
                        </div>

                        <div class="col-12 mt-5">
                            <input name="veiculo_id" type="hidden" value="{{ $veiculo->id }}">
                            <button class="btn btn-primary font-weight-medium" type="submit">Salvar</button>

                            <a href="{{url('admin/ativo/veiculo')}}">
                                <button class="btn btn-warning font-weight-medium" type="button">Cancelar</button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha256-Kg2zTcFO9LXOc7IwcBx1YeUBJmekycsnTsq2RuFHSZU=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function($) {
        $('#valor').mask('000.000.000.000.000,00', {
            reverse: true
        });
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        var valorDoLitroInput = document.getElementById('valor_do_litro');
        var quantidadeInput = document.getElementById('quantidade');
        var valorTotalInput = document.getElementById('valor_total');

        valorDoLitroInput.addEventListener('change', updateValorTotal);
        quantidadeInput.addEventListener('change', updateValorTotal);

        function updateValorTotal() {
            var valorDoLitro = parseFloat(valorDoLitroInput.value);
            var quantidade = parseFloat(quantidadeInput.value);

            var valorTotal = valorDoLitro * quantidade;

            valorTotalInput.value = valorTotal.toFixed(2);
        }
    });
</script>
@endsection

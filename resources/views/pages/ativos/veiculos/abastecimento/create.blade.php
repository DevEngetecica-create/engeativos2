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
        
                        <form method="post" action="{{ route('ativo.veiculo.abastecimento.store') }}" enctype="multipart/form-data">                          
                            
                            @csrf
                            <div class="form-group">
                                <label for="quilometragem_inicial">Quilometragem Inicial</label>
                                <input type="text" id="quilometragem_inicial" name="quilometragem_inicial" value="{{ $lastQuilometragem }}" class="form-control" readonly>
                            </div>

                            <div class="form-group">
                                <label for="quilometragem_final">Quilometragem Final</label>
                                <input type="number" id="quilometragem_final" name="quilometragem_final" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="quantidade_combustivel">Quantidade de Combustível</label>
                                <input type="number" step="0.01" id="quantidade_combustivel" name="quantidade_combustivel" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="tipo_combustivel">Tipo de Combustível</label>
                                <input type="text" id="tipo_combustivel" name="tipo_combustivel" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="valor_total">Valor Total</label>
                                <input type="number" step="0.01" id="valor_total" name="valor_total" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="data_abastecimento">Data do Abastecimento</label>
                                <input type="date" id="data_abastecimento" name="data_abastecimento" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Salvar</button>
                           
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
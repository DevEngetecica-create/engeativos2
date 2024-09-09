@extends('dashboard')
@section('title', 'Veículo')
@section('content')

    

    <div class="container">
        <div class="row">
            <div class="card">
                <div class="card-body">

                    <div class="page-header">
                        <h3 class="page-title">
                            <span class="page-title-icon bg-gradient-primary mx-3">
                                <i class="mdi mdi-car-clock mdi-36px"></i>
                            </span>
                            @if ($veiculo->tipo == 'maquinas')
                                IPVA da Máquina <i class="mdi mdi-arrow-right-thin mdi-36px"></i>  <small class="font-weight-bold">{{ $veiculo->marca }} | {{ $veiculo->modelo }} | {{ $veiculo->veiculo }}</small>
                            @else
                                IPVA do Veículo <i class="mdi mdi-arrow-right-thin mdi-24px"></i>  <small class="font-weight-bold">{{ $veiculo->marca }} | {{ $veiculo->modelo }} | {{ $veiculo->veiculo }}</small>
                            @endif
                        </h3>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Ops!</strong><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <hr >

                    <form method="post" action="{{ route('ativo.veiculo.ipva.store') }}" enctype="multipart/form-data">
                        @csrf                       

                        <div class="row mt-5 mb-3">
                            <div class="col-md-3">
                                <label class="form-label" for="referencia_ano">Ano de Referência</label>
                                <input class="form-control" id="referencia_ano" name="referencia_ano" type="number" value="{{ old('referencia_ano') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label" for="valor">Valor</label>
                                <div class="d-flex">
                                    <span class="pr-2" style="margin-top: 10px; font-size:18px; margin-right: 8px">R$ </span>
                                    <input class="form-control" id="valor" name="valor" type="text" value="{{ old('valor') }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label" for="data_de_pagamento">Data do Lançamento</label>
                                <input class="form-control" id="data_de_pagamento" name="data_de_pagamento" type="date" value="{{ old('data_de_pagamento') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label" for="data_de_vencimento">Data de Vencimento</label>
                                <input class="form-control" id="data_de_vencimento" name="data_de_vencimento" type="date" value="{{ old('data_de_vencimento') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label" for="data_de_pagamento">Nome do arquivo</label>
                                <input class="form-control" id="nome_anexo_ipva" name="nome_anexo_ipva" type="text" value="{{ old('nome_anexo_ipva') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="data_de_pagamento">Inserir arquivo(s)</label>
                                <input class="form-control" id="extensao" name="extensao" type="file" value="{{ old('extensao') }}">
                                <span>Extensões permitidas: 'png,' 'jpg', 'jpeg', 'gif', 'pdf', 'excel', 'arquivo compactado'.<span>
                            </div>
                        </div>

                        <div class="col-12 mt-5">
                            <input name="veiculo_id" type="hidden" value="{{ $veiculo->id }}">
                            <button class="btn btn-primary font-weight-medium" type="submit">Salvar</button>
                            <a href="{{url('admin/ativo/veiculo') }}">
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

@endsection


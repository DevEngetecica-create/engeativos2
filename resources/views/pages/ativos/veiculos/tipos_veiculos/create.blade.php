@extends('dashboard')

@section('content')
    <div class="card m-5">
        <div class="card-body m-3">
            <div class="card-header mb-3">

                <h1>Cadastar Tipo de Veículo</h1>
            </div>

            <form action="{{ route('tipos_veiculos.store') }}" method="POST">
                @csrf

                <div class="row my-5">
                    <div class="col-2">
                        <label>Tipo de Veículo</label>
                    </div>

                    <div class="col-3">
                        <input type="text" class="form-control fom-control-sm" name="nome_tipo_veiculo">
                    </div>

                    <div class="col-1">
                        <button class="btn btn-primary btn-ms" type="submit">Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

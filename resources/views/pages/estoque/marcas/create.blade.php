@extends('dashboard')
@section('title', 'Estoque - Marcas')
@section('content')


@section('content')

    <div class="row my-3 bg-white py-3 shadow ">
        <div class="col-3 active">
            <h5 class="page-title text-left m-0">
                <a class="btn btn-success btn-sm" href="{{ route('ativo.estoque.marcas.index') }}">
                    <i class="mdi mdi-arrow-left icon-sm align-middle text-white"></i> Voltar
                </a>
            </h5>
        </div>

        <div class="col-6 breadcrumb-item active" aria-current="page">
            <h5 class="page-title text-center">
                <span class="page-title-icon bg-gradient-primary me-2">
                    <i class="mdi mdi-access-point-network menu-icon"></i>
                </span> Cadastrar Marcas
            </h5>
        </div>
    </div>



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
    <div class="card">
        <div class="card-body p-5">
            <form action="{{ route('ativo.estoque.marcas.store') }}" method="POST">
                @csrf

                <div class="container">
                    <div class="row">      
                        <div class="col">
                            <div class="form-group mb-3">
                                <label for="name">Nome</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name') }}">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>

            </form>
        </div>
    </div>

@endsection

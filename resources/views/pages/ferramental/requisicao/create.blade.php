@extends('dashboard')
@section('title', 'Requisições')
@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary me-2 text-white">
                <i class="mdi mdi-access-point-network menu-icon"></i>
            </span> Requisição
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Requisição <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

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

                    <form method="post" action="{{ route('ferramental.requisicao.store') }}">
                        @csrf
                        @include('pages.ferramental.requisicao.partials.form')

                        <div class="row">
                            <div class="col-10 mt-3">
                                <button class="btn btn-gradient-primary font-weight-medium" type="submit">Salvar</button>
                                <a href="{{ route('ferramental.requisicao.index') }}">
                                    <button class="btn btn-gradient-danger font-weight-medium" type="button">Cancelar</button>
                                </a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection

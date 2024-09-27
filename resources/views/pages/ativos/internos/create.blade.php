@extends('dashboard')
@section('title', 'Ativos Internos')
@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary me-2 text-white">
                <i class="mdi mdi-access-point-network menu-icon"></i>
            </span> Cadastrar Ativo Interno
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <a class="btn btn-success" href="{{ route('ativo.interno.index') }}">
                        <i class="mdi mdi-arrow-left icon-sm align-middle text-white"></i> Voltar
                    </a>
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

                    <form method="post" action="{{ route('ativo.interno.store') }}" enctype="multipart/form-data">
                        @csrf

                        @include('pages.ativos.internos.partials.form')
                        
                        <div class="card-footer mt-2">
                            <button class="btn btn-primary btn-lg font-weight-medium" type="submit">Salvar</button>

                            <a href="{{ route('ativo.interno.index') }}">
                                <button class="btn btn-danger btn-lg font-weight-medium" type="button">Cancelar</button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

   {{-- MODAL MARCAS CONFIRMATION --}}
    @include('pages.ativos.internos.partials.form-marcas')


    {{-- MODAL INCLUSAO RAPIDA DE OBRAS --}}
    @include('pages.cadastros.obra.partials.inclusao-rapida')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!--SCRIPT PARA CARREGAR IMAGEM PRINCIPAL -->
<script type="text/javascript">
    function carregarImg() {

        var target = document.getElementById('target');
        var file = document.querySelector("input[type=file]").files[0];
        var reader = new FileReader();

        reader.onloadend = function() {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);


        } else {
            target.src = "";
        }
    }
</script>

@endsection


@extends('dashboard')
@section('title', 'Folgas dos Funcionários')
@section('content')

<div class="row">
    <div class="col-2 breadcrumb-item active" aria-current="page">
        <h3 class="page-title text-center">
            <span class="page-title-icon bg-gradient-primary me-2">
            </span> Funcionários
        </h3>
    </div>

    <div class="col-4 active m-2">
        <h5 class="page-title text-left m-0">
            <span>Folgas <i class="mdi mdi-check icon-sm text-primary align-middle"></i></span>
        </h5>
    </div>
</div>

<hr>

<form method="post" class="form" enctype="multipart/form-data" id="form">
    @csrf
    <div class="row my-4">
        <div class="col-2">
            <h3 class="page-title text-left">
                <a href="{{ route('cadastro.funcionario.adicionar') }}">
                    @if (session()->get('usuario_vinculo')->id_nivel <= 2) 
                        <a class="btn btn-sm btn-success" href="{{ route('cadastro.funcionario.folga.adicionar') }}">
                            Novo Registro
                        </a>
                    @endif
                </a>
            </h3>
        </div>
        <div class="col-10">
            <div class="row justify-content-center">
                <div class="col-5 m-0 p-0 ">
                    <input type="hidden" id="page" name="page" value="0">
                    <input type="text" id="search" name="search" class="form-control" placeholder="Pesquisar funfionário">
                </div>
                <div class="col-2 text-left  m-0 p-0 mb-2 mx-2">
                    <a href="{{ url('admin/cadastro/funcionario/funcao') }}" title="Limpar pesquisa">
                        <span class="btn btn-warning btn-sm py-0 shadow"><i class="mdi mdi-delete-outline mdi-24px"></i></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>


@if(session('mensagem'))
<div class="alert alert-warning">
    {{ session('mensagem') }}
</div>
@endif


<hr class="dropdown-divider bg-dark mb-4">

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card pb-1">  
        <div class="card">
            <div class="card-body p-2">

                {{--Tabela--}}

            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.js"></script>

<script>
    $(document).ready(function() {

        
        carregarTabela(0);

    });

    $(document).on('click', '.paginacao a', function(e) {
        e.preventDefault();
        var link = $(this).attr('aria-label');
        var pagina = $(this).attr('href').split('page=')[1];
        carregarTabela(pagina);

    });
    $(document).on('change keyup', '.form', function(e) {
        e.preventDefault();
        carregarTabela(0);
    });

    function carregarTabela(pagina) {

        $('#page').val(pagina);
        var dados = $('#form').serialize();
        $.ajax({
            url: "{{route('cadastro/funcionario/folga/list')}}",
            method: 'GET',
            data: dados
        }).done(function(data) {
            $('.card-body').html(data);
        });

    }
</script>

@endsection
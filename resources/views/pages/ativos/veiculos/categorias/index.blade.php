@extends('dashboard')
@section('title', 'Categorias dos Veículos')
@section('content')

    <div class="d-flex page-header m-0 p-0 mt-3">
    <h3 class="page-title">
        Categorias dos Veículos
    </h3>
</div>

<div class="d-flex flex-row col-12 bd-highlight bg-light m-0 p-0">

    <div class="p-2 bd-highlight  align-self-center col-1  m-0 p-0">
        <h3 class="page-title">
            @if (session()->get('usuario_vinculo')->id_nivel <= 2) <a class="btn btn-sm btn-danger" href="{{ route('cadastro.veiculo.categoria.adicionar') }}">
                Adicionar
                </a>
                @endif
        </h3>
    </div>

    <div class=" d-flex justify-content-end p-2 bd-highlight col-lg-8  m-0 p-0 mr-5">

       <form method="post" class="form" enctype="multipart/form-data" id="form">
            @csrf


            <div class="p-2 bd-highlight col-lg-12 ">
                <div class="d-flex bd-highlight col-12  ">
                    <div class="p-2  bd-highlight col-3">
                        <div class="row">
                            <label class="col-sm-4 col-form-label">Listar</label>
                            <div class="col-sm-8">
                                <select class="form-select form-control" id="lista" name="lista" aria-label="Default select example">
                                    <option value="5" selected="">5</option>
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-7 p-2 flex-grow-1 bd-highlight ml-2">
                        <div class="row">
                            <label class="col-sm-2
                                 col-form-label">Pesquisar</label>
                            <div class="col-sm-10">
                                <div class="input-group ml-1">
                                    <input type="hidden" id="page" name="page" value="0">
                                    <input type="text" id="search" name="search" class="form-control">
                                    <div>
                                        <span class="input-group-text p-1 px-2"><i class="mdi mdi-magnify mdi-18px"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>

            </div>
        </form>
    </div>
</div>


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
            url: "{{route('admin/ativo/veiculo/categoria/list')}}",
            method: 'GET',
            data: dados
        }).done(function(data) {
            $('.card-body').html(data);
        });

    }
</script>
    @endsection
@extends('dashboard')
@section('title', 'Manutencao do Ativo')
@section('content')

<div class="d-flex page-header m-0 p-0 my-5">
    <h3 class="page-title">
        Lista de Manutenções
    </h3>
</div>

<div class="accordion accordion-flush rounded" id="accordionFlushExample">
    <div class="accordion-item">
        <h2 class=" d-flex accordion-header  bg-secondary text-white rounded shadow" id="flush-headingOne">
            @if (session()->get('usuario_vinculo')->id_nivel <= 2) <a class="shadow-lg bg-body rounded" href="{{ route('ativo.externo.manutencao.adicionar') }}">
                <button class="btn btn-sm btn-warning">Novo Registro</button>
                </a>
                @endif
                <button class="accordion-button collapsed  bg-secondary text-white" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                    Pesquisar
                </button>
        </h2>

        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">

                <div class="row">
                    <form class="form" enctype="multipart/form-data" id="form_locacao">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-2">
                                <div class="row">
                                    <label class="form-label">Listar</label>
                                    <select class="form-select form-control" id="lista" name="lista" aria-label="Default select example">
                                        <option value="5" selected>5</option>
                                        <option value="10">10</option>
                                        <option value="15">15</option>
                                        <option value="20">20</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-4 mx-2">
                                <label class="form-label">Pesquisar</label>
                                <div class="input-group ml-1">
                                    <input type="hidden" id="page" name="page" value="0">
                                    <input type="text" id="search" name="search" class="form-control">
                                    <div>
                                        <span class="input-group-text p-1 px-2"><i class="mdi mdi-magnify mdi-18px"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-4 mx-2">
                                <label class="form-label">Fornecedores</label>
                                <select class="form-select form-control" id="id_fornecedor" name="id_fornecedor" aria-label="Default select example">
                                    <option>Selecione um fornecedor</option>                                    
                                    @foreach($fornecedores as $fornecedore)
                                    <option value="{{$fornecedore->id}}">{{$fornecedore->nome_fantasia}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
        var dados = $('#form_locacao').serialize();

        console.log(dados);

        $.ajax({
            url: "{{url('admin/ativo/externo/manutencao/list')}}",
            method: 'GET',
            data: dados
        }).done(function(data) {
            $('.card-body').html(data);
        });

    }
</script>


@endsection
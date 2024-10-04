@extends('dashboard')
@section('title', 'Locação de Veículos')
@section('content')

    <div class="d-flex page-header m-0 p-0 my-5">
    <h3 class="page-title">
        Locação de Veículos
    </h3>
</div>

 <div class="accordion accordion-flush" id="accordionFlushExample">
    <div class="accordion-item">
        <h2 class=" d-flex accordion-header  bg-secondary text-white" id="flush-headingOne">
            @if (session()->get('usuario_vinculo')->id_nivel <= 2)
            <a href="{{ route('ativo.veiculo.locacaoVeiculos.create') }}">
                <button class="btn btn-sm btn-success">Novo Registro</button>
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
                                            <option value="8" selected="">8</option>
                                            <option value="8">8</option>
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
                                
                                <div class="col-md-5">
                                    <label class="form-label" for="obra">Obra de Locação</label>
                                    <select class="form-select form-control " id="id_obraDestino" name="id_obraDestino">
                                        <option selecte value=""> Seleciona uma obra para efetuar a pesquisa</option>
                                        @foreach ($obras as $obra)
                                            <option value="{{ $obra->id }}" {{ @$editLocacaoVeiculos->obra->id == $obra->id ? 'selected' : '' }}>
                                                {{ $obra->codigo_obra }} - {{ $obra->razao_social }}
                                            </option><br>
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
        $.ajax({
            url: "{{url('admin/ativo/veiculo/locacaoVeiculos/list')}}",
            method: 'GET',
            data: dados
        }).done(function(data) {
            $('.card-body').html(data);
        });

    }
</script>


    @endsection
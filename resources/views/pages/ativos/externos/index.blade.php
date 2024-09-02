@extends('dashboard')

@section('title', 'Ativos Externos')

@section('content')



<div class="d-flex page-header m-0 p-0 mt-3 ">
    <h3 class="page-title">Ferramentas</h3>
</div>



<div class="d-flex flex-row col-12 bd-highlight bg-light m-0 p-0">
    @if (session()->get('usuario_vinculo')->id_nivel <= 2) <div class="p-2 bd-highlight  align-self-center col-1  m-0 p-0">
        <h3 class="row page-title">
            <a href="{{ route('ativo.externo.adicionar') }}">
                <button class="btn btn-primary">Cadastrar</button>
            </a>
        </h3>
</div>
@endif



<div class=" d-flex justify-content-center p-2 bd-highlight col-lg-8  m-0 p-0 mr-5">
    <form method="post" class="form" enctype="multipart/form-data" id="form">
        @csrf

        <div class="p-2 bd-highlight col-lg-12 ">
            <div class="d-flex bd-highlight col-12  ">
                <div class="p-2  bd-highlight col-3">
                    <div class="row">
                        <label class="col-sm-4 col-form-label">Listar</label>
                        <div class="col-sm-8">
                            <select class="form-select form-control" id="lista" name="lista">
                                <option value="10" selected="">10</option>
                                <option value="5">5</option>
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

                <div class="p-2  bd-highlight col-6">
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Listar</label>
                        <div class="col-sm-8">
                            <select class="form-select form-select-sm" id="status_ferramentas" name="status_ferramentas">
                                <option value="" selected>Selecionar um status</option>
                                @foreach($countStatus as $countStatu)
                                <option value="{{$countStatu->idStatus}}">{{$countStatu->titulo}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="p-2 bd-highlight col-1">
                    <span id="print" class="btn btn-success btn-sm ml-1"><i class="mdi mdi-file-excel-box mdi-18px"></i></span>
                </div>

                <div class="p-2 bd-highlight col-1 mx-1 d-none" id="download">
                    <a href="{{route('ativo.externo.download')}}" class="btn btn-info btn-sm"> <i class="mdi mdi-download mdi-18px"></i></a>
                </div>
            </div>
        </div>
</div>
</div>

</form>

<div class="row">
    <div class="table-responsive ">
        <div id="table-gridjs" class="tabela  row col-lg-12 grid-margin stretch-card">

            {{--Tabela--}}

        </div>
    </div>
</div>



<div class="modal fade" id="foraOperacao" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Lista - Equipamentos Fora de Operacao</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Patrimonio</th>
                            <th scope="col" class="text-center">Nome</th>
                            <th scope="col" class="text-center">Ver</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(GraficosAtivosExternos::statusForaOperacao() as $foraOperacao)

                        <tr class="m-0 p-0">
                            <td class="m-0 p-0 px-2">{{$foraOperacao->patrimonio}}</td>
                            <td class="m-0 p-0 px-2">{{$foraOperacao->configuracao->titulo}}</td>
                            <td class="text-center m-0 p-0">
                                <a href="{{ route('ativo.externo.detalhes', $foraOperacao->ativo_externo->id) }}">
                                    <span class="btn btn-succes btn-sm"><i class="mdi mdi-eye mdi-18px"></i></span>
                                </a>
                            </td>
                        </tr>

                        @endforeach

                    </tbody>
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.js"></script>


<script>
    $("#historicoRetirada").on('click', function() {
        let id_retirada = $(this).attr('data-id_retirada');

        $.ajaxSetup({
            headers: {

                'X-CSRF-TOKEN': '{{ csrf_token()}}'
            }
        });

        $.ajax({
            type: 'GET',
            url: '/admin/ativo/externo/historico/' + id_retirada,
            data: {},
            success: function(result) {
                $(".modal-title").html('Itens Retirados # ' + id_retirada)
                $(".modal-body").html(result)
            }
        });
    });

    $(document).ready(function() {

        $(document).on('click', '.delete-ativo', function(e) {
            e.preventDefault();

            var ativoId = $(this).data('id');            

            if (confirm('Você tem certeza que deseja deletar este ativo?')) {
                $.ajax({
                    url: "/admin/ativo/externo/delete/" + ativoId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            // Aqui você pode atualizar a página ou remover o item da lista
                        } else {
                            alert('Erro ao deletar o ativo.');
                        }
                    },
                    error: function(xhr) {
                        alert('Erro ao deletar o ativo.');
                    }
                });
            }
        });

        var gerarRelatorio = document.querySelector("#print");

        gerarRelatorio.addEventListener("click", function() {

            var dados = $('#form').serialize();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token()}}'
                }
            });

            let timerInterval;
            Swal.fire({
                title: 'Gerando Relatório...',
                html: '<p>Relatório será gerado em <b></b> segundos.</p> <p>Aguarde o término</p>',
                timer: 5000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                    const b = Swal.getHtmlContainer().querySelector('b')
                    timerInterval = setInterval(() => {
                        b.textContent = Math.round(Swal.getTimerLeft() / 1000)
                    }, 100)
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
            }).then((result) => {
                $.ajax({
                    url: "/admin/ativo/externo/report",
                    method: 'get',
                    data: dados,
                    success: function(result) {
                        Swal.fire({
                            title: 'Sucesso!',
                            text: 'Relatório gerado com sucesso!',
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonText: 'Download',
                            cancelButtonText: 'Fechar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "{{ route('ativo.externo.download') }}";
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Erro!',
                            text: 'Ocorreu um erro ao gerar o relatório.',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }
                });
            })
        });
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

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token()}}'
            }
        });

        $('#page').val(pagina);
        var dados = $('#form').serialize();
        console.log(dados)

        //alert($('#status_ferramenta').val())            
        $.ajax({
            url: "{{route('admin/ativo/externo/list')}}",
            method: 'GET',
            data: dados
        }).done(function(data) {
            $('.tabela').html(data);
        });
    }
</script>

@endsection
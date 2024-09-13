@extends('dashboard')
@section('title', 'Ativos Externos - MANUTENCAO')
@section('content')



<div class="page-header mt-5">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Detalhes do Ativo Externo
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Ativos <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

<div class="page-header ">
    <h3 class="page-title">

        <a href="">
            <button class="btn btn-md btn-primary mt-3">Listar Manutenção</button>
        </a>
        @foreach($showtAtivos as $showtAtivo)
        <a href="{{route('ativo.externo.manutencao.editar', @$showtAtivo->id)}}">
            <button class="btn btn-md btn-warning  mt-3 mx-3">Editar</button>
        </a>
        @endforeach
    </h3>
</div>

<div class="row col-12">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-header  bg-primary text-center  shadow-sm">
                <h3 class="card-title m-auto text-white">DADOS DA MANUTENÇÃO</h3>
            </div>

            <div class="card-body p-3">



                <dl class="row m-1">
                    @foreach($showtAtivos as $showtAtivo)

                    <dt class="col-sm-3">Obra:</dt>
                    <dd class="col-sm-9"> {{$showtAtivo->obra->nome_fantasia ?? ""}}</dd>

                    <dt class="col-sm-3 my-3">Patrimonio:</dt>
                    <dd class="col-sm-9 text-left my-3"> <span class=" bg-secondary py-1 px-5 rounded text-white my-2"> {{$showtAtivo->ativo_externo_estoque->patrimonio ?? ""}}</span></dd>
                    
                    <dt class="col-sm-3">Ferramenta:</dt>
                    <dd class="col-sm-9"> {{$showtAtivo->ativo_externo->titulo ?? ""}}</dd>

                    <dt class="col-sm-3 my-2">Fornecedor:</dt>
                    <dd class="col-sm-9 my-2"> {{$showtAtivo->fornecedor->nome_fantasia}}</dd>

                    <dt class="col-sm-3 my-2">Data de retirada:</dt>
                    <dd class="col-sm-9 my-2"> {{Tratamento::FormatarData($showtAtivo->data_retirada) }}</dd>

                    <dt class="col-sm-3 my-2">Prazo:</dt>
                    <dd class="col-sm-9 my-2"> {{Tratamento::FormatarData($showtAtivo->data_prevista)}}</dd>

                    @if($showtAtivo->data_realizada)

                    <dt class="col-sm-3 my-2">Data da Devolução:</dt>
                    <dd class="col-sm-9 my-2"> {{Tratamento::FormatarData($showtAtivo->data_retirada) }}</dd>
                    @else

                    <dt class="col-sm-3 my-2">Data da Devolução:</dt>
                    <dd class="col-sm-9 my-2"><span class=" col-3 badge bg-warning text-dark">
                        Pendente</dd><br>

                    @endif

                    <dt class="col-sm-3 my-2">Custo:</dt>
                    <dd class="col-sm-9 my-2"> R$ {{Tratamento::currencyFormatBr($showtAtivo->valor)}}</dd>

                    <dt class="col-sm-3 my-2">Status do orçamento:</dt>
                    <dd class="col-sm-9 my-2">

                        <div class="d-flex justify-content-start">
                            <div class="col-5">
                                <span class="bg-{{$showtAtivo->situacao->classe}} p-2 rounded shadow text-white" id="status">
                                    @if($showtAtivo->situacao->titulo =="Liberado") 
                                        "Orçamento Liberado"
                                    @else
                                        {{$showtAtivo->situacao->titulo}}
                                    @endif
                                </span>
                            </div>
                            @if (session()->get('usuario_vinculo')->id_nivel <= 1) 
                                <div class="col-4 form-check form-switch mx-4 my-0 " {{($showtAtivo->data_realizada) ? "readonly" : ""}}>
                                <input  class="form-check-input shadow" type="checkbox" id="aproved" name="aproved" value="true" style="height:30px; width:60px" {{($showtAtivo->situacao->titulo == "Liberado") ? "checked='disabled'" : ""}} {{($showtAtivo->data_realizada or $showtAtivo->id_status == 2) ? "disabled" : ""}}>
                                <label class="form-check-label" for="aproved">Aprovar?</label>
                        </div>
                        @else

                        @endif



                    </dd>

                    <hr class="mt-4">

                    <dt class="col-sm-12 badge bg-warning text-dark py-2">RELATÓRIO</dt>
                    <dd class="col-12"> {!!$showtAtivo->description!!}</dd>
                    @endforeach
                </dl>


            </div>
        </div>
    </div>


    <div class="col-md-6 grid-margin stretch-card ">
        <div class="card ">
            <div class="d-flex justify-content-start card-header bg-primary py-0">
                <h3 class="card-title text-white m-0 mt-2">ANEXOS

                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-file" title="Adicionar anexo"><i class="mdi mdi-plus" ></i></button>

                </h3>
            </div>
            <div class="card-body p-3">

                <table class="table-hover table-striped table">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Descrição</th>
                            <th>Tipo</th>
                            <th>Data Cad.</th>
                            <th width="10%">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($anexos as $anexo)
                        <tr>

                            <td class="align-middle">{{ $anexo->titulo ?? NULL}}</td>
                            <td class="align-middle">{{ $anexo->descricao ?? NULL}}</td>
                            <td class="align-middle">{{ $anexo->tipo ?? NULL}}</td>
                            <td class="align-middle">{{ Tratamento::datetimeBr($anexo->created_at) ?? NULL}}</td>
                            <td class="d-flex gap-2 align-middle">

                                <a class="m-auto" href="{{ route('ativo.externo.manutencao.download',$anexo->id) }}" title="Baixar Anexo">
                                    <button class="btn btn-warning btn-sm p-0 px-1">
                                        <i class="mdi mdi-arrow-collapse-down mdi-18px"></i>
                                    </button>
                                </a>

                                <form class="m-auto" action="{{ route('ativo.externo.manutencao.destroyAnexo', $anexo->id) }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-danger btn-sm p-0 m-0 px-1" data-toggle="tooltip" data-placement="top" type="submit" title="Excluir">
                                        <i class="mdi mdi-delete mdi-18px"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

        </div>
    </div>
</div>



    <div class="modal fade" id="modal-file" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="file-form" action="{{ route('ativo.externo.manutencao.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="titulo">Título do anexo</label>
                            <input class="form-control" name="titulo" type="text" placeholder="Título do anexo" required>
                        </div>
                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <textarea class="form-control" name="descricao" type="text" placeholder="Descrição do anexo" required></textarea>
                        </div>
                        <br>
                        <div class="form-group mb-3">
                            <div class="input-group">
                                <div class="custom-file">
                                    <label class="custom-file-label" for="file">Escolha o arquivo</label>
                                    <input class="custom-file-input" name="file" type="file" required>
                                    
                                </div>
                            </div>
                            <br>
                            <span class="text-muted"> Formatos válidos: *.PDF, *.XLS, *.XLSx, *.JPG, *.PNG, *.JPEG, *.GIF Tamanho Máximo: 64M</span>
                        </div>
                        <input name="id_ativo_interno" type="hidden" value="{{ @$showtAtivo->id ?? @$data->id}}">
                        <button class="btn btn-secondary" data-dismiss="modal" type="button">Cancelar</button>
                        <button class="btn btn-primary" type="submit">Inserir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>

    
    
    $(document).ready(function() {
        $(".ItemsRetirada").on('click', function() {
            let id_retirada = $(this).attr('data-id_retirada');

            $.ajax({
                type: 'GET',
                url: BASE_URL + '/ferramental/retirada/items/' + id_retirada,
                data: {},
                success: function(result) {
                    $(".modal-title").html('Itens da Retirada #' + id_retirada)
                    $(".modal-body").html(result)
                }
            });
        });

        var id = "{{@$showtAtivo->id}}"
        var url = "{{route('ativo.externo.manutencao.aprovedOrcamento', ':id') }}".replace(':id', id);

        $("#aproved").on('click', function(e) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let tipo = $(this).val();


            console.log('Autenticar Digitalmente')

            Swal.fire({
                title: 'Atenção!',
                text: "Você está prestes a liberar o orçamento para manuteção",
                icon: 'warning',
                footer: 'Em caso de dúvidas, entre em contato com o fornecedor.',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Liberar'
            }).then((result) => {
                if (result.isConfirmed) {

                    let timerInterval
                    Swal.fire({
                        title: 'Aguarde.',
                        html: 'Estamos autenticando o documento em <b></b> milisegundos.',
                        timer: 1000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                            const b = Swal.getHtmlContainer()
                                .querySelector('b')
                            timerInterval = setInterval(() => {
                                b.textContent = Swal
                                    .getTimerLeft()
                            }, 100)
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                    }).then((result) => {

                        if (result.dismiss === Swal.DismissReason
                            .timer) {


                            // salvar autenticidade
                            $.ajax({
                                type: 'POST',
                                url: url,

                                data: {
                                    tipo: tipo,
                                    id: id
                                },
                                success: function(result) {

                                    if (result == 0) {
                                        Swal.fire(
                                            'Eita!',
                                            'Algo deu errado na assinatura.',
                                            'error'
                                        )
                                    }

                                    Swal.fire({
                                        title: 'Sucesso!',
                                        text: "O orçamento foi liberado com sucesso com sucesso!",
                                        icon: 'success',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        cancelButtonText: 'Fechar',
                                    }).then((result) => {

                                        if (result
                                            .isConfirmed) {
                                            location.reload();
                                        }
                                    })
                                }
                            });
                        }
                    })
                }
            })

        })

    });
</script>

@endsection
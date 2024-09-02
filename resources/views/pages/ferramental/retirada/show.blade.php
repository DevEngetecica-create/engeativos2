@extends('dashboard')
@section('title', 'Retirada - Detalhes')
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Detalhes da Retirada
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Ferramental <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

<div class="page-header">
    <h3 class="page-title">
        <a href="{{ url('admin/ferramental/retirada/adicionar') }}">
            <button class="btn btn-sm btn-danger">Nova Retirada</button>
        </a>

        <a href="{{ route('ferramental.retirada') }}">
            <button class="btn btn-sm btn-light">Listar Todas </button>
        </a>
    </h3>
</div>


<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">

                            <button class="btn btn-outline-warning btn-fw" type="button">
                                RETIRADA <span class="mdi mdi-pound"></span>{{ $detalhes->id }}
                            </button>

                            @if($detalhes->id_relacionamento)
                            <a href="{{ route('ferramental.retirada.detalhes', $detalhes->id_relacionamento) }}">
                                <button class="btn btn-outline-danger btn-fw" type="button">
                                    RELACIONAMENTO <span class="mdi mdi-pound"></span>{{ $detalhes->id_relacionamento}}
                                </button>
                            </a>
                            @endif

                            <button class="btn btn-{{ Tratamento::getStatusRetirada($detalhes->status)['classe'] }} btn-fw" type="button">
                                <span class="mdi mdi-check-all"></span> {{ Tratamento::getStatusRetirada($detalhes->status)['titulo'] }}
                            </button>

                            <hr>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle table-nowrap mb-0">
                                <tr>
                                    <td>ID</td>
                                    <td>Funcionário</td>
                                    <td>Obra</td>
                                    <td>Data</td>
                                    <td>Previsão Devolução</td>
                                    <td>Status</td>
                                </tr>

                                <tr>
                                    <td>{{ $detalhes->id }}</td>
                                    <td>{{ $detalhes->funcionario }}</td>
                                    <td>{{ $detalhes->codigo_obra }}</td>
                                    <td>{{ Tratamento::datetimeBr($detalhes->created_at) }}</td>
                                    <td>{{ Tratamento::datetimeBr($detalhes->data_devolucao_prevista) }}</td>
                                    <td>
                                        <span class="badge badge-{{ Tratamento::getStatusRetirada($detalhes->status)['classe'] }}">
                                            {{ Tratamento::getStatusRetirada($detalhes->status)['titulo'] }}
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            @if ($detalhes->status == 2 && !$detalhes->termo_responsabilidade_gerado)
                            {{--<div class="btn-align-float mb-3 mt-3">
                                <a href="javascript:void(0)">
                                    <button class="btn btn-warning" data-id_retirada="{{ $detalhes->id }}" data-bs-toggle="modal" data-bs-target="#gerarTermoModal">
                                        <span class="mdi mdi-access-point-network"></span> Gerar Termo
                                    </button>
                                </a>
                            </div>--}}

                            <div class="btn-align-float mb-3 mt-3">
                                <button type="button" class="btn btn-primary" id="gerar_termo" data-bs-toggle="modal" data-bs-target="#gerarTermoModal" data-bs-whatever="@mdo">Gerar Termo Digital</button>
                            </div>

                            @endif



                            @if (!empty($detalhes->termo_responsabilidade_gerado))
                            
                            <div class="d-flex">
                                
                                @if ($detalhes->status == 2 && $detalhes->termo_responsabilidade_gerado)
                                    
                                    <div class="btn-align-float mb-3 mt-3">
                                        <a class="dropdown-item" href="{{ url('admin/ferramental/retirada/termo') }}/{{ $detalhes->id }}?devolver_itens=false&funcionario={{ $detalhes->funcionario->nome ?? "sem reg." }}">
                                            <button class="btn btn-secondary">
                                                <span class="mdi mdi-download"></span> Baixar Termo
                                            </button>
                                        </a>
                                    </div>
                                    
                                @elseif ( $detalhes->status == 3 && $detalhes->termo_responsabilidade_gerado)
                                
                                    <div class="btn-align-float mb-3 mt-3">
                                        <a class="dropdown-item" href="{{ url('admin/ferramental/retirada/termo') }}/{{ $detalhes->id }}?devolver_itens=true&funcionario={{ $detalhes->funcionario->nome ?? "sem reg." }}">
                                            <button class="btn btn-secondary">
                                                <span class="mdi mdi-download"></span> Baixar Termo
                                            </button>
                                        </a>
                                    </div>
                               
                                
                                @endif
                                
    
                                <div class="btn-align-float mb-3 mx-3 mt-3">
                                    <a href="{{ route('ferramental.retirada.devolver', $detalhes->id) }}">
                                        <button class="btn btn-info">
                                            <span class="mdi mdi-redo-variant"></span> Devolver Itens
                                        </button>
                                    </a>
                                </div>
    
                                <div class="btn-align-float mb-3 mt-3">
                                    <a href="{{ route('ferramental.retirada.ampliar', $detalhes->id) }}">
                                        <button class="btn btn-default" style="border: solid 1px #ccc">
                                            <span class="mdi mdi-calendar-plus"></span> Ampliar Prazo
                                        </button>
                                    </a>
                                </div>
                            </div>
                            @endif

                            <table class="table table-bordered table-hover align-middle table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th> Obra </th>
                                        <th> Solicitante </th>
                                        <th> Funcionário </th>
                                        <th> Item </th>
                                        <th> Data de Inclusão </th>
                                        <th> Devolução Prevista </th>
                                        <th> Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detalhes->itens as $item)
                                    <tr>
                                        <td>{{ $detalhes->codigo_obra  }}</td>
                                        <td>{{ $detalhes->name }}</td>
                                        <td>{{ $detalhes->funcionario }}</td>
                                        <td class="d-flex">
                                            <div class="bg-danger px-2 py-1 rounded text-white">{{ $item->item_codigo_patrimonio }}
                                            </div>
                                            <div class="bg-info mx-2 px-2 py-1 rounded text-white">{{ $item->item_nome }}</div>
                                        </td>
                                        <td>{{ Tratamento::FormatarData($detalhes->created_at) }}</td>
                                        <td>{{ Tratamento::FormatarData($detalhes->data_devolucao_prevista) }}</td>
                                        <td>
                                            <div class="bg-{{ Tratamento::getStatusRetirada($item->status)['classe'] }} rounded px-2 py-1 text-white">
                                                {{ Tratamento::getStatusRetirada($item->status)['titulo'] }}
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            @if (!empty($detalhes->anexo))
                            <hr>
                            <h3>Anexos</h3>
                            <table class="table table-bordered table-hover align-middle table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th> Tipo </th>
                                        <th> Título </th>
                                        <th> Descrição </th>
                                        <th> Data de Inclusão </th>
                                        <th> Visualizar Arquivo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Termo de Responsabilidade - {{ $detalhes->anexo->tipo }}</td>
                                        <td>{{ $detalhes->anexo->titulo }}</td>
                                        <td>{{ $detalhes->anexo->descricao }}</td>
                                        <td>{{ Tratamento::FormatarData($detalhes->anexo->created_at) }}</td>
                                        <td>
                                            <a href="{{ route('ferramental.retirada.download', $detalhes->id) }}">
                                                <button class="btn btn-danger btn-sm">Baixar Arquivo</button>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            @endif
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="gerarTermoModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="gerarTermoLabel" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gerarTermoLabel">Assinar Termo de Retirada | Confira os dados logo abaixo</h5>
                <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @include('components.termo.termo_retirada_digital')
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancelar</button>
                {{--<button class="btn btn-warning retirada-assinar-termo" data-tipo="manual" type="button">Assinatura Manual</button>--}}
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assinar_termo_digital" data-bs-whatever="@getbootstrap">Assinatura digital</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade mt-5" id="assinar_termo_digital" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">

                <h5 class="modal-title" id="exampleModalLabel">Assinatura Digital</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if (session('mensagemFail'))

                <div class="alert alert-success d-flex align-items-center alert-dismissible fade show" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                        <use xlink:href="#check-circle-fill" />
                    </svg>
                    <div>
                        {{ session('mensagemFail') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                @endif

                <div class="card-header text-center mb-3">
                    VERIFIQUE SE OS SEUS DADOS ESTÃO CORRETOS
                </div>

                <form id="form_assinatura_digital">
                    @csrf
                    <div class="mb-3">

                        <label for="cpf" class="col-form-label">ID: </label>
                        <input type="text" class="form-control" value="{{ $detalhes->idFuncionario }}">

                        <label for="matricula" class="col-form-label">Matrícula:</label>
                        <input type="text" class="form-control" value="{{ @$detalhes->funcionario_matricula }}" readonly>

                        <label for="Nome" class="col-form-label">Nome: </label>
                        <input type="text" class="form-control" value="{{ @$detalhes->funcionario }}" readonly>

                        <label for="cpf" class="col-form-label">CPF: </label>
                        <input type="text" class="form-control" value="{{ @$detalhes->cpf }}" readonly>

                        <hr>

                        <label for="recipient-name" class="col-form-label"><strong>Insira a sua senha:</strong> (se esqueceu a sua senha? Solicite ao responsável pelo Almoxarifado para alterar)
                        <input type="password" class="form-control" id="password" name="password">
                    </div>

            </div>

            <div class="modal-footer">


                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                <button type="button" id="digital" class="btn btn-success">Assinar</button>

                </form>

            </div>


        </div>
    </div>
</div>

@include('components.anexo.form', [
'path' => 'termos_retirada',
'id_item' => $detalhes->id,
'id_modulo' => 18,
])

<script src="https://code.jquery.com/jquery-3.6.0.js"></script>

 <script>
    $(document).ready(function() {

        var id_retirada = '{{ @$detalhes->id }}';
        var url = "{{ route('ferramental/retirada/consultarCredenciaisTermo', ['id' => ':id']) }}";
        url = url.replace(':id', id_retirada);

        var urlAssinar = "";

        urlAssinar = "{{ route('ferramental/retirada/termo_assinar', ['id' => ':id']) }}";
        urlAssinar = urlAssinar.replace(':id', id_retirada);

        $('#digital').click(function() {

            let devolver_itens = "";

            devolver_itens = $('#devolver_itens').val();

            console.log(devolver_itens);

            var id_retirada = '{{ @$detalhes->id }}';

            var assinatura = $('#form_assinatura_digital').serialize();

            var urlEntregar_itens = "";

            if (devolver_itens == undefined) {
                urlEntregar_itens = urlAssinar;
            } else {
                urlEntregar_itens = urlAssinar + '?devolver_itens=true';
            }

            console.log(urlEntregar_itens);
            console.log(assinatura);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var password = $('#password').val();

            $.ajax({
                type: 'post',
                url: url,
                data: assinatura
            }).done(function(data) {

                if (password == "") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção',
                        text: 'A senha é obrigatória.'
                    });
                    return;
                }

                if (data == 'success') {
                    Swal.fire({
                        title: 'Atenção!',
                        text: "Você está prestes a assinar um documento confidencial para liberação dos itens já descritos. Esta operação não poderá ser revertida.",
                        icon: 'warning',
                        footer: 'Em caso de dúvidas, entre em contato com seu gestor.',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Assinar Documento'
                    }).then((result) => {
                        if (result.isConfirmed) {

                            let timerInterval;
                            Swal.fire({
                                title: 'Aguarde.',
                                html: 'Estamos autenticando o documento em <b></b> milisegundos.',
                                timer: 2000,
                                timerProgressBar: true,
                                didOpen: () => {
                                    Swal.showLoading();
                                    const b = Swal.getHtmlContainer().querySelector('b');
                                    timerInterval = setInterval(() => {
                                        b.textContent = Swal.getTimerLeft();
                                    }, 100);
                                },
                                willClose: () => {
                                    clearInterval(timerInterval);
                                }
                            }).then((result) => {

                                if (result.dismiss === Swal.DismissReason.timer) {

                                    $.ajaxSetup({
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        }
                                    });

                                    // salvar autenticidade
                                    $.ajax({
                                        type: 'post',
                                        url: urlEntregar_itens,
                                        data: assinatura,
                                        success: function(result) {

                                            if (result == 0) {
                                                Swal.fire(
                                                    'Atenção!!!',
                                                    'Algo deu errado na assinatura.',
                                                    'error'
                                                );
                                            }

                                            Swal.fire({
                                                title: 'Sucesso!',
                                                text: "O Termo de responsabilidade foi assinado com sucesso!",
                                                icon: 'success',
                                                showCancelButton: true,
                                                confirmButtonColor: '#3085d6',
                                                cancelButtonColor: '#d33',
                                                cancelButtonText: 'Fechar',
                                                confirmButtonText: 'Baixar Documento'
                                            }).then((result) => {

                                                if (result.isConfirmed) {

                                                    var urlDevolver_itens = "";

                                                    if (devolver_itens == undefined) {
                                                        urlDevolver_itens = 'admin/ferramental/retirada/termo/' + id_retirada;
                                                    } else {
                                                        urlDevolver_itens = 'admin/ferramental/retirada/termo/' + id_retirada + '?devolver_itens=true';
                                                    }

                                                    window.open(urlDevolver_itens);
                                                    location.reload();

                                                    $("#assinar_termo_digital").modal("hide");
                                                    $("#gerarTermoModal").modal("hide");
                                                }
                                            });
                                        }
                                    });
                                }
                            });
                        }
                    });

                } else {
                    Swal.fire({
                        title: "Atenção",
                        text: data,
                        icon: "warning"
                    });
                }

            });
        });

    });
</script>
@endsection
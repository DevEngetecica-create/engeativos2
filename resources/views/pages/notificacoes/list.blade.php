@extends('dashboard')

@section('title', 'Notificações')

@section('content')


    <div class="email-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-3">
        <div class="email-content minimal-border">
            <div class="p-4 pb-0">
                <div class="border-bottom border-bottom-dashed">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-3 flex-grow-1">Notificações - Obra: {{ $obra->codigo_obra }}</h4>
                    </div>

                    <div id="mensagem">
                    </div>

                    <div class="row align-items-end mt-3">
                        <div class="col">
                            <div id="mail-filter-navlist">
                                <ul class="nav nav-tabs nav-tabs-custom nav-success gap-1 text-center border-bottom-0"
                                    role="tablist">
                                    <li class="nav-item" style="width: 120px">
                                        <button class="nav-link fw-semibold active" id="pills-primary-tab"
                                            data-bs-toggle="pill" data-bs-target="" type="button" role="tab"
                                            aria-controls="pills-primary" aria-selected="true">
                                            <i class="ri-inbox-fill align-bottom d-inline-block"></i>
                                            <span class="ms-1 d-none d-sm-inline-block">Ações</span>
                                        </button>
                                    </li>

                                    <li class="nav-item" style="width: 250px">
                                        <button class="nav-link fw-semibold" id="pills-social-tab" data-bs-toggle="pill"
                                            data-bs-target="" type="button" role="tab" aria-controls="pills-social"
                                            aria-selected="false">
                                            <i class="ri-group-fill align-bottom d-inline-block"></i>
                                            <span class="ms-1 d-none d-sm-inline-block">Situação</span>
                                        </button>
                                    </li>

                                    <li class="nav-item">
                                        <button class="nav-link fw-semibold" id="pills-promotions-tab" data-bs-toggle="pill"
                                            data-bs-target="" type="button" role="tab" aria-controls="pills-promotions"
                                            aria-selected="false">
                                            <i class="ri-price-tag-3-fill align-bottom d-inline-block"></i>
                                            <span class="ms-1 d-none d-sm-inline-block">Menssagens</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="tab-content">
                    <div class="tab-pane fade show active" id="pills-primary" role="tabpanel"
                        aria-labelledby="pills-primary-tab">
                        <div class="message-list-content mx-n4 px-4 message-list-scroll">

                            <ul class="message-list" id="mail-list">
                                @if ($notificacoeslist->isEmpty())

                                    <p>Nenhuma notificação encontrada.</p>
                                @else
                                    @foreach ($notificacoeslist as $not)
                                        <div class="row my-3">
                                            <div class="d-flex col-1">
                                                <div class="text-center">

                                                    @if ($not->status == 'unread')
                                                        <a href="/admin/notificacoes/read/{{ $not->id }}"
                                                            title="Marcar como lida">
                                                            <span>
                                                                <i class="mdi mdi-square-outline mdi-18px text-warning"></i>
                                                            </span>
                                                        </a>
                                                    @endif

                                                    <span class="ver_menssagens" title="Visualizar mensagem"
                                                        data-id="{{ $not->id }}" data-bs-toggle="modal"
                                                        data-bs-target="#modalMenssagem">
                                                        <i class="mdi mdi-eye mdi-18px text-info mx-3"></i>
                                                    </span>
                                                </div>
                                            </div>


                                            <div class="col-2" style="width: 230px">
                                                <a href="/admin/notificacoes/read/{{ $not->id }}" class="title">
                                                    <span class="title-name">Engeativos: {{ $not->tipo }}</span>
                                                </a>
                                            </div>

                                            <div class="col-9 mx-3">
                                                <a href="/admin/notificacoes/read/{{ $not->id }}" class="subject">
                                                    <span class="subject-title">Olá</span> –
                                                    <span class="teaser">{{ $not->mensagem }} <strong> - data:
                                                            {{ Tratamento::dateBr($not->created_at) }}</strong></span>
                                                </a>

                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                            </ul>

                        </div>

                    </div>

                    <div class="row">
                        <div class="d-flex justify-content-start col-12 ">
                            <div class="paginacao">
                                {{ $notificacoeslist->render() }}
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-social" role="tabpanel" aria-labelledby="pills-social-tab">
                        <div class="message-list-content mx-n4 px-4 message-list-scroll">

                            <ul class="message-list" id="social-mail-list"></ul>

                        </div>

                    </div>

                    <div class="tab-pane fade" id="pills-promotions" role="tabpanel" aria-labelledby="pills-promotions-tab">

                        <div class="message-list-content mx-n4 px-4 message-list-scroll">

                            <ul class="message-list" id="promotions-mail-list"></ul>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- end email-content -->

        <!-- removeItemModal -->

        <div id="modalMenssagem" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Notificação do sistema</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            id="btn-close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mt-2 text-left">

                            <div id="menssagem_notificacao"> </div>

                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    @endsection

    @section('script')

        <script>
            $(document).ready(function() {
                $('.ver_menssagens').on('click', function() {
                    // Limpe o conteúdo do modal
                    $('#menssagem_notificacao').empty();

                    // Obtenha o id da mensagem
                    var id_menssagem = $(this).data('id');

                    console.log(id_menssagem);

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: 'GET',
                        url: "/admin/notificacoes/show/" + id_menssagem,
                        data: {
                            id: id_menssagem
                        },
                        success: function(data) {
                            console.log(data);
                            // Atualize o conteúdo do modal com a mensagem recebida
                            $('#menssagem_notificacao').html(
                                '<p><strong>Obra: </strong> ' + data.menssagem.obra.nome_fantasia +
                                '</p>' +
                                '<p><strong>Ação: </strong> ' + data.menssagem.tipo + '</p>' +
                                '<p><strong>Descrição: </strong> ' + data.menssagem.mensagem +
                                '</p>' +
                                '<p><strong>usuário: </strong> ' + data.menssagem.usuario +
                                '</p>' +


                                '<div class="d-flex gap-2 justify-content-center mb-3 mt-5">' +
                                '<button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>' +
                                '<a href="' + data.menssagem.link_acesso + '">' +
                                '<button class="btn w-sm btn-info " id="delete-record">Acessar</button>' +
                                '</a>' +
                                '</div>'
                            );

                            
                        },
                        error: function(xhr, status, error) {
                            console.error('Erro ao buscar a mensagem:', error);
                        }
                    });
                });
            });
        </script>

    @endsection

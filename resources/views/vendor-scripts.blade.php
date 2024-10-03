
<script src="{{ URL::asset('assets/js/Xlsx-export/browser/xlsx-populate.min.js') }}"></script>

<script src="{{ URL::asset('build/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ URL::asset('build/js/pages/select2.init.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
<script src="https://cdn.lordicon.com/libs/mssddfmo/lord-icon-2.1.0.js"></script>
<script src="{{ URL::asset('build/js/pages/modal.init.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/profile.init.js') }}"></script>
<script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>

<script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<!-- <script src="{{ URL::asset('build/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script> -->
<!-- <script src="{{ URL::asset('build/js/pages/mailbox.init.js') }}"></script>-->

<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="{{ URL::asset('build/js/pages/notifications.init.js') }}"></script>


<script>
    $(document).ready(function() {

        listarNotificacoes();

        $('.excel-filter-table').excelTableFilter({
            pagination: true,
            rowsPerPage: 10,
            paginationContainer: '#meu-container-paginacao', // Opcional: Define onde os botões de paginação serão inseridos
            captions: {
                prevPaginateBtn: 'Anterior',
                nextPaginateBtn: 'Próximo'
                // Outros captions podem ser adicionados conforme necessário
            }
        });

        /*  var motivoReprovacaoModal = new bootstrap.Modal(document.getElementById('motivoReprovacaoModal'), {
            keyboard: false
        });
 */
        var currentId = null;
        var currentSelectValue = null;

        $('.situacao-select').change(function() {
            var id = $(this).data('id');
            var selectValue = $(this).val();

            if (selectValue == 18) {
                // Armazenar o ID e o valor selecionado
                currentId = id;
                currentSelectValue = selectValue;

                // Fazer uma solicitação AJAX para obter o motivo existente
                $.ajax({
                    url: "{{ route('cadastro.funcionario.obter_motivo', ['id' => ':id']) }}"
                        .replace(':id', id),
                    method: 'get',
                    success: function(data) {
                        // Exibir o motivo existente na modal

                        console.log(data)
                        $('#motivoReprovacaoExistente').html(data.motivoReprovacao);
                        // Abrir o modal para solicitar o motivo da reprovação
                        motivoReprovacaoModal.show();
                    },
                    error: function(xhr, status, error) {
                        var errorMsg = "Ocorreu um erro ao obter o motivo da reprovação. ";
                        if (xhr.status && xhr.responseText) {
                            errorMsg += " Código do erro: " + xhr.status + ". Mensagem: " +
                                xhr.responseText;
                        } else {
                            errorMsg += " Status: " + status + ". Erro: " + error;
                        }
                        console.log(errorMsg);
                    }
                });
            } else {


                // Enviar a solicitação AJAX sem o motivo da reprovação
                enviarSolicitacao(id, selectValue);
            }

            $(this).data('previous', selectValue); // Armazenar a seleção anterior
        });

        $('#enviarMotivo').click(function() {
            var motivoReprovacao = $('#motivoReprovacao').val();
            if (motivoReprovacao) {
                // Enviar a solicitação AJAX com o motivo da reprovação
                enviarSolicitacao(currentId, currentSelectValue, motivoReprovacao);
                // Fechar o modal
                motivoReprovacaoModal.hide();
            } else {
                alert('Por favor, preencha o motivo da reprovação.');
            }
        });

        function enviarSolicitacao(id, selectValue, motivoReprovacao = '') {
            var url = "{{ route('cadastro.funcionario.aprovar_documentos', ['id' => ':id']) }}";
            url = url.replace(':id', id);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: url,
                method: 'post',
                data: {
                    id: id,
                    selectValue: selectValue,
                    motivoReprovacao: motivoReprovacao
                },
                success: function(data) {

                    console.log(data)

                    var documento = data[0];
                    var notification = data[1];

                    toastr[notification.type](notification.message);

                    // Atualizar o select específico
                    setTimeout(function() {
                        $('#situacao_doc' + id).html(
                            `
                        <select class="form-select situacao-select" style="opacity: 0.8; background-color: chartreuse;" disabled="disabled" readonly="true">
                            <option value="${documento.situacao_doc}">${documento.situacao_doc == 2 ? 'Sim' : 'Não'}</option>
                        </select>
                        `
                        );

                        // Recarregar a página após a atualização do select

                    }, 500);
                },
                error: function(xhr, status, error) {
                    var errorMsg = "Ocorreu um erro na solicitação. ";
                    if (xhr.status && xhr.responseText) {
                        errorMsg += " Código do erro: " + xhr.status + ". Mensagem: " + xhr
                            .responseText;
                        console.log(errorMsg);
                    } else {
                        errorMsg += " Status: " + status + ". Erro: " + error;
                        console.log(errorMsg);
                    }
                    $('#message').empty().removeClass().text("Ocorreu um erro na solicitação.")
                        .addClass('text-danger');
                }
            });
        }

        // Verificar se há um hash na URL ao carregar a página
        if (window.location.hash) {
            var hash = window.location.hash;
            if ($(hash).length) {
                // Ativar a aba correspondente
                $('a[href="' + hash + '"]').tab('show');

                // Rolagem suave para a div com id especificado no hash
                $('html, body').animate({
                    scrollTop: $(hash).offset().top
                }, 1000);
            }
        }
    });



    dados1 = {};

    dados1 = {};
    BASE_URL = '/admin';
    var route = window.location.pathname;


    $("#submit-prazo").submit(function() {
        let devolucao_prevista_atual = $("#devolucao_prevista_atual").val()
        let devolucao_prevista_nova = $("#devolucao_prevista").val()

        if (devolucao_prevista_atual == devolucao_prevista_nova) {
            toastr.error('A data de devolução não opde ser igual')
        } else {
            $("#submit-prazo").submit();
        }
        return false;
    });

    //Pesquisar as subcategorias de acordo com as categorias dos veiculos

    /*$('#idCategoria').change(function() {
        $('#mensagem').html('<span class="mensagem">Aguarde, carregando ...</span>');
        var selecao = $(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'GET',
            url: "{{ route('pesquisarSubcategorias') }}",
            data: {
                selecao: selecao
            }
        }).done(function(data) {

            console.log(data)
            // Preencha o segundo campo com os dados recebidos
            if (data.length > 0) {

                var option = "";

                var option = '<option> Selecione uma Subcategoria</option>';

                $.each(data, function(i, obj) {
                    option += '<option value="' + obj.id + '">' + obj.nomeSubCategoria + '</option>';
                });
                $('#mensagem').html(' - Resultado: <span class="fs-6 badge text-bg-secondary">' + data.length + ' </span> subcategoria(s) ');
            } else {

                $('#mensagem').html(' - Não foram encontradas nenhuma subcategoria!');
            }
            $('#idSubCategoria').html(option).show();
        });
    });*/

    //***************************************************************** */


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
                                    const b = Swal.getHtmlContainer()
                                        .querySelector('b');
                                    timerInterval = setInterval(() => {
                                        b.textContent = Swal
                                            .getTimerLeft();
                                    }, 100);
                                },
                                willClose: () => {
                                    clearInterval(timerInterval);
                                }
                            }).then((result) => {

                                if (result.dismiss === Swal.DismissReason
                                    .timer) {

                                    $.ajaxSetup({
                                        headers: {
                                            'X-CSRF-TOKEN': $(
                                                'meta[name="csrf-token"]'
                                            ).attr('content')
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
                                            }).then((
                                                result) => {

                                                if (result
                                                    .isConfirmed
                                                ) {

                                                    var urlDevolver_itens =
                                                        "";

                                                    if (devolver_itens ==
                                                        undefined
                                                    ) {
                                                        urlDevolver_itens
                                                            =
                                                            '/admin/ferramental/retirada/termo/' +
                                                            id_retirada +
                                                            '?devolver_itens=true';
                                                    } else {
                                                        urlDevolver_itens
                                                            =
                                                            '/admin/ferramental/retirada/termo/' +
                                                            id_retirada +
                                                            '?devolver_itens=true';
                                                    }

                                                    window
                                                        .open(
                                                            urlDevolver_itens
                                                        );
                                                    location
                                                        .reload();

                                                    $("#assinar_termo_digital")
                                                        .modal(
                                                            "hide"
                                                        );
                                                    $("#gerarTermoModal")
                                                        .modal(
                                                            "hide"
                                                        );
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

    //Pesquisar as placas ou os numeros de series  de acordo com os tipo dos veiculos

    /* $('#tipo_veiculo').change(function() {
        $('#mensagem').html('<span class="mensagem">Aguarde, carregando' +
            ' <div class="spinner-grow" style="width: 1rem; height: 1rem;" role="status">' +
            '<span class="visually-hidden">Loading...</span> ' +
            '</div></span>');

        var selecao_tipo = $(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'GET',
            url: "{{ route('ativo.veiculo.locacaoVeiculos.pesquisar_placa_modelo') }}",
            data: {
                selecao_tipo: selecao_tipo
            }
        }).done(function(data) {

            console.log(data)
            // Preencha o segundo campo com os dados recebidos
            if (data.length > 0) {

                console.log(data);

                var option = "";

                var option = '<option> Selecione uma Subcategoria</option>';

                $.each(data, function(i, obj) {

                    if (obj.tipo == "maquinas") {

                        option += '<option value="' + obj.id + '">' + obj.codigo_da_maquina + '</option>';

                    } else {

                        option += '<option value="' + obj.id + '">' + obj.placa + '</option>';

                    }

                });
                $('#mensagem').html(' - Resultado: <span class="fs-6 badge text-bg-secondary">' + data.length + ' </span> registros ');
            } else {

                $('#mensagem').html(' - Não foram encontrdo registros!');
            }
            $('#placa_modelo').html(option).show();
        });
    }); */
    //***************************************************************** */


    $("#calibrar_item").on('click', function() {
        $("#calibrarItem").show('fade');
    });


    /** Novo ID para o perfil Administrador */
    $("#novo_id").on('change', function() {

        novo_id = $(this).val();

        $.ajax({
            type: 'POST',
            url: "{{ route('atualizar.obra') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                novo_id: novo_id
            },
            success: function(result) {

                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                if (result.id == null) {
                    Toast.fire({
                        icon: "success",
                        title: "Você está logado como Administrador do Sistema."
                    });
                } else {

                    Toast.fire({
                        icon: "success",
                        title: "Você fez login na obra " + result.codigo_obra
                    });

                }
                setInterval(() => {
                    window.location.href = route;
                }, 3000);

            }
        })
    });




    var id_obra = $("#novo_id").val();

    function listarNotificacoes(id_obra) {
        $.ajax({
            url: "{{ route('get.notifications') }}",
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                id_obra: id_obra
            },
            dataType: 'json',
            success: function(response) {
                // Limpe a área onde você deseja exibir as notificações
                $('#listnotificacoes').empty();
                $('#totalnotificacoesbd1').text(response.totalnotificacoes);
                $('#totalnotificacoesbd2').text(response.totalnotificacoes);
                $('#totalnotificacoesbd3').text(response.totalnotificacoes);

                // Itere sobre as notificações recebidas e exiba-as
                response.notificacoes.forEach(function(notificacao) {
                    var id_servico = notificacao.id_servico;

                    // Adicione o HTML para exibir cada notificação
                    var notificationHtml = `
                        <div class="text-reset notification-item d-block dropdown-item position-relative">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <a href="ferramental/retirada/detalhes/${id_servico}" class="stretched-link notification-link">
                                        <h6 class="mt-0 mb-1 fs-13 fw-semibold">${notificacao.tipo} ${notificacao.id}</h6>
                                    </a>
                                    <div class="fs-13 text-muted">
                                        <p class="mb-1">${notificacao.mensagem}</p>
                                    </div>
                                    <p class="mb-0 fs-11 fw-medium text-uppercase text-muted"></p>
                                </div>
                                <div class="px-2 fs-15">
                    `;

                    if (notificacao.status == "unread") {
                        notificationHtml += `
                            <a href="/admin/notificacoes/read/${notificacao.id}" title="Marcar como lida">
                                <span>
                                    <i class="mdi mdi-square-outline mdi-18px text-warning"></i>
                                </span>
                            </a>
                        `;
                    }

                    notificationHtml += `
                                </div>
                            </div>
                        </div>
                    `;

                    $('#listnotificacoes').append(notificationHtml);

                    var mensagem_notificacao = "<a href='/admin/notificacoes'>" + notificacao
                        .mensagem + "</a>";

                    toastr["success"](mensagem_notificacao);
                });
            },
            error: function(xhr, status, error) {
                console.error('Erro ao buscar notificações:', error);
            }
        });
    }

    $('.notification-link').click(function(e) {
        e.preventDefault();
        var idRetirada = $(this).data('id');
        var url_notication = '{{ route('ferramental.retirada.detalhes', ':idRetirada') }}';
        url_notication = url_notication.replace(':idRetirada', idRetirada);
        window.location.href = url_notication;
    });

    /*$(document).ready(function() {
         setInterval(() => {
             listarNotificacoes()
         }, 100000);

     });*/

    $(document).ready(function() {

        $("#solicitante").select2();
        $("#novo_id").select2();
        $('#id_obra').select2();
        $('#id_funcionario').select2();
        $('#nivel').select2();
        $('#id_funcao').select2();
        //colorir a div da categoria
        $('#colorInput').on('input', function() {
            var color = $(this).val();
            $('#colorDiv').css('background-color', color);
        });

        // Inicializa o Select2
        $('#id_categoria').select2();
        $('#id_subcategoria').select2();
        $('#id_marcas').select2();


        $('#valor_unitario').mask('000.000.000.000.000,00', {
            reverse: true
        });

    });




    $(".ItemsRetirada").on('click', function() {
        let id_retirada = $(this).attr('data-id_retirada');


        $.ajax({
            type: 'GET',
            url: '/ferramental/retirada/items/' + id_retirada,
            data: {},
            success: function(result) {
                $(".modal-title").html('Itens Retirados # ' + id_retirada)
                $(".modal-body").html(result)
            }
        });
    });


    $("#gerar_termo").on('click', function() {

        $("#gerarTermoModal").show('fade');
        let id_retirada = $(this).attr('data-id_retirada');

        $(".retirada-assinar-termo").on('click', function(e) {

            let tipo = $(this).attr('data-tipo');


            console.log(tipo)

            if (tipo == 'manual') {
                window.open('/ferramental/retirada/termo/' + id_retirada);
                return location.reload();
            }

        })
    })

    $(".digitar-manualmente").on('click', function() {
        let field = $(this).attr('data-field');
        console.log(field)
        if (this.checked) {
            $("#" + field).attr("readonly", false);
        } else {
            $("#" + field).attr("readonly", true);
        }
    })



    $(document).on('blur', '#cep', function() {
        const cep = $(this).val();
        $.ajax({
            url: 'https://viacep.com.br/ws/' + cep + '/json/',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#estado').val(data.uf);
                $('#cidade').val(data.localidade);
                $('#bairro').val(data.bairro);
                $('#endereco').val(data.logradouro);
            }
        });
    });

    $(document).on('blur', '#cnpj', function() {
        const cnpj = $(this).val();
        const numerosCnpj = cnpj.replace(/\D/g, '');

        $.ajax({
            url: 'https://publica.cnpj.ws/cnpj/' + numerosCnpj,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#razao_social').val(data.razao_social);
                $('#nome_fantasia').val(data.estabelecimento.nome_fantasia);
                $('#cep').val(data.estabelecimento.cep);
                $('#endereco').val(data.estabelecimento.tipo_logradouro + ' ' + data.estabelecimento
                    .logradouro);
                $('#numero').val(data.estabelecimento.numero);
                $('#bairro').val(data.estabelecimento.bairro);
                $('#cidade').val(data.estabelecimento.cidade.nome);
                $('#estado').val(data.estabelecimento.estado.sigla);
                $('#email').val(data.estabelecimento.email);
            }
        });
    });



    $('.select2-multiple').select2({
        minimumResultsForSearch: -1,
        placeholder: function() {
            $(this).data('placeholder');
        }
    });

    $('#obra').select2();

    $('#tipo').select2();

    $('#marca').select2();
    //  $('#modelo').select2();
    $('#ano').select2();
    $('#situacao').select2();
    // $('#fornecedor').select2();
    $('#combustivel').select2();
    $('#servico').select2();
    $('.addItem').select2();

    /*     $(".money").inputmask('currency', {
            prefix: "R$ ",
            decimal: ",",
            thousands: "."
        });
    */
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })


    /*  $('.celular').inputmask('(99) 99999-9999');
        $('.cpf').inputmask('999.999.999-99');
        $('.cep').inputmask('99999-999');
        $('.cnpj').inputmask('99.999.999/9999-99');
    */

    $('#unit_price').mask('000.000.000.000.000,00', {
        reverse: true
    });

    /* Exclusão Padrão */
    $('.excluir-padrao').on('click', function() {
        let tabela = $(this).data('table');
        let modulo = $(this).data('module');
        let redirecionar = $(this).data('redirect');
        let id = $(this).data('id');

        console.log(tabela, modulo, redirecionar, id)

    });

    $(document).on('click', '.remove', function() {
        $(this).closest('.item-lista').remove();
    });

    // $("#placa").inputmask({
    //     mask: 'AAA-9*99'
    // });


    $(document).ready(function() {
        $("#placa").on("blur", function() {
            var numero = $(this).val();
            var numeroFormatado = formatarNumero(numero);
            $(this).val(numeroFormatado);
        });


    });

    function formatarNumero(numero) {
        if (numero.length === 7) {
            var quintoCaractere = numero.charAt(4);
            if (isNaN(quintoCaractere)) {
                return numero.slice(0, 3) + " " + numero.slice(3);
            } else {
                return numero.slice(0, 3) + "-" + numero.slice(3);
            }
        } else {
            return numero; // Não aplicar formatação se o número não tiver o comprimento esperado
        }
    }



    /** Listagem de Ativos - Requisição */

    $(".listar-ativos").select2({
        tags: true,
        multiple: false,
        tokenSeparators: [",", " "],
        minimumInputLength: 2,
        minimumResultsForSearch: 10,
        ajax: {
            url: '/ferramental/requisicao/lista_ativo',
            dataType: "json",
            type: "get",
            data: function(params) {
                var queryParameters = {
                    term: params.term,
                };
                return queryParameters;
            },
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            text: item.titulo + ' - Em Estoque: ' + item.quantidade_estoque,
                            id: item.id,
                        };
                    }),
                };
            },
        },
    });

    $('.listar-ativos-remover').on("click", function() {
        $(".template-row:last").remove();
    });

    $('.listar-ativos-adicionar').click(function() {
        $('#listar-ativos-linha').append($('#listar-ativos-template').html());
        $(".template:last").select2();
    });



    $('.listar-epis-remover').on("click", function() {
        $(".template-row-epis:last").remove();
    });

    $('.listar-epis-adicionar').click(function() {
        $('#listar-epis-linha').append($('#listar-epis-template').html());
        $(".template:last").select2();
    });



    $(document).on('change', '.listar-ativos', function() {
        console.log($(this).val());
        alvo = $(this);

        $.ajax({
                url: "{{ route('ferramental.requisicao.ativo_externo_id') }}/" + $(this).val(),
                type: 'get',
                data: {
                    "_token": "{{ csrf_token() }}",
                }
            })
            .done(function(quantidade) {
                console.log("Resultado", quantidade);
                alvo.parent().parent().find(".text_quantidade").val(1);
                alvo.parent().parent().find(".text_quantidade").prop('disabled', false);
                alvo.parent().parent().find(".text_quantidade").attr('max', quantidade);
            })
            .fail(function(jqXHR, textStatus, quantidade) {
                alert(quantidade);
            });

    });


    $(document).on('submit', '#addMarcaModal', function(e) {
        e.preventDefault();
        var marca = $("#add_marca_da_maquina").val();
        var _token = $("#_token_modal").val();

        $.ajax({
            type: 'POST',
            url: '',
            dataType: 'json',
            data: {
                '_token': _token,
                'marca': marca
            },
            success: function(response) {
                $('#marca_da_maquina').append('<option value="' + marca + '" selected="selected">' +
                    marca + '</option>');
                $('#addMarcaModal').hide();
                $('.modal-backdrop').hide();
                $('#addMarcaModal').trigger("reset");
            }
        });
    });

    $(document).on('submit', '#servicos-form', function(e) {
        e.preventDefault();
        var name = $("#servicos_modal").val();
        var _token = $("#_token_modal").val();

        $.ajax({
            type: 'POST',
            url: '{{ route('ativo.veiculo.manutencao.servico.ajax') }}',
            dataType: 'json',
            data: {
                '_token': _token,
                'name': name
            },
            success: function(response) {
                var servico_id = response.servico_id;
                var servico = response.servico;
                $('#servico_id').append('<option value="' + servico_id + '" selected="selected">' +
                    servico + '</option>');
                $('#modal-servicos').hide();
                $('.modal-backdrop').hide();
                $('#servicos-form').trigger("reset");
            }
        });
    });

    $(document).on('submit', '#funcao-form', function(e) {

        e.preventDefault();
        var funcao = $("#funcao_modal").val();
        var codigo = $("#codigo_modal").val();
        var _token = $("#_token_modal").val();

        $.ajax({
            type: 'POST',
            url: '{{ route('cadastro.funcionario.funcoes.ajax') }}',
            dataType: 'json',
            data: {
                '_token': _token,
                'funcao': funcao,
                'codigo': codigo
            },
            success: function(response) {
                var funcao_id = response.funcao_id;
                var funcao = response.funcao;
                var codigo = response.codigo;
                $('#id_funcao').append('<option value="' + funcao_id + '" selected="selected">' +
                    codigo + ' | ' + funcao + '</option>');
                $('#modal-funcao').hide();
                $('.modal-backdrop').hide();
                $('#funcao-form').trigger("reset");
            }
        });
    });

    $('#file-form').on('submit', function() {
        $('#modal-file').hide();
        $('.modal-backdrop').hide();
        $('#marcas-file').trigger("reset");
    });
</script>

<script>
    @if (Session::has('message'))

        var type = "{{ Session::has('type') ? session('type') : 'info' }}";
        var menssagem = "{{ session('message') }}"

        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        Toast.fire({
            icon: type,
            title: menssagem
        });
    @endif
</script>

@yield('script')

@yield('script-bottom')

@extends('dashboard')
@section('title', 'Produtos do Estoque')
@section('content')



<div class="row justify-content-center col-sm-12 col-lg-4 col-xl-12 mb-2 ">
    <div class="col-5">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary me-2">
                <i class=""></i>
            </span> Saída de Produtos<i class="mdi mdi mdi-dolly mdi-36px mx-4 align-middle"></i>
        </h3>
    </div>
</div>

<hr>

<div class="col-12">
    <form class="app-search-transfer d-none d-md-block">
        <div class="row">
            <div class="col-4">
                <div class="form-group">
                    <select class="form-control select2" id="id_categoria" name="id_categoria">

                        <option value="">Selecione uma Categoria</option>


                        @foreach ($ativo_configuracoes as $configuracao)

                        @if ($configuracao->id_relacionamento == 0)
                        <optgroup label="{{ $configuracao->titulo }}" readonly>
                            @else
                            <option value="{{ $configuracao->id }}">{{ $configuracao->titulo }}</option>
                            @endif

                            @endforeach

                    </select>
                    <input type="hidden" id="page" name="page" value="0">
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <select class="form-select form-control select2" id="id_epis_funcionarios" name="id_funcionarios" required>
                        <option value="" selected>Selecione um funcionário</option>
                        @foreach ($funcionarios as $funcionario)
                        <option value="{{ $funcionario->id }}">
                            {{ $funcionario->nome }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-1" title="Pesquisar">
                <button class="btn btn-primary btn-sm py-0"><i class="mdi mdi-magnify search-widget-icon mdi-24px"></i></button>
            </div>

            <div class="col-1" title="Limpar pesquisa!!!">
                <a href="{{ route('ativo.externo.transferencia.create') }}">
                    <span class="btn btn-warning btn-sm py-0">
                        <i class="clear-search bg-warning mdi mdi-delete mdi-24px"></i>
                    </span>
                </a>

            </div>

            <div class="col-1" title="Limpar a Sessão!!!">

                <span id="clearSession" class="btn btn-danger btn-sm">Limpar Sessão</span>

            </div>


        </div>
    </form>
</div>

<hr>


<form method="post" action="{{ route('ativo.estoque.saida.store') }}">

    @csrf
    <div class="row">

        <div class="col-lg-5 grid-margin stretch-card">
            <div class="card" style="height: 500px;">
                <div class="card-body" id="lista_produtos">

                    {{--tabela de itens do estoque--}}

                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap col-sm-12 col-lg-7 m-0 ">

            <div class="col-lg-12 mb-2" style="overflow-y:auto; height:500px">
                <div class="card p-0 m-0" style="overflow-y:auto; height:500px">
                    <div class="card-body px-4 m-0">

                        <ul class="list-group">
                            <li class="list-group-item active text-center" aria-current="true">Lista de Materiais</li>
                            <table class="table table-hover align-middle table-nowrap table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-left">Item</th>
                                        <th class="text-center">Lote</th>
                                        <th class="text-center">CA</th>
                                        <th class="text-center" width="11%">Qtde</th>
                                        <th class="text-center" width="10%">Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="ferramentasSelecionadas">

                                    {{--Itens selecionado do estoque--}}

                                </tbody>
                            </table>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card p-0 m-0">
                    <div class="card-footer clearfix">
                        <li class="list-group-item text-center" aria-current="true">
                            <span class="btn btn-outline-success btn-md" id="total">Total de itens: <strong>0</strong></span>
                        </li>
                    </div>
                </div>
            </div>
            <div class="col-3 mx-1">
                <div class="card p-0 m-0">
                    <div class="card-footer clearfix">
                        <li class="list-group-item text-center" aria-current="true">
                            <button class="btn btn-primary btn-md" id="btn-submit" type="submit">Salvar</button>
                        </li>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


<div id="lista_epis" class="modal fade modal-dialog-scrollable" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center pb-3">
                <h5 class="modal-title text-white" id="myModalLabel">EPIs' cadastrado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>

            <div class="m-4" id="mensagem"></div>

            <div class="modal-body" id="epis_data_bloqueados">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>

            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    // Função para enviar a solicitação AJAX para atualizar os IDs selecionados   

    var search = $('#search').val();

    var spanPatrimonio;

    $(document).ready(function() {
        var categorias = $('#id_categoria');
        var epis_funcionarios = $("#id_epis_funcionarios");
        var lista_produtos = $('#lista_produtos');


        // Carrega a tabela com itens baseado na categoria selecionada
        function carregarTabela(id_categoria, pagina = 1) {
            $.ajax({
                url: "/admin/ativo/estoque/pesquisar_categoria",
                type: 'GET',
                data: {
                    page: pagina,
                    id_categoria: id_categoria
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    lista_produtos.html(response.html); // Atualizar a lista de produtos
                    atualizarTotal(); // Atualiza o total de itens sempre que a lista é carregada ou alterada
                },
                error: function(xhr, status, error) {
                    console.error("Erro ao receber dados: " + error);
                }
            });
        }

        // Evento de mudança para carregar itens ao selecionar uma categoria
        categorias.change(function() {
            carregarTabela($(this).val());
        });

        // Paginação via AJAX
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            carregarTabela(categorias.val(), page);
        });

        // Manipulação de mudanças nos checkboxes para adicionar ou remover itens
        $(document).on('change', "input[name='id_produto[]']", function() {
            var $checkbox = $(this);
            var id = $checkbox.val();
            var $row = $checkbox.closest('tr');
            var id_funcionario = $("#id_epis_funcionarios").val();

            if ($("#id_epis_funcionarios").val() === "") {
                Swal.fire({
                    title: 'Atenção!',
                    text: 'Selecione um funcionário',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
                $checkbox.prop('checked', false);
                return;
            }

            //verificar se o epi esta liberado para o funcionário

            if ($('#id_categoria').val() == 26) { // se a categoria dos epis foi selecionada
                // faz a consulta ajax
                $.ajax({
                    type: 'GET',
                    url: "/admin/ativo/estoque/saida/consulta_epi",
                    data: {
                        id_funcionario: id_funcionario,
                        id_produto: id
                    },
                    success: function(data) {
                        //retorno da consulta                       

                        // se retornou success é porque o epi esta liberadop
                        if (data.icon == "success") {
                            Swal.fire({
                                title: data.title,
                                html: data.html,
                                icon: "warning",
                                confirmButtonText: 'Ok'
                            });

                            // se retornou warning é porque o epi não esta liberado
                        } else {

                            Swal.fire({
                                title: data.title,
                                html: `<p> Este EPI não está liberado para este funcionário <p>É necessário justificar a entrega no campo logo abaixo!!! </p>
                                        <div class="mb-3">
                                            <label for="justificar_epi" class="form-label">Justificaficava de entrega de EPI:</label>
                                            <textarea class="form-control" id="justificar_epi"  name="justificar_epi" rows="5"></textarea>
                                        </div>`,
                                icon: "warning",
                                focusConfirm: false,
                                showCancelButton: true,
                                confirmButtonText: "Cadastrar",
                                showLoaderOnConfirm: true,
                                preConfirm: () => {

                                    const justificar_epi = document.getElementById('justificar_epi').value;

                                    // cria os objetos para salvar a justificativa
                                    return {
                                        id_funcionario: id_funcionario,
                                        id_produto: id,
                                        justificar_epi: justificar_epi
                                    };
                                },
                                allowOutsideClick: () => !Swal.isLoading()
                            }).then((result) => {
                                if (result.value) {
                                    (async () => {
                                        try {
                                            // rota para salvar a justificativa
                                            const response = await fetch("{{route('ativo.estoque.saida.justificar_epi')}}", {
                                                method: "POST",
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                                },
                                                body: JSON.stringify({
                                                    // gera o json para passar para a controller justificar_epi
                                                    justificar_epi: result.value.justificar_epi,
                                                    id_funcionario: result.value.id_funcionario,
                                                    id_produto: result.value.id_produto
                                                })
                                            });
                                            if (!response.ok) {
                                                throw new Error('Request failed with status ' + response.status);
                                            }

                                            const data = await response.json();

                                            Swal.fire({
                                                title: 'Sucesso!',
                                                text: 'Dados enviados com sucesso.',
                                                icon: 'success'
                                            });

                                        } catch (error) {

                                            Swal.showValidationMessage(`Request failed: ${error.message}`);

                                        }
                                    })();
                                }
                            });

                        }

                        // Adiciona ou remove linhas baseado na resposta
                        handleCheckboxToggle($checkbox, $row, data.epiEncontrado);
                    },
                    error: function(xhr, status, error) {
                        console.error("Erro ao processar a solicitação: " + error);
                    }
                });
            } else {
                handleCheckboxToggle($checkbox, $row);
            }
        });

        function handleCheckboxToggle($checkbox, $row, epiEncontrado = true) {
            if ($checkbox.prop('checked')) {
                var nome_produtos = $row.find('td:eq(2)').text();
                var qtdes_estoque = $row.find('td:eq(3)').text();
                addRow($checkbox.val(), nome_produtos, qtdes_estoque);
            } else {
                removeRow($checkbox.val());
            }
        }

    });


    // Função para adicionar uma linha na tabela com eventos de incremento/decremento
    function addRow(id, nome_produtos, qtdes_estoque) {
        var newRow = `
                    <tr id="linha_tr-${id}">
                        <td>${nome_produtos}
                      
                        <input type="text" value="${qtdes_estoque}" id="quantidade_estoque${id}">
                        </td>
                        <td class="text-center">Lote</td>
                        <td class="text-center">Certificado de Autorização</td>
                        <td class="text-center">
                            <div class="input-group">
                                <span class="btn btn-outline-warning btn-sm  decrement" data-id="${id}" id="decrement${id}"><i class="mdi mdi-minus"></i></span>
                                <input type="text" class="form-control text-center quantity" value="1" data-id="${id}" style="height:30px" id="product-quantity${id}" 
                                        aria-label="Product quantity" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                <span class="btn btn-outline-secondary btn-sm increment" data-id="${id}" id="increment${id}"><i class="mdi mdi-plus"></i></span>
                            </div>                 
                        </td>
                        <td class="text-center"><span class="btn btn-outline-danger btn-sm remove-item" data-id="${id}"><i class="mdi mdi-close"></i></span></td>
                    </tr>
        `;
        $('#ferramentasSelecionadas').append(newRow);


        // Eventos para incrementar e decrementar quantidades

        function updateButtons(id) {
            var $incrementBtn = $('#increment' + id);
            var $decrementBtn = $('#decrement' + id);
            var $quantityInput = $('#product-quantity' + id);
            var qtde_estoque = parseInt($('#quantidade_estoque' + id).val(), 10);
            var qtde_retirada = parseInt($quantityInput.val(), 10);
            var qtde_saldo = qtde_estoque - qtde_retirada;

            // Atualiza a habilidade dos botões baseado no estoque
            $incrementBtn.prop('disabled', qtde_saldo <= 0);
            $decrementBtn.prop('disabled', $quantityInput.val() <= 1);

            if (qtde_saldo <= 0) {
                Swal.fire({
                    title: 'Atenção!',
                    html: 'A quantidade requisitada excede a quantidade em estoque. Estoque atual: <strong>' + qtde_estoque + '</strong>',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
            }
        }

        // Incremento
        $('#ferramentasSelecionadas').on('click', '#increment' + id, function() {
            var id = $(this).data('id');
            var $input = $('#product-quantity' + id);
            $input.val(parseInt($input.val(), 10) + 1);
            updateButtons(id);
            atualizarTotal(id)
        });

        // Decremento
        $('#ferramentasSelecionadas').on('click', '#decrement' + id, function() {
            var id = $(this).data('id');
            var $input = $('#product-quantity' + id);
            var newValue = parseInt($input.val(), 10) - 1;
            $input.val(newValue > 1 ? newValue : 1);
            updateButtons(id);
            atualizarTotal(id)
        });
    }

    // Remove uma linha da tabela de itens selecionados
    function removeRow(id) {
        $('#linha_tr-' + id).remove();
    }

    // Remove um item ao clicar no botão remover
    $(document).on('click', '.remove-item', function() {
        var id = $(this).data('id');
        removeRow(id);
        $("input[value='" + id + "'][name='id_ativo_externo_check[]']").prop('checked', false);
        atualizarTotal();
    });

    // Atualiza o total de itens selecionados
    function atualizarTotal(id) {
        var total = 0;
        $(".quantity").each(function() {
            total += parseInt($(this).val(), 10) || 0;
        });
        $("#total").text(`Total de itens: ${total}`);
    }

    // Gerenciamento da seleção de lote e ativação do checkbox
    $(document).on('change', '.lote-select', function() {
        var $select = $(this);
        var id = $select.data('id');
        var quantidadeDisponivel = parseInt($select.find('option:selected').data('quantidade'), 10);
        var nomeProduto = $select.closest('tr').find('td:eq(2)').text(); // Ajuste conforme a estrutura da tabela

        if (quantidadeDisponivel > 0) {
            $("#id_produto" + id).prop('checked', true);
            addRow(id, nomeProduto, quantidadeDisponivel);
        }
    });
</script>

@endsection
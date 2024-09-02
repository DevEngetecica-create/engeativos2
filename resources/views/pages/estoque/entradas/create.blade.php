@extends('dashboard')
@section('title', 'Editar Entrada de Produto')
@section('content')

    <div class="card">
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-center col-sm-12 col-lg-4 col-xl-12">
                    <div class="col-5">
                        <h3 class="page-title">
                            <span class="page-title-icon bg-gradient-primary me-2">
                                <i class="mdi mdi-dolly"></i>
                            </span> Entrada de Produtos<i class="mdi mdi-dolly mdi-36px mx-4 align-middle"></i>
                        </h3>
                    </div>
                </div>

                <hr class="m-0 mb-2">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Ops!</strong><br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="post" action="{{ route('ativo.estoque.entrada.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-3">
                            <div class="form-group mb-2">
                                <label for="obra_id">Obra </label>
                                <select class="form-select select2" id="id_obra" name="id_obra">

                                    @if(session()->get('obra')['id'] == null)
                                    <option value="">Selecione uma obra</option>

                                    @foreach ($obras as $obra)
                                        <option value="{{ $obra->id }}">{{ $obra->codigo_obra }} |
                                            {{ $obra->razao_social }}
                                        </option>
                                    @endforeach

                                    @else

                                    @foreach ($obras as $obra)
                                        <option value="{{ $obra->id }}" selected>{{ $obra->codigo_obra }} </option>
                                    @endforeach

                                    @endif

                                </select>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group mb-2">
                                <label for="obra_id">Categorias </label>
                                <select class="form-select select2" id="id_categoria" name="id_categoria">
                                    <option value="">Selecione uma Categoria</option>
                                    @foreach ($categorias as $categorie)
                                        <option value="{{ $categorie->id }}">{{ $categorie->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group mb-2">
                                <label for="obra_id">Subcategorias </label>
                                <select class="form-select select2" id="id_subcategoria" name="id_subcategoria">
                                    <option value="">Selecione uma Subcategoria</option>
                                    @foreach ($subcategorias as $subcategorie)
                                        <option value="{{ $subcategorie->id }}">{{ $subcategorie->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-3">
                            <div class="form-group mb-3">
                                <label for="obra_id">Produto </label>
                                <input class="form-control form-control-sm " id="nome_produto"
                                    placeholder="Nome do Produto">
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <div id="lista_produtos">
                                        <table class="table table-hover align-middle table-nowrap table-sm mb-0" >
                                            <thead style="background-color: crimson; color:aliceblue">
                                                <tr>
                                                    <th class="text-center">ID</th>
                                                    <th>Produto</th>
                                                    <th>Fornecedor</th>
                                                    <th>Marca</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($produtos_entradas as $produto)
                                                    <tr>
                                                        <td class="text-center">
                                                            <input data-id="{{ $produto->id }}" type="checkbox"
                                                                class="checkbox-container form-check-input"
                                                                value="{{ $produto->id }}"
                                                                id="id_produto{{ $produto->id }}"
                                                                name="id_produto_tabela[]" style="height:15px; width:15px">
                                                        </td>
                                                        <td><small>{{ $produto->nome_produto }}</small></td>
                                                        <td><small>{{ $produto->fornecedor->nome_fantasia ?? "sem reg." }}</small></td>
                                                        <td><small>{{ $produto->marca->nome_marca }}</small></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <small>
                                            <div class="row mt-3">
                                                <div class="d-flex justify-content-end col-sm-12 col-md-12 col-lg-12">
                                                    <div class="paginacao">
                                                        {{ $produtos_entradas->render() }}
                                                    </div>
                                                </div>
                                            </div>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-xl-12">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Lista de produtos</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-hover align-middle table-nowrap table-sm mb-0">
                                        <thead style="background-color: darkorange; color:aliceblue">
                                            <tr>
                                                <th>Produto</th>
                                                <th>Qtde</th>
                                                <th>Valor unit. (R$)</th>
                                                <th>Total (R$)</th>
                                                <th>Nº NF</th>
                                                <th>Arquivo NF</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody id="ferramentasSelecionadas">
                                            {{-- Itens selecionados do estoque --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-1 col-md-10 col-xl-10"></div>
                            <div class="col-sm-10 col-md-10 col-xl-2">
                                <input type="text" class="form-control text-center bg-success text-white"
                                    id="total_calculado" readonly>
                            </div>
                        </div>

                        <div class="card-footer mt-2">
                            <button class="btn btn-primary btn-md font-weight-medium" type="submit">Salvar</button>

                            <a href="{{ route('ativo.estoque.index') }}">
                                <span class="btn btn-danger btn-md font-weight-medium">Cancelar</span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        $(document).ready(function() {
            var categorias = $('#id_categoria');
            var subcategorias = $('#id_subcategoria');
            var lista_produtos = $('#lista_produtos');
            var nome_produto = $('#nome_produto');
            var selectedCategory;

            function carregarTabela(id_categoria, id_subcategoria, pagina = 1, nome_produto = '') {
                if ($("#id_categoria").val() === "") {
                    Swal.fire({
                        title: 'Atenção!',
                        text: 'Selecione uma categoria',
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    });
                    $('#nome_produto').val("");
                    return;
                }

                $.ajax({
                    url: "{{ route('anexo.estoque.entrada.pesquisar_categoria') }}",
                    type: 'GET',
                    data: {
                        page: pagina,
                        id_categoria: id_categoria,
                        id_subcategoria: id_subcategoria,
                        nome_produto: nome_produto
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        lista_produtos.html(response.html); // Atualizar a lista de produtos
                        atualizarTotal
                    (); // Atualiza o total de itens sempre que a lista é carregada ou alterada
                    },
                    error: function(xhr, status, error) {
                        console.error("Erro ao receber dados: " + error);
                    }
                });
            }

            function atualizarTotal() {
                // Adicione aqui a lógica para atualizar o total de itens
            }

            categorias.on('change keyup', function() {
                selectedCategory = $(this).val();
                carregarTabela(selectedCategory, subcategorias.val(), 1, nome_produto.val());
            });

            subcategorias.on('change keyup', function() {
                carregarTabela(categorias.val(), $(this).val(), 1, nome_produto.val());
            });

            nome_produto.on('change keyup', function() {
                carregarTabela(categorias.val(), subcategorias.val(), 1, nome_produto.val());
            });

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                carregarTabela(categorias.val(), subcategorias.val(), page, nome_produto.val());
            });

            $(document).on('change', "input[name='id_produto_tabela[]']", function() {
                var $checkbox = $(this);
                var id = $checkbox.val();
                var $row = $checkbox.closest('tr');

                handleCheckboxToggle($checkbox, $row);
            });


            function handleCheckboxToggle($checkbox, $row) {

                if ($("#id_categoria").val() === "" ||  $("#id_subcategoria").val() ==="") {
                    Swal.fire({
                        title: 'Atenção!',
                        text: 'Selecione uma categoria e uma Subcategoria',
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    });
                    $('#nome_produto').val("");
                    return;
                }
                if ($checkbox.prop('checked')) {
                    var nome_produtos = $row.find('td:eq(1)').text();
                    addRow($checkbox.val(), nome_produtos);
                    if (selectedCategory == 1) {
                        addAdditionalFields($checkbox.val());
                    }
                } else {
                    removeRow($checkbox.val());
                }
            }

            function addRow(id, nome_produtos) {
                var newRow = `
                <tr id="linha_tr-${id}" >
                    <td>
                        <input type="hidden" name="id_produto[]" value="${id}">
                        <small>${nome_produtos}</small>
                    </td>
                    <td>
                        <input class="form-control form-control-sm text-center" id="quantidade${id}" name="quantidade_entrada[]" type="text" style="height: 27px;padding: 3px; width:50%" required>
                    </td>
                    <td>
                        <input class="form-control form-control-sm text-center" id="valor_unitario${id}" name="valor_unitario_entrada[]" type="text" style="height: 27px;padding: 3px; width:50%" required>
                    </td>
                    <td>
                        <input class="form-control form-control-sm text-center valor_total" id="valor_total${id}" name="valor_total_entrada[]" type="text" style="height: 27px;padding: 3px;width:50%" required>
                    </td>
                    <td>
                        <input class="form-control form-control-sm" name="nota_fical[]" type="text" style="height: 27px;padding: 3px; width:50%" required>
                    </td>
                    <td>
                        <input class="form-control form-control-sm" type="file" id="arquivo_nf" name="arquivo_nf[]" required>
                    </td>
                    <td class="text-center">
                        <span class="btn btn-outline-danger btn-sm remove-item" data-id="${id}">
                            <i class="mdi mdi-close"></i>
                        </span>
                    </td>
                </tr>
            `;

                $('#ferramentasSelecionadas').append(newRow);
            }

            function addAdditionalFields(id) {
                var additionalFields = `
                <tr id="extra_fields_tr-${id}" class="border-success">
                    <td colspan="7">
                        <div class="row mb-2">                                               
                            <div class="col-sm-6 col-xxl-1">
                                <input class="form-control form-control-sm" id="cert_aut_entrada${id}" name="cert_aut_entrada[]" placeholder="CA">
                            </div>

                            <div class="col-sm-6 col-xxl-1">
                                <input class="form-control form-control-sm" id="num_lote_entrada${id}" name="num_lote_entrada[]" placeholder="Nº Lote">
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-6 col-xxl-2">
                                <input class="form-control form-control-sm" type="date" id="data_validade_lote_ca${id}" tittle="data de validade do lote" name="data_validade_lote_ca[]">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-xxl-4">
                                <input class="form-control form-control-sm" type="file" id="arquivo_ca${id}" name="arquivo_ca[]">
                            </div>
                        </div>
                    </td>
                </tr>
            `;

                $(`#linha_tr-${id}`).after(additionalFields);
            }

            function removeRow(id) {
                $('#linha_tr-' + id).remove();
                $('#extra_fields_tr-' + id).remove();
            }

            function calcularValorTotal(id) {
                var quantidade = parseFloat($(`#quantidade${id}`).val()) || 0;
                var valorUnitario = parseFloat($(`#valor_unitario${id}`).val()) || 0;
                var valorTotal = quantidade * valorUnitario;
                $(`#valor_total${id}`).val(valorTotal.toFixed(2));
                calcularTotalGeral(); // Atualizar o total geral
            }

            $(document).on('change keyup', '[id^=valor_unitario]', function() {
                var id = this.id.match(/\d+/)[0];
                calcularValorTotal(id);
            });

            $(document).on('input', '[id^=quantidade]', function() {
                var id = this.id.match(/\d+/)[0];
                calcularValorTotal(id);
            });

            $(document).on('click', '.remove-item', function() {
                var id = $(this).data('id');
                removeRow(id);
                $("input[value='" + id + "'][name='id_produto_tabela[]']").prop('checked', false);
                atualizarTotal();
            });

            function calcularTotalGeral() {
                var totalGeral = 0;
                $('.valor_total').each(function() {
                    var valor = parseFloat($(this).val()) || 0;
                    totalGeral += valor;
                });
                $('#total_calculado').val(totalGeral.toFixed(2));
            }
        });
    </script>


@endsection

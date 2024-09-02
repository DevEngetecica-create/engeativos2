@extends('dashboard')
@section('title', 'Transferências - Ativos')
@section('content')

<div class="card shadow-sm">
    <div class="card-body">
        <div class="row justify-content-center col-sm-12 col-lg-4 col-xl-12 mb-2 ">
            <div class="col-5">
                <h3 class="page-title">
                    </span> Desmobilização de obra<i class="mdi mdi-check icon-sm text-primary align-middle"></i>
                </h3>
            </div>
        </div>

        <hr>

        <div class="col-6">
            <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Pesquisar" autocomplete="on" id="search" name="search" value="" aria-label="Search">
                <button class="btn btn-outline-success m-0 " type="submit"><i class="mdi mdi-magnify search-widget-icon"></i></button>
                <a class="btn btn-outline-warning mx-2 " title="Limpar pesquisa!!!" href="{{ route('ativo.externo.transferencia') }}"><i class="mdi mdi mdi-delete mdi-24x"></i></a>
            </form>
        </div>
        <hr>

        <form method="post" action="{{ route('ativo.externo.transferencia.store') }}">

            <div class="row mx-3 mb-3">
                <div class="col-3">
                    <div class="form-check form-switch form-check-warning form-switch-lg" dir="ltr">
                        <input type="checkbox" class="form-check-input" id="obra_inteira" name="obra_inteira" value="true">
                        <label class="form-check-label" for="obra_inteira">Desmobilizar obra inteira</label>
                    </div>
                </div>
                <!-- Default Modals -->

            </div>

            @csrf
            <div class="row">
                <div class="col-lg-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <table class="table-striped table" id="lista_ferramentas">
                                <thead>
                                    <tr>
                                        <th>ID </th>
                                        <th>Obra</th>
                                        <th>Patrimônio</th>
                                        <th>Item</th>
                                        <th>Situação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ativos as $ativo)
                                    <tr>
                                        <td>
                                            <input class="checkbox-container" value="{{ $ativo->id }}" class="form-check-input" id="id_ativo_externo_check{{ $ativo->id }}" name="id_ativo_externo_check[]" type="checkbox" style="height:15px; width:15px">
                                        </td>
                                        <td>{{ $ativo->obra->nome_fantasia }}</td>
                                        <td>{{ $ativo->patrimonio }}</td>
                                        <td>{{ $ativo->configuracao->titulo ?? "Sem reg."}}</td>
                                        <td>{{ $ativo->situacao->titulo ?? "Sem reg." }}</td>
                                    </tr>

                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row mt-3" disabled="disabled" readonly="true">
                                <div class="d-flex justify-content-end col-sm-12 col-md-12 col-lg-12 " disabled="disabled" readonly="true">
                                    <div class="paginacao" disabled="disabled" readonly="true">
                                        {{$ativos->render()}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap col-sm-12 col-lg-3 m-0 ">
                    <div class="col-lg-12 mb-3" style="overflow-y:auto; height:600px">
                        <div class="card p-0 m-0" style="overflow-y:auto; height:600px">
                            <div class="card-body px-4 m-0">
                                <ul class="list-group" id="ferramentasSeleciondas">
                                    <li class="list-group-item active text-center" aria-current="true">Ferramentas a serem Transferidas</li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="card p-3 m-0">
                        <div class="mb-3">
                            <i class="mdi mdi-arrow-left-circle mdi-24px text-warning"></i>
                            <label class="form-label" for="obra">Obra de Origem <span class="text-danger">*</span></label>
                            
                            <select class="form-select form-control select2" id="id_obraOrigem" name="id_obraOrigem" required>
                                <option value="" selected>Selecione uma obra</option>

                                @foreach ($obras as $obra)

                                <option value="{{ $obra->id }}" {{ @$editLocacaoVeiculos->obraDestino->id == $obra->id ? 'selected' : '' }}>

                                    {{ $obra->codigo_obra }} - {{ $obra->razao_social }}

                                </option>

                                @endforeach

                            </select>
                        </div>
                        <div class="mb-3">
                            <i class="mdi  mdi-arrow-right-circle mdi-24px text-success"></i>
                            <label class="form-label" for="obra">Obra de Destino <span class="text-danger">*</span></label>
                            <select class="form-select form-control select2" id="id_obraDestino" name="id_obraDestino" required>
                                <option value="" selected>Selecione uma obra</option>

                                @foreach ($obras as $obra)

                                <option value="{{ $obra->id }}" {{ @$editLocacaoVeiculos->obraDestino->id == $obra->id ? 'selected' : '' }}>

                                    {{ $obra->codigo_obra }} - {{ $obra->razao_social }}

                                </option>

                                @endforeach

                            </select>
                        </div>

                        <div>
                            <label class="form-label" for="obra">Motivo da transferência<span class="text-danger"> * </span></label>
                            <textarea class="form-control" id="motivo_transferencia" placeholder="Descreva o motivo da transferência" name="motivo_transferencia" rows="6" required>{{ old('motivo_transferencia') }}</textarea>
                        </div>

                        <div class="card-footer clearfix">
                            <button class="btn btn-success btn-lg" id="btn-submit" type="submit">Transferir</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="modal_bloqueados" class="modal fade modal-dialog-scrollable modal-lg" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center bg-warning pb-3">
                <h5 class="modal-title text-white" id="myModalLabel">Ferramentas bloqueadas para transferência</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>

            <div class="modal-body" id="data_bloqueados">

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>



    <script>
        // Função para enviar a solicitação AJAX para atualizar os IDs selecionados 

        var search = $('#search').val();
        var spanPatrimonio;

        $(document).ready(function() {

            $("#id_obraDestino").select2();
            $("#id_obraOrigem").select2();

            $("#obra_inteira").click(function(e) {
                // Verifica se o checkbox #obra_inteira está marcado

                if ($(this).is(":checked")) {

                    var selecao = $(this).val();

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: 'GET',
                        url: "{{ route('ativo.externo.transferencia.bloqueio')}}",
                        data: {
                            selecao: selecao
                        }
                    }).done(function(data) {

                        console.log(data.message)
                        if (data.type == "error") {
                            toastr[data.type](data.message)
                        } else {

                            // Limpe o conteúdo anterior

                            $('#data_bloqueados').empty();

                            // Preencha a tabela com os dados recebidos

                            var html = `
                                <table  class="table table-nowrap">
                                    <thead>
                                        <tr>  
                                            <th >Patrimônio</th>
                                            <th style="max-width:200px !important">Item</th>
                                            <th>Situação</th>
                                        </tr>
                                    </thead>
                                    <tbody>  
                                `;

                            $.each(data.type, function(index, item) {

                                html += "<tr>";
                                html += "<td>" + item.patrimonio + "</td>";
                                html += "<td style='max-width: 200px !important'>" + item.ativo_externo.titulo + "</td>";
                                html += "<td>" + item.situacao.titulo + "</td>";
                                html += "</tr>";
                            });

                            html += "</tbody>";
                            html += "</table>";

                            $('#data_bloqueados').html(html);

                            // Exiba a modal

                            $('#modal_bloqueados').modal('show');

                        }

                    });

                    // Desativa todos os checkboxes dentro da tabela

                    $("#lista_ferramentas input[type='checkbox']").prop("disabled", true);

                    //desativa a paginação

                    $(".paginacao").css({
                        "pointer-events": "none", // Impede eventos de clique
                        "opacity": "0.5" // Reduz a opacidade para indicar que está bloqueada
                    });

                } else {

                    // Ativa todos os checkboxes dentro da tabela

                    $("#lista_ferramentas input[type='checkbox']").prop("disabled", false);

                    //Reativa a paginação

                    $(".paginacao").css({
                        "pointer-events": "block", // Impede eventos de clique
                        "opacity": "1" // volta a opacidade para indicar que está bloqueada
                    });

                    // Esconde a modal quando o checkbox for desmarcado
                    $('#modal_bloqueados').hide();
                }

            });

        });


        $(".checkbox-container").click(function(e) {

            var checados = [];
            var selectedIds = [];
            const patrimonio = [];
            const titulo = [];

            var clicado = $('#id_ativo_externo_check' + $(this).val());

            $("input[name='id_ativo_externo_check[]']:checked").each(function() {

                checados.push($(this).val());
                selectedIds.push($(this).val());

            });

            spanPatrimonio = (search != "") ? $('#patrimonio').val() + "," + $(this).val() : checados.join(",");

            clicado.change(function(event) {
                
                event.preventDefault();
                var isChecked = $(this).prop('checked');

                if (!isChecked) {
                    var valorParaRemover = $(this).val();
                    var index = checados.indexOf(valorParaRemover);
                    if (index !== -1) {
                        checados.splice(index, 1);
                    }

                    $('#' + $(this).val()).remove();
                    $('#div' + $(this).val()).remove();
                }
            });



            $("#id_ativo_externo_check" + $(this).val()).change(function() {

                var row = $(this).closest('tr');
                if ($(this).prop('checked')) {

                    var id = row.find('td:eq(0)').text();
                    titulo.push(row.find('td:eq(3)').text());
                    patrimonio.push(row.find('td:eq(2)').text());

                    var spanPatrimonio = document.getElementById('id_ativo_externo' + $(this).val());
                    spanPatrimonio.textContent = patrimonio + ' - ' + titulo;
                    updateSelectedIds(selectedIds, patrimonio, titulo);

                } else {

                    var indexToRemove = patrimonio.indexOf(row.find('td:eq(2)').text());

                    if (indexToRemove != -1) {
                        patrimonio.splice(indexToRemove, 1);
                    }

                    var spanPatrimonio = "";
                }

            });
//<input type="hidden" value="${$(this).val()}" name="id_ativo_externo[]">
            var divDetalhes = `<li class="list-group-item" id="div${$(this).val()}">

            <span type="button" id="${$(this).val()}">
                <i class="mdi mdi-checkbox-marked-outline mdi-18x text-success"></i>
                <span class=" p-0 m-0" id="id_ativo_externo${$(this).val()}" style="font-size:0.8rem"></span>
                <input type="hidden" value="${$(this).val()}" name="id_ativo_externo[]">

            </span>

        </li>`;

            $('#ferramentasSeleciondas').append(divDetalhes);

        });



        function updateSelectedIds(selectedIds, patrimonio, titulo, valorParaRemover) {

            $.ajax({
                url: '/update-selected-ids',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    selectedIds: selectedIds,
                    patrimonio: patrimonio,
                    titulo: titulo,
                },

                success: function(response) {
                    console.log(response);
                },

                error: function(xhr, status, error) {
                    console.error('Erro ao atualizar IDs selecionados:', error);
                }
            });
        }


        $(document).ready(function() {

            var selectedIds = {!! json_encode(Session::get('selectedIds', [])) !!};
            var patrimonio = {!! json_encode(Session::get('patrimonio', [])) !!};
            var titulo = {!! json_encode(Session::get('titulo', [])) !!};

            var id = [];
            var nome_patrimonio = [];
            var tituloSessao = [];

            // Preencher os arrays id e nome_patrimonio

            selectedIds.forEach(function(valor) {
                id.push(valor);
            });

            patrimonio.forEach(function(valor) {
                nome_patrimonio.push(valor);
            });

            // Mostrar os checkboxes selecionados

            id.forEach(function(valor) {
                $('.checkbox-container[value="' + valor + '"]').prop('checked', true);
            });

            // Gerar o HTML para os detalhes das ferramentas selecionadas

            titulo.forEach(function(tituloSessao, index) {
                var divDetalhesSessao;

                divDetalhesSessao =
                `<li class="list-group-item" id="div${id[index]}"> 
                    <span type="button" id="${id[index]}"> 
                        <i class="mdi mdi-checkbox-marked-outline mdi-18x text-success"></i> 
                        <span class="p-0 m-0" id="id_ativo_externo${id[index]}" style="font-size:0.8rem">${nome_patrimonio[index]} - ${tituloSessao}</span> 
                        <input type="hidden" value="${id[index]}" name="id_ativo_externo[]"> 
                    </span> 
                </li>`;

                $('#ferramentasSeleciondas').append(divDetalhesSessao);
            });

        });
    </script>



    @endsection
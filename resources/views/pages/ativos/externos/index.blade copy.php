@extends('dashboard')
@section('title', 'Ativos Externos')
@section('content')


<link href="http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link href="http://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<link href="http://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap4.min.css">



<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Ativos Externos
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Ativos <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

<div class="page-header">
    <h3 class="page-title">
        <a href="{{ route('ativo.externo.adicionar') }}">
            <button class="btn btn-sm btn-danger">Inclusão de Novos Ativos</button>
        </a>
    </h3>
</div>

<div class="container-fluid">
    <div class="table-responsive table-sm">

        <table id="example" class="table table-striped table-bordered" width="100%">
            <thead>
                <tr>
                    <th>Ações</th>
                    <th>Obra</th>
                    <th>Patrimônio</th>
                    <th style="max-width:30% !important">Título</th>
                    <th>Valor</th>
                    <th>Calibração</th>
                </tr>
            <tbody>

            </tbody>
            </thead>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="anexarArquivoAtivoExterno" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="anexarArquivoAtivoExternoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="anexarArquivoAtivoExternoLabel">Anexo Ativo Externo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="lista_anexo">

            </div>

            <form action="{{ route('anexo.upload') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="diretorio" value="ativo_externo">
                <input type="hidden" name="id_modulo" value="13">

                <div class="modal-body">
                    <div class="mb-3 col-2">
                        <input type="text" class="form-control" id="id_item" name="id_item" required readonly>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-6">
                            <input type="text" class="form-control" id="titulo" name="titulo" required placeholder="Titulo do Arquivo">
                        </div>
                        <div class="mb-3 col-6">
                            <input type="date" class="form-control" id="data_vencimento" name="data_vencimento" required placeholder="">
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="file" accept="image/*,.pdf" class="form-control" id="arquivo" name="arquivo" required>
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" id="detalhes" name="detalhes" placeholder="Detalhes"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Salvar Anexo</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>


<script lang="javascript">
    $(function() {
        $(document).on("click", ".ativo-externo", function() {
            var id_ativo_externo = $(this).data('id');
            $(".modal-body #id_item").val(id_ativo_externo);

            $.ajax({
                    url: "{{ url('admin/ativo/externo/anexo') }}/" + id_ativo_externo,
                    type: 'get',
                    data: {}
                })
                .done(function(result) {
                    $("#lista_anexo").html(result)
                })
                .fail(function(jqXHR, textStatus, result) {

                });
        });


    });



    function format(d) {
        return (
            '<p>' +
            'ID: ' +
            d.id +
            ' | ' +
            'Status: ' +
            d.status +
            ' | ' +
            'Gerenciar: ' +
            d.acoes +
            '<p>'


        );
    }

    $(document).ready(function() {


        var table = $('#example').DataTable({

            ajax: BASE_URL + "/ativo/externo/lista",
            method:'GET',
            columns: [{
                    class: 'dt-control',
                    orderable: false,
                    data: null,
                    defaultContent: '',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'obra'
                },
                {
                    data: 'patrimonio'
                },
                {
                    data: 'titulo'
                },
                {
                    data: 'valor'
                },
                {
                    data: 'calibracao'
                }


            ],
            dom: 'B<"clear">lfrtip',
            buttons: {
                name: 'primary',
                buttons: ['pdf', 'excel']
            },
            pageLength: 50,
            order: [
                [0, 'desc']
            ],
            language: {
                search: 'Buscar informação da Lista',
                url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json',
            },

            lengthChange: true,
        });



        // Array to track the ids of the details displayed rows
        const detailRows = [];

        table.on('click', 'tbody td.dt-control', function() {
            let tr = event.target.closest('tr');
            let row = table.row(tr);
            let idx = detailRows.indexOf(tr.id);

            if (row.child.isShown()) {
                tr.classList.remove('details');
                row.child.hide();

                // Remove from the 'open' array
                detailRows.splice(idx, 1);
            } else {
                tr.classList.add('details');
                row.child(format(row.data())).show();

                // Add to the 'open' array
                if (idx === -1) {
                    detailRows.push(tr.id);
                }
            }
        });

        // On each draw, loop over the `detailRows` array and show any child rows
        table.on('draw', () => {
            detailRows.forEach((id, i) => {
                let el = document.querySelector('#' + id + ' td.dt-control');

                if (el) {
                    el.dispatchEvent(new Event('click', {
                        bubbles: true
                    }));
                }
            });
        });


    });
</script>


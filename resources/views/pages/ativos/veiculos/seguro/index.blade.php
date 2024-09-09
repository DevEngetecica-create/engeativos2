@extends('dashboard')
@section('title', 'Veículo')
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span>

        Seguro do Veículo
       
    </h3>

    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                Ativos <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

<div class="page-header">
    <h3 class="page-title">
        <a class="btn btn-sm btn-danger" href="">
            Adicionar
        </a>
    </h3>
</div>



@if(session('mensagem'))
<div class="alert alert-warning">
    {{ session('mensagem') }}
</div>
@endif


<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
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


                <table class="table-hover table-striped table">

                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th>Seguradora</th>
                            <th>Custo</th>
                            <th>Carência Inicial</th>
                            <th>Carência Final</th>
                            <th width="10%">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($seguros as $seguro)

                        <tr>

                            <td><span class="text-center">{{ $seguro->id }}</span></td>

                            <td>{{($seguro->nome_seguradora) }}</td>

                            <td>R$ {{ Tratamento::currencyFormatBr($seguro->valor) }} </td>

                            <td>{{ Tratamento::dateBr($seguro->carencia_inicial) }}</td>

                            <td>{{ Tratamento::dateBr($seguro->carencia_final) }}</td>

                            <td class="d-flex gap-2">
                                <a data-bs-toggle="modal" data-bs-target="#anexarArquivoAtivoSeguro" class="seguro" href="javascript:void(0)" data-id="{{$seguro->id}}">
                                    <span class='btn btn-success  btn-sm ml-1'><i class="mdi mdi-upload"></i></span>
                                </a>

                                <a href="{{ route('ativo.veiculo.seguro.editar', [$seguro->id, 'edit']) }}">
                                    <button class="btn btn-info  btn-sm" data-toggle="tooltip" data-placement="top" title="Editar"><i class="mdi mdi-pencil"></i></button>
                                </a>

                                 <form action="{{ route('ativo.veiculo.seguro.delete', $seguro->id) }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <a class="excluir-padrao" data-id="{{ $seguro->id }}" data-table="empresas" data-module="cadastro/empresa">
                                        <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" type="submit" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o registro?')">
                                            <i class="mdi mdi-delete"></i></button>
                                    </a>
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

@endsection



<!-- Modal -->

<div class="modal fade" id="anexarArquivoAtivoSeguro" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="anexarArquivoAtivoSeguroLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="anexarArquivoAtivoExternoLabel">Anexo de Seguro do Veiculo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body" id="lista_anexo">

            </div>

            <form action="{{ route('anexo.upload') }}" id="form" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="diretorio" value="seguro">
                <input type="hidden" name="id_modulo" value="30">
                <input type="hidden" name="id_veiculo"  id="id_veiculo" value="">
                <div class="modal-body">
                    <div class="mb-3 col-2">
                        <input type="hidden" class="form-control" id="id_item" name="id_item" readonly>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-6">
                            <label class="form-label">Nome do arquivo</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>

                        <div class="mb-3 col-6">
                            <label class="form-label">Data de validade do arquivo</label>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

    $(function() {

        $(document).on("click", ".seguro", function() {

            var id_ativo_externo = $(this).data('id');
            var dados =  $('#form').serialize();            

            $(".modal-body #id_item").val(id_ativo_externo);     
            $.ajax({
                    url: "{{ url('admin/ativo/veiculo/seguro/anexo') }}/" + id_ativo_externo,
                    method: 'GET',
                    data: dados
                })

                .done(function(result) {
                    $("#lista_anexo").html(result)
                })
                .fail(function(jqXHR, textStatus, result) {
                });
        });

    });

</script>
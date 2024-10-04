@extends('dashboard')
@section('title', 'Retirada - Devolução')
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Devolução de Itens
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
        <a href="{{ route('ferramental.retirada.adicionar') }}">
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
                        <form name="devolverItem" action="{{ route('ferramental.retirada.devolver.salvar') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">

                                <a class="btn btn-outline-warning btn-fw" type="button" href="{{ route('ferramental.retirada.detalhes', $detalhes->id) }}">
                                    RETIRADA <span class="mdi mdi-pound"></span>
                                </a>
                                <hr>
                                <table class="table-bordered table-striped table-houver table">
                                    <thead>
                                        <tr>
                                            <th> Obra </th>
                                            <th> Solicitante </th>
                                            <th> Funcionário </th>
                                            <th> Item </th>
                                            <th> Solicitado </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($detalhes->itens as $item)
                                        <tr>
                                            <td>{{ $detalhes->codigo_obra . ' - ' . $detalhes->razao_social }}</td>
                                            <td>{{ $detalhes->name }}</td>
                                            <td>{{ $detalhes->funcionario }}</td>
                                            <td>
                                                <div class="btn btn-danger btn-sm">{{ $item->item_codigo_patrimonio }}
                                                </div>
                                                <div class="btn btn-info btn-sm">{{ $item->item_nome }}</div>
                                            </td>
                                            <td>{{ Tratamento::FormatarData($detalhes->created_at) }}</td>

                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <hr>


                                <div class="mt-2">

                                    <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#assinar_termo_digital" data-bs-whatever="@getbootstrap">Devolver Itens</button>

                                    <a href="{{ route('ferramental.retirada') }}">
                                        <button class="btn btn-warning btn-md mx-3" type="button">Cancelar</button>
                                    </a>
                                </div>

                            </div>
                        </form>

                        @if($detalhes->status==3)
                        <div class="container-fluid">
                            <div class="card-header text-center">
                                RELATÓRIO DA DEVOLUÇÃO 
                                
                                <a href="{{ url('admin/ferramental/retirada/termo') }}/{{ $detalhes->id }}?devolver_itens=true">
                                    <button class="btn btn-success btn-sm font-weight-medium mx-3" type="button">VER TERMO</button>
                                </a>
                            </div>
                            {!! @$detalhes->devolucao_observacoes !!}
                        </div>
                        @else

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

<!-- Default Modals -->

<div id="assinar_termo_digital" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Assinatura Digital - DEVOLUÇÃO</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                
                <form id="form_assinatura_digital">
                    @csrf

                    <table class="table-bordered table-striped table-houver table" style="width:100%">
                        <thead>
                            <tr>
                                <th> Item </th>
                                <th> Situação </th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($detalhes->itens as $item)
                            
                            <tr>
                                <td style="width:80%">
                                    <div class="d-flex justify-content-start">
                                        <div class="bg-danger mx-2 p-1 text-white rounded">{{ $item->item_codigo_patrimonio }}
                                        </div>
                                        <div class="bg-primary p-1 text-white rounded">{{ $item->item_nome }}</div>
                                    </div>
                                </td>
                                <td>
                                    <select class="form-select-sm" id="id_ativo_externo" name="id_ativo_externo[{{ $item->id }}]" {{ $item->status == 2 || $item->status == 5 ? '' : 'disabled' }}>
                                        <option value="{{ $item->status }}">{{ Tratamento::getStatusRetirada($item->status)['titulo'] }}</option>
                                        <option value="3">Devolvido</option>
                                        <option value="4">Devolvido com Defeito</option>
                                        <option value="5">Não Devolvido</option>
                                    </select>
                                </td>

                            </tr>

                            @endforeach

                        </tbody>
                    </table>

                    <label for="recipient-name" class="col-form-label">Observações:</label>
                    <textarea name="description" id="description" cols="30" rows="10"></textarea>

                    <input type="hidden" id="devolver_itens" value="true">

                    <div class="mb-3">
                         <label for="recipient-name" class="col-form-label">Se não possui senha, solicite ao responsável pelo Almoxarifado</strong>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="digital" class="btn btn-success">Assinar</button>
                    </div>
                </form>
                
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script>
    $('#description').summernote({
        placeholder: 'DESCREVA A SITUAÇÃO DA FERRAMENTA (insira foto/ imagem...',
        height: 300
    });
</script>
@endsection
@extends('dashboard')
@section('title', 'Retirada de Ferramentas')
@section('content')

{{-- @dd($retiradas) --}}

<style>
    .relacionamento {
        pointer-events: none;
        background-color: #eee;
        opacity: 0.6;
        /* Outros estilos para indicar visualmente que a linha está desativada */
    }
</style>


<div class="row">
    <div class="col-2 breadcrumb-item active" aria-current="page">
        <h3 class="page-title text-center">
            <span class="page-title-icon bg-gradient-primary me-2">
                <i class="mdi mdi-office-building-cog mdi-24px"></i>
            </span> Ferramental
        </h3>
    </div>

    <div class="col-4 active m-2">
        <h5 class="page-title text-left m-0">
            <span>Retirada de Ferramentas <i class="mdi mdi-check icon-sm text-primary align-middle"></i></span>
        </h5>
    </div>

</div>

<hr>

<form action="{{ route('ferramental.retirada') }}" method="GET" class="mb-4">
    @csrf
    <div class="row my-4 align-middle">
        <div class="col-2">
            <h3 class="page-title text-left">
                <a href="{{ url('admin/ferramental/retirada/adicionar') }}">
                    <span class="btn btn-md btn-success shadow ">Nova Retirada</span>
                </a>
            </h3>
        </div>

        <div class="col-10">
            <div class="row justify-content-center">
                <div class="col-5 m-0 p-0 ">
                    <div class="row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">Fucnionários</label>
                        <div class="col-sm-10">
                            <select class="form-select form-control select2" name="solicitante" id="solicitante" value="{{ request()->solicitante }}">
                                <option value="" selected>Selecione um solicitante</option>
                                @foreach ($solicitantes as $solicitante)
                                <option value="{{ $solicitante->id }}">
                                    {{ $solicitante->nome}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-2 text-left  m-0 p-0 mb-2 mx-2">

                    <button type="submit" class="btn btn-primary btn-sm py-0 shadow" title="Pesquisar"><i class="mdi mdi-file-search-outline mdi-24px"></i></button>

                    <a href="{{ route('ferramental.retirada') }}" title="Limpar pesquisa">
                        <span class="btn btn-warning btn-sm py-0 shadow"><i class="mdi mdi-delete-outline mdi-24px"></i></span>
                    </a>
                </div>
                <div class="col-1 text-left m-0">

                </div>
            </div>
        </div>
    </div>
</form>

<div class="col-md-12 mb-2">
    <div class="card shadow ">
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle table-nowrap mb-0">
                <thead>
                    <tr>
                        <th class="text-center" width="5%">ID</th>
                        <th> Obra </th>
                        <th> Solicitante </th>
                        <th> Funcionário </th>
                        <th class="text-center"> Data de Inclusão </th>
                        <th class="text-center"> Devolução Prevista </th>
                        <th class="text-center"> Status</th>
                        <th class="text-center" width="10%">Ações</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($retiradas as $retirada)
                    @php
                    $relacionado = collect($retiradas)->firstWhere('id_relacionamento', $retirada->id);
                    @endphp
                    <tr class="{{ $relacionado ? 'relacionamento' : '' }}">
                        <td class="text-center" class="align-middle" style="width: 75px;">{{ $retirada->id }} </td>
                        <td class="align-middle">{{ $retirada->obra->codigo_obra?? 'Obra excluída' }}</td>
                        <td class="align-middle">{{ $retirada->usuario->name ?? null}}</td>
                        <td class="align-middle">{{ $retirada->funcionario->nome ?? null}}</td>
                        <td class="text-center">{{ Tratamento::datetimeBr($retirada->created_at) }}</td>
                        <td class="text-center">{{ Tratamento::datetimeBr($retirada->data_devolucao_prevista) }}</td>

                        <td class="text-center align-middle">
                            <span class=" btn btn-{{ $retirada->situacao->classe }} btn-sm">{{ $retirada->situacao->titulo }}</span>
                            <a href="javascript:void(0)">
                                <button class="btn btn-info btn-sm p-1 ItemsRetirada " id="" data-id_retirada="{{ $retirada->id }}" data-bs-toggle="modal" data-bs-target="#ItemsRetiradaModal">Ferramentas retiradas</button>
                            </a>

                            <a href="{{ route('ferramental.retirada.detalhes', $retirada->id) }}"> @if($retirada->id_relacionamento) <span class="btn btn-danger btn-sm"> Prazo #  {{$retirada->id_relacionamento}} @else  @endif</span></a>
                        </td>

                        <td class="text-center" width="10%">
                            <div class="dropdown">
                                <button class="btn btn-secondary btn-sm" id="dropdownMenuButton1" data-bs-toggle="dropdown" type="button" aria-expanded="false">
                                    Selecione <i class="mdi mdi-menu-down"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    
                                    @if ($retirada->status == '2' || ($retirada->status == '5' && $retirada->termo_responsabilidade_gerado))
                                        <li>
                                            <a class="dropdown-item" href="{{ route('ferramental.retirada.devolver', $retirada->id) }}"><i class="mdi mdi-redo-variant"></i> Devolver Itens</a>
                                        </li>
                                    @endif
                                    
                                    
                                    @if ($retirada->situacao->id == 2 && $retirada->termo_responsabilidade_gerado)
                                        <li>
                                            <a class="dropdown-item" href="{{ url('admin/ferramental/retirada/termo') }}/{{ $retirada->id }}?devolver_itens=false&funcionario={{ $retirada->funcionario->nome ?? "sem reg." }}">
                                                <i class="mdi mdi-download"></i> Baixar Termo</a>
                                        </li>
                                    @elseif ( $retirada->situacao->id  == 3 && $retirada->termo_responsabilidade_gerado)

                                    <li>
                                        <a class="dropdown-item" href="{{ url('admin/ferramental/retirada/termo') }}/{{ $retirada->id }}?devolver_itens=true&funcionario={{ $retirada->funcionario->nome ?? "sem reg." }}">
                                            <i class="mdi mdi-download"></i> Baixar Termo</a>
                                    </li>
                                            
                                    
                                    @endif
                                    
                                    @if ($retirada->id_relacionamento == null && $retirada->status < 3) 
                                        <li>
                                            <a class="dropdown-item" href="{{ route('ferramental.retirada.ampliar', $retirada->id) }}"><i class="mdi mdi-calendar-plus"></i> Ampliar prazo</a>
                                        </li>
                                    @endif


                                        @if ($retirada->status == '1' && !$retirada->termo_responsabilidade_gerado)
                                        <li><a class="dropdown-item" href="{{ route('ferramental.retirada.editar', $retirada->id) }}"><i class="mdi mdi-pencil"></i> Modificar Retirada</a></li>
                                        <li>
                                            <form action="{{ route('ferramental.retirada.destroy', $retirada->id) }}" method="POST">
                                                {{ csrf_field() }}
                                                <input name="_method" type="hidden" value="DELETE">
                                                <button class="dropdown-item" type="submit" onclick="return confirm('Deseja realmente cancelar a retirada?')">
                                                    <i class="mdi mdi-cancel"></i> Cancelar Retirada
                                                </button>
                                            </form>
                                        </li>
                                        @endif

                                        <li><a class="dropdown-item" href="{{ route('ferramental.retirada.detalhes', $retirada->id) }}"><i class="mdi mdi-minus"></i> Detalhes</a></li>

                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>

        <div class="card-footer clearfix">
            <div class=" mx-3">
                {{$retiradas->onEachSide(2)->links()}}
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="ItemsRetiradaModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="ItemsRetiradaLabel" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ItemsRetiradaLabel">Itens da Retirada </h5>
                <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                items
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Fechar</button>
            </div>
        </div>
    </div>
</div>

@endsection


@section('script')
<script>
    $(".ItemsRetirada").on('click', function() {
        
        var id_ferramentas_retiradas = $(this).attr('data-id_retirada');

        var url_ferramentas_retiradas = '{{ route("ferramental.retirada.items", ":id_ferramentas_retiradas") }}';
        url_ferramentas_retiradas = url_ferramentas_retiradas.replace(':id_ferramentas_retiradas', id_ferramentas_retiradas);
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token()}}'
            }
        });

        $.ajax({
            type: 'GET',
            url: url_ferramentas_retiradas,
            data: {id_ferramentas_retiradas: id_ferramentas_retiradas},
            success: function(result) {
                $(".modal-title").html('Itens Retirados # ' + id_ferramentas_retiradas)
                $(".modal-body").html(result)
            }
        });
    });
</script>
@endsection
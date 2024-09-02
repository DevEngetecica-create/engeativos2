@extends('dashboard')
@section('title', 'Transferências - Ativos')
@section('content')


<div class="card shadow-sm p-4">
    <div class="card-body">
        
    
    <div class="row">
        <div class="col-6 breadcrumb-item active" aria-current="page">
            <h3 class="page-title text-left">
                <span class="page-title-icon bg-gradient-primary me-2">
                        <i class="mdi mdi-arrow-left-right-bold-outline mdi-24px mdi-24px"></i>
                    </span> 
               Ferramental
               
            </h3>
        </div>

        <div class="col-4 active m-2">
            <h5 class="page-title text-left m-0">
                <span>Desmobilização de obra <i class="mdi mdi-check icon-sm text-primary align-middle"></i></span>
            </h5>
        </div>
    </div>

    <hr>
    @if (session()->get('usuario_vinculo')->id_nivel == 1 )
        <div class="page-header">
            <h3 class="page-title">
                <a class="btn btn-md btn-success" href="{{route('ativo.externo.transferencia.create')}}">
                    Adicionar
                </a>
            </h3>
        </div>
    @endif
        
        
        @if(session('mensagem'))
        <div class="alert alert-warning">
            {{ session('mensagem') }}
        </div>
        @endif
        
        
        {{--dd($modulos_permitidos[11]["submodulos"][2]["id"])--}}
        
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <table id="lista_ferramentas" class="table table-bordered table-hover table-sm align-middle table-nowrap mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">ID </th>
                            <th>Obra de Origem</th>
                            <th>Obra de Destino</th>
                            <th class="text-center">Usuário resp.</th>
                            <th class="text-center">Situação</th>

                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($transferenciaObras as $transferenciaObra)

                        <tr>
                            <td class="text-center">{{ $transferenciaObra->id }}</td>
                            <td>{{ $transferenciaObra->obraOrigem->nome_fantasia }}</td>
                            <td>{{ $transferenciaObra->obraDestino->nome_fantasia ?? "Sem reg." }}</td>
                            <td >{{ $transferenciaObra->usuario->email }}</td>
                            <td class="text-center"> <span class="bg-{{ $transferenciaObra->situacao->classe }} px-2 roundede text-white">{{ $transferenciaObra->situacao->titulo }}</span></td>

                            <td class="text-center"><a href="{{route('ativo.externo.transferencia.show', $transferenciaObra->id)}}" title="Visualizar Transferência"><span class="btn btn-info btn-sm"> <i class="mdi mdi-eye-outline"></i></span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="row mt-3" disabled="disabled" readonly="true">
                    <div class="d-flex justify-content-end col-sm-12 col-md-12 col-lg-12 " disabled="disabled" readonly="true">
                        <div class="paginacao" disabled="disabled" readonly="true">
                            {{$transferenciaObras->render()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.js"></script>

<script>



</script>

@endsection
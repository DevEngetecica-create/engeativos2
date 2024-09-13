
@extends('dashboard')
@section('title', 'Calibração')
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span>Calibração
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
        @foreach($ativoExternoEstoque as $id)
        <a href="{{ route('ativo.externo.calibracao.adicionar',$id->id) }}">
            <button class="btn btn-sm btn-danger">Cadastrar</button>
        </a>
        @endforeach
    </h3>
</div>

<div class="col-md-12">
    <div class="card card-primary">
     
        @foreach($dadosAtivoExterno as $dados)
        <div class="card">
            <div class="card-header">
                <h6 >Lista de Certificados do equipamento: <strong>{{$dados->ativo_externo->titulo}} | Patrimônio: {{$dados->patrimonio}}</strong></h6>
            </div>
        @endforeach
        
            <div class="card-body">
                <table id="tabelAanexo" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Empresa Calibradora</th>
                            <th>Dt. da Calibração</th>
                            <th>Dt Venc. da Calibração</th>
                            <th>Arquivo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        @if(count($anexo) >0)
                        @foreach($anexo as $item)
                        <tr>
                            <td>{{ $item->nome_empresa }}</td>
                            <td>{{Tratamento::dateBr( $item->data_calibracao ?? '-' ) }}</td>
                            <td>{{ Tratamento::dateBr($item->data_vencimento ?? '-') }}</td>
                            <td>{{$item->arquivo ?? '-'}}</td>
                           
                            <td>
                                 <a href="{{route('ativo.externo.editar.calibracao', $item->id)}}">
                                    <span class="btn btn-info btn-sm"><span class="mdi mdi-pencil mdi-24px"></span></span>
                                </a>
                                <a href="{{ route('anexo.download', $item->id) }}">
                                    <span class="btn btn-success btn-sm"><span class="mdi mdi-arrow-collapse-down mdi-24px"></span></span>
                                </a>
                                
                                <a href="{{ route('anexo.destroy', $item->id) }}" id="deletar">
                                    <span class="btn btn-danger btn-sm"><span class="mdi mdi-delete mdi-24px"></span></span>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="5">Nenhum registro encontrado.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
                    
            
    </div>

</div>


@endsection




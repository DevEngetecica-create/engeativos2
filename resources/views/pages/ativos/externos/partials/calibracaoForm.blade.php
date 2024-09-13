
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


<div class="col-md-8">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Edição do registro: {{$calibracao->id_item}}</h3>
        </div>
            
            
        <form action="{{route('ativo.externo.update.calibracao', $calibracao->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="diretorio" name="diretorio" value="ativo_externo">
            <input type="hidden" name="id_modulo" value="13">

            <div class="modal-body">
                <div class="mb-3 col-2">
                    <input type="text" class="form-control" id="id_item" name="id_item" value="{{$calibracao->id_item}}" required readonly>
                </div>
                <div class="row mb-3">
                    <div class="mb-3 col-6">
                        <label> Nº do certificado</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" value="{{$calibracao->titulo}}" required>
                    </div>
                    <div class="mb-3 col-6">
                        <label> Nome da Empresa Calibradora</label>
                        <input type="text" class="form-control" id="nome_empresa" name="nome_empresa" value="{{$calibracao->nome_empresa}}" required>
                    </div>
                    
                    <div class="mb-3 col-6">
                        <label> Data da Calibração</label>
                        <input type="date" class="form-control" id="data_calibracao" name="data_calibracao" value="{{$calibracao->data_calibracao}}" required>
                    </div>
                    
                    <div class="mb-3 col-6">
                        <label> Data de Venc. do Certificado</label>
                        <input type="date" class="form-control" id="data_vencimento" name="data_vencimento" value="{{$calibracao->data_vencimento}}" required>
                    </div>
                   
                </div>
                
                <div class="mb-3">
                  <label for="formFileSm" class="form-label">Carregar Arquivo</label>
                  <input class="form-control form-control-sm"  id="arquivo" name="arquivo" type="file" value="{{ $calibracao->arquivo ?? old('arquivo')}}">
                </div>

                <div class="mb-3">
                    <textarea class="form-control" id="detalhes" name="detalhes" placeholder="Detalhes">{{$calibracao->detalhes}}</textarea>
                </div>

            </div>
            <div class="modal-footer">
               
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-warning">Salvar Anexo</button>
            </div>
        </form>
    </div>

</div>


@endsection




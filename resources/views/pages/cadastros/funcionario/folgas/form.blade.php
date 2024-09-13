@extends('dashboard')
@section('title', 'Obras')
@section('content')

    <div class="page-header mt-5">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary me-2 text-white">
                <i class="mdi mdi-access-point-network menu-icon"></i>
            </span> Cadastro de Obras
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Cadastros <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

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

                    @php
                        $action = isset($editFolgaFuncionarios) ? route('cadastro.funcionario.folga.update', $editFolgaFuncionarios->id) : route('cadastro.funcionario.folga.store');
                    @endphp
                    <form method="post" enctype="multipart/form-data" action="{{ $action }}">
                        @csrf

                        <div class="row">
                            

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    
                                    <label class="form-label" for="obra">Obra</label>
                                    
                                    <select class="form-select form-control select2" id="id_obra" name="id_obra">
                                        @foreach ($obras as $obra)
                                        <option value="{{ $obra->id }}" {{ old('obra') == $obra->id ? 'selected' : '' }}>{{ $obra->codigo_obra }} | {{ $obra->razao_social }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                 
                            </div>
                            
                            <div class="row mb-3">
                                
                              <div class="col-md-5">
                                 <label class="form-label">Funcionário</label>
                                 
                                      <select class="form-select form-control select2" id="id_funcionario" name="id_funcionario">
                                            <option value="" selected >Selecione um Funcionário</option>
                                            @foreach($funcionarios as $funcionario)
                                                <option value="{{ $funcionario->id }}" {{ old('id_funcionario') == $funcionario->id ? 'selected' : '' }}>{{ $funcionario->nome }}</option>
                                            @endforeach
                                    </select>
                                    
                                </div>
                                
                                <div class="col-md-2">
                                        <label class="form-label" for="tipo">Data de Iníncio</label>
                                        <input class="form-control" id="data_inicio" name="data_inicio" type="date" value="{{ old('data_inicio', @$editFolgaFuncionarios->data_inicio) }}">
                                        
                                </div>
                                
                                <div class="col-md-2">
                                        <label class="form-label" for="tipo">Data Fim</label>
                                        <input class="form-control" id="data_fim" name="data_fim" type="date" value="{{ old('data_fim', @$editFolgaFuncionarios->data_fim) }}">
                                </div>
                                
                            </div>
                            
                      
                        
                        </div>

                        <div class="col-12 mt-5 m-3">
                            <button class="btn btn-primary btn-lg font-weight-medium" type="submit">Salvar</button>

                            <a href="{{ url('admin/cadastro/funcionario/folga') }}">
                                <button class="btn btn-warning btn-lg font-weight-medium" type="button">Cancelar</button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

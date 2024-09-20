@extends('layouts.master-without-nav')
@section('title')
@lang('translation.signin')
@endsection
@section('content')

<style>
    @keyframes blink {
    0% { opacity: 1; }
    50% { opacity: 0; }
    100% { opacity: 1; }
    }
    
    .loading-text {
        font-size: 11px;
        font-weight: bold;
    }
    
    .dot {
        animation: blink 1.5s infinite;
    }
    
    .dot:nth-child(1) {
        animation-delay: 0s;
        font-size: 20px;
    }
    
    .dot:nth-child(2) {
        animation-delay: 0.3s;
        font-size: 20px;
    }
    
    .dot:nth-child(3) {
        animation-delay: 0.6s;
        font-size: 20px;
    }
    
    .dot:nth-child(4) {
        animation-delay: 0.9s;
        font-size: 11px;
    }
    
    .dot:nth-child(5) {
        animation-delay: 1.2s;
        font-size:20px;
    }

</style>

<div class="container p-5">
    <div class="col-12 btn bg-warning bg-gradient waves-effect waves-light pt-4 mb-4 mb-lg-3 pb-lg-4">
        <div class="row g-4">
            <div class="col-auto m-auto mt-sm-3 mt-xl-5 mb-0">
                <img src="{{ URL::asset('build/images/icones/LogoMarca - Horizontal.svg') }}" alt="" height="50">
            </div>
            
            <div class="col-auto text-center">
                
                <div class="avatar-lg">
                    @if ($store->imagem_usuario)
                        <img src="{{ asset('build/images/users') }}/{{ $store->id }}/{{$store->imagem_usuario}}" class="img-thumbnail rounded-circle" />
                    @else
                        <img src="{{ asset('imagens/usuarios/lista-de-usuarios.png') }}"
                            class="img-thumbnail rounded-circle" />
                    @endif
                    
                </div>
            </div>
            
            

            {{-- dd("break") --}}
            <!--end col-->
            <div class="col">
                <div class="p-2">
                    <h3 class="text-white mb-1">{{ $store->nome }}</h3>
                    <p class="text-white text-opacity-75">{{ $store->funcao->funcao ?? 'Sem reg.' }}</p>
                    <div class="hstack text-white-50 gap-1">
                       
                    </div>
                </div>
            </div>
        

        </div>
        <!--end row-->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div>
                <!-- Tab panes -->
                <div class="tab-content pt-4 text-muted">
                    <div class="tab-pane active" id="overview-tab" role="tabpanel">
                        <div class="row">
                            <div class="col-sm-12 col-xl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Informações</h5>
                                        <div class="table-responsive">
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                    <tr>
                                                        <th class="ps-0" scope="row">Matrícula :</th>
                                                        <td class="text-muted">{{ $store->matricula }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-0" scope="row">Nome completo :</th>
                                                        <td class="text-muted">{{ $store->nome }}</td>
                                                    </tr>
                                                  
                                                    <tr>
                                                        <th class="ps-0" scope="row">Contato :</th>
                                                        <td class="text-muted">{{ $store->celular }}</td>
                                                    </tr>
                                                   
                                                    <tr>
                                                        <th class="ps-0" scope="row">Status</th>
                                                        <td class="text-muted">{{ $store->status }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div>
                            <!--end col-->
                            
                               <div class="col-sm-12 col-xl-9">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title m1-4">Qualificações</h5>
                                        <hr class="m-0 p-0 mb-5 text-warning">

                                        <div class="d-flex flex-wrap gap-2 fs-15">

                                            <div class="row row-cols-1 row-cols-md-3 g-4">
                                                @foreach ($qualificacao_funcoes as $qualificacao)
                                                    <div class="col">
                                                        <div class="card h-100">
                                                            <h5 class="mx-3">
                                                                {{ $qualificacao->qualificacoes->nome_qualificacao ?? 'Sem reg' }}
                                                            </h5>
                                                            <hr class="m-0 p-0">
                                                            <div class="card-body pb-0 ">
                                                                @php
                                                                    $situacao = $qualificacao->situacao_doc ?? $qualificacao->situacao
                                                                @endphp
                                                                    
                                                                 
                                                                @if($situacao == 1 )
                                                                
                                                                        <p class="mb-2">
                                                                            <small><strong>Situação: </strong> 
                                                                                <button type="button" class="btn btn-warning btn-sm btn-label waves-effect waves-light">
                                                                                    <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i>
                                                                                     Pendente
                                                                                </button>
                                                                            </small>
                                                                        </p>
                                                                
                                                                @elseif($situacao == 2)
                                                                
                                                                    <p class="mb-2">
                                                                        <small><strong>Situação: </strong> 
                                                                            <button type="button" class="btn btn-success btn-sm btn-label waves-effect waves-light">
                                                                                <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i>
                                                                                 Aprovado
                                                                            </button>
                                                                        </small>
                                                                    </p>
                                                                    
                                                                @elseif($situacao == 18)
                                                                
                                                                    <p class="mb-2">
                                                                        <small><strong>Situação: </strong> 
                                                                            <button type="button" class="btn btn-danger btn-sm btn-label waves-effect waves-light">
                                                                                <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i>
                                                                                 Reprovado
                                                                            </button>
                                                                        </small>
                                                                    </p>
                                                                
                                                                    
                                                                @endif
                                                                
                                                              {{--dd($qualificacao)--}}
                                                                
                                                               @if(isset($qualificacao->qualificacoes) && $qualificacao->qualificacoes->publica == 1 && $situacao == 2)
                                                                <p class="mb-0">
                                                                    <small><strong>Download: </strong> 
                                                                        <a href="{{ route('download.documento.publico', $qualificacao->id_anexos ?? 0) }}">
                                                                            <button type="button"
                                                                                class="btn btn-outline-success btn-sm waves-effect waves-light material-shadow-none">
                                                                                <i class="mdi mdi-cloud-download"></i>
                                                                            </button>
                                                                        </a>
                                                                    </small>
                                                                </p>
                                                                
                                                                @else
                                                                
                                                                 <p class="mb-0">
                                                                        <small class=text-danger><strong>Download: </strong> 
                                                                            
                                                                                 Não permitido
                                                                         
                                                                        </small>
                                                                    </p>
                                                                @endif
                                                                
                                                            </div>
                                                            <div class="card-footer">
                                                                <small class="text-muted">Data da validade:
                                                                
                                                                    @if($qualificacao->data_aprovacao)
                                                                        {{ Tratamento::dateBr($qualificacao->data_validade_doc) }}
                                                                    @else
                                                                        Sem registro
                                                                    @endif    
                                                                </small>
                                                            </div>
                                                        </div>

                                                    </div>
                                                @endforeach
                                            </div><!-- end card body -->
                                        </div><!-- end card -->
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </div>

                        </div>
                        <!--end row-->
                    </div>

                 
                </div>
                <!--end tab-content-->
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->

</div>

@endsection

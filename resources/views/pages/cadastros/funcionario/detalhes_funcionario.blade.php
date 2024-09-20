@extends('layouts.master-without-nav')
@section('title')
@lang('translation.signin')
@endsection
@section('content')


<style>
    @import url('https://fonts.googleapis.com/css2?family=Barlow:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

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
    
    .nowrap {
        white-space: nowrap; /* Impede quebra de linha */
    }

    /* Estilos para o header e footer com imagens fluidas */
    header, footer {
        width: 100%;
        height: 150px; 
        background-size: cover; 
        background-position: center;
        background-repeat: no-repeat;
        z-index: 999;
    }

    /* Ajustes para dispositivos móveis */
    @media (max-width: 768px) {
        header, footer {
            height: 120px; 
            background-size: cover; 
        }
    }

    header {
        background-image: url('{{ asset("build/images/usuarios/header.png") }}'); /* Caminho da imagem do header */
        position: fixed;
    }

    footer {
        background-image: url('{{ asset("build/images/usuarios/footer.png") }}'); /* Caminho da imagem do footer */
    }

    /* Limita a largura máxima do container e centraliza */
    .custom-container {
        max-width: 500px; /* Define a largura máxima no desktop */
        width: 100%;
        margin: 0 auto; /* Centraliza o container no desktop */
        padding: 0 15px; /* Adiciona um pouco de padding nas laterais */
    }
    /* Alinhar os itens a esquerda */
    .table th, .table td {
        text-align: left; /* Alinha os itens a esquerda */
    }

    .blockquote.custom-blockquote h3, 
    .blockquote.custom-blockquote h5 {
    font-family:"Barlow", sans-serif; 
    }

    .avatar-lg{
        margin-top: 100px;
        margin-bottom: 10px;
    }


</style>
<!-- Header -->
<header></header>

<!-- Troca de container-fluid para custom container -->
<div class="custom-container d-flex flex-column align-items-center justify-content-center content" style="min-height: 100vh;">
    <!-- Imagem centralizada -->
    <div class="text-center mb-4" style="width: 100%;">
        <div class="d-flex justify-content-center">
            <div class="avatar-lg">
                @if ($store->imagem)
                    <img src="{{ asset('imagens/usuarios') }}/{{ $store->id }}" class="img-thumbnail rounded-circle" />
                @else
                    <img src="{{ asset('imagens/usuarios/lista-de-usuarios.png') }}" class="img-thumbnail rounded-circle" />
                @endif
            </div>
        </div>
    </div>

    <!-- Bloco de citação e informações abaixo da imagem -->
    <div class="col-lg-12 text-center">
        <!-- Blockquote com nome e função -->
        <blockquote class="blockquote custom-blockquote blockquote-outline blockquote-primary rounded mb-4 material-shadow">
            <h3 class="text-body mb-2"><strong>{{ $store->nome }}</strong></h3>
            <h5 class="text-body mb-2"><strong>{{ $store->funcao->funcao ?? 'Sem reg.' }}</strong></h5>
        </blockquote>
        

        <!-- Box de informações original -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Informações</h5>
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="ps-0 nowrap" scope="row">Matrícula :</th>
                                <td class="text-muted">{{ $store->matricula }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0 nowrap" scope="row">Nome completo :</th>
                                <td class="text-muted">{{ $store->nome }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0 nowrap" scope="row">Contato :</th>
                                <td class="text-muted">{{ $store->celular }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0 nowrap" scope="row">Status :</th>
                                <td class="text-muted">{{ $store->status }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
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
        </div><!-- end card -->
    </div><!-- end col-lg-12 -->
</div><!-- end custom container -->

<!-- Footer -->
<footer></footer>

@endsection
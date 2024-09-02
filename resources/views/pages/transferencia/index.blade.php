@extends('dashboard')
@section('title', 'Transferências')
@section('content')



<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Transferências
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Gestão de Transferências de Banco de Dados <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

<style>
    * {
        text-decoration: none !important;
    }

    .weight-500 {
        font-weight: 500;
    }

    .height-100-p {
        height: 100%;
    }

    .bg-white {
        background: #ffffff;
    }

    .border-radius-10 {
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
    }

    .text-dark {
        color: #000000;
    }

    .font-30 {
        font-size: 30px;
        line-height: 1.46em;
    }

    a:hover {
        text-decoration: none !important;
    }

    .font-20 {
        font-size: 20px;
        line-height: 1.5em;
    }

    .box-shadow {
        -webkit-box-shadow: 0px 0px 28px rgba(0, 0, 0, .08);
        -moz-box-shadow: 0px 0px 28px rgba(0, 0, 0, .08);
        box-shadow: 0px 0px 28px rgba(0, 0, 0, .08);
    }

    .widget-style3 {
        padding: 30px 20px;
    }


    .widget-style3 .widget-data {
        width: calc(100% - 60px);
    }

    .widget-style3 .widget-icon {
        width: 60px;
        font-size: 45px;
        line-height: 1;
    }

    .gradient-style1 {
        background-image: linear-gradient(90deg, rgba(36, 30, 152, 1) 0%, rgba(75, 75, 121, 1) 35%, rgba(0, 212, 255, 1) 100%);
    }

    .gradient-style2 {
        background-image: linear-gradient(0deg, rgba(34, 193, 195, 1) 0%, rgba(253, 187, 45, 1) 100%);
    }

    .gradient-style3 {
        background-image: radial-gradient(circle, rgba(63, 94, 251, 1) 0%, rgba(252, 70, 107, 1) 100%);
    }

    .gradient-style4 {
        background-image: linear-gradient(90deg, rgba(131, 58, 180, 1) 0%, rgba(253, 29, 29, 1) 50%, rgba(252, 176, 69, 1) 100%);
    }

    .gradient-style5 {
        background-image: linear-gradient(90deg, rgba(45, 6, 70, 1) 0%, rgba(173, 23, 23, 1) 63%, rgba(252, 176, 69, 1) 100%);
    }

    .gradient-style6 {
        background-image: radial-gradient(circle, rgba(128, 20, 67, 1) 0%, rgba(5, 31, 68, 1) 100%);
    }

    .gradient-style7 {
        background-image: radial-gradient(circle, rgba(11, 34, 152, 1) 0%, rgba(29, 31, 29, 1) 70%, rgba(252, 70, 107, 1) 100%);
    }

    .gradient-style8 {
        background-color: #333;
    }
</style>

<div class="row mb-5">

    <div class="col-xl-3 mb-50 mb-2">
        <a href="{{ route('transferencia.todas') }}">
            <div class="gradient-style8 box-shadow border-radius-10 height-100-p widget-style3 text-white">
                <div class="d-flex align-items-center flex-wrap">
                    <div class="widget-data">
                        <div class="weight-400 font-20">Todas</div>
                        <span>Executar todas as transferências</span>
                    </div>
                    <div class="widget-icon">
                        <div class="icon"><i class="mdi mdi-watch" aria-hidden="true"></i></div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-3 mb-50 mb-2">
        <a href="{{ route('transferencia.empresa') }}">
            <div class="gradient-style7 box-shadow border-radius-10 height-100-p widget-style3 text-white">
                <div class="d-flex align-items-center flex-wrap">
                    <div class="widget-data">
                        <div class="weight-400 font-20">Empresas</div>
                        <span>Transferência de Empresas cadastradas</span>
                    </div>
                    <div class="widget-icon">
                        <div class="icon"><i class="mdi mdi-wrench" aria-hidden="true"></i></div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-3 mb-50 mb-2">
        <a href="{{ route('transferencia.obra') }}">
            <div class="gradient-style4 box-shadow border-radius-10 height-100-p widget-style3 text-white">
                <div class="d-flex align-items-center flex-wrap">
                    <div class="widget-data">
                        <div class="weight-400 font-20">Obras</div>
                        <span>Transferência de Obras</span>
                    </div>
                    <div class="widget-icon">
                        <div class="icon"><i class="mdi mdi-city" aria-hidden="true"></i></div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-3 mb-50 mb-2">
        <a href="{{ route('transferencia.funcionario') }}">
            <div class="gradient-style4 box-shadow border-radius-10 height-100-p widget-style3 text-white">
                <div class="d-flex align-items-center flex-wrap">
                    <div class="widget-data">
                        <div class="weight-400 font-20">Funcionários</div>
                        <span>Transferência de Funcionários Ativos e Inativos</span>
                    </div>
                    <div class="widget-icon">
                        <div class="icon"><i class="mdi mdi-account-group" aria-hidden="true"></i></div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row mb-5">
    <div class="col-xl-3 mb-50 mb-2">
        <a href="{{ route('transferencia.ativo_configuracao') }}">
            <div class="gradient-style3 box-shadow border-radius-10 height-100-p widget-style3 text-white">
                <div class="d-flex align-items-center flex-wrap">
                    <div class="widget-data">
                        <div class="weight-400 font-20">Configurações Ativos</div>
                        <span>Transferêcia de Categorias e Subcategorias dos Ativos</span>
                    </div>
                    <div class="widget-icon">
                        <div class="icon"><i class="mdi mdi-cash" aria-hidden="true"></i></div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 mb-50 mb-2">
        <a href="{{ route('transferencia.ativo') }}">
            <div class="gradient-style1 box-shadow border-radius-10 height-100-p widget-style3 text-white">
                <div class="d-flex align-items-center flex-wrap">
                    <div class="widget-data">
                        <div class="weight-400 font-20">Ferramental Externo</div>
                        <span>Transferência de Ferramentas Externas</span>
                    </div>
                    <div class="widget-icon">
                        <div class="icon"><i class="mdi mdi-wrench" aria-hidden="true"></i></div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-3 mb-50 mb-2">
        <a href="{{ route('transferencia.veiculo') }}">
            <div class="gradient-style5 box-shadow border-radius-10 height-100-p widget-style3 text-white">
                <div class="d-flex align-items-center flex-wrap">
                    <div class="widget-data">
                        <div class="weight-400 font-20">Veículos</div>
                        <span>Transferência de Veículos e seus respectivos subitens</span>
                    </div>
                    <div class="widget-icon">
                        <div class="icon"><i class="mdi mdi-car" aria-hidden="true"></i></div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-3 mb-50 mb-2">
        <a href="{{ route('transferencia.fornecedor') }}">
            <div class="gradient-style6 box-shadow border-radius-10 height-100-p widget-style3 text-white">
                <div class="d-flex align-items-center flex-wrap">
                    <div class="widget-data">
                        <div class="weight-400 font-20">Fornecedores</div>
                        <span>Transferência de Fornecedores Internos / Externos</span>
                    </div>
                    <div class="widget-icon">
                        <div class="icon"><i class="mdi mdi-account" aria-hidden="true"></i></div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>




@endsection
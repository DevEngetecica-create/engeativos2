@extends('dashboard')
@section('title', 'Dashboard')
@section('content')


    @component('components.breadcrumb')
        @slot('li_1') Dashboards @endslot
        @slot('title') SGA-Engeativos
    @endcomponent
    @section('css')
    <link href="{{ URL::asset('build/libs/leaflet/leaflet.css') }}" rel="stylesheet" type="text/css" />
<style>

    .animated-progress {
        width: 100%;
        height: 25px;
        border-radius: 2px;
        overflow: hidden;
        position: relative;
    }

    .animated-progress span {
        font-size: 0.7rem;
        display: block;
        width: 0;
        line-height: 20px;
        color: #ffffff;
        position: absolute;
        text-align: end;
        padding-right: 2px;

    }

    .progress-blue span {
        background-color: #0d6efd;
    }


    .progress .progress-bar {
        border-radius: 0rem !important;
    }

    @keyframes piscar {
        0% {
            background-color: red;
        }

        50% {
            background-color: yellow;
        }

        100% {
            background-color: red;
        }
    }

    .div-piscando {
        height: 30px;
        width: 30px;
        background-color: red;
        animation: piscar 1s infinite;
        /* Aplica a animação infinitamente */
    }
    
    .alerta-circle {
        height: 18px;
        width: 18px;
        border-radius:50%;
        background-color: red;
        animation: piscar 1s infinite;
        
    }
</style>
@endsection
    <div class="row">
        <div class="col-xl-12">
            <div class="card crm-widget">
                <div class="card-body p-0">
                    <div class="row row-cols-xxl-5 row-cols-md-3 row-cols-1 g-0">
                        <div class="col">
                            <div class="py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">Empresas<i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i></h5>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="mdi mdi-home-city  display-6 text-muted cfs-22"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h2 class="mb-0 cfs-22"><span class="counter-value" data-target="{{ Estatistica::empresas() }}">0</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->
                        <div class="col">
                            <div class="mt-3 mt-md-0 py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">Obras <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i></h5>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="mdi mdi-hard-hat display-6 text-muted cfs-22"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h2 class="mb-0 cfs-22"><span class="counter-value" data-target="{{ Estatistica::obras() }}">0</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->
                        <div class="col">
                            <div class="mt-3 mt-md-0 py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">Funcionários <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i></h5>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="mdi mdi-account-group display-6 text-muted cfs-22"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h2 class="mb-0 cfs-22"><span class="counter-value" data-target="{{ Estatistica::funcionarios(session()->get('obra')->id ?? session()->get('obra')['id']) }}">0</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->
                        <div class="col">
                            <div class="mt-3 mt-lg-0 py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">Fornecedores <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i></h5>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="mdi mdi-dolly display-6 text-muted cfs-22"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h2 class="mb-0 cfs-22"><span class="counter-value" data-target="{{ Estatistica::fornecedores() }}">0</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->
                        
                        <div class="col">
                            <div class="mt-3 mt-lg-0 py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">Veículos <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i></h5>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="mdi mdi-truck-check display-6 text-muted cfs-22"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h2 class="mb-0 cfs-22"><span class="counter-value" data-target="{{ Charts::totalModelo()[0]->totalModelos }}">0</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->
                        
                    </div><!-- end row -->
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Obras Engetecnica</h4>
                </div><!-- end card header -->
    
                <div class="card-body">
                    <div id="map" style="width: 100%; height: 700px;"></div>
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div>
        <!-- end col -->
    </div>

    <div class="row">
        <div class="col-sm-12 col-xl-6">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Manutenções dos Veiculos</h4>
                    <div class="flex-shrink-0">
                        <div class="dropdown card-header-dropdown">
                            <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="text-muted">2024<i class="mdi mdi-chevron-down ms-1"></i></span>
                            </a>
                            
                        </div>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                   <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead>
                                <tr class="text-muted">
                                    <th scope="col" style="width: 35%;">Placa</th>
                                    <th scope="col" class="text-center d-none d-xxl-table-cell" style="width: 15%;">Próx. rev.</th>
                                    <th scope="col" class="text-center" style="width: 10%;">Hrs Rest</th>
                                    <th scope="col" class="text-center d-none d-xxl-table-cell" style="width: 25%;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (Tarefa::horimetro(session()->get('obra')->id ?? session()->get('obra')['id']) as $horas)
                                <tr>
                                    <td>
                                        <small>{{ $horas->placa ? $horas->placa : $horas->codigo_da_maquina }}</small>
                                    </td>
                                    <td class="text-center d-none d-xxl-table-cell">
                                        <small>{{ $horas->proxRev }} horas</small>
                                    </td>
                                    <td class="text-center">
                                        <small>{{ $horas->horasRest > 0 ? $horas->horasRest : 0 }}</small>
                                    </td>
                                    <td class="text-center d-none d-xxl-table-cell">
                                        <div class="progress animated-progress" style="height: 25px;">
                                            <div class="progress-bar bg-success" style="width: {{ round($horas->valorCalc, 1) }}%;" aria-valuenow="{{ round($horas->valorCalc > 0 ? $horas->valorCalc : 0, 1) }}" aria-valuemin="0" aria-valuemax="100">{{ round($horas->valorCalc > 0 ? $horas->valorCalc : 0, 1) }}%</div>
                                            <div class="progress-bar bg-warning" style="width: {{ 100 - round($horas->valorCalc > 0 ? $horas->valorCalc : 0, 1) }}%;" aria-valuenow="{{ 100 - round($horas->valorCalc > 0 ? $horas->valorCalc : 0, 1) }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-sm-12 col-xl-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Manutenção dos veículos/ PERÍODO</h4>
                    <div class="flex-shrink-0">
                        <div class="dropdown card-header-dropdown">
                            <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                
                            </a>
                            
                        </div>
                    </div>
                </div><!-- end card header -->

                <div class="card-body p-0">

                    <div class="align-items-center p-3 justify-content-between d-flex">
                        <div class="d-flex flex-row">
                            <div class="p-2"><i class="mdi mdi-square text-success"></i> No prazo</div>
                            <div class="p-2"><i class="mdi mdi-square text-warning"></i> Ateção no prazo</i></div>
                            <div class="p-2"><i class="mdi mdi-square text-danger"></i> Atenção!!! resta menos que 20 dias para a próxima revisão</i></div>
                        </div>
                    </div><!-- end card header -->
                    
                     <div class="card-body">
                        
                       <div class="table-responsive">
                            <table class="table table-bordered table-nowrap align-middle mb-0">
                                <thead>
                                    <tr class="text-muted">
                                        <th scope="col" class="text-center d-none d-xxl-table-cell">ID</th>
                                        <th scope="col">Placa</th>
                                        <th scope="col" class="text-center d-none d-xxl-table-cell">Próx. rev.</th>
                                        <th scope="col">Hrs Rest</th>
                                        <th scope="col" class="d-none d-xxl-table-cell" width='50%'>Status da manutenção</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (Tarefa::dataVencimentaManutencao(session()->get('obra')->id ?? session()->get('obra')['id']) as $dias)
                                    <tr> <!-- alerta de manutenção vencida-->
                                        <td class="text-center d-none d-xxl-table-cell"><small>{{ $dias->idVeiculoManutencao }}</small></td>
                                        <td class="text-center">
                                            <span>{{ $dias->placa ? $dias->placa : $dias->codigo_da_maquina }}</span>
                                        </td>
                                        <td class="text-center d-none d-xxl-table-cell"><small>{{ Tratamento::dateBr($dias->data_de_vencimento) }}</small></td>
                                        <td class="text-center">
                                            {{ $dias->diasRestantes > 0 ? $dias->diasRestantes : 0 }}
                                            <a href="{{ url('admin/ativo/veiculo/manutencao/list/' . $dias->idVerManutencao) }}" class="btn btn-sm btn-success">
                                                <i class="mdi mdi-eye-outline"></i>
                                            </a>
                                        </td>
                                       
                                        
                                        <td class="text-center d-none d-xxl-table-cell">
                                            <div class="progress" style="height: 25px;">
                                                <div class="progress-bar bg-success" style="width: {{ round($dias->porDias > 0 ? $dias->porDias : 0, 1) }}%;" aria-valuenow="{{ round($dias->porDias > 0 ? $dias->porDias : 0, 1) }}" aria-valuemin="0" aria-valuemax="100">
                                                    {{ round($dias->porDias > 0 ? $dias->porDias : 0, 1) }}%
                                                </div>
                                                <div class="progress-bar bg-warning" style="width: {{ 100 - round($dias->porDias > 0 ? $dias->porDias : 0, 1) }}%;" aria-valuenow="{{ 100 - round($dias->porDias > 0 ? $dias->porDias : 0, 1) }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        </div>
                       </div>
                    </div><!-- end card body -->
              
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    <div class="row">
        <div class="col-sm-12 col-xl-6">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1"> Controle de Vencimento dos Seguros</h4>
                    <div class="flex-shrink-0">
                        <div class="dropdown card-header-dropdown">
                            <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="text-muted fs-18"><i class="mdi mdi-dots-vertical"></i></span>
                            </a>
                            
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-responsive table-valign-middle">
                            <thead>
                                <tr>
                                    <th class="text-center">Placa</th>
                                    <th class="text-center">Dt Vencimento</th>
                                    <th class="text-center d-none d-xxl-table-cell">Dias Rest</th>
                                    <th class="text-center d-none d-xxl-table-cell" scope="col" width='30%'>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vencimentoSeguros as $vencimentoSeguro)
                                <tr>
                                    <td>
                                        <small>{{ $vencimentoSeguro->placa ? $vencimentoSeguro->placa : $vencimentoSeguro->codigo_da_maquina }}</small>
                                    </td>
                                    <td class="text-center {{ $vencimentoSeguro->porcDiasSeguro <= 20 ? 'div-piscando' : '' }}">
                                        <small>{{ Tratamento::dateBr($vencimentoSeguro->carencia_final) }}</small>
                                    </td>
                                    <td class="text-center d-none d-xxl-table-cell">
                                        <small>{{ $vencimentoSeguro->diasRestantesSeguro }}</small>
                                    </td>
                                    <td class="text-center d-none d-xxl-table-cell">
                                        <div class="progress" style="height: 25px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ round($vencimentoSeguro->porcDiasSeguro > 0 ? $vencimentoSeguro->porcDiasSeguro : 0, 1) }}%;" aria-valuenow="{{ round($vencimentoSeguro->porcDiasSeguro > 0 ? $vencimentoSeguro->porcDiasSeguro : 0, 1) }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ round($vencimentoSeguro->porcDiasSeguro > 0 ? $vencimentoSeguro->porcDiasSeguro : 0, 1) }}%
                                            </div>
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ 100 - round($vencimentoSeguro->porcDiasSeguro > 0 ? $vencimentoSeguro->porcDiasSeguro : 0, 1) }}%;" aria-valuenow="{{ 100 - round($vencimentoSeguro->porcDiasSeguro > 0 ? $vencimentoSeguro->porcDiasSeguro : 0, 1) }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-sm-12 col-xl-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1"> Controle de Vencimento dos IPVA's</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-nowrap align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">ID IPVA</th>
                                    <th class="text-center">Placa</th>
                                    <th class="text-center">Dt Vencimento</th>
                                    <th class="text-center d-none d-xxl-table-cell">Dias Rest </th>
                                    <th  class="text-center d-none d-xxl-table-cell" scope="col" width='30%'>Status </th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vencimentoIPVAs as $vencimentoIPVA)
                                <tr>

                                    <td class="text-center">
                                        {{$vencimentoIPVA->idIPVA}}
                                    </td>

                                    <td class="text-center">
                                        {{$vencimentoIPVA->placa ? $vencimentoIPVA->placa : $vencimentoIPVA->codigo_da_maquina}}
                                    </td>

                                    <td class="text-center">
                                        {{Tratamento::dateBr($vencimentoIPVA->data_de_vencimento)}}
                                    </td>

                                    <td class="text-center d-none d-xxl-table-cell">
                                        {{$vencimentoIPVA-> diasRestantesIpva}}
                                    </td>

                                    <td  class="text-center d-none d-xxl-table-cell" class=" {{($vencimentoIPVA->porcDiasIpva <= 20 )? 'div-piscando': ''}}"> <!-- alerta de manutenção vencida-->

                                        <!--Staus de verificação do seguro-->
                                        <div class="d-flex flex-row d-none d-xxl-table-cell">
                                            <div class="p-2 col-12">
                                                <div class="animated-progress progress-blue">
                                                    <span data-progress="{{($vencimentoIPVA->porcDiasIpva > 0)? $vencimentoIPVA->porcDiasIpva: 0}}" style="position:absolute; z-index:2"></span>
                                                </div>
                                            </div>
                                            <div class="p-2 col-12">
                                                <div class="progress" style="position:relative; z-index:1; right:105%; top: 0.1rem; border-radius: 0rem !important;">
                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 10%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="border-radius: 0px;"></div>
                                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 30%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                                                    <div class="progress-bar bg-success " role="progressbar" style="width: 60%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>

                                        </div>

                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table><!-- end table -->
                    </div><!-- end table responsive -->
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
        
        
    </div><!-- end row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1"> Calibração dos equipamentos</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-nowrap align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:10%">Obra</th>
                                    <th class="text-center" style="width:4%">Nº Patr.</th>
                                    <th class="text-center" style="width:5%">Vencim.</th>
                                    <th class="text-center d-none d-xxl-table-cell" style="width:5%">Dias Rest.</th>
                                    <th class="text-center d-none d-xxl-table-cell" style="width:30%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(Charts::vencimentoCalibracao() as $dataVencimento)
    <tr>
        <td><small>{{ $dataVencimento->nome_fantasia }}</small></td>
        <td><small>{{ $dataVencimento->patrimonio }}</small></td>
        <td class="{{ ($dataVencimento->diasRestantesCalibracao <= 20 ) ? 'div-piscando' : '' }} text-center">
            <small>{{ Tratamento::dateBr($dataVencimento->max_data_vencimento) }}</small>
        </td>
        <td class="text-center d-none d-xxl-table-cell">
            <small>{{ ($dataVencimento->diasRestantesCalibracao < 0 ) ? 'Venceu há '. abs($dataVencimento->diasRestantesCalibracao) . ' dia(s)' : $dataVencimento->diasRestantesCalibracao }}</small>
        </td>
        <td class="text-center d-none d-xxl-table-cell">
            <div class="progress" style="height: 20px;">
                <div class="progress-bar bg-success" role="progressbar" 
                     style="width: {{ round($dataVencimento->porcDiasCalibracao > 0 ? $dataVencimento->porcDiasCalibracao : 0, 1) }}%;" 
                     aria-valuenow="{{ round($dataVencimento->porcDiasCalibracao > 0 ? $dataVencimento->porcDiasCalibracao : 0, 1) }}" 
                     aria-valuemin="0" aria-valuemax="100">
                    {{ round($dataVencimento->porcDiasCalibracao > 0 ? $dataVencimento->porcDiasCalibracao : 0, 1) }}%
                </div>
                <div class="progress-bar bg-warning" role="progressbar" 
                     style="width: {{ 100 - round($dataVencimento->porcDiasCalibracao > 0 ? $dataVencimento->porcDiasCalibracao : 0, 1) }}%;" 
                     aria-valuenow="{{ 100 - round($dataVencimento->porcDiasCalibracao > 0 ? $dataVencimento->porcDiasCalibracao : 0, 1) }}" 
                     aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
        </td>
    </tr>
@endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 col-xl-6">
            <div class="card mb-3">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Relatório Mensal</h3>
                    </div>
                </div>
                <div class="card-body">

                    <div class="position-relative mb-4">
                        <div class="chartjs-size-monitor">
                            <div class="chartjs-size-monitor-expand">
                                <div class=""></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink">
                                <div class=""></div>
                            </div>
                        </div>
                        <canvas id="bar-chart-grouped" height="400" style="display: block; width: 764px; height: 200px;" width="764" class="chartjs-render-monitor"></canvas>
                    </div>

                </div>
            </div>

        </div>

        <div class="col-sm-12 col-xl-6">
            <div class="card mb-3">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Ferramentas por Obra</h3>
                    </div>
                </div>
                <div class="card-body">

                    <div class="position-relative mb-4">
                        <div class="chartjs-size-monitor">
                            <div class="chartjs-size-monitor-expand">
                                <div class=""></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink">
                                <div class=""></div>
                            </div>
                        </div>
                        <canvas id="ferramentasObras" height="400" style="display: block; width: 764px; height: 200px;" width="764" class="chartjs-render-monitor"></canvas>
                    </div>

                </div>
            </div>
        </div>
    </div>


        <div class="row">
            <div class="col-sm-12 col-xl-6">
                <div class="card mb-3">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">Ferramentas Calibradas</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="position-relative mb-4">
                            <div class="chartjs-size-monitor">
                                <div class="chartjs-size-monitor-expand">
                                    <div class=""></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink">
                                    <div class=""></div>
                                </div>
                            </div>
                            <canvas id="chartCalibracao" height="400" style="display: block; width: 764px; height: 400px;" width="764" class="chartjs-render-monitor"></canvas>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-xl-6">
                <div class="card mb-3">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">Ferramentas Calibradas por Obra</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="position-relative mb-4">
                            <canvas id="ferramentasCalibradasObras" height="400" style="display: block; width: 764px; height: 400px;" width="764" class="chartjs-render-monitor"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>



    @foreach ((array)$ativosExterno as $ativosExternos)
    <input type="hidden" name="ativosExternos" id="ativosExternos" value="{{$ativosExternos}}">
    @endforeach
    
    @foreach ($calibracaoAtivosExternos as $calibracaoAtivosExterno)
    <input type="hidden" name="calibracao" id="calibracao" value="[{{$calibracaoAtivosExterno}}]">
    @endforeach
    
    @foreach((array) $qtdeAtivosObra as $qtdeObras)
    <input type="hidden" name="qtdeObras" id="qtdeObras" value="{{$qtdeAtivosObra}}">
    @endforeach
    
      <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
      
    <script src="{{ URL::asset('build/libs/leaflet/leaflet.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/leaflet-us-states.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/leaflet-map.init.js') }}"></script>
  
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.4.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
    $(document).ready(function() {


        /// início mapa LEAFTLET

 var map = L.map('map').setView([-14.235, -51.925], 4.3);

        // Adicionar um mapa base
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);




        // Definir as coordenadas e nomes dos marcadores
        var markers = [{
                name: "REPLAN",
                coords: [-22.7340972, -47.1129444]
            },
            {
                name: "TAUBATÉ",
                coords: [-23.0619528, -45.587525]
            },
            {
                name: "Engetecnica",
                coords: [-25.469111203265395, -49.34961572699073]
            },
            {
                name: "SE MACAPÁ I",
                coords: [0.0983028, -51.1218417]
            },
            {
                name: "JUAZEIRO III",
                coords: [-9.4850917, -40.5200694]
            },
            {
                name: "CURITIBA LESTE",
                coords: [-25.5727111, -49.0792389]
            },
            {
                name: "BOM JESUS DA LAPA",
                coords: [-13.3117972, -43.3429194]
            },
            {
                name: "LEC (em Pinhais) - Depósito",
                coords: [-25.4373694, -49.1963417]
            },
            {
                name: "ENGETECNICA - SERVICE (em Pinhais)",
                coords: [-25.4370722, -49.1966806]
            },
            {
                name: "CERRO CHATO (DESMOBILIZADA)",
                coords: [-30.8143944, -55.7057611]
            },
            {
                name: "SCATEC (DESMOBILIZADA)",
                coords: [-5.6181972, -37.0381861]
            }
        ];

        // Função para criar um ícone personalizado com tamanho específico, mantendo o estilo original do Leaflet
        function createCustomIcon(size) {
            return L.icon({
                iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
                iconSize: [15, 25], // Tamanho personalizado do ícone
                iconAnchor: [size / 2, size], // Ponto de ancoragem no meio inferior do ícone
                popupAnchor: [0, -size] // Deslocamento do pop-up em relação ao ícone
            });
        }

        // Defina o tamanho desejado para os marcadores
        var markerSize = 20; // Defina o tamanho dos marcadores

        // Adicione cada marcador ao mapa usando o ícone personalizado com tamanho específico
        markers.forEach(marker => {
            L.marker(marker.coords, {
                icon: createCustomIcon(markerSize)
            }).addTo(map).bindPopup(marker.name);
        });

        // Desabilitar todos os controles de zoom
        map.zoomControl.remove();

        // Desativar a capacidade de zoom através do scroll do mouse
        map.scrollWheelZoom.disable();

        // Também é recomendável desabilitar o zoom de duplo clique, se desejado
        map.doubleClickZoom.disable();

        /* // Adicione cada marcador ao mapa
        markers.forEach(marker => {
            L.marker(marker.coords).addTo(map).bindPopup(marker.name);
        }); */




        //fim do mapa LEAFTLET

    })
    
    $(document).ready(function() {
        carregaHorimetro();
    });

    function carregaHorimetro() {


        $(".animated-progress div").each(function() {
            $(this).animate({
                    width: $(this).attr("aria-valuenow") + "%",
                },
                1000
            );
            $(this).text(Math.round($(this).attr("aria-valuenow")) + "%");

        });

        $(".diasRestantes span").each(function() {
            $(this).animate({
                    width: $(this).attr("data-progress") + "%",
                },
                1000
            );
            $(this).text(Math.round($(this).attr("data-progress")) + "%");

        });
    }

    $(document).ready(function() {

        var jsonCalibracao = [$('#calibracao').val()];

        const arrayCalibracao = JSON.parse(jsonCalibracao);

        const totalCalibrados = arrayCalibracao.map(row => row.quantidade_calibrados);
        const totalAtivos = arrayCalibracao.map(row => row.quantidade_total);


        var totalGeral = (parseInt(totalCalibrados, 10) + parseInt(totalAtivos, 10))
        console.log(totalGeral);

        var dataCalibracao = {
            datasets: [{
                data: [totalCalibrados, totalAtivos],
                backgroundColor: [
                    '#ff7707',
                    '#0d6efd',


                ],
                borderColor: [
                    '#fff',
                    '#fff'
                ],
            }],

            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: [
                'Ferramentas calibradas',
                'Total de Ferramentas',

            ]
        };
        var doughnutPieOptionsCalibracao = {
            responsive: true,
            animation: {
                animateScale: true,
                animateRotate: true
            },
            plugins: {
                datalabels: {

                    display: true,
                    color: '#333', // Cor do texto
                    anchor: 'top',
                    align: 'top',

                }
            },

            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                            return previousValue + currentValue;
                        });
                        var currentValue = dataset.data[tooltipItem.index];
                        var percentage = ((currentValue / totalGeral) * 100).toFixed(2); // Calcula a porcentagem

                        return data.labels[tooltipItem.index] + ": " + percentage + "%";
                    }
                }
            }
        };


         if ($("#chartCalibracao").length) {
            var chartCalibracaoCanvas = $("#chartCalibracao").get(0).getContext("2d");
             var doughnutChartCalibracao = new Chart(chartCalibracaoCanvas, {
                 type: 'doughnut',
                 data: dataCalibracao,
                options: doughnutPieOptionsCalibracao
            });
        }
    })
    
        //inicio Grafico de Ativos Externos

    var jsonAtivosExternos = $('#ativosExternos').val()
    const arrayAtivosExternos = JSON.parse(jsonAtivosExternos);


    //console.log(datasFormatadas); // A matriz agora incluirá o novo elemento no final


    var data = {
        labels: arrayAtivosExternos.map(row => row.mes),
        datasets: [{
            label: 'Qtde de Ativos',
            borderColor: '#6781d7',
            backgroundColor: '#6781d7',
            data: arrayAtivosExternos.map(row => row.quantidade_acumulada_criados)
        },
        {
            label: 'Qtde de Perda de Ativos',
            borderColor: '#f44336',
            backgroundColor: '#f44336',
            data: arrayAtivosExternos.map(row => row.quantidade_anterior)
        }
        ]

    };

    var options = {
        plugins: {
            datalabels: {
                value: '1',
                display: true,
                color: '#333', // Cor do texto
                anchor: 'top',
                align: 'top',

            }
        },
        scales: {
            y: {
                max: 4000, // Define o valor máximo do eixo Y
                beginAtZero: true, // Isso garante que o eixo comece em zero
            }
        },

    };

    // Get context with jQuery - using jQuery's .get() method.
    if ($("#bar-chart-grouped").length) {
        var barChartCanvas = $("#bar-chart-grouped").get(0).getContext("2d");
        // This will get the first returned node in the jQuery collection.
        var barChartd = new Chart(barChartCanvas, {
            type: 'bar',
            data: data,
            options: options
        });
    }
    //Fim Grafico de Ativos Externos




    // Início gráfico quanatidade de ferramentas por obra

    var jsonQtdeFerramentoaObra = $('#qtdeObras').val()
    const arrayQtdeFerramentoaObra = JSON.parse(jsonQtdeFerramentoaObra);

    const nomeFantasia = arrayQtdeFerramentoaObra.map(row => row.nome_fantasia)
    const qtdeAtivosObras = arrayQtdeFerramentoaObra.map(row => row.qtdeAtivosObras)

    console.log(nomeFantasia)

    var dataAtivosObra = {
        labels: nomeFantasia,
        datasets: [{
            label: 'Qtde de Ativos por Obra',
            borderColor: '#ff7707',
            backgroundColor: '#ff7707',
            data: qtdeAtivosObras
        },
        ]

    };

    var optionsAtivosObra = {
        plugins: {
            datalabels: {
                value: '1',
                display: true,
                color: '#333', // Cor do texto
                anchor: 'top',
                align: 'top',

            }
        },
        scales: {
            y: {
                title: {
                    display: true,
                    text: 'Qtde'
                },
                min: 0,
                max: 6000,

            },
            x: {
                title: {
                    display: true,
                    text: 'Mês/ ano'
                },
                grid: {
                    display: false, // Oculta as grades do eixo X
                },
            }
        },
    };

    // Get context with jQuery - using jQuery's .get() method.
    if ($("#ferramentasObras").length) {
        var barAtivosObra = $("#ferramentasObras").get(0).getContext("2d");
        // This will get the first returned node in the jQuery collection.
        var barChart = new Chart(barAtivosObra, {
            type: 'bar',
            data: dataAtivosObra,
            options: optionsAtivosObra
        });
    }

    // FIM gráfico quanatidade de ferramentas por obra


    // Início gráfico quanatidade de ferramentas por obra

    var jsonQtdeFerramentasCaliObra = $('#qtdeObras').val()
    const arrayQtdeFerramentasCaliObra = JSON.parse(jsonQtdeFerramentasCaliObra);

    const nomeFantasiaCalibrados = arrayQtdeFerramentasCaliObra.map(row => row.nome_fantasia)
    const qtdeAtivosObrasCalibrados = arrayQtdeFerramentasCaliObra.map(row => row.qtdeAtivosObras)
    const qtdeCalibradosObrasCalibrados = arrayQtdeFerramentasCaliObra.map(row => row.qtdeCalibrados)



    var dataFerramentasCaliObra = {
        labels: nomeFantasiaCalibrados,
        datasets: [
            {
                label: 'Qtde de Ativos por Obra',
                borderColor: '#0d6efd',
                backgroundColor: '#0d6efd',
                data: qtdeAtivosObrasCalibrados
            },
            {
                label: 'Equip. Calibrados',
                borderColor: '#ff7707',
                backgroundColor: '#ff7707',
                data: qtdeCalibradosObrasCalibrados
            }
        ]

    };

    var optionsFerramentasCaliObra = {
        plugins: {
            datalabels: {
                value: '1',
                display: true,
                color: '#333', // Cor do texto
                anchor: 'top',
                align: 'top',
                value: qtdeAtivosObras,
                formatter: function (value, context) {
                    return value; // Exibir o valor do dado diretamente na barra
                }
            }
        },
       
	            
        scales: {
            yAxes: [{
	                    ticks: {
	                        beginAtZero: true,
	                        max: 1500, // Valor limite do eixo Y
	                       
	                    }
	                }],
	                
            y: {
                title: {
                    display: true,
                    text: 'Qtde'
                },
                min: 0,

            },
            x: {
                title: {
                    display: true,
                    text: 'Mês/ ano'
                },
                grid: {
                    display: false, // Oculta as grades do eixo X
                },
            }
        },
    };

    // Get context with jQuery - using jQuery's .get() method.
    if ($("#ferramentasCalibradasObras").length) {
        var barFerramentasCaliObra = $("#ferramentasCalibradasObras").get(0).getContext("2d");
        // This will get the first returned node in the jQuery collection.
        var barFerramentasCaliObra = new Chart(barFerramentasCaliObra, {
            type: 'bar',
            data: dataFerramentasCaliObra,
            options: optionsFerramentasCaliObra
        });
    }

    // FIM gráfico quanatidade de ferramentas calibradas por obra
</script>

@endsection
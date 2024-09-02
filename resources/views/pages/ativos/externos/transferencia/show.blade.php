@extends('dashboard')
@section('title', 'Transferências - Detalhes')
@section('content')

<div class="row justify-content-center col-sm-12 col-lg-4 col-xl-12 mb-2 ">
    <div class="col-5">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary me-2">
                <i class="mdi mdi-transmission-tower-export mdi-36px"></i>
            </span> Desmobilização de obra - Detalhes<i class="mdi mdi-check icon-sm text-primary align-middle"></i>
        </h3>
    </div>
</div>

<hr>

<div class="card">
    <div class="d-flex align-items-center card-header  ">
        <h4 class="card-title mb-0 flex-grow-1">Lista de Ferramentas</h4>
        <a href="{{route('ativo.externo.transferencia.pdf', @$show_transferencia->id)}}">
            <button class="btn btn-warning btn-sm">Gerar Romaneio <i class="mdi mdi-file-pdf-box mdi-24px"></i></button>
        </a>
    </div><!-- end card header -->

    <div class="card-body">
        <div class="live-preview">
            <div class="table-responsive">
                <table class="table table-bordered align-middle table-nowrap mb-0">
                    <thead>
                        <thead>
                            <tr>
                                <th>Patrimônio</th>
                                <th style="max-width:200px !important">Item</th>
                                <th>Situação</th>
                            </tr>
                        </thead>
                    </thead>
                    <tbody>

                        @foreach($itens_transferidos as $itens)
                        <tr>
                            <td> {{$itens->patrimonio}}</td>
                            <td> {{$itens->ativo_externo->id}} - {{$itens->ativo_externo->titulo}}</td>
                            <td> {{$itens->situacao->titulo}}</td>
                            <td>
                                <div class="dropdown">
                                    <a href="#" role="button" id="dropdownMenuLink4" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ri-more-2-fill"></i>
                                    </a>

                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink4">
                                        <li><a class="dropdown-item" href="#">View</a></li>
                                        <li><a class="dropdown-item" href="#">Edit</a></li>
                                        <li><a class="dropdown-item" href="#">Delete</a></li>

                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- end card-body -->
</div>





@endsection
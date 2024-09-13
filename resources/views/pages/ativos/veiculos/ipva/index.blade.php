@extends('dashboard')

@section('title', 'Veículo')

@section('content')



<div class="page-header">

    <h3 class="page-title">

        <span class="page-title-icon bg-gradient-primary me-2 text-white">

            <i class="mdi mdi-access-point-network menu-icon"></i>

        </span>

        @if ($veiculo->tipo == 'maquinas')

        IPVA da Máquina

        @else

        IPVA do Veículo

        @endif

    </h3>

    <nav aria-label="breadcrumb">

        <ul class="breadcrumb">

            <li class="breadcrumb-item active" aria-current="page">

                Ativos <i class="mdi mdi-check icon-sm text-primary align-middle"></i>

            </li>

        </ul>

    </nav>

</div>



<div class="page-header">

    <h3 class="page-title">

        <a class="btn btn-sm btn-danger" href="{{ route('ativo.veiculo.ipva.adicionar', $veiculo->id) }}">

            Adicionar

        </a>

    </h3>

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

                @include('pages.ativos.veiculos.partials.header')

                <table class="table-hover table-striped table responsive">

                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">Ano</th>
                            <th whdth="20%" class="text-center">Custo</th>
                            <th class="text-center">Pagamento</th>
                            <th class="text-center">Vencimento</th>
                            <th class="text-center">Nome Doc.</th>
                            <th class="text-center ">Ações</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($ipvas as $ipva)

                        <tr>
                            <td class="text-center"><span class="badge badge-dark">{{ $ipva->id }}</span></td>
                            <td class="text-center">{{ $ipva->referencia_ano }}</td>
                            <td class="text-center">R$ {{ Tratamento::currencyFormatBr($ipva->valor) }}</td>  
                            <td class="text-center">{{ Tratamento::dateBr($ipva->data_de_pagamento) }}</td>
                            <td class="text-center">{{ Tratamento::dateBr($ipva->data_de_vencimento) }}</td>
                            <td class="text-center">{{ ($ipva->nome_anexo_ipva) }}</td>

                            <td class="d-flex justify-content-center">
                                <a href="{{ route('ativo.veiculo.ipva.download', $ipva->id) }}">
                                    <span class="btn btn-success btn-sm"><i class="mdi mdi-download mdi-14px" ></i></span>
                                </a>

                                <a href="{{ route('ativo.veiculo.ipva.editar', $ipva->id) }}">
                                    <span class="btn btn-info btn-sm m-2" data-toggle="tooltip" data-placement="center" title="Editar"><i class="mdi mdi-pencil mdi-14px"></i></span>
                                </a>

                                <form class="m-0" action="{{ route('ativo.veiculo.ipva.delete', $ipva->id) }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <a class="excluir-padrao" data-id="{{ $ipva->id }}" data-table="empresas" data-module="cadastro/empresa">
                                        <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" type="submit" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o registro?')">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </a>
                                </form> 
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
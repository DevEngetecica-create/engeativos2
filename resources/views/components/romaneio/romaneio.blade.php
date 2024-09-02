<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Romaneio</title>
</head>

<style>
    * {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 11px;
        border-collapse: collapse;
        text-align: justify;
    }

    body {
        padding-top: 180px !important;
        padding-bottom: 40px !important;
        padding-left: 75px !important;
        padding-right: 75px !important;
        background-image: url('assets/images/background-romaneio.jpeg');
        background-size: cover;
        background-repeat: no-repeat;
        z-index: -1000;
    }

    @page {
        margin: 0 !important;
    }

    table {
        width: 100% !important;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #ddd;
    }

    th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #C02028;
        color: white;
    }

    td,
    th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    /* .container {
        margin-top: 300px !important;
        margin-left: 75px !important;
        margin-right: 75px !important;
    } */
</style>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body text-justify">

                        <h4 class="card-title">ROMANEIO REQUISIÇÃO #{{ $requisicao->id }}</h4>
                        <h4 class="card-title">DADOS</h4>
                        <table class="table-bordered table-houver table">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID Requisição</th>
                                    <th>Solicitação</th>
                                    <th>Liberação</th>
                                    <th>Transferência</th>
                                    <th>Tipo</th>
                                    <th>Situação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $requisicao->id }}</td>
                                    <td>{{ Tratamento::datetimeBr($requisicao->created_at) }}</td>
                                    <td>{{ Tratamento::datetimeBr($requisicao->data_liberacao) }}</td>
                                    <td>{{ Tratamento::datetimeBr($requisicao->updated_at) }}</td>
                                    <td>Requisição</td>
                                    <td>{{ $requisicao->situacao->titulo }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <br><br>
                        
                        <div class="divider"></div>
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 20%">Liberado por</th>
                                    <th>Obra Origem</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>{{ $requisicao->despachante->name }}</strong><br>
                                        {{ $requisicao->despachante->telefone }}<br>
                                        {{ $requisicao->despachante->email }}
                                    </td>
                                    <td>
                                        @php
                                        $obra_origem = $ativos_liberados->first();
                                        @endphp
                                        <strong>{{ $obra_origem->obraOrigem->id ?? '-' }} {{ $obra_origem->obraOrigem->codigo_obra }} || {{ $obra_origem->obraOrigem->razao_social }}</strong><br>
                                       
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <br><br>


                        <table class="table-bordered table-striped table-houver table">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 20%">Solicitado por</th>
                                    <th style="width: 20%">Liberado por</th>
                                    <th>Obra destino</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>{{ $requisicao->solicitante->name ?? null}}</strong><br>
                                        {{ $requisicao->solicitante->telefone ?? null}}<br>
                                        {{ $requisicao->solicitante->email ?? null}}
                                    </td>
                                    <td>
                                        <strong>{{ $requisicao->despachante->name ?? null}}</strong><br>
                                        {{ $requisicao->despachante->telefone ?? null}}<br>
                                        {{ $requisicao->despachante->email ?? null}}
                                    </td>
                                    <td>
                                        <strong>{{ $requisicao->obraDestino->codigo_obra }} - {{ $requisicao->obraDestino->razao_social }}</strong><br>
                                        {{ $requisicao->obraDestino->endereco }}, {{ $requisicao->obraDestino->numero }}<br>
                                        {{ $requisicao->obraDestino->bairro }} | {{ $requisicao->obraDestino->cidade }} | {{ $requisicao->obraDestino->estado }}<br>
                                        <strong>Contato:</strong>
                                        {{ $requisicao->obraDestino->celular }} | {{ $requisicao->obraDestino->email }}<br>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <br><br>
                        <h4 class="card-title">ITENS E ATIVOS</h4>
                       
                        <table class="table-bordered table-striped table-houver table">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID Item</th>
                                    <th>Descrição</th>
                                    <th>Qtde. Solicitada</th>
                                    <th>Qtde. Liberada</th>
                                </tr>
                            </thead>
                            <tbody>
                                 @foreach ($ativos as $ativo)
                                    <tr class="page-break">
                                        <td><strong>{{ $ativo->id }}</strong></td>
                                        <td><strong>{{ $ativo->ativo_externo->titulo ?? null}}</strong></td>
                                        <td><strong>{{ $ativo->quantidade_solicitada ?? null}}</strong></td>
                                        <td><strong>{{ $ativo->quantidade_liberada ?? null}}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        <table class="table-bordered table-striped table-houver table">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width:10%;">ID Ativo</th>
                                    <th style="width:40%;">Categoria</th>
                                    <th style="width:10%;">Código</th>
                                    <th style="width:40%;">Nome</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ativos_liberados as $liberado)
                                <tr class="page-break">
                                    <td><strong>{{ $liberado->id }}</strong></td>
                                    <td><strong>{{ $liberado->ativo->ativo_externo->categoria->titulo ?? null}}</strong></td>
                                    <td><strong>{{ $liberado->ativo->patrimonio ?? null}}</strong></td>
                                    <td><strong>{{ $liberado->ativo->ativo_externo->titulo ?? null}}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        

                        <div style="margin-top: 100px;"></div>
                        <h4 class="card-title" style="text-align: center ">Responsável pelo Recebimento</h4>
                        <div style="margin-top: 100px;"></div>
                        <h4 class="card-title" style="text-align: center ">_______________________________________________________________________ Data: _____/_____/__________</h4>
                        <div style="margin-top: 100px;"></div>
                        <p><b>Este romaneio foi gerado através da Plataforma SGA-E</b></p>
                        <p>{{ date('d/m/Y H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
</body>

</html>
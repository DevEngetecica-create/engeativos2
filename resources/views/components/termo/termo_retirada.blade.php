<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Termo de Retirada</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            border-collapse: collapse;
            text-align: justify;
        }

        @page {
            size: A4;
            margin: 2cm;
        }
        

        body {
            //background-image: url('assets/images/background-termo.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            z-index: -1000;
            margin: 0;
            padding: 0;
            position: relative;
            min-height: 100vh;
            box-sizing: border-box;
        }
        
        #header {
           
            z-index: 9999;
            width: 100%;
            display: block;
            height: 60px;
            text-align: center;
            color: black;
        }

        #header table {
            text-align: center;
        }

        .titulo {
            margin: 0;
            font-size: 14px;
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
            align-items:center;
        }

        .subtitulo {
            margin-top: 100px;
            font-size: 14px;
            font-family: Arial, Helvetica, sans-serif;
            text-align: center
        }

        .image_cabecalho {
            text-align: center;

        }

        .image_cabecalho img {
            margin-bottom: 20px;
        }
        
        .container {
            margin: 0px;
             margin-top: 20px !important;
        }
        

        table {
            width: 100%;
        }

        td,
        th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: '#0d6efd';
        }

        tr:hover {
            background-color: #ddd;
        }

        th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #ff7707;
            color: white;
        }

        
        #qrcodeEntregarItens {
            width: 100px;
            height: auto;
        }

        #qrcodeDevolverItens {
            width: 100px;
            height: auto;
        }

        div.gallery {
            float: left;
            width: 380px;
            align-items: left;
            margin: auto;
            margin-right: 7px;
            text-align: left;
        }

        div.gallery:hover {
            border: 1px solid #777;
        }

        div.gallery img {
            width: 100%;
            height: auto;
        }

        div.desc {
            padding: 3px;
            text-align: left;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 50px;
            text-align: center;
            line-height: 50px;
            border-top: 1px solid #ddd;
        }
        
        .center-text {
            text-align: center;
        }
    </style>
</head>

<body>
    
    <div id="header">
        <table style="width:100%">
            <tr>
                <td class="center-text" rowspan="2">
                    <img src="{{ public_path('build/images/icones/logo_engetecnica.svg') }}" width="150">
                </td>
                <td class="center-text">
                    <span class="titulo">TERMO DE RETIRADA DE FERRAMENTAS</span>
                </td>
            </tr>
            <tr>
                <td class="center-text">
                    <span class="subtitulo">Identificação da Retirada #{{ $detalhes->id }}</span>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="container">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body text-justify">

                        <h4 class="card-title">DADOS DO FUNCIONÁRIO</h4>
                        <table class="table table-bordered table-striped table-houver">
                            <thead class="table-dark">
                                <tr>
                                    <th>Requisitante</th>
                                    <th>Matrícula</th>
                                    <th>Obra Atual</th>
                                    <th>Usuário Autorizado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $detalhes->funcionario }}</td>
                                    <td>{{ $detalhes->funcionario_matricula }}</td>
                                    <td>{{ $detalhes->codigo_obra }}</td>
                                    <td>{{ $detalhes->name }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <hr>
                        <h4 class="card-title">TERMO DE RETIRADA</h4>
                        <p class="card-description"></p>
                        <p>
                            PROPRIETÁRIO: ENGETECNICA ENGENHARIA E CONSTRUÇÃO LTDA, inscrita no CNPJ: 76.624.584/0001-38,
                            Rua do Semeador, 431, CEP 81270-050, Cidade Industrial, Município de Curitiba,
                            Estado do Paraná, doravante denominada COMODANTE.
                        </p>

                        <p>
                            USUÁRIO: Contratado pela Engetecnica Engenharia e Construção Ltda, doravante denominada COMODATÁRIO.
                        </p>

                        <hr>
                        <h4 class="card-title">CLÁUSULA PRIMEIRA – DAS DECLARAÇÕES</h4>

                        <p>1.1 Declaro ter recebido da COMODANTE, à título de empréstimo, para uso em minhas funções operacionais,
                            conforme determinado em lei, as ferramentas e equipamentos especificados neste termo de responsabilidade. </p>

                        <p>1.2 O COMODATÁRIO compromete-se a zelar, cuidar, manter em ordem e perfeito funcionamento, todas as
                            ferramentas e equipamentos disponibilizados para uso pela COMODANTE, durante todo período
                            da execução de suas atividades. </p>

                        <hr>
                        <h4 class="card-title">CLÁUSULA SEGUNDA – DAS OBRIGAÇÕES E RESPONSABILIDADES</h4>

                        <p>O COMODATÁRIO será responsabilizado em caso de danificar, extraviar, emprego inadequado, e/ou mau uso.
                            A COMODANTE fornecerá um novo equipamento ao COMODATÁRIO e cobrará o valor de acordo com o custo do
                            equipamento da mesma marca e modelo ou equivalente, disponível no mercado. </p>

                        <p>2.2 Em caso de dano e inutilização do equipamento por parte do COMODATÁRIO o mesmo, deverá comunicar
                            por escrito a COMODANTE apresentando o equipamento danificado no prazo máximo de 24 horas para
                            equipe de ativos da COMODANTE </p>

                        <p>2.3 Em caso de furto ou roubo, o COMODATÁRIO deverá: (I) comunicar imediatamente a COMODANTE e,
                            (II) apresentar o boletim de ocorrência, no qual informe detalhadamente os fatos e as
                            circunstâncias do ocorrido. </p>

                        <p>2.4 Uma vez em posse do COMODATÁRIO ferramentas e equipamentos, a COMODANTE poderá a qualquer
                            momento e sem prévio aviso, realizar as inspeções e conferências de todos os itens
                            disponibilizados ao COMODATÁRIO. </p>

                        <hr>
                        <h4 class="card-title">CLÁUSULA TERCEIRA – DOS ITENS DISPONIBILIZADOS</h4>

                        <p>3.1 Todos os itens da lista abaixo foram conferidos pela COMODANTE e COMODATARIO, tendo sido
                            testado(s) e recebido(s) pelo COMODATÁRIO sem qualquer defeito e em pleno funcionamento,
                            atendendo a todos os requisitos de segurança aplicáveis aos mesmos. Ítens Retirados </p>
                        <hr>

                        <div class="page-break"></div>
                        <div style="margin-top: 90px;"></div>

                        <h4 class="card-title">Dados da retirada</h4>
                        <table class="table table-bordered table-striped table-houver">
                            <thead>
                                <tr>
                                    <th> Obra </th>
                                    <th> Entregue por? </th>
                                    <th> Requisitante </th>
                                    <th> Data de Inclusão </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $shown = false;
                                @endphp

                                @foreach ($detalhes->itens as $item)
                                    @if (!$shown)
                                        <tr>
                                            <td>{{ $detalhes->codigo_obra }}</td>
                                            <td>{{ $detalhes->name }}</td>
                                            <td>{{ $detalhes->funcionario }}</td>
                                            <td>{{ Tratamento::FormatarData($detalhes->created_at) }}</td>
                                        </tr>
                                        @php
                                            $shown = true;
                                        @endphp
                                    @endif
                                @endforeach

                            </tbody>
                        </table>

                        <br>

                        <table class="table table-bordered table-striped table-houver">
                            <thead>
                                <tr>
                                    <th> Patrimônio </th>
                                    <th> Descrição da ferramenta </th>
                                    <th> Valor </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detalhes->itens as $item)
                                    <tr>
                                        <td>{{ $item->item_codigo_patrimonio }}</td>
                                        <td>{{ $item->item_nome }}</td>
                                        <td>R$ {{ Tratamento::currencyFormatBr($item->valor) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <p>Data prevista para devolução:
                            <b>{{ Tratamento::FormatarData($detalhes->data_devolucao_prevista) }}</b>
                        </p>

                        <hr>

                        <h4 class="card-title">Assinaturas</h4>

                        <table class="table table-bordered">
                            <tr>
                                <td width="50%"><b>COMODANTE</b></td>
                                <td width="50%"><b>COMODATÁRIO</b></td>
                            </tr>
                            <tr>
                                <td>ENGETECNICA ENGENHARIA E CONSTRUCAO LTDA</td>
                                <td><b>Nome:</b> {{ $detalhes->funcionario }}</td>
                            </tr>
                            <tr>
                                <td><b>Representante:</b> {{ $detalhes->name }}</td>
                                <td><b>Matrícula:</b> {{ $detalhes->funcionario_matricula }}</td>
                            </tr>
                        </table>

                        <div style="margin-top: 10px;"></div>

                        <p><b>Este termo foi assinado e gerado através da Plataforma SGA-E</b></p>
                        <p>{{ date('d/m/Y H:i:s') }}</p>

                        <h4 class="card-title">Assinaturas Digital do Funcionários</h4>

                        <div class="gallery">
                            <div class="desc">ENTREGA</div>
                            <img class="card-img-top" id="qrcodeEntregarItens" src="{{ ($qrcodeEntregarItens) }}" alt="QR Code">

                            <div class="desc">Nome: {{ $detalhes->funcionario }}</div>
                            <div class="desc">Matricula: {{ $detalhes->funcionario_matricula }}</div>
                            <div class="desc"><strong></b>Entregue às: </strong>{{ Tratamento::datetimeBr($detalhes->created_at) }}</div>
                        </div>

                        @if($qrcodeDevolverItens)

                            <div class="gallery">
                                <div class="desc">DEVOLUÇÃO</div>
                                <img id="qrcodeDevolverItens" src="{{ ($qrcodeDevolverItens) }}" alt="QR Code">
                                <div class="desc">Nome: {{ $detalhes->funcionario }}</div>
                                <div class="desc">Matricula: {{ $detalhes->funcionario_matricula }}</div>
                                <div class="desc"><strong>Devolvido às: </strong>{{ Tratamento::datetimeBr($detalhes->data_devolucao)}}</div>
                            </div>

                        @else

                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

   
</body>

</html>

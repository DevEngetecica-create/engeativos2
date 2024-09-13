<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Romaneio</title>

    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            border-collapse: collapse;
            text-align: justify;
        }

        body {
            background-size: cover;
            background-repeat: no-repeat;
            z-index: -1000;
            margin: 0 !important;
            padding: 0;
            margin-top: 200px;
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
            font-size: 16px;
            font-family: Arial, Helvetica, sans-serif;
            text-align: center
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

        .tabela_transferencia{
            margin-top: 30px;
        }

        .container {
            margin-top: 20px !important;
            width: 100%;
        }

        body.conteudo {
            margin-top: 150px;
        }

        @page {
            mso-title-page: yes;
        }

        .text-center{
            text-align: center
        }

        table {
            width: 100% !important;
            top: 50px;
        }

        td,
        th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #ff7707;
            color: white;
        }

        #footer {
            border-top: black 0.5px solid;
            position: fixed;
            width: 100%;
            background: #ff7707;
            z-index: 9999;
            max-width: 1140px;
            position: fixed;
            display: block;
            bottom: 0px;
        }

        .pagenum:before {
            content: counter(page);
        }

        footer .pagenum:before {
            content: counter(page);
        }
    </style>
</head>

<body>


    <div id="header">

        <table style="width:100%">
            <tr>
                <td rowspan="2">
                    <img src="{{ public_path('build/images/icones/LogoMarcaHorizontal.png') }}" height="40">

                </td>

                <td class="text-center"> <span class="titulo">ROMANEIO DE TRANSFERêNCIA DE FERRAMENTAS {{-- $requisicao->id --}}</h>
                </td>
            </tr>
            <tr>
                <td class="text-center"> <span class="subtitulo">LISTA DE MATERIAIS</span></td>
            </tr>
        </table>
    </div>

    <div class="tabela_transferencia">
        <table>
            <thead>
                <tr>
                    <th>Da obra: {{$romaneio_itens_transferidos->first()->destino_nome_fantasia ?? "Obra 1"}}</th>
                    <th>Para obra: {{$romaneio_itens_transferidos->first()->origem_nome_fantasia ?? "Obra 2"}}</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="container">
        <div>
            <div>
                <table>
                    <thead>
                        <tr>
                            <th class="text-center">ID trans.</th>
                            <th>Patrimonio</th>
                            <th>Ferramenta</th>
                            <th>Situação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($romaneio_itens_transferidos as $itens_transferidos)
                        <tr>
                            <td class="text-center"><strong>{{ $itens_transferidos->id_trasferencia }}</strong></td>
                            <td><strong>{{ $itens_transferidos->patrimonio }}</strong></td>
                            <td><strong>{{ $itens_transferidos->titulo }}</strong></td>
                            <td><strong>{{ $itens_transferidos->status_ativo ?? "Sem reg."}}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
            </div>
        </div>
    </div>

    <div id="footer" class="margin-top">

        <div class="pagenum-container">Página <span class="pagenum"></span></div>
        <p><b>Este romaneio foi gerado através da Plataforma SGA-Engeativos</b></p>
        <p>{{ date('d/m/Y H:i:s') }}</p>

        </footer>
</body>

</html>
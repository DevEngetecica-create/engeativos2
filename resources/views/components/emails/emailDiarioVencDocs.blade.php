<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Notificação Vencimento de Documento</title>

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f1f0f0;
            display: flex;
            justify-content: center;
        }

        .column {
            width: 100%;
            padding: 0 20px;
        }

        .row {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        @media screen and (max-width: 600px) {
            .column {
                width: 100%;
                display: block;
                margin-bottom: 20px;
            }
        }

        .card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            padding: 16px;
            text-align: center;
            background-color: #ffffff;
            border-radius: 7px;
            margin: 0 auto;
            width: 100%;
            height: auto;
        }

        .card h3 {
            padding-top: 3px;
            padding-bottom: 3px;
            margin: 0px !important;
        }

        .card h4 {
            padding-top: 10px;
            padding-bottom: 0px;
            margin: 0px !important;
        }

        .card-body {
            padding: 16px;
            padding-top: 8px;
            padding-bottom: 8px;
            text-align: left;
            width: 100%;
        }

        .titulo {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 10px 0;
        }

        .titulo img {
            margin: 0 5px;
            /* Adiciona um pequeno espaçamento entre as imagens */
        }

        .subtitulo {
            text-align: center;
            margin: 3px;
            padding: 0px;
        }

        .btn_verificar {
            margin-top: 4px;
            margin-bottom: 4px;
        }

        .button {
            background-color: #04AA6D;
            /* Green */
            border: none;
            color: white;
            padding: 8px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            transition-duration: 0.4s;
            cursor: pointer;
            border: 2px solid #04AA6D;
            border-radius: 4px;
        }

        .button:hover {
            background-color: #0056b3 !important;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        h1,
        h2,
        h4 {
            color: #333333;
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="column">
            <div class="card">
                <div class="titulo">
                    <img src="https://sga-engeativos.com.br/build/images/icones/logo_engetecnica.svg" alt=""
                        height="50">
                    <img src="https://sga-engeativos.com.br/build/images/icones/logo_engeativos.png" alt=""
                        height="50">
                </div>

                <h3>Olá, {{ Tratamento::formatEmailName($email) }}</h3>

                <div class="card-body">

                    <!-- Título para os Eventos do Mês Vigente -->
                    <h2 style="text-align: center">Alerta de Vencimento de Documentos</h2>
                    <p style="text-transform: uppercase; color: red"> O documeto abaixo está vencido e precisa
                        ser atualizado</p>
                    <!-- Iterar sobre os Grupos de Eventos do Mês Vigente -->

                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Data do Vencimento</th>
                                <th>Documento</th>
                                <th>Colaborador(a)</th>
                                <th>Ação</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($anexos as $anexo)
                                <tr>
                                    <td>{{ $anexo->id }}</td>
                                    <td>{{ \Carbon\Carbon::parse($anexo->data_validade_doc)->format('d/m/Y') }}</td>
                                    <td>{{ $anexo->qualificacao->qualificacoes->nome_qualificacao ?? 'Sem reg.' }}</td>
                                    <td>{{ $anexo->funcionario->nome ?? 'Sem reg.' }}</td>
                                    <td>
                                        <a
                                        href="https://sga-engeativos.com.br/admin/cadastro/funcionario/show/{{ @$anexo->id_funcionario }}">
                                        <button class="button mt-5">Atualizar documento</button>
                                    </a>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                    
                </div>




                <p>Atenciosamente,<br>
                    <small>Engetecnica Engenharia e Construção Ltda.</small>
                </p>
            </div>
        </div>
    </div>
</body>

</html>

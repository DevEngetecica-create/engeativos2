<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notificação de Cadastro de Funcionário</title>
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

        /* Float four columns side by side */
        .column {
            float: center;
            width: 100%;
            padding: 0 20px;
        }

        /* Remove extra left and right margins, due to padding */
        .row {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        /* Responsive columns */
        @media screen and (max-width: 600px) {
            .column {
                width: 100%;
                display: block;
                margin-bottom: 20px;
            }
        }

        /* Style the counter cards */
        .card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            padding: 16px;
            text-align: center;
            background-color: #ffff;
            border-radius: 7px;
            margin: 0 auto;
            height: 530px;
            /* Para centralizar a card */
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
        }

        .titulo {
            text-align: center;
            margin: 3px;
            padding: 0px;
        }

        .titulo {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .titulo img {
            margin: 0 5px;
            /* Adiciona um pequeno espaçamento entre as imagens */
        }

        .card-body {
            text-align: left;
            /* Se quiser alinhar o texto à esquerda dentro da card */
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
    </style>
</head>

<body>

    <div class="row">
        <div class="column">

            <div class="card">
                <div class="titulo">
                    <img src="{{$imageUrl_logo_engetecnica}}" alt="" height="50"> <img src="{{$imageUrl_logo_engeativos}}" alt="" height="40">
                </div>
                <h3> Olá, {{$email}}</h3>
                <h4>O Funcinário: <strong> "{{$funcionario_nome}}"</strong> foi cadastrado no sistema Engeativos</h4>
                <div class="card-body">
                    <p> Observações:</p>
                    <p> a) Arquivos: <strong> {{$mensagem_arquivo }}</strong> </p>
                    <p> b) Função: <strong>{{$funcao}}</strong></p>
                    <p> c) Filial: <strong>{{$obra}}</strong></p>
                </div>

                <div class="btn_verificar">
                    <a href="{{ $url }}">
                        <button class="button">Verificar</button>
                    </a>
                </div>

                <p>Atenciosamente,<br>
                    <small>Engetecnica Engenharia e construção Ltda.</small>
                </p>

            </div>


        </div>
</body>

</html>
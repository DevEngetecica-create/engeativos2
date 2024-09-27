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

        .tabela_transferencia {
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

        .text-center {
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

                <td class="text-center"> <span class="titulo">FICHA DE ENTREGA DE EPI <p>EQUIPAMENTO DE PROTEÇÃO INDIVIDUAL</p>
                        </h>
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
                    <th>Obra: </th>
                    <th></th>
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
                            <th>Data da entrega</th>
                            <th>Quantidade</th>
                            <th>CA</th>
                            <th>Descrição do Equipamento de proteção individual</th>
                            <th>Assinatura do trabalhador</th>
                            <th>Devolução do EPI</th>
                            <th>Visto almoxarife/Sesmt</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <br>
            </div>
        </div>
    </div>

    <div>
        <p>Declaro ter recebido com as devidas orientações da Segurança do Trabalho quanto ao uso correto, os equipamentos de proteção individuais – EPI´s relacionados nesta ficha, sobre os quais comprometo-me a utilizar e a conservar conforme as normas de segurança da empresa e o que estipula a CLT e o disposto na portaria n. 3.214 de 08 de junho de 1978 – NR – 06. Declaro ainda: </p>
        <p>1- usá-los em meu trabalho para o fim a que se destina; </p>
        <p>2- Responsabilizar-me integralmente por sua guarda e conservação; </p>
        <p>3- Comunicar ao empregador qualquer alteração que os torne impróprios para o uso;</p>
        <p>4- Autorizar a empresa a descontar de meu salário o valor do EPI que por mim for danificado, extraviado e não devolvido para substituição ou devolução quando desligado da empresa;</p>
        <p>5- Tenho pleno conhecimento que o não uso dos EPIs é passível de demissão por justa causa.</p>
    </div>

    <div>
        <p>ASSINO ABAIXO DECLARANDO TER RECEBIDO O MATERIAL ACIMA DISCRIMINADO E INSTRUÇÃO DE USO, ESTANDO CIENTE DE MINHAS OBRIGAÇÕES:</p>
        <p>Local:CURITIBA/PR Data: 04/04/2024 Assinatura do funcionários:______________________________________________________________</p>
    </div>

    <div id="footer" class="margin-top">
        <div class="pagenum-container">Página <span class="pagenum"></span></div>
        <p><b>Esta ficha de Equipamento de Proteção Individual foi gerada através da Plataforma SGA-Engeativos</b></p>
        <p>{{ date('d/m/Y H:i:s') }}</p>
    </div>

</body>

</html>
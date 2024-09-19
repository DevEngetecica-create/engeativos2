<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>

<style>
    .styled-table {
        border-collapse: collapse;
        margin: 25px 0;
        font-size: 0.9em;
        font-family: sans-serif;
        min-width: 400px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    }

    .styled-table thead tr {
        background-color: #009879;
        color: #ffffff;
        text-align: left;
    }

    .styled-table th,
    .styled-table td {
        padding: 12px 15px;
    }

    .styled-table tbody tr {
        border-bottom: 1px solid #dddddd;
    }

    .styled-table tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
    }

    .styled-table tbody tr:last-of-type {
        border-bottom: 2px solid #009879;
    }

    .styled-table tbody tr.active-row {
        font-weight: bold;
        color: #009879;
    }
</style>

<body>
    <h1 style="font-size: 25px">Relatório de Veículos, gerado em {{ $data_de_geracao }}</h1>
    <table class="styled-table">
        <thead>
            <tr>
                <th width="8%">ID</th>
                <th>Código</th>
                <th>Empresa</th>
                <th>Endereço</th>
                <th>Data</th>
                <th>Situação</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($obras as $obra)
                <tr>
                    <td><span class="badge badge-dark">{{ $obra->id }}</span></td>

                    <td>
                        @isset($obra->codigo_obra)
                            {{ $obra->codigo_obra }}
                        @endisset
                    </td>
                    <td>
                        @isset($obra->empresa)
                            {{ $obra->empresa->razao_social }}
                        @endisset
                    </td>
                    <td>
                        @isset($obra->endereco)
                            {{ $obra->endereco }}
                        @endisset
                    </td>
                    <td>
                        @isset($obra->created_at)
                            {{ $obra->created_at }}
                        @endisset
                    </td>
                    <td>
                        @isset($obra->status)
                            {{ $obra->status }}
                        @endisset
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

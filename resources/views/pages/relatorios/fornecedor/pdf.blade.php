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
    <h1 style="font-size: 25px">Relatório de Fornecedores, gerado em {{ $data_de_geracao }}</h1>
    <table class="styled-table">
        <thead>
            <tr>
                <th width="8%">ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Endereço</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($fornecedores as $fornecedor)
                <tr>
                    <td><span class="badge badge-dark">{{ $fornecedor->id }}</span></td>

                    <td>
                        @isset($fornecedor->razao_social)
                            {{ $fornecedor->razao_social }}
                        @endisset
                    </td>
                    <td>
                        @isset($fornecedor->email)
                            {{ $fornecedor->email }}
                        @endisset
                    </td>
                    <td>
                        @isset($fornecedor->endereco)
                            {{ $fornecedor->endereco }}
                        @endisset
                    </td>
                    <td>
                        @isset($fornecedor->created_at)
                            {{ $fornecedor->created_at }}
                        @endisset
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

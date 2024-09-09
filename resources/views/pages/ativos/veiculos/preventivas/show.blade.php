@extends('dashboard')

<style>
   table td
   {
    font-size:small;
   }
</style>

@section('content')
    <div class="container">
        <h1>Detalhes da Manutenção Preventiva</h1>
        <div class="card">
            <div class="card-header">
                <h3>{{ $preventiva->nome_preventiva }}</h3>
            </div>
            <div class="card-body">
                @php
                    $periodosArray = json_decode($preventiva->periodo, true);
                    $periodos = [];
                    foreach ($periodosArray as $p) {
                        $periodos = array_merge($periodos, array_map('trim', explode(',', $p)));
                    }
                    $periodos = array_unique($periodos);
                    sort($periodos);
                @endphp
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Nome do Serviço</th>
                            @foreach ($periodos as $periodo)
                                <th class="text-center">{{ $periodo }} Horas</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (json_decode($preventiva->nome_servico) as $key => $nome_servico)
                            <tr>
                                <td >{{ $nome_servico }}</td>
                                @foreach ($periodos as $periodo)
                                    <td class="text-center">
                                        @php
                                            $periodoServico = array_map('trim', explode(',', json_decode($preventiva->periodo)[$key]));
                                            if (in_array($periodo, $periodoServico)) {
                                                $situacao = json_decode($preventiva->situacao)[$key];
                                                switch ($situacao) {
                                                    case 1:
                                                        echo "<span style='font-size:14px;'>&#9899;</span>"; // Executar
                                                        break;
                                                    case 2:
                                                        echo "<span style='font-size:14px;'>&#9673;</span>"; // Executar conforme condição
                                                        break;
                                                    case 3:
                                                        echo "<span style='font-size:14px;'>&#9650;</span>"; // Conferir/ verificar
                                                        break;
                                                }
                                            }
                                        @endphp
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('veiculo.manut_preventiva.edit', $preventiva->id) }}" class="btn btn-warning">Editar</a>
                <form action="{{ route('veiculo.manut_preventiva.delete', $preventiva->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
                <a href="{{ route('veiculo.manut_preventiva.index') }}" class="btn btn-primary">Voltar para a Lista</a>
            </div>
        </div>
    </div>
@endsection

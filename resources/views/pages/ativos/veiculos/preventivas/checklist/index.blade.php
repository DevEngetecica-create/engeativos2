@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Checklists de Manutenção Preventiva</h1>

        <div class="card-header">
            <h3>{{ $preventiva->nome_preventiva }}</h3>
        </div>

        @php
            $periodosArray = json_decode($preventiva->periodo, true);
            $periodos = [];
            foreach ($periodosArray as $p) {
                $periodos = array_merge($periodos, array_map('trim', explode(',', $p)));
            }
            $periodos = array_unique($periodos);
            sort($periodos);

        @endphp

        <a href="{{ route('veiculo_preventivas_checklist.create', $preventiva->id) }}" class="btn btn-primary">Checklist</a>

            <div class="btn-group">
                <a href="#" class="btn btn-primary active" aria-current="page">Cadastrar Checklist</a>
                @foreach ($periodos as $periodo)
                    <a href="{{ route('veiculo_preventivas_checklist.create', $preventiva->id.'?periodo='.$periodo)}}" class="btn btn-primary">De {{ $periodo }} horas |</a>  
                @endforeach
            </div>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Veículo</th>
                    <th>Manutenção Preventiva</th>                    
                    <th>Período</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($checkLists as $checkList)
                    <tr>
                        <td>{{ $checkList->id }}</td>
                        <td>{{ $checkList->id_veiculo }}</td>
                        <td>{{ $preventiva->nome_preventiva }}</td>

                        <td>
                           
                                <a href="{{ route('veiculo_preventivas_checklist.show', $checkList->id) }}" class="btn btn-info">Ver</a>
                                <a href="{{ route('veiculo_preventivas_checklist.edit', $checkList->id) }}" class="btn btn-warning">Editar</a>
                                <form action="{{ route('veiculo_preventivas_checklist.destroy', $checkList->id) }}"
                                    method="POST" style="display:inline;">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger">Excluir</button>
                                </form>
                         
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $checkLists->links() }}
    </div>
@endsection

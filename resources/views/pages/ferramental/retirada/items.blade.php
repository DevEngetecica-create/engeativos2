<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Ativo</th>
            <th>Patrimônio</th>            
        </tr>
    </thead>
    <tbody>
        @foreach($detalhes->itens as $item)

        {{--dd($detalhes->itens)--}}
        <tr>
            <td>{{ $item->item_nome }}</td>
            <td>{{ $item->patrimonio }}</td>            
        </tr>
        @endforeach
    </tbody>
</table>
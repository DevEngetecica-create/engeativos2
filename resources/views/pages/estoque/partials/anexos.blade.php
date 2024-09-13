<table class="table table-bordered table-hover align-middle table-nowrap table-sm mb-0" id="idDaTabela">
    <thead>
        <tr>
            <th class="text-center">ID</th>
            <th class="text-center">Data de Inclusão</th>
            <th class="text-center">Arquivo</th>   
            <th class="text-center">Ações</th>
        </tr>
    </thead>
    <tbody>
        @if(count($anexos) >0)
        @foreach($anexos as $item)
        <tr id="produto-{{ $item->id }}">
            <td class="text-center">{{ $item->id }}</td>
            <td class="text-center">{{ Tratamento::dateBr($item->created_at) }}</td>
            <td>{{ $item->nome_arquivo}}</td>
                <td class="text-center">
                    
                    <a href="{{ route('anexo.download', $item->id) }}">
                        <span class="btn btn-success btn-sm" title="Baixar anexo"><i class="mdi mdi-download"></i></span>
                    </a>

                    <a  id="deletar_anexos_estoque"  data-id="{{ $item->id}}">
                        <span class="btn btn-danger btn-sm" title="Excluir"><i class="mdi mdi-delete"></i></span>
                    </a>
                </td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="5">Nenhum registro encontrado.</td>
        </tr>
        @endif
    </tbody>
</table>

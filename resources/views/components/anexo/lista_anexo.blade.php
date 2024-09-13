<table id="tabelAanexo" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="text-center">Data de Inclusão</th>
            <th>Título</th>
            <th class="text-center">Tipo</th>
            
                        <th class="text-center">Vencimento</th>
            
           
            <th class="text-center">Ações</th>
        </tr>
    </thead>
    <tbody>
        @if(count($anexo) >0)
        @foreach($anexo as $item)
        <tr>
            <td class="text-center">{{ Tratamento::dateBr($item->created_at) }}</td>
           
            <td>{{ $item->titulo }}</td>
            <td class="text-center">{{ $item->tipo }}</td>
            
                    
                    @if($item->data_vencimento < Now())
                        <td class="text-center">-</td>
                    @else
                        <td class="text-center">{{Tratamento::dateBr($item->data_vencimento) ?? "-" }}</td>
                    @endif
    
                    <td class="text-center">
                 <a href="{{route('ativo.externo.editar.calibracao', $item->id)}}">
                    <span class="btn btn-warning btn-sm" title="Editar"><i class="mdi mdi-pencil mdi-18px"></i></span>
                </a>
                <a href="{{ route('anexo.download', $item->id) }}">
                    <span class="btn btn-success btn-sm" title="Baixar anexo"><i class="mdi mdi-download mdi-18px"></i></span>
                </a>
                
                <a href="{{ route('anexo.destroy', $item->id) }}" id="deletar">
                    <span class="btn btn-danger btn-sm" title="Excluir"><i class="mdi mdi-delete mdi-18px"></i></span>
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
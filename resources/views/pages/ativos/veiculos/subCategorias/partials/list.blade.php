<table class="table-hover  table-bordered table">
    <thead>
        <tr>
            <th class="text-center" width="8%">ID</th>
            <th>Categoria</th>
            <th>SubCategoria</th>
            <th>Status</th>
            <th>Dt Cadastro</th>
            <th class="text-center" width="10%">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($veiculoSubCategoria as $subCategoria)
        <tr>           
            <td class="text-center">{{ $subCategoria->id }}</td>
            <td >{{ $subCategoria->categorias->nomeCategoria }}</td>
            <td>{{ $subCategoria->nomeSubCategoria}}</td>
             <td class="text-center">{{ $subCategoria->statusSubCategoria }} </td>
            <td>{{ $subCategoria->created_at ?? '-' }}</td>
            
           
            <td class="  d-flex justify-content-center text-center ">

                <a class="mr-2" href="{{ route('ativo.veiculo.subCategoria.editar', $subCategoria->id) }}">
                    <button type="button" class="btn bg-warning btn-sm"><i class="mdi mdi-pencil " title="Editar"></i></button>
                </a>

                @if (session()->get('usuario_vinculo')->id_nivel ?? 1 <= 2) 
                
                <form class="m-0 p-0" action="{{ route('ativo.veiculo.subCategoria.destroy', $subCategoria->id) }}" method="POST">
                    @csrf
                    @method('delete')
                        <a class="excluir-padrao mx-2" data-id="$subCategoria->id" data-table="empresas" data-module="cadastro/empresa">
                            <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" type="submit" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o registro?')">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </a>
                </form>   
            @endif
            
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


<div class="d-flex justify-content-end mt-3">
    <div class="paginacao">
        {{$veiculoSubCategoria->render()}}
    </div>
</div>
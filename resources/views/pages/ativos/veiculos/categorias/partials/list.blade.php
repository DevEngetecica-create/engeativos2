<table class="table-hover  table-bordered table">
    <thead>
        <tr>
            <th class="text-center" width="8%">ID</th>
            <th>Nome</th>
            <th>Status</th>
            <th>Dt Cadastro</th>
            <th class="text-center" width="10%">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($veiculoCategoria as $categoria)
        <tr>
            <td class="text-center">{{ $categoria->id }}</td>
            <td>{{ $categoria->nomeCategoria}}</td>
             <td class="text-center">{{ $categoria->statusCategoria }} </td>
            <td>{{ $categoria->created_at ?? '-' }}</td>
            
           
            <td class="  d-flex justify-content-center text-center ">

                <a class="mr-2" href="{{ route('cadastro.veiculo.categoria.editar', $categoria->id) }}">
                    <button type="button" class="btn bg-warning btn-sm"><i class="mdi mdi-pencil " title="Editar"></i></button>
                </a>

                @if (session()->get('usuario_vinculo')->id_nivel ?? 1 <= 2) 
                
                <form class="m-0 p-0" action="{{ route('cadastro.veiculo.categoria.destroy', $categoria->id) }}" method="POST">
                    @csrf
                    @method('delete')
                        <a class="excluir-padrao mx-2" data-id="$categoria->id" data-table="empresas" data-module="cadastro/empresa">
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
        {{$veiculoCategoria->render()}}
    </div>
</div>
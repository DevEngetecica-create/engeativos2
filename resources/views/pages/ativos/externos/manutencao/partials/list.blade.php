    <table class="table table-hover">
        <thead>
            <tr>
                <th class="text-center" scope="col">ID</th>
                <th scope="col">Obra</th>
                <th scope="col">Patrimônio</th>
                <th scope="col">Título</th>
                <th scope="col">Fornecedor</th>
                <th class="text-center" scope="col">Valor</th>
                <th class="text-center" scope="col">Status</th>
                <th class="text-center {{ session()->get('usuario_vinculo')->id_nivel <= 2 ? 'd-block' : 'd-none' }}" scope="col">Ações</th>
            </tr>
        </thead>

        <tbody>
            @foreach($ativos as $ativo)
            <tr>
                <td class="text-center">{{$ativo->id}}</td>
                <td>{{$ativo->obra->nome_fantasia}}</td>
                <td>{{$ativo->ativo_externo_estoque->patrimonio ?? ""}}</td>

                <td>{{$ativo->configuracao->titulo ?? ""}}</td>
                <td>{{$ativo->fornecedor->nome_fantasia}}</td>
                <td class="text-center">R${{Tratamento::formatFloat($ativo->valor)}}</td>
                <td class="text-center"> <span class="px-3 py-1 bg-{{$ativo->situacao->classe}} text-white">{{$ativo->situacao->titulo}}</span></td>

                <td class="d-flex justify-content-center text-center {{ session()->get('usuario_vinculo')->id_nivel <= 2 ? 'd-block' : 'd-none' }}">

                    <a class="mx-2" href="{{ route('ativo.externo.manutencao.detalhes', $ativo->id) }}">
                        <button type="button" class="btn btn-block bg-info btn-sm text-white"><i class="mdi mdi-eye mdi-18px" title="Editar"></i></button>
                    </a>

                    <a class="mx-2 " href="{{ route('ativo.externo.manutencao.editar', $ativo->id) }}">
                        <button type="button" class="btn btn-block bg-warning btn-sm text-white"><i class="mdi mdi-lead-pencil mdi-18px" title="Editar"></i></button>
                    </a>

                    <form class="m-0 p-0" action="{{route('ativo.externo.manutencao.delete', $ativo->id)}}" method="post">
                        @csrf
                        @method('delete')
                        <a class="excluir-padrao" data-id="{{ $ativo->id }}" data-table="empresas" data-module="cadastro/empresa">
                            <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" type="submit" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o registro?')">
                                <i class="mdi mdi-delete"></i></button>
                        </a>
                    </form>                    
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row mt-3">
        <div class="d-flex justify-content-end col-sm-12 col-md-12 col-lg-12 ">
            <div class="paginacao">
                {{$ativos->render()}}
            </div>
        </div>
    </div>
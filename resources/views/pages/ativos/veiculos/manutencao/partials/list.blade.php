


    <table class="table-hover table-striped table  mt-2">
        <thead>
            <tr>
                <th class="text-center">ID</th>
                <th>Fornecedor</th>
                <th>Serviço</th>
                <th>Custo</th>
                @if ($veiculo->tipo == 'maquinas')
                        <th>Atual (hr)</th>
                        <th>Próxima Revisão (hr)</th>
                    @else
                        <th>Atual (km)</th>
                        <th>Próxima Revisão(km)</th>
                    @endif
                <th>Data De Execução</th>
                <th>Situação</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($manutencoes as $manutencao)
            <tr class="m-0 p-0">
                <td class="text-center">{{ $manutencao->id }}</td>
                
                <td>{{ $manutencao->fornecedor->razao_social ?? 'Sem reg.'}}</td>
                
                <td>{{ $manutencao->servico->nomeServico  }}</td>
                
                <td>R$ {{ Tratamento::formatFloat($manutencao->valor_do_servico) }} </td>
                
                @if ($veiculo->tipo == 'maquinas')
                            <td>{{ $manutencao->horimetro_atual  ?? 0}} hr</td>
                            <td>{{ $manutencao->horimetro_proximo   ?? 0}} hr</td>
                        @else
                            <td>{{ $manutencao->quilometragem_atual   ?? 0}} km</td>
                            <td>{{ $manutencao->quilometragem_nova   ?? 0}} km</td>
                        @endif
                        
                <td>{{ Tratamento::dateBr($manutencao->data_de_execucao)}}</td>
                
                <td>
                    @if ($manutencao->situacao == 1)
                    <span class="px-2 py-1 bg-primary">Pendente</span>
                    @elseif ($manutencao->situacao == 2)
                    <span class="px-2 py-1 bg-warning">Em Execução</span>
                    @elseif ($manutencao->situacao == 3)
                    <span class="px-2 py-1 bg-success text-white">Concluído</span>
                    @elseif ($manutencao->situacao == 4)
                    <span class="px-2 py-1 bg-danger">Cancelado</span>
                    @endif

                </td>
                <td class="d-flex justify-content-center m-0 p-1">
                    <div class="d-flex" >
                        <div class="p-1 ">
                        <a data-bs-toggle="modal" data-bs-target="#anexarArquivoAtivoManutencao" class="manutencao" href="javascript:void(0)" 
                            data-id="{{ $manutencao->id}}"><span class='btn btn-success btn-sm ml-1'><i class="mdi mdi-upload"></i></span></a>
                        </div>
                   
                        <div class="p-1 ">
                        <a href="{{ route('ativo.veiculo.manutencao.show', $manutencao->id) }}" title="Visualizar manutenção">
                            <span class='btn btn-primary btn-sm ml-1'><i class="mdi mdi-eye"></i></span></a>
                        </div>

                        <div class="p-1">
                            <a href="{{ route('ativo.veiculo.manutencao.editar', [$manutencao->id, 'edit']) }}">
                                <button class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Editar"><i class="mdi mdi-pencil"></i>
                                </button>
                            </a>
                        </div>
                    
                        <div class="p-1 ">

                            <form class="m-0 p-0" action="{{ route('ativo.veiculo.manutencao.cancel', $manutencao->id) }}" method="POST">
                                @csrf
                                @method('patch')
                                <a class="excluir-padrao" data-id="{{ $manutencao->id }}" data-table="empresas" data-module="cadastro/empresa">

                                    <button class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" type="submit" title="Cancelar" onclick="return confirm('Tem certeza que deseja cancelar a manutenção?')">
                                        <i class="mdi mdi-cancel"></i></button>
                                </a>
                            </form>
                        </div>

                        <div class="p-1">
                            <form class="m-0 p-0" action="{{ route('ativo.veiculo.manutencao.delete', $manutencao->id) }}" method="POST">
                                @csrf
                                @method('delete')
                                <a class="excluir-padrao" data-id="{{ $manutencao->id }}" data-table="empresas" data-module="cadastro/empresa">
                                    <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" type="submit" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o registro?')">
                                        <i class="mdi mdi-delete"></i></button>
                                </a>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
<!-- Paginação -->
<div class="row mt-3">
    <div class="d-flex justify-content-end col-sm-12 col-md-12 col-lg-12 ">
        <div class="paginacao">
            {{$manutencoes->render()}}
        </div>
    </div>
</div>
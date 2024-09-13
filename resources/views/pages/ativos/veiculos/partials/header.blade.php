<div class="jumbotron p-3">
<table class="table table-bordered table-hover table-sm align-middle table-nowrap mb-0">
        <thead>
            <tr>
                @if ($veiculo->tipo == 'maquinas')
                    <th >ID Máquina</th>
                @else
                    <th>Placa</th>
                @endif
                <th>Descrição</th>
                @if ($veiculo->tipo == 'maquinas')
                    <th class="text-center">Hor. Anterior</th>
                    <th class="text-center">Hor. Atual</th>
                @else
                    <th class="text-center">KM Anterior</th>
                    <th class="text-center">KM atual</th>
                @endif

                <th class="text-center">Inclusão</th>
                <th width="10%">Ações</th>
            </tr>
        </thead>
        <tbody>

            <tr>
                @if ($veiculo->tipo == 'maquinas')
                    <td><span class="bg-secondary px-2 rounded border text-white">{{ $veiculo->codigo_da_maquina }}</span></td>
                @else
                    <td><span class="bg-secondary px-2 rounded border text-white">{{ $veiculo->placa}}</span></td>
                @endif

                <td>{{ $veiculo->marca }} | {{ $veiculo->modelo }} | {{ $veiculo->veiculo}}</td>

                @php
                    $ultima_quilometragem = $veiculo->quilometragens->last();
                @endphp
                
                @if ($veiculo->tipo == 'maquinas')
                
                    <td class="text-center">{{ $ultima_quilometragem->horimetro_atual ?? 0}} hr</td>
                    <td class="text-center">{{ $ultima_quilometragem->horimetro_proximo?? 0}} hr</td>
                @else
                    <td class="text-center">{{ $ultima_quilometragem->quilometragem_atual?? 0}} km</td>
                    <td class="text-center">{{ $ultima_quilometragem->quilometragem_nova?? 0}} km</td>
                  
                @endif

                <td class="text-center">{{ Tratamento::datetimeBr($veiculo->created_at) }}</td>

                <td class="d-flex">
                    <div class="dropdown mr-2" title="Gerenciar">
                        <button class="btn btn-warning btn-sm" id="dropdownMenuButton1" data-bs-toggle="dropdown" type="button" aria-expanded="false">
                            <i class="mdi mdi-spin mdi-cog-outline fs-14"></i> 
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="{{ route('ativo.veiculo.editar', $veiculo->id) }}">Editar</a></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('ativo.veiculo.quilometragem.index', $veiculo->id) }}">
                                    @if ($veiculo->tipo == 'maquinas')
                                        Horímetro
                                    @else
                                        Quilometragem
                                    @endif
                                </a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('ativo.veiculo.abastecimento.index', $veiculo->id) }}">Abastecimento</a></li>
                            <li><a class="dropdown-item" href="{{ route('ativo.veiculo.manutencao.index', $veiculo->id) }}">Manutenção</a></li>
                            <li><a class="dropdown-item" href="{{ route('ativo.veiculo.ipva.index', $veiculo->id) }}">IPVA</a></li>
                            <li><a class="dropdown-item" href="{{ route('ativo.veiculo.seguro.index', $veiculo->id) }}">Seguro</a></li>
                            <li><a class="dropdown-item" href="{{ route('ativo.veiculo.depreciacao.index', $veiculo->id) }}">Depreciação</a></li>
                        </ul>
                    </div>
                    <form action="{{ route('ativo.veiculo.delete', $veiculo->id) }}" method="POST">
                        @csrf
                        <a class="excluir-padrao" data-id="{{ $veiculo->id }}" data-table="empresas" data-module="cadastro/empresa">
                            <button class="btn btn-danger btn-sm mx-2" data-toggle="tooltip" data-placement="top" type="submit" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o veículo?')">
                                <i class="mdi mdi-delete"></i> 
                            </button>
                        </a>
                    </form>
                </td>
            </tr>
        </tbody>
    </table>
</div>

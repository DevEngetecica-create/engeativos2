

@foreach($count_veiculos_list as $total_veiculos)
      <h5 class="text-success">{{$total_veiculos->total_veiculos}} veiculos</h5>
@endforeach

<table class="table table-bordered table-hover table-sm align-middle table-nowrap mb-0">

    <thead>
        <tr>  
            <th class="d-none d-xl-block" >Obra</th>
            <th>Placa/ID Interna</th>
            <th>CÓD. Veículo</th>
            <th class="d-none d-xl-block">Tipo</th>
            <th class="text-center">KM atual</th>
            <th class="text-center">HR Atual</th>

            @if (session()->get('usuario_vinculo')->id_nivel < 2 or session()->get('usuario_vinculo')->id_nivel == 13)             
                <th class="text-center">Ações</th>
            @endif
        </tr>
    </thead>

    <tbody>

        @if (session()->get('usuario_vinculo')->id_nivel < 2 or session()->get('usuario_vinculo')->id_nivel == 13)        

        @foreach ($veiculos->groupBy('id') as $veiculoId => $veiculosGrupo)

            @foreach ($veiculosGrupo as $veiculo)

            <tr>            
                <td class="d-none d-xl-block">
                    <span>{{ $veiculo->obra->nome_fantasia ?? "Sem reg."}}</span>
                </td>

                <td>
                    @if ($veiculo->tipo == 'maquinas')

                    <span class="bg-secondary px-2 text-white">{{ $veiculo->codigo_da_maquina }}</span>

                    @else

                    <span class="bg-secondary px-2 text-white">{{ $veiculo->placa }}</span>

                    @endif

                </td>
                
                @php

                $tiposVeiculos = [

                'motos' => 'Moto',

                'caminhoes' => 'Caminhão',

                'carros' => 'Carro',

                'maquinas' => 'Máquina',

                ];

                @endphp



                <td class="text-uppercase ">

                    {{-- {{ $veiculo->marca }} | {{ $veiculo->modelo }} | --}}

                    {{ $veiculo->veiculo }}



                </td>



                <td class="d-none d-xl-block">

                    @if ($veiculo->tipo == 'motos')

                    <span class="bg-primary px-1 text-white">{{ $tiposVeiculos[$veiculo->tipo] }}</span>

                    @elseif ($veiculo->tipo == 'caminhoes')

                    <span class="bg-danger px-1 text-white">{{ $tiposVeiculos[$veiculo->tipo] }}</span>

                    @elseif ($veiculo->tipo == 'carros')

                    <span class="bg-success px-1 text-white">{{ $tiposVeiculos[$veiculo->tipo] }}</span>

                    @elseif ($veiculo->tipo == 'maquinas')

                    <span class="bg-warning px-1 text-white">{{ $tiposVeiculos[$veiculo->tipo] }}</span>

                    @endif

                </td>



                <td class="text-center">

                    @if ($veiculo->tipo != 'maquinas')

                    

                        @php

                            $ultimaQuilometragem = $veiculo->quilometragens->last();

                        @endphp

                        

                        @if ($ultimaQuilometragem)

                            {{ $ultimaQuilometragem->quilometragem_nova ?? 0 }} km

                        @else

                            {{ $veiculo->quilometragem_inicial ?? 0 }} km

                        @endif

                    @endif

                </td>

                <td class="text-center">

                    @if ($veiculo->tipo == 'maquinas')

                    

                        @php

                            $ultimoHorimetro = $veiculo->horimetro->last();

                        @endphp

                    

                        @if ($ultimoHorimetro)

                        

                            {{ $ultimoHorimetro->horimetro_novo ?? 0}} hr

                        @else

                            -

                            

                        @endif

                    

                    @endif

                </td>

                @if (session()->get('usuario_vinculo')->id_nivel < 2 or session()->get('usuario_vinculo')->id_nivel == 13) <td class="text-center">

                    <!-- Default dropstart button -->





                    <div class="btn-group dropstart" title="Gerenciar">

                        <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">

                            

                            <i class="mdi mdi-spin mdi-cog-outline"></i>

                        </button>

                        <ul class="dropdown-menu" style="">

                            <li>

                                <a class="dropdown-item bg-warning" href="{{ route('ativo.veiculo.editar', $veiculo->id) }}">

                                    <span class='btn btn-warning btn-sm ml-1'>

                                        <i class="mdi mdi-pencil"></i> Editar

                                    </span>

                                </a>

                            </li>

                            <div class="dropdown-divider"></div>

                            <li>

                                <a data-bs-toggle="modal" data-bs-target="#anexarArquivoAtivoVeiculo" class="veiculo dropdown-item bg-info" href="javascript:void(0)" data-id="{{ $veiculo->id}}"><span class='btn btn-info btn-sm ml-1'><i class="mdi mdi-upload"></i>Anexar arquivos</span></a>

                            </li>





                            <div class="dropdown-divider"></div>

                            <li>

                                <a class="dropdown-item" href="{{ route('ativo.veiculo.quilometragem.index', $veiculo->id) }}">

                                    @if ($veiculo->tipo == 'maquinas')

                                    <i class='mdi mdi-clock p-2'></i> Horímetro

                                    @else

                                    <i class='mdi mdi-road p-2' p-2></i> Quilometragem

                                    @endif

                                </a>

                            </li>



                            <li>

                                <a class="dropdown-item" href="{{ route('ativo/veiculo/tacografo/index', $veiculo->id) }}">

                                    @if ($veiculo->tipo == 'caminhoes')

                                    <i class='mdi mdi-gauge p-2' p-2></i> Tacografo

                                    @else



                                    @endif

                                </a>

                            </li>



                            <li>

                                <a class="dropdown-item" href="{{ route('ativo/veiculo/acessorios/index', $veiculo->id) }}"><i class="mdi mdi-shape-plus p-2"></i> Acessórios</a>

                            </li>

                            <li>

                                <a class="dropdown-item" href="{{ route('ativo.veiculo.abastecimento.index', $veiculo->id) }}"> <i class="mdi mdi-gas-station p-2"></i> Abastecimento</a>

                            </li>

                            <li>

                                <a class="dropdown-item" href="{{ route('ativo.veiculo.manutencao.index', $veiculo->id) }}"><i class="mdi mdi-wrench p-2"></i>Manutenção</a>

                            </li>

                            <li>

                                <a class="dropdown-item" href="{{ route('ativo.veiculo.ipva.index', $veiculo->id) }}"><i class="mdi mdi-garage p-2"></i> IPVA</a>

                            </li>

                            <li>

                                <a class="dropdown-item" href="{{ route('ativo.veiculo.seguro.index', $veiculo->id) }}"> <i class="mdi mdi-lock-reset p-2"></i> Seguro</a>

                            </li>

                            <li>

                                <a class="dropdown-item" href="{{ route('ativo.veiculo.depreciacao.index', $veiculo->id) }}"><i class="mdi mdi-currency-usd-off p-2"></i> Depreciação</a>

                            </li>



                            <div class="dropdown-divider"></div>



                            <li>



                                <a class="dropdown-item bg-danger" data-id="{{ $veiculo->id }}" data-table="empresas" data-module="cadastro/empresa" onclick="return confirm('Tem certeza que deseja exluir o veículo?')">

                                    <form action="{{ route('ativo.veiculo.delete', $veiculo->id) }}" method="POST" class="m-0 p-0">

                                        @csrf

                                        <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" type="submit" title="Excluir"><i class="mdi mdi-delete"></i>

                                            Excluir</button>

                                    </form>

                                </a>



                            </li>

                        </ul>

                    </div>



                    <a href="{{ route('ativo.veiculo.show', $veiculo->id) }}" title="Visualizar detalhes do veículo"><!--Visualizar-->

                        <span class='btn btn-primary btn-sm ml-1'>

                            <i class="mdi mdi-eye"></i>

                        </span>

                    </a>

                    </td>

                    @endif

            </tr>

            @endforeach

            @endforeach

            @else

            @foreach ($veiculos->where('obra_id', session()->get('obra')->id)->groupBy('id') as $veiculoId => $veiculosGrupo)

            @foreach ($veiculosGrupo as $veiculo)

            <tr>

                <td>

                    <span class="badge badge-dark">{{ $veiculo->id }}</span>

                </td>

                <td>

                    <span class="badge badge-secondary">{{ $veiculo->obra->razao_social }}</span>

                </td>

                @php

                $tiposVeiculos = [

                'motos' => 'Moto',

                'caminhoes' => 'Caminhão',

                'carros' => 'Carro',

                'maquinas' => 'Máquina',

                ];

                @endphp

                <td>

                    @if ($veiculo->tipo == 'motos')

                    <span class="badge badge-primary">{{ $tiposVeiculos[$veiculo->tipo] }}</span>

                    @elseif ($veiculo->tipo == 'caminhoes')

                    <span class="badge badge-danger">{{ $tiposVeiculos[$veiculo->tipo] }}</span>

                    @elseif ($veiculo->tipo == 'carros')

                    <span class="badge badge-success">{{ $tiposVeiculos[$veiculo->tipo] }}</span>

                    @elseif ($veiculo->tipo == 'maquinas')

                    <span class="badge badge-warning">{{ $tiposVeiculos[$veiculo->tipo] }}</span>

                    @endif

                </td>

                <td>

                    @if ($veiculo->tipo == 'maquinas')

                    <span class="badge badge-secondary">{{ $veiculo->codigo_da_maquina }}</span>

                    @else

                    <span class="badge badge-secondary">{{ $veiculo->placa }}</span>

                    @endif

                </td>

                <td class="text-uppercase">

                    {{-- {{ $veiculo->marca }} | {{ $veiculo->modelo }} | --}}

                    {{ $veiculo->veiculo }}



                </td>

                <td>

                    @if ($veiculo->tipo != 'maquinas')

                    

                        @php

                            $ultimaQuilometragem = $veiculo->quilometragens->last();

                        @endphp

                        

                        @if ($ultimaQuilometragem)

                            {{ $ultimaQuilometragem->quilometragem_nova ?? 0 }} km

                        @else

                            {{ $veiculo->quilometragem_inicial ?? 0 }} km

                        @endif

                    @endif

                </td>

                <td>

                    @if ($veiculo->tipo == 'maquinas')

                    

                        @php

                            $ultimoHorimetro = $veiculo->horimetro->last();

                        @endphp

                    

                        @if ($ultimoHorimetro)

                        

                            {{ $ultimoHorimetro->horimetro_novo ?? 0}} hr

                            

                        @endif

                    

                    @endif

                </td>







                @if (session()->get('usuario_vinculo')->id_nivel <= 2 or session()->get('usuario_vinculo')->id_nivel == 13) <td class="row">

                    <div class="dropdown">

                        <button class="btn btn-info btn-sm" id="dropdownMenuButton1" data-bs-toggle="dropdown" type="button" aria-expanded="false">

                            <i class="mdi mdi-pencil"></i> Gerenciar

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

                            <li>

                                <a class="dropdown-item" href="{{ route('ativo.veiculo.acessorios.ajax', $veiculo->id) }}">Acessórios</a>

                            </li>

                            <li>

                                <a class="dropdown-item" href="{{ route('ativo.veiculo.abastecimento.index', $veiculo->id) }}">Abastecimento</a>

                            </li>

                            <li>

                                <a class="dropdown-item" href="{{ url('admin/ativo/veiculo/manutencao/list/'. $veiculo->id) }}">Manutenção</a>

                            </li>

                            <li>

                                <a class="dropdown-item" href="{{ route('ativo.veiculo.ipva.index', $veiculo->id) }}">IPVA</a>

                            </li>

                            <li>

                                <a class="dropdown-item" href="{{ route('ativo.veiculo.seguro.index', $veiculo->id) }}">Seguro</a>

                            </li>

                            <li>

                                <a class="dropdown-item" href="{{ route('ativo.veiculo.depreciacao.index', $veiculo->id) }}">Depreciação</a>

                            </li>

                        </ul>

                    </div>



                    <form action="{{ route('ativo.veiculo.delete', $veiculo->id) }}" method="POST">

                        @csrf

                        <a class="excluir-padrao" data-id="{{ $veiculo->id }}" data-table="empresas" data-module="cadastro/empresa">

                            <button class="badge badge-danger" data-toggle="tooltip" data-placement="top" type="submit" title="Excluir" onclick="return confirm('Tem certeza que deseja exluir o veículo?')"><i class="mdi mdi-delete"></i>

                                Excluir</button>

                        </a>

                    </form>

                    </td>

                    @endif

            </tr>

            @endforeach

            @endforeach

            @endif

    </tbody>

</table>

<!-- Paginação -->

<div class="row mt-3">

    <div class="d-flex justify-content-end col-sm-12 col-md-12 col-lg-12 ">

        <div class="paginacao">

            {{$veiculos->render()}}

        </div>

    </div>

</div>



{{--dd(GraficosVeiculos::countVeiculos())--}}







<script src="https://code.jquery.com/jquery-3.6.0.js"></script>


    <div class="row row-cols-1 row-cols-md-4 g-4">
        @foreach ($locacoes as $locacao)
            <div class="col ">
                <div class="card h-100 shadow rounded">
                    <div class="d-flex justify-content-center">
                        
                        {{--$locacao->id--}}
    
                    

                    @if($locacao->veiculo->tipo ?? "null" == 'caminhoes' )
                       
                    <img class="card-img-center" src="{{asset('imagens/veiculos/frente-do-caminhao-de-entrega.png')}}" style="max-width:100px !important">
                        
                    @elseif($locacao->veiculo->tipo ?? "null"  == 'motos' )
                    
                        <img src="{{asset('imagens/veiculos/motocicleta.png')}}" style="max-width:100px !important">
                        
                    @elseif($locacao->veiculo->tipo ?? "null"  == 'carros' )
                    
                        <img src="{{asset('imagens/veiculos/carro-sedan-na-frente.png')}}" style="max-width:100px !important">
                    
                    @elseif($locacao->veiculo->tipo ?? "null"  == 'maquinas' )
                    
                        <img src="{{asset('imagens/veiculos/caminhao-guindaste.png')}}" style="max-width:100px !important">
                   
                    @endif
                    </div>
                        <div class="card-body">
                            <h5 class="fs-6"><span class="text-success">ORIGEM: {{ $locacao->obra->nome_fantasia}}</span></h5>
                            <h6 class="fs-6"><span class="text-warning">DESTINO: @if($locacao->id_obraDestino == $locacao->obraDestino->id){{$locacao->obraDestino->nome_fantasia}}@endif </span></h6>
                            
                            <hr>
                            
                            <p ><strong>Motorista</strong> - {{ $locacao->funcionarios->nome ?? "Sem reg."}}</p>
                            
                            <p ><strong>{{ $locacao->veiculo->marca ?? "Sem reg." }}</strong> - {{ $locacao->veiculo->veiculo ?? "Sem reg." }}</p>
                            
                            <p ><strong>PLACA/ SÉRIE: </strong> {{ $locacao->veiculo->placa ?? "Sem reg." ? $locacao->veiculo->placa ?? "Sem reg." : $locacao->veiculo->codigo_da_maquina ?? "Sem reg."}}</p>
                            
                            <p class="card-text"> Alocado de: <strong>{{ Tratamento::dateBr($locacao->data_inicio) }}</strong> até <strong>{{ Tratamento::dateBr($locacao->data_prevista) }}</strong></p>
                        </div>
                        <div class="card-footer d-flex justify-content-center text-center">
                            
                             <a class="mx-2 " href="{{ route('cadastro.funcionario.show', $locacao->id_funcionario) }}" title="Dados do motorista">
                                <button type="button" class="btn bg-secondary btn-sm">Dados do motorista</button>
                            </a>
                            
                            <a class="mx-2 " href="{{ route('ativo.veiculo.manutencao.index', $locacao->veiculo_id) }}" title="Manutenções">
                                <button type="button" class="btn bg-info btn-sm">Manutenções</button>
                            </a>
                            
                            <a class="mx-2" href="{{ route('ativo.veiculo.locacaoVeiculos.editar', $locacao->id) }}">
                                <button type="button" class="btn bg-warning btn-sm"><i class="mdi mdi-pencil" title="Editar"></i></button>
                            </a>
                            
                            
        
                            @if (session()->get('usuario_vinculo')->id_nivel ?? 1 <= 2) 
                                <form class="m-0 p-0" action="{{ route('ativo.veiculo.locacaoVeiculos.delete', $locacao->id) }}" method="POST">
                                    @csrf
                                    @method('delete')
                                        <a class="excluir-padrao x" data-id="$locacao->id" data-table="empresas" data-module="cadastro/empresa">
                                            <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" type="submit" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o registro?')">
                                                <i class="mdi mdi-delete text-white"></i>
                                            </button>
                                        </a>
                                </form>   
                            @endif
                        </div>
                </div>
            </div>
        @endforeach
    </div>
   

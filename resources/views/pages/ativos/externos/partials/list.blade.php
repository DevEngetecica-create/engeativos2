<div class="card">

    <div class="card-header align-items-center d-flex">

        <div class="d-flex justify-content-start border-2 border-warning border-bottom mb-4 col-12 py-0">





            <p class="fs-6 pt-1 ml-3 bg-warning p-0 px-2">



                @if($totalAtivos)

                @foreach($totalAtivos as $totalAtivo)

                Total de Ativos: <strong>{{Tratamento::formatFloat($totalAtivo->totalAtivos)}}</strong>

                @endforeach

                @endif

            </p>



            <p class="fs-6 pt-1 ml-3 bg-warning p-0 px-2 mx-3">

                @foreach($valorTotalAtivos as $valorTotalAtivo)

                Valor Total de Ativos: <strong>R$ {{Tratamento::FormatBrMoeda($valorTotalAtivo->somaValorTotalFerramentasObra)}}</strong>

                @endforeach

            </p>

        </div>

    </div><!-- end card header -->



    <div class="row">



        <div class="d-flex flex-wrap mb-3">



            @foreach(GraficosAtivosExternos::countStatus() as $countStatu)

            <div class="d-flex align-items-center  @if ($countStatu->statusAtivo == 9)  col-sm-2 col-md-2 col-xl-2 @else col-sm-1 col-md-2 col-xl-2 @endif border rounded m-1 shadow-sm" style="height:40px !important;">

                <div class="info-box d-flex align-items-center mx-2">

                    <span class="info-box-icon"><i class="mdi mdi-checkbox-blank-circle mdi-18px text-{{$countStatu->classe}}"></i></span>

                    <div class="d-flex">

                        <span class="m-0 p-0 mx-2"><small>{{$countStatu->titulo}}:</small></span>

                        <span class="m-0 p-0"><small>{{$countStatu->totalStatus}}</small></span>

                        @if($countStatu->statusAtivo == 9)

                        <span class="btn btn-success m-0 p-0 mx-2 px-2" data-bs-toggle="modal" data-bs-target="#foraOperacao"><i class="mdi mdi-eye-outline mdi-18x"></i></span>

                        @endif

                    </div>

                </div>

            </div>

            @endforeach



        </div>



        <div class="card-body">



            <div class="live-preview">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover table-sm align-middle table-nowrap mb-0">

                        <thead>

                            <tr>

                                <th>ID</th>

                                <th>Obra</th>

                                <th class="text-center">Patrimônio</th>

                                <th style="max-width:30% !important">Título</th>

                                <th class="text-center">Valor</th>

                                <th class="text-center" style="max-width:180px !important">Calibração</th>

                                <th class="text-center" style="width: 250px;">Status</th>

                                <th class="text-center {{ (session()->get('usuario_vinculo')->id_nivel <= 2)? 'd-block' : 'd-none' }}">Ações</th>

                            </tr>

                        </thead>

                        <tbody>





                            @foreach ($ativos as $ativo)

                            <tr>

                                <td class="text-center">{{ $ativo->id}}</td>

                                <td>{{ $ativo->obra->codigo_obra ?? '-'}}</td>

                                <td>{{ $ativo->patrimonio ?? '-' }}</td>

                                <td>{{ $ativo->configuracao->titulo ?? ""}}</td>





                                <td class="text-center">R$ {{ Tratamento::currencyFormatBr($ativo->valor ?? "erro number") }}</td>

                                <td class="text-center">







                                    @if ($ativo->calibracao == "Não")

                                    <span class="px-3 rounded bg-primary text-white">Não</span>



                                    @else

                                    <span class='bg-secondary px-3 rounded text-white'>Sim</span>

                                    <a href="{{route('ativo.externo.calibracao', $ativo->id)}}">

                                        <span class='bg-info px-3 rounded ml-1 text-white'>Certificados</span>

                                    </a>



                                    @endif

                                </td>



                                <td class="text-left">



                                    <div class="d-flex justify-content-start">



                                        <div class="mx-1">

                                            <a href="javascript:void(0)">

                                                <span class="btn btn-sm btn-success edit-item-btn ItemsRetiradaHistorico" id="" data-id_retirada="{'id': {{$ativo->id}}}, {'titulo': '{{$ativo->configuracao->titulo}}', {'patrimonio': '{{$ativo->patrimonio}}'}" data-bs-toggle="modal" data-bs-target="#historicoRetiradaModal">

                                                    <i class="mdi mdi-format-float-left"></i>

                                                </span>

                                            </a>

                                        </div>



                                        <div>

                                            <span class="badge bg-{{ $ativo->situacao->classe ?? "sem reg." }}-subtle text-success text-uppercase">

                                                {{ $ativo->situacao->titulo ?? "sem reg." }}

                                            </span>

                                        </div>





                                        <div>



                                            @foreach($itensRetirados as $itemRetirado)



                                            @if($ativo->id == $itemRetirado->id_ferramenta_retirada && $ativo->status == 6 && $itemRetirado->statusRetirada == 2)



                                            <small>{{$itemRetirado->funcionario}}</small>



                                            @else



                                            @endif



                                            @endforeach



                                        </div>

                                    </div>

                                </td>



                                <!--  <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop">

                                <i class="mdi mdi-eye mdi-18px"></i>

                            </button> -->



                                <td class="  d-flex justify-content-center text-center {{ session()->get('usuario_vinculo')->id_nivel <= 2 ? 'd-block' : 'd-none' }}">



                                    <a class="mx-2 text-white" href="{{ route('ativo.externo.detalhes', $ativo->id) }}">

                                        <button type="button" class="btn btn-block bg-success btn-sm "><i class="mdi mdi-eye" title="Visualizar"></i></button>

                                    </a>





                                    <a class="mx-2 text-white" href="{{ route('ativo.externo.editar', $ativo->id) }}">

                                        <button type="button" class="btn btn-block bg-warning btn-sm "><i class="mdi mdi-lead-pencil" title="Editar"></i></button>

                                    </a>



                                    <button data-id="{{ $ativo->id }}" class="delete-ativo btn btn-danger btn-sm {{ session()->get('usuario_vinculo')->id_nivel <= 1 ? 'd-block' : 'd-none'  }}" title="Excluir"><i class="mdi mdi-delete"></i>
                                        Excluir
                                    </button>

                                </td>

                            </tr>







                            @endforeach



                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>



    <div class="row mt-3">

        <div class="d-flex justify-content-end col-sm-12 col-md-12 col-lg-12 ">

            <div class="paginacao">

                {{$ativos->render()}}

            </div>

        </div>

    </div>



    <!-- Modal -->

    <div class="modal fade" id="historicoRetiradaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-scrollable modal-lg">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

                <div class="modal-body">







                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Fechar</button>



                </div>

            </div>

        </div>

    </div>

    <div class="modal fade" id="foraOperacao" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="exampleModalLabel">Lista - Equipamentos Fora de Operacao</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

                <div class="modal-body">

                    <table class="table table-hover">

                        <thead>

                            <tr>

                                <th scope="col">Patrimonio</th>

                                <th scope="col" class="text-center">Nome</th>

                                <th scope="col" class="text-center">Ver</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach(GraficosAtivosExternos::statusForaOperacao() as $foraOperacao)

                            <tr class="m-0 p-0">

                                <td class="m-0 p-0 px-2">{{$foraOperacao->patrimonio}}</td>

                                <td class="m-0 p-0 px-2">{{$foraOperacao->configuracao->titulo}}</td>

                                <td class="text-center m-0 p-0">

                                    <a href="{{ route('ativo.externo.detalhes', $foraOperacao->ativo_externo->id) }}">

                                        <span class="btn btn-succes btn-sm"><i class="mdi mdi-eye mdi-18px"></i></span>

                                    </a>

                                </td>

                            </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Fechar</button>

                </div>

            </div>

        </div>

    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>







    <script>
        $(document).ready(function() {



            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': '{{ csrf_token()}}'

                }

            });





            $(".ItemsRetiradaHistorico").on('click', function() {



                // Removendo as aspas duplas da string

                var stringSemAspasDuplas = $(this).attr('data-id_retirada').replace(/"/g, '');



                // Removendo os colchetes e dividindo a string em substrings separadas por vírgulas

                var substrings = stringSemAspasDuplas.split(',');



                // Criando um novo array de objetos com as chaves id e titulo

                var id_retirada = [];



                substrings.forEach(function(substring) {

                    // Removendo os caracteres indesejados

                    var cleanSubstring = substring.replace(/[{}']/g, '');

                    // Separando a substring em chave e valor

                    var parts = cleanSubstring.split(':');

                    // Verificando se é a chave id ou titulo

                    var key = parts[0].trim();

                    var value = parts[1].trim();

                    console.log(key)

                    // Adicionando o objeto ao array

                    if (key === 'id') {

                        id_retirada.push({

                            id: value

                        });

                    } else if (key === 'titulo') {

                        id_retirada[id_retirada.length - 1].titulo = value;



                    } else if (key === 'patrimonio') {

                        id_retirada[id_retirada.length - 1].patrimonio = value;

                    }

                });





                console.log(id_retirada);





                $.ajax({

                    type: 'GET',

                    url: '/admin/ativo/externo/historico/' + id_retirada[0]['id'],

                    data: {},

                    success: function(result) {

                        $(".modal-title").html('Histórico de Retirada: ' + id_retirada[0]['patrimonio'] + ' - ' + id_retirada[0]['titulo'])

                        $(".modal-body").html(result)

                    }

                });

            });

        });
    </script>
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Detalhes da Manutenção Preventiva</h1>
        <div class="card">
            <div class="card-header">
                <h3>{{ $preventiva->nome_preventiva }}</h3>
                <a href="{{ route('veiculo_preventivas.index') }}" class="btn btn-primary">Voltar para a Lista</a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card-body">
                <form id="checklistForm" action="{{ route('veiculo_preventivas_checklist.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @php
                        $periodosArray = json_decode($preventiva->periodo, true);
                        $periodos = [];
                        foreach ($periodosArray as $p) {
                            $periodos = array_merge($periodos, array_map('trim', explode(',', $p)));
                        }
                        $periodos = array_unique($periodos);
                        sort($periodos);

                        $nomeServicos = json_decode($preventiva->nome_servico);
                        //$situacaoPreventiva json_decode($preventiva->situacao);
                        
                        $nomeServicosJson = json_encode($nomeServicos);
                    @endphp

                    <!-- Hidden fields for nome_servico and id_manut_preventiva -->
                    <input type="hidden" name="id_manut_preventiva" value="{{ $preventiva->id }}">
                    <input type="hidden" name="nome_servicos" value="{{ $nomeServicosJson }}">

                    @if($id_veiculo)
                        <input type="text" name="id_veiculo" value="{{ $id_veiculo }}">
                    @endif

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Nome do Serviço</th>
                                @foreach ($periodos as $p)
                                    @if(!$periodo || $periodo == $p)
                                        <th class="text-center">{{ $p }} Horas</th>
                                    @endif
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($nomeServicos as $key => $nome_servico)
                                <tr>
                                  
                                    @foreach ($periodos as $p)
                                        @if(!$periodo || $periodo == $p)   
                                            @php
                                                $periodoServico = array_map('trim', explode(',', json_decode($preventiva->periodo)[$key]));

                                               

                                                if (in_array($p, $periodoServico)) {
                                                    $situacao = json_decode($preventiva->situacao, true)[$key];
                                                    $checked = isset($checklistDataChecbox[$key][$p]) && $checklistDataChecbox[$key][$p] == "1";
                                                    $observacao = isset($checklistObservacao[$key][$p]) ? $checklistObservacao[$key][$p] : '';
                                                    $file = isset($files[$key][$p]) ? $files[$key][$p] : '';
                                                    
                                                   /*  echo '<pre>';
                                                    var_dump($checked);
 */
                                                    echo '<input type="hidden" name="situacaoPreventiva[]" value="'. $situacao .'">';
                                                    echo '<input type="hidden" name="nome_servicos[]" value="'. $nome_servico .'">';
                                                    echo ' <td>'. $nome_servico .'</td>';


                                                    echo '<td>';
                                                    switch ($situacao) {
                                                        case 1:
                                                            echo '<div class="d-flex align-items-center justify-content-center">
                                                                    <div class="form-check form-switch">
                                                                        <label class="form-check-label" for="obgr_' . $situacao . '_' . $p . '"><span style="font-size:18px;" title="Deve ser executado">&#9899;</span></label>
                                                                        <input style="width:40px; height:20px" type="checkbox" id="obgr_' . $key . '_' . $p . '" class="form-check-input checklist-checkbox" name="checklist[] "  >                                                         
                                                                        <input  type="hidden" id="periodo_' . $key . '_' . $p . '"  name="periodo" value="' . $p . '">
                                                                    </div>
                                                                    <div class="upload-btn-wrapper ms-2">
                                                                        <span class="btn-upload"><i class="fas fa-cloud-upload-alt" style="font-size:25px;" ></i></span>
                                                                        <input class="observacao disabled" type="file" name="file[]" id="file[' . $key . ']"/>                                                                     
                                                                    </div>
                                                                    <span data-bs-toggle="modal" data-bs-target="#executar' . $key . '" class="observacao text-secondary disabled mx-2" style="font-size:25px;" title="Observações"><i class="fas fa-comment-alt"></i></span>
                                                                </div>';

                                                            echo '<!-- Modal -->
                                                                    <div class="modal fade" id="executar' . $key . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="exampleModalLabel">' . $nome_servico . '</h5>
                                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div class="mb-3">
                                                                                        <label for="exampleFormControlTextarea1" class="form-label">Observações</label>
                                                                                        <textarea class="form-control" id="observacoes_' . $key . '" name="observacoes[]" rows="3"></textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Salvar</button>                                                                              
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>';
                                                            break;
                                                        case 2:
                                                            echo '<div class="d-flex align-items-center justify-content-center">
                                                                    <div class="align-items-center form-check form-switch">
                                                                        <label class="form-check-label mx-2" for="condicao_' . $situacao . '_' . $p . '"><span style="font-size:18px;" title="Deve ser executado conforme condição">&#9673;</span></label>
                                                                        <input style="width:40px; height:20px" type="checkbox" id="condicao_' . $key . '_' . $p . '" class="form-check-input checklist-checkbox" name="checklist[]"  >
                                                                        <input  type="hidden" id="periodo_' . $key . '_' . $p . '"  name="periodo" value="' . $p . '">
                                                                    </div>
                                                                    <div class="upload-btn-wrapper ms-2">
                                                                        <span class="btn-upload"><i class="fas fa-cloud-upload-alt" style="font-size:25px;"></i></span>
                                                                        <input class="observacao disabled" type="file" name="file[]" id="file[' . $key . ']"/>
                                                                    </div>
                                                                    
                                                                    <span data-bs-toggle="modal" data-bs-target="#condicao' . $key . '"class="observacao text-secondary disabled mx-2" style="font-size:25px;" title="Observações"><i class="fas fa-comment-alt text-secondary" title="Observações"></i></span>
                                                                </div>';

                                                            echo '<!-- Modal -->
                                                                <div class="modal fade" id="condicao' . $key . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">' . $nome_servico . '</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="mb-3">
                                                                                    <label for="exampleFormControlTextarea1" class="form-label">Observações</label>
                                                                                    <textarea class="form-control" id="observacoes_' . $key . '" name="observacoes[]" rows="3"></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancelar</button>                                                                              
                                                                            </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>';
                                                            break;
                                                        case 3:
                                                            echo '<div class="d-flex align-items-center justify-content-center">
                                                                    <div class="align-items-center form-check form-switch">
                                                                        <label class="form-check-label" for="verificar_' . $situacao . '_' . $p . '"><span style="font-size:25px;" title="Deve ser verificado">&#9650;</span></label>
                                                                        <input style="width:40px; height:20px" type="checkbox" id="verificar_' . $key . '_' . $p . '" class="form-check-input checklist-checkbox" name="checklist[]" >
                                                                        <input  type="hidden" id="periodo_' . $key . '_' . $p . '"  name="periodo" value="' . $p . '">
                                                                    </div>
                                                                    <span>' . htmlspecialchars($observacao) . '</span>
                                                                    <div class="upload-btn-wrapper ms-2">
                                                                        <span class="btn-upload"><i class="fas fa-cloud-upload-alt" style="font-size:25px;"></i></span>
                                                                        <input class="observacao disabled" type="file" name="file[]" id="file[' . $key . ']"/>                                                                       
                                                                    </div>
                                                                    <span data-bs-toggle="modal" data-bs-target="#verificar' . $key . '" class="observacao text-secondary disabled mx-2" style="font-size:25px;" title="Observações"><i class="fas fa-comment-alt" title="Observações"></i></span>
                                                                </div>';
                                                                    
                                                            echo '<!-- Modal -->
                                                                <div class="modal fade" id="verificar' . $key . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">' . $nome_servico . '</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="mb-3">
                                                                                    <label for="exampleFormControlTextarea1" class="form-label">Observações</label>
                                                                                   <textarea class="form-control" id="observacoes_' . $key . '" name="observacoes[]" rows="3"></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancelar</button>                                                                              
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>';
                                                            break;
                                                    }
                                                }  
                                                echo '</td>';
                                            @endphp     
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-primary">Salvar Checklist</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Inicialmente, desabilitar todos os ícones de observação
            $('.observacao').addClass('disabled');

            // Função para habilitar/desabilitar o ícone de observação
            function toggleObservacao(checkbox) {
                var target = $(checkbox).closest('td').find('.observacao');
                var target_upload = $(checkbox).closest('td').find('.btn-upload');

                if (checkbox.checked) {
                    target.removeClass('disabled');
                    target.removeAttr('readonly').removeClass('text-secondary disabled').addClass('text-warning');
                    target_upload.removeAttr('readonly').removeClass('text-secondary disabled').addClass('text-success');

                } else {
                    target.addClass('disabled');
                    target.attr('readonly', true).removeClass('text-warning').addClass('text-secondary disabled');
                    target_upload.attr('readonly', true).removeClass('text-success').addClass('text-secondary disabled');
                }
               
            }

            // Ação ao clicar na checkbox
            $('.checklist-checkbox').change(function() {
                toggleObservacao(this);
            });

            // Ajuste ao submeter o formulário
            $('#checklistForm').on('submit', function() {
                $('.checklist-checkbox').each(function() {
                    var checkbox = $(this);
                    if (checkbox.is(':checked')) {
                        checkbox.val(1);
                    } else {
                        checkbox.prop('checked', true);
                        checkbox.val(0);
                    }
                });
            });

            // Inicializar com base nos valores das checkboxes
            $('.checklist-checkbox').each(function() {
                toggleObservacao(this);
            });

            // Impedir o clique no ícone de observação se estiver desabilitado
            $(document).on('click', '.observacao.disabled', function(event) {
                event.preventDefault();
                return false;
            });
        });


    </script>
    <script>
        function validateFileInput(input) {
            const allowedExtensions = ['jpg', 'png', 'pdf', 'doc', 'docx'];
            const file = input.files[0];
            if (file) {
                const fileExtension = file.name.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(fileExtension)) {
                    alert("Extensão inválida. Extensões permitidas: " + allowedExtensions.join(', '));
                    input.value = ''; // Clear the input
                }
            }
        }
    </script>
@endsection


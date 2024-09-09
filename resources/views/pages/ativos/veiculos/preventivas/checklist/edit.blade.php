@extends('dashboard')

@section('content')
    <div class="container">
        <div class="card p-5">
            <h1 class="mb-3 ">Detalhes da Manutenção Preventiva</h1>
            <div class="card-header align-middle">
                <div class="row">
                    <div class="col-3"><a href="{{route('veiculo.show', $checklist->id_veiculo)}}" class="btn btn-info ">Voltar para a Lista</a></div>
                    <div class="col"><h3 >{{ $preventiva->nome_preventiva }}</h3>         </div>
                </div>      
            </div>
        <form action="{{ route('veiculo_preventivas_checklist.update', $checklist->id) }}" method="POST" enctype="multipart/form-data" id="checklistForm">
            @php
                $nomeServicos = json_decode($checklist->nome_servico, true) ?? [];

                $situacoesPreventivas = $checklist->situacaoPreventiva;
                $situacao_checklist = json_decode($checklist->situacao, true) ?? [];
               // $situacoes = $checklist->situacaoPreventiva;
                $observacao = json_decode($checklist->observacoes, true) ?? [];
                $file = json_decode($checklist->files, true) ?? [];
               
            @endphp
            <div class="card-body">                
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="id_manut_preventiva" value="{{ $preventiva->id }}">

                    <table class="table table-bordered align-middle table-sm">
                        <thead>
                            <tr>
                                <th class="text-center">Nome do Serviço</th>
                                <th class="text-center">{{ $checklist->periodo }} Horas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($nomeServicos as $key => $nome_servico)
                                <tr>
                                    <td>{{ $nome_servico }}</td>
                                    <td>
                                        @php
                                            $situacaoPreventiva = $situacoesPreventivas[$key] ?? 0;                                                
                                            $situacao = $situacao_checklist[$key] ?? '';

                                            //$situacao = $situacoes[$index] ?? 0;
                                            $observacaoAtual = $observacao[$key] ?? '';
                                            $fileAtual = $file[$key] ?? null;

                                            echo'<table class="table align-middle mb-0">
                                                    <thead class="table-light my-0">
                                                        <tr class="text-muted ">
                                                            <th class="py-2 text-center" scope="col">Situação</th>
                                                            <th class="py-2 text-center" scope="col" style="width: 20%;">Verificado?</th>
                                                            <th class="py-2 text-center" scope="col">Anexos</th>
                                                            <th class="py-2 text-center" scope="col" style="width: 16%;">Observações</th>                                                                                    
                                                        </tr>
                                                    </thead>
                                                    <tbody>';
                                                    
                                                        echo '<tr>';
                                                            switch ($situacaoPreventiva) {
                                                                case 1:
                                                                    echo'<td class="py-1">Deve ser executado &#9899;</td>';
                                                                break;

                                                                case 2:
                                                                    echo'<td class="py-1">Deve ser executado conforme condição &#9673;</td>';
                                                                break;


                                                                case 3:
                                                                    echo'<td class="py-1">Deve ser verificado &#9650;</td>';
                                                                break;
                                                            }

                                                            echo '<td class="py-1">
                                                                    <div class="form-check  text-center">
                                                                        <input style="width:40px; height:20px" type="checkbox" id="obgr_' . $key . '_' . $situacaoPreventiva . '" class="form-check-input checklist-checkbox" name="checklist[]" value="1" ' . ($situacao == 1 ? "checked" : ""). '>
                                                                      
                                                                    </div>
                                                                </td>
                                                            
                                                            <td class="py-1 text-center">
                                                                
                                                                <div class="upload-btn-wrapper ms-2" title="Anexar arquivo">                                                                                                                                                                          
                                                                    <label class="form-label btn-upload btn btn-outline-'. ($fileAtual != "" ? "success" : "secondary").' btn-sm btn-border px-4 py-0 mt-2 mb-0" for="' . $key . '"><i class="mdi mdi-cloud-upload-outline mdi-18px"></i></label>                                                                                       
                                                                    <input class="observacao disabled" type="file" name="file[]" id="' . $key . '"/>                                                                     
                                                                </div> 
                                                            </td>

                                                            <td class="py-1 text-center">
                                                                <span data-bs-toggle="modal" data-bs-target="#modal' . $key . '" class="btn-observacao  btn btn-outline-'. ($observacaoAtual != "" ? "success" : "secondary").' btn-sm btn-border px-4 py-0 mt-1 mb-0" title="Observações"><i class="mdi mdi-comment-edit-outline mdi-18px"></i></span>
                                                            </td>
                                                        </tr>';
                                                            
                                                echo '</tbody><!-- end tbody -->
                                                </table><!-- end table --> ';                                                    
                                                
                                                
                                                echo '<!-- Modal -->
                                                        <div class="modal fade" id="modal' . $key . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">' . $nome_servico . '</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="mb-3">
                                                                            <label for="exampleFormControlTextarea1" class="form-label">Observações</label>
                                                                            <textarea class="form-control" id="observacoes_' . $key . '" name="observacoes[]" rows="3">'. $observacaoAtual.'</textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Salvar</button>                                                                              
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>'; 
                                                echo '</td>';       
                                        @endphp

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-success">Salvar Checklist</button>                
            </div>
        </form>
        </div>
    </div>
<!-- Inclua o jQuery, o Bootstrap JS e o Toastr JS aqui -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Função para habilitar/desabilitar o ícone de observação e upload

        // Inicializar com base nos valores das checkboxes ao carregar a página
        $('.checklist-checkbox').each(function() {
            toggleObservacao(this);
        });

        function toggleObservacao(checkbox) {
            // Acessa a linha <tr> mais próxima do checkbox e encontra os elementos dentro dela
            var row = $(checkbox).closest('tr');

            var target_upload = row.find('.btn-upload');
            var target_observacao = row.find('.btn-observacao');
            var input_file = row.find('.observacao');

            if (checkbox.checked) {
              
                target_observacao.css('pointer-events', 'all').removeClass('btn btn-outline-secondary text-secondary').addClass('btn btn-outline-success');
                input_file.css('pointer-events', 'all');

                input_file.css('z-index',' -1');
                target_upload.css('z-index', '2');          
                
            } else {
                // Desabilitar interação
               
                target_observacao.css('pointer-events', 'none').removeClass('btn btn-outline-success text-success').addClass('btn btn-outline-secondary text-secondary');
                input_file.css('pointer-events', 'none');
            }
        }

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

        // Ação ao clicar na checkbox
        $('.checklist-checkbox').change(function() {
            toggleObservacao(this);
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
                    input.value = '';
                }
            }
        }
    </script>
@endsection

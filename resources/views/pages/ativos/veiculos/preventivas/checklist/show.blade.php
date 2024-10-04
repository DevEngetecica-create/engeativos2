@extends('dashboard')



@section('content')
    <div class="container">
        <h1>Detalhes da Manutenção Preventiva</h1>
        <div class="card">
            <div class="card-header">
                <h3>{{ $preventiva->nome_preventiva }}</h3>

                <a href="{{route('veiculo.show', $checklist->id_veiculo)}}" class="btn btn-primary mx-3">Voltar para a Lista</a>

            </div>
            <div class="card-body">
                
                    @php
                    
                    $nomeServicos = json_decode($checklist->nome_servico, true) ?? [];
                    $situacoesPreventivas = $checklist->situacaoPreventiva;
                    $situacao_checklist = json_decode($checklist->situacao, true) ?? [];
                    $observacao = json_decode($checklist->observacoes, true) ?? [];
                    $file = json_decode($checklist->files, true) ?? [];                              

                @endphp
                    <!-- Hidden fields for nome_servico and id_manut_preventiva -->
                 
                    <table class="table table-bordered align-middle table-sm">
                        <thead>
                            <tr>
                                <th class="text-center"  style="width: 20%;">Nome do Serviço</th>
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

                                         
                                            $observacaoAtual = $observacao[$key] ?? '';
                                            $fileAtual = $file[$key] ?? null;

                                            echo'<table class="table align-middle mb-0">
                                                    <thead class="table-light my-0">
                                                        <tr class="text-muted ">
                                                            <th class="py-2 text-center" >Situação</th>
                                                            <th class="py-2 text-center" >Verificado?</th>
                                                            <th class="py-2 text-center" >Anexos</th>
                                                            <th class="py-2 text-center" >Observações</th>                                                                                    
                                                        </tr>
                                                    </thead>
                                                    <tbody>';
                                                    
                                                        echo '<tr>';
                                                            switch ($situacaoPreventiva) {
                                                                case 1:
                                                                    echo'<td class="py-1" style="width: 20%;">Deve ser executado &#9899;</td>';
                                                                break;

                                                                case 2:
                                                                    echo'<td class="py-1" style="width: 20%;">Deve ser executado conforme condição &#9673;</td>';
                                                                break;
                                                                case 3:
                                                                    echo'<td class="py-1" style="width: 20%;">Deve ser verificado &#9650;</td>';
                                                                break;
                                                            }

                                                            echo '<td class="py-1" style="width: 15%;">
                                                                    <div class="form-check text-center">
                                                                        <input style="width:40px; height:20px; pointer-events: none;" type="checkbox" id="obgr_' . $key . '_' . $situacaoPreventiva . '" class="form-check-input checklist-checkbox" name="checklist[]" value="1" ' . ($situacao == 1 ? "checked" : ""). '>
                                                                      
                                                                    </div>
                                                                </td>
                                                            
                                                            <td class="py-1 text-center" style="width: 15%;">
                                                                
                                                                <div class="upload-btn-wrapper ms-2">'.

                                                                     ($fileAtual != 'null' ? '<a id="download' . $key . '" href="' . route('checklist.download', ['id' => $checklist->id, 'fileIndex' => $key]) . '">
                                                                                                <span class="btn btn-outline-success btn-sm btn-border px-4 py-0 mt-2 mb-0"> 
                                                                                                    <i class="mdi mdi-download" title="Baixar anexo"></i>
                                                                                                </span>
                                                                                            </a>' :  
                                                                                            '<span class="btn btn-outline-danger btn-sm btn-border px-4 py-0 mt-2 mb-0"  title="Não há arquivo anexado"><i class="mdi mdi-download mdi-18px"></i></span>')
                                                                     .'                                                                                                                                  
                                                                </div> 
                                                            </td>

                                                            <td class="py-1">'.
                                                                ($observacaoAtual ? '  <div class="card-body border-success border-start border-3"> <p class="mx-1"> '.$observacaoAtual.'</p> </div>' : '<span class=" btn btn-outline-secondary btn-sm btn-border px-4 py-0 mt-1 mb-0" title="Observações"><i class="mdi mdi-comment-edit-outline mdi-18px"></i></span>')
                                                                .

                                                            '                                                              
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
                
            </div>
        </div>
    </div>
<!-- Inclua o jQuery, o Bootstrap JS e o Toastr JS aqui -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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

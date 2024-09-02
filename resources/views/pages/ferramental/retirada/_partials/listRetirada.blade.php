
<div class="card-body" id="conteudo">
    <table class="table table-bordered table-hover" id="retirada-itens">
        <thead>
            <tr>
                <th >Obra</th>
                <th width="10%">Patrimônio</th>
                <th>Item</th>
                <th width="10%">Marcar/Desmarcar</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($estoques as $estoque)
            <tr @if($estoque->status == 6) style=" opacity: 0.5;" disabled="disabled" readonly="true" data-bs-toggle="tooltip" data-bs-placement="top" title="Equipamento bloque porque está em operação!!!" @endif >

                <td class="my-1">
                    {{ $estoque->obra->codigo_obra ?? '' }}
                </td>
                <td>
                    <span class="bg-primary px-1 rounded text-white">{{ $estoque->patrimonio }}</span>
                </td>

                <td>
                    <span >{{ $estoque->ativo_externo->titulo }}
                </td>

                <td class="text-center ">
                    <div>
                        <input class="checkbox-container"
                        @if($estoque->status == 6) style="opacity: 0.5;height:25px; width:25px" disabled="disabled" readonly="true" @endif
                        value="{{ $estoque->id }}"
                        class="form-check-input" 
                        id="id_ativo_externo_check{{ $estoque->id }}" 
                        name="id_ativo_externo_check[]" 
                        type="checkbox" 
                    >

                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="card-footer">
    <div class="paginacao mx-3">

        {{$estoques->onEachSide(2)->links()}}

    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        


        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        var search = $('#search').val();

        var spanPatrimonio;

        $(".checkbox-container").click(function(e) {
            var checados = [];
            $.each($("input[name='id_ativo_externo_check[]']:checked"), function() {

                checados.push($(this).val());



            });

            if (search != "") {
                spanPatrimonio = $('#patrimonio').val() + "," + $(this).val();
            } else {

                spanPatrimonio = checados.join(",");
            }

            var divDetalhes = document.getElementById('ferramentasSeleciondas');
            var novoInput = $(this).val();

            $('#id_ativo_externo_check' + $(this).val()).change(function(event) {
                event.preventDefault();

                var isChecked = $(this).prop('checked');

                if (isChecked) {

                } else { // Código para quando o checkbox é desmarcado

                    var valorParaRemover = $(this).val();
                    var index = checados.indexOf(valorParaRemover);

                    if (index !== -1) {
                        checados.splice(index, 1);
                    }

                    $('#' + $(this).val()).remove();
                    $('#div' + $(this).val()).remove();


                }
            });

            var detalhesButtons = document.querySelectorAll("#id_ativo_externo_check" + $(this).val());
            //console.log(detalhesButtons)
            //let patrimonio;

            // Adicione um evento de clique a cada botão "Detalhes"
            detalhesButtons.forEach(function(button) {
                button.addEventListener('change', function() {
                    // Encontre a linha pai (tr) da célula de botão clicada
                    var row = button.closest('tr');

                    // Obtenha os dados da linha da tabela
                    var id = row.cells[0].textContent;
                    var patrimonio = row.cells[1].textContent;
                    var titulo = row.cells[2].textContent;

                    //passa os dados para o elemento html de acordo com o seu ID
                    var spanPatrimonio = document.getElementById('id_ativo_externo' + $(this).val());
                    spanPatrimonio.textContent = patrimonio + " - " + titulo;

                    /*  var spanTitulo = document.getElementById('titulo'+$(this).val());
                     spanTitulo.textContent = titulo; */

                });
            });


            var divDetalhes = `
           
                <div class="col-5 m-0 mx-1 my-2" id="div${$(this).val()}" >
                
                    <span type="button" id="${$(this).val()}" class="btn btn-primary position-relative">

                    <span class=" p-0 m-0" id="id_ativo_externo${$(this).val()}" style="font-size:0.8rem"></span>              

                    <input type="hidden" value="${$(this).val()}" name="id_ativo_externo[]">

                        <span class="position-absolute top-0 start-100 badge rounded-circle bg-success p-0">
                        
                        <i class="mdi mdi-check-circle"></i>
                            
                        </span>
                    </span>               
                </div>
            `;

            $('#ferramentasSeleciondas').append(divDetalhes);
            //console.log(spanPatrimonios)

            //  console.log(search)



        });
    });
</script>
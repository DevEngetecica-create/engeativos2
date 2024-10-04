@extends('dashboard')
@section('title', 'Ativos Externos - Detalhes')
@section('content')

<link href="http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link href="http://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<link href="http://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap4.min.css">

<style>
    #qrcode img {
      width:300px;
      height:300px;
    }
    
    #qrcode1 img {
      width:100px;
      height:100px;
    }
</style>


<div class="page-header mb-5">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Ativo Interno
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Ativos <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>
        @if(session('mensagem'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('mensagem') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        
<div class="row mb-4">
     <div class="col-md-6">

        <div class="card card-info mb-3">
            <div class="card-header bg-warning">
                <h3 class="card-title text-black">Detalhes</h3>
            </div>

           <dl class="row">
               
                <dt class="col-sm-2 my-3">ID:</dt>
                <dd class="col-sm-9 my-3">{{ $data->id }}</dd>
                
                <dt class="col-sm-2 my-3">TITULO:</dt>
                <dd class="col-sm-9 my-3"><h5 class="card-title">{{ $data->titulo }}</h5></dd>  
            
                <dt class="col-sm-2 my-3">PATRIMONIO:</dt>
                <dd class="col-sm-9 my-3"> <h5 class="card-title">{{ $data->patrimonio }}</h5></dd>

                <dt class="col-sm-2 my-3">QRCode</dt>
                <dd class="col-sm-9 my-3">
                    <div class="d-flex align-items-center col-3">
                       <input id="link" type="hidden" value="https://sga-engeativos.com.br/admin/ativo/interno/1/show" style="width:80%" /><br />
                        <div id="qrcode1"></div>
                    </div>
                </dd>               
                
                <dt class="col-sm-2 my-3">Etiqueta</dt>
                <dd class="col-sm-9 my-3">
                    <div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#etiqueta">
                          Imprimir etiqueta
                        </button>
                    </div>
                </dd> 
            </dl>
      
           <dl class="row">
               
                <dt class="col-sm-2 my-3">Imagem</dt>
                <dd class="col-sm-9 my-3">
                    
                     <div class="form-group my-3">
                            @if($data->imagem)
                            <img src="{{url('storage/imagem_ativo_interno')}}/{{$data->imagem}}" id="target" class="img-thumbnail" class="img-fluid" style="max-width: 500px; height: autopx;">
                            @else
                            <img src="{{url('storage/imagem_ativo_interno/nao-ha-fotos.png')}}" id="target" class="img-thumbnail" class="img-fluid" style="min-width: 500px; min-height: 300px;">
                            @endif
                        </div>
                        
                </dd>
                
                <dt class="col-sm-2 my-3">Criado em:</dt>
                <dd class="col-sm-9 my-3">{{ Tratamento::datetimeBr($data->created_at) }}</dd> 
                
                
                <dt class="col-sm-2 my-3">Nota Fiscal</dt>
                <dd class="col-sm-9 my-3">{{$data->arquivo}}</dd> 
                
                <dt class="col-sm-2 my-3"></dt>
                <dd class="col-sm-9 my-3">
                    <div>
                        
                        <a href="{{ route('ativo.interno.download', $data->id) }}">
                           <button type="button" class="btn btn-primary" >
                              Baixar NF
                            </button>
                        </a>
                        
                        <a href="{{ route('anexo.interno.destroy', $data->id) }}">
                            <button type="button" class="btn btn-danger" >
                              <span class="mdi mdi-delete"> Excluir NF
                            </button>
                        </a>
                
                        
                    </div>
                </dd> 
            </dl>
        </div>
        

    </div>
    
    
     <div class="col-md-6">
         
        {{-- ANEXOS--}}
        @include('pages.ativos.internos.partials.form-anexos')

    </div>

</div>


<!-- Modal -->
<div class="modal fade" id="etiqueta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Etiquetas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body align-middle">
            <table id="etiquetasIndividuais" style="margin-left:25px; top:-5px; width: 90%;height:100px !important; border-width: 2px; border-style: solid; border-color: black;">
                <tr>
                    <input id="text" type="hidden" value="https://sga-engeativos.com.br/admin/ativo/interno/{{$data->id}}/show">
                    
                    <td style="padding: 0px;">
                        <div id="qrcode"></div>
                    </td>
                    
                    
                    <td style="padding: 0px;">
                        <span style="font-size: 40px; text-transform: uppercase; padding-left:20px"><strong>{{ $data->patrimonio }}</strong></span><br>
                        <span style="font-size: 40px; text-transform: uppercase; padding-left:20px"><strong>{{ $data->titulo }} </strong></span> <br>
                        <span style="font-size: 40px; text-transform: uppercase; padding-left:20px"> <strong>www.engetecnica.com.br</strong></span>
                    </td>
                </tr>
            </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" id="print-button-Individual" class="btn btn-primary">Imprimir</button>
      </div>
    </div>
  </div>
</div>


    
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>
<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>


<script>
    $(document).ready(function() {
        $(document).on("click", ".ferramenta", function() {

            var dados = $('#form').serialize();

            var id_ativo_externo = $(this).data('id');


            $(".modal-body #id_item").val(id_ativo_externo);

            //ativo/externo//detalhes/anexoDocsAtivos

            $.ajax({
                    url: "{{ url('admin/ativo/externo/anexo') }}/" + id_ativo_externo,
                    type: 'get',
                    data: {

                    }
                })
                .done(function(result) {
                    $("#lista_anexo").html(result)
                })
                .fail(function(jqXHR, textStatus, result) {

                });
        });


    });
</script>

<script>
    $(document).ready(function() {
        
      var qrcode = new QRCode("qrcode1");

        function makeCode () {    
          var elText1 = document.getElementById("link");
          
          if (!elText1.value) {
            alert("Input a text");
            elText1.focus();
            return;
          }
          
          qrcode1.makeCode(elText1.value);
        }
        
        makeCode();
        
        $("#link").
          on("blur", function () {
            makeCode();
          }).
          on("keydown", function (e) {
            if (e.keyCode == 13) {
              makeCode();
            }
          });
          
          
          
        
        var qrcode = new QRCode("qrcode");

        function makeCode () {    
          var elText = document.getElementById("text");
          
          if (!elText.value) {
            alert("Input a text");
            elText.focus();
            return;
          }
          
          qrcode.makeCode(elText.value);
        }
        
        makeCode();
        
        $("#text").
          on("blur", function () {
            makeCode();
          }).
          on("keydown", function (e) {
            if (e.keyCode == 13) {
              makeCode();
            }
          });
  
  
        document.getElementById('print-button-Individual').addEventListener('click', function() {
            // Captura o conteúdo da etiqueta como uma imagem usando html2canvas imagemQRCode


            html2canvas(document.getElementById('etiquetasIndividuais')).then(function(canvas) {
                // Imprime a imagem usando Print.js
                printJS({
                    printable: canvas.toDataURL('image/png'),
                    type: 'image',
                });
            });
        });


        // Encontre todos os botões "Detalhes"
        var detalhesButtons = document.querySelectorAll('#imprimirEtiqueta');

        // Encontre a <div> onde os detalhes serão exibidos
        var divDetalhes = document.getElementById('etiquetasIndividuais');

        // Adicione um evento de clique a cada botão "Detalhes"
        detalhesButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                // Encontre a linha pai (tr) da célula de botão clicada
                var row = button.closest('tr');

                // Obtenha os dados da linha
                var id = row.cells[0].textContent;
                var patrimonio = row.cells[1].textContent;
                var titulo = row.cells[2].textContent;

                var spanPatrimonio = document.getElementById('patrimonio');
                spanPatrimonio.textContent = patrimonio;


                // Preencha a <div> com os dados da linha
                /*  divDetalhes.innerHTML = `    
                     <p>Patrimônio: ${patrimonio}</p>
                     <p>Título: ${titulo}</p>
                 `; */
            });
        });

    });
</script>
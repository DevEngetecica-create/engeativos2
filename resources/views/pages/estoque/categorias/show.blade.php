@extends('dashboard')

@section('title', 'Produtos do Estoque')



@section('content')



<div class="row my-3 bg-white py-3 shadow ">

  <div class="col-3 active">

    <h5 class="page-title text-left m-0">

      <a class="btn btn-success btn-sm" href="{{ route('ativo.estoque.index') }}">

        <i class="mdi mdi-arrow-left icon-sm align-middle text-white"></i> Voltar

      </a>

    </h5>

  </div>



  <div class="col-6 breadcrumb-item active" aria-current="page">

    <h5 class="page-title text-center">

      <span class="page-title-icon bg-gradient-primary me-2">

        <i class="mdi mdi-access-point-network menu-icon"></i>

      </span> Editar Produtos do Estoque

    </h5>

  </div>

</div>



<div class="row">

  <div class="col-md-12 grid-margin stretch-card">

    <div class="card">

      <div class="card-body">

        @if ($errors->any())

        <div class="alert alert-danger">

          <strong>Ops!</strong><br><br>

          <ul>

            @foreach ($errors->all() as $error)

            <li>{{ $error }}</li>

            @endforeach

          </ul>

        </div>

        @endif





        <form method="post" action="{{ route('ativo.estoque.update', $protudo_etoque->id) }}" enctype="multipart/form-data">

          @csrf

          <div class="row">

            <div class="col-sm-12 col-md-5">

              <div class="card">

                <div class="card-header">

                  <h3 class="card-title">Dados do Produto</h3>

                </div>



                <div class="card-body">



                  <div class="form-group mb-2">



                    <label><strong>Obra: </strong></label>



                    @foreach ($obras as $obra)

                    <span>{{ $protudo_etoque->obra->id == $obra->id ?  $obra->codigo_obra : '' }}</span>

                    @endforeach



                  </div>



                  <div class="form-group mb-2">

                    <label for="obra_id"><strong>Fornecedor: </strong></label>



                    @foreach ($fornececedores as $fornecedor)

                    <span> {{ $protudo_etoque->id_fornecedor == $fornecedor->id ? $fornecedor->nome_fantasia : '' }}</span>

                    @endforeach

                    </select>

                  </div>



                  <div class="form-group">

                    <label class="form-label" for="marca"><strong>Marca: </strong> </label>





                    @foreach ($marcas as $marca)

                    <spna>{{ $protudo_etoque->id_marca == $marca->id ? $marca->marca : '' }}</option>

                      @endforeach





                  </div>



                  <div class="form-group mb-2">

                    <label class="form-label" for="id_categoria"><strong>Categoria: </strong></label>

                    @foreach ($ativo_configuracoes as $configuracoes)

                    <span>{{ $protudo_etoque->id_categoria == $configuracoes->id ? $configuracoes->titulo : '' }}</span>

                    @endforeach

                    </select>

                  </div>



                  <div class="form-group mb-2">

                    <label class="form-label" for="nome_produto"><strong>Título: </strong></label>

                    <span> {{$protudo_etoque->nome_produto}}</span>

                  </div>



                  <div class="row">



                    <div class="form-group mb-2 col-sm-6 col-md-6 col-lg-2 text-center">

                      <label class="form-label" for="estoque_minimo"><strong>Est. minímo</strong></label>

                      <span>{{$protudo_etoque->estoque_minimo}}</span>

                    </div>



                    <div class="form-group mb-2 col-sm-6 col-md-6 col-lg-2 text-center">

                      <label class="form-label" for="quantidade"><strong>Qtde. inic.: </strong></label>

                      <span>{{$protudo_etoque->quantidade}}</span>

                    </div>



                    <div class="form-group mb-2 col-sm-6 col-md-6 col-lg-2 text-center">

                      <label class="form-label" for="unidade"><strong>Unidade: </strong></label>

                      <span>{{$protudo_etoque->unidade}}</span>

                    </div>



                    <div class="form-group mb-2 col-sm-6 col-md-6 col-lg-2 text-center">

                      <label class="form-label" for="valor_unitario"><strong>Valor unit.: </strong></label>

                      <span>{{$protudo_etoque->valor_unitario }}</span>

                    </div>



                    <div class="form-group mb-2 col-sm-6 col-md-6 col-lg-2 text-center">

                      <label class="form-label" for="valor_total"><strong>Valor total: </strong></label>

                      <span>{{$protudo_etoque->valor_total}} </span>

                    </div>

                  </div>



                  <div class="row" id="epis">



                    <h6>Epis</h6>

                    <hr>



                    <div class="form-group col-sm-6 col-md-6 col-lg-3 mb-2 text-center">

                      <label class="form-label" for="cert_aut"><strong>Nº cert. de auto.: </strong></label>

                      <span>{{$protudo_etoque->cert_aut ?? old('cert_aut')}}</span>

                    </div>



                    <div class="form-group col-sm-6 col-md-6 col-lg-3 mb-2 text-center">

                      <label class="form-label" for="num_lote"><strong>Núm do lote: </strong></label>

                      <span>{{$protudo_etoque->num_lote }}</span>

                    </div>



                    <div class="form-group col-sm-6 col-md-6 col-lg-4 mb-2 text-center">

                      <label class="form-label" for="data_validade"><strong>Data de validade do EPI: </strong></label>

                      <span>{{$protudo_etoque->data_validade }}</span>

                    </div>



                    <!-- <div class="col-sm-12 col-md-12 col-lg-12 mb-2">

                                            <label for="formFile" class="form-label">Arquivo do CA</label>

                                            <input class="form-control" type="file" id="arquivo_cert_aut" value="{{$protudo_etoque->arquivo_cert_aut ?? old('arquivo_cert_aut')}}" name="arquivo_cert_aut">

                                        </div> -->



                    <hr>





                  </div>



                  <div class="row mb-2">

                    <div class="form-group col-sm-6 col-md-6 col-lg-6 text-left">

                      <label class="form-label" for="status_produto"><strong>Situação: </strong></label>

                      <span>{{$protudo_etoque->status_produto }}</span>



                    </div>

                    <div class="form-group">

                      <label for="formFile" class="form-label"><strong>Núm. da nota fiscal: </strong></label>

                      <span>{{$protudo_etoque->titulo_nf ?? "sem reg" }}</span>

                    </div>

                  </div>



                </div>

              </div>

            </div>



            <div class="col-sm-12  col-md-4">

              <div class="card mb-2">

                <div class="card-header">

                  <h3 class="card-title">Imagem do Produto</h3>

                </div>

                <div class="card-body">

                  <div class="row">



                    <div class="form-group my-3">



                      <img src="{{ URL::asset('imagens/estoque/nao-ha-fotos.png')}}" id="target" class="img-thumbnail" style="max-width: 300px; max-height: 300px;">



                    </div>

                  </div>



                </div>

              </div>

            </div>

            <div class="col-sm-12  col-md-3">

              <div class="card mb-2">

                <div class="card-header">

                  <h3 class="card-title">Arquivos</h3>

                </div>

                <div class="card-body">



                  <!-- Hoverable Rows -->

                  <table class="table table-hover table-nowrap mb-0">

                    <thead>

                      <tr>

                        <th scope="col">Arquivo</th>

                        <th class="text-center" scope="col">Ações</th>

                      </tr>

                    </thead>

                    <tbody>

                      @foreach($anexos as $anexo)

                      <tr>

                        <td>{{$anexo->nome_arquivo}}</td>

                        <td class="text-center">                        

                          <a href="" title="Excluir"><i class="mdi mdi-delete mdi-18px text-danger"></i></a>

                        </td>

                      </tr>

                      @endforeach

                    </tbody>

                  </table>











                </div>

              </div>

            </div>



            <div class="card-footer mt-2">

              <button class="btn btn-primary btn-md font-weight-medium" type="submit">Salvar</button>



              <a href="{{ route('ativo.estoque.index') }}">

                <button class="btn btn-danger btn-md font-weight-medium" type="button">Cancelar</button>

              </a>

            </div>

        </form>

      </div>

    </div>

  </div>

</div>





@endsection



@section('script')

<!--SCRIPT PARA CARREGAR IMAGEM PRINCIPAL -->

<script type="text/javascript">

  function carregarImg() {



    var target = document.getElementById('target');

    var file = document.querySelector("input[type=file]").files[0];

    var reader = new FileReader();



    reader.onloadend = function() {

      target.src = reader.result;

    };



    if (file) {

      reader.readAsDataURL(file);





    } else {

      target.src = "";

    }

  }

</script>



@endsection
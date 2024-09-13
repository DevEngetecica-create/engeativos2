@extends('dashboard')
@section('title', 'Ativos Externos')
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Incluir novo Ativo Externo
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Ativos <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
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

                <form method="post" action="{{ route('ativo.externo.store') }}" enctype="multipart/form-data">
                    @csrf

                    @include('pages.ativos.externos.partials.form')

                    <hr class="dropdown-divider">

                    <div class="d-flex row mt-3">
                        <div class="form-group col-md-6">
                            <div>
                                <label for="formFile" class="form-label">
                                    <h5> Imagem (300 x 300)</h5>
                                </label>
                                <input class="form-control" type="file" name="imagem" id="imagem" value="{{ $estoque->imagem ??  old('imagem') }}" onChange="carregarImg()">
                                <span class="text-danger">Extensões de imagens permitidas = 'png', 'jpg', 'jpeg', 'gif'</span>
                            </div>

                        </div>
                        <div class="form-group col-md-6 my-3">

                            <img src="{{url('storage/imagem_ativo/nao-ha-fotos.png')}}" id="target" class="img-thumbnail" style="width: 500px; height: 300px;">

                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <button class="btn btn-primary btn-md font-weight-medium" type="submit">Salvar</button>
                        <a href="{{ url('admin/ativo/externo') }}">
                            <button class="btn btn-warning btn-md font-weight-medium" type="button">Cancelar</button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL INCLUSAO RAPIDA DE OBRAS --}}
@include('pages.cadastros.obra.partials.inclusao-rapida')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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


<script>
    $(document).ready(function() {

        $('#calibracao').change(function() {
            var selectValue = $(this).val(); // Obtém o valor selecionado

            // Verifica o valor selecionado
            if (selectValue == "Sim") {
                document.getElementById("div_calibracao").style.display = 'flex';
                document.getElementById("menssagem_alert").style.display = ' flex'

                var valorQtde = $('#quantidade').val();

                if (selectValue == "Sim" && valorQtde > 1) {

                     alert("Só é possivel cadastrar um item calibrado por vez");

                    document.getElementById("quantidade").value = 1;

                    var x = document.getElementById("quantidade").value = 1;

                    console.log(x)
                }

            } else {
                document.getElementById("div_calibracao").style.display = 'none';
                document.getElementById("menssagem_alert").style.display = ' none'
            }
        });

    });

    function valorQuantidade(inputElement) {

        // Obtenha o valor do input quantidade
        var valorQtde = inputElement.value;

        if ($('#calibracao').val() == "Sim" && valorQtde > 1) {

            alert("Só é possivel cadastrar um item calibrado por vez");

            inputElement.value = 1;
        }

    }
</script>
@endsection
@extends('dashboard')
@section('title', 'Minha Conta')
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Minha Conta
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Configurações <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>


<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <form method="post" enctype="multipart/form-data" action="{{ route('minhaconta.store') }}">
                    @csrf
                    <div class="col-md-6">
                        <label for="email" class="form-label">E-mail / Usuário</label>
                        <input type="email" class="form-control" id="email" value="{{ Auth::user()->email }}" disabled>
                    </div>
                    <div class="col-md-3">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="col-md-3">
                        <label for="password_confirm" class="form-label">Confirmar Senha</label>
                        <input type="password" class="form-control" id="password_confirm" name="password_confirm">
                    </div>
                    <div class="col-12">
                        <label for="nome" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="nome" value="{{ Auth::user()->name }}" name="nome">
                    </div>
                    <?php if (isset(Auth::user()->user_level) && Auth::user()->user_level == 1) { ?>
                        <div class="col-12">
                            <label for="nivel" class="form-label">Tipo de Usuário</label>
                            <select class="form-select" id="nivel" name="nivel">
                                <option value="">Escolha o Tipo de Usuário</option>
                                @foreach($usuario_niveis as $nivel)
                                <option value="{{ $nivel->id }}" @php if(isset(Auth::user()->user_level) && Auth::user()->user_level == $nivel->id){
                                    echo "selected='selected'";
                                    }
                                    @endphp
                                    >{{ $nivel->titulo }}</option>
                                @endforeach
                            </select>
                        </div>
                    <?php } ?>

                    <div class="col-12">
                        <button type="submit" class="btn btn-gradient-primary btn-lg font-weight-medium">Salvar</button>

                        <a href="{{ url('dashboard') }}">
                            <button type="button" class="btn btn-gradient-danger btn-lg font-weight-medium">Cancelar</button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>





@endsection
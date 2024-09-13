@extends('dashboard')
@section('title', 'Configurações')
@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary me-2 text-white">
                <i class="mdi mdi-access-point-network menu-icon"></i>
            </span> Sistema
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

                    <form method="post" action="{{ route('config.update') }}">
                        @csrf

                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label" for="title">Título da Aplicação</label>
                                <input class="form-control @error('title') is-invalid @enderror" id="title" name="title" type="text" value="{{ $config->title ?? old('title') }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="email">E-mail de Origem Notificações</label>
                                <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ $config->email ?? old('email') }}" required>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label" for="notifications">Enviar notificações e e-mails?</label>
                                <select class="form-control form-select @error('notifications') is-invalid @enderror" id="notifications" name="notifications" required>
                                    <option value="1">Sim</option>
                                    <option value="0" {{ $config->notifications == 0 ? 'selected' : '' }}>Não</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label" for="alerts">Alertas</label>
                                <select class="form-control form-select @error('alerts') is-invalid @enderror" id="alerts" name="alerts" required>
                                    <option value="0">Próxima Revisão por KM</option>
                                    <option value="1" {{ $config->alerts == 1 ? 'selected' : '' }}>Próxima Revisão por Tempo</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <button class="btn btn-gradient-primary btn-lg font-weight-medium" type="submit">Salvar</button>

                            <a href="{{ url('dashboard') }}">
                                <button class="btn btn-gradient-danger btn-lg font-weight-medium" type="button">Cancelar</button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

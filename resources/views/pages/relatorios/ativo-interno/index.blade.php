@extends('dashboard')
@section('title', 'Veículo')
@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-access-point-network menu-icon"></i>
            </span> Relatórios
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Cadastros <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
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

                    <form method="post" enctype="multipart/form-data"
                        action="{{ route('relatorio.ativo.interno.gerar') }}">
                        @csrf
                        <div>
                            <h1>Gerar veículos</h1>
                        </div>
                        {{-- <div class="row">
                            <div class="col-md-10">
                                <label for="obra" class="form-label">Obra</label>
                                <select name="obra" id="obra" class="form-select">

                                    <option value="" selected>Selecione</option>

                                    <option value="">AAA</option>

                                </select>
                            </div>
                        </div> --}}

                        <div class="col-12 mt-5">
                            <button type="submit" class="btn btn-gradient-primary btn-lg font-weight-medium">
                                Gerar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

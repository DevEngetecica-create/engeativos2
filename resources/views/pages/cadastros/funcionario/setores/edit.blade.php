@extends('dashboard')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body p-5">
                <h1>Cadastrar Setor</h1>
                <div class="row mt-5">
                    @if ($errors->any())
                        <!-- Danger Alert -->
                        <div class="alert alert-warning alert-dismissible fade show material-shadow" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                </div>
                <form action="{{ route('cadastro.funcionario.setores.update', $funcionario->id) }}" method="POST">
                    @csrf

                    @csrf
                    <div class="form-group">
                        <label for="nome_setor">Nome do Setor:</label>
                        <input type="text" name="nome_setor" class="form-control" value="{{ $funcionario->nome_setor ?? old('nome_setor') }}">                        
                    </div>

                    <button class="btn btn-success btn-ms mt-4" type="submit">Salvar</button>

                </form>

            </div>
        </div>
    </div>

@endsection

@extends('dashboard')
@section('title', 'OnDrive')
@section('content')


    <div class="row">
        <div class="col-2 breadcrumb-item active" aria-current="page">
            <h3 class="page-title text-center">
                <span class="page-title-icon bg-gradient-primary me-2">
                    <i class="mdi mdi-account-hard-hat  mdi-24px"></i>
                </span> OnDrive
            </h3>
        </div>

    </div>

    <hr>

    <div class="col-10">
        <form action="{{ route('upload.file') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- Isso simula o método PUT para a rota -->
            <div class="card">
                <div class="card-body">    
                    <div class="col-6 text-left  m-0 p-0 mb-2 mx-2">
                        <label for="folder_name">Nome da Pasta:</label>
                        <input type="text" class="form-control shadow" name="folder_name" id="folder_name" required>
                    </div>
    
                    <div class="col-6 text-left  m-0 p-0 mb-2 mx-2">
                        <label for="file">Escolha um arquivo:</label>
                        <input type="file" class="form-control shadow" name="file" id="file" required>
                    </div>
    
                    <button class="btn btn-success" type="submit">Enviar Arquivo</button>
    
                </div>
            </div>
        </form>
    </div>
    

@endsection

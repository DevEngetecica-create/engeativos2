@extends('layouts.master-without-nav')
@section('title')
@lang('translation.signup')
@endsection
@section('content')

<div class="auth-page-wrapper">
    <!-- auth page bg -->
    <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
        <div class="bg-overlay"></div>

        <div class="shape">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
                <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
            </svg>
        </div>
    </div>

    <!-- auth page content -->
    <div class="auth-page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mt-sm-5 mb-2 text-white-50">
                        <div>
                            <a href="index" class="d-inline-block auth-logo">
                                <img src="{{ URL::asset('build/images/icones/Engeativos Logo C.png')}}" alt="" height="100">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
            <div class="row justify-content-beetwen">     
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card mt-1">
                        <div class="card-body p-2">
                            <div class="text-center mt-2">
                                <h5 class="text-primary">Cadastro de usuários</h5>                               
                            </div>
                            <div class="p-2 mt-2">
                                <form class="needs-validation" novalidate method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="useremail" class="form-label">Email: <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="useremail" placeholder="Insira o seu email da Engetecnica" required>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Este e-mail já esta cadastrado</strong>
                                        </span>
                                        @enderror
                                        <div class="invalid-feedback">
                                            Por favor insira um email
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Nome Completo: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"  id="username" required>
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <div class="invalid-feedback">
                                            Por favor insira o seu nome Completo
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="userpassword" class="form-label">Senha: <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="userpassword"  placeholder="A sua senha deve ter no minimo 8 caracteres" required>
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <div class="invalid-feedback">
                                            Por favor insira a sua senha
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="input-password">Confirmar a senha: <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" id="input-password" placeholder="Confirmar senha" required>

                                        <div class="form-floating-icon">
                                            <i data-feather="lock"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="nivel_acesso" class="form-label">Nível de acesso: <span class="text-danger">*</span></label>
                                     
                                        <select class="form-select form-select2 form-select-md" aria-label=".form-select-sm example"  name="nivel_acesso" id="nivel_acesso" required>>
                                            <option selected>Selecione o seu nível de acesso</option>
                                            <option value="4">Usuario</option>
                                            <option value="5">Adm de Obra</option>
                                            <option value="2">Almoxarifado</option>
                                            <option value="11">Cliente</option>
                                            <option value="10">Coordenador(a) de SMS</option>
                                            <option value="13">Encarregado de Manutenção do Veículos</option>
                                            <option value="6">Engenheiro/ Coordenador de Obra</option>
                                            <option value="12">Fornecedor</option>
                                            <option value="15">Gerente SMS</option>
                                            <option value="3">Qualidade</option>
                                            <option value="8">Técnico de Meio Ambiente</option>
                                            <option value="9">Técnico de Qualidade</option>
                                            <option value="8">Técnico de Segurança</option>
                                            <option value="14">Técnico de Segurança do Trabalho</option>
                                        </select>
                                    </div>

                                
                                {{--
                                    <div class="mb-3">
                                        <label for="matricula" class="form-label">Matricula: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('matricula') is-invalid @enderror" name="matricula" id="matricula" placeholder="Matricula do cartão ponto" required>
                                        @error('matricula')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Esta matricula já esta cadastrada</strong>
                                        </span>
                                        @enderror
                                        <div class="invalid-feedback">
                                            Por favor insira a sua matricula
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="input-avatar">Foto <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @error('avatar') is-invalid @enderror" name="avatar" id="input-avatar" required>
                                        @error('avatar')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <div class="">
                                            <i data-feather="file"></i>
                                        </div>
                                    </div>--}}

                                    <div class="mt-3">
                                        <button class="btn btn-success w-100" type="submit">Cadastrar-se</button>

                                    </div>
                                    <div class="text-center mt-2">
                                        <p class="mb-0">Já tem uma conta? <a href="{{ route('login') }}" class="fw-semibold text-primary text-decoration-underline"> Acessar </a> </p>
                                    </div>


                                </form>

                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->
                </div>

                <div class="col-sm-12 col-lg-3 col-xl-7 ">
                    <ul class="list-group mx-sm-1 mx-md-2 mx-lg-5 mx-xxl-5">
                        <li class="list-group-item disabled text-center" aria-disabled="true">Requisitos para a senha</li>
                        <li class="list-group-item">Não pode ser NÚMEROS sequenciais. <span class="text-danger">Ex.: 123456789...</span> </li>
                        <li class="list-group-item">Não pode ser mais que dois NÚMEROS sequenciais. <span class="text-danger">Ex.: 123...</span> </li>
                        <li class="list-group-item">Não pode ser NÚMEROS REPETIDOS. <span class="text-danger">Ex.: 111111...</span> </li>
                        <li class="list-group-item">Não pode ser MAIS QUE DOIS NÚMEROS REPETIDOS. <span class="text-danger">Ex.: 111...</span> </li>
                        <li class="list-group-item">Não pode ser LETRAS sequenciais. <span class="text-danger">Ex.: abcdef... ou ABCDEF... ou AbCdef</span></li>
                        <li class="list-group-item">No minimo uma letra MAÍSCULA.</li>
                        <li class="list-group-item">No minimo uma letra MÍNUSCULA.</li>
                        <li class="list-group-item">No minimo 8 (oito caracteres). </li>
                        <li class="list-group-item">Senha e Confirmação da senha tem que ser iguais.</li> 
                    </ul>
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end auth page content -->

    <!-- footer -->
    <footer class="footer mb-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <p class="mb-0 text-muted">&copy; <script>
                                document.write(new Date().getFullYear())
                            </script> Engetecnica Engenharia e Construção Ltda. <img src="{{ URL::asset('build/images/icones/LogoMarca - Horizontal.svg')}}" alt="" height="50"></p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- end Footer -->
</div>
<!-- end auth-page-wrapper -->
@endsection
@section('script')
<script src="{{ URL::asset('build/js/pages/form-validation.init.js') }}"></script>
@endsection
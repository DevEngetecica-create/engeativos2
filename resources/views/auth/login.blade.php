@extends('layouts.master-without-nav')
@section('title')
@lang('translation.signin')
@endsection
@section('content')
<div class="auth-page-wrapper pt-5">
    <!-- auth page bg -->
    <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
        <div class="bg-overlay"></div>

        <div class="shape">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
                <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
            </svg>
        </div>
    </div>
    @if(Session::has('error'))
                                    <div class="alert alert-danger">
                                        {{ dd(Session::get('error')) }}
                                    </div>
                                @endif

    <!-- auth page content -->
    <div class="auth-page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mt-sm-5 mb-4 text-white-50">
                        <div>
                            <a href="index" class="d-inline-block auth-logo">
                                <img src="{{ URL::asset('build/images/icones/Engeativos Logo C.png')}}" alt="" height="100">
                            </a>
                        </div>                       
                    </div>
                </div>
            </div>

            <!-- end row -->

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card mt-4">

                        <div class="card-body p-4">
                            <div class="text-center mt-2">
                                <h5 class="text-primary">Seja Bem vindo!</h5>
                            </div>
                             <!-- Exibir mensagem de erro da sessão, se houver -->
                                
                            <div class="p-2 mt-4">
                                <form action="{{ route('login.custom') }}" method="POST">
                                    
                                    @csrf
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Usuário<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" id="username" name="email" placeholder="E-mail">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <div class="float-end">
                                            <a href="{{ route('password.update') }}" class="text-info text-decoration-underline">Esqueceu a senha?</a>
                                        </div>
                                        <label class="form-label" for="password-input">Senha <span class="text-danger">*</span></label>
                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                            <input type="password" class="form-control password-input pe-5 @error('password') is-invalid @enderror" name="password" placeholder="Senha" id="password-input">
                                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="auth-remember-check">
                                        <label class="form-check-label" for="auth-remember-check">Lembrar</label>
                                    </div>

                                    <div class="mt-4">
                                        <button class="btn btn-success w-100" type="submit">Acessar</button>
                                    </div>

                                    <div class="mt-4 text-center">

                                        <div>
                                            <div class="mt-4 text-center">
                                                <a class="btn btn-warning w-100" href="{{ route('register') }}" class="fw-semibold text-primary text-decoration-underline">
                                                    Cadastrar-se
                                                </a>
                                            </div>

                                        </div>
                                </form>
                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->



                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end auth page content -->

    <!-- footer -->
    <footer class="footer">
         <div class="container d-sm-none d-lg-block" style="height:150px"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <p class="mb-0 text-muted">&copy; <script>
                                document.write(new Date().getFullYear())
                            </script> Engetecnica Engenharia e Construção Ltda.  <img src="{{ URL::asset('build/images/icones/LogoMarca - Horizontal.svg')}}" alt="" height="50"></p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- end Footer -->
</div>
@endsection
@section('script')
<script src="{{ URL::asset('build/libs/particles.js/particles.js') }}"></script>
<!-- <script src="{{ URL::asset('build/js/pages/particles.app.js') }}"></script> -->
<script src="{{ URL::asset('build/js/pages/password-addon.init.js') }}"></script>

@endsection
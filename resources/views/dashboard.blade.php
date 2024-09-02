<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | SGA - Engeativos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Sitema Engetecnica Engenharia e Construção Ltda. para gerenciar Segurança do Trabalho, Qualidade, Obras, Estoque de Materiais, Ferramentas e a Frota de Veículos" name="description" />
    <meta content="Setor de Engenharia e Tecnologia" name="author" />

    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- tag meta csrf-token -->

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico') }}">
    @include('head-css')
</head>

<body>
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('topbar')
        @include('sidebar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            @include('footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->
   @include('customizer')

    <!-- JAVASCRIPT -->
    @include('vendor-scripts')
</body>

</html>
<style>
    #select2-novo_id-container {
        width: 600px !important;
    }
</style>


<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">              
                

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger" id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

                <!-- App Search-->
                <form class="app-search d-none d-md-block">

                    <div class="position-relative col-12">

                        <!-- Verifica se o perfil é administrador, se for exibe o perfil de Adminsitrador-->
                        
                                 @if (session()->get('usuario_vinculo')->id_nivel >= 2) 
                        
                        <style>
                           #novo_id .selection{
                                 pointer-events: none;
                            }
                        </style>
                        
                        @endif
                        
                    <select class="{{ session()->get('usuario_vinculo')->id_nivel <= 1 ? 'form-select select2' : '' }} form-control mr-2" id="novo_id" name="novo_id">
                       
                        @if (session()->get('usuario_vinculo')->id_nivel == 1
                            or session()->get('usuario_vinculo')->id_nivel == 10 
                            or session()->get('usuario_vinculo')->id_nivel == 15 
                        )

                        <option value="" {{ session()->get('obra')['id'] == null ? 'selected' : '' }}>PERFIL ADMINISTRADOR - TODAS</option>

                        @foreach ($obras_lista as $obra_lista)
                        <option value="{{ $obra_lista->id }}" {{ session()->get('obra')['id'] == $obra_lista->id ? 'selected' : '' }}>
                            => {{ $obra_lista->codigo_obra }} 
                        </option>

                        @endforeach

                        @elseif (session()->get('usuario_vinculo')->id_nivel >= 2)
                        <option value="{{ session()->get('obra')->id }}" readonly>
                            => {{ session()->get('obra')->codigo_obra }}
                        </option>
                        @endif
                        
                    </select>

                        
                        
                </form>
            </div>



        </div>


        <div class="d-flex align-items-center {{ session()->get('usuario_vinculo')->id_nivel <= 1 ? 'd-block' : 'd-none' }}">
            <div class="dropdown ms-sm-3 header-item topbar-user bg-white">
                
                    <span class="d-flex align-items-center" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        
                        <i class="mdi mdi-content-save-all-outline mdi-24px  text-warning"></i>                        
                        <span class="text-start ms-xl-2 "> Cadastros</span>
                        <i class="mdi mdi-chevron-down text-black mx-2 mdi-18px"></i>
                    </span>
                    
                    <div class="dropdown-menu ">
                        <a class="dropdown-item" href="{{route('cadastro.empresa.adicionar')}}"> - <i class="mdi mdi-office-building-marker "></i> Empresas</a>
                        <a class="dropdown-item" href="{{route('cadastro.obra.adicionar')}}"> - <i class="mdi mdi-transmission-tower-export "></i> Obras</a>
                        <a class="dropdown-item" href="{{route('cadastro.funcionario.adicionar')}}"> - <i class="mdi mdi-account-details "></i> Funcionários</a>
                        <a class="dropdown-item" href="{{route('cadastro.fornecedor.adicionar')}}"> - <i class="mdi mdi-office-building-cog-outline "></i> Fornecedores</a>
                    </div>
            </div><!-- /btn-group -->
        </div>

        <div class="dropdown d-md-none topbar-head-dropdown header-item">
            <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="bx bx-search fs-22"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-search-dropdown">
                <form class="p-3">
                    <div class="form-group m-0">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                            <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="dropdown topbar-head-dropdown ms-1 header-item" id="notificationDropdown" title="Atenção!!! As notificações ficarão disponíveis por três dias.">
            <button type="button" class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle p-3" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
                <i class='bx bx-bell fs-22'></i>
                <span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger" id="totalnotificacoesbd1">{{ count($notificacoes) }}<span class="visually-hidden">unread messages</span></span>
                <i class="mdi mdi-chevron-down text-black mx-2 mdi-18px"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">

                <div class="dropdown-head bg-primary bg-pattern rounded-top">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0 fs-16 fw-semibold text-white"> Notificações </h6>
                            </div>
                            <div class="col-auto dropdown-tabs">
                                <span class="badge bg-light text-body fs-13" id="totalnotificacoesbd2">{{ count($notificacoes) }}</span>
                            </div>
                        </div>
                    </div>
                   

                    <div class="px-2 pt-2">
                        <ul class="nav nav-tabs dropdown-tabs nav-tabs-custom" data-dropdown-tabs="true" id="notificationItemsTab" role="tablist">
                            <li class="nav-item waves-effect waves-light">
                                <a class="nav-link active" data-bs-toggle="tab" href="#all-noti-tab" role="tab" aria-selected="true">
                                    Todas (<span id="totalnotificacoesbd3">{{ count($notificacoes) }}</span>)
                                </a>
                            </li>

                        </ul>
                    </div>                    

                </div>
                
                <div class="tab-content position-relative" id="notificationItemsTabContent">
                    <div class="tab-pane fade show active py-2 ps-2" id="all-noti-tab" role="tabpanel">
                        <div data-simplebar class="pe-2" id="listnotificacoes" style="max-height: 500px; overflow-y: auto;">
                            @foreach ($notificacoes as $notificacao)
                                <div class="text-reset notification-item d-block dropdown-item position-relative">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <a href="{{ $notificacao->link_acesso }}" class="stretched-link">
                                                <h6 class="mt-0 mb-1 fs-13 fw-semibold">{{ $notificacao->tipo }}</h6>
                                            </a>
                                            <div class="fs-13 text-muted">
                                                <p class="mb-1">{{ $notificacao->mensagem }}</p>
                                            </div>
                                            <p class="mb-0 fs-11 fw-medium text-uppercase text-muted"></p>
                                        </div>
                                        <div class="px-2 fs-15">
                                            <div class="form-check notification-check">
                                                <input class="form-check-input" type="checkbox" value="read" id="all-notification-check02">
                                                <label class="form-check-label" for="all-notification-check02"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="my-3 text-center view-all">
                        <a href="{{ route('notificacoes') }}" class="btn btn-soft-success waves-effect waves-light">
                            Ver todas
                            <i class="ri-arrow-right-line align-middle"></i>
                        </a>
                    </div>
                </div>
            </div>
            
        </div>

        <div class="dropdown topbar-head-dropdown ms-1 header-item" id="calendarDropdown" title="Atenção! As notificações ficarão disponíveis por três dias.">
            <button type="button" class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle p-3" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
                <i class='mdi mdi-calendar-month-outline mdi-24px'></i>
                <span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger" id="totalEventos">{{ $eventos->count() }}<span class="visually-hidden">Vencimentos</span></span>
                <i class="mdi mdi-chevron-down text-black mx-2 mdi-18px"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
        
                <div class="dropdown-head bg-primary bg-pattern rounded-top">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0 fs-16 fw-semibold text-white"> Eventos </h6>
                            </div>
                            <div class="col-auto dropdown-tabs">
                                <span class="badge bg-light text-body fs-13" id="totalEventos2">{{ $eventos->count() }}</span>
                            </div>
                        </div>
                    </div>
        
                    <div class="px-2 pt-2">
                        <ul class="nav nav-tabs dropdown-tabs nav-tabs-custom" data-dropdown-tabs="true" id="notificationItemsTab" role="tablist">
                            <li class="nav-item waves-effect waves-light">
                                <a class="nav-link active" data-bs-toggle="tab" href="#all-noti-tab" role="tab" aria-selected="true">
                                    Todas (<span id="totalEventos3">{{ $eventos->count() }}</span>)
                                </a>
                            </li>
                        </ul>
                    </div>
        
                </div>
        
                <div class="tab-content position-relative" id="notificationItemsTabContent">
                    <div class="tab-pane fade show active py-2 ps-2" id="all-noti-tab" role="tabpanel">
                        <div data-simplebar class="pe-2" id="listnotificacoesCalendario" style="max-height: 500px; overflow-y: auto;">
                            @forelse ($eventos as $evento)
                            <a href="{{'/'.$evento->url}}">
                                <div class="text-reset notification-item d-block dropdown-item position-relative">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <a href="{{$evento->url}}" class="stretched-link">
                                                <h6 class="mt-0 mb-1 fs-13 fw-semibold">{{ $evento->title }}</h6>
                                            </a>
                                            <div class="fs-13 text-muted">
                                                <p class="mb-1">{{ \Carbon\Carbon::parse($evento->end)->format('d/m/Y') }}</p>
                                            </div>
                                            <p class="mb-0 fs-11 fw-medium text-uppercase text-muted"></p>
                                        </div>
                                        <div class="px-2 fs-15">
                                            <div class="form-check notification-check">
                                                <input class="form-check-input" type="checkbox" value="read" id="notification-check-{{ $evento->id }}">
                                                <label class="form-check-label" for="notification-check-{{ $evento->id }}"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            @empty
                                <div class="text-reset notification-item d-block dropdown-item position-relative">
                                    <p class="text-muted text-center">Não há eventos neste mês.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <div class="my-3 text-center view-all">
                        <a href="{{ route('calendarios.show') }}" class="btn btn-soft-success waves-effect waves-light">
                            Ver todos
                            <i class="ri-arrow-right-line align-middle"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        

        <div class="dropdown ms-sm-3 header-item topbar-user bg-white">
            <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="d-flex align-items-center">
                     <img class="rounded-circle header-profile-user" src="@if (Auth::user()->avatar == '') {{ URL::asset('build/images/users/user-dummy-img.jpg') }} @else {{ URL::asset('build/images/users')}}/{{session()->get('usuario_vinculo')->id_funcionario}}/{{session()->get('usuario_vinculo')->vinculo_funcionario->imagem_usuario }}@endif" >
                    <span class="text-start ms-xl-2">
                        <span class="d-none d-xl-inline-block ms-1 fw-semibold user-name-text">{{Auth::user()->name}}</span>

                    </span>
                    <i class="mdi mdi-chevron-down text-black mx-2 mdi-18px"></i>
                </span>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <!-- item-->



                <a class="dropdown-item" href="{{ route('cadastro.funcionario.show', session()->get('usuario_vinculo')['id_funcionario']) }}">
                    <i class="mdi mdi-wallet text-muted fs-16 align-middle me-1"></i>
                    <span class="align-middle">Perfil</span>
                </a>


                <!-- <a class="dropdown-item" href="pages-profile-settings"><span class="badge bg-success-subtle text-success mt-1 float-end">New</span><i class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Settings</span></a>
                    <a class="dropdown-item" href="auth-lockscreen-basic"><i class="mdi mdi-lock text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Lock screen</span></a>
 -->
                <a class="dropdown-item " href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bx bx-power-off font-size-16 align-middle me-1"></i> <span key="t-logout">@lang('translation.logout')</span></a>

                <form id="logout-form" action="{{ route('signout') }}" method="get" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
    </div>
    </div>
</header>


<!-- removeNotificationModal -->
<div id="removeNotificationModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <div class="mt-2 text-center">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#495057,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                        <h4 class="fw-bold">Are you sure ?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to remove this Notification ?</p>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn w-sm btn-danger" id="delete-notification">Yes, Delete It!</button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

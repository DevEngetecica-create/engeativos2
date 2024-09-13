@yield('css')
<!-- Layout config Js -->
<script src="{{ URL::asset('build/js/layout.js') }}"></script>
<!-- Bootstrap Css -->
<link href="{{ URL::asset('build/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ URL::asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{ URL::asset('build/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<!-- custom Css-->
<link href="{{ URL::asset('build/css/custom.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="{{ URL::asset('build/libs/gridjs/theme/mermaid.min.css') }}">

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<link rel="stylesheet" href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<!-- MAPA -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<style>
    .mdi {
        font-size: 14px !important;
    }
    .tablink {
            background-color: #555;
            color: white;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            font-size: 17px;
            width: 100%;
        }

        .tablink:hover {
            background-color: #777;
        }

        /* Style the tab content (and add height:100% for full page content) */
        .tabcontent {

            display: none;
            padding: 20px 20px;
            height: 100%;
        }

        #detalhes {
            background-color: white;
            color: black;
        }

        #docs_tecnicos {
            background-color: white;
            color: black;
        }

        #docs_legais {
            background-color: white;
            color: white;
        }

        #custos_aquisicao {

            background-color: orange;
            color: black;

        }

        #manutencoes {
            background-color: cornflowerblue;
            color: black;
        }

        table td {
            font-size: small;
        }

        .upload-btn-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            margin-bottom: auto;
        }


        .btn-observacao {            
            cursor: pointer;
            pointer-events: none
        }

        .upload-btn-wrapper input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
        }
        
</style>

{{-- /* <div class="table-responsive table-card">
    <table class="table table-borderless table-hover table-nowrap align-middle mb-0">
        <thead class="table-light">
            <tr class="text-muted">
                <th scope="col">Situação</th>
                <th scope="col" style="width: 20%;">Verificado?</th>
                <th scope="col">Anexos</th>
                <th scope="col" style="width: 16%;">Observações</th>
            
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>Deve ser executado &#9899;</td>
                <td>
                    <input style="width:20px; height:16px" type="checkbox" id="obgr_' . $key . '_' . $p . '" class="form-check-input checklist-checkbox" name="checklist[] "  >                                                         
                    <input  type="hidden" id="periodo_' . $key . '_' . $p . '"  name="periodo" value="' . $p . '">
                </td>
                <td><img src="assets/images/users/avatar-1.jpg" alt="" class="avatar-xs rounded-circle me-2 material-shadow">
                    <a href="#javascript: void(0);" class="text-body fw-medium">Donald Risher</a>
                </td>
                <td>
                    <div class=" upload-btn-wrapper ">
                        <span class="btn-upload"><i class="mdi mdi-cloud-upload-outline" ></i></span>
                        <input class="observacao disabled" type="file" name="file[]" id="file[' . $key . ']"/> 
                    </div>    
                </td>

                <td>
                    <span data-bs-toggle="modal" data-bs-target="#executar' . $key . '" class="observacao text-secondary disabled mx-2" title="Observações"><i class="mdi mdi-comment-edit-outline mdi-24px"></i></span>
                </td>
            </tr>                                                
        </tbody>
    </table>
</div> --}}
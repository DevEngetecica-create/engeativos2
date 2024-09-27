<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

/* Configurações */

use App\Http\Controllers\ConfiguracaoController;
use App\Http\Controllers\ConfiguracaoMinhaContaController;
use App\Http\Controllers\ConfiguracaoUsuarioTipoController;
use App\Http\Controllers\ConfiguracaoUsuarioController;
use App\Http\Controllers\ConfiguracaoModuloController;
use App\Http\Controllers\ConfiguracaoSistemaController;

/* Cadastros */


use App\Http\Controllers\CadastroEmpresaController;
use App\Http\Controllers\CadastroFornecedorController;
use App\Http\Controllers\CadastroObraController;
use App\Http\Controllers\CadastroFuncionarioController;
use App\Http\Controllers\CadastroFolgaFuncionariosController;

/* Ativos */

use App\Http\Controllers\AtivoConfiguracaoController;
use App\Http\Controllers\AtivoExternoController;
use App\Http\Controllers\AtivoExternoManutencaoController;
use App\Http\Controllers\CalibracaoController;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\QRCodeController;

/* Ferramental */

use App\Http\Controllers\FerramentalRetiradaController;
use App\Http\Controllers\FerramentalRequisicaoController;

/* Anexos */

use App\Http\Controllers\AnexoController;
use App\Http\Controllers\ApiController;

/**
 * Consumindo API em Módulos
 * Redução de redundancia de pesquisa
 *
 * @Modulos
 *
 * 1.0 - Requisições

 */


use App\Http\Controllers\RelatorioAtivoInternoController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\RelatorioFornecedorController;
use App\Http\Controllers\RelatorioFuncionarioController;
use App\Http\Controllers\RelatorioObraController;

/*Veículos*/

use App\Http\Controllers\RelatorioVeiculoController;
use App\Http\Controllers\VeiculoCategoriaController;
use App\Http\Controllers\VeiculoSubCategoriaController;
use App\Http\Controllers\VeiculoAbastecimentoController;
use App\Http\Controllers\VeiculoAcessoriosController;
use App\Http\Controllers\VeiculoDepreciacaoController;
use App\Http\Controllers\VeiculoIpvaController;
use App\Http\Controllers\VeiculoManutencaoController;
use App\Http\Controllers\VeiculoLocacaoController;
use App\Http\Controllers\VeiculoQuilometragemController;
use App\Http\Controllers\VeiculoSeguroController;
use App\Http\Controllers\VeiculoServicosController;
use App\Http\Controllers\VeiculoTacografoController;
use App\Http\Controllers\AtivoInternoController;
use App\Http\Controllers\NotificacoesController;
use App\Http\Controllers\HomeController;


use App\Http\Controllers\ConfigController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\EstoqueEntradaController;
use App\Http\Controllers\EstoqueSaidaController;
use App\Http\Controllers\FuncaoFuncionarioController;

use App\Http\Controllers\TransferenciaController;
use App\Http\Controllers\TransferenciaFerramentalController;

use App\Http\Controllers\Estoque\CategoryController;
use App\Http\Controllers\Estoque\SubcategoryController;
use App\Http\Controllers\Estoque\ProductController;
use App\Http\Controllers\Estoque\BrandController;
use App\Http\Controllers\LogController;

use App\Http\Controllers\EstoqueCategoriaController;
use App\Http\Controllers\EstoqueMarcasController;
use App\Http\Controllers\EstoqueSubCategoriaController;

use App\Http\Controllers\OneDriveController;
use App\Http\Controllers\CadastroFuncionarioSetorController;
use App\Http\Controllers\ConfiguracaoNotificacaoEmailController;
use App\Http\Controllers\FipeController;
use App\Http\Controllers\FuncionarioPublicController;


use App\Http\Controllers\VeiculoPreventivaController;
use App\Http\Controllers\Formularios\Veiculos\CheckListManutPreventivaController;
use App\Http\Controllers\VeiculosDocsLegaisController;
use App\Http\Controllers\DocsLegaisController;
use App\Http\Controllers\DocsTecnicosController;
use App\Http\Controllers\TiposVeiculosController;
use App\Http\Controllers\VeiculosDocsTecnicosController;
use App\Models\Api\ApiRequisicao;

/*

|--------------------------------------------------------------------------

| Web Routes

|--------------------------------------------------------------------------

|

| Here is where you can register web routes for your application. These

| routes are loaded by the RouteServiceProvider within a group which

| contains the "web" middleware group. Now create something great!

|

*/

Route::get('/detalhes/funcionario/{id}', [FuncionarioPublicController::class, 'publicShow'])->name('public.funcionario');

Route::get('/funcionario/download/{id}', [FuncionarioPublicController::class, 'download'])->name('download.documento.publico');

Route::get('/download/{id}', 'NomeDoController@download')
    ->middleware('auth') // Exige que o usuário esteja autenticado
    ->name('download.documento');



Route::get('/bloqueado', function () {
    return view('auth.bloqueado');
})->name('bloqueado');


Auth::routes();

//Language Translation

Route::middleware(['auth'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('subcategories', SubcategoryController::class);
    Route::resource('estoque', ProductController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('logs', LogController::class);
});


Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

//Update User Details

Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');



//Update User Details

Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');

Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');

/* Route::get('admin',                                                             [CustomAuthController::class, 'index'])->name('admin');
Route::get('admin/login',                                                       [CustomAuthController::class, 'index'])->name('login'); */

Route::post('admin/custom-login',                                               [CustomAuthController::class, 'customLogin'])->name('login.custom');

Route::get('admin/signout',                                                     [CustomAuthController::class, 'signOut'])->name('signout');

Auth::routes(['reset' => true]);

/* Grupo de Rotas Autenticadas */

Route::middleware(['auth',])->group(function () {

    /** Atualizar obra ID */
    Route::post('/atualizar-obra',                                              [CustomAuthController::class, 'atualizar_obra'])->name('atualizar.obra');

    Route::post('/get-notifications',            [NotificacoesController::class, 'getNotifications'])->name('get.notifications');
    Route::get('admin/notificacoes',             [NotificacoesController::class, 'index'])->name('notificacoes');
    Route::get('admin/notificacoes/read/{id}',   [NotificacoesController::class, 'read'])->name('notificacoes.read');
    Route::get('admin/notificacoes/show/{id}',   [NotificacoesController::class, 'show'])->name('notificacoes.show');


    /* Configurações - Dashboard */
    Route::get('admin/configuracao',                                            [ConfigController::class, 'edit'])->name('config.edit');
    Route::post('admin/configuracao',                                           [ConfigController::class, 'update'])->name('config.update');
    Route::get('admin/dashboard',                                               [CustomAuthController::class, 'dashboard'])->name('dashboard');

    //Route::get('admin/dashboard',                                 [CustomAuthController::class, 'charts'])->name('dashboard.charts');
    /* Minha Conta */

    Route::get('admin/configuracao/minhaconta',                                 [ConfiguracaoMinhaContaController::class, 'index'])->name('minhaconta');
    Route::post('admin/configuracao/minhaconta/store',                          [ConfiguracaoMinhaContaController::class, 'store'])->name('minhaconta.store');

    /* Tipos de Usuário */

    Route::get('admin/configuracao/usuario_tipo',                               [ConfiguracaoUsuarioTipoController::class, 'index'])->name('usuario_tipo');
    Route::get('admin/configuracao/usuario_tipo/editar/{id?}',                  [ConfiguracaoUsuarioTipoController::class, 'edit'])->name('usuario_tipo.editar');
    Route::get('admin/configuracao/usuario_tipo/adicionar',                     [ConfiguracaoUsuarioTipoController::class, 'create'])->name('usuario_tipo.adicionar');
    Route::post('admin/configuracao/usuario_tipo/store',                        [ConfiguracaoUsuarioTipoController::class, 'store'])->name('usuario_tipo.store');
    Route::post('admin/configuracao/usuario_tipo/update/{id}',                  [ConfiguracaoUsuarioTipoController::class, 'update'])->name('usuario_tipo.update');

    /* Usuários */



    Route::get('admin/configuracao/usuario',                                    [ConfiguracaoUsuarioController::class, 'index'])->name('usuario');
    Route::get('admin/configuracao/usuario/editar/{id?}',                       [ConfiguracaoUsuarioController::class, 'edit'])->name('usuario.editar');
    Route::get('admin/configuracao/usuario/adicionar',                          [ConfiguracaoUsuarioController::class, 'create'])->name('usuario.adicionar');
    Route::post('admin/configuracao/usuario/store',                             [ConfiguracaoUsuarioController::class, 'store'])->name('usuario.store');
    Route::post('admin/configuracao/usuario/update/{id}',                       [ConfiguracaoUsuarioController::class, 'update'])->name('usuario.update');
    Route::delete('admin/configuracao/usuario/{id}',                            [ConfiguracaoUsuarioController::class, 'destroy'])->name('usuario.destroy');
    Route::post('admin/configuracao/usuario/bloquear/{id}',                      [ConfiguracaoUsuarioController::class, 'bloquear'])->name('usuario.bloquear');
    Route::post('admin/configuracao/usuario/desbloquear/{id}',                   [ConfiguracaoUsuarioController::class, 'desbloquear'])->name('usuario.desbloquear');


    /* Módulos */

    Route::get('admin/configuracao/modulo',                                     [ConfiguracaoModuloController::class, 'index'])->name('modulo');
    Route::get('admin/configuracao/modulo/editar/{id?}',                        [ConfiguracaoModuloController::class, 'edit'])->name('modulo.editar');
    Route::get('admin/configuracao/modulo/adicionar',                           [ConfiguracaoModuloController::class, 'create'])->name('modulo.adicionar');
    Route::post('admin/configuracao/modulo/store',                              [ConfiguracaoModuloController::class, 'store'])->name('modulo.store');
    Route::post('admin/configuracao/modulo/update/{id}',                        [ConfiguracaoModuloController::class, 'update'])->name('modulo.update');


    /* Configurações - Sistema */

    Route::get('admin/configuracao/sistema',                                    [ConfiguracaoSistemaController::class, 'index'])->name('sistema');
    Route::post('admin/configuracao/sistema/store',                             [ConfiguracaoSistemaController::class, 'store'])->name('sistema.store');

    Route::get('admin/configuracao/notification_email',                          [ConfiguracaoNotificacaoEmailController::class, 'index'])->name('notificacoes-email.index');
    Route::get('admin/configuracao/notification_email/editar/{id?}',             [ConfiguracaoNotificacaoEmailController::class, 'edit'])->name('notificatio_email.editar');
    Route::get('admin/configuracao/notification_email/show/{id?}',               [ConfiguracaoNotificacaoEmailController::class, 'show'])->name('notificatio_email.show');
    Route::get('admin/configuracao/notification_email/adicionar',                [ConfiguracaoNotificacaoEmailController::class, 'create'])->name('notificatio_email.adicionar');
    Route::post('admin/configuracao/notification_email/store',                   [ConfiguracaoNotificacaoEmailController::class, 'store'])->name('notificatio_email.store');
    Route::post('admin/configuracao/notification_email/update/{id}',             [ConfiguracaoNotificacaoEmailController::class, 'update'])->name('notificatio_email.update');
    Route::delete('admin/configuracao/notification_email/destroy/{id}',          [ConfiguracaoNotificacaoEmailController::class, 'destroy'])->name('notificatio_email.destroy');
    /* Cadastros */



    /* Cadastros - Cliente */



    /*     Route::get('admin/cadastro/cliente',                                        [CadastroClienteController::class, 'index'])->name('cadastro.cliente');

    Route::get('admin/cadastro/cliente/editar/{id?}',                           [CadastroClienteController::class, 'edit'])->name('cadastro.cliente.editar');
    Route::get('admin/cadastro/cliente/adicionar',                              [CadastroClienteController::class, 'create'])->name('cadastro.cliente.adicionar');
    Route::post('admin/cadastro/cliente/store',                                 [CadastroClienteController::class, 'store'])->name('cadastro.cliente.store');
    Route::post('admin/cadastro/cliente/update/{id}',                           [CadastroClienteController::class, 'update'])->name('cadastro.cliente.update'); */


    /* Cadastros - Empresa */



    Route::get('admin/empresa',                                                 [CadastroEmpresaController::class, 'index'])->name('empresa');
    Route::get('admin/cadastro/empresa',                                        [CadastroEmpresaController::class, 'index'])->name('cadastro.empresa');
    Route::get('admin/cadastro/empresa/editar/{id?}',                           [CadastroEmpresaController::class, 'edit'])->name('cadastro.empresa.editar');
    Route::get('admin/cadastro/empresa/adicionar',                              [CadastroEmpresaController::class, 'create'])->name('cadastro.empresa.adicionar');
    Route::post('admin/cadastro/empresa/store',                                 [CadastroEmpresaController::class, 'store'])->name('cadastro.empresa.store');
    Route::post('admin/cadastro/empresa/update/{id}',                           [CadastroEmpresaController::class, 'update'])->name('cadastro.empresa.update');
    Route::delete('admin/cadastro/empresa/{id}',                                [CadastroEmpresaController::class, 'destroy'])->name('cadastro.empresa.destroy');

    /* Cadastros - Fornecedor */



    Route::get('admin/fornecedor',                                              [CadastroFornecedorController::class, 'index'])->name('fornecedor');
    Route::get('admin/cadastro/fornecedor',                                     [CadastroFornecedorController::class, 'index'])->name('cadastro.fornecedor');
    Route::get('admin/cadastro/fornecedor/editar/{id?}',                        [CadastroFornecedorController::class, 'edit'])->name('cadastro.fornecedor.editar');
    Route::get('admin/cadastro/fornecedor/adicionar',                           [CadastroFornecedorController::class, 'create'])->name('cadastro.fornecedor.adicionar');
    Route::post('admin/cadastro/fornecedor/store',                              [CadastroFornecedorController::class, 'store'])->name('cadastro.fornecedor.store');
    Route::post('admin/cadastro/fornecedor/update/{id}',                        [CadastroFornecedorController::class, 'update'])->name('cadastro.fornecedor.update');
    Route::delete('admin/cadastro/fornecedor{id}',                              [CadastroFornecedorController::class, 'destroy'])->name('cadastro.fornecedor.destroy');

    //CONTATOS FORNECEDOR

    Route::post('admin/fornecedor/contato/store',                               [CadastroFornecedorController::class, 'storeContato'])->name('fornecedor.contato.store');
    Route::delete('admin/fornecedor/contato/{contato}/destroy',                 [CadastroFornecedorController::class, 'destroyContato'])->name('fornecedor.contato.destroy');

    /* Cadastros - Obra */

    Route::get('admin/obra',                                                    [CadastroObraController::class, 'index'])->name('obra');
    // Route::get('admin/cadastro/obra',                                           [CadastroObraController::class, 'index'])->name('cadastro.obra');
    Route::get('admin/cadastro/obra/editar/{id?}',                              [CadastroObraController::class, 'edit'])->name('cadastro.obra.editar');
    Route::get('admin/cadastro/obra/adicionar',                                 [CadastroObraController::class, 'create'])->name('cadastro.obra.adicionar');
    Route::post('admin/cadastro/obra/store',                                    [CadastroObraController::class, 'store'])->name('cadastro.obra.store');
    Route::post('admin/cadastro/obra/update/{id}',                              [CadastroObraController::class, 'update'])->name('cadastro.obra.update');
    Route::delete('admin/cadastro/obra/{id}',                                   [CadastroObraController::class, 'destroy'])->name('cadastro.obra.destroy');
    Route::post('admin/cadastro/obra',                                          [CadastroObraController::class, 'fastStore'])->name('cadastro.obra.fast.store');

    /* Cadastros - Funcionário */



    Route::get('admin/cadastro/funcionario/list',    [CadastroFuncionarioController::class, 'index'])->name('admin/cadastro/funcionario/list');

    Route::get('admin/cadastro/funcionario', function () {

        return view('pages.cadastros.funcionario.index');
    });

    Route::get('admin/cadastro/funcionario/editar/{id?}',                       [CadastroFuncionarioController::class, 'edit'])->name('cadastro.funcionario.editar');
    Route::get('admin/cadastro/funcionario/show/{id?}',                         [CadastroFuncionarioController::class, 'show'])->name('cadastro.funcionario.show');
    Route::get('admin/cadastro/funcionario/adicionar',                          [CadastroFuncionarioController::class, 'create'])->name('cadastro.funcionario.adicionar');
    Route::get('admin/cadastro/funcionario/consultar_qualificacao',             [CadastroFuncionarioController::class, 'consultar_qualificacao'])->name('cadastro.funcionario.consultar_qualificacao');
    Route::post('admin/cadastro/funcionario/store',                             [CadastroFuncionarioController::class, 'store'])->name('cadastro.funcionario.store');
    Route::post('admin/cadastro/funcionario/update/{id}',                       [CadastroFuncionarioController::class, 'update'])->name('cadastro.funcionario.update');
    Route::delete('admin/cadastro/funcionario/{id}/destroy',                    [CadastroFuncionarioController::class, 'destroy'])->name('cadastro.funcionario.destroy');
    Route::post('admin/cadastro/funcionario/adicionar_anexos_funcionarios',     [CadastroFuncionarioController::class, 'adicionar_anexos_funcionarios'])->name('cadastro.funcionario.funcoes.adicionar_anexos_funcionarios');
    Route::get('admin/cadastro/funcionario/excluir_anexos_funcionarios/{id}',   [CadastroFuncionarioController::class, 'excluir_anexos_funcionarios'])->name('cadastro.funcionario.excluir_anexos_funcionarios');
    Route::post('admin/cadastro/funcionario/aprovar_documentos/{id}',           [CadastroFuncionarioController::class, 'aprovar_documentos'])->name('cadastro.funcionario.aprovar_documentos');
    Route::get('admin/cadastro/funcionario/download/{id}',                      [CadastroFuncionarioController::class, 'download'])->name('cadastro.funcionario.download');
    Route::get('admin/cadastro/funcionario/downloads_zip/{id}',                 [CadastroFuncionarioController::class, 'downloads_zip'])->name('cadastro.funcionario.downloads_zip');
    Route::post('admin/cadastro/funcionario/cad_edi_password_func/{id}',        [CadastroFuncionarioController::class, 'cad_edi_password_func'])->name('cadastro.funcionario.cad_edi_password_func');
    Route::get('admin/cadastro/funcionario/obter-motivo/{id}',                  [CadastroFuncionarioController::class, 'obter_motivo'])->name('cadastro.funcionario.obter_motivo');
    Route::post('admin/cadastro/funcionario/excluir-qualificacao',              [CadastroFuncionarioController::class, 'excluir_qualificacao'])->name('cadastro.funcionario.excluir_qualificacao');
    Route::put('admin/cadastro/funcionario/editar_anexos_funcionarios/{id}',   [CadastroFuncionarioController::class, 'editar_anexos_funcionarios'])->name('cadastro.funcionario.editar_anexos_funcionarios');


    Route::get('admin/uploadOndrive', [OneDriveController::class, 'index'])->name('upload.index');
    Route::put('admin/uploadOndrive/upload_file', [OneDriveController::class, 'uploadFile'])->name('upload.file');


    /* Cadastros - Funções - Funcionário */

    Route::get('admin/cadastro/funcionario/funcoes',                            [FuncaoFuncionarioController::class, 'index'])->name('cadastro.funcionario.funcoes.index');
    Route::get('admin/cadastro/funcionario/funcoes/adicionar',                  [FuncaoFuncionarioController::class, 'create'])->name('cadastro.funcionario.funcoes.create');
    Route::post('admin/cadastro/funcionario/funcoes',                           [FuncaoFuncionarioController::class, 'store'])->name('cadastro.funcionario.funcoes.store');
    Route::get('admin/cadastro/funcionario/funcoes/editar/{id}',                [FuncaoFuncionarioController::class, 'edit'])->name('cadastro.funcionario.funcoes.edit');
    Route::get('admin/cadastro/funcionario/funcoes/show/{id}',                  [FuncaoFuncionarioController::class, 'show'])->name('cadastro.funcionario.funcoes.show');
    Route::post('admin/cadastro/funcionario/funcoes/update/{id}',               [FuncaoFuncionarioController::class, 'update'])->name('cadastro.funcionario.funcoes.update');
    Route::delete('admin/cadastro/funcionario/funcoes/{funcao}',                [FuncaoFuncionarioController::class, 'destroy'])->name('cadastro.funcionario.funcoes.destroy');
    Route::post('admin/cadastro/funcionario',                                   [FuncaoFuncionarioController::class, 'fastStore'])->name('cadastro.funcoes.fast.store');
    Route::post('admin/cadastro/funcionario/funcao/ajax',                       [FuncaoFuncionarioController::class, 'storeFuncao'])->name('cadastro.funcionario.funcoes.ajax');
    Route::get('admin/cadastro/funcionario/funcoes/delete_epi/{id}',            [FuncaoFuncionarioController::class, 'delete_epi'])->name('cadastro.funcionario.funcoes.delete_epi');
    Route::post('admin/cadastro/funcionario/funcoes/delete_funcao/{id}',        [FuncaoFuncionarioController::class, 'delete_funcao'])->name('cadastro.funcionario.funcoes.delete_funcao');
    
    
    /* Cadastros - Setores - Funcionário */

    Route::get('admin/cadastro/funcionario/setores',                            [CadastroFuncionarioSetorController::class, 'index'])->name('cadastro.funcionario.setores.index');
    Route::get('admin/cadastro/funcionario/setores/adicionar',                  [CadastroFuncionarioSetorController::class, 'create'])->name('cadastro.funcionario.setores.create');
    Route::post('admin/cadastro/funcionario/setores',                           [CadastroFuncionarioSetorController::class, 'store'])->name('cadastro.funcionario.setores.store');
    Route::get('admin/cadastro/funcionario/setores/editar/{id}',                [CadastroFuncionarioSetorController::class, 'edit'])->name('cadastro.funcionario.setores.edit');
    Route::get('admin/cadastro/funcionario/setores/show/{id}',                  [CadastroFuncionarioSetorController::class, 'show'])->name('cadastro.funcionario.setores.show');
    Route::post('admin/cadastro/funcionario/setores/update/{id}',               [CadastroFuncionarioSetorController::class, 'update'])->name('cadastro.funcionario.setores.update');
    Route::delete('admin/cadastro/funcionario/setores/{id}',                    [CadastroFuncionarioSetorController::class, 'delete'])->name('cadastro.funcionario.setores.delete');

    /* Fucnionário - Folga */

    Route::get('admin/cadastro/funcionario/folga/list',                         [CadastroFolgaFuncionariosController::class, 'index'])->name('cadastro/funcionario/folga/list');

    Route::get('admin/cadastro/funcionario/folga', function () {
        return view('pages.cadastros.funcionario.folgas.index');
    });

    Route::get('admin/cadastro/funcionario/folga/edit{id?}',                    [CadastroFolgaFuncionariosController::class, 'edit'])->name('cadastro.funcionario.folga.editar');
    Route::get('admin/cadastro/funcionario/folga/adicionar/',                   [CadastroFolgaFuncionariosController::class, 'create'])->name('cadastro.funcionario.folga.adicionar');
    Route::post('admin/cadastro/funcionario/folga/store',                       [CadastroFolgaFuncionariosController::class, 'store'])->name('cadastro.funcionario.folga.store');
    Route::post('admin/cadastro/funcionario/folga/update/{id}',                 [CadastroFolgaFuncionariosController::class, 'update'])->name('cadastro.funcionario.folga.update');
    Route::delete('admin/cadastro/funcionario/folga/delete{id}',                [CadastroFolgaFuncionariosController::class, 'destroy'])->name('cadastro.funcionario.folga.destroy');

    /* Ativo - Configuração */

    Route::get('admin/ativo',                                                   [AtivoConfiguracaoController::class, 'index'])->name('ativo');
    Route::get('admin/ativo/configuracao',                                      [AtivoConfiguracaoController::class, 'index'])->name('ativo.configuracao');
    Route::get('admin/ativo/configuracao/editar/{id?}',                         [AtivoConfiguracaoController::class, 'edit'])->name('ativo.configuracao.editar');
    Route::get('admin/ativo/configuracao/adicionar',                            [AtivoConfiguracaoController::class, 'create'])->name('ativo.configuracao.adicionar');
    Route::post('admin/ativo/configuracao/store',                               [AtivoConfiguracaoController::class, 'store'])->name('ativo.configuracao.store');
    Route::post('admin/ativo/configuracao/update/{id}',                         [AtivoConfiguracaoController::class, 'update'])->name('ativo.configuracao.update');

    /* Ativo - Externo */

    Route::get('admin/ativo/externo/list',                                      [AtivoExternoController::class, 'search'])->name('admin/ativo/externo/list');
    Route::get('admin/ativo/externo', function () {

        return view('pages.ativos.externos.index');
    });

    Route::get('admin/ativo/externo',                                           [AtivoExternoController::class, 'index'])->name('ativo.externo');
    Route::get('admin/ativo/externo/historico/{id}',                            [AtivoExternoController::class, 'historico'])->name('admin/ativo/externo/historico');
    Route::get('admin/ativo/externo/editar/{id?}',                              [AtivoExternoController::class, 'edit'])->name('ativo.externo.editar');
    Route::get('admin/ativo/externo/adicionar',                                 [AtivoExternoController::class, 'create'])->name('ativo.externo.adicionar');
    Route::post('admin/ativo/externo/store',                                    [AtivoExternoController::class, 'store'])->name('ativo.externo.store');
    Route::get('admin/ativo/externo/inserir/{ativo}',                           [AtivoExternoController::class, 'insert'])->name('ativo.externo.inserir');
    Route::post('admin/ativo/externo/inserir/store',                            [AtivoExternoController::class, 'insertStore'])->name('ativo.externo.inserir.store');
    Route::post('admin/ativo/externo/update/{id}',                              [AtivoExternoController::class, 'update'])->name('ativo.externo.update');
    Route::get('admin/ativo/externo/detalhes/{id}',                             [AtivoExternoController::class, 'show'])->name('ativo.externo.detalhes');
    Route::get('admin/ativo/externo/search/{id}',                               [AtivoExternoController::class, 'searchAtivoID'])->name('ativo.externo.search');
    Route::get('admin/ativo/externo/report',                                    [AtivoExternoController::class, 'report'])->name('ativoExterno.report');
    Route::delete('admin/ativo/externo/delete/{id}',                            [AtivoExternoController::class, 'destroy'])->name('ativo.externo.delete');

    Route::get('admin/ativo/download',                                          [AtivoExternoController::class, 'download'])->name('ativo.externo.download');

    Route::get('admin/ativo/externo/tranferencia',                              [TransferenciaFerramentalController::class, 'index'])->name('ativo.externo.transferencia');
    Route::get('admin/ativo/externo/tranferencia/create',                  [TransferenciaFerramentalController::class, 'create'])->name('ativo.externo.transferencia.create');
    Route::get('admin/ativo/externo/tranferencia/show/{id}',                    [TransferenciaFerramentalController::class, 'show'])->name('ativo.externo.transferencia.show');
    Route::get('admin/ativo/externo/tranferencia/pdf/{id}',                    [TransferenciaFerramentalController::class, 'romaneio_transferencia_feramenta'])->name('ativo.externo.transferencia.pdf');
    Route::post('admin/ativo/externo/tranferencia/store', [TransferenciaFerramentalController::class, 'store'])->name('ativo.externo.transferencia.store');



    // rota para capturar os id dos checbox da transferria/ desmobilização da obra

    Route::post('/update-selected-ids', function (Request $request) {

        $selectedIds = $request->input('selectedIds', []);
        Session::put('selectedIds', $selectedIds);

        /*INICIO sessão dos patrimonios*/

        $patrimonio = $request->input('patrimonio', []);
        $patrimonioSessao = $request->session()->get('patrimonio', []);
        $patrimonios = array_merge($patrimonioSessao, $patrimonio);

        // Remover valores duplicados

        $patrimonios = array_unique($patrimonios);

        //monta a sessão dos patrimonios

        Session::put('patrimonio', $patrimonios);

        /*FIM sessão dos patrimonios*/

        /********************************************************************************************* */
        /*INICIO sessão dos titulo*/

        $titulo = $request->input('titulo', []);
        $tituloSessao = $request->session()->get('titulo', []);
        $titulos = array_merge($tituloSessao, $titulo);
        // Remover os nome duplicados dos títulos
        $titulos = array_unique($titulos);
        Session::put('titulo', $titulos);
        /*FIM sessão dos titulos*/

        return response()->json(['success' => true, 'patrimonio' => $patrimonios]);
    });



    //rota para bloquear ferramentas que estão em operação

    Route::get('admin/ativo/externo/tranferencia/bloqueio', [TransferenciaFerramentalController::class, 'bloqueio'])->name('ativo.externo.transferencia.bloqueio');

    /* Ativo - Externo Manutenção */

    Route::get('admin/ativo/externo/manutencao/buscarInformacoes',             [AtivoExternoManutencaoController::class, 'preecherCampos'])->name('admin.ativo.externo.manutencao.preencher.campos');
    Route::get('admin/ativo/externo/manutencao/list',                           [AtivoExternoManutencaoController::class, 'list'])->name('admin/ativo/externo/manutencao/list');
    Route::get('admin/ativo/externo/manutencao', function () {

        return view('pages.ativos.externos.manutencao.index');
    });

    Route::get('admin/ativo/externo/manutencao',                                [AtivoExternoManutencaoController::class, 'index'])->name('ativo.externo.manutencao');
    Route::get('admin/ativo/externo/manutencao/editar/{id?}',                   [AtivoExternoManutencaoController::class, 'edit'])->name('ativo.externo.manutencao.editar');
    Route::get('admin/ativo/externo/manutencao/adicionar',                      [AtivoExternoManutencaoController::class, 'create'])->name('ativo.externo.manutencao.adicionar');
    Route::post('admin/ativo/externo/manutencao/store',                         [AtivoExternoManutencaoController::class, 'store'])->name('ativo.externo.manutencao.store');
    Route::post('admin/ativo/externo/manutencao/update/{id}',                   [AtivoExternoManutencaoController::class, 'update'])->name('ativo.externo.manutencao.update');
    Route::post('admin/ativo/externo/manutencao/aprovedOrcamento/{id}',         [AtivoExternoManutencaoController::class, 'aprovedOrcamento'])->name('ativo.externo.manutencao.aprovedOrcamento');
    Route::get('admin/ativo/externo/manutencao/detalhes/{id}',                  [AtivoExternoManutencaoController::class, 'show'])->name('ativo.externo.manutencao.detalhes');
    Route::delete('admin/ativo/externo/manutencao/destroy/{id}',                   [AtivoExternoManutencaoController::class, 'destroy'])->name('ativo.externo.manutencao.delete');
    Route::get('admin/ativo/externo/manutencao/search/{id}',                    [AtivoExternoManutencaoController::class, 'searchAtivoID'])->name('ativo.externo.manutencao.search');
    Route::post('admin/ativo/externo/manutencao/upload',                        [AtivoExternoManutencaoController::class, 'upload'])->name('ativo.externo.manutencao.upload');
    Route::get('admin/ativo/externo/manutencao/download/{id}',                  [AtivoExternoManutencaoController::class, 'download'])->name('ativo.externo.manutencao.download');
    Route::delete('admin/ativo/externo/manutencao/destroyAnexo/{id}',           [AtivoExternoManutencaoController::class, 'destroyAnexo'])->name('ativo.externo.manutencao.destroyAnexo');


    //CALIBRAÇÃO

    Route::get('admin/ativo/externo/calibracao/{id}',                           [CalibracaoController::class, 'index'])->name('ativo.externo.calibracao');
    Route::get('admin/ativo/externo/calibracao/adicionar/{ativoExternoEstoque}', [CalibracaoController::class, 'create'])->name('ativo.externo.calibracao.adicionar');
    Route::post('admin/ativo/externo/calibracao/store',                         [CalibracaoController::class, 'store'])->name('ativo.externo.calibracao.store');
    Route::get('admin/ativo/externo/calibracao/editar/{id?}',                   [CalibracaoController::class, 'edit'])->name('ativo.externo.editar.calibracao');
    Route::post('admin/ativo/externo/alibracao/update/c{id}',                   [CalibracaoController::class, 'update'])->name('ativo.externo.update.calibracao');


    /** Ativo - Externo Anexos */

    Route::get('admin/ativo/externo/anexo/{id}',                                    [AtivoExternoController::class, 'anexo'])->name('ativo.externo.anexo');
    Route::get('admin/ativo/veiculo/anexo/{id}',                                    [AtivoExternoController::class, 'anexo'])->name('ativo.externo.veiculo');
    Route::get('admin/ativo/externo/anexoRelatorioDescarte/{id}',                   [AtivoExternoController::class, 'anexoRelatorioDescarte'])->name('ativo.externo.anexoRelatorioDescarte');
    Route::get('admin/ativo/externo/detalhes/anexo/{id}',                           [AtivoExternoController::class, 'anexoDocsAtivos'])->name('ativo/externo/detalhes/anexoDocsAtivos');
    
    /*qrcode*/  
    Route::get('gerar-qrcode/{id}',                                                 [QRCodeController::class, 'gerarQRCode'])->name('qrCode');
    Route::get('gerar-qrcode/interno/{id}',                                         [QRCodeController::class, 'gerarQRCodeAtvInt'])->name('qrCodeInterno');
    
    //CONTROLE DE ESTOQUE - MARCAS  
    Route::get('admin/ativo/estoque/marcas',                                        [EstoqueMarcasController::class, 'index'])->name('ativo.estoque.marcas.index');
    Route::get('admin/ativo/estoque/marcas/create',                                 [EstoqueMarcasController::class, 'create'])->name('ativo.estoque.marcas.create');
    Route::post('admin/ativo/estoque/marcas',                                       [EstoqueMarcasController::class, 'store'])->name('ativo.estoque.marcas.store');
    Route::get('admin/ativo/estoque/marcas/show/{ativo}',                           [EstoqueMarcasController::class, 'show'])->name('ativo.estoque.marcas.show');
    Route::get('admin/ativo/estoque/marcas/edit/{ativo}',                           [EstoqueMarcasController::class, 'edit'])->name('ativo.estoque.marcas.edit');
    Route::put('admin/ativo/estoque/marcas/{ativo}',                                [EstoqueMarcasController::class, 'update'])->name('ativo.estoque.marcas.update');
    Route::delete('admin/ativo/estoque/marcas/destroy/{ativo}',                     [EstoqueMarcasController::class, 'destroy'])->name('ativo.estoque.marcas.destroy');
    
    
    //CONTROLE DE ESTOQUE - CATEGORIAS  
    Route::get('admin/ativo/estoque/categorias',                                    [EstoqueCategoriaController::class, 'index'])->name('ativo.estoque.categorias.index');
    Route::get('admin/ativo/estoque/categorias/create',                             [EstoqueCategoriaController::class, 'create'])->name('ativo.estoque.categorias.create');
    Route::post('admin/ativo/estoque/categorias',                                   [EstoqueCategoriaController::class, 'store'])->name('ativo.estoque.categorias.store');
    Route::get('admin/ativo/estoque/categorias/show/{ativo}',                       [EstoqueCategoriaController::class, 'show'])->name('ativo.estoque.categorias.show');
    Route::get('admin/ativo/estoque/categorias/edit/{ativo}',                       [EstoqueCategoriaController::class, 'edit'])->name('ativo.estoque.categorias.edit');
    Route::put('admin/ativo/estoque/categorias/{ativo}',                            [EstoqueCategoriaController::class, 'update'])->name('ativo.estoque.categorias.update');
    Route::delete('admin/ativo/estoque/categorias/destroy/{ativo}',                 [EstoqueCategoriaController::class, 'destroy'])->name('ativo.estoque.categorias.destroy');
    Route::get('admin/anexo/estoque/entrada/pesquisar_categoria',                   [EstoqueCategoriaController::class, 'pesquisar_categoria'])->name('anexo.estoque.categorias.pesquisar_categoria');
    
    
    //CONTROLE DE ESTOQUE - SUBCATEGORIAS   
    Route::get('admin/ativo/estoque/subcategorias',                                 [EstoqueSubCategoriaController::class, 'index'])->name('ativo.estoque.subcategorias.index');
    Route::get('admin/ativo/estoque/subcategorias/create',                          [EstoqueSubCategoriaController::class, 'create'])->name('ativo.estoque.subcategorias.create');
    Route::post('admin/ativo/estoque/subcategorias',                                [EstoqueSubCategoriaController::class, 'store'])->name('ativo.estoque.subcategorias.store');
    Route::get('admin/ativo/estoque/subcategorias/show/{ativo}',                    [EstoqueSubCategoriaController::class, 'show'])->name('ativo.estoque.subcategorias.show');
    Route::get('admin/ativo/estoque/subcategorias/edit/{ativo}',                    [EstoqueSubCategoriaController::class, 'edit'])->name('ativo.estoque.subcategorias.edit');
    Route::put('admin/ativo/estoque/subcategorias{ativo}',                          [EstoqueSubCategoriaController::class, 'update'])->name('ativo.estoque.subcategorias.update');
    Route::delete('admin/ativo/estoque/subcategorias/destroy/{ativo}',              [EstoqueSubCategoriaController::class, 'destroy'])->name('ativo.estoque.subcategorias.destroy');

    //CONTROLE DE ESTOQUE - CADASTRO DE PRODUTOS
    Route::get('admin/ativo/estoque',                                               [EstoqueController::class, 'index'])->name('ativo.estoque.index');
    Route::get('admin/ativo/estoque/create',                                        [EstoqueController::class, 'create'])->name('ativo.estoque.create');
    Route::post('admin/ativo/estoque',                                              [EstoqueController::class, 'store'])->name('ativo.estoque.store');
    Route::get('admin/ativo/estoque/show/{ativo}',                                  [EstoqueController::class, 'show'])->name('ativo.estoque.show');
    Route::get('admin/ativo/estoque/edit/{ativo}',                                  [EstoqueController::class, 'edit'])->name('ativo.estoque.edit');
    Route::put('admin/ativo/estoque/update/{id}',                                   [EstoqueController::class, 'update'])->name('ativo.estoque.update');
    Route::delete('admin/ativo/estoque/delete/{produto}',                           [EstoqueController::class, 'destroy'])->name('ativo.estoque.destroy');
    Route::get('admin/anexo/estoque/excluir/{id?}',                                 [EstoqueController::class, 'destroyAnexo'])->name('anexo.estoque.destroy');
    Route::post('/admin/ativo/estoque/marca/ajax',                                  [EstoqueController::class, 'storeMarca'])->name('ativo.estoque.marcas.ajax');
    Route::get('admin/ativo/estoque/anexos/{id}',                                   [EstoqueController::class, 'anexos'])->name('ativo.estoque.anexos');
    Route::post('admin/ativo/estoque/fileUpload',                                   [EstoqueController::class, 'fileUpload'])->name('ativo.estoque.store.fileUpload');
    Route::post('admin/ativo/estoque/download/{id}',                                [EstoqueController::class, 'download'])->name('ativo.estoque.store.download');
    Route::post('admin/ativo/estoque/destroy_file',                                 [EstoqueController::class, 'destroy_file'])->name('ativo.estoque.store.destroy_file');
    // Route::post('admin/ativo/estoque/create/file/{id}',                          php[EstoqueController::class, 'adicionar_anexos_produto_estoque'])->name('ativo.estoque.store.adicionar_anexos_produto_estoque');


    //CONTROLE DE ESTOQUE - ENTRADA DE MATERIAL 
    Route::get('admin/ativo/estoque/entrada',                                       [EstoqueEntradaController::class, 'index'])->name('ativo.estoque.entrada.index');
    Route::get('admin/ativo/estoque/entrada/create',                                [EstoqueEntradaController::class, 'create'])->name('ativo.estoque.entrada.create');
    Route::post('admin/ativo/estoque/entrada',                                      [EstoqueEntradaController::class, 'store'])->name('ativo.estoque.entrada.store');
    Route::get('admin/ativo/estoque/entrada/{ativo}/show',                          [EstoqueEntradaController::class, 'show'])->name('ativo.estoque.entrada.show');
    Route::get('admin/ativo/estoque/entrada/{ativo}/edit',                          [EstoqueEntradaController::class, 'edit'])->name('ativo.estoque.entrada.edit');
    Route::put('admin/ativo/estoque/entrada{ativo}',                                [EstoqueEntradaController::class, 'update'])->name('ativo.estoque.entrada.update');
    Route::delete('admin/ativo/estoque/entrada/{ativo}',                            [EstoqueEntradaController::class, 'destroy'])->name('ativo.estoque.entrada.destroy');
    Route::get('admin/ativo/estoque/download/entrada/{id}',                         [EstoqueEntradaController::class, 'download'])->name('ativo.estoque.entrada.download');
    Route::get('admin/anexo/estoque/excluir/entrada/{id?}',                         [EstoqueEntradaController::class, 'destroyAnexo'])->name('anexo.estoque.entrada.destroy');
    Route::get('admin/anexo/estoque/entrada/pesquisar_categoria',                   [EstoqueEntradaController::class, 'pesquisar_categoria'])->name('anexo.estoque.entrada.pesquisar_categoria');
    Route::post('admin/ativo/estoque/create/entrada/file',                          [EstoqueEntradaController::class, 'fileUpload'])->name('ativo.estoque.store.entrada.file');

    //CONTROLE DE ESTOQUE - SAÍDA DE MATERIAL
    Route::get('admin/ativo/estoque/saida',                                         [EstoqueSaidaController::class, 'index'])->name('ativo.estoque.saida.index');
    Route::get('admin/ativo/estoque/saida/create',                                  [EstoqueSaidaController::class, 'create'])->name('ativo.estoque.saida.create');
    Route::post('admin/ativo/estoque/saida',                                        [EstoqueSaidaController::class, 'store'])->name('ativo.estoque.saida.store');
    Route::get('admin/ativo/estoque/saida/{ativo}/show',                            [EstoqueSaidaController::class, 'show'])->name('ativo.estoque.saida.show');
    Route::get('admin/ativo/estoque/saida/{ativo}/edit',                            [EstoqueSaidaController::class, 'edit'])->name('ativo.estoque.saida.edit');
    Route::put('admin/ativo/estoque/saida{ativo}',                                  [EstoqueSaidaController::class, 'update'])->name('ativo.estoque.saida.update');
    Route::delete('admin/ativo/estoque/saida/{ativo}',                              [EstoqueSaidaController::class, 'destroy'])->name('ativo.estoque.saida.destroy');
    Route::get('admin/ativo/estoque/download/saida/{id}',                           [EstoqueSaidaController::class, 'download'])->name('ativo.estoque.download');
    Route::get('admin/anexo/estoque/excluir/saida/{id?}',                           [EstoqueSaidaController::class, 'destroyAnexo'])->name('ativo.estoque.saida.destroy');
    Route::post('admin/ativo/estoque/create/saida/file',                            [EstoqueSaidaController::class, 'fileUpload'])->name('ativo.estoque.store.saida.file');
    Route::get('admin/ativo/estoque/saida/consulta_epi',                            [EstoqueSaidaController::class, 'consulta_epi'])->name('ativo.estoque.saida.consulta_epi');
    Route::get('admin/ativo/estoque/pesquisar_categoria',                           [EstoqueSaidaController::class, 'pesquisar_categoria'])->name('ativo.estoque.saida.pesquisar_categoria');
    Route::post('admin/ativo/estoque/saida/update-session',                         [EstoqueSaidaController::class, 'updateSession'])->name('update.session');
    Route::post('admin/ativo/estoque/saida/limpar_sessao_estoque',                  [EstoqueSaidaController::class, 'limpar_sessao_estoque'])->name('clear.session.saida.limpar_sessao_estoque');
    Route::post('admin/ativo/estoque/saida/justificar_epi',                         [EstoqueSaidaController::class, 'justificar_epi'])->name('ativo.estoque.saida.justificar_epi');
    Route::get('admin/ativo/estoque/saida/lotes/{idProduto}',                       [EstoqueSaidaController::class, 'listar_lotes_epis'])->name('ativo.estoque.saida.listar_lotes_epis');

    Route::get('admin/ativo/veiculo/tipos_veiculos',                                [TiposVeiculosController::class, 'index'])->name('tipos_veiculos.index');
    Route::get('admin/ativo/veiculo/tipos_veiculos/create',                         [TiposVeiculosController::class, 'create'])->name('tipos_veiculos.create');
    Route::get('admin/ativo/veiculo/tipos_veiculos/edit/{id}',                      [TiposVeiculosController::class, 'edit'])->name('tipos_veiculos.edit');
    Route::get('admin/ativo/veiculo/tipos_veiculos/show/{id}',                      [TiposVeiculosController::class, 'show'])->name('tipos_veiculos.show');
    Route::post('admin/ativo/veiculo/tipos_veiculos/store',                         [TiposVeiculosController::class, 'store'])->name('tipos_veiculos.store');
    Route::put('admin/ativo/veiculo/tipos_veiculos/update/{id}',                    [TiposVeiculosController::class, 'update'])->name('tipos_veiculos.update');
    Route::delete('admin/ativo/veiculo/tipos_veiculos/delete/{id}',                 [TiposVeiculosController::class, 'delete'])->name('tipos_veiculos.delete');
    

    //consultar tabela fipe
    Route::get('admin/ativo/veiculo/consultar-marcas',                             [FipeController::class, 'consultarMarcas'])->name('consultar-marcas');
    Route::get('admin/ativo/veiculo/consultar-modelos',                            [FipeController::class, 'consultarModelos'])->name('consultar-modelos');
    Route::get('admin/ativo/veiculo/consultar-ano_modelos',                        [FipeController::class, 'consultarAnoModelos'])->name('consultar-ano_modelos');
    Route::get('admin/ativo/veiculo/consultar-ano',                                [FipeController::class, 'ConsultarModelosAtravesDoAno'])->name('consultar-ano');
    Route::get('admin/ativo/veiculo/consultar-todos_parametros',                   [FipeController::class, 'ConsultarValorComTodosParametros'])->name('consultar-todos_parametros');

    /* Ativo - Veículos */

    Route::get('admin/ativo/veiculo',                                               [VeiculoController::class, 'index'])->name('veiculos.index');
    Route::get('admin/ativo/veiculo/show/{id}',                                     [VeiculoController::class, 'show'])->name('veiculo.show');
    Route::get('admin/ativo/veiculo/create',                                        [VeiculoController::class, 'create'])->name('veiculo.create');
    Route::get('admin/ativo/veiculo/edit/{id}',                                     [VeiculoController::class, 'edit'])->name('veiculo.edit');
    Route::post('admin/ativo/veiculo/store',                                        [VeiculoController::class, 'store'])->name('veiculo.store');
    Route::put('admin/ativo/veiculo/update/{id}',                                   [VeiculoController::class, 'update'])->name('veiculo.update');
    Route::delete('admin/ativo/veiculo/delete/{id}',                                [VeiculoController::class, 'delete'])->name('veiculo.delete');

    Route::post('admin/ativo/veiculos/storeImage/{id}',                             [VeiculoController::class, 'storeImage'])->name('veiculos.storeImage');
    Route::put('admin/ativo/veiculos/updateImage/{id}',                             [VeiculoController::class, 'updateImage'])->name('veiculos.updateImage');
    Route::post('admin/ativo/veiculos/deleteImage/{id}',                            [VeiculoController::class, 'deleteImage'])->name('veiculos.deleteImage');

    Route::get('admin/ativo/pesquisarSubcategoria',                                 [VeiculoController::class, 'pesquisarSubcategoria'])->name('pesquisarSubcategorias');
    Route::get('admin/ativo/veiculo/anexo/{id}',                                    [VeiculoController::class, 'anexo'])->name('ativo.veiculo.anexo');


    Route::get('admin/ativo/veiculo/manut_preventiva',                              [VeiculoPreventivaController::class, 'index'])->name('veiculo.manut_preventiva.index');
    Route::get('admin/ativo/veiculo/manut_preventiva/show/{id}',                    [VeiculoPreventivaController::class, 'show'])->name('veiculo.manut_preventiva.show');
    Route::get('admin/ativo/veiculo/manut_preventiva/create',                       [VeiculoPreventivaController::class, 'create'])->name('veiculo.manut_preventiva.create');
    Route::get('admin/ativo/veiculo/manut_preventiva/edit/{id}',                    [VeiculoPreventivaController::class, 'edit'])->name('veiculo.manut_preventiva.edit');
    Route::post('admin/ativo/veiculo/manut_preventiva/store',                       [VeiculoPreventivaController::class, 'store'])->name('veiculo.manut_preventiva.store');
    Route::put('admin/ativo/veiculo/manut_preventiva/update',                       [VeiculoPreventivaController::class, 'update'])->name('veiculo.manut_preventiva.update');
    Route::delete('admin/ativo/veiculo/manut_preventivadelete/{id}',                [VeiculoPreventivaController::class, 'delete'])->name('veiculo.manut_preventiva.delete');
    
    Route::get('admin/ativo/veiculo/veiculos_docs_legais/{tipo_veiculo_id}',        [VeiculosDocsLegaisController::class, 'index'])->name('veiculos_docs_legais.index');
    Route::get('admin/ativo/veiculo/veiculos_docs_legais/create/{tipo_veiculo_id}', [VeiculosDocsLegaisController::class, 'create'])->name('veiculos_docs_legais.create');
    Route::get('admin/ativo/veiculo/veiculos_docs_legais/edit/{tipo_veiculo_id}',   [VeiculosDocsLegaisController::class, 'edit'])->name('veiculos_docs_legais.edit');
    Route::post('admin/ativo/veiculo/veiculos_docs_legais/store',                   [VeiculosDocsLegaisController::class, 'store'])->name('veiculos_docs_legais.store');
    Route::put('admin/ativo/veiculo/veiculos_docs_legais/update/{id}',              [VeiculosDocsLegaisController::class, 'update'])->name('veiculos_docs_legais.update');
    Route::delete('admin/ativo/veiculo/veiculos_docs_legais/delete/{id}',           [VeiculosDocsLegaisController::class, 'delete'])->name('veiculos_docs_legais.delete');
    // Rota para download de arquivos
    Route::get('veiculos_docs_legais/download/{id}',                                [VeiculosDocsLegaisController::class, 'download'])->name('veiculos_docs_legais.download');
    Route::put('admin/ativo/veiculo/veiculos_docs_legais/upload/{id}',              [VeiculosDocsLegaisController::class, 'upload'])->name('veiculos_docs_legais.upload');


    Route::get('admin/ativo/veiculo/veiculo_docs_tecnico/{tipo_veiculo_id}',         [VeiculosDocsTecnicosController::class, 'index'])->name('veiculo_docs_tecnico.index');
    Route::get('admin/ativo/veiculo/veiculo_docs_tecnico/create/{tipo_veiculo_id}',  [VeiculosDocsTecnicosController::class, 'create'])->name('veiculo_docs_tecnico.create');
    Route::get('admin/ativo/veiculo/veiculo_docs_tecnico/edit/{tipo_veiculo_id}',    [VeiculosDocsTecnicosController::class, 'edit'])->name('veiculo_docs_tecnico.edit');
    Route::post('admin/ativo/veiculo/veiculo_docs_tecnico/store',                    [VeiculosDocsTecnicosController::class, 'store'])->name('veiculo_docs_tecnico.store');
    Route::put('admin/ativo/veiculo/veiculo_docs_tecnico/update/{id}',               [VeiculosDocsTecnicosController::class, 'update'])->name('veiculo_docs_tecnico.update');
    Route::delete('admin/ativo/veiculo/veiculo_docs_tecnico/delete/{id}',            [VeiculosDocsTecnicosController::class, 'delete'])->name('veiculo_docs_tecnico.delete');
    // Rota para download de arquivos
    Route::get('admin/ativo/veiculo/veiculo_doc_tecnico/upload/{id}',             [VeiculosDocsTecnicosController::class, 'upload'])->name('veiculo_docs_tecnico.upload');
    Route::get('admin/ativo/veiculo/veiculo_doc_tecnico/download/{id}',             [VeiculosDocsTecnicosController::class, 'download'])->name('veiculo_docs_tecnico.download');
    Route::get('admin/ativo/veiculo/veiculo_doc_tecnico/not_emai',                  [VeiculosDocsTecnicosController::class, 'email'])->name('veiculo_docs_tecnico.email');

    Route::get('admin/ativo/veiculo/docs_legais/{id}',                              [DocsLegaisController::class, 'index'])->name('docs_legais.index');
    Route::get('admin/ativo/veiculo/docs_legais/create/{veiculo_tipo}',             [DocsLegaisController::class, 'create'])->name('docs_legais.create');
    Route::get('admin/ativo/veiculo/docs_legais/edit/{veiculo_tipo}',               [DocsLegaisController::class, 'edit'])->name('docs_legais.edit');
    Route::get('admin/ativo/veiculo/docs_legais/show/{id}',                         [DocsLegaisController::class, 'show'])->name('docs_legais.show');
    Route::post('admin/ativo/veiculo/docs_legais/store',                            [DocsLegaisController::class, 'store'])->name('docs_legais.store');
    Route::put('admin/ativo/veiculo/docs_legais/update/{id}',                       [DocsLegaisController::class, 'update'])->name('docs_legais.update');
    Route::delete('admin/ativo/veiculo/docs_legais/delete/{id}',                    [DocsLegaisController::class, 'delete'])->name('docs_legais.delete');
    //Route::get('docs_legais',                                   [DocsLegaisController::class, 'index'])->name('docs_legais.index');


    Route::get('admin/ativo/veiculo/docs_tecnicos/{id}',                              [DocsTecnicosController::class, 'index'])->name('docs_tecnicos.index');
    Route::get('admin/ativo/veiculo/docs_tecnicos/create/{veiculo_tipo}',             [DocsTecnicosController::class, 'create'])->name('docs_tecnicos.create');
    Route::get('admin/ativo/veiculo/docs_tecnicos/edit/{veiculo_tipo}',               [DocsTecnicosController::class, 'edit'])->name('docs_tecnicos.edit');
    Route::get('admin/ativo/veiculo/docs_tecnicos/show/{id}',                         [DocsTecnicosController::class, 'show'])->name('docs_tecnicos.show');
    Route::post('admin/ativo/veiculo/docs_tecnicos/store',                            [DocsTecnicosController::class, 'store'])->name('docs_tecnicos.store');
    Route::put('admin/ativo/veiculo/docs_tecnicos/update/{id}',                       [DocsTecnicosController::class, 'update'])->name('docs_tecnicos.update');
    Route::delete('admin/ativo/veiculo/docs_tecnicos/delete/{id}',                    [DocsTecnicosController::class, 'delete'])->name('docs_tecnicos.delete');
    //Route::get('docs_legais',                                   [DocsLegaisController::class, 'index'])->name('docs_legais.index');


    Route::get('veiculo_preventivas_checklist/{id}',            [CheckListManutPreventivaController::class, 'index'])->name('veiculo_preventivas_checklist.index');
    Route::get('veiculo_preventivas_checklist/create/{id}',     [CheckListManutPreventivaController::class, 'create'])->name('veiculo_preventivas_checklist.create');
    Route::post('veiculo_preventivas_checklist/store',          [CheckListManutPreventivaController::class, 'store'])->name('veiculo_preventivas_checklist.store');
    Route::put('veiculo_preventivas_checklist/update/{id}',     [CheckListManutPreventivaController::class, 'update'])->name('veiculo_preventivas_checklist.update');
    Route::delete('veiculo_preventivas_checklist/delete/{id}',  [CheckListManutPreventivaController::class, 'destroy'])->name('veiculo_preventivas_checklist.destroy');

    Route::get('veiculo_preventivas_checklist/show/{id}',       [CheckListManutPreventivaController::class, 'show'])->name('veiculo_preventivas_checklist.show');
    Route::get('veiculo_preventivas_checklist/edit/{id}',       [CheckListManutPreventivaController::class, 'edit'])->name('veiculo_preventivas_checklist.edit');
    Route::get('veiculo_preventivas_checklist/show/download/{id}/{fileIndex}',       [CheckListManutPreventivaController::class, 'edit'])->name('checklist.download');

    /* Veículos - Categorias */



    Route::get('admin/ativo/veiculo/categoria/list',                            [VeiculoCategoriaController::class, 'index'])->name('admin/ativo/veiculo/categoria/list');
    Route::get('admin/ativo/veiculo/categoria', function () {
        return view('pages.ativos.veiculos.categorias.index');
    });
    Route::get('admin/ativo/veiculo/categoria/edir{id?}',                       [VeiculoCategoriaController::class, 'edit'])->name('cadastro.veiculo.categoria.editar');
    Route::get('admin/ativo/veiculo/categoria/adicionar/',                      [VeiculoCategoriaController::class, 'create'])->name('cadastro.veiculo.categoria.adicionar');
    Route::post('admin/ativo/veiculo/categoria/store',                          [VeiculoCategoriaController::class, 'store'])->name('cadastro.veiculo.categoria.store');
    Route::post('admin/ativo/veiculo/categoria/{id}',                           [VeiculoCategoriaController::class, 'update'])->name('cadastro.veiculo.categoria.update');
    Route::delete('admin/ativo/veiculo/categoria/{id}',                         [VeiculoCategoriaController::class, 'destroy'])->name('cadastro.veiculo.categoria.destroy');


    /* Veículos - Sub Categorias */

    Route::get('admin/ativo/veiculo/subCategoria/list',                         [VeiculoSubCategoriaController::class, 'index'])->name('admin/ativo/veiculo/subCategoria/list');
    Route::get('admin/ativo/veiculo/subCategoria', function () {
        return view('pages.ativos.veiculos.subCategorias.index');
    });

    Route::get('admin/ativo/veiculo/subCategoria/{id?}',                        [VeiculoSubCategoriaController::class, 'edit'])->name('ativo.veiculo.subCategoria.editar');
    Route::get('admin/ativo/veiculo/subCategoria/adicionar/',                   [VeiculoSubCategoriaController::class, 'create'])->name('ativo.veiculo.subCategoria.adicionar');
    Route::post('admin/ativo/veiculo/subCategoria/store',                       [VeiculoSubCategoriaController::class, 'store'])->name('ativo.veiculo.subCategoria.store');
    Route::post('admin/ativo/veiculo/subCategoria/{id}',                        [VeiculoSubCategoriaController::class, 'update'])->name('ativo.veiculo.subCategoria.update');
    Route::delete('admin/ativo/veiculo/subCategoria/{id}',                      [VeiculoSubCategoriaController::class, 'destroy'])->name('ativo.veiculo.subCategoria.destroy');


    /* Ativo - Veículos - Locação de Veículos */



    Route::get('admin/ativo/veiculo/locacaoVeiculos',                           [VeiculoLocacaoController::class, 'index'])->name('admin/ativo/veiculo/locacaoVeiculos');

    Route::get('admin/ativo/veiculo/locacaoVeiculos/list',                      [VeiculoLocacaoController::class, 'list'])->name('admin/ativo/veiculo/locacaoVeiculos/list');
    Route::get('admin/ativo/veiculo/locacaoVeiculos/adicionar',                 [VeiculoLocacaoController::class, 'create'])->name('ativo.veiculo.locacaoVeiculos.create');
    Route::post('admin/ativo/veiculo/locacaoVeiculos',                          [VeiculoLocacaoController::class, 'store'])->name('ativo.veiculo.locacaoVeiculos.store');
    Route::get('admin/ativo/veiculo/locacaoVeiculos/editar/{id}',               [VeiculoLocacaoController::class, 'edit'])->name('ativo.veiculo.locacaoVeiculos.editar');
    Route::post('admin/ativo/veiculo/locacaoVeiculos/{id}',                     [VeiculoLocacaoController::class, 'update'])->name('ativo.veiculo.locacaoVeiculos.update');
    Route::delete('admin/ativo/veiculo/locacaoVeiculos/delete/{id}',            [VeiculoLocacaoController::class, 'delete'])->name('ativo.veiculo.locacaoVeiculos.delete');
    Route::patch('admin/ativo/veiculo/locacaoVeiculos/{id}',                    [VeiculoLocacaoController::class, 'cancel'])->name('ativo.veiculo.locacaoVeiculos.cancel');
    Route::get('admin/ativo/veiculo/locacaoVeiculos/download/{id}',             [VeiculoLocacaoController::class, 'download'])->name('ativo.veiculo.locacaoVeiculos/download');
    Route::get('admin/ativo/veiculo/locacaoVeiculos/anexo/{id}',                [VeiculoLocacaoController::class, 'anexo'])->name('ativo.externo.anexoLocacaoVeiculos');
    Route::get('admin/ativo/veiculo/locacaoVeiculos/pesquisar_placa_modelo/',   [VeiculoLocacaoController::class, 'pesquisar_placa_modelo'])->name('ativo.veiculo.locacaoVeiculos.pesquisar_placa_modelo');


    /* Ativo - Veículos - Acessórios */
    Route::get('admin/ativo/veiculo/acessorios/{veiculo}',                      [VeiculoAcessoriosController::class, 'index'])->name('ativo/veiculo/acessorios/index');
    Route::get('admin/ativo/veiculo/acessorios/adicionar/{veiculo}',            [VeiculoAcessoriosController::class, 'create'])->name('ativo.veiculo.acessorios.adicionar');
    Route::post('admin/ativo/veiculo/acessorios',                               [VeiculoAcessoriosController::class, 'store'])->name('ativo.veiculo.acessorios.store');
    Route::get('admin/ativo/veiculo/acessorios/editar/{id}',                    [VeiculoAcessoriosController::class, 'edit'])->name('ativo/veiculo/acessorios/edit');
    Route::post('admin/ativo/veiculo/acessorios/{id}',                          [VeiculoAcessoriosController::class, 'update'])->name('ativo.veiculo.acessorios.update');
    Route::delete('admin/ativo/veiculo/acessorios/delete/{id}',                 [VeiculoAcessoriosController::class, 'delete'])->name('ativo/veiculo/acessorios/delete');
    Route::patch('admin/ativo/veiculo/acessorios/{veiculo}',                    [VeiculoAcessoriosController::class, 'cancel'])->name('ativo.veiculo.acessorios.cancel');


    /* Ativo - Veículos - Tacografo */
    Route::get('admin/ativo/veiculo/tacografo/{veiculo}',                       [VeiculoTacografoController::class, 'index'])->name('ativo/veiculo/tacografo/index');
    Route::get('admin/ativo/veiculo/tacografo/adicionar/{veiculo}',             [VeiculoTacografoController::class, 'create'])->name('ativo.veiculo.tacografo.adicionar');
    Route::post('admin/ativo/veiculo/tacografo',                                [VeiculoTacografoController::class, 'store'])->name('ativo.veiculo.tacografo.store');
    Route::get('admin/ativo/veiculo/tacografo/editar/{id}',                     [VeiculoTacografoController::class, 'edit'])->name('ativo/veiculo/tacografo/edit');
    Route::post('admin/ativo/veiculo/tacografo/{id}',                           [VeiculoTacografoController::class, 'update'])->name('ativo/veiculo/tacografo/editar');
    Route::delete('admin/ativo/veiculo/tacografo/delete/{id}',                  [VeiculoTacografoController::class, 'delete'])->name('ativo/veiculo/tacografo/delete');
    Route::patch('admin/ativo/veiculo/tacografo/{veiculo}',                     [VeiculoTacografoController::class, 'cancel'])->name('ativo.veiculo.tacografo.cancel');


    /* Ativo - Veículos - Abastecimento */
    Route::get('admin/ativo/veiculo/abastecimento/{veiculo}',                   [VeiculoAbastecimentoController::class, 'index'])->name('ativo.veiculo.abastecimento.index');
    Route::get('admin/ativo/veiculo/abastecimento/adicionar/{veiculo}',         [VeiculoAbastecimentoController::class, 'create'])->name('ativo.veiculo.abastecimento.adicionar');
    Route::post('admin/ativo/veiculo/abastecimento',                            [VeiculoAbastecimentoController::class, 'store'])->name('ativo.veiculo.abastecimento.store');
    Route::get('admin/ativo/veiculo/abastecimento/editar/{id}',                 [VeiculoAbastecimentoController::class, 'edit'])->name('ativo.veiculo.abastecimento.edit');
    Route::put('admin/ativo/veiculo/abastecimento/{id}',                        [VeiculoAbastecimentoController::class, 'update'])->name('ativo.veiculo.abastecimento.update');
    Route::delete('admin/ativo/veiculo/abastecimento/delete/{id}',              [VeiculoAbastecimentoController::class, 'delete'])->name('ativo.veiculo.abastecimento.delete');
    Route::get('admin/ativo/veiculo/abastecimento/download/{id}',               [VeiculoAbastecimentoController::class, 'download'])->name('ativo.veiculo.abastecimento.download');
    Route::get('admin/ativo/veiculo/abastecimento/anexo/{id}',                  [VeiculoAbastecimentoController::class, 'anexo'])->name('ativo.externo.anexo');


    /* Ativo - Veículos - Depreciacao */
    Route::get('admin/ativo/veiculo/depreciacao/{veiculo}',                     [VeiculoDepreciacaoController::class, 'index'])->name('ativo.veiculo.depreciacao.index');
    Route::get('admin/ativo/veiculo/depreciacao/adicionar/{veiculo}',           [VeiculoDepreciacaoController::class, 'create'])->name('ativo.veiculo.depreciacao.adicionar');
    Route::post('admin/ativo/veiculo/depreciacao',                              [VeiculoDepreciacaoController::class, 'store'])->name('ativo.veiculo.depreciacao.store');
    Route::get('admin/ativo/veiculo/depreciacao/editar/{id}',                   [VeiculoDepreciacaoController::class, 'edit'])->name('ativo.veiculo.depreciacao.editar');
    Route::put('admin/ativo/veiculo/depreciacao/{id}',                          [VeiculoDepreciacaoController::class, 'update'])->name('ativo.veiculo.depreciacao.update');
    Route::delete('admin/ativo/veiculo/depreciacao/delete/{id}',                [VeiculoDepreciacaoController::class, 'delete'])->name('ativo.veiculo.depreciacao.delete');
    //Route::get('admin/ativo/veiculo/depreciacao/download/{id}',                 [VeiculoDepreciacao::class, 'download'])->name('ativo.veiculo.depreciacao/download');


    /* Ativo - Veículos - Ipva */
    Route::get('admin/ativo/veiculo/ipva/{veiculo}',                            [VeiculoIpvaController::class, 'index'])->name('ativo.veiculo.ipva.index');
    Route::get('admin/ativo/veiculo/ipva/adicionar/{veiculo}',                  [VeiculoIpvaController::class, 'create'])->name('ativo.veiculo.ipva.adicionar');
    Route::get('admin/ativo/veiculo/ipva/download/{id}',                        [VeiculoIpvaController::class, 'download'])->name('ativo.veiculo.ipva.download');
    Route::get('admin/ativo/veiculo/ipva/editar/{id}',                          [VeiculoIpvaController::class, 'edit'])->name('ativo.veiculo.ipva.editar');
    Route::post('admin/ativo/veiculo/ipva',                                     [VeiculoIpvaController::class, 'store'])->name('ativo.veiculo.ipva.store');
    Route::put('admin/ativo/veiculo/ipva/{id}',                                 [VeiculoIpvaController::class, 'update'])->name('ativo.veiculo.ipva.update');
    Route::delete('admin/ativo/veiculo/ipva/delete/{id}',                       [VeiculoIpvaController::class, 'delete'])->name('ativo.veiculo.ipva.delete');


    /* Ativo - Veículos - Manutencao */
    Route::get('admin/ativo/veiculo/manutencao/{veiculo}',                      [VeiculoManutencaoController::class, 'index'])->name('ativo.veiculo.manutencao.index');
    Route::get('admin/ativo/veiculo/manutencao/{veiculo} ',                     [VeiculoManutencaoController::class, 'index'])->name('ativo.veiculo.manutencao.custoAno');
    Route::get('admin/ativo/veiculo/manutencao/list/{veiculo}',                 [VeiculoManutencaoController::class, 'list'])->name('ativo/veiculo/manutencao/list');
    Route::get('admin/ativo/veiculo/manutencao/adicionar/{veiculo}',            [VeiculoManutencaoController::class, 'create'])->name('ativo.veiculo.manutencao.adicionar');
    Route::post('admin/ativo/veiculo/manutencao',                               [VeiculoManutencaoController::class, 'store'])->name('ativo.veiculo.manutencao.store');
    Route::get('admin/ativo/veiculo/manutencao/editar/{id}',                    [VeiculoManutencaoController::class, 'edit'])->name('ativo.veiculo.manutencao.edit');
    Route::get('admin/ativo/veiculo/manutencao/show/{id}',                      [VeiculoManutencaoController::class, 'show'])->name('ativo.veiculo.manutencao.show');
    Route::put('admin/ativo/veiculo/manutencao/{id}',                           [VeiculoManutencaoController::class, 'update'])->name('ativo.veiculo.manutencao.update');
    Route::delete('admin/ativo/veiculo/manutencao/delete/{id}',                 [VeiculoManutencaoController::class, 'delete'])->name('ativo.veiculo.manutencao.delete');
    Route::post('/admin/ativo/veiculo/manutencao/marca/ajax',                   [VeiculoManutencaoController::class, 'storeServico'])->name('ativo.veiculo.manutencao.servico.ajax');
    Route::patch('admin/ativo/veiculo/manutencao/{id}',                         [VeiculoManutencaoController::class, 'cancel'])->name('ativo.veiculo.manutencao.cancel');
    Route::get('admin/ativo/veiculo/manutencao/download/{id}',                  [VeiculoManutencaoController::class, 'download'])->name('ativo.veiculo.manutencao.download');
    Route::post('admin/ativo/veiculo/manutencao/updateArquivo/{id}',           [VeiculoManutencaoController::class, 'upload'])->name('ativo.veiculo.manutencao.upload');

    
    //Imagens da Manutenca do veículo
    Route::post('admin/ativo/veiculo/manutencao/storeImagem/{id}',             [VeiculoManutencaoController::class, 'storeImage'])->name('ativo.veiculo.manutencao.storeimagem');
    Route::get('admin/ativo/veiculo/manutencao/detalhes/{id}',                 [VeiculoManutencaoController::class, 'show'])->name('ativo.veiculo.manutencao.detalhes');
    Route::post('admin/ativo/veiculo/manutencao/updateImagem/{id}',            [VeiculoManutencaoController::class, 'updateImage'])->name('ativo.veiculo.manutencao.updateImagem');
    Route::post('admin/ativo/veiculo/manutencao/deleteImagem/{id}',            [VeiculoManutencaoController::class, 'deleteImage'])->name('ativo.veiculo.manutencao.deleteImagem');
   

  

    /* Ativo - Veículos - Quilometragem */
    Route::get('admin/ativo/veiculo/quilometragem/{veiculo}',                   [VeiculoQuilometragemController::class, 'index'])->name('ativo.veiculo.quilometragem.index');
    Route::get('admin/ativo/veiculo/quilometragem/adicionar/{veiculo}',         [VeiculoQuilometragemController::class, 'create'])->name('ativo.veiculo.quilometragem.adicionar');
    Route::post('admin/ativo/veiculo/quilometragem',                            [VeiculoQuilometragemController::class, 'store'])->name('ativo.veiculo.quilometragem.store');
    Route::get('admin/ativo/veiculo/quilometragem/editar/{id}',                 [VeiculoQuilometragemController::class, 'edit'])->name('ativo.veiculo.quilometragem.editar');
    Route::put('admin/ativo/veiculo/quilometragem/{id}',                        [VeiculoQuilometragemController::class, 'update'])->name('ativo.veiculo.quilometragem.update');
    Route::delete('admin/ativo/veiculo/quilometragem/delete/{id}',              [VeiculoQuilometragemController::class, 'delete'])->name('ativo.veiculo.quilometragem.delete');
    Route::get('admin/ativo/veiculo/quilometragem/download/{id}',               [VeiculoQuilometragemController::class, 'download'])->name('ativo.veiculo.quilometragem.download');
//

    /* Ativo - Veículos - Seguro */
    Route::get('admin/ativo/veiculo/seguro/{veiculo}',                          [VeiculoSeguroController::class, 'index'])->name('ativo.veiculo.seguro.index');
    Route::get('admin/ativo/veiculo/seguro/adicionar/{veiculo}',                [VeiculoSeguroController::class, 'create'])->name('ativo.veiculo.seguro.adicionar');
    Route::post('admin/ativo/veiculo/seguro',                                   [VeiculoSeguroController::class, 'store'])->name('ativo.veiculo.seguro.store');
    Route::get('admin/ativo/veiculo/seguro/editar/{id}',                        [VeiculoSeguroController::class, 'edit'])->name('ativo.veiculo.seguro.editar');
    Route::put('admin/ativo/veiculo/seguro/{id}',                               [VeiculoSeguroController::class, 'update'])->name('ativo.veiculo.seguro.update');
    Route::delete('admin/ativo/veiculo/seguro/delete/{id}',                     [VeiculoSeguroController::class, 'delete'])->name('ativo.veiculo.seguro.delete');
    Route::get('admin/ativo/veiculo/seguro/download/{id}',                      [VeiculoSeguroController::class, 'download'])->name('ativo.veiculo.segurodownload');
    Route::get('admin/ativo/veiculo/seguro/anexo/{id}',                         [VeiculoSeguroController::class, 'anexo'])->name('ativo.externo.anexo');

    // Veiculos - Serviços



    /* Route::get('servicos', [VeiculoServicosController::class, 'index'])->name('servicos.index');

Route::post('servicos', [VeiculoServicosController::class, 'insert'])->name('servicos.insert');

Route::get('admin/ativo/veiculo/manutencao/servicos/inserir', [VeiculoServicosController::class, 'create'])->name('servicos.inserir');

Route::get('admin/ativo/veiculo/manutencao/servicos/{item}/edit', [VeiculoServicosController::class, 'edit'])->name('servicos.edit');

Route::put('admin/ativo/veiculo/manutencao/servicos/{item}', [VeiculoServicosController::class, 'editar'])->name('servicos.editar');

Route::delete('admin/ativo/veiculo/manutencao/servicos/{item}', [VeiculoServicosController::class, 'delete'])->name('servicos.delete');

Route::get('admin/ativo/veiculo/manutencao/servicos/{item}/delete', [VeiculoServicosController::class, 'modal'])->name('servicos.modal'); */


    /* Relatórios - Funcionários */



    Route::get('admin/relatorio/funcionarios', [RelatorioFuncionarioController::class, 'index'])->name('relatorio.funcionario.index');
    Route::post('admin/relatorio/funcionarios/gerar', [RelatorioFuncionarioController::class, 'gerar'])->name('relatorio.funcionario.gerar');
    Route::get('obras/select', [RelatorioFuncionarioController::class, 'select'])->name('obras.select');



    /* Relatórios - Fornecedores */
    Route::get('admin/relatorio/fornecedores', [RelatorioFornecedorController::class, 'index'])->name('relatorio.fornecedor.index');
    Route::post('admin/relatorio/fornecedores/gerar', [RelatorioFornecedorController::class, 'gerar'])->name('relatorio.fornecedor.gerar');


    /* Relatórios - Obras */



    Route::get('admin/relatorio/obras', [RelatorioObraController::class, 'index'])->name('relatorio.obra.index');
    Route::post('admin/relatorio/obras/gerar', [RelatorioObraController::class, 'gerar'])->name('relatorio.obra.gerar');


    /* Relatórios - Veículos */

    Route::get('admin/relatorio/veiculos', [RelatorioVeiculoController::class, 'index'])->name('relatorio.veiculo.index');
    Route::post('admin/relatorio/veiculos/gerar', [RelatorioVeiculoController::class, 'gerar'])->name('relatorio.veiculo.gerar');

    /* Relatórios - Ativos Internos */
    Route::get('admin/relatorio/ativos-internos', [RelatorioAtivoInternoController::class, 'index'])->name('relatorio.ativo.interno.index');
    Route::post('admin/relatorio/ativos-internos/gerar', [RelatorioAtivoInternoController::class, 'gerar'])->name('relatorio.ativo.interno.gerar');



    /* Ferramental - Retirada */


    /* Route::get('admin/ativo/externo/manutencao/list',  [AtivoExternoManutencaoController::class, 'list'])->name('admin/ativo/externo/manutencao/list');



    Route::get('admin/ativo/externo/manutencao', function () {

        return view('pages.ativos.externos.manutencao.index');

    });


    Route::get('admin/ativo/externo/manutencao',       [AtivoExternoManutencaoController::class, 'index'])->name('ativo.externo.manutencao'); */



    Route::get('admin/ferramental/retirada/list',    [FerramentalRetiradaController::class, 'list'])->name('admin/ferramental/retirada/list');
    Route::get('admin/ferramental/retirada/adicionar', function () {
        return view('pages.ferramental.retirada.form');
    });

    Route::get('admin/ferramental/retirada/adicionar',          [FerramentalRetiradaController::class, 'create'])->name('ferramental.retirada.adicionar');
    Route::get('admin/ferramental/retirada',                    [FerramentalRetiradaController::class, 'index'])->name('ferramental.retirada');
    Route::get('admin/ferramental/retirada/editar/{id}',        [FerramentalRetiradaController::class, 'edit'])->name('ferramental.retirada.editar');
    Route::get('admin/ferramental/retirada/editar/prazo/{id}',  [FerramentalRetiradaController::class, 'ampliar'])->name('ferramental.retirada.ampliar');
    Route::post('admin/ferramental/retirada/editar/prazo',      [FerramentalRetiradaController::class, 'ampliarStore'])->name('ferramental.retirada.ampliar.store');
    Route::post('admin/ferramental/retirada/store',         [FerramentalRetiradaController::class, 'store'])->name('ferramental.retirada.store');
    Route::put('admin/ferramental/retirada/update/{id}',        [FerramentalRetiradaController::class, 'update'])->name('ferramental.retirada.update');



    Route::get('admin/ferramental/retirada/detalhes/{id}',          [FerramentalRetiradaController::class, 'show'])->name('ferramental.retirada.detalhes');
    Route::get('admin/ferramental/retirada/termo/{id}',             [FerramentalRetiradaController::class, 'gerar_termo_digital'])->name('ferramental.retirada.termo');
    Route::get('admin/ferramental/retirada/items/{id}',             [FerramentalRetiradaController::class, 'items'])->name('ferramental.retirada.items');
    Route::get('admin/ferramental/retirada/lista',                  [FerramentalRetiradaController::class, 'lista'])->name('ferramental.retirada.lista');
    Route::get('admin/ferramental/retirada/devolver/{id}',          [FerramentalRetiradaController::class, 'devolver'])->name('ferramental.retirada.devolver');
    Route::post('admin/ferramental/retirada/salvar',                [FerramentalRetiradaController::class, 'devolver_salvar'])->name('ferramental.retirada.devolver.salvar');
    Route::get('admin/ferramental/retirada/termo_download/{id}',    [FerramentalRetiradaController::class, 'termo_download'])->name('ferramental.retirada.download');
    Route::delete('admin/ferramental/retirada/{id}/destroy',        [FerramentalRetiradaController::class, 'destroy'])->name('ferramental.retirada.destroy');
    Route::get('admin/ferramental/retirada/bloqueio/contagem/{usuario}', [FerramentalRetiradaController::class, 'bloqueio'])->name('ferramental.retirada.bloqueio');

    Route::post('admin/ferramental/retirada/consultarCredenciaisTermo/{id}', [FerramentalRetiradaController::class, 'consultarCredenciaisTermo'])->name('ferramental/retirada/consultarCredenciaisTermo');
    Route::post('admin/ferramental/retirada/termo_assinar/{id}', [FerramentalRetiradaController::class, 'assinarTermo'])->name('ferramental/retirada/termo_assinar');


    /* Ferramental - Requisição */

    Route::get('admin/ferramental/requisicao',                      [FerramentalRequisicaoController::class, 'index'])->name('ferramental.requisicao.index');
    Route::get('admin/ferramental/requisicao/adicionar',            [FerramentalRequisicaoController::class, 'create'])->name('ferramental.requisicao.create');
    Route::post('admin/ferramental/requisicao',                     [FerramentalRequisicaoController::class, 'store'])->name('ferramental.requisicao.store');
    Route::get('admin/ferramental/requisicao/{id}',                 [FerramentalRequisicaoController::class, 'show'])->name('ferramental.requisicao.show');

    // Route::get('admin/ferramental/requisicao/{id}/editar', [FerramentalRequisicaoController::class, 'edit'])->name('ferramental.requisicao.edit');


    Route::put('admin/ferramental/requisicao/{id}',                 [FerramentalRequisicaoController::class, 'update'])->name('ferramental.requisicao.update');

    Route::post('admin/ferramental/requisicao/romaneio/{id}',       [FerramentalRequisicaoController::class, 'romaneio'])->name('ferramental.requisicao.romaneio');
    Route::patch('admin/ferramental/requisicao/{id}',               [FerramentalRequisicaoController::class, 'recept'])->name('ferramental.requisicao.recept');
    // Route::delete('admin/ferramental/requisicao/{id}', [FerramentalRequisicaoController::class, 'destroy'])->name('ferramental.requisicao.destroy');

    Route::get('admin/ferramental/requisicao/romaneio/{id}/obra/{obra}', [FerramentalRequisicaoController::class, 'romaneioObra'])->name('ferramental.requisicao.romaneio.obra');
    Route::get('admin/ferramental/requisicao/romaneio/{id}',        [FerramentalRequisicaoController::class, 'romaneioGeral'])->name('ferramental.requisicao.romaneio.geral');

    Route::post('admin/anexo/upload',                               [AnexoController::class, 'upload'])->name('anexo.upload');
    Route::get('admin/anexo/excluir/{id?}/{modulo?}',               [AnexoController::class, 'destroy'])->name('anexo.destroy');
    Route::get('admin/anexo/download/{id}',                         [AnexoController::class, 'download'])->name('anexo.download');

    // Adicionar marca

    Route::post('adicionar-marca', [VeiculoController::class, 'adicionarMarca'])->name('adicionar.marca');

    Route::get('admin/ferramental/requisicao/lista_ativo/{term?}', [ApiRequisicao::class, 'lista_ativo'])->name('ferramental.requisicao.lista_ativo');
    Route::get('admin/ferramental/requisicao/ativo_externo_id/{id?}', [ApiRequisicao::class, 'ativo_externo_id'])->name('ferramental.requisicao.ativo_externo_id');

    /* API de Controles */

    Route::post('admin/api/selecionar_obra', [ApiController::class, 'selecionar_obra'])->name('api.selecionar_obra');
    /**



     * Configurações Internas da Aplicação
     *
     * 1.0 - Função para remover cache
     * 2.0 - Migration Refresh
     */



    Route::get('/clear-cache', function () {
        Artisan::call('cache:clear');
        Artisan::call('route:cache');
        Artisan::call('config:cache');
        Artisan::call('view:clear');

        return 'Todos os caches foram limpos com sucesso. (cache, route, config, view)';
    });

    /** SGA-E Transferências - Todas */
    Route::get('admin/ferramenta/transferencia/todas', [TransferenciaController::class, 'todas'])->name('transferencia.todas');

    /** SGA-E Transferências - Obra */
    Route::get('admin/ferramenta/transferencia', [TransferenciaController::class, 'index'])->name('transferencia.index');
    Route::get('admin/ferramenta/transferencia/obra', [TransferenciaController::class, 'obra'])->name('transferencia.obra');
    Route::get('admin/ferramenta/transferencia/obra/salvar', [TransferenciaController::class, 'obra_store'])->name('transferencia.obra.store');

    /** SGA-E Transferências - Empresa */

    Route::get('admin/ferramenta/transferencia/empresa', [TransferenciaController::class, 'empresa'])->name('transferencia.empresa');
    Route::get('admin/ferramenta/transferencia/empresa/salvar', [TransferenciaController::class, 'empresa_store'])->name('transferencia.empresa.store');

    /** SGA-E Transferências - Fornecedor */
    Route::get('admin/ferramenta/transferencia/fornecedor', [TransferenciaController::class, 'fornecedor'])->name('transferencia.fornecedor');
    Route::get('admin/ferramenta/transferencia/fornecedor/salvar', [TransferenciaController::class, 'fornecedor_store'])->name('transferencia.fornecedor.store');

    /** SGA-E Transferências - Funcionario */
    Route::get('admin/ferramenta/transferencia/funcionario', [TransferenciaController::class, 'funcionario'])->name('transferencia.funcionario');
    Route::get('admin/ferramenta/transferencia/funcionario/salvar', [TransferenciaController::class, 'funcionario_store'])->name('transferencia.funcionario.store');


    /** SGA-E Transferências - Configuracoes de Ativos */
    Route::get('admin/ferramenta/transferencia/ativo_configuracao', [TransferenciaController::class, 'ativo_configuracao'])->name('transferencia.ativo_configuracao');
    Route::get('admin/ferramenta/transferencia/ativo_configuracao/salvar', [TransferenciaController::class, 'ativo_configuracao_store'])->name('transferencia.ativo_configuracao.store');

    /** SGA-E Transferências - Ativos */
    Route::get('admin/ferramenta/transferencia/ativo', [TransferenciaController::class, 'ativo'])->name('transferencia.ativo');
    Route::get('admin/ferramenta/transferencia/ativo/salvar', [TransferenciaController::class, 'ativo_store'])->name('transferencia.ativo.store');

    /** SGA-E Transferências - Veículos */
    Route::get('admin/ferramenta/transferencia/veiculo', [TransferenciaController::class, 'veiculo'])->name('transferencia.veiculo');
    Route::get('admin/ferramenta/transferencia/veiculo/salvar', [TransferenciaController::class, 'veiculo_store'])->name('transferencia.veiculo.store');
});







Route::get('/refresh-migrate', function () {
    Artisan::call('refresh --seed');
});

<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;


use App\Interfaces\VeiculoRepositoryInterface;
use App\Interfaces\VeiculoAbastecimentoRepositoryInterface;
use App\Interfaces\VeiculoCategoriaRepositoryInterface;
use App\Interfaces\VeiculoAcessoriosRepositoryInterface;
use App\Interfaces\VeiculoIpvaRepositoryInterface;
use App\Interfaces\VeiculoManutencaoRepositoryInterface;
use App\Interfaces\VeiculoPreventivaRepositoryInterface;
use App\Interfaces\VeiculoQuilometragemRepositoryInterface;
use App\Interfaces\VeiculosDocsLegaisRepositoryInterface;
use App\Interfaces\VeiculoSeguroRepositoryInterface;
use App\Interfaces\VeiculoSubCategoriaRepositoryInterface;
use App\Interfaces\VeiculoTacografoRepositoryInterface;
use App\Interfaces\CheckListManutPreventivaRepositoryInterface;
use App\Interfaces\DocsLegaisRepositoryInterface;
use App\Interfaces\DocsTecnicosRepositoryInterface;
use App\Interfaces\TiposVeiculosRepositoryInterface;
use App\Interfaces\VeiculosDocsTecnicosRepositoryInterface;

use App\Repositories\CadastroFuncionarioSetorRepository;
use App\Interfaces\CadastroFuncionarioSetorRepositoryInterface;

use App\Repositories\CheckListManutPreventivaRepository;
use App\Repositories\DocsLegaisRepository;
use App\Repositories\DocsTecnicosRepository;
use App\Repositories\TiposVeiculosRepository;
use App\Repositories\VeiculoRepository;
use App\Repositories\VeiculoAbastecimentoRepository;
use App\Repositories\VeiculoCategoriaRepository;
use App\Repositories\VeiculoAcessoriosRepository;
use App\Repositories\VeiculoIpvaRepository;
use App\Repositories\VeiculoManutencaoRepository;
use App\Repositories\VeiculoPreventivaRepository;
use App\Repositories\VeiculoQuilometragemRepository;
use App\Repositories\VeiculosDocsLegaisRepository;
use App\Repositories\VeiculosDocsTecnicosRepository;
use App\Repositories\VeiculoSeguroRepository;
use App\Repositories\VeiculoSubCategoriaRepository;
use App\Repositories\VeiculoTacografoRepository;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(VeiculoRepositoryInterface::class, VeiculoRepository::class);
        $this->app->bind(VeiculoAbastecimentoRepositoryInterface::class, VeiculoAbastecimentoRepository::class);
        $this->app->bind(VeiculoCategoriaRepositoryInterface::class, VeiculoCategoriaRepository::class);
        $this->app->bind(VeiculoAcessoriosRepositoryInterface::class, VeiculoAcessoriosRepository::class);
        $this->app->bind(VeiculoIpvaRepositoryInterface::class, VeiculoIpvaRepository::class);
        $this->app->bind(VeiculoManutencaoRepositoryInterface::class, VeiculoManutencaoRepository::class);
        $this->app->bind(VeiculoQuilometragemRepositoryInterface::class, VeiculoQuilometragemRepository::class);
        $this->app->bind(VeiculoSeguroRepositoryInterface::class, VeiculoSeguroRepository::class);
        $this->app->bind(VeiculoSubCategoriaRepositoryInterface::class, VeiculoSubCategoriaRepository::class);
        $this->app->bind(VeiculoTacografoRepositoryInterface::class, VeiculoTacografoRepository::class);
        $this->app->bind(VeiculoPreventivaRepositoryInterface::class, VeiculoPreventivaRepository::class);
        $this->app->bind(CheckListManutPreventivaRepositoryInterface::class, CheckListManutPreventivaRepository::class);
        $this->app->bind(VeiculosDocsLegaisRepositoryInterface::class, VeiculosDocsLegaisRepository::class);
        $this->app->bind(VeiculosDocsTecnicosRepositoryInterface::class,VeiculosDocsTecnicosRepository::class);
        $this->app->bind(DocsLegaisRepositoryInterface::class, DocsLegaisRepository::class);
        $this->app->bind(DocsTecnicosRepositoryInterface::class, DocsTecnicosRepository::class);
        $this->app->bind(TiposVeiculosRepositoryInterface::class, TiposVeiculosRepository::class);

        //funcionarios
        $this->app->bind(CadastroFuncionarioSetorRepositoryInterface::class, CadastroFuncionarioSetorRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        Schema::defaultStringLength(191);
      //  \Carbon\Carbon::setLocale('pt_BR');
    }
}

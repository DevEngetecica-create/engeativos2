<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Traits\FuncoesAdaptadas;
use App\Models\ConfiguracaoUsuarioNiveis;

use Illuminate\Support\Facades\Session;


class ConfiguracaoModulo extends Model
{
    use HasFactory;
    use FuncoesAdaptadas;

    protected $table = "modulos";



    // Por padrão o laravel requer para as o campo de chave primaria o nome
    // ID, porem quando se quer utilizar um nome diferente se faz necessário 
    // aplicar a regra a baixo.
    protected $primaryKey = 'id';

    // Com esta proteção permite que somente os campos identificados no array
    // serão manipulados.
    protected $fillable =   [
        'id_modulo',
        'titulo',
        'posicao',
        'url_amigavel',
        'icone',
        'tipo_de_acao',
        'created_at', 
        'updated_at',
        'deleted_at' 
    ];  


    static function get_modulos()
    {

        $modulos = DB::table('modulos')->where('id_modulo', 0)->orderBy('posicao')->get();
        foreach($modulos as &$module){

            $module->submodulos = DB::table('modulos')
                                        ->where('id_modulo', $module->id)
                                        ->orderBy('posicao', 'ASC')
                                        ->get();
        }


        return $modulos;
    }


    static function get_modulos_permitidos()
    {

        if (Auth::check() && isset(Session::get('usuario_vinculo')['id_nivel'])) {
            $nivel_id = Session::get('usuario_vinculo')['id_nivel'];
            $permissoes = json_decode(ConfiguracaoUsuarioNiveis::find($nivel_id)->permissoes);
            
            //dd(ConfiguracaoUsuarioNiveis::find($nivel_id));
            
            if (count((array)$permissoes) == 0) {
                return [];
            }

            $modulos = [];
            
            foreach ($permissoes as $id_modulo => $p) {
                $modulo = ConfiguracaoModulo::orderBy('posicao')->find($id_modulo);
                if ($modulo) {
                    $modulos[$id_modulo] = $modulo->toArray();
                    foreach ($p as $id_sub_modulo => $submodulo) {
                        $submodulo = ConfiguracaoModulo::orderBy('posicao')->find($id_sub_modulo);
                        if ($submodulo) {
                            $modulos[$id_modulo]['submodulos'][] = $submodulo->toArray();
                        }
                    }
                }
            }
            
           //dd($modulos);
            return $modulos;
        }

        return [];
    }

    
}

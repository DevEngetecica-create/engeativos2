<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\CadastroObra;
use App\Models\CadastroUsuariosVinculo;
use App\Models\CadastroFuncionario;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Session;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */


     protected function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                'name' => ['required', 'string', 'min:5', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],              
            ]

        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        
        
        $avatarName = "user-dummy-img.jpg";

        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'avatar'    =>  $avatarName,
        ]);

        $user->save();
        
        $funcionario = CadastroFuncionario::create(
            [
                'id_obra'           => 7,
                'id_funcao '        => 0,
                'nome'              => $user->name,
                'email'             => $user->email,               
                'imagem_usuario'    => $avatarName,
            ]
        );
        
        $funcionario->save();

            // Caminho para a imagem padrão
            $defaultImageName = "user-dummy-img.jpg";
            $defaultImagePath = public_path("build/images/users/" . $defaultImageName);
    
            // Diretório de destino
            $destinationPath = public_path("build/images/users/{$funcionario->id}");
    
            // Verifica se o diretório de destino existe e cria se necessário
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
    
            // Copia a imagem para o novo local
            File::copy($defaultImagePath, $destinationPath . '/' . $defaultImageName);
    
            // Atualiza o campo 'avatar' do usuário com o caminho da imagem
            $user->avatar = "build/images/users/{$funcionario->id}/{$defaultImageName}";

        $user_vinculo = CadastroUsuariosVinculo::create(
            [
                'id_usuario' => $user->id,
                'id_obra'  => 7,
                'id_funcionario'  => $funcionario->id,
                'id_nivel'  => $data['nivel_acesso'],       
            ]
        );

        $user_vinculo->save();

        Auth::login($user);

        $usuario_vinculo = CadastroUsuariosVinculo::find(Auth::user()->id);
        
        Session::put("usuario_vinculo", $usuario_vinculo);
      
        $id_obra = $usuario_vinculo->id_obra ?? 4;
        $obra_detalhes = CadastroObra::find($id_obra);      

        Session::put("obra", $obra_detalhes);


        return $user;
        
    }
}

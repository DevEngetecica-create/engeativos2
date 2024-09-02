<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use Hash;



use App\Models\{

    CadastroObra,

    CadastroUsuariosVinculo,

    User,

    Charts,

    Veiculo

};



use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Session;

use App;



use Illuminate\Support\Facades\Cache;



use App\Traits\FuncoesAdaptadas;

use Carbon\Carbon;



class CustomAuthController extends Controller

{

    use FuncoesAdaptadas;



    public function index()

    {
        if (Auth::check()) {
            return redirect()->intended('admin/dashboard');
        }
        return view('auth.login');
    }

    public function customLogin(Request $request)

    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {

            /** Verificação de Vínculo de Usuário */
            $usuario_vinculo = CadastroUsuariosVinculo::find(Auth::user()->id);
            $request->session()->put("usuario_vinculo", $usuario_vinculo);
            if (!$usuario_vinculo) {
                return redirect()->route('login')->with('error', 'Usuário não possui vínculo com Obra');
            }

            $id_obra = $usuario_vinculo->id_obra ?? null;

            if ($id_obra == null) {
                $obra_detalhes = [

                    'id' => null,
                    'razao_social' => 'SGA Todas as Obras',
                    'codigo_obra' => 'SGAE-OBRA-ADM'
                ];
            } else {
                $obra_detalhes = CadastroObra::find($id_obra);            }



            $request->session()->put("obra", $obra_detalhes);



            $userLog = Auth::user()->email;

            Log::channel('main')->info($userLog . ' | LOGIN NO SISTEMA');



            return redirect()->intended('admin/dashboard');

        }



        return redirect()->route('login')->with('error', 'Email ou senha inválidos');

    }



    public function dashboard()

    {

        // var_dump("opa");die;

        if (Auth::check()) {

            $dataAtual = Carbon::now();



            $QtdeTotalVeiculos = Veiculo::selectRaw('count(tipo)')

                ->where('tipo', '!=', 'maquinas')

                ->groupBy('id')->get();



          $valoresGraficosVeiculos = Charts::valoresGraficosVeiculos();

            $contaModelo = Charts::contaModelos();

            $valorTotalVeiculo = Charts::valorTotalVeiculos();

            $valorTotalMaquina = Charts::valorTotalMaquinas();

            $totalModelos = Charts::totalModelo();

            $ativosExterno = Charts::ativosExternos();

            $vencimentoIPVAs = Charts::vencimentoIPVA();

            $vencimentoSeguros = Charts::vencimentoSeguro();

            $calibracaoAtivosExternos = Charts::calibracaoAtivosExternos();

            $qtdeAtivosObra = Charts::qtdeAtivosObras();

          

           //dd($valoresGraficosVeiculos);

           

            return view('pages.dashboard.index', compact('dataAtual', 'valoresGraficosVeiculos','QtdeTotalVeiculos',  'contaModelo', 'valorTotalVeiculo', 'valorTotalMaquina', 'totalModelos', 'ativosExterno', 'vencimentoIPVAs', 'vencimentoSeguros', 'calibracaoAtivosExternos', 'qtdeAtivosObra'));

        }

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('login');
    }

    public function signOut(Request $request)
    {
        $userLog = Auth::user()->email ?? 'Error de sessão';
        Log::channel('main')->info($userLog . ' | SAIU DO SISTEMA: ');
        Cache::flush();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }



    public function atualizar_obra(request $request)
    {

        $id_obra = $request->input('novo_id') ?? null;
        $obra = CadastroObra::find($id_obra);

        if ($id_obra) {

            $obra['id'] = $id_obra;
            $obra['razao_social'] = $obra->razao_social;
            $obra['codigo_obra'] = $obra->codigo_obra;

        } else {

            $obra['id'] = null;
            $obra['razao_social'] = 'PERFIL ADMINISTRADOR - TODAS';
            $obra['codigo_obra'] = 'Todas';
        }

        session(['obra' => $obra]);

       return response()->json($obra); 

    }



    /*Language Translation*/

    public function lang($locale)

    {
        if ($locale) {
            App::setLocale($locale);
            Session::put('lang', $locale);
            Session::save();
            return redirect()->back()->with('locale', $locale);

        } else {

            return redirect()->back();
        }
    }



    public function updateProfile(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);


        $user = User::find($id);
        $user->name = $request->get('name');
        $user->email = $request->get('email');

        if ($request->file('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatarPath = public_path('/images/');
            $avatar->move($avatarPath, $avatarName);
            $user->avatar =  $avatarName;

        }

        $user->update();

        if ($user) {
            Session::flash('message', 'User Details Updated successfully!');
            Session::flash('alert-class', 'alert-success');
            // return response()->json([

            //     'isSuccess' => true,

            //     'Message' => "User Details Updated successfully!"

            // ], 200); // Status code here

            return redirect()->back();

        } else {

            Session::flash('message', 'Something went wrong!');

            Session::flash('alert-class', 'alert-danger');

            // return response()->json([

            //     'isSuccess' => true,

            //     'Message' => "Something went wrong!"

            // ], 200); // Status code here

            return redirect()->back();
        }
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            return response()->json([
                'isSuccess' => false,
                'Message' => "Your Current password does not matches with the password you provided. Please try again."
            ], 200); // Status code

        } else {
            $user = User::find($id);
            $user->password = Hash::make($request->get('password'));
            $user->update();
            if ($user) {

                Session::flash('message', 'Password updated successfully!');

                Session::flash('alert-class', 'alert-success');

                return response()->json([

                    'isSuccess' => true,

                    'Message' => "Password updated successfully!"

                ], 200); // Status code here

            } else {

                Session::flash('message', 'Something went wrong!');

                Session::flash('alert-class', 'alert-danger');

                return response()->json([

                    'isSuccess' => true,

                    'Message' => "Something went wrong!"

                ], 200); // Status code here

            }

        }

    }

}


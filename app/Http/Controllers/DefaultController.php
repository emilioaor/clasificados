<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\Index\RegisterUserRequest;
use App\PasswordReset;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DefaultController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $categories = Category::all();

        return view('default.index', [
            'categoryGroups' => $this->buildCategoriesArray($categories),
        ]);
    }

    /**
     * Registra un usuario
     *
     * @param RegisterUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(RegisterUserRequest $request)
    {
        $user = new User($request->all());
        $user->password = bcrypt($request->password);
        $user->save();

        $this->sessionMessages('Usuario registrado');

        return redirect()->route('index.index');
    }

    /**
     * Autentica al usuario
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            if (Auth::user()->level === User::LEVEL_ADMIN) {
                return redirect()->route('admin.index');
            }

            return redirect()->route('publication.index');
        }

        $this->sessionMessages('Credenciales invalidas', 'alert-danger');

        return redirect()->route('index.index');
    }

    /**
     * Cierra la sesion del usuario
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();

        return redirect()->route('index.index');
    }

    /**
     * Carga
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function passwordReset()
    {
        return view('default.passwordReset');
    }

    /**
     * Envia el email de recuperacion de contraseña
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function emailPasswordReset(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user) {
            $this->sessionMessages('Disculpe, este correo no existe', 'alert-danger');

            return redirect()->route('index.passwordReset');
        }

        DB::beginTransaction();

        try {
            $passwordReset = new PasswordReset();
            $passwordReset->token = csrf_token();
            $passwordReset->email = $request->email;
            $passwordReset->created_at = new \DateTime();
            $passwordReset->save();

            $user->sendPasswordResetNotification($passwordReset->token);
            $this->sessionMessages('Se ha enviado un correo de recuperación');

            DB::commit();

            return redirect()->route('index.index');
        } catch (\Exception $ex) {
            DB::rollback();
            $this->sessionMessages('Error al enviar correo de recuperación, intente nuevamente', 'alert-danger');

            return redirect()->route('index.passwordReset');
        }
    }

    /**
     * Verifica el token y lo envia a la vista de cambio de contraseña
     *
     * @param $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restorePassword($token)
    {
        $reset = PasswordReset::where('token', $token)->first();

        if (! $reset) {
            $this->sessionMessages('El token es invalido', 'alert-danger');

            return redirect()->route('index.index');
        }

        $created = new \DateTime($reset->created_at);
        $diff = $created->diff(new \DateTime());

        if ($diff->days > 0 || $diff->i > 30) {
            $this->sessionMessages('El token ya expiro, solicite de nuevo la recuperación de contraseña', 'alert-danger');

            return redirect()->route('index.index');
        }

        return view('default.changePassword', ['token' => $token]);
    }

    /**
     * Cambia la contraseña del usuario para completar el proceso
     * de recuperacion de contraseña
     *
     * @param ChangePasswordRequest $request
     * @param $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(ChangePasswordRequest $request, $token)
    {
        $reset = PasswordReset::where('token', $token)->first();

        if (! $reset) {
            $this->sessionMessages('El token es invalido', 'alert-danger');

            return redirect()->route('index.index');
        }

        $created = new \DateTime($reset->created_at);
        $diff = $created->diff(new \DateTime());

        if ($diff->days > 0 || $diff->i > 30) {
            $this->sessionMessages('El token ya expiro, solicite de nuevo la recuperación de contraseña', 'alert-danger');

            return redirect()->route('index.index');
        }

        // Actualiza la contraseña
        DB::beginTransaction();

        $user = User::where('email', $reset->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();
        // Caduca el token
        DB::table('password_resets')
            ->where('token', $token)
            ->update([
                'created_at' => date('Y-m-d h:i:s') - 1800,
            ])
        ;

        DB::commit();

        $this->sessionMessages('Contraseña actualizada');

        return redirect()->route('index.index');
    }

    /**
     * Arma un array con estructura de 3 en 3 para mostrar en la
     * vista principal
     *
     * @param $categories
     * @return array
     */
    private function buildCategoriesArray($categories)
    {
        $response = [];
        $cat = [];
        $top = count($categories) - 1;
        foreach ($categories as $i => $category) {

            $cat[] = $category;
            if ((($i +1) % 3 === 0) || $i >= $top) {
                $response[] = $cat;
                $cat = [];
            }
        }

        return $response;
    }
}

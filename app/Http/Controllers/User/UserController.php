<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    /**
     * Carga la vista de configuracion
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function config()
    {
        return view('user.config');
    }

    /**
     * Actualiza la configuracion del usuario
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function configUpdate(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();

        if (isset($data['current_password'])) {

            if (! Hash::check($data['current_password'], $user->password)) {
                $this->sessionMessages('La contraseña actual es incorrecta', 'alert-danger');

                return redirect()->route('user.config');
            }
            if (! isset($data['password']) && ! isset($data['password_confirmation'])) {
                $this->sessionMessages('Debe indicar la nueva contraseña', 'alert-danger');

                return redirect()->route('user.config');
            }
            if ((isset($data['password']) && ! isset($data['password_confirmation'])) ||
                (! isset($data['password']) && isset($data['password_confirmation']))) {
                // Si se envia una contraseña y no la otra
                $this->sessionMessages('Debe llenar ambas contraseñas', 'alert-danger');

                return redirect()->route('user.config');

            } elseif (isset($data['password']) && isset($data['password_confirmation'])) {
                // Si envia ambas contraseñas valido el formato
                if ($data['password'] !== $data['password_confirmation']) {
                    $this->sessionMessages('Las contraseñas no coinciden', 'alert-danger');

                    return redirect()->route('user.config');
                }
                if (strlen($data['password']) < 6 || strlen($data['password']) > 20) {
                    $this->sessionMessages('Las contraseñas deben ser de 6 a 20 caracteres', 'alert-danger');

                    return redirect()->route('user.config');
                }

                $user->password = bcrypt($data['password']);
            }
        }

        $user->phone = $data['phone'];
        $user->save();

        $this->sessionMessages('Configuración actualizada');

        return redirect()->route('user.config');
    }
}

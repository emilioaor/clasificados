<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\Index\RegisterUserRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

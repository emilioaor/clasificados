<?php

namespace App\Http\Controllers\User;

use App\Publication;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WhistListController extends Controller
{

    /**
     * Carga la tabla de lista de deseos
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $whistList = Auth::user()->whistListPublications()->orderBy('created_at', 'DESC')->paginate(15);

        return view('user.whistList.index', ['whistList' => $whistList]);
    }

    /**
     * Agrega una publicacion a la lista de deseos del usuario
     * autenticado
     *
     * @param $publicId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addPublication($publicId)
    {
        $publication = Publication::where('public_id', $publicId)->first();
        $publication->whistListUser()->attach(Auth::user()->id);

        $this->sessionMessages('Publicación agregada a tu lista de deseos');

        return redirect()->route('index.publication.show', ['publication' => $publicId]);
    }

    /**
     * Remueve una publicacion de la lista de deseos del usuario
     * autenticado
     *
     * @param $publicId
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removePublication($publicId, Request $request)
    {
        $publication = Publication::where('public_id', $publicId)->first();
        $publication->whistListUser()->detach(Auth::user()->id);

        $this->sessionMessages('Publicación removida de tu lista de deseos');

        if (!empty($request->route)) {
            return redirect()->route($request->route);
        }

        return redirect()->route('index.publication.show', ['publication' => $publicId]);
    }
}

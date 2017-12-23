<?php

namespace App\Http\Controllers;

use App\Publication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Este controlador es para el manejo de las publicaciones
 * para usuarios no autenticados
 *
 * Class PublicationController
 * @package App\Http\Controllers
 */
class PublicationController extends Controller
{

    /**
     * Vista de la publicacion
     *
     * @param $publicId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($publicId)
    {
        $publication = Publication::where('public_id', $publicId)->first();
        $comments = $publication->comments()->orderBy('created_at', 'DESC')->where('parent', null)->limit(10)->get();
        $relatedPosts = Publication::where('status', Publication::STATUS_PUBLISHED)
            ->where('category_id', $publication->category_id)
            ->where('id', '<>', $publication->id)
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get()
        ;

        return view('user.publication.show', [
            'publication' => $publication,
            'comments' => $comments,
            'relatedPosts' => $relatedPosts,
        ]);
    }

    /**
     * Realiza una busqueda de publicaciones
     *
     * @param Request $request
     * @return array
     */
    public function search(Request $request)
    {
        if (! $request->isXmlHttpRequest()) {
            abort(404);
        }

        $search = $request->search;
        $search = explode(' ', $search);

        // Busco coincidencia en la busqueda
        $publications = Publication::join('categories', 'publications.category_id', '=', 'categories.id')
            ->where('publications.status', Publication::STATUS_PUBLISHED)
        ;
        //Por cada palabra de la busqueda agrego una condicion
        foreach ($search as $i => $s) {
            if (strlen($s) > 2) {
                if ($i === 0) {
                    $publications->where('search', 'like', "%\"%{$s}%\"%");
                } else {
                    $publications->orWhere('search', 'like', "%\"%{$s}%\"%");
                }
            }
        }

        return new JsonResponse($this->buildSearchArray($publications->get(), $request->search));
    }

    /**
     * Realiza la busqueda de publicaciones para mostrarlos
     * en la vista
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchWords(Request $request)
    {
        $search = $request->words;
        $search = explode(' ', $search);

        // Busco coincidencia en la busqueda
        $publications = Publication::where('publications.status', Publication::STATUS_PUBLISHED);
        //Por cada palabra de la busqueda agrego una condicion
        foreach ($search as $i => $s) {
            if (strlen($s) > 2) {
                if ($i === 0) {
                    $publications->where('search', 'like', "%\"%{$s}%\"%");
                } else {
                    $publications->orWhere('search', 'like', "%\"%{$s}%\"%");
                }
            }
        }

        $publications = $publications->paginate(15);
        $publications->setPath(route('index.publication.searchWords', ['words' => $request->words]));

        return view('default.search', ['publications' => $publications]);
    }

    /**
     * Arma la respuesta de las busquedas en array, muy importante diferenciar
     * las "palabras de la publicacion" son aquellas que se generan con  el
     * contenido de la misma y sirve para hacer busquedas por terminos. Por otro
     * lado las "palabras del usuario" son aquellas que el usuario escribio en
     * el buscador
     *
     * De momento estoy usando esta logica para el autocompletado
     *
     * @param $publications
     * @param $search
     * @return array
     */
    private function buildSearchArray($publications, $search)
    {
        $matching = [];
        $repeat = [];
        $explodeSearch = explode(' ', $search); // Separo la busqueda del usuario por palabra

        // Recorro todas las publicaciones resultantes de la busqueda
        foreach ($publications as $publication) {
            $searchPublication = json_decode($publication->search); // Palabras de la publicacion en array

            // Busco la palabra que hizo matching con la busqueda
            // Foreach con cada palabra de la publicacion
            foreach ($searchPublication as $word) {

                // Foreach con cada palabra del usuario
                foreach ($explodeSearch as $userWord) {
                    if (strlen($userWord) > 2 && str_replace($userWord, '', $word) !== $word) {

                        /* | Si la palabra que busco el usuario coincide con esta palabra de la
                         * | publicacion, lo agrego como resultado de busqueda y paso a la
                         * | siguiente palabra del usuario
                         * |____________________________________________________________________*/

                        if (! in_array($publication->category->name . ' - ' . $word, $repeat)) {
                            $repeat[] = $publication->category->name . ' - ' . $word;
                            $matching[] = [
                                'label' => $publication->category->name . ' - ' . $word,
                                'words' => $word,
                            ];

                            if (count($matching) >= 10) {
                                return $matching;
                            }
                        }
                    }
                }
            }
        }

        return $matching;
    }
}

<?php

namespace App\Http\Controllers;

use App\Publication;
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
}

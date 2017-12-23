<?php

namespace App\Http\Controllers\User;

use App\Category;
use App\Comment;
use App\Publication;
use App\PublicationImage;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PublicationController extends Controller
{
    /**
     * Carga la vista principal de publicaciones de usuario
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $publications = Publication::orderBy('created_at', 'DESC');

        if (Auth::user()->level !== User::LEVEL_ADMIN) {
            $publications->where('user_id', Auth::user()->id);
        }

        return view('user.publication.index', ['publications' => $publications->paginate(15)]);
    }

    /**
     * Carga la vista de crear publicacion
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::all();

        return view('user.publication.create', [
            'categories' => $categories,
            'categoriesArray' => json_encode($this->buildCategoriesObject($categories)),
        ]);
    }

    /**
     * Registra una publicacion
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        $publication = new Publication($request->all());
        $publication->save();
        $options = isset($request->options) ? $request->options : [];
        $publication->subCategoryOptions()->sync($options);

        DB::commit();

        $this->sessionMessages('Publicación registrada');

        return redirect()->route('publication.index');
    }

    /**
     * Carga la vista para editar una publicacion
     *
     * @param $public_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($public_id)
    {
        $publication = Publication::where('public_id', $public_id)->first();
        $categories = Category::all();
        $selectedOptions = $publication->getSelectedOptionsArray();

        return view('user.publication.edit', [
            'publication' => $publication,
            'categories' => $categories,
            'categoriesArray' => json_encode($this->buildCategoriesObject($categories, $selectedOptions)),
        ]);
    }

    /**
     * Actualiza una publicacion
     *
     * @param Request $request
     * @param $publicId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $publicId)
    {
        DB::beginTransaction();

        $data = $request->all();
        $data['hash_tags'] = isset($data['hash_tags']) ? $data['hash_tags'] : [];
        $publication = Publication::where('public_id', $publicId)->first();
        $publication->update($data);
        $publication->subCategoryOptions()->sync($request->options);

        DB::commit();

        $this->sessionMessages('Publicación actualizada');

        return redirect()->route('publication.edit', ['publication' => $publicId]);
    }

    /**
     * Carga una imagen y la asocia a la publicacion
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadImage(Request $request, $id)
    {
        if ($error = PublicationImage::hasErrorImage($request->newImage)) {
            $this->sessionMessages($error, 'alert-danger');

            return redirect()->route('publication.edit', ['publication' => $id]);
        }

        $originalPath = PublicationImage::generateUploadImageOriginalPath($request->publication_id);
        $filename = PublicationImage::generateImageFileName($request->newImage);

        if (! $request->newImage->move($originalPath, $filename)) {
            $this->sessionMessages('Ocurrio un error al subir la imagen, intente de nuevo', 'alert-danger');

            return redirect()->route('publication.edit', ['publication' => $id]);
        }

        PublicationImage::generateImagesSizes($originalPath, $filename, $request->publication_id);

        // Si llego hasta este punto guardo la imagen en base de datos
        $publicationImage = new PublicationImage();
        $publicationImage->publication_id = $request->publication_id;
        $publicationImage->url = $filename;
        $publicationImage->save();

        $this->sessionMessages('Imagen cargada satisfactoriamente');

        return redirect()->route('publication.edit', ['publication' => $id]);
    }

    /**
     * Elimina una imagen en base de datos
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteImage(Request $request)
    {
        $publicationImage = PublicationImage::find($request->publicationImageId);
        $id = $publicationImage->publication->public_id;
        $publicationImage->delete();

        $this->sessionMessages('Imagen eliminada');

        return redirect()->route('publication.edit', ['publication' => $id]);
    }

    /**
     * Actualiza a ubicacion establecida en la publicacion
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePosition(Request $request, $id)
    {
        $publication = Publication::where('public_id', $id)->first();
        $publication->location = json_encode(['lat' => floatval($request->lat), 'lng' => floatval($request->lng)]);
        $publication->save();

        $this->sessionMessages('Ubicación actualizada');

        return redirect()->route('publication.edit', ['publication' => $id]);
    }

    /**
     * Agrega un comentario a la publicacion
     *
     * @param Request $request
     * @param $public_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addComment(Request $request, $public_id)
    {
        $data = $request->all();

        if (empty($data['g-recaptcha-response']) || ! $this->validComment($data['g-recaptcha-response'])) {
            $this->sessionMessages('Captcha invalida', 'alert-danger');

            return redirect()->route('index.publication.show', ['publication' => $public_id]);
        }

        $publication = Publication::where('public_id', $public_id)->first();

        $comment = new Comment($data);
        $comment->publication_id = $publication->id;
        $comment->save();

        $this->sessionMessages('Su mensaje fue publicado');

        return redirect()->route('index.publication.show', ['publication' => $public_id]);
    }

    /**
     * Construye un objeto para funcionalidad en front end que permita
     * que al cambiar una categoria, se actualicen en la vista las
     * sub categorias disponibles
     *
     * @param $categories, Categorias guardadas en base de datos
     * @param array $selectedOptions, Un array con las opciones seleccionada en una publicacion
     * @return array
     */
    private function buildCategoriesObject($categories, array $selectedOptions = [])
    {
        $response['categories'] = [];
        foreach ($categories as $category) {
            if ($category->hasSubCategories()) {
                // Si tiene sus categorias

                $subCategories = [];
                foreach ($category->subCategories as $subCategory) {

                    // Le agrego las opciones a la subCategoria
                    $options = [];
                    foreach ($subCategory->subCategoryOptions as $option) {
                        $options[] = [
                            'id' => $option->id,
                            'name' => $option->name,
                            'selected' => in_array($option->id, $selectedOptions),
                        ];
                    }

                    // Agrego la subCategoria
                    $subCategories[] = [
                        'id' => $subCategory->id,
                        'name' => $subCategory->name,
                        'options' => $options,
                    ];
                }

                // agrego la categoria al objeto resultante
                $response['categories'][] = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'subCategories' => $subCategories,
                ];
            }
        }

        return $response;
    }

    /**
     * Valida si el usuario paso la prueba de "No soy un robot"
     *
     * @param $captchaCode
     * @return bool
     */
    private function validComment($captchaCode)
    {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $url .= '?secret=' . env('GOOGLE_CATCHA_SECRET');
        $url .= '&response=' . $captchaCode;

        $ch = curl_init($url);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}

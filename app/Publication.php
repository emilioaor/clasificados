<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Publication extends Model
{
    /** Estatus de las publicaciones */
    const STATUS_PUBLISHED = 1;
    const STATUS_HIDDEN = 2;
    const STATUS_EXPIRED = 3;

    /** Maxima cantidad de imagenes para una publicacion */
    const MAX_IMAGES_FREE = 4;
    const MAX_IMAGES_PAY = 6;

    /** Coordenadas de Naguanagua */
    const DEFAULT_LAT = 10.275086285352806;
    const DEFAULT_LNG = -68.01956550625005;

    /** Prefijo para el public_id de las publicaciones */
    const PUBLIC_ID_PREFIX = 'PUB-';

    /** Planes */
    const PLAN_FREE = 1;
    const PLAN_PREMIUM = 2;

    /** Simbolo de la moneda */
    const CURRENCY_SYMBOL = '$';

    protected $table = 'publications';

    protected $fillable = [
        'title', 'price', 'location', 'user_id',
        'category_id', 'description', 'status',
        'public_id', 'hash_tags', 'transaction',
        'video', 'search',
    ];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->status = self::STATUS_HIDDEN;
        $this->location = json_encode(['lat' => self::DEFAULT_LAT, 'lng' => self::DEFAULT_LNG]);
        parent::__construct($attributes);
    }

    /**
     * Cree este metodo con la intencion de pasar
     * los hashtags a array
     *
     * @param string $key
     * @return array|mixed
     */
    public function getAttribute($key)
    {
        $attribute = parent::getAttribute($key);

        if ($key === 'hash_tags') {
            $attribute = $attribute ? explode(',', $attribute) : [];
        }

        return $attribute;
    }

    /**
     * Cree este metodo con la intencion de pasar el hashtag
     * de array a string para guardar en base de datos
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        if ($key === 'hash_tags') {
            $value = $value ? implode(',', $value) : null;
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Realiza cambios en el objeto antes de persistir
     *
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        if (! $this->public_id) {
            $this->public_id = self::PUBLIC_ID_PREFIX . strtotime( (new \DateTime())->format('Y-m-d h:i:s') );
        }
        if ($this->video) {
            $this->video = str_replace('watch?v=', 'embed/', $this->video);
        }
        if (Auth::check()) {
            $this->user_id = Auth::user()->id;
        }
        $this->generateSearch();

        return parent::save($options);
    }

    /**
     * Realiza cambios en el objeto antes de actualizar
     *
     * @param array $attributes
     * @param array $options
     * @return bool
     */
    public function update(array $attributes = [], array $options = [])
    {
        $this->generateSearch();

        return parent::update($attributes, $options);
    }

    /**
     * Indica si la publicacion es paga
     *
     * @return bool
     */
    public function isPaid()
    {
        if (! is_null($this->transaction)) {
            return true;
        }

        return false;
    }

    /**
     * Usuario creador de la publicacion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Categoria de la publicacion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id');
    }

    /**
     * Cuando se asocia una categoria a la publicacion luego se selecciona un valor
     * para cada subcategoria. Esta relacion sirve para guardar el valor de cada
     * subcategoria de la categoria principal seleccionada en la publicacion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subCategoryOptions()
    {
       return $this->belongsToMany('App\SubCategoryOption', 'publications_options', 'publication_id', 'sub_category_option_id');
    }

    /**
     * Todas las imagenes asociadas a esta publicacion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function publicationImages()
    {
        return $this->hasMany('App\PublicationImage', 'publication_id');
    }

    /**
     * Todos los comentarios de la publicacion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Comment', 'publication_id');
    }

    /**
     * Todos los usuario que tienen esta publicacion en su lista
     * de deseos
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function whistListUser()
    {
        return $this->belongsToMany('App\User', 'whist_list', 'publication_id', 'user_id');
    }

    /**
     * Todoss los usuarios que se les notifico de esta publicacion. Las notificaciones
     * se generan cada hora en base a la lista de deseos de cada usuario
     *
     * @return $this
     */
    public function userNotified()
    {
        return $this->belongsToMany('App\User', 'notifications', 'publication_id', 'user_id')
            ->withPivot(['status']);
    }

    /**
     * Contruye un array con las opciones seleccionadas para esta publicacion
     *
     * @return array
     */
    public function getSelectedOptionsArray()
    {
        $selectedOptions = [];
        foreach ($this->subCategoryOptions as $option) {
            $selectedOptions[] = $option->id;
        }

        return $selectedOptions;
    }

    /**
     * Indica si ya la publicacion posee el maximo de imagenes
     *
     * @return bool
     */
    public function hasMaxImages()
    {
        $max = $this->isPaid() ? self::MAX_IMAGES_PAY : self::MAX_IMAGES_FREE;

        if (count($this->publicationImages) >= $max) {
            return true;
        }

        return false;
    }

    /**
     * Formatea el precio
     *
     * @return string
     */
    public function getFormattedPrice()
    {
        return number_format($this->price, '2', ',', '.');
    }

    /**
     * Toma toda la informacion de la publicacion como titulo,
     * contenido y hashtags y obtiene cuales son las palabras
     * mas utilizadas para generar una cadena de caracteres
     * que pueda ser utilizada en las busquedas
     */
    private function generateSearch()
    {
        $words = [];

        // Cuenta las palabras en el titulo
        $words = $this->countWords($this->title, $words, ' ');
        // Cuenta las palabras en la descripcion
        $words = $this->countWords($this->description, $words, ' ');
        // Cuenta las palabras en los hashtags
        if (! empty($this->hash_tags)) {
            $hashTags = is_array($this->hash_tags) ? implode(',', $this->hash_tags) : $this->hash_tags;

            $words = $this->countWords(str_replace('#', '', $hashTags), $words, ',');
        }
        // Limpia las palabras que no se repitan
        $search = [];
        foreach ($words as $k => $c) {
            if ($c > 1) {
                $search[] = $k;
            }
        }

        $this->search = json_encode($search);
    }

    /**
     * Cuenta la cantidad de palabras en un texto
     *
     * @param $text, Texto de que va a contar
     * @param array $words, Array donde se guardan los resultados para acumular el resultado de varios conteos
     * @params $delimiter, Delimitador para separar las palabras
     * @return array
     */
    private function countWords($text, $words, $delimiter)
    {
        $explode = explode($delimiter, $text);

        foreach ($explode as $keyWord => $word) {

            // Limpio la palabra
            $word = $this->cleanWord($word);

            if (! isset($words[$word])) {
                $words[$word] = 1;
            } else {
                // Ya existe la palabra subo el contador
                $words[$word] ++;

                // Agrego tambien la combinacion de dos palabras para las palabras mas utilizadas
                if (isset($explode[$keyWord - 1])) {
                    // Si existe una palabra anterior, agrego esa y la actual
                    $twoWord =  $this->cleanWord($explode[$keyWord - 1]) . ' ' . $word;

                    if (! isset($words[$twoWord])) {
                        // Si no existe lo inicializo
                        $words[$twoWord] = 1;
                    } else {
                        // Si existe lo incremento
                        $words[$twoWord] ++;
                    }
                }

                // Agrego tambien la combinacion de tres palabras para las palabras mas utilizadas
                if (isset($explode[$keyWord - 1]) && isset($explode[$keyWord - 2])) {
                    // Si existe una palabra anterior, agrego esa y la actual
                    $threeWord =  $this->cleanWord($explode[$keyWord - 2]) . ' ' . $this->cleanWord($explode[$keyWord - 1]) . ' ' . $word;

                    if (! isset($words[$threeWord])) {
                        // Si no existe lo inicializo
                        $words[$threeWord] = 1;
                    } else {
                        // Si existe lo incremento
                        $words[$threeWord] ++;
                    }
                }
            }
        }

        return $words;
    }

    /**
     * Dejo lo palabra lo mas limpia posible
     *
     * @param $word
     * @return mixed
     */
    private function cleanWord($word)
    {
        $word = strtolower($word);
        $word = str_replace('á', 'a', $word);
        $word = str_replace('é', 'e', $word);
        $word = str_replace('í', 'i', $word);
        $word = str_replace('ó', 'o', $word);
        $word = str_replace('ú', 'u', $word);
        $word = str_replace("\n", '', $word);
        $word = str_replace("\r", '', $word);

        return $word;
    }

    /**
     * Obtiene las ultimas publicaciones
     *
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public static function getRecent($limit = 10)
    {
        return Publication::where('status', self::STATUS_PUBLISHED)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
        ;
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Publication extends Model
{
    /** Estatus de las publicaciones */
    const STATUS_PUBLISHED = 1;
    const STATUS_HIDDEN = 2;
    const STATUS_PENDING_PAY = 3;

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
        'video',
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

        $this->user_id = Auth::user()->id;

        return parent::save($options);
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
}

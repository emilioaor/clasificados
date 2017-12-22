<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** Estatus de la categoria */
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    protected $table = 'categories';

    protected $fillable = [
        'name', 'status',
    ];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->status = self::STATUS_ACTIVE;
        parent::__construct($attributes);
    }

    /**
     * Sub categorias de esta categoria principal
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subCategories()
    {
        return $this->hasMany('App\SubCategory', 'category_id');
    }

    /**
     * Todas las publicaciones asociadas a esta categoria
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function publications()
    {
        return $this->hasMany('App\Publication', 'category_id');
    }

    /**
     * Indica si tiene categorias
     *
     * @return bool
     */
    public function hasSubCategories()
    {
        return count($this->subCategories) > 0;
    }

    /**
     * Obtiene la primer SubCategoria con opciones configuradas
     *
     * @return mixed
     */
    public function getFirstSubCategoryWithOptions()
    {
        foreach ($this->subCategories as $subCategory) {
            if ($subCategory->hasOptions()) {
                return $subCategory;
            }
        }

        return null;
    }

    /**
     * Cree el metodo para generar las publicaciones de prueba
     * que se muestran en el index
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getPublicationsPreview()
    {
        return $this->publications()
            ->where('status', Publication::STATUS_PUBLISHED)
            ->orderBy('created_at', 'DESC')
            ->limit(6)
            ->get();
    }
}

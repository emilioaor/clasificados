<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'sub_categories';

    protected $fillable = [
        'name', 'category_id',
    ];

    /**
     * Categoria a la que pertenece
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id');
    }

    /**
     * Opciones configuradas para la subcategoria
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subCategoryOptions()
    {
        return $this->hasMany('App\SubCategoryOption', 'sub_category_id');
    }

    /**
     * Indica si esta subCategoria tiene opciones configuradas
     *
     * @return bool
     */
    public function hasOptions()
    {
        return count($this->subCategoryOptions) > 0;
    }
}

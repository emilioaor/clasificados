<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategoryOption extends Model
{
    protected $table = 'sub_categories_options';

    protected $fillable = [
        'name', 'sub_category_id',
    ];

    /**
     * subcategoria a la que pertenece
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subCategory()
    {
        return $this->belongsTo('App\SubCategory', 'sub_category_id');
    }

    /**
     * Cuando se asocia una categoria a la publicacion luego se selecciona un valor
     * para cada subcategoria. Esta relacion sirve para guardar el valor de cada
     * subcategoria de la categoria principal seleccionada en la publicacion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function publications()
    {
        return $this->belongsToMany('App\Publication', 'publications_options', 'sub_category_option_id', 'publication_id');
    }
}

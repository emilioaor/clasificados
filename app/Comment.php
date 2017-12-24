<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = [
        'publication_id', 'comment', 'parent', 'user_id',
    ];

    public function __construct(array $attribute = [])
    {
        parent::__construct($attribute);
    }

    /**
     * Modifica el objeto antes de persistir
     *
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        $this->user_id = Auth::user()->id;

        return parent::save($options);
    }

    /**
     * Publicacion en la que se publico este comentario
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function publication()
    {
        return $this->belongsTo('App\Publication', 'publication_id');
    }

    /**
     * Comentario padre
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo('App\Comment', 'parent');
    }

    /**
     * Todos los comentarios hijos
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany('App\Comment', 'parent');
    }

    /**
     * Usuario creador del comentario
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Obtiene los ultimos comentarios
     *
     * @param int $limit
     * @return $this
     */
    public static function getRecent($limit = 10)
    {
        return Comment::select(['comment', 'name', 'public_id', 'publications.id', 'title'])
            ->join('publications', 'comments.publication_id', '=', 'publications.id')
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->where('publications.status', Publication::STATUS_PUBLISHED)
            ->orderByDesc('comments.created_at')
            ->limit($limit)
            ->get()
        ;
    }
}

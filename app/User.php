<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /** Niveles de usuario */
    const LEVEL_USER = 1;
    const LEVEL_ADMIN = 2;

    /** Estatus de las notificaciones */
    const STATUS_NOTIFICATION_READ = 1;
    const STATUS_NOTIFICATION_UNREAD = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'level',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function __construct(array $attributes = [])
    {
        $this->level = self::LEVEL_USER;
        parent::__construct($attributes);
    }

    /**
     * Todas las publicaciones del usuario
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function publications()
    {
        return $this->hasMany('App\Publication', 'user_id');
    }

    /**
     * Todas las publicaciones en lista de deseos del usuario
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function whistListPublications()
    {
        return $this->belongsToMany('App\Publication', 'whist_list', 'user_id', 'publication_id');
    }

    /**
     * Indica si una publicacion esta en la lista de deseos
     * del usuario
     *
     * @param publicationId
     * @return bool
     */
    public function hasWhistListPublication($publicationId)
    {
        if ($this->whistListPublications()->find($publicationId)) {
            return true;
        }

        return false;
    }

    /**
     * Todas las notificaciones del usuario. Se generan cada hora en base a la
     * lista de deseos de cada usuario
     *
     * @return $this
     */
    public function notifications()
    {
        return $this->belongsToMany('App\Publication', 'notifications', 'user_id', 'publication_id')
            ->withPivot(['status']);
    }

    /**
     * Obtiene las ultimas notificaciones
     *
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function lastNotifications($limit = 10)
    {
        return $this->notifications()->orderByDesc('created_at')->limit($limit)->get();
    }
}

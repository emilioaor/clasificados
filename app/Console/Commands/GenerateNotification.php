<?php

namespace App\Console\Commands;

use App\Publication;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publication:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Busca todas las publicaciones de la ultima hora y la compara con la lista de
     * deseos de cada usuario, si coincide lo publicado con la lista de deseos del
     * usuario se le genera la notificacion
     *
     * @return mixed
     */
    public function handle()
    {
        DB::beginTransaction();

        $oneHourBefore = (new \DateTime())->modify('-59 minutes');
        $notifications = [];

        // Busco todas las publicaciones de una hora atras
        $publications = Publication::where('status', Publication::STATUS_PUBLISHED)
            ->where('created_at', '>=', $oneHourBefore)
            ->get()
        ;

        // Obtengo todos los usuarios
        $users = User::all();

        $this->info('Fecha en adelante: ' . $oneHourBefore->format('d-m-Y H:i:s'));
        $this->info('Publicaciones encontradas: ' . count($publications));

        foreach ($publications as $publication) {

            $search = json_decode($publication->search); // Palabras claves de esta publicacion

            foreach ($search as $s) {
                // Por cada palabra de la publicacion busco si existe una lista de deseos para el usuario

                foreach ($users as $user) {

                    // Evito que notifique sobre las publicaciones propias
                    if ($publication->user_id !== $user->id) {

                        $this->info('Buscando para usuario: ' . $user->email . ' la palabra: ' . $s);

                    /* | Hago una busqueda para ver si existe en la lista de deseos del
                     * | usuario, otra publicacion con palabras claves parecidas. Si
                     * | encuentra coincidencia entonces agrega la notificacion
                     * |-----------------------------------------------------------------*/

                        if (! isset($notifications[$user->id])) {
                            $notifications[$user->id] = [];
                        }

                        foreach ($user->whistListPublications as $whistList) {

                            if (str_replace($s, '', $whistList->search) !== $whistList->search) {
                                /* | Genero un array con esta estructura para evitar que se repita
                                 * | una misma publicacion para un mismo usuario
                                 * |--------------------------------------------------------------*/

                                $notifications[$user->id][$publication->id] = $publication;
                            }
                        }
                    }
                }
            }
        }

        // Genera las notificaciones
        foreach ($users as $user) {
            if (isset($notifications[$user->id]) && count($notifications[$user->id])) {
                // Si pasa por aqui es porque encontro coincidencias en el proceso anterior

                foreach ($notifications[$user->id] as $publication) {
                    // Por cada publicacion guardada genera la notificacion
                    $this->info('-------------------------------------------');
                    $this->info('Publicacion: ' . $publication->public_id);
                    $this->info('Palabras claves: ' . $publication->search);
                    $this->info('Agregada la notificación');

                    $user->notifications()->attach($publication->id, [
                        'status' => User::STATUS_NOTIFICATION_UNREAD,
                        'created_at' => new \DateTime(),
                    ]);
                }
            }
        }

        DB::commit();
    }
}

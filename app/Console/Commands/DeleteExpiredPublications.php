<?php

namespace App\Console\Commands;

use App\Publication;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteExpiredPublications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publication:delete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Caduca las publicaciones con 30 dias de antiguedad';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::beginTransaction();
        $expiredDate = new \DateTime();
        $expiredDate = $expiredDate->modify('-30 days');

        // Busco todas las publicaciones gratuitas con mas de 30 dias
        $publications = Publication::where('transaction', null)
            ->where('status', '<>', Publication::STATUS_EXPIRED)
            ->where('created_at', '<', $expiredDate)
            ->get();

        // Expira cada publicacion
        foreach ($publications as $publication) {

            $this->info('---------------------------------------------------');
            $publication->status = Publication::STATUS_EXPIRED;
            $publication->save();

            $this->info('Publicacion: ' . $publication->public_id);
            $this->info('Titulo: ' . $publication->title);
            $this->info('Categoria: ' . $publication->category->name);
            $this->info('Usuario: ' . $publication->user->name);
            $this->info('Telefono: ' . $publication->user->phone);
            $this->info('Creado: ' . $publication->created_at->format('d-m-Y h:i a'));
        }

        $this->info('---------------------------------------------------');
        $this->info('Publicaciones expiradas: ' . count($publications));

        DB::commit();
    }
}

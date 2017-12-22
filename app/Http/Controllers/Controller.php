<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Crea una alerta para mostrar como notificacion
     *
     * @param $message
     * @param string $alertType
     */
    protected function sessionMessages($message, $alertType = 'alert-success')
    {
        Session::flash('alert-message', $message);
        Session::flash('alert-type', $alertType);
    }
}

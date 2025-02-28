<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

//controller es el papa y le hereda funciones a otros
    public function get_response($message, $estado,$data)
    {
        return [
        "estado" =>$estado, 
        "mensaje"=> $message,
        "data" =>$data
        ];
    }

}

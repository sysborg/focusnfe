<?php

namespace Sysborg\FocusNFe\App\Http\Controllers\Api;


use Illuminate\Routing\Controller;
use Sysborg\FocusNFe\App\Services\ConsultaEmails;

class ConsultaEmailsController extends Controller
{
    
    public function getEmail(string $email)
    {
  

        $consultaEmail = new ConsultaEmails($email);

        return response()->json($consultaEmail->get($email));
    }
}


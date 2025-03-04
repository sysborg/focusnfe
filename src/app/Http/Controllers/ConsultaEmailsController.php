<?php 

namespace Sysborg\FocusNFe\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Sysborg\FocusNFe\app\Services\ConsultaEmails;

class ConsultaEmailsController extends Controller
{
   
    public function getEmail(string $email)
    {
        return response()->json(ConsultaEmails::get($email));
    }


    public function deleteEmail(string $email)
    {
        return response()->json(ConsultaEmails::delete($email));
    }
}

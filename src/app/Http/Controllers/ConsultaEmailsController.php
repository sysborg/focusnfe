<?php 

namespace Sysborg\FocusNFe\App\Http\Controllers;

use Illuminate\Routing\Controller;
use Sysborg\FocusNFe\App\Services\ConsultaEmails;

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

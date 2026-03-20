<?php

namespace Sysborg\FocusNfe\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Facades\Sysborg\FocusNfe\app\Services\ConsultaEmails;

/**
 * Controlador responsável por consultar e gerenciar e-mails bloqueados
 */
class ConsultaEmailsController extends Controller
{
    /**
     * @param string $email
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmail(string $email)
    {
        return response()->json(ConsultaEmails::get($email));
    }


    /**
     * @param string $email
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteEmail(string $email)
    {
        return response()->json(ConsultaEmails::delete($email));
    }
}

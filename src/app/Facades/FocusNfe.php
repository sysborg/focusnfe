<?php

namespace Sysborg\FocusNfe\app\Facades;

use Illuminate\Support\Facades\Facade;
use Sysborg\FocusNfe\app\Services\Backups;
use Sysborg\FocusNfe\app\Services\CEP;
use Sysborg\FocusNfe\app\Services\CFOP;
use Sysborg\FocusNfe\app\Services\CNAE;
use Sysborg\FocusNfe\app\Services\Cnpjs;
use Sysborg\FocusNfe\app\Services\ConsultaEmails;
use Sysborg\FocusNfe\app\Services\CTe;
use Sysborg\FocusNfe\app\Services\CTERecebidas;
use Sysborg\FocusNfe\app\Services\Empresas;
use Sysborg\FocusNfe\app\Services\MDFe;
use Sysborg\FocusNfe\app\Services\Municipios;
use Sysborg\FocusNfe\app\Services\NCM;
use Sysborg\FocusNfe\app\Services\NFCe;
use Sysborg\FocusNfe\app\Services\NFe;
use Sysborg\FocusNfe\app\Services\NFeRecebidas;
use Sysborg\FocusNfe\app\Services\NFSe;
use Sysborg\FocusNfe\app\Services\NFSeArquivo;
use Sysborg\FocusNfe\app\Services\NFSeNacional;
use Sysborg\FocusNfe\app\Services\NFSeRecebidas;
use Sysborg\FocusNfe\app\Services\Webhooks;

/**
 * Facade principal do pacote FocusNFe.
 *
 * @method static NFe nfe()
 * @method static NFCe nfce()
 * @method static CTe cte()
 * @method static MDFe mdfe()
 * @method static NFSe nfse()
 * @method static NFSeNacional nfseNacional()
 * @method static NFSeArquivo nfseArquivo()
 * @method static NFSeRecebidas nfseRecebidas()
 * @method static NFeRecebidas nfeRecebidas()
 * @method static CTERecebidas cteRecebidas()
 * @method static Empresas empresas()
 * @method static Webhooks webhooks()
 * @method static Backups backups()
 * @method static ConsultaEmails consultaEmails()
 * @method static Municipios municipios()
 * @method static CEP cep()
 * @method static CFOP cfop()
 * @method static CNAE cnae()
 * @method static NCM ncm()
 * @method static Cnpjs cnpjs()
 *
 * @see \Sysborg\FocusNfe\app\Services\FocusNfeManager
 */
class FocusNfe extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'focusnfe';
    }
}

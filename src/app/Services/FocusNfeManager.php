<?php

namespace Sysborg\FocusNfe\app\Services;

use Illuminate\Contracts\Container\Container;

/**
 * Manager principal do pacote FocusNFe.
 * Provê acesso centralizado a todos os serviços registrados no container.
 */
class FocusNfeManager
{
    public function __construct(private readonly Container $app) {}

    public function nfe(): NFe
    {
        return $this->app->make(NFe::class);
    }

    public function nfce(): NFCe
    {
        return $this->app->make(NFCe::class);
    }

    public function cte(): CTe
    {
        return $this->app->make(CTe::class);
    }

    public function mdfe(): MDFe
    {
        return $this->app->make(MDFe::class);
    }

    public function nfse(): NFSe
    {
        return $this->app->make(NFSe::class);
    }

    public function nfseNacional(): NFSeNacional
    {
        return $this->app->make(NFSeNacional::class);
    }

    public function nfseArquivo(): NFSeArquivo
    {
        return $this->app->make(NFSeArquivo::class);
    }

    public function nfseRecebidas(): NFSeRecebidas
    {
        return $this->app->make(NFSeRecebidas::class);
    }

    public function nfeRecebidas(): NFeRecebidas
    {
        return $this->app->make(NFeRecebidas::class);
    }

    public function cteRecebidas(): CTERecebidas
    {
        return $this->app->make(CTERecebidas::class);
    }

    public function empresas(): Empresas
    {
        return $this->app->make(Empresas::class);
    }

    public function webhooks(): Webhooks
    {
        return $this->app->make(Webhooks::class);
    }

    public function backups(): Backups
    {
        return $this->app->make(Backups::class);
    }

    public function consultaEmails(): ConsultaEmails
    {
        return $this->app->make(ConsultaEmails::class);
    }

    public function municipios(): Municipios
    {
        return $this->app->make(Municipios::class);
    }

    public function cep(): CEP
    {
        return $this->app->make(CEP::class);
    }

    public function cfop(): CFOP
    {
        return $this->app->make(CFOP::class);
    }

    public function cnae(): CNAE
    {
        return $this->app->make(CNAE::class);
    }

    public function ncm(): NCM
    {
        return $this->app->make(NCM::class);
    }

    public function cnpjs(): Cnpjs
    {
        return $this->app->make(Cnpjs::class);
    }
}

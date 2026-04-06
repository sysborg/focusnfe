<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Facade;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\Services\Backups;
use Sysborg\FocusNfe\app\Services\CEP;
use Sysborg\FocusNfe\app\Services\CFOP;
use Sysborg\FocusNfe\app\Services\CNAE;
use Sysborg\FocusNfe\app\Services\Cnpjs;
use Sysborg\FocusNfe\app\Services\ConsultaEmails;
use Sysborg\FocusNfe\app\Services\CTe;
use Sysborg\FocusNfe\app\Services\CTERecebidas;
use Sysborg\FocusNfe\app\Services\Empresas;
use Sysborg\FocusNfe\app\Services\FocusNfeManager;
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

class FocusNfeManagerTest extends TestCase
{
    private FocusNfeManager $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $container = new Container();
        $container->instance('config', new ConfigRepository([
            'focusnfe' => [
                'URL' => ['production' => 'https://api.focusnfe.com.br'],
                'log' => ['channel' => 'stack', 'level' => 'error'],
                'rate_limit' => ['enabled' => false],
                'retry' => ['times' => 1, 'sleep' => 0],
                'token' => 'test-token',
                'ambiente' => 'production',
            ],
        ]));
        $container->instance('http', new HttpFactory());
        $container->instance('log', new class () {
            public function channel(?string $channel = null): static { return $this; }
            public function error(string $message, array $context = []): void {}
            public function info(string $message, array $context = []): void {}
        });

        // Register all services as singletons (mimics SBFocusNFeProvider)
        $services = [
            Backups::class, CEP::class, CFOP::class, CNAE::class, Cnpjs::class,
            ConsultaEmails::class, CTe::class, CTERecebidas::class, Empresas::class,
            MDFe::class, Municipios::class, NCM::class, NFCe::class, NFe::class,
            NFeRecebidas::class, NFSe::class, NFSeArquivo::class, NFSeNacional::class,
            NFSeRecebidas::class, Webhooks::class,
        ];
        foreach ($services as $serviceClass) {
            $container->singleton($serviceClass, static fn () => new $serviceClass('test-token', 'production'));
        }

        Container::setInstance($container);
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($container);

        $this->manager = new FocusNfeManager($container);
    }

    public function test_nfe_retorna_instancia_correta(): void
    {
        $this->assertInstanceOf(NFe::class, $this->manager->nfe());
    }

    public function test_nfce_retorna_instancia_correta(): void
    {
        $this->assertInstanceOf(NFCe::class, $this->manager->nfce());
    }

    public function test_cte_retorna_instancia_correta(): void
    {
        $this->assertInstanceOf(CTe::class, $this->manager->cte());
    }

    public function test_mdfe_retorna_instancia_correta(): void
    {
        $this->assertInstanceOf(MDFe::class, $this->manager->mdfe());
    }

    public function test_nfse_retorna_instancia_correta(): void
    {
        $this->assertInstanceOf(NFSe::class, $this->manager->nfse());
    }

    public function test_nfse_nacional_retorna_instancia_correta(): void
    {
        $this->assertInstanceOf(NFSeNacional::class, $this->manager->nfseNacional());
    }

    public function test_nfse_arquivo_retorna_instancia_correta(): void
    {
        $this->assertInstanceOf(NFSeArquivo::class, $this->manager->nfseArquivo());
    }

    public function test_nfse_recebidas_retorna_instancia_correta(): void
    {
        $this->assertInstanceOf(NFSeRecebidas::class, $this->manager->nfseRecebidas());
    }

    public function test_nfe_recebidas_retorna_instancia_correta(): void
    {
        $this->assertInstanceOf(NFeRecebidas::class, $this->manager->nfeRecebidas());
    }

    public function test_cte_recebidas_retorna_instancia_correta(): void
    {
        $this->assertInstanceOf(CTERecebidas::class, $this->manager->cteRecebidas());
    }

    public function test_empresas_retorna_instancia_correta(): void
    {
        $this->assertInstanceOf(Empresas::class, $this->manager->empresas());
    }

    public function test_webhooks_retorna_instancia_correta(): void
    {
        $this->assertInstanceOf(Webhooks::class, $this->manager->webhooks());
    }

    public function test_backups_retorna_instancia_correta(): void
    {
        $this->assertInstanceOf(Backups::class, $this->manager->backups());
    }

    public function test_consulta_emails_retorna_instancia_correta(): void
    {
        $this->assertInstanceOf(ConsultaEmails::class, $this->manager->consultaEmails());
    }

    public function test_municipios_retorna_instancia_correta(): void
    {
        $this->assertInstanceOf(Municipios::class, $this->manager->municipios());
    }

    public function test_cep_retorna_instancia_correta(): void
    {
        $this->assertInstanceOf(CEP::class, $this->manager->cep());
    }

    public function test_cfop_retorna_instancia_correta(): void
    {
        $this->assertInstanceOf(CFOP::class, $this->manager->cfop());
    }

    public function test_cnae_retorna_instancia_correta(): void
    {
        $this->assertInstanceOf(CNAE::class, $this->manager->cnae());
    }

    public function test_ncm_retorna_instancia_correta(): void
    {
        $this->assertInstanceOf(NCM::class, $this->manager->ncm());
    }

    public function test_cnpjs_retorna_instancia_correta(): void
    {
        $this->assertInstanceOf(Cnpjs::class, $this->manager->cnpjs());
    }

    public function test_retorna_singleton_do_container(): void
    {
        $first = $this->manager->nfe();
        $second = $this->manager->nfe();

        $this->assertSame($first, $second);
    }
}

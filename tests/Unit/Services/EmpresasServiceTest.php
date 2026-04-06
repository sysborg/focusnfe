<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory as ValidationFactory;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\EmpresaDTO;
use Sysborg\FocusNfe\app\Events\EmpresaCreated;
use Sysborg\FocusNfe\app\Events\EmpresaDeleted;
use Sysborg\FocusNfe\app\Events\EmpresaUpdated;
use Sysborg\FocusNfe\app\Rules\CepRule;
use Sysborg\FocusNfe\app\Rules\CnaeRule;
use Sysborg\FocusNfe\app\Rules\CnpjRule;
use Sysborg\FocusNfe\app\Services\Empresas;

class EmpresasServiceTest extends TestCase
{
    private Empresas $service;
    private string $baseUrl = 'https://api.focusnfe.com.br';
    private string $token = 'test-token-123';
    private string $ambiente = 'production';

    protected function setUp(): void
    {
        parent::setUp();

        $container = new Container();
        $container->instance('config', new ConfigRepository([
            'focusnfe' => [
                'URL' => ['production' => $this->baseUrl],
                'log' => ['channel' => 'stack', 'level' => 'error'],
                'rate_limit' => ['enabled' => false],
                'retry' => ['times' => 1, 'sleep' => 0],
            ],
        ]));

        $translator = new Translator(new ArrayLoader(), 'pt_BR');
        $validatorFactory = new ValidationFactory($translator, $container);

        $container->instance('translator', $translator);
        $container->instance('validator', $validatorFactory);
        $container->instance('http', new HttpFactory());
        $container->instance('events', new Dispatcher($container));
        $container->instance('log', new class () {
            public function channel(?string $channel = null): static { return $this; }
            public function error(string $message, array $context = []): void {}
            public function debug(string $message, array $context = []): void {}
            public function info(string $message, array $context = []): void {}
            public function warning(string $message, array $context = []): void {}
        });

        Container::setInstance($container);
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($container);

        Validator::extend('cnpj', fn ($attribute, $value) => (new CnpjRule())->passes($attribute, (string) $value), (new CnpjRule())->message());
        Validator::extend('cep', fn ($attribute, $value) => (new CepRule())->passes($attribute, (string) $value), (new CepRule())->message());
        Validator::extend('cnae', fn ($attribute, $value) => (new CnaeRule())->passes($attribute, (string) $value), (new CnaeRule())->message());

        $this->service = new Empresas($this->token, $this->ambiente);
    }

    protected function tearDown(): void
    {
        Facade::clearResolvedInstances();
        parent::tearDown();
    }

    private function getEmpresaDTO(): EmpresaDTO
    {
        return new EmpresaDTO(
            razaoSocial: 'Empresa Teste LTDA',
            nomeFantasia: 'Empresa Teste',
            bairro: 'Centro',
            cep: 80045165,
            cnpj: '11222333000181',
            complemento: '',
            email: 'empresa@teste.com.br',
            inscricaoEstadual: '',
            inscricaoMunicipal: '',
            logradouro: 'Rua Teste',
            numero: 100,
            regimeTributario: 1,
            telefone: '41999999999',
            municipio: 'Curitiba',
            uf: 'PR',
            habilitaNfe: false,
            habilitaNfce: false,
            habilitaNfse: false,
            arquivoCertificado: '',
            senhaCertificado: '',
            cscNfceProducao: '',
            idTokenNfceProducao: '',
        );
    }

    public function test_cria_empresa_com_sucesso(): void
    {
        Http::fake([
            '*' => Http::response(['id' => 1, 'cnpj' => '11222333000181'], 201),
        ]);

        $response = $this->service->create($this->getEmpresaDTO());

        $this->assertSame(201, $response->status());
    }

    public function test_lista_empresas_com_sucesso(): void
    {
        Http::fake([
            '*' => Http::response(['data' => []], 200),
        ]);

        $response = $this->service->list(1);

        $this->assertTrue($response->ok());
    }

    public function test_get_empresa_por_id_com_sucesso(): void
    {
        Http::fake([
            $this->baseUrl . Empresas::URL . '/1' => Http::response(['id' => 1, 'cnpj' => '11222333000181'], 200),
        ]);

        $response = $this->service->get(1);

        $this->assertTrue($response->ok());
    }

    public function test_get_empresa_nao_encontrada(): void
    {
        Http::fake([
            '*' => Http::response(['message' => 'Not Found'], 404),
        ]);

        $response = $this->service->get(9999);

        $this->assertTrue($response->failed());
    }

    public function test_atualiza_empresa_com_sucesso(): void
    {
        Http::fake([
            '*' => Http::response(['id' => 1, 'cnpj' => '11222333000181'], 200),
        ]);

        $response = $this->service->update(1, $this->getEmpresaDTO());

        $this->assertTrue($response->ok());
    }

    public function test_deleta_empresa_com_sucesso(): void
    {
        Http::fake([
            '*' => Http::response([], 200),
        ]);

        $response = $this->service->delete(1);

        $this->assertTrue($response->ok());
    }
}

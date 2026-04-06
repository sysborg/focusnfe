<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ServiceCatalogTest extends TestCase
{
    #[DataProvider('servicesProvider')]
    public function test_services_possuem_url_constante_esperada(string $class, string $expectedUrl): void
    {
        $this->assertTrue(class_exists($class));
        $this->assertSame($expectedUrl, $class::URL);
    }

    #[DataProvider('servicesProvider')]
    public function test_services_sao_instanciaveis(string $class): void
    {
        $service = new $class('fake-token', 'production');
        $this->assertInstanceOf($class, $service);
    }

    public static function servicesProvider(): array
    {
        return [
            'CEP' => ['Sysborg\\FocusNfe\\app\\Services\\CEP', '/v2/ceps'],
            'CFOP' => ['Sysborg\\FocusNfe\\app\\Services\\CFOP', '/v2/cfops'],
            'CNAE' => ['Sysborg\\FocusNfe\\app\\Services\\CNAE', '/v2/codigos_cnae'],
            'Cnpjs' => ['Sysborg\\FocusNfe\\app\\Services\\Cnpjs', '/v2/cnpjs'],
            'ConsultaEmails' => ['Sysborg\\FocusNfe\\app\\Services\\ConsultaEmails', '/v2/blocked_emails'],
            'CTe' => ['Sysborg\\FocusNfe\\app\\Services\\CTe', '/v2/cte'],
            'CTERecebidas' => ['Sysborg\\FocusNfe\\app\\Services\\CTERecebidas', '/v2/ctes_recebidas'],
            'Empresas' => ['Sysborg\\FocusNfe\\app\\Services\\Empresas', '/v2/empresas'],
            'MDFe' => ['Sysborg\\FocusNfe\\app\\Services\\MDFe', '/v2/mdfe'],
            'Municipios' => ['Sysborg\\FocusNfe\\app\\Services\\Municipios', '/v2/municipios'],
            'NCM' => ['Sysborg\\FocusNfe\\app\\Services\\NCM', '/v2/ncms'],
            'NFCe' => ['Sysborg\\FocusNfe\\app\\Services\\NFCe', '/v2/nfce'],
            'NFe' => ['Sysborg\\FocusNfe\\app\\Services\\NFe', '/v2/nfe'],
            'NFeRecebidas' => ['Sysborg\\FocusNfe\\app\\Services\\NFeRecebidas', '/v2/nfes_recebidas'],
            'NFSe' => ['Sysborg\\FocusNfe\\app\\Services\\NFSe', '/v2/nfse'],
            'NFSeArquivo' => ['Sysborg\\FocusNfe\\app\\Services\\NFSeArquivo', '/v2/lotes_rps'],
            'NFSeNacional' => ['Sysborg\\FocusNfe\\app\\Services\\NFSeNacional', '/v2/nfsen'],
            'NFSeRecebidas' => ['Sysborg\\FocusNfe\\app\\Services\\NFSeRecebidas', '/v2/nfses_recebidas'],
            'Backups' => ['Sysborg\\FocusNfe\\app\\Services\\Backups', '/v2/backups/%s.json'],
        ];
    }
}

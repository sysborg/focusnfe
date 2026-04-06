<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\EmpresaDTO;
use Sysborg\FocusNfe\app\DTO\NFSeDTO;
use Sysborg\FocusNfe\app\DTO\NFSeNDTO;
use Sysborg\FocusNfe\app\DTO\NFCeDTO;
use Sysborg\FocusNfe\app\DTO\NFeDTO;
use Sysborg\FocusNfe\app\Events\CTeAutorizado;
use Sysborg\FocusNfe\app\Events\CTeCancelado;
use Sysborg\FocusNfe\app\Events\EmpresaCreated;
use Sysborg\FocusNfe\app\Events\EmpresaDeleted;
use Sysborg\FocusNfe\app\Events\EmpresaUpdated;
use Sysborg\FocusNfe\app\Events\MDFeAutorizado;
use Sysborg\FocusNfe\app\Events\MDFeCancelado;
use Sysborg\FocusNfe\app\Events\MDFeEncerrado;
use Sysborg\FocusNfe\app\Events\NFCeAutorizada;
use Sysborg\FocusNfe\app\Events\NFCeCancelada;
use Sysborg\FocusNfe\app\Events\NFSeCancelada;
use Sysborg\FocusNfe\app\Events\NFSeEnviada;
use Sysborg\FocusNfe\app\Events\NFSeNacionalAutorizada;
use Sysborg\FocusNfe\app\Events\NFSeNacionalCancelada;
use Sysborg\FocusNfe\app\Events\NFeAutorizada;
use Sysborg\FocusNfe\app\Events\NFeCancelada;
use Sysborg\FocusNfe\app\Events\NFeInutilizada;
use Sysborg\FocusNfe\app\Services\CTe;
use Sysborg\FocusNfe\app\Services\Empresas;
use Sysborg\FocusNfe\app\Services\MDFe;
use Sysborg\FocusNfe\app\Services\NFCe;
use Sysborg\FocusNfe\app\Services\NFSe;
use Sysborg\FocusNfe\app\Services\NFSeNacional;
use Sysborg\FocusNfe\app\Services\NFe;
use Sysborg\FocusNfe\tests\Traits\BootstrapsFacadesTrait;

class ServiceEventDispatchTest extends TestCase
{
    use BootstrapsFacadesTrait;

    private string $baseUrl = 'https://api.focusnfe.com.br';

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootstrapFacades();
    }

    public function test_nfe_dispara_eventos_de_envio_cancelamento_e_inutilizacao(): void
    {
        $captured = [];
        app('events')->listen(NFeAutorizada::class, function (NFeAutorizada $event) use (&$captured): void { $captured['envia'] = $event; });
        app('events')->listen(NFeCancelada::class, function (NFeCancelada $event) use (&$captured): void { $captured['cancela'] = $event; });
        app('events')->listen(NFeInutilizada::class, function (NFeInutilizada $event) use (&$captured): void { $captured['inutiliza'] = $event; });

        Http::fake([
            $this->baseUrl . NFe::URL . '*' => Http::sequence()
                ->push(['status' => 'processando_autorizacao'], 202)
                ->push(['status' => 'cancelado'], 200)
                ->push(['status' => 'autorizado'], 200),
        ]);

        $service = new NFe('test-token', 'production');
        $service->envia($this->makeNFeDto(), 'nfe-evento');
        $service->cancela('nfe-evento');
        $service->inutilizar(['justificativa' => 'Teste', 'serie' => '1']);

        $this->assertSame(202, $captured['envia']->data['status']);
        $this->assertSame('cancelado', $captured['cancela']->data['data']['status']);
        $this->assertTrue($captured['inutiliza']->data['success']);
    }

    public function test_nfce_dispara_eventos_de_envio_e_cancelamento(): void
    {
        $captured = [];
        app('events')->listen(NFCeAutorizada::class, function (NFCeAutorizada $event) use (&$captured): void { $captured['envia'] = $event; });
        app('events')->listen(NFCeCancelada::class, function (NFCeCancelada $event) use (&$captured): void { $captured['cancela'] = $event; });

        Http::fake([
            $this->baseUrl . NFCe::URL => Http::response(['status' => 'processando_autorizacao'], 202),
            $this->baseUrl . NFCe::URL . '/nfce-evento' => Http::response(['status' => 'cancelado'], 200),
        ]);

        $service = new NFCe('test-token', 'production');
        $service->envia($this->makeNFCeDto());
        $service->cancela('nfce-evento');

        $this->assertSame(202, $captured['envia']->data['status']);
        $this->assertSame('cancelado', $captured['cancela']->data['data']['status']);
    }

    public function test_cte_dispara_eventos_de_envio_e_cancelamento(): void
    {
        $captured = [];
        app('events')->listen(CTeAutorizado::class, function (CTeAutorizado $event) use (&$captured): void { $captured['envia'] = $event; });
        app('events')->listen(CTeCancelado::class, function (CTeCancelado $event) use (&$captured): void { $captured['cancela'] = $event; });

        Http::fake([
            $this->baseUrl . CTe::URL . '?ref=cte-evento' => Http::response(['status' => 'processando_autorizacao'], 202),
            $this->baseUrl . CTe::URL . '/cte-evento' => Http::response(['status' => 'cancelado'], 200),
        ]);

        $service = new CTe('test-token', 'production');
        $service->envia(['emitente' => ['cnpj' => '07504505000132']], 'cte-evento');
        $service->cancela('cte-evento');

        $this->assertSame(202, $captured['envia']->data['status']);
        $this->assertSame('cancelado', $captured['cancela']->data['data']['status']);
    }

    public function test_mdfe_dispara_eventos_de_envio_cancelamento_e_encerramento(): void
    {
        $captured = [];
        app('events')->listen(MDFeAutorizado::class, function (MDFeAutorizado $event) use (&$captured): void { $captured['envia'] = $event; });
        app('events')->listen(MDFeCancelado::class, function (MDFeCancelado $event) use (&$captured): void { $captured['cancela'] = $event; });
        app('events')->listen(MDFeEncerrado::class, function (MDFeEncerrado $event) use (&$captured): void { $captured['encerra'] = $event; });

        Http::fake([
            $this->baseUrl . MDFe::URL => Http::response(['status' => 'processando_autorizacao'], 202),
            $this->baseUrl . MDFe::URL . '/mdfe-evento' => Http::response(['status' => 'cancelado'], 200),
            $this->baseUrl . MDFe::URL . '/mdfe-evento/encerrar' => Http::response(['status' => 'encerrado'], 200),
        ]);

        $service = new MDFe('test-token', 'production');
        $service->envia(['emitente' => ['cnpj' => '07504505000132']]);
        $service->cancela('mdfe-evento');
        $service->encerra('mdfe-evento');

        $this->assertSame(202, $captured['envia']->data['status']);
        $this->assertSame('cancelado', $captured['cancela']->data['data']['status']);
        $this->assertSame('encerrado', $captured['encerra']->data['data']['status']);
    }

    public function test_nfse_dispara_eventos_de_envio_e_cancelamento(): void
    {
        $captured = [];
        app('events')->listen(NFSeEnviada::class, function (NFSeEnviada $event) use (&$captured): void { $captured['envia'] = $event; });
        app('events')->listen(NFSeCancelada::class, function (NFSeCancelada $event) use (&$captured): void { $captured['cancela'] = $event; });

        Http::fake([
            $this->baseUrl . NFSe::URL . '?ref=nfse-evento' => Http::response(['status' => 'processando_autorizacao'], 202),
            $this->baseUrl . NFSe::URL . '/nfse-evento' => Http::response(['status' => 'cancelado'], 200),
        ]);

        $service = new NFSe('test-token', 'production');
        $service->envia($this->makeNFSeDto(), 'nfse-evento');
        $service->cancela('nfse-evento', 'Erro de emissao');

        $this->assertSame(202, $captured['envia']->data['status']);
        $this->assertSame('cancelado', $captured['cancela']->data['data']['status']);
    }

    public function test_nfse_nacional_dispara_eventos_de_envio_e_cancelamento(): void
    {
        $captured = [];
        app('events')->listen(NFSeNacionalAutorizada::class, function (NFSeNacionalAutorizada $event) use (&$captured): void { $captured['envia'] = $event; });
        app('events')->listen(NFSeNacionalCancelada::class, function (NFSeNacionalCancelada $event) use (&$captured): void { $captured['cancela'] = $event; });

        Http::fake([
            $this->baseUrl . NFSeNacional::URL => Http::response(['status' => 'processando_autorizacao'], 202),
            $this->baseUrl . NFSeNacional::URL . '/nfsen-evento' => Http::response(['status' => 'cancelado'], 200),
        ]);

        $service = new NFSeNacional('test-token', 'production');
        $service->envia($this->makeNFSeNDto());
        $service->cancela('nfsen-evento');

        $this->assertSame(202, $captured['envia']->data['status']);
        $this->assertSame('cancelado', $captured['cancela']->data['data']['status']);
    }

    public function test_empresas_dispara_eventos_de_criacao_atualizacao_e_exclusao(): void
    {
        $captured = [];
        app('events')->listen(EmpresaCreated::class, function (EmpresaCreated $event) use (&$captured): void { $captured['create'] = $event; });
        app('events')->listen(EmpresaUpdated::class, function (EmpresaUpdated $event) use (&$captured): void { $captured['update'] = $event; });
        app('events')->listen(EmpresaDeleted::class, function (EmpresaDeleted $event) use (&$captured): void { $captured['delete'] = $event; });

        Http::fake([
            $this->baseUrl . Empresas::URL => Http::response(['id' => 1], 201),
            $this->baseUrl . Empresas::URL . '/1' => Http::sequence()
                ->push(['id' => 1, 'updated' => true], 200)
                ->push([], 200),
        ]);

        $service = new Empresas('test-token', 'production');
        $dto = $this->makeEmpresaDto();

        $service->create($dto);
        $service->update(1, $dto);
        $service->delete(1);

        $this->assertSame(201, $captured['create']->data['status']);
        $this->assertTrue($captured['update']->data['success']);
        $this->assertTrue($captured['delete']->data['success']);
    }

    private function makeNFeDto(): NFeDTO
    {
        return NFeDTO::fromArray([
            'natureza_operacao' => 'Venda',
            'data_emissao' => '2026-01-15T10:00:00-03:00',
            'tipo_documento' => 1,
            'local_destino' => 1,
            'finalidade_emissao' => 1,
            'consumidor_final' => 1,
            'presenca_comprador' => 1,
            'cnpj_emitente' => '07504505000132',
            'inscricao_estadual_emitente' => '111111111111',
            'logradouro_emitente' => 'Rua Teste',
            'numero_emitente' => '100',
            'bairro_emitente' => 'Centro',
            'municipio_emitente' => 'Sao Paulo',
            'uf_emitente' => 'SP',
            'regime_tributario_emitente' => 1,
            'nome_destinatario' => 'Cliente',
            'cpf_destinatario' => '12345678909',
            'logradouro_destinatario' => 'Rua Cliente',
            'numero_destinatario' => '200',
            'bairro_destinatario' => 'Centro',
            'municipio_destinatario' => 'Sao Paulo',
            'uf_destinatario' => 'SP',
            'indicador_inscricao_estadual_destinatario' => 9,
            'itens' => [[
                'numero_item' => 1,
                'codigo_produto' => 'P001',
                'descricao' => 'Produto',
                'codigo_ncm' => '84713012',
                'cfop' => '5102',
                'unidade_comercial' => 'UN',
                'quantidade_comercial' => 1,
                'valor_unitario_comercial' => 100.0,
                'valor_total_bruto' => 100.0,
                'icms_situacao_tributaria' => '400',
                'icms_origem' => 0,
                'pis_situacao_tributaria' => '07',
                'cofins_situacao_tributaria' => '07',
            ]],
            'formas_pagamento' => [
                ['forma_pagamento' => '01', 'valor_pagamento' => 100.0],
            ],
        ]);
    }

    private function makeNFCeDto(): NFCeDTO
    {
        return NFCeDTO::fromArray([
            'natureza_operacao' => 'VENDA AO CONSUMIDOR',
            'data_emissao' => '2026-01-15T10:00:00-03:00',
            'presenca_comprador' => 1,
            'modalidade_frete' => 9,
            'local_destino' => 1,
            'cnpj_emitente' => '07504505000132',
            'regime_tributario_emitente' => 1,
            'logradouro_emitente' => 'Rua Teste',
            'numero_emitente' => '100',
            'bairro_emitente' => 'Centro',
            'municipio_emitente' => 'Sao Paulo',
            'uf_emitente' => 'SP',
            'cep_emitente' => '01001000',
            'itens' => [[
                'numero_item' => 1,
                'codigo_produto' => 'P001',
                'descricao' => 'Produto',
                'codigo_ncm' => '84713012',
                'cfop' => '5102',
                'unidade_comercial' => 'UN',
                'quantidade_comercial' => 1,
                'valor_unitario_comercial' => 100.0,
                'valor_total_bruto' => 100.0,
                'icms_situacao_tributaria' => '400',
                'icms_origem' => 0,
                'pis_situacao_tributaria' => '07',
                'cofins_situacao_tributaria' => '07',
            ]],
            'formas_pagamento' => [
                ['forma_pagamento' => '01', 'valor_pagamento' => 100.0],
            ],
        ]);
    }

    private function makeNFSeDto(): NFSeDTO
    {
        return NFSeDTO::fromArray([
            'natureza_operacao' => '1',
            'data_emissao' => '2026-01-15T10:00:00-03:00',
            'prestador' => [
                'cnpj' => '07504505000132',
                'inscricao_municipal' => '123456',
                'codigo_municipio' => '3550308',
            ],
            'tomador' => [
                'cpf' => '12345678909',
                'razao_social' => 'Cliente Teste',
                'email' => 'cliente@example.com',
                'endereco' => [
                    'logradouro' => 'Rua Cliente',
                    'numero' => '100',
                    'bairro' => 'Centro',
                    'codigo_municipio' => '3550308',
                    'uf' => 'SP',
                    'cep' => '01001000',
                ],
            ],
            'servico' => [
                'aliquota' => 0.05,
                'iss_retido' => false,
                'item_lista_servico' => '0107',
                'codigo_tributario_municipio' => '0107',
                'discriminacao' => 'Servico prestado',
                'valor_servicos' => 100.0,
            ],
        ]);
    }

    private function makeNFSeNDto(): NFSeNDTO
    {
        return NFSeNDTO::fromArray([
            'data_emissao' => '2026-01-15T10:00:00-03:00',
            'data_competencia' => '2026-01-15',
            'codigo_municipio_emissora' => 3550308,
            'cnpj_prestador' => '07504505000132',
            'inscricao_municipal_prestador' => '123456',
            'codigo_opcao_simples_nacional' => 1,
            'regime_especial_tributacao' => 0,
            'cnpj_tomador' => '11222333000181',
            'cpf_tomador' => '12345678909',
            'razao_social_tomador' => 'Cliente Teste',
            'codigo_municipio_tomador' => 3550308,
            'codigo_municipio_prestacao' => 3550308,
            'codigo_tributacao_nacional_iss' => '010701',
            'descricao_servico' => 'Servico prestado',
            'valor_servico' => 100.0,
            'tributacao_iss' => 1,
            'tipo_retencao_iss' => 1,
            'email_tomador' => 'cliente@example.com',
        ]);
    }

    private function makeEmpresaDto(): EmpresaDTO
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
}

<?php

namespace Sysborg\FocusNfe\tests\Unit\Services;

use Carbon\Carbon;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\NFeDTO;
use Sysborg\FocusNfe\app\DTO\NFeEmissaoResponseDTO;
use Sysborg\FocusNfe\app\Services\NFe;

class NFeServiceTest extends TestCase
{
    private NFe $service;
    private string $baseUrl = 'https://api.focusnfe.com.br';
    private string $sandboxBaseUrl = 'https://homologacao.focusnfe.com.br';
    private string $ref = 'nfe-001';

    protected function setUp(): void
    {
        parent::setUp();

        $container = new Container();
        $container->instance('config', new ConfigRepository([
            'focusnfe' => [
                'URL' => [
                    'production' => $this->baseUrl,
                    'sandbox' => $this->sandboxBaseUrl,
                ],
                'log' => ['channel' => 'stack', 'level' => 'error'],
                'rate_limit' => ['enabled' => false],
                'retry' => ['times' => 1, 'sleep' => 0],
            ],
        ]));
        $container->instance('log', new class () {
            public function channel(?string $channel = null): static { return $this; }
            public function error(string $message, array $context = []): void {}
            public function warning(string $message, array $context = []): void {}
            public function info(string $message, array $context = []): void {}
            public function debug(string $message, array $context = []): void {}
        });
        $container->instance('http', new HttpFactory());
        $container->instance('events', new Dispatcher($container));
        Container::setInstance($container);
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($container);

        $this->service = new NFe('test-token', 'production');
    }

    private function makeDto(): NFeDTO
    {
        return NFeDTO::fromArray([
            'natureza_operacao' => 'Venda de produto',
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
            'municipio_emitente' => 'São Paulo',
            'uf_emitente' => 'SP',
            'regime_tributario_emitente' => 1,
            'nome_destinatario' => 'Cliente Teste',
            'cpf_destinatario' => '12345678909',
            'logradouro_destinatario' => 'Rua Cliente',
            'numero_destinatario' => '200',
            'bairro_destinatario' => 'Bairro',
            'municipio_destinatario' => 'São Paulo',
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

    public function test_envia_nfe_com_sucesso(): void
    {
        Http::fake([
            $this->baseUrl . NFe::URL . '*' => Http::response([
                'status' => 'processando_autorizacao',
                'ref' => $this->ref,
            ], 202),
        ]);

        $response = $this->service->envia($this->makeDto(), $this->ref);

        $this->assertEquals(202, $response->status());
        $this->assertEquals('processando_autorizacao', $response->json('status'));
    }

    public function test_envia_nfe_para_url_de_sandbox_quando_configurado(): void
    {
        $service = new NFe('test-token', 'sandbox');

        Http::fake([
            $this->sandboxBaseUrl . NFe::URL . '*' => Http::response([
                'status' => 'processando_autorizacao',
                'ref' => $this->ref,
            ], 202),
        ]);

        $response = $service->envia($this->makeDto(), $this->ref);

        $this->assertSame(202, $response->status());
        Http::assertSent(function ($request): bool {
            return $request->url() === $this->sandboxBaseUrl . NFe::URL . '?ref=' . $this->ref;
        });
    }

    /**
     * @dataProvider enviaDtoStatusProvider
     */
    public function test_envia_dto_classifica_response_por_status_code(
        int $httpStatus,
        array|string $body,
        string $resultado,
        string $assertionMethod,
        bool $sucesso
    ): void {
        Http::fake([
            $this->baseUrl . NFe::URL . '*' => Http::response($body, $httpStatus),
        ]);

        $dto = $this->service->enviaDto($this->makeDto(), $this->ref);

        $this->assertInstanceOf(NFeEmissaoResponseDTO::class, $dto);
        $this->assertSame($httpStatus, $dto->http_status);
        $this->assertSame($resultado, $dto->resultado);
        $this->assertSame($sucesso, $dto->sucesso);
        $this->assertTrue($dto->{$assertionMethod}());
    }

    public static function enviaDtoStatusProvider(): array
    {
        return [
            '201 autorizada' => [
                201,
                [
                    'cnpj_emitente' => '07504505000132',
                    'ref' => 'nfe-001',
                    'status' => 'autorizado',
                    'status_sefaz' => '100',
                    'mensagem_sefaz' => 'Autorizado o uso da NF-e',
                    'chave_nfe' => 'NFe4119060750450500013255001000000221923094166',
                    'numero' => '22',
                    'serie' => '1',
                    'caminho_xml_nota_fiscal' => '/xml/nfe.xml',
                    'caminho_danfe' => '/danfe/nfe.pdf',
                ],
                NFeEmissaoResponseDTO::RESULTADO_AUTORIZADA,
                'autorizada',
                true,
            ],
            '202 processando' => [
                202,
                [
                    'cnpj_emitente' => '07504505000132',
                    'ref' => 'nfe-001',
                    'status' => 'processando_autorizacao',
                ],
                NFeEmissaoResponseDTO::RESULTADO_PROCESSANDO,
                'processando',
                true,
            ],
            '400 requisicao invalida' => [
                400,
                [
                    'codigo' => 'requisicao_invalida',
                    'mensagem' => 'Parâmetro "ref" não informado.',
                ],
                NFeEmissaoResponseDTO::RESULTADO_REQUISICAO_INVALIDA,
                'requisicaoInvalida',
                false,
            ],
            '401 nao autorizado' => [
                401,
                'HTTP Basic: Access denied',
                NFeEmissaoResponseDTO::RESULTADO_NAO_AUTORIZADO,
                'naoAutorizado',
                false,
            ],
            '415 formato invalido' => [
                415,
                [
                    'codigo' => 'formato_invalido',
                    'mensagem' => 'Recebida requisição vazia quando eram esperados dados.',
                ],
                NFeEmissaoResponseDTO::RESULTADO_FORMATO_INVALIDO,
                'formatoInvalido',
                false,
            ],
            '422 erro processamento' => [
                422,
                [
                    'codigo' => 'erro_validacao_schema',
                    'mensagem' => 'Erro na validação do Schema XML, verifique o detalhamento dos erros.',
                    'erros' => [
                        ['campo' => 'tipo_documento', 'mensagem' => 'Tipo documento não pode ser vazio'],
                    ],
                ],
                NFeEmissaoResponseDTO::RESULTADO_ERRO_PROCESSAMENTO,
                'erroProcessamento',
                false,
            ],
        ];
    }

    public function test_envia_dto_preserva_campos_da_resposta_autorizada(): void
    {
        Http::fake([
            $this->baseUrl . NFe::URL . '*' => Http::response([
                'cnpj_emitente' => '07504505000132',
                'ref' => $this->ref,
                'status' => 'autorizado',
                'status_sefaz' => '100',
                'mensagem_sefaz' => 'Autorizado o uso da NF-e',
                'chave_nfe' => 'NFe4119060750450500013255001000000221923094166',
                'numero' => '22',
                'serie' => '1',
                'caminho_xml_nota_fiscal' => '/xml/nfe.xml',
                'caminho_danfe' => '/danfe/nfe.pdf',
            ], 201),
        ]);

        $dto = $this->service->enviaDto($this->makeDto(), $this->ref);

        $this->assertSame('07504505000132', $dto->cnpj_emitente);
        $this->assertSame($this->ref, $dto->ref);
        $this->assertSame('autorizado', $dto->status);
        $this->assertSame('100', $dto->status_sefaz);
        $this->assertSame('NFe4119060750450500013255001000000221923094166', $dto->chave_nfe);
        $this->assertSame('/xml/nfe.xml', $dto->caminho_xml_nota_fiscal);
        $this->assertSame('/danfe/nfe.pdf', $dto->caminho_danfe);
    }

    public function test_envia_dto_preserva_erros_de_validacao_422(): void
    {
        Http::fake([
            $this->baseUrl . NFe::URL . '*' => Http::response([
                'codigo' => 'erro_validacao_schema',
                'mensagem' => 'Erro na validação do Schema XML, verifique o detalhamento dos erros.',
                'erros' => [
                    ['campo' => 'tipo_documento', 'mensagem' => 'Tipo documento não pode ser vazio'],
                ],
            ], 422),
        ]);

        $dto = $this->service->enviaDto($this->makeDto(), $this->ref);

        $this->assertSame('erro_validacao_schema', $dto->codigo);
        $this->assertSame('Erro na validação do Schema XML, verifique o detalhamento dos erros.', $dto->mensagem);
        $this->assertSame('tipo_documento', $dto->erros[0]['campo']);
        $this->assertSame('Tipo documento não pode ser vazio', $dto->erros[0]['mensagem']);
    }

    public function test_get_nfe_autorizada(): void
    {
        Http::fake([
            $this->baseUrl . NFe::URL . '/' . $this->ref => Http::response([
                'status' => 'autorizado',
                'ref' => $this->ref,
                'chave_nfe' => '35260107504505000132550010000000011234567890',
            ], 200),
        ]);

        $response = $this->service->get($this->ref);

        $this->assertEquals(200, $response->status());
        $this->assertEquals('autorizado', $response->json('status'));
    }

    public function test_get_nfe_completa(): void
    {
        Http::fake([
            $this->baseUrl . NFe::URL . '/' . $this->ref . '?completa=1' => Http::response([
                'status' => 'autorizado',
                'requisicao_nota_fiscal' => ['natureza_operacao' => 'Venda'],
            ], 200),
        ]);

        $response = $this->service->get($this->ref, true);

        $this->assertEquals(200, $response->status());
        $this->assertEquals('Venda', $response->json('requisicao_nota_fiscal.natureza_operacao'));
    }

    public function test_cancela_nfe(): void
    {
        Http::fake([
            $this->baseUrl . NFe::URL . '/' . $this->ref => Http::response([
                'status' => 'cancelado',
                'ref' => $this->ref,
            ], 200),
        ]);

        $response = $this->service->cancela($this->ref);

        $this->assertEquals(200, $response->status());
        $this->assertEquals('cancelado', $response->json('status'));
    }

    public function test_carta_correcao(): void
    {
        Http::fake([
            $this->baseUrl . NFe::URL . '/' . $this->ref . '/carta_correcao' => Http::response([
                'status' => 'autorizado',
                'mensagem_sefaz' => 'Evento registrado e vinculado a NF-e',
            ], 200),
        ]);

        $response = $this->service->cartaCorrecao($this->ref, ['correcao' => 'Correção de dado']);

        $this->assertEquals(200, $response->status());
    }

    public function test_inutilizar_nfe(): void
    {
        Http::fake([
            $this->baseUrl . NFe::URL . '/inutilizacao' => Http::response([
                'status' => 'autorizado',
                'serie' => '1',
            ], 200),
        ]);

        $response = $this->service->inutilizar([
            'cnpj' => '07504505000132',
            'serie' => '1',
            'numero_inicial' => '10',
            'numero_final' => '12',
            'justificativa' => 'Teste de inutilizacao',
        ]);

        $this->assertEquals(200, $response->status());
        $this->assertEquals('autorizado', $response->json('status'));
    }

    public function test_inutilizacoes(): void
    {
        Http::fake([
            $this->baseUrl . NFe::URL . '/inutilizacoes' => Http::response([], 200),
        ]);

        $response = $this->service->inutilizacoes();

        $this->assertEquals(200, $response->status());
    }

    public function test_reenvia_email(): void
    {
        $email = 'cliente@exemplo.com';

        Http::fake([
            $this->baseUrl . NFe::URL . "/$this->ref/email" => Http::response([
                'mensagem' => 'Emails agendados para envio',
            ], 200),
        ]);

        $response = $this->service->reenviaEmail($this->ref, $email);

        $this->assertEquals(200, $response->status());
        $this->assertEquals('Emails agendados para envio', $response->json('mensagem'));
        Http::assertSent(function ($request) use ($email): bool {
            return $request->url() === $this->baseUrl . NFe::URL . "/$this->ref/email"
                && $request['emails'] === [$email];
        });
    }

    public function test_download_xml(): void
    {
        Http::fake([
            $this->baseUrl . NFe::URL . '/' . $this->ref . '?completa=1' => Http::response([
                'status' => 'autorizado',
                'xml' => '<NFe>conteudo</NFe>',
            ], 200),
        ]);

        $response = $this->service->downloadXml($this->ref);

        $this->assertEquals(200, $response->status());
        $this->assertEquals('<NFe>conteudo</NFe>', $response->json('xml'));
    }

    public function test_insucesso_entrega(): void
    {
        Http::fake([
            $this->baseUrl . NFe::URL . "/$this->ref/insucesso_entrega" => Http::response([
                'status' => 'autorizado',
            ], 200),
        ]);

        $response = $this->service->insucessoEntrega($this->ref, [
            'data_tentativa' => '2026-01-15',
            'numero_tentativas' => 1,
            'motivo' => 1,
        ]);

        $this->assertEquals(200, $response->status());
    }

    public function test_cancela_insucesso_entrega(): void
    {
        Http::fake([
            $this->baseUrl . NFe::URL . "/$this->ref/insucesso_entrega" => Http::response([
                'status' => 'autorizado',
                'numero_cancelamento_insucesso_entrega' => 1,
            ], 200),
        ]);

        $response = $this->service->cancelaInsucessoEntrega($this->ref);

        $this->assertEquals(200, $response->status());
        $this->assertEquals(1, $response->json('numero_cancelamento_insucesso_entrega'));
    }

    public function test_ator_interessado(): void
    {
        Http::fake([
            $this->baseUrl . NFe::URL . "/$this->ref/ator_interessado" => Http::response([
                'status' => 'autorizado',
            ], 200),
        ]);

        $response = $this->service->atorInteressado($this->ref, [
            'tipo_ator' => 1,
            'cnpj' => '07504505000132',
        ]);

        $this->assertEquals(200, $response->status());
    }

    public function test_prorrogacao_icms(): void
    {
        Http::fake([
            $this->baseUrl . NFe::URL . "/$this->ref/prorrogacao_icms" => Http::response([
                'status' => 'autorizado',
            ], 200),
        ]);

        $response = $this->service->prorrogacaoIcms($this->ref, ['tipo' => 1]);

        $this->assertEquals(200, $response->status());
    }

    public function test_registra_econf(): void
    {
        Http::fake([
            $this->baseUrl . NFe::URL . "/$this->ref/econf" => Http::response([
                'protocolo' => 'ECONF-001',
                'status' => 'autorizado',
            ], 201),
        ]);

        $response = $this->service->registraEconf($this->ref, [
            'forma_pagamento' => '01',
            'valor' => 100.0,
        ]);

        $this->assertEquals(201, $response->status());
        $this->assertEquals('ECONF-001', $response->json('protocolo'));
    }

    public function test_consulta_econf(): void
    {
        $protocolo = 'ECONF-001';

        Http::fake([
            $this->baseUrl . NFe::URL . "/$this->ref/econf/$protocolo" => Http::response([
                'protocolo' => $protocolo,
                'status' => 'autorizado',
            ], 200),
        ]);

        $response = $this->service->consultaEconf($this->ref, $protocolo);

        $this->assertEquals(200, $response->status());
        $this->assertEquals($protocolo, $response->json('protocolo'));
    }

    public function test_cancela_econf(): void
    {
        $protocolo = 'ECONF-001';

        Http::fake([
            $this->baseUrl . NFe::URL . "/$this->ref/econf/$protocolo" => Http::response([
                'status' => 'cancelado',
            ], 200),
        ]);

        $response = $this->service->cancelaEconf($this->ref, $protocolo);

        $this->assertEquals(200, $response->status());
        $this->assertEquals('cancelado', $response->json('status'));
    }

    public function test_reenviar_hook(): void
    {
        Http::fake([
            $this->baseUrl . NFe::URL . "/$this->ref/hook" => Http::response([
                ['id' => 'hook-1', 'event' => 'nfe'],
            ], 200),
        ]);

        $response = $this->service->reenviarHook($this->ref);

        $this->assertEquals(200, $response->status());
        $this->assertEquals('hook-1', $response->json('0.id'));
    }

    public function test_nfe_dto_inclui_formas_pagamento_no_payload(): void
    {
        $dto = $this->makeDto();
        $payload = $dto->toArray();

        $this->assertArrayHasKey('formas_pagamento', $payload);
        $this->assertNotEmpty($payload['formas_pagamento']);
        $this->assertEquals('01', $payload['formas_pagamento'][0]['forma_pagamento']);
    }

    public function test_nfe_dto_inclui_campos_opcionais_quando_informados(): void
    {
        $dto = NFeDTO::fromArray(array_merge($this->makeDto()->toArray(), [
            'data_emissao' => '2026-01-15T10:00:00-03:00',
            'cep_emitente' => '01001000',
            'email_emitente' => 'emitente@empresa.com',
            'informacoes_adicionais_contribuinte' => 'Pedido #123',
            'documentos_referenciados' => [['chave_nfe' => '35260107504505000132550010000000011234567890']],
        ]));

        $payload = $dto->toArray();

        $this->assertEquals('01001000', $payload['cep_emitente']);
        $this->assertEquals('emitente@empresa.com', $payload['email_emitente']);
        $this->assertEquals('Pedido #123', $payload['informacoes_adicionais_contribuinte']);
        $this->assertNotEmpty($payload['notas_referenciadas']);
    }
}

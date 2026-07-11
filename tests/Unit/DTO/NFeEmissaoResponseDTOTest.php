<?php

namespace Sysborg\FocusNfe\tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\NFeEmissaoResponseDTO;

class NFeEmissaoResponseDTOTest extends TestCase
{
    public function test_from_array_classifica_nfe_autorizada_201(): void
    {
        $dto = NFeEmissaoResponseDTO::fromArray([
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
        ], 201);

        $this->assertTrue($dto->autorizada());
        $this->assertTrue($dto->sucesso);
        $this->assertSame(NFeEmissaoResponseDTO::RESULTADO_AUTORIZADA, $dto->resultado);
        $this->assertSame('100', $dto->status_sefaz);
        $this->assertSame('/danfe/nfe.pdf', $dto->caminho_danfe);
    }

    public function test_from_array_classifica_nfe_processando_202(): void
    {
        $dto = NFeEmissaoResponseDTO::fromArray([
            'cnpj_emitente' => '07504505000132',
            'ref' => 'nfe-001',
            'status' => 'processando_autorizacao',
        ], 202);

        $this->assertTrue($dto->processando());
        $this->assertTrue($dto->sucesso);
        $this->assertSame(NFeEmissaoResponseDTO::RESULTADO_PROCESSANDO, $dto->resultado);
    }

    public function test_from_array_classifica_erros_documentados(): void
    {
        $cases = [
            400 => [NFeEmissaoResponseDTO::RESULTADO_REQUISICAO_INVALIDA, 'requisicaoInvalida'],
            401 => [NFeEmissaoResponseDTO::RESULTADO_NAO_AUTORIZADO, 'naoAutorizado'],
            415 => [NFeEmissaoResponseDTO::RESULTADO_FORMATO_INVALIDO, 'formatoInvalido'],
            422 => [NFeEmissaoResponseDTO::RESULTADO_ERRO_PROCESSAMENTO, 'erroProcessamento'],
        ];

        foreach ($cases as $httpStatus => [$resultado, $method]) {
            $dto = NFeEmissaoResponseDTO::fromArray([
                'codigo' => 'erro_teste',
                'mensagem' => 'Mensagem de erro',
                'erros' => [['campo' => 'tipo_documento', 'mensagem' => 'Campo obrigatório']],
            ], $httpStatus);

            $this->assertSame($httpStatus, $dto->http_status);
            $this->assertSame($resultado, $dto->resultado);
            $this->assertFalse($dto->sucesso);
            $this->assertTrue($dto->{$method}());
            $this->assertSame('erro_teste', $dto->codigo);
            $this->assertCount(1, $dto->erros);
        }
    }

    public function test_from_array_preserva_body_textual_para_401_html(): void
    {
        $dto = NFeEmissaoResponseDTO::fromArray([], 401, 'HTTP Basic: Access denied');

        $this->assertTrue($dto->naoAutorizado());
        $this->assertSame('HTTP Basic: Access denied', $dto->raw_body);
        $this->assertSame([], $dto->raw);
    }
}

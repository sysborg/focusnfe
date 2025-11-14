<?php

namespace Sysborg\FocusNFe\tests\Traits;

use Illuminate\Support\Facades\Http;

trait MockHttpResponseTrait
{
    /**
     * Mock de resposta HTTP genérico
     *
     * @param string $url
     * @param array|string $response
     * @param int $status
     * @return void
     */
    protected function mockHttpResponse(string $url, array|string $response, int $status = 200): void
    {
        $responseBody = is_array($response) ? json_encode($response) : $response;

        Http::fake([
            $url => Http::response($responseBody, $status)
        ]);
    }

    /**
     * Mock de resposta de NFSe autorizada
     *
     * @param string $url
     * @return void
     */
    protected function mockNFSeAutorizada(string $url): void
    {
        $this->mockHttpResponse($url, [
            'cnpj_prestador' => '07504505000132',
            'ref' => 'nfs-2',
            'numero_rps' => '224',
            'serie_rps' => '1',
            'status' => 'autorizado',
            'numero' => '233',
            'codigo_verificacao' => 'DU1M-M2Y',
            'data_emissao' => '2019-05-27T00:00:00-03:00',
            'url' => 'https://example.com/nfse/233',
            'caminho_xml_nota_fiscal' => '/arquivos/07504505000132_12345/202401/XMLsNFSe/nfse.xml'
        ], 201);
    }

    /**
     * Mock de resposta de NFSe processando autorização
     *
     * @param string $url
     * @return void
     */
    protected function mockNFSeProcessandoAutorizacao(string $url): void
    {
        $this->mockHttpResponse($url, [
            'cnpj_prestador' => '07504505000132',
            'ref' => 'nfs-2',
            'status' => 'processando_autorizacao'
        ], 202);
    }

    /**
     * Mock de resposta de NFSe com erro de autorização
     *
     * @param string $url
     * @return void
     */
    protected function mockNFSeErroAutorizacao(string $url): void
    {
        $this->mockHttpResponse($url, [
            'cnpj_prestador' => '07504505000132',
            'ref' => 'nfs-2',
            'numero_rps' => '224',
            'serie_rps' => '1',
            'status' => 'erro_autorizacao',
            'erros' => [
                [
                    'codigo' => 'E145',
                    'mensagem' => 'Regime Especial de Tributação ausente/inválido.',
                    'correcao' => null
                ]
            ]
        ], 200);
    }

    /**
     * Mock de resposta de NFSe cancelada
     *
     * @param string $url
     * @return void
     */
    protected function mockNFSeCancelada(string $url): void
    {
        $this->mockHttpResponse($url, [
            'cnpj_prestador' => '07504505000132',
            'ref' => 'nfs-2',
            'numero_rps' => '224',
            'serie_rps' => '1',
            'status' => 'cancelado',
            'numero' => '233',
            'codigo_verificacao' => 'DU1M-M2Y',
            'data_emissao' => '2019-05-27T00:00:00-03:00',
            'url' => 'https://example.com/nfse/233',
            'caminho_xml_nota_fiscal' => '/arquivos/07504505000132_12345/202401/XMLsNFSe/nfse.xml',
            'caminho_xml_cancelamento' => '/arquivos/07504505000132_12345/202401/XMLsNFSe/nfse-can.xml'
        ], 200);
    }

    /**
     * Mock de resposta de erro no cancelamento
     *
     * @param string $url
     * @return void
     */
    protected function mockNFSeErroCancelamento(string $url): void
    {
        $this->mockHttpResponse($url, [
            'status' => 'erro_cancelamento',
            'erros' => [
                [
                    'codigo' => 'E523',
                    'mensagem' => 'nota que você está tentando cancelar está fora do prazo permitido para cancelamento',
                    'correcao' => null
                ]
            ]
        ], 400);
    }

    /**
     * Mock de resposta de requisição inválida
     *
     * @param string $url
     * @return void
     */
    protected function mockNFSeRequisicaoInvalida(string $url): void
    {
        $this->mockHttpResponse($url, [
            'codigo' => 'requisicao_invalida',
            'mensagem' => 'Parâmetro "prestador.codigo_municipio" não informado'
        ], 400);
    }

    /**
     * Mock de resposta de NFSe já cancelada
     *
     * @param string $url
     * @return void
     */
    protected function mockNFSeJaCancelada(string $url): void
    {
        $this->mockHttpResponse($url, [
            'codigo' => 'nfe_cancelada',
            'mensagem' => 'Nota Fiscal já cancelada'
        ], 400);
    }

    /**
     * Mock de resposta de reenvio de email com sucesso
     *
     * @param string $url
     * @return void
     */
    protected function mockNFSeReenvioEmailSucesso(string $url): void
    {
        $this->mockHttpResponse($url, [
            'status' => 'email_reenviado',
            'mensagem' => 'Email reenviado com sucesso'
        ], 200);
    }
}

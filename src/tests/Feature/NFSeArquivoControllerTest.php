<?php

namespace Sysborg\FocusNfe\tests\Feature;

use Sysborg\FocusNfe\tests\mocks\Stub\NFSeArquivoStub;
use Sysborg\FocusNfe\app\Services\NFSeArquivo;
use Sysborg\FocusNfe\tests\mocks\NFSeArquivoMock;

class NFSeArquivoControllerTest extends Common
{
    use NFSeArquivoMock;

    /**
     * Teste de envio de NFSe por arquivo com sucesso.
     * 
     * @return void
     */
    public function test_envia_nfse_arquivo_sucesso(): void
    {
        $inputData = []; // Simulando o envio do arquivo
        $expectedKeys = array_keys(NFSeArquivoStub::EnviaNFSeArquivoSucesso());

        $this->mockHttp(
            config('focusnfe.URL.production') . NFSeArquivo::URL,
            'EnviaNFSeArquivoSucesso',
            201
        );

        $response = $this->post($this->prefix . '/nfse-arquivo', $inputData);
        $response->assertStatus(201);
        $response->assertJsonStructure($expectedKeys);
    }

    /**
     * Teste de erro ao consultar NFSe enviada por arquivo.
     * 
     * @return void
     */
    public function test_consulta_nfse_arquivo_erro(): void
    {
        $expectedKeys = array_keys(NFSeArquivoStub::consultaNFSeArquivoErro());

        $this->mockHttp(
            config('focusnfe.URL.production') . NFSeArquivo::URL . '/consulta',
            'consultaNFSeArquivoErro',
            404
        );

        $response = $this->get($this->prefix . '/nfse-arquivo/consulta');
        $json = $response->json();

        $response->assertStatus(404);
        $response->assertJsonStructure($expectedKeys);
        $response->assertEquals($json['erro'], 'Lote não encontrado com a referência informada.');
    }
}

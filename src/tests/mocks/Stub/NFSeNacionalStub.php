<?php

namespace Sysborg\FocusNfe\tests\mocks\Stub;

class NFSeNacionalStub {

    /**
     * Mock de request para a emissão de NFSe Nacional.
     * 
     * @return array
     */
    public static function request(): array
    {
        return [
            'data_emissao' => '2024-05-07T07:34:56-0300',
            'data_competencia' => '2024-05-07',
            'codigo_municipio_emissora' => 4106902,

            'cnpj_prestador' => '18765499000199',
            'inscricao_municipal_prestador' => '12345',
            'codigo_opcao_simples_nacional' => 2,
            'regime_especial_tributacao' => 0,

            'cnpj_tomador' => '07504505000132',
            'razao_social_tomador' => 'Acras Tecnologia da Informação LTDA',
            'codigo_municipio_tomador' => 4106902,
            'cep_tomador' => '80045165',
            'logradouro_tomador' => 'Rua Dias da Rocha Filho',
            'numero_tomador' => '999',
            'complemento_tomador' => 'Prédio 04 - Sala 34C',
            'bairro_tomador' => 'Alto da XV',
            'telefone_tomador' => '41 3256-8060',
            'email_tomador' => 'contato@focusnfe.com.br',

            'codigo_municipio_prestacao' => 4106902,
            'codigo_tributacao_nacional_iss' => '010701',
            'descricao_servico' => 'Nota emitida em caráter de TESTE',
            'valor_servico' => 1.00,
            'tributacao_iss' => 1,
            'tipo_retencao_iss' => 1,
        ];
    }


   
    /**
     * Retorna dados mocados de NFSe com status processando autorização
     * 
     * @return string
     */
    public static function processandoAutorizacaoEnvio(): string
    {
        return json_encode([
            'cnpj_prestador' => 'CNPJ_PRESTADOR',
            'ref' => 'REFERENCIA',
            'status' => 'processando_autorizacao'
        ]);
    }

      /**
     * Retorna dados mocados para requisição inválida
     * 
     * @return string
     */
    public static function requisicaoInvalida(): string
    {
        return json_encode([
            'codigo' => 'requisicao_invalida',
            'mensagem' => 'Parâmetro "codigo_municipio_emissora" não informado'
        ]);
    }

     /**
     * Retorna dados mocados de NFSe autorizada
     * 
     * @return string
     */
    public static function autorizada(): string
    {
        return json_encode([
            'cnpj_prestador' => '18765499000199',
            'ref' => '12345',
            'numero_rps' => '123',
            'serie_rps' => '1',
            'tipo_rps' => '1',
            'status' => 'autorizado',
            'numero' => '1245',
            'codigo_verificacao' => '12345678901234567890',
            'data_emissao' => '2024-05-07T07:34:56-03:00',
            'url' => 'https://www.nfse.gov.br/consultapublica/?tpc=1&chave=1234567890123456789012345678901234567890',
            'caminho_xml_nota_fiscal' => '/arquivos/18765499000199_166/202405/XMLsNFSe/18765499000199-1245-nfse.xml',
            'url_danfse' => 'https://focusnfe.s3.sa-east-1.amazonaws.com/arquivos/18765499000199_166/202405/DANFSEs/NFSe187654990001994106902-14018919393-43-12345678901234567890123456789012345678901234567890.pdf'
        ]);
    }

     /**
     * Retorna dados mocados de NFSe cancelada
     * 
     * @return string
     */
    public static function cancelada(): string
{
    return json_encode([
        'cnpj_prestador' => '18765499000199',
        'ref' => '12345',
        'numero_rps' => '123',
        'serie_rps' => '1',
        'tipo_rps' => '1',
        'status' => 'cancelado',
        'numero' => '1245',
        'codigo_verificacao' => '1234567890123456789012345678901234567890',
        'data_emissao' => '2024-05-07T07:34:56-03:00',
        'url' => 'https://www.nfse.gov.br/consultapublica/?tpc=1&chave=12345678901234567890123456789012345678901234567890',
        'caminho_xml_nota_fiscal' => '/arquivos/18765499000199_166/202405/XMLsNFSe/187654990001994106902-14018919393-43-123456789012345678901234567890-nfse.xml',
        'caminho_xml_cancelamento' => '/arquivos/187654990001994106902-14018919393-43-123456789012345678901234567890-can.xml',
        'url_danfse' => 'https://focusnfe.s3.sa-east-1.amazonaws.com/arquivos/187654990001994106902-14018919393-43-123456789012345678901234567890.pdf'
    ]);
}


/*
* Mock de processnado autorização na consulta
*
* @return string 
*/

public static function processandoAutorizacaoConsulta(): string
{
    return json_encode([
        'cnpj_prestador' => '18765499000199',
        'ref' => '12345',
        'numero_rps' => '123',
        'serie_rps' => '1',
        'tipo_rps' => '1',
        'status' => 'processando_autorizacao'
    ]);
}


    /**
     * Mock de erro na autorização
     *
     * @return string
     */
    public static function erroAutorizacao(): string
    {
        return json_encode([
            'cnpj_prestador' => '18765499000199',
            'ref' => '12345',
            'numero_rps' => '123',
            'serie_rps' => '1',
            'status' => 'erro_autorizacao',
            'erros' => [
                [
                    'codigo' => 'E0014',
                    'mensagem' => 'Conjunto de Série, Número, Código do Município Emissor e CNPJ/CPF informado nesta DPS já existe em uma NFS-e gerada a partir de uma DPS enviada anteriormente.',
                    'correcao' => null
                ]
            ]
        ]);
    }

    /**
     * Mock de erro no cancelamento
     *
     * @return string
     */
    public static function erroCancelamento(): string
    {
        return json_encode([
            'status' => 'erro_cancelamento',
            'erros' => [
                [
                    'codigo' => 'E523',
                    'mensagem' => 'Nota que você está tentando cancelar está fora do prazo permitido para cancelamento',
                    'correcao' => null
                ]
            ]
        ]);
    }

    /**
     * Mock de NFSe já cancelada
     *
     * @return string
     */
    public static function nfseJaCancelada(): string
    {
        return json_encode([
            'codigo' => 'nfe_cancelada',
            'mensagem' => 'Nota Fiscal já cancelada'
        ]);
    }
}

<?php
namespace Sysborg\FocusNFe\tests\mocks\Stub;

class NFSeStub {

  /**
     * Mock de request para a emissão de NFSe.
     * 
     * @return array
     */
    public static function request(): array
    {
        return [
            'data_emissao' => '2017-09-21T22:15:00',
            'prestador' => [
                'cnpj' => '18765499000199',
                'inscricao_municipal' => '12345',
                'codigo_municipio' => '3516200'
            ],
            'tomador' => [
                'cnpj' => '07504505000132',
                'razao_social' => 'Acras Tecnologia da Informação LTDA',
                'email' => 'contato@focusnfe.com.br',
                'endereco' => [
                    'logradouro' => 'Rua Dias da Rocha Filho',
                    'numero' => '999',
                    'complemento' => 'Prédio 04 - Sala 34C',
                    'bairro' => 'Alto da XV',
                    'codigo_municipio' => '4106902',
                    'uf' => 'PR',
                    'cep' => '80045165'
                ]
            ],
            'servico' => [
                'aliquota' => 3.00,
                'discriminacao' => 'Nota fiscal referente a serviços prestados',
                'iss_retido' => false,
                'item_lista_servico' => '0107',
                'codigo_tributario_municipio' => '620910000',
                'valor_servicos' => 1.00
            ]
        ];
    }

    
  /**
   * Mock de processando autorizacao
   * 
   * @return string
   */
  public static function processandoAutorizacao(): string
  {
    return json_encode([
      'cnpj_prestador' => 'CNPJ_PRESTADOR',
      'ref' => 'REFERENCIA',
      'status' => 'processando_autorizacao'
    ]);
  }

  /**
   * Requisição inválida
   * 
   * @return string
   */
  public static function requisicaoInvalida(): string
  {
    return json_encode([
      'codigo' => 'requisicao_invalida',
      'mensagem' => 'Parâmetro "prestador.codigo_municipio" não informado'
    ]);
  }

  /**
   * Mock de NFSe autorizada
   * 
   * @return string
   */
  public static function autorizada(): string
  {
    return json_encode([
      'cnpj_prestador' => '07504505000132',
      'ref' => 'nfs-2',
      'numero_rps' => '224',
      'serie_rps' => '1',
      'status' => 'autorizado',
      'numero' => '233',
      'codigo_verificacao' => 'DU1M-M2Y',
      'data_emissao' => '2019-05-27T00:00:00-03:00',
      'url' => 'https://200.189.192.82/PilotoNota_Portal/Default.aspx?doc=07504505000132&num=233&cod=DUMMY',
      'caminho_xml_nota_fiscal' => '/arquivos/07504505000132_12345/202401/XMLsNFSe/075045050001324106902-004949940-433-DUMMY-nfse.xml'
    ]);
  }

  /**
   * Mock de NFSe cancelada
   * 
   * @return string
   */
  public static function cancelada(): string
  {
    return json_encode([
      'cnpj_prestador' => '07504505000132',
      'ref' => 'nfs-2',
      'numero_rps' => '224',
      'serie_rps' => '1',
      'status' => 'cancelado',
      'numero' => '233',
      'codigo_verificacao' => 'DU1M-M2Y',
      'data_emissao' => '2019-05-27T00:00:00-03:00',
      'url' => 'https://200.189.192.82/PilotoNota_Portal/Default.aspx?doc=07504505000132&num=233&cod=DUMMY',
      'caminho_xml_nota_fiscal' => '/arquivos/07504505000132_12345/202401/XMLsNFSe/075045050001324106902-004949940-433-DUMMY-nfse.xml',
      'caminho_xml_cancelamento' => '/arquivos/07504505000132_12345/202401/XMLsNFSe/075045050001324106902-004949940-433-DUMMY-can.xml'
    ]);
  }

  /**
   * Mock de NFSe com erro na autorização
   * 
   * @return string
   */
  public static function erroAutorizacao(): string
  {
    return json_encode([
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
    ]);
  }

  /**
   * Mock de NFSe processando autorização
   * 
   * @return string
   */
  public static function erroProcessandoAutorizacao(): string
  {
    return json_encode([
      'cnpj_prestador' => '07504505000132',
      'ref' => 'nfs-2',
      'numero_rps' => '224',
      'serie_rps' => '1',
      'status' => 'processando_autorizacao'
    ]);
  }

  /**
   * Mock de NFSe cancelada com sucesso
   * 
   * @return string
   */
  public static function canceladaSucesso(): string
  {
    return json_encode([
      'status' => 'cancelado'
    ]);
  }

  /**
   * Mock de NFSe com erro no cancelamento
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
          'mensagem' => 'nota que você está tentando cancelar está fora do prazo permitido para cancelamento',
          'correcao' => null
        ]
      ]
    ]);
  }

  /**
   * Mock de NFSe cancelamento quando NFSe já está cancelada
   * 
   * @return string
   */
  public static function canceladaJaCancelada(): string
  {
    return json_encode([
      'codigo' => 'nfe_cancelada',
      'mensagem' => 'Nota Fiscal já cancelada'
    ]);
  }
}

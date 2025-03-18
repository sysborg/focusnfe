<?php
namespace Sysborg\FocusNFe\tests\mocks\Stub;

class EmpresaStub {
  /**
   * Mock de request da empresa
   * 
   * @return array
   */
  public static function request(): array
  {
    return [
      'nome' => 'Nome da empresa Ltda',
      'nome_fantasia' => 'Nome Fantasia',
      'bairro' => 'Vila Isabel',
      'cep' => 80210000,
      'cnpj' => '10964044000164',
      'complemento' => 'Loja 1',
      'discrimina_impostos' => true,
      'email' => 'test@example.com',
      'enviar_email_destinatario' => true,
      'inscricao_estadual' => 1234,
      'inscricao_municipal' => 46532,
      'logradouro' => 'Rua João da Silva',
      'numero' => 153,
      'regime_tributario' => 1,
      'telefone' => '4130333333',
      'municipio' => 'Curitiba',
      'uf' => 'PR',
      'habilita_nfe' => true,
      'habilita_nfce' => true,
      'arquivo_certificado_base64' => 'MIIj4gIBAzCCI54GCSqGSIb3DQEHAaCC..apagado…ASD==',
      'senha_certificado' => 123456,
      'csc_nfce_producao' => 'ABCDEF',
      'id_token_nfce_producao' => '00001'
    ];
  }

  /**
   * Retorna dados mocados de erro validação certificado
   * 
   * @return array
   */
  public static function erroValidacaoCertificado(): array
  {
    return [
      'codigo' => 'erro_validacao',
      'mensagem' => 'Erro de validação',
      'erros' => [
        [
          'codigo' => 'erro_validacao',
          'mensagem' => 'Arquivo certificado base64 Houve um erro ao instalar o certificado, verifique se a senha está correto e o arquivo está no formato PFX ou P12 codificado em base64',
          'campo' => 'arquivo_certificado_base64'
        ]
      ]
    ];
  }

  /**
   * Retorna dados mocados de erro de senha ou de cnpj de o utra empresa do certificado digital
   * 
   * @return array
   */
  public static function erroGeralCertificado(): array
  {
    return [
      'codigo' => 'erro_validacao',
      'mensagem' => 'Erro de validação',
      'erros' => [
        [
          'codigo' => 'erro_validacao',
          'mensagem' => 'Arquivo certificado base64 Certificado não pertence ao CNPJ informado',
          'campo' => 'arquivo_certificado_base64'
        ]
      ]
    ];
  }

  /**
   * Retorna dados mocados de empresa nao encontrada
   * 
   * @return array
   */
  public static function erroEmpresaNaoEncontrada(): array
  {
    return [
      'codigo' => 'nao_encontrado',
      'mensagem' => 'Empresa não encontrada'
    ];
  }

  /**
   * Retorna dados mocados de permissão negada não pertence ao domínio do cliente
   * 
   * @return array
   */
  public static function erroPermissaoNegada(): array
  {
    return [
      'codigo' => 'permissao_negada',
      'mensagem' => 'Empresa não encontrada como propriedade da revenda'
    ];
  }

  /**
   * Retorna dados mocados para parâmetros inválidos
   * 
   * @return array
   */
  public static function erroParametrosInvalidos(): array
  {
    return [
      'codigo' => 'parametros_invalidos',
      'mensagem' => 'Existe um problema no JSON recebido: 822: unexpected token at \'empresa_id=12079\'',
    ];
  }

  /**
   * Retorna dados mocados de uma empresa criada em array
   * 
   * @return array
   */
  public static function sucesso(): array
  {
    return [
      "id" => 123,
      "nome" => "Razão social da empresa",
      "nome_fantasia" => "Nome fantasia da empresa",
      "inscricao_estadual" => "123456",
      "inscricao_municipal" => "123456",
      "bairro" => "Bairro",
      "cargo_responsavel" => null,
      "cep" => "12345-678",
      "cnpj" => "12345678000123",
      "cpf" => "",
      "codigo_municipio" => "12345678",
      "codigo_pais" => "1058",
      "codigo_uf" => "26",
      "complemento" => "",
      "cpf_cnpj_contabilidade" => "",
      "cpf_responsavel" => "",
      "discrimina_impostos" => false,
      "email" => "",
      "enviar_email_destinatario" => false,
      "enviar_email_homologacao" => false,
      "habilita_nfce" => false,
      "habilita_nfe" => false,
      "habilita_nfse" => false,
      "habilita_nfsen_producao" => false,
      "habilita_nfsen_homologacao" => false,
      "habilita_cte" => false,
      "habilita_mdfe" => false,
      "habilita_manifestacao" => false,
      "habilita_manifestacao_homologacao" => false,
      "habilita_manifestacao_cte" => false,
      "habilita_manifestacao_cte_homologacao" => false,
      "logradouro" => "Logradouro",
      "municipio" => "Municipio",
      "nome_responsavel" => "",
      "numero" => "1234",
      "pais" => "Pais",
      "regime_tributario" => "3",
      "telefone" => "",
      "uf" => "UF",
      "habilita_contingencia_offline_nfce" => false,
      "habilita_contingencia_epec_nfce" => false,
      "reaproveita_numero_nfce_contingencia" => false,
      "mostrar_danfse_badge" => true,
      "csc_nfce_producao" => null,
      "id_token_nfce_producao" => null,
      "csc_nfce_homologacao" => null,
      "id_token_nfce_homologacao" => null,
      "proximo_numero_nfe_producao" => null,
      "proximo_numero_nfe_homologacao" => null,
      "serie_nfe_producao" => null,
      "serie_nfe_homologacao" => null,
      "proximo_numero_nfse_producao" => null,
      "proximo_numero_nfse_homologacao" => null,
      "proximo_numero_nfsen_producao" => null,
      "proximo_numero_nfsen_homologacao" => null,
      "serie_nfse_producao" => null,
      "serie_nfse_homologacao" => null,
      "serie_nfsen_producao" => null,
      "serie_nfsen_homologacao" => null,
      "proximo_numero_nfce_producao" => null,
      "proximo_numero_nfce_homologacao" => null,
      "serie_nfce_producao" => null,
      "serie_nfce_homologacao" => null,
      "proximo_numero_cte_producao" => null,
      "proximo_numero_cte_homologacao" => null,
      "serie_cte_producao" => null,
      "serie_cte_homologacao" => null,
      "proximo_numero_cte_os_producao" => null,
      "proximo_numero_cte_os_homologacao" => null,
      "serie_cte_os_producao" => null,
      "serie_cte_os_homologacao" => null,
      "proximo_numero_mdfe_producao" => null,
      "proximo_numero_mdfe_homologacao" => null,
      "serie_mdfe_producao" => null,
      "serie_mdfe_homologacao" => null,
      "certificado_valido_ate" => "2025-04-01T15:03:25-03:00",
      "certificado_valido_de" => "2024-04-01T15:03:25-03:00",
      "certificado_cnpj" => "12345678000123",
      "certificado_especifico" => false,
      "data_ultima_emissao" => null,
      "caminho_logo" => null,
      "login_responsavel" => "",
      "senha_responsavel_preenchida" => false,
      "orientacao_danfe" => "portrait",
      "recibo_danfe" => true,
      "exibe_sempre_ipi_danfe" => false,
      "exibe_issqn_danfe" => false,
      "exibe_impostos_adicionais_danfe" => false,
      "exibe_fatura_danfe" => false,
      "exibe_unidade_tributaria_danfe" => false,
      "exibe_desconto_itens" => false,
      "exibe_sempre_volumes_danfe" => false,
      "exibe_composicao_carga_mdfe" => false,
      "data_inicio_recebimento_nfe" => null,
      "data_inicio_recebimento_cte" => null,
      "habilita_csrt_nfe" => true,
      "nfe_sincrono" => false,
      "nfe_sincrono_homologacao" => false,
      "mdfe_sincrono" => false,
      "mdfe_sincrono_homologacao" => false,
      "smtp_endereco" => null,
      "smtp_dominio" => null,
      "smtp_autenticacao" => null,
      "smtp_porta" => null,
      "smtp_login" => null,
      "smtp_remetente" => null,
      "smtp_responder_para" => null,
      "smtp_modo_verificacao_openssl" => null,
      "smtp_habilita_starttlls" => true,
      "smtp_ssl" => false,
      "smtp_tls" => false,
      "token_producao" => "",
      "token_homologacao" => ""
    ];
  }
}

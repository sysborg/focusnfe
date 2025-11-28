<?php

namespace Sysborg\FocusNFe\tests\Unit\DTO;

use PHPUnit\Framework\TestCase;
use Sysborg\FocusNfe\app\DTO\EmpresaDTO;
use Illuminate\Validation\ValidationException;

class EmpresaDTOTest extends TestCase
{
    /**
     * Dados válidos básicos para teste
     */
    private function getDadosValidos(): array
    {
        return [
            'razaoSocial' => 'Empresa Teste LTDA',
            'nomeFantasia' => 'Empresa Teste',
            'bairro' => 'Centro',
            'cep' => 80000000,
            'cnpj' => '11222333000181',
            'complemento' => 'Sala 101',
            'email' => 'contato@empresateste.com.br',
            'inscricaoEstadual' => '1234567890',
            'inscricaoMunicipal' => '9876543210',
            'logradouro' => 'Rua das Flores',
            'numero' => 123,
            'regimeTributario' => 1,
            'telefone' => '41999887766',
            'municipio' => 'Curitiba',
            'uf' => 'PR',
            'habilitaNfe' => true,
            'habilitaNfce' => true,
            'habilitaNfse' => true,
            'arquivoCertificado' => 'certificado.pfx',
            'senhaCertificado' => 'senha123',
            'cscNfceProducao' => 'CSC123456789',
            'idTokenNfceProducao' => '000001',
            'enviaEmailDestinatario' => true,
            'discriminaImposto' => true,
            'certificadoEspecifico' => true,
        ];
    }

    /**
     * Testa criação de EmpresaDTO com todos os campos válidos
     */
    public function test_cria_empresa_dto_com_todos_campos_validos(): void
    {
        $dados = $this->getDadosValidos();

        $empresa = new EmpresaDTO(
            razaoSocial: $dados['razaoSocial'],
            nomeFantasia: $dados['nomeFantasia'],
            bairro: $dados['bairro'],
            cep: $dados['cep'],
            cnpj: $dados['cnpj'],
            complemento: $dados['complemento'],
            email: $dados['email'],
            inscricaoEstadual: $dados['inscricaoEstadual'],
            inscricaoMunicipal: $dados['inscricaoMunicipal'],
            logradouro: $dados['logradouro'],
            numero: $dados['numero'],
            regimeTributario: $dados['regimeTributario'],
            telefone: $dados['telefone'],
            municipio: $dados['municipio'],
            uf: $dados['uf'],
            habilitaNfe: $dados['habilitaNfe'],
            habilitaNfce: $dados['habilitaNfce'],
            habilitaNfse: $dados['habilitaNfse'],
            arquivoCertificado: $dados['arquivoCertificado'],
            senhaCertificado: $dados['senhaCertificado'],
            cscNfceProducao: $dados['cscNfceProducao'],
            idTokenNfceProducao: $dados['idTokenNfceProducao'],
            enviaEmailDestinatario: $dados['enviaEmailDestinatario'],
            discriminaImposto: $dados['discriminaImposto'],
            certificadoEspecifico: $dados['certificadoEspecifico'],
        );

        $this->assertInstanceOf(EmpresaDTO::class, $empresa);
        $this->assertEquals($dados['razaoSocial'], $empresa->razaoSocial);
        $this->assertEquals($dados['cnpj'], $empresa->cnpj);
        $this->assertEquals($dados['email'], $empresa->email);
        $this->assertEquals($dados['inscricaoEstadual'], $empresa->inscricaoEstadual);
        $this->assertEquals($dados['inscricaoMunicipal'], $empresa->inscricaoMunicipal);
    }

    /**
     * Testa criação com valores padrão opcionais
     */
    public function test_cria_empresa_dto_com_valores_padrao(): void
    {
        $empresa = new EmpresaDTO(
            razaoSocial: 'Empresa Teste LTDA',
            nomeFantasia: 'Empresa Teste',
            bairro: 'Centro',
            cep: 80000000,
            cnpj: '11222333000181',
            complemento: '',
            email: 'contato@empresateste.com.br',
            inscricaoEstadual: '',
            inscricaoMunicipal: '',
            logradouro: 'Rua das Flores',
            numero: 123,
            regimeTributario: 1,
            telefone: '41999887766',
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

        $this->assertTrue($empresa->enviaEmailDestinatario);
        $this->assertTrue($empresa->discriminaImposto);
        $this->assertFalse($empresa->certificadoEspecifico);
    }

    // ==================== TESTES DE CAMPOS OBRIGATÓRIOS ====================

    /**
     * Testa validação quando razaoSocial está vazio
     */
    public function test_valida_razao_social_obrigatorio(): void
    {
        $this->expectException(ValidationException::class);

        $dados = $this->getDadosValidos();
        $dados['razaoSocial'] = '';

        EmpresaDTO::fromArray($dados);
    }

    /**
     * Testa validação quando CNPJ está vazio
     */
    public function test_valida_cnpj_obrigatorio(): void
    {
        $this->expectException(ValidationException::class);

        $dados = $this->getDadosValidos();
        $dados['cnpj'] = '';

        EmpresaDTO::fromArray($dados);
    }

    /**
     * Testa validação quando email está vazio
     */
    public function test_valida_email_obrigatorio(): void
    {
        $this->expectException(ValidationException::class);

        $dados = $this->getDadosValidos();
        $dados['email'] = '';

        EmpresaDTO::fromArray($dados);
    }

    /**
     * Testa validação quando nomeFantasia está vazio
     */
    public function test_valida_nome_fantasia_obrigatorio(): void
    {
        $this->expectException(ValidationException::class);

        $dados = $this->getDadosValidos();
        $dados['nomeFantasia'] = '';

        EmpresaDTO::fromArray($dados);
    }

    /**
     * Testa validação quando telefone está vazio
     */
    public function test_valida_telefone_obrigatorio(): void
    {
        $this->expectException(ValidationException::class);

        $dados = $this->getDadosValidos();
        $dados['telefone'] = '';

        EmpresaDTO::fromArray($dados);
    }

    // ==================== TESTES DE FORMATO ====================

    /**
     * Testa validação de CNPJ inválido
     */
    public function test_valida_cnpj_invalido(): void
    {
        $this->expectException(ValidationException::class);

        $dados = $this->getDadosValidos();
        $dados['cnpj'] = '11111111111111'; // CNPJ inválido

        EmpresaDTO::fromArray($dados);
    }

    /**
     * Testa validação de email inválido
     */
    public function test_valida_email_invalido(): void
    {
        $this->expectException(ValidationException::class);

        $dados = $this->getDadosValidos();
        $dados['email'] = 'email-invalido';

        EmpresaDTO::fromArray($dados);
    }

    /**
     * Testa validação de UF com tamanho incorreto
     */
    public function test_valida_uf_tamanho_incorreto(): void
    {
        $this->expectException(ValidationException::class);

        $dados = $this->getDadosValidos();
        $dados['uf'] = 'PRR'; // 3 caracteres em vez de 2

        EmpresaDTO::fromArray($dados);
    }

    /**
     * Testa validação de UF vazia
     */
    public function test_valida_uf_vazia(): void
    {
        $this->expectException(ValidationException::class);

        $dados = $this->getDadosValidos();
        $dados['uf'] = '';

        EmpresaDTO::fromArray($dados);
    }

    /**
     * Testa validação de numero negativo
     */
    public function test_valida_numero_negativo(): void
    {
        $this->expectException(ValidationException::class);

        $dados = $this->getDadosValidos();
        $dados['numero'] = -1;

        EmpresaDTO::fromArray($dados);
    }

    /**
     * Testa validação de regimeTributario fora do range
     */
    public function test_valida_regime_tributario_valor_invalido_menor(): void
    {
        $this->expectException(ValidationException::class);

        $dados = $this->getDadosValidos();
        $dados['regimeTributario'] = 0; // Deve ser entre 1 e 3

        EmpresaDTO::fromArray($dados);
    }

    /**
     * Testa validação de regimeTributario fora do range
     */
    public function test_valida_regime_tributario_valor_invalido_maior(): void
    {
        $this->expectException(ValidationException::class);

        $dados = $this->getDadosValidos();
        $dados['regimeTributario'] = 4; // Deve ser entre 1 e 3

        EmpresaDTO::fromArray($dados);
    }

    /**
     * Testa valores válidos de regimeTributario (1, 2, 3)
     */
    public function test_valida_regime_tributario_valores_validos(): void
    {
        $dados = $this->getDadosValidos();

        // Regime 1 - Simples Nacional
        $dados['regimeTributario'] = 1;
        $empresa1 = EmpresaDTO::fromArray($dados);
        $this->assertEquals(1, $empresa1->regimeTributario);

        // Regime 2 - Simples Nacional - Excesso
        $dados['regimeTributario'] = 2;
        $empresa2 = EmpresaDTO::fromArray($dados);
        $this->assertEquals(2, $empresa2->regimeTributario);

        // Regime 3 - Regime Normal
        $dados['regimeTributario'] = 3;
        $empresa3 = EmpresaDTO::fromArray($dados);
        $this->assertEquals(3, $empresa3->regimeTributario);
    }

    // ==================== TESTES DE LIMITES DE CARACTERES ====================

    /**
     * Testa validação de razaoSocial excedendo tamanho máximo
     */
    public function test_valida_razao_social_tamanho_maximo(): void
    {
        $this->expectException(ValidationException::class);

        $dados = $this->getDadosValidos();
        $dados['razaoSocial'] = str_repeat('A', 256); // 256 caracteres (max é 255)

        EmpresaDTO::fromArray($dados);
    }

    /**
     * Testa validação de email excedendo tamanho máximo
     */
    public function test_valida_email_tamanho_maximo(): void
    {
        $this->expectException(ValidationException::class);

        $dados = $this->getDadosValidos();
        $dados['email'] = str_repeat('a', 250) . '@test.com'; // > 255 caracteres

        EmpresaDTO::fromArray($dados);
    }

    /**
     * Testa validação de complemento excedendo tamanho máximo
     */
    public function test_valida_complemento_tamanho_maximo(): void
    {
        $this->expectException(ValidationException::class);

        $dados = $this->getDadosValidos();
        $dados['complemento'] = str_repeat('A', 101); // 101 caracteres (max é 100)

        EmpresaDTO::fromArray($dados);
    }

    /**
     * Testa validação de inscricaoEstadual excedendo tamanho máximo
     */
    public function test_valida_inscricao_estadual_tamanho_maximo(): void
    {
        $this->expectException(ValidationException::class);

        $dados = $this->getDadosValidos();
        $dados['inscricaoEstadual'] = str_repeat('1', 21); // 21 caracteres (max é 20)

        EmpresaDTO::fromArray($dados);
    }

    /**
     * Testa validação de inscricaoMunicipal excedendo tamanho máximo
     */
    public function test_valida_inscricao_municipal_tamanho_maximo(): void
    {
        $this->expectException(ValidationException::class);

        $dados = $this->getDadosValidos();
        $dados['inscricaoMunicipal'] = str_repeat('1', 21); // 21 caracteres (max é 20)

        EmpresaDTO::fromArray($dados);
    }

    // ==================== TESTES DE REGRAS CONDICIONAIS ====================

    /**
     * Testa que inscricaoEstadual é obrigatória quando habilitaNfe é true
     */
    public function test_valida_inscricao_estadual_obrigatoria_quando_nfe_habilitada(): void
    {
        $this->expectException(ValidationException::class);

        $dados = $this->getDadosValidos();
        $dados['habilitaNfe'] = true;
        $dados['inscricaoEstadual'] = '';

        EmpresaDTO::fromArray($dados);
    }

    /**
     * Testa que inscricaoEstadual é obrigatória quando habilitaNfce é true
     */
    public function test_valida_inscricao_estadual_obrigatoria_quando_nfce_habilitada(): void
    {
        $this->expectException(ValidationException::class);

        $dados = $this->getDadosValidos();
        $dados['habilitaNfe'] = false;
        $dados['habilitaNfce'] = true;
        $dados['inscricaoEstadual'] = '';
        $dados['cscNfceProducao'] = 'CSC123';
        $dados['idTokenNfceProducao'] = '000001';

        EmpresaDTO::fromArray($dados);
    }

    /**
     * Testa que inscricaoEstadual pode ser vazia quando NFe e NFCe estão desabilitadas
     */
    public function test_permite_inscricao_estadual_vazia_quando_nfe_nfce_desabilitadas(): void
    {
        $dados = $this->getDadosValidos();
        $dados['habilitaNfe'] = false;
        $dados['habilitaNfce'] = false;
        $dados['habilitaNfse'] = false;
        $dados['inscricaoEstadual'] = '';
        $dados['inscricaoMunicipal'] = '';
        $dados['cscNfceProducao'] = '';
        $dados['idTokenNfceProducao'] = '';
        $dados['arquivoCertificado'] = '';
        $dados['senhaCertificado'] = '';
        $dados['certificadoEspecifico'] = false;

        $empresa = EmpresaDTO::fromArray($dados);

        $this->assertInstanceOf(EmpresaDTO::class, $empresa);
        $this->assertEquals('', $empresa->inscricaoEstadual);
    }

    /**
     * Testa que inscricaoMunicipal é obrigatória quando habilitaNfse é true
     */
    public function test_valida_inscricao_municipal_obrigatoria_quando_nfse_habilitada(): void
    {
        $this->expectException(ValidationException::class);

        $dados = $this->getDadosValidos();
        $dados['habilitaNfse'] = true;
        $dados['inscricaoMunicipal'] = '';

        EmpresaDTO::fromArray($dados);
    }

    /**
     * Testa que inscricaoMunicipal pode ser vazia quando NFSe está desabilitada
     */
    public function test_permite_inscricao_municipal_vazia_quando_nfse_desabilitada(): void
    {
        $dados = $this->getDadosValidos();
        $dados['habilitaNfse'] = false;
        $dados['habilitaNfe'] = false;
        $dados['habilitaNfce'] = false;
        $dados['inscricaoEstadual'] = '';
        $dados['inscricaoMunicipal'] = '';
        $dados['cscNfceProducao'] = '';
        $dados['idTokenNfceProducao'] = '';
        $dados['arquivoCertificado'] = '';
        $dados['senhaCertificado'] = '';
        $dados['certificadoEspecifico'] = false;

        $empresa = EmpresaDTO::fromArray($dados);

        $this->assertInstanceOf(EmpresaDTO::class, $empresa);
        $this->assertEquals('', $empresa->inscricaoMunicipal);
    }

    /**
     * Testa que cscNfceProducao é obrigatório quando habilitaNfce é true
     */
    public function test_valida_csc_nfce_obrigatorio_quando_nfce_habilitada(): void
    {
        $this->expectException(ValidationException::class);

        $dados = $this->getDadosValidos();
        $dados['habilitaNfce'] = true;
        $dados['cscNfceProducao'] = '';
        $dados['idTokenNfceProducao'] = '000001';

        EmpresaDTO::fromArray($dados);
    }

    /**
     * Testa que idTokenNfceProducao é obrigatório quando habilitaNfce é true
     */
    public function test_valida_id_token_nfce_obrigatorio_quando_nfce_habilitada(): void
    {
        $this->expectException(ValidationException::class);

        $dados = $this->getDadosValidos();
        $dados['habilitaNfce'] = true;
        $dados['cscNfceProducao'] = 'CSC123';
        $dados['idTokenNfceProducao'] = '';

        EmpresaDTO::fromArray($dados);
    }

    /**
     * Testa que CSC e ID Token podem ser vazios quando NFCe está desabilitada
     */
    public function test_permite_csc_id_token_vazios_quando_nfce_desabilitada(): void
    {
        $dados = $this->getDadosValidos();
        $dados['habilitaNfce'] = false;
        $dados['habilitaNfe'] = false;
        $dados['habilitaNfse'] = false;
        $dados['inscricaoEstadual'] = '';
        $dados['inscricaoMunicipal'] = '';
        $dados['cscNfceProducao'] = '';
        $dados['idTokenNfceProducao'] = '';
        $dados['arquivoCertificado'] = '';
        $dados['senhaCertificado'] = '';
        $dados['certificadoEspecifico'] = false;

        $empresa = EmpresaDTO::fromArray($dados);

        $this->assertInstanceOf(EmpresaDTO::class, $empresa);
        $this->assertEquals('', $empresa->cscNfceProducao);
        $this->assertEquals('', $empresa->idTokenNfceProducao);
    }

    /**
     * Testa validação de tamanho máximo do idTokenNfceProducao
     */
    public function test_valida_id_token_nfce_tamanho_maximo(): void
    {
        $this->expectException(ValidationException::class);

        $dados = $this->getDadosValidos();
        $dados['idTokenNfceProducao'] = '12345678901'; // 11 caracteres (max é 10)

        EmpresaDTO::fromArray($dados);
    }

    /**
     * Testa que arquivoCertificado é obrigatório quando certificadoEspecifico é true
     */
    public function test_valida_arquivo_certificado_obrigatorio_quando_certificado_especifico(): void
    {
        $this->expectException(ValidationException::class);

        $dados = $this->getDadosValidos();
        $dados['certificadoEspecifico'] = true;
        $dados['arquivoCertificado'] = '';
        $dados['senhaCertificado'] = 'senha123';

        EmpresaDTO::fromArray($dados);
    }

    /**
     * Testa que senhaCertificado é obrigatória quando certificadoEspecifico é true
     */
    public function test_valida_senha_certificado_obrigatoria_quando_certificado_especifico(): void
    {
        $this->expectException(ValidationException::class);

        $dados = $this->getDadosValidos();
        $dados['certificadoEspecifico'] = true;
        $dados['arquivoCertificado'] = 'certificado.pfx';
        $dados['senhaCertificado'] = '';

        EmpresaDTO::fromArray($dados);
    }

    /**
     * Testa que certificado pode ser vazio quando certificadoEspecifico é false
     */
    public function test_permite_certificado_vazio_quando_certificado_especifico_desabilitado(): void
    {
        $dados = $this->getDadosValidos();
        $dados['certificadoEspecifico'] = false;
        $dados['arquivoCertificado'] = '';
        $dados['senhaCertificado'] = '';

        $empresa = EmpresaDTO::fromArray($dados);

        $this->assertInstanceOf(EmpresaDTO::class, $empresa);
        $this->assertEquals('', $empresa->arquivoCertificado);
        $this->assertEquals('', $empresa->senhaCertificado);
    }

    // ==================== TESTES DO MÉTODO fromArray ====================

    /**
     * Testa criação através do método fromArray com dados completos
     */
    public function test_cria_empresa_dto_from_array_com_dados_completos(): void
    {
        $dados = $this->getDadosValidos();

        $empresa = EmpresaDTO::fromArray($dados);

        $this->assertInstanceOf(EmpresaDTO::class, $empresa);
        $this->assertEquals($dados['razaoSocial'], $empresa->razaoSocial);
        $this->assertEquals($dados['cnpj'], $empresa->cnpj);
        $this->assertEquals($dados['email'], $empresa->email);
        $this->assertTrue($empresa->habilitaNfe);
        $this->assertTrue($empresa->habilitaNfce);
        $this->assertTrue($empresa->habilitaNfse);
    }

    /**
     * Testa método fromArray com campos opcionais ausentes
     */
    public function test_from_array_com_campos_opcionais_ausentes(): void
    {
        $dados = [
            'razaoSocial' => 'Empresa Teste LTDA',
            'bairro' => 'Centro',
            'cep' => 80000000,
            'cnpj' => '11222333000181',
            'email' => 'contato@empresateste.com.br',
            'logradouro' => 'Rua das Flores',
            'numero' => 123,
            'regimeTributario' => 1,
            'telefone' => '41999887766',
            'municipio' => 'Curitiba',
            'uf' => 'PR',
            'habilitaNfe' => false,
            'habilitaNfce' => false,
        ];

        $empresa = EmpresaDTO::fromArray($dados);

        $this->assertInstanceOf(EmpresaDTO::class, $empresa);
        $this->assertEquals('', $empresa->nomeFantasia);
        $this->assertEquals('', $empresa->complemento);
        $this->assertEquals('', $empresa->inscricaoEstadual);
        $this->assertEquals('', $empresa->inscricaoMunicipal);
        $this->assertFalse($empresa->habilitaNfse);
        $this->assertTrue($empresa->enviaEmailDestinatario);
        $this->assertTrue($empresa->discriminaImposto);
        $this->assertFalse($empresa->certificadoEspecifico);
    }

    /**
     * Testa método toArray
     */
    public function test_to_array(): void
    {
        $dados = $this->getDadosValidos();
        $empresa = EmpresaDTO::fromArray($dados);

        $array = $empresa->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('razaoSocial', $array);
        $this->assertArrayHasKey('cnpj', $array);
        $this->assertArrayHasKey('email', $array);
        $this->assertArrayHasKey('inscricaoEstadual', $array);
        $this->assertArrayHasKey('inscricaoMunicipal', $array);
        $this->assertEquals($dados['razaoSocial'], $array['razaoSocial']);
        $this->assertEquals($dados['cnpj'], $array['cnpj']);
    }

    /**
     * Testa cenário completo: empresa com NFe e NFCe habilitadas
     */
    public function test_cenario_empresa_com_nfe_nfce_habilitadas(): void
    {
        $dados = [
            'razaoSocial' => 'Comercial ABC LTDA',
            'nomeFantasia' => 'ABC Comercial',
            'bairro' => 'Industrial',
            'cep' => 80050000,
            'cnpj' => '11222333000181',
            'complemento' => 'Galpão 5',
            'email' => 'fiscal@abc.com.br',
            'inscricaoEstadual' => '1234567890',
            'inscricaoMunicipal' => '',
            'logradouro' => 'Avenida das Indústrias',
            'numero' => 500,
            'regimeTributario' => 3,
            'telefone' => '4133334444',
            'municipio' => 'Curitiba',
            'uf' => 'PR',
            'habilitaNfe' => true,
            'habilitaNfce' => true,
            'habilitaNfse' => false,
            'arquivoCertificado' => 'certificado.pfx',
            'senhaCertificado' => 'senha@123',
            'cscNfceProducao' => 'ABCD1234EFGH5678',
            'idTokenNfceProducao' => '000001',
            'certificadoEspecifico' => true,
        ];

        $empresa = EmpresaDTO::fromArray($dados);

        $this->assertInstanceOf(EmpresaDTO::class, $empresa);
        $this->assertTrue($empresa->habilitaNfe);
        $this->assertTrue($empresa->habilitaNfce);
        $this->assertFalse($empresa->habilitaNfse);
        $this->assertNotEmpty($empresa->inscricaoEstadual);
        $this->assertNotEmpty($empresa->cscNfceProducao);
        $this->assertNotEmpty($empresa->idTokenNfceProducao);
        $this->assertNotEmpty($empresa->arquivoCertificado);
        $this->assertNotEmpty($empresa->senhaCertificado);
    }

    /**
     * Testa cenário completo: empresa somente com NFSe habilitada
     */
    public function test_cenario_empresa_somente_nfse_habilitada(): void
    {
        $dados = [
            'razaoSocial' => 'Prestadora de Serviços XYZ LTDA',
            'nomeFantasia' => 'XYZ Serviços',
            'bairro' => 'Centro',
            'cep' => 80000100,
            'cnpj' => '11222333000181',
            'complemento' => 'Sala 203',
            'email' => 'contato@xyz.com.br',
            'inscricaoEstadual' => '',
            'inscricaoMunicipal' => '9876543210',
            'logradouro' => 'Rua das Palmeiras',
            'numero' => 150,
            'regimeTributario' => 1,
            'telefone' => '4199998888',
            'municipio' => 'Curitiba',
            'uf' => 'PR',
            'habilitaNfe' => false,
            'habilitaNfce' => false,
            'habilitaNfse' => true,
            'arquivoCertificado' => '',
            'senhaCertificado' => '',
            'cscNfceProducao' => '',
            'idTokenNfceProducao' => '',
            'certificadoEspecifico' => false,
        ];

        $empresa = EmpresaDTO::fromArray($dados);

        $this->assertInstanceOf(EmpresaDTO::class, $empresa);
        $this->assertFalse($empresa->habilitaNfe);
        $this->assertFalse($empresa->habilitaNfce);
        $this->assertTrue($empresa->habilitaNfse);
        $this->assertEmpty($empresa->inscricaoEstadual);
        $this->assertNotEmpty($empresa->inscricaoMunicipal);
        $this->assertEmpty($empresa->cscNfceProducao);
    }
}

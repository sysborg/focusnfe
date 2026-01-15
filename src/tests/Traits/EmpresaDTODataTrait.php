<?php

namespace Sysborg\FocusNfe\tests\Traits;

trait EmpresaDTODataTrait
{
    /**
     * Retorna dados de mock para EmpresaDTO
     * Todos os parâmetros são opcionais com valores padrão válidos
     *
     * @example
     * // Empresa com todos os valores padrão
     * $dados = $this->getEmpresaMock();
     *
     * @example
     * // Empresa somente com NFSe habilitada
     * $dados = $this->getEmpresaMock(
     *     habilitaNfe: false,
     *     habilitaNfce: false,
     *     habilitaNfse: true,
     *     inscricaoEstadual: '',
     *     cscNfceProducao: '',
     *     idTokenNfceProducao: ''
     * );
     *
     * @example
     * // Empresa do Regime Normal
     * $dados = $this->getEmpresaMock(regimeTributario: 3);
     *
     * @example
     * // Mudar apenas o CNPJ
     * $dados = $this->getEmpresaMock(cnpj: '12345678000190');
     */
    protected function getEmpresaMock(
        string $razaoSocial = 'Empresa Teste LTDA',
        string $nomeFantasia = 'Empresa Teste',
        string $bairro = 'Centro',
        int $cep = 80000000,
        string $cnpj = '11222333000181',
        string $complemento = 'Sala 101',
        string $email = 'contato@empresateste.com.br',
        string $inscricaoEstadual = '1234567890',
        string $inscricaoMunicipal = '9876543210',
        string $logradouro = 'Rua das Flores',
        int $numero = 123,
        int $regimeTributario = 1,
        string $telefone = '41999887766',
        string $municipio = 'Curitiba',
        string $uf = 'PR',
        bool $habilitaNfe = true,
        bool $habilitaNfce = true,
        bool $habilitaNfse = true,
        string $arquivoCertificado = 'certificado.pfx',
        string $senhaCertificado = 'senha123',
        string $cscNfceProducao = 'CSC123456789',
        string $idTokenNfceProducao = '000001',
        bool $enviaEmailDestinatario = true,
        bool $discriminaImposto = true,
        bool $certificadoEspecifico = true,
    ): array {
        return [
            'razaoSocial' => $razaoSocial,
            'nomeFantasia' => $nomeFantasia,
            'bairro' => $bairro,
            'cep' => $cep,
            'cnpj' => $cnpj,
            'complemento' => $complemento,
            'email' => $email,
            'inscricaoEstadual' => $inscricaoEstadual,
            'inscricaoMunicipal' => $inscricaoMunicipal,
            'logradouro' => $logradouro,
            'numero' => $numero,
            'regimeTributario' => $regimeTributario,
            'telefone' => $telefone,
            'municipio' => $municipio,
            'uf' => $uf,
            'habilitaNfe' => $habilitaNfe,
            'habilitaNfce' => $habilitaNfce,
            'habilitaNfse' => $habilitaNfse,
            'arquivoCertificado' => $arquivoCertificado,
            'senhaCertificado' => $senhaCertificado,
            'cscNfceProducao' => $cscNfceProducao,
            'idTokenNfceProducao' => $idTokenNfceProducao,
            'enviaEmailDestinatario' => $enviaEmailDestinatario,
            'discriminaImposto' => $discriminaImposto,
            'certificadoEspecifico' => $certificadoEspecifico,
        ];
    }
}

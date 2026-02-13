<?php

namespace Sysborg\FocusNfe\tests\mocks\Stub;

class CEPStub {
  
    /**
     * Mock de uma consulta bem-sucedida de CEP.
     * 
     * @return string
     */
    public static function sucesso(): string
    {
        return json_encode([
            "cep" => "69900932",
            "tipo" => "logradouro",
            "nome" => "",
            "uf" => "AC",
            "nome_localidade" => "Rio Branco",
            "codigo_ibge" => "1200401",
            "tipo_logradouro" => "Rua",
            "nome_logradouro" => "Colinas",
            "nome_bairro_inicial" => "Rosa Linda",
            "descricao" => "Rua Colinas, Rio Branco, AC"
        ]);
    }

    /**
     * Mock para quando o CEP não é encontrado.
     * 
     * @return string
     */
    public static function cepNaoEncontrado(): string
    {
        return json_encode([
            "codigo" => "nao_encontrado",
            "mensagem" => "CEP não encontrado na base de dados"
        ]);
    }

    /**
     * Mock para requisição inválida.
     * 
     * @return string
     */
    public static function requisicaoInvalida(): string
    {
        return json_encode([
            "codigo" => "requisicao_invalida",
            "mensagem" => "CEP informado está em formato inválido"
        ]);
    }
}

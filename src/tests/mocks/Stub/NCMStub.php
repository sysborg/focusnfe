<?php

namespace Sysborg\FocusNFe\tests\mocks\Stub;

class NCMStub
{
    /**
     * Retorna um exemplo de resposta para a consulta de NCM
     *
     * @return array
     */
    public static function consultaNCM(): array
    {
        return [
            [
                "codigo" => "90049090",
                "descricao_completa" => "Óculos para correção, proteção ou outros fins, e artigos semelhantes. Outros Outros",
                "capitulo" => "90",
                "posicao" => "04",
                "subposicao1" => "9",
                "subposicao2" => "0",
                "item1" => "9",
                "item2" => "0"
            ],
            [
                "codigo" => "90051000",
                "descricao_completa" => "Binóculos, lunetas, incluindo as astronômicas, telescópios ópticos, e suas armações, outros instrumentos de astronomia e suas armações, exceto os aparelhos de radioastronomia. Binóculos",
                "capitulo" => "90",
                "posicao" => "05",
                "subposicao1" => "1",
                "subposicao2" => "0",
                "item1" => "0",
                "item2" => "0"
            ],
            [
                "codigo" => "90058000",
                "descricao_completa" => "Binóculos, lunetas, incluindo as astronômicas, telescópios ópticos, e suas armações, outros instrumentos de astronomia e suas armações, exceto os aparelhos de radioastronomia. Outros instrumentos",
                "capitulo" => "90",
                "posicao" => "05",
                "subposicao1" => "8",
                "subposicao2" => "0",
                "item1" => "0",
                "item2" => "0"
            ],
            [
                "codigo" => "90059010",
                "descricao_completa" => "Binóculos, lunetas, incluindo as astronômicas, telescópios ópticos, e suas armações, outros instrumentos de astronomia e suas armações, exceto os aparelhos de radioastronomia. Partes e acessórios (incluindo as armações) De binóculos",
                "capitulo" => "90",
                "posicao" => "05",
                "subposicao1" => "9",
                "subposicao2" => "0",
                "item1" => "1",
                "item2" => "0"
            ]
        ];
    }
}

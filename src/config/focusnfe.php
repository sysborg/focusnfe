<?php

return [
  'URL' => [
    'production' => 'https://api.focusnfe.com.br/',
    'sandbox' => 'https://homologacao.focusnfe.com.br/'
  ],

  'regimeTributario' => [
    1 => 'Simples Nacional',
    2 => 'Simples Nacional - Excesso de sublimite de receita bruta',
    3 => 'Regime Normal',
    4 => 'Simples Nacional - Microempreendedor Individual - MEI'
  ],

  'token' => env('FOCUSNFE_TOKEN'),

  'cte' => [
    'modal_aereo' => [
      'classe_tarifa' => [
        'M' => 'Tarifa mínima',
        'G' => 'Tarifa geral',
        'E' => 'Tarifa específica'
      ],

      'artigos_perigosos' => [
        'unidade_medida' => [
          'KG' => 'Quilograma',
          'KG G' => 'Quilograma bruto',
          'LITROS' => 'Litros',
          'TI' => 'Índice de transporte para radioativos',
          'Unidades' => 'Unidades - Apenas para artigos perigosos que não se enquadram nas demais unidades de medida'
        ]
      ],

      'informacoes_manuseio' => [
        '01' => 'Certificado do expedidor para embarque de animal vivo',
        '02' => 'Artigo perigoso conforme Declaração do Expedidor anexa',
        '03' => 'Somente em aeronave cargueira',
        '04' => 'Artigo perigoso - declaração do expedidor não requerida',
        '05' => 'Artigo perigoso em quantidade isenta',
        '06' => 'Gelo seco para refrigeração (especificar no campo observações a quantidade)',
        '07' => 'Não restrito (especificar a Disposição Especial no campo observações)',
        '08' => 'Artigo perigoso em carga consolidada (especificar a quantidade no campo observações)',
        '09' => 'Autorização da autoridade governamental anexa (especificar no campo observações)',
        '10' => 'Baterias de íons de lítio em conformidade com a Seção II da PI965 – CAO',
        '11' => 'Baterias de íons de lítio em conformidade com a Seção II da PI966',
        '12' => 'Baterias de íons de lítio em conformidade com a Seção II da PI967',
        '13' => 'Baterias de metal lítio em conformidade com a Seção II da PI968 — CAO',
        '14' => 'Baterias de metal lítio em conformidade com a Seção II da PI969',
        '15' => 'Baterias de metal lítio em conformidade com a Seção II da PI970',
        '99' => 'Outro (especificar no campo observações)'
      ],
    ],

    'modal_aquaviario' => [
      'direcao' => [
        'N' => 'Norte',
        'S' => 'Sul',
        'L' => 'Leste',
        'O' => 'Oeste'
      ],

      'tipo_navegacao' => [
        '0' => 'Interior',
        '1' => 'Cabotagem'
      ],
    ],

    'modal_ferroviario' => [
      'tipo_trafego' => [
        '0' => 'Próprio',
        '1' => 'Mútuo',
        '2' => 'Rodoferroviário',
        '3' => 'Rodoviário'
      ],

      'responsavel_faturamento' => [
        '1' => 'Ferrovia de Origem',
        '2' => 'Ferrovia de Destino'
      ],

      'ferrovia_emitente' => [
        '1' => 'Ferrovia de Origem',
        '2' => 'Ferrovia de Destino'
      ],
    ],

    'modal_multimodal' => [
      'indicador_negociavel' => [
        '0' => 'Não Negociável',
        '1' => 'Negociável'
      ]
    ],

    'modal_rodoviario' => [
      'CTeOS' => [
        'tipo_proprietario' => [
          '0' => 'TAC - Agregado',
          '1' => 'TAC - Independente',
          '2' => 'Outros'
        ],

        'tipo_fretamento' => [
          '1' => 'Eventual',
          '2' => 'Continuo'
        ]
      ]
    ],
  ],

  'middlewares' => [],

  'apiPrefix' => 'api/v1',

  'listeners' => [
    'hooks' => []
  ],
];
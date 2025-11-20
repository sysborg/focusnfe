<?php

return [
  'URL' => [
    'production' => 'https://api.focusnfe.com.br',
    'sandbox' => 'https://homologacao.focusnfe.com.br'
  ],

  'regimeTributario' => [
    1 => 'Simples Nacional',
    2 => 'Simples Nacional - Excesso de sublimite de receita bruta',
    3 => 'Regime Normal',
    4 => 'Simples Nacional - Microempreendedor Individual - MEI'
  ],

  'token' => env('FOCUSNFE_TOKEN'),

  'ambiente' => env('FOCUSNFE_AMBIENTE', 'production'),

  'icms_situacao_tributaria' => [
    '00'  => 'Tributada integralmente',
    '10'  => 'Tributada e com cobrança do ICMS por substituição tributária',
    '20'  => 'Tributada com redução de base de cálculo',
    '30'  => 'Isenta ou não tributada e com cobrança do ICMS por substituição tributária',
    '40'  => 'Isenta',
    '41'  => 'Não tributada',
    '50'  => 'Suspensão',
    '51'  => 'Diferimento (a exigência do preenchimento das informações do ICMS diferido fica a critério de cada UF)',
    '60'  => 'Cobrado anteriormente por substituição tributária',
    '70'  => 'Tributada com redução de base de cálculo e com cobrança do ICMS por substituição tributária',
    '90'  => 'Outras (regime Normal)',

    '101' => 'Tributada pelo Simples Nacional com permissão de crédito',
    '102' => 'Tributada pelo Simples Nacional sem permissão de crédito',
    '103' => 'Isenção do ICMS no Simples Nacional para faixa de receita bruta',

    '201' => 'Tributada pelo Simples Nacional com permissão de crédito e com cobrança do ICMS por substituição tributária',
    '202' => 'Tributada pelo Simples Nacional sem permissão de crédito e com cobrança do ICMS por substituição tributária',
    '203' => 'Isenção do ICMS no Simples Nacional para faixa de receita bruta e com cobrança do ICMS por substituição tributária',

    '300' => 'Imune',
    '400' => 'Não tributada pelo Simples Nacional',
    '500' => 'ICMS cobrado anteriormente por substituição tributária (substituído) ou por antecipação',
    '900' => 'Outras (regime Simples Nacional)',
  ],

  'icms_origem' => [
    '0' => 'Nacional',
    '1' => 'Estrangeira (importação direta)',
    '2' => 'Estrangeira (adquirida no mercado interno)',
    '3' => 'Nacional com mais de 40% de conteúdo estrangeiro',
    '4' => 'Nacional produzida através de processos produtivos básicos',
    '5' => 'Nacional com menos de 40% de conteúdo estrangeiro',
    '6' => 'Estrangeira (importação direta) sem produto nacional similar',
    '7' => 'Estrangeira (adquirida no mercado interno) sem produto nacional similar',
  ],

  'cofins_situacao_tributaria' => [
    '01' => 'Operação tributável: base de cálculo = valor da operação (alíquota normal – cumulativo/não cumulativo)',
    '02' => 'Operação tributável: base de cálculo = valor da operação (alíquota diferenciada)',
    '03' => 'Operação tributável: base de cálculo = quantidade vendida × alíquota por unidade de produto',
    '04' => 'Operação tributável: tributação monofásica (alíquota zero)',
    '05' => 'Operação tributável: substituição tributária',
    '06' => 'Operação tributável: alíquota zero',
    '07' => 'Operação isenta da contribuição',
    '08' => 'Operação sem incidência da contribuição',
    '09' => 'Operação com suspensão da contribuição',
    '49' => 'Outras operações de saída',
    '50' => 'Operação com direito a crédito: vinculada exclusivamente a receita tributada no mercado interno',
    '51' => 'Operação com direito a crédito: vinculada exclusivamente a receita não tributada no mercado interno',
    '52' => 'Operação com direito a crédito: vinculada exclusivamente a receita de exportação',
    '53' => 'Operação com direito a crédito: vinculada a receitas tributadas e não-tributadas no mercado interno',
    '54' => 'Operação com direito a crédito: vinculada a receitas tributadas no mercado interno e de exportação',
    '55' => 'Operação com direito a crédito: vinculada a receitas não-tributadas no mercado interno e de exportação',
    '56' => 'Operação com direito a crédito: vinculada a receitas tributadas e não-tributadas no mercado interno e de exportação',
    '60' => 'Crédito presumido: operação de aquisição vinculada exclusivamente a receita tributada no mercado interno',
    '61' => 'Crédito presumido: operação de aquisição vinculada exclusivamente a receita não-tributada no mercado interno',
    '62' => 'Crédito presumido: operação de aquisição vinculada exclusivamente a receita de exportação',
    '63' => 'Crédito presumido: operação de aquisição vinculada a receitas tributadas e não-tributadas no mercado interno',
    '64' => 'Crédito presumido: operação de aquisição vinculada a receitas tributadas no mercado interno e de exportação',
    '65' => 'Crédito presumido: operação de aquisição vinculada a receitas não-tributadas no mercado interno e de exportação',
    '66' => 'Crédito presumido: operação de aquisição vinculada a receitas tributadas e não-tributadas no mercado interno e de exportação',
    '67' => 'Crédito presumido: outras operações',
    '70' => 'Operação de aquisição sem direito a crédito',
    '71' => 'Operação de aquisição com isenção',
    '72' => 'Operação de aquisição com suspensão',
    '73' => 'Operação de aquisição a alíquota zero',
    '74' => 'Operação de aquisição sem incidência da contribuição',
    '75' => 'Operação de aquisição por substituição tributária',
    '98' => 'Outras operações de entrada',
    '99' => 'Outras operações',
  ],

  'pis_situacao_tributaria' => [
    '01' => 'Operação tributável: base de cálculo = valor da operação (alíquota normal – cumulativo/não cumulativo)',
    '02' => 'Operação tributável: base de cálculo = valor da operação (alíquota diferenciada)',
    '03' => 'Operação tributável: base de cálculo = quantidade vendida × alíquota por unidade de produto',
    '04' => 'Operação tributável: tributação monofásica (alíquota zero)',
    '05' => 'Operação tributável: substituição tributária',
    '06' => 'Operação tributável: alíquota zero',
    '07' => 'Operação isenta da contribuição',
    '08' => 'Operação sem incidência da contribuição',
    '09' => 'Operação com suspensão da contribuição',
    '49' => 'Outras operações de saída',
    '50' => 'Operação com direito a crédito: vinculada exclusivamente a receita tributada no mercado interno',
    '51' => 'Operação com direito a crédito: vinculada exclusivamente a receita não tributada no mercado interno',
    '52' => 'Operação com direito a crédito: vinculada exclusivamente a receita de exportação',
    '53' => 'Operação com direito a crédito: vinculada a receitas tributadas e não-tributadas no mercado interno',
    '54' => 'Operação com direito a crédito: vinculada a receitas tributadas no mercado interno e de exportação',
    '55' => 'Operação com direito a crédito: vinculada a receitas não-tributadas no mercado interno e de exportação',
    '56' => 'Operação com direito a crédito: vinculada a receitas tributadas e não-tributadas no mercado interno e de exportação',
    '60' => 'Crédito presumido: operação de aquisição vinculada exclusivamente a receita tributada no mercado interno',
    '61' => 'Crédito presumido: operação de aquisição vinculada exclusivamente a receita não-tributada no mercado interno',
    '62' => 'Crédito presumido: operação de aquisição vinculada exclusivamente a receita de exportação',
    '63' => 'Crédito presumido: operação de aquisição vinculada a receitas tributadas e não-tributadas no mercado interno',
    '64' => 'Crédito presumido: operação de aquisição vinculada a receitas tributadas no mercado interno e de exportação',
    '65' => 'Crédito presumido: operação de aquisição vinculada a receitas não-tributadas no mercado interno e de exportação',
    '66' => 'Crédito presumido: operação de aquisição vinculada a receitas tributadas e não-tributadas no mercado interno e de exportação',
    '67' => 'Crédito presumido: outras operações',
    '70' => 'Operação de aquisição sem direito a crédito',
    '71' => 'Operação de aquisição com isenção',
    '72' => 'Operação de aquisição com suspensão',
    '73' => 'Operação de aquisição a alíquota zero',
    '74' => 'Operação de aquisição sem incidência da contribuição',
    '75' => 'Operação de aquisição por substituição tributária',
    '98' => 'Outras operações de entrada',
    '99' => 'Outras operações',
  ],

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
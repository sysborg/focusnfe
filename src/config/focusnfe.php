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

  'middlewares' => []
];
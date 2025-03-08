<?php
namespace Sysborg\FocusNFe\tests\Feature;

use PHPUnit\Framework\TestCase;

class Common extends TestCase
{
  /**
   * Variáve do prefix das apis
   * 
   * @var string
   */
  protected $prefix = '';

  /**
   * Setando prefixo
   * 
   * @return void
   */
  public function setUpBeforeClass(): void
  {
    $this->prefix = config('focusnfe.apiPrefix') . '/sbfocus';
  }
}

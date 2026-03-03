<?php

namespace Sysborg\FocusNfe\tests\Feature;

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
    protected function setUp(): void
    {
        parent::setUp();
        $this->markTestSkipped('Feature tests depend on Laravel HTTP TestCase bootstrap, not available in this package test harness yet.');
        $this->prefix = config('focusnfe.apiPrefix') . '/sbfocus';
    }
}

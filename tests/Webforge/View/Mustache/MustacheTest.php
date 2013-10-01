<?php

namespace Webforge\View\Mustache;

use Webforge\View\Test\EngineBaseTest;

class MustacheTest extends EngineBaseTest {
  
  public function setUp() {
    $this->chainClass = 'Webforge\\View\\Mustache\\Mustache';
    parent::setUp();

    $this->engine = new Mustache($this->templatesDir->sub('mustache/'), $this->cacheDir->sub('mustache/'));
  }

  public function testRenderAcceptanceTest() {
    $this->html = $this->engine->render('team', $this->getViewModel(0));

    $this->assertViewModel(0);
  }
}

<?php

namespace Webforge\View\Mustache;

use Webforge\View\Test\EngineBaseTest;

class MustacheTest extends EngineBaseTest {
  
  public function setUp() {
    $this->chainClass = 'Webforge\\View\\Mustache\\Mustache';
    parent::setUp();
    $this->spec->usesDirectories = TRUE;

    $this->engine = new Mustache($this->templatesDir->sub('mustache/'), $this->cacheDir->sub('mustache/'));
  }

  public function testRenderAcceptanceTest() {
    $this->html = $this->engine->render('team', $this->getViewModel(0));

    $this->assertViewModel(0);
  }

  public function testRendersFromAnotherDir_chained() {
    $this->engine->addTemplatesDirectory($this->otherTemplatesDir);

    $this->html = $this->engine->render('headline', array('level'=>2, 'content'=>$h = 'The Headline Level 2'));

    $this->css('h2')->count(1, 'headline is not rendered from other Template directory')->hasText($h);
  }
}

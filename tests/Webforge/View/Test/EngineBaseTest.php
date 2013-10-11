<?php

namespace Webforge\View\Test;

class EngineBaseTest extends \Webforge\Code\Test\Base implements \Webforge\Code\Test\HTMLTesting {

  protected $engine;

  protected $viewModels = array();

  public function setup() {
    $this->spec = (object) array(
      'usesDirectories'=>FALSE
    );

    $this->viewModels = array(json_decode('{
      "team": {
        "name": "team",
        "isActive": true,
        "mas": [{
          "name": "imme"
        }, {
          "name": "philipp"
        }]
       }
     }'
    ));

    parent::setup();

    $this->templatesDir = $this->getPackageDir('tests/files/templates/');
    $this->otherTemplatesDir = $this->getPackageDir('tests/files/other-templates/');
    $this->cacheDir = $this->getPackageDir('build/cache/');
  }


  // simple tests
  public function testImplementsEngineInterfaces() {
    $this->assertInstanceOf('Webforge\View\TemplateEngine', $this->engine);

    if ($this->spec->usesDirectories) {
      $this->assertInstanceOf('Webforge\View\TemplatesDirectoryEngine', $this->engine);
    }
  }

  // UTILS
  protected function getViewModel($index) {
    return $this->viewModels[$index];
  }

  protected function assertViewModel($index) {
    if ($index === 0) {
      $this->css('div.team')->count(1)
        ->css('h1')->count(1)->hasClass('active')->end()
        ->css('div.mitarbeiter')->count(2)->end()
      ;

      return TRUE;
    }

    $this->fail('No viewModel found with index: '.$index);
  }
}

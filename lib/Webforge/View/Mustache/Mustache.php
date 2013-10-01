<?php

namespace Webforge\View\Mustache;

use Webforge\View\TemplateEngine as TemplateEngineInterface;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;
use Webforge\Common\System\Dir;

class Mustache implements TemplateEngineInterface {

  /**
   * @var Mustache_Engine
   */
  protected $mustacheEngine;

  /**
   * @var Webforge\Common\System\Dir
   */
  protected $tplBase, $tplCache;

  public function __construct(Dir $templates, Dir $cache) {
    //$tplBase = $packageRoot->sub('resources/tpl/');
    //$tplCache = $packageRoot->sub('files/cache/mustache/')->create();
    $this->tplBase = $templates;
    $this->tplCache = $cache;
  }

  /**
   * @return string
   */
  public function render($template, $vars) {
    return $this->getMustacheEngine()->render($template, $vars);
  }

  protected function getMustacheEngine() {
    if (!isset($this->mustacheEngine)) {

      $this->mustacheEngine = new Mustache_Engine(array(
        'loader'=>new Mustache_Loader_FilesystemLoader((string) $this->tplBase),
        'partials_loader' => new Mustache_Loader_FilesystemLoader((string) $this->tplBase),
        'cache_file_mode' => Dir::$defaultMod,
        'cache'=>(string) $this->tplCache,
        'helpers'=>array(
          'renderPartial'=>function($partialName, $lambdaHelper) {
            $partialName = trim($lambdaHelper->render($partialName));

            return $lambdaHelper->render('{{> '.$partialName.'}}');
          }
        )
      ));
    }

    return $this->mustacheEngine;
  }
}

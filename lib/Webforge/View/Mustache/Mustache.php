<?php

namespace Webforge\View\Mustache;

use Webforge\View\TemplateEngine;
use Webforge\View\TemplatesDirectoryEngine;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;
use Mustache_Loader_CascadingLoader;
use Webforge\Common\System\Dir;

class Mustache implements TemplateEngine, TemplatesDirectoryEngine {

  /**
   * @var Mustache_Engine
   */
  protected $mustacheEngine;

  /**
   * @var Webforge\Common\System\Dir
   */
  protected $tplBase, $tplCache;

  /**
   * @var Mustache_Loader
   */
  protected $loader;

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

  public function addTemplatesDirectory(Dir $dir) {
    $this->getLoader()->addLoader(
      new Mustache_Loader_FilesystemLoader((string) $dir)
    );
  }

  protected function getMustacheEngine() {
    if (!isset($this->mustacheEngine)) {

      $this->mustacheEngine = new Mustache_Engine(array(
        'loader'=>$this->getLoader(),
        'partials_loader' => $this->getLoader(),
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

  protected function getLoader() {
    if (!isset($this->loader)) {
      $this->loader = new Mustache_Loader_CascadingLoader();
      $this->addTemplatesDirectory($this->tplBase);
    }

    return $this->loader;
  }
}

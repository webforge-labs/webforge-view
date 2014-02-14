<?php

namespace Webforge\View;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Symfony\Component\Templating\Loader\LoaderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Response;
use Webforge\Common\ClassUtil;

class SymfonyAdapter implements EngineInterface {

  protected $engine;
  protected $nameParser;
  protected $loader;
  protected $urlGenerator;

  public function __construct(TemplateEngine $engine, TemplateNameParserInterface $parser, LoaderInterface $loader, UrlGeneratorInterface $urlGenerator) {
    $this->engine = $engine;
    $this->engineName = mb_strtolower(ClassUtil::getClassName(get_class($this->engine)));
    $this->nameParser = $parser;
    $this->loader = $loader;
    $this->urlGenerator = $urlGenerator;
    $this->setFunctionalHelpers();
  }

  public function renderResponse($name, Array $parameters = array(), Response $response = NULL) {
    if ($response === NULL) {
      $response = new Response();
    }

    $response->setContent($this->render($name, $parameters));

    return $response;
  }

  public function render($name, Array $parameters = array()) {
    $templateReference = $this->nameParser->parse($name);

    if ($template = $this->loader->load($templateReference)) {
      // render as a symfony-loaded-template with the raw content
      return $this->engine->render($template->getContent(), $parameters);
    } else { 
      // find the template as overriden somewhere in our directories
      $templateName = sprintf(
        '%s/%s/%s.%s',
        $templateReference->get('bundle'),
        $templateReference->get('controller'),
        $templateReference->get('name'),
        $templateReference->get('format')
      );
      return $this->engine->render($templateName, $parameters);
    }
  }

  public function exists($name) {
    $templateReference = $this->nameParser->parse($name);

    return $this->loader->load($templateReference) !== FALSE;
  }

  public function supports($name) {
    $template = $this->parser->parse($name);

    return $template->get('engine') === $this->engineName;
  }

  public function setFunctionalHelpers() {
    $urlGenerator = $this->urlGenerator;

    $this->engine->addFunctionalHelper(
      'symfonyPath', function($name) use ($urlGenerator) {
        $relative = false;
        $parameters = array();
        return $urlGenerator->generate(
          $name, 
          $parameters, 
          $relative ? UrlGeneratorInterface::RELATIVE_PATH : UrlGeneratorInterface::ABSOLUTE_PATH
        );
      }
    );
  }
}

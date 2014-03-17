<?php

namespace Webforge\View;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Symfony\Component\Templating\Loader\LoaderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Response;
use Webforge\Common\ClassUtil;

// $template can be of instance Webforge\View\Renderable or the name in the format: bundle:controller:name.format (e.g.: :CMS/Widgets:video.html)
class SymfonyAdapter implements EngineInterface {

  protected $engine, $engineName;
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

  public function renderResponse($template, Array $parameters = array(), Response $response = NULL) {
    if ($response === NULL) {
      $response = new Response();
    }

    $response->setContent($this->render($template, $parameters));

    return $response;
  }

  public function render($template, Array $parameters = array()) {
    if ($template instanceof Renderable) {
      $renderable = $template;
      $parameters = $renderable->getTemplateVariables();
      $template = $renderable->getTemplateIdentifier();
    }

    $templateReference = $this->nameParser->parse($template);

    if (is_array($parameters)) {
      $parameters['debug'] = print_r($parameters, true);
    }

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

  public function exists($template) {
    $templateReference = $this->nameParser->parse($template);

    return $this->loader->load($templateReference) !== FALSE;
  }

  public function supports($template) {
    $template = $this->parser->parse($template);

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

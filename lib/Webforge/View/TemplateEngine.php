<?php

namespace Webforge\View;

interface TemplateEngine {

  /**
   * Renders a template given as $identifier with the $vars as parameters
   * 
   * @param string $templateIdentifier
   * @param array|object $vars
   */
  public function render($templateIdentifier, $vars);
}

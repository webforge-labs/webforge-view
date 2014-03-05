<?php

namespace Webforge\View;

interface Renderable {

  /**
   * @return string in the format passed to the TemplateEngine as first parameter of render()
   */
  public function getTemplateIdentifier();

  /**
   * @return array|object the variables to render within the template
   */
  public function getTemplateVars();
}

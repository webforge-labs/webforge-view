<?php

namespace Webforge\View;

use Webforge\Common\System\Dir;

interface TemplatesDirectoryEngine {

  /**
   * Adds another template directory to the engine to search for template files
   */
  public function addTemplatesDirectory(Dir $dir);
}

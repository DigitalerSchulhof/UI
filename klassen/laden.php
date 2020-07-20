<?php
namespace UI;

use UI;

class Ladesymbol extends UI\Element {
  protected $tag = "div";

  public function __construct() {
    parent::__construct();
    $this->addKlasse("dshUiLadesymbol");
  }

  public function __toString() : string {
    return "{$this->codeAuf()}<div></div><div></div><div></div><div></div>{$this->codeZu()}";
  }
}
?>

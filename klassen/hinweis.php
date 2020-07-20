<?php
namespace UI;
use UI;

/**
* Ein Hinweis ist das Feld, das oberhalb von einem Knopf angezeigt wird.
*/
class Hinweis extends UI\InhaltElement{
  protected $tag = "span";
  /** @var string Mögliche Positionen */
  const POSITIONEN = ["OR", "OL", "UR", "UL"];

  /** @var string Position des Hinweises */
  protected $position;

	/**
	* @param string $inhalt Inhalt des Hinweises
	*/
  public function __construct($inhalt, $position = null) {
    parent::__construct($inhalt);
    if(!in_array($position, self::POSITIONEN)) {
      $position = self::POSITIONEN[0];
    }
    $this->position = $position;
    $this->addKlasse("dshUiHinweis");
    $this->addKlasse("dshUiHinweis$position");
  }
}
?>

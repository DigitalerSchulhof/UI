<?php
namespace UI;

/**
* Ein Hinweis ist das Feld, das oberhalb von einem Element angezeigt wird.
*/
class Hinweis extends InhaltElement {
  protected $tag = "span";
  /** @var string Mögliche Positionen */
  const POSITIONEN = ["OR", "OL", "UR", "UL"];

  /** @var string Position des Hinweises */
  protected $position;

	/**
	* @param string $inhalt Inhalt des Hinweises
	* @param string $position Position des Hinweises - ["OR", "OL", "UR", "UL"]
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

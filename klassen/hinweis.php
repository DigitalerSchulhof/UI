<?php
namespace UI;

/**
* Schaltflächen erstellen
*/
class Hinweis {
  /** @var string Inhalt des Hinweises */
  private $inhalt;

	/**
	* @param string $inhalt Inhalt des Hinweises
	*/
  public function __construct($inhalt) {
    $this->inhalt = $inhalt;
  }


  /**
   * Gibt den Hinweis aus
   * @param string $position des Hinweises
   * @return string      HTML-Code des Hinweises
   */
  public function ausgabe ($position = "OR") : string {
    $moeglich = ["OR", "OL", "UR", "UL"];
    if (!in_array($position, $moeglich)) {
      $position = "OR";
    }
    return "<span class=\"dshUiHinweis dshUiHinweis{$position}\">{$this->inhalt}</span>";
  }
}
?>

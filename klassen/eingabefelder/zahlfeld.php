<?php
namespace UI;
use UI\Eingabefelder;

/**
* Eine Datenbankverbindung
*/
class Zahlfeld extends Eingabefeld {
  /** @var string Minimalwert des Zahlbereichs */
  private $min;
  /** @var string Maximalwert des Zahlbereichs */
  private $max;
  /** @var string Schrittweite des Zahlbereiches */
  private $schritt;

	/**
	* @param string $id
	* @param string $max
	* @param string $min
	* @param string $schritt
	* @param string $wert
	* @param string $klasse
	*/
  public function __construct($id, $max=null, $min=0, $schritt=1, $wert="", $klasse="") {
    parent::__construct($id, $wert, $klasse);
    $this->min = $min;
    $this->max = $max;
    $this->schritt = $schritt;
  }

  /**
	* Gibt das Eingabefeld als Textfeld aus
	* @return string HTML-Code für ein Eingabefeld
	*/
  public function zahlfeld () : string {
    $code = "<input type=\"number\" id=\"$this->id\" name=\"$this->id\"";
    $code .= " value=\"$this->wert\" class=\"dsh_eingabefeld $this->klasse\"";
    if ($min != null) {$code .= " min=\"$this->min\"";}
    if ($max != null) {$code .= " max=\"$this->max\"";}
    if ($schritt != null) {$code .= " step=\"$this->schritt\"";}
    return $code.">";
  }
}
?>

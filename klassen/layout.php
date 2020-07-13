<?php
namespace UI;

class Spalte {
  /** @var string Typ der Spalte */
  private $typ;
  /** @var string[] Elemente der Spalte */
  private $elemente;
  /** @var string CSS-Zusatzklassen dieser Spalte */
  private $klasse;

  /**
   * Erstellt eine neue Spalte
   * @param string $typ     Typ der Spalte
   * @param string $klasse  Klasse der Spalte
   */
  public function __construct($element = null, $typ = "A1", $klasse = "") {
    $moeglich = ["A1", "A2", "A3", "A4", "A5", "B23", "B34", "P10", "P20", "P30", "P40", "P50", "P60", "P70", "P80", "P90"];
    if (!in_array($typ, $moeglich)) {
      $klasse = "A1";
    }
    $this->typ     = $typ;
    if (strlen($klasse) > 0) {$klasse = " ".$klasse;}
    $this->klasse = $klasse;
    $this->elemente = array();
    if ($element !== null) {
      $this->elemente[] = $element;
    }
  }

  /**
   * Gibt den HTML-Code zurück
   * @return string Der HTML-Code
   */
  public function ausgabe() : string {
    $code  = "<div class=\"dshSpalte{$this->typ}{$this->klasse}\">";
    foreach ($this->elemente as $e) {
      $code .= $e;
    }
    $code .= "</div>";
    return $code;
  }

  /**
   * Fügt ein Element der Spalte hinzu
   * @param  string $element Hinzuzufügendes Element
   */
  public function dazu($element) {
    $this->elemente[] = $element;
  }
}

class Zeile {
  /** @var Spalte[] Spalten dieser Zeile */
  private $spalten;
  /** @var string CSS-Zusatzklassen dieser Zeile */
  private $klasse;

  /**
   * Erstellt eine neue Zeile
   * @param Spalte $spalte  Erste Spalte dieser Zeile
   * @param string $klasse  Klasse der Zeile
   */
  public function __construct($spalte, $klasse = "") {
    $this->spalten = array();
    $this->spalten[] = $spalte;
    if (strlen($klasse) > 0) {$klasse = " ".$klasse;}
    $this->klasse = $klasse;
  }


  /**
   * Gibt den HTML-Code zurück
   * @return string Der HTML-Code
   */
  public function ausgabe() : string {
    $code  = "<div class=\"dshZeile{$this->klasse}\">";
    foreach ($this->spalten as $s) {
      $code .= $s->ausgabe();
    }
    $code .= "<div class=\"dshClear\"></div>";
    return $code;
  }

  /**
   * Fügt eine Spalte der Zeile hinzu
   * @param Spalte $spalte Hinzuzufügende Spalte
   */
  public function dazu($spalte) {
    $this->spalten[] = $spalte;
  }

}
?>

<?php
namespace UI;

/**
 * Legt fest, wie Seitenüberschriften auszusehen haben
 */
class Farbbeispiel extends Element {
  protected $tag = "span";
  protected $farbe;

  public function __construct($farbe) {
    if (!Check::istFarbe($farbe)) {
      throw new \Exception("Keine gültige Farbe übergeben.");
    }
    parent::__construct();
    $this->farbe = $farbe;
    $this->addKlasse("dshUiFarbbeispielAnzeige");
    $this->setAttribut("style", "background-color:$farbe;");
  }
}
?>

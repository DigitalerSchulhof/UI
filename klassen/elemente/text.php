<?php
namespace UI;

/**
 * Legt fest, wie Seitenüberschriften auszusehen haben
 */
class SeitenUeberschrift extends InhaltElement {
  protected $tag = "h1";
}

class Ueberschrift extends InhaltElement {
  protected $tag = "h2";

  public function __construct($groesse, $inhalt) {
    $check = new \Check();
    if (!$check->istZahl($groesse, 1, 6)) {
      throw new \Exception("Falscher Zahlbereich für Überschriften");
    }
    parent::__construct($inhalt);
    $this->setTag("h$groesse");
  }
}
?>

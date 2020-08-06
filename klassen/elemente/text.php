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
    if (!\Check::istZahl($groesse, 1, 6)) {
      throw new \Exception("Falscher Zahlbereich für Überschriften");
    }
    parent::__construct($inhalt);
    $this->setTag("h$groesse");
  }
}

class Absatz extends InhaltElement {
  protected $tag = "p";
}

class Notiz extends InhaltElement {
  protected $tag = "p";

  public function __construct($inhalt = "") {
    parent::__construct($inhalt);
    $this->addKlasse("dshNotiz");
 }
}

class Link extends InhaltElement {
  protected $tag = "a";

  protected $extern;

  public function __construct($inhalt, $ziel, $extern = false) {
    parent::__construct($inhalt);
    $this->ziel = $ziel;
    $this->setAttribut("href", $ziel);
    $this->setAttribut("tabindex", "0");
    if ($extern) {
      $this->addKlasse("dshExtern");
    }
    $this->extern = $extern;
    $this->addKlasse("dshUiLink");
  }

  public function setZiel($ziel) : self {
    $this->setAttribut("href", $ziel);
    return $this;
  }

  public function __toString() : string {
    if ($this->extern) {
      return "{$this->codeAuf()}{$this->inhalt} ".(new Icon(Konstanten::LINKEXT))."{$this->codeZu()}";
    } else {
      return "{$this->codeAuf()}{$this->inhalt}{$this->codeZu()}";
    }
  }
}

class Liste extends Element {
  /** @var InhaltElement[] $punkte */
  private $punkte;

  const TYPEN = ["UL", "OL"];

  /**
   * Erstellt eine neue Liste
   * @param string          $typ    Typ der Liste
   * @param InhaltElement[] $punkte Punkte der Liste
   */
  public function __construct($typ = "UL", ...$punkte) {
    if (!in_array($typ, self::TYPEN)) {
      $typ = self::TYPEN[0];
    }
    $this->tag = $typ;
    $this->punkte = [];
    foreach ($punkte as $p) {
      $this->punkte[] = $p;
    }
    parent::__construct();
    $this->addKlasse("dshUiListe");
  }

  /**
   * Fügt Punkte zur Liste hinzu
   * @param  InhaltElement[] $punkte Punkte der Liste
   * @return self            Gibt die Liste selbst zurück
   */
  public function add(...$punkte) : self {
    foreach ($punkte as $p) {
      $this->punkte[] = $p;
    }
    return $this;
  }

  /**
   * Erzeugt HTML-Code der Lsite
   * @return string HTML-Code der Liste
   */
  public function __toString() : string {
    $code = $this->codeAuf();
    foreach ($this->punkte as $p) {
      $code .= "<li>$p</li>";
    }
    return "$code{$this->codeZu()}";
  }
}

class Datum {
    /** @var int Timestamp */
  private $zeit;

  /**
   * Legt ein Datum an
   * @param int $zeit Timestamp
   */
  public function __construct($zeit) {
    $this->zeit = $zeit;
  }

  /**
   * Gibt dieses Datum ausführlich aus
   * @return string :)
   */
  public function __toString() : string {
    return $this->kurz("WMU");
  }

  /**
   * Gibt dieses Datum gemäß der vorgabe aus
   * @param
   * @return string :)
   */
  public function kurz($vorgabe = "MU") : string {
    $rueck = "";
    // Wochentag
    if (strpos($vorgabe, "w")) {$rueck .= $this->getWochentag(true).", den ";}
    else if (strpos($vorgabe, "W")) {$rueck .= $this->getWochentag().", den ";}
    // Monat
    if (strpos($vorgabe, "m")) {$rueck .= date("d", $this->zeit).". ".$this->getMonatname(true)." ".date("Y", $this->zeit);}
    else if (strpos($vorgabe, "M")) {$rueck .= date("d", $this->zeit).". ".$this->getMonatname()." ".date("Y", $this->zeit);}
    else {$rueck .= date("d.m.Y", $this->zeit);}
    // Uhrzeit
    if (strpos($vorgabe, "u")) {$rueck .= " ".date("H:i", $this->zeit);}
    else if (strpos($vorgabe, "U")) {$rueck .= " um ".date("H:i", $this->zeit)." Uhr";}

    return $rueck;
  }

  public function getWochentag($kurz = false) {
    $wochentag = date("N", $this->zeit);

    if ($kurz) {
      switch ($wochentag) {
        case 0: return "SO";
        case 1: return "MO";
        case 2: return "DI";
        case 3: return "MI";
        case 4: return "DO";
        case 5: return "FR";
        case 6: return "SA";
        case 7: return "SO";
        default: return "";
      }
    } else {
      switch ($wochentag) {
        case 0: return "Sonntag";
        case 1: return "Montag";
        case 2: return "Dienstag";
        case 3: return "Mittwoch";
        case 4: return "Donnerstag";
        case 5: return "Freitag";
        case 6: return "Samstag";
        case 7: return "Sonntag";
        default: return "";
      }
    }
  }

  public function getMonatname($kurz = false) {
    $monat = date("n", $this->zeit);

    if ($kurz) {
      switch ($monat) {
        case 1: return "JAN";
        case 2: return "FEB";
        case 3: return "MÄR";
        case 4: return "APR";
        case 5: return "MAI";
        case 6: return "Juni";
        case 7: return "Juli";
        case 8: return "AUG";
        case 9: return "SEP";
        case 10: return "OKT";
        case 11: return "NOV";
        case 12: return "DEZ";
        default: return "";
      }
    } else {
      switch ($monat) {
        case 1: return "Januar";
        case 2: return "Februar";
        case 3: return "März";
        case 4: return "April";
        case 5: return "Mai";
        case 6: return "Juni";
        case 7: return "Juli";
        case 8: return "August";
        case 9: return "September";
        case 10: return "Oktober";
        case 11: return "November";
        case 12: return "Dezember";
        default: return "";
      }
    }
  }

}
?>

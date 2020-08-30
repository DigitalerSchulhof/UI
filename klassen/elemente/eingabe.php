<?php
namespace UI;

/**
 * Abstrakte Klasse für alle Eingabeelemente
 */
abstract class Eingabe extends Element {
  protected $tag = "input";

  /** @var string Wert des Eingabefelds */
  protected $wert = null;
  /** @var string Typ des Eingabefelds */
  protected $typ = null;
  /** @var string Autocomplete-Wert des Eingabefelds */
  protected $autocomplete;

  /**
   * Erstellt eine neue Eingabe
   * @param   string $id ID des Eingabeelements
   */
  public function __construct($id) {
    parent::__construct();
    $this->id   = $id;
    $this->autocomplete = "on";
    $this->addKlasse("dshUiFeld");
    $this->addKlasse("dshUiEingabefeld");
    $this->setAttribut("tabindex", "0");
  }

  /**
	 * Gibt den Code des öffnenden Tags zurück (Ohne < >)
	 * @param   boolean $klammer True => mit < >; False => Ohne < >
	 * @param 	string ...$nicht Attribute, die ignoriert werden sollen
	 * @return 	string Der Code des öffnenden Tags
	 */
	public function codeAuf($klammer = true, ...$nicht) : string {
    $rueck = "";
    if($klammer) {
      $rueck = "<";
    }
    $rueck .= parent::codeAuf(false, ...$nicht);

    if($this->wert !== null && !in_array("value", $nicht))
      $rueck .= " value=\"{$this->wert}\"";
    if($this->typ !== null && !in_array("type", $nicht))
      $rueck .= " type=\"{$this->typ}\"";
    if($this->autocomplete !== null && !in_array("autocomplete", $nicht))
      $rueck .= " autocomplete=\"{$this->autocomplete}\"";
    if($klammer) {
      $rueck .= ">";
    }
    return $rueck;
  }

    /**
     * Setzt den Wert des Eingabefelds
     * @param   string $wert :)
     * @return  self
     */
    public function setWert($wert) : self {
      $this->wert = $wert;
      return $this;
    }

  /**
   * Gibt den Wert des Eingabefelds zurück
   * @return  string
   */
  public function getWert() : string {
    return $this->wert;
  }

  /**
   * Setzt den Wert des Autocomplete-Attributs
   * @param   string $autocomplete :)
   * @return  self
   */
  public function setAutocomplete($autocomplete) : self {
    $this->autocomplete = $autocomplete;
    return $this;
  }

  /**
   * Setzt, ob die Eingabe deaktiviert ist
   * @param   bool $disabled :)
   * @return  self
   */
  public function setDisabled($disabled) : self {
    if($disabled) {
      $disabled = "disabled";
    } else {
      $disabled = "";
    }
    $this->setAttribut("disabled", $disabled);
    return $this;
  }
}

/**
 * Erstellt ein Verstecktes hidden-Feld
 */
class VerstecktesFeld extends Eingabe {
  protected $typ = "hidden";

  /**
   * Feld für die Eingabe von Versteckten Informationen
   * @param string $id   ID des Feldes
   * @param string $wert Wert des Feldes
   */
  public function __construct($id, $wert) {
    parent::__construct($id);
    $this->wert = $wert;
    $this->setAttribut("tabindex", "-1");
    $this->setAttribut("autocomplete", "off");
  }
}

abstract class PlatzhalterEingabe extends Eingabe {
  /** @var string Platzhalter des Eingabefelds */
  protected $platzhalter = null;

  /**
   * Erstellt eine neue Eingabe
   *
   * @param   string $id ID des Eingabeelements
   */
  public function __construct($id) {
    parent::__construct($id);
  }


  /**
	 * Gibt den Code des öffnenden Tags zurück (Ohne < >)
	 * @param   boolean $klammer True => mit < >; False => Ohne < >
	 * @param 	string ...$nicht Attribute, die ignoriert werden sollen
	 * @return 	string Der Code des öffnenden Tags
	 */
	public function codeAuf($klammer = true, ...$nicht) : string {
    $rueck = "";
    if($klammer) {
      $rueck = "<";
    }
    $rueck .= parent::codeAuf(false, ...$nicht);

    if($this->platzhalter !== null && !in_array("placeholder", $nicht))
      $rueck .= " placeholder=\"{$this->platzhalter}\"";

    if($klammer) {
      $rueck .= ">";
    }
    return $rueck;
  }

  /**
   * Setzt den Platzhalter des Eingabefelds
   * @param   string $platzhalter :)
   * @return  self
   */
  public function setPlatzhalter($platzhalter) : self {
    $this->platzhalter = $platzhalter;
    return $this;
  }

  /**
   * Gibt den Platzhalter des Eingabefelds zurück
   * @return  string
   */
  public function getPlatzhalter() : string {
    return $this->platzhalter;
  }
}

class Uhrzeitfeld extends Eingabe {
  protected $typ = "text";
  /** @var boolean Ob bei der Ausgabe Sekunden angezeigt werden sollen */
  protected $zeigeSekunden = false;

  public function __construct($id, $sekunden = false) {
    parent::__construct($id);
    $this->zeigeSekunden = $sekunden;
    if ($sekunden) {
      $sek = "1";
    } else {
      $sek = "0";
    }
    $this->aktionen->addFunktionPrioritaet("oninput",  3, "ui.datumsanzeige.checkUhrzeit('{$this->id}', $sek)");
  }

  public function __toString() : string {
    // Werte richtig setzen
    $uhrzeit = explode(":", $this->wert);
    if (count($uhrzeit) != 2 && count($uhrzeit) != 3) {
      $uhrzeit[0] = date("H");
      $uhrzeit[1] = date("i");
      if ($this->zeigeSekunden == 2) {
        $uhrzeit[2] = date("s");
      }
    }

    $code    = "<{$this->codeAuf(false, "id", "value", "class")} id=\"{$this->id}Std\" value=\"{$uhrzeit[0]}\" class=\"dshUiEingabefeld dshUiUhrzeitfeldStd".join(" ", array_merge(array(""), $this->klassen))."\">{$this->codeZu()}";
    $code   .= " : ";
    $code   .= "<{$this->codeAuf(false, "id", "value", "class")} id=\"{$this->id}Min\" value=\"{$uhrzeit[1]}\" class=\"dshUiEingabefeld dshUiUhrzeitfeldMin".join(" ", array_merge(array(""), $this->klassen))."\">{$this->codeZu()}";

    if ($this->zeigeSekunden) {
      $code .= " : ";
      $code .= "<{$this->codeAuf(false, "id", "value", "class")} id=\"{$this->id}Sek\" value=\"$uhrzeit[2]\" class=\"dshUiEingabefeld dshUiUhrzeitfeldSek".join(" ", array_merge(array(""), $this->klassen))."\">{$this->codeZu()}";
    }

    return $code;
  }
}

class Datumfeld extends Eingabe {
  protected $typ = "text";


  public function __construct($id) {
    parent::__construct($id);
    $this->aktionen->addFunktionPrioritaet("onfocus",  3, "ui.datumsanzeige.aktion('{$this->id}', true)");
    $this->aktionen->addFunktionPrioritaet("onkeydown",  3, "ui.datumsanzeige.aktion('{$this->id}', false)");
    $this->aktionen->addFunktionPrioritaet("onchange", 3, "ui.datumsanzeige.checkTag('{$this->id}')");
  }

  /**
   * Gibt den Code für die Tageswahl zurück
   * @return string
   */
  public function tageswahlGenerieren() : string {
    $datum = explode(".", $this->wert);
    if (count($datum) != 3) {
      $datum[0] = date("d");
      $datum[1] = date("m");
      $datum[2] = date("Y");
    }

    $tag   = $datum[0];
    $monat = $datum[1];
    $jahr  = $datum[2];

    $r = "<table>";
    $monatz = new MiniIconKnopf(new \UI\Icon(\UI\Konstanten::ZURUECK), "Vorheriger Monat", null, "OR");
    $monatz->addFunktion("onclick", "ui.datumsanzeige.monataendern('{$this->id}', $tag, ".($monat-1).", $jahr)");
    $monatv = new MiniIconKnopf(new \UI\Icon(\UI\Konstanten::VOR), "Nächster Monat", null, "OL");
    $monatv->addFunktion("onclick", "ui.datumsanzeige.monataendern('{$this->id}', $tag, ".($monat+1).", $jahr)");

    $r .= "<tr><th>$monatz</th><th colspan=\"6\" class=\"dshUiTageswahlMonatname\">".Generieren::monatnameLang($monat)." $jahr</th><th>$monatv</th></tr>";

    $r .= "<tr><td class=\"dshUiTageswahlTagname\"></td>";
    for ($i = 1; $i <= 7; $i++) {
      $r .= "<td class=\"dshUiTageswahlTagname\">".Generieren::tagnameKurz($i)."</td>";
    }
    $r .= "</tr>";

    $erster = mktime(0, 0, 0, $monat, 1, $jahr);
    $wochentag = date("N", $erster);
    $letzter = mktime(0, 0, 0, $monat+1, 1, $jahr)-1;
    $letzter = date("d", $letzter);
    if ($tag > $letzter) {$tag = $letzter;}

    $nr = 1;
    $klassenzusatz = "";

    $r .= "<tr>";
    $r .= "<td class=\"dshUiTageswahlKaledenderwoche\">".date("W", mktime(0, 0, 0, $monat, $nr, $jahr))."</td>";
    // leer auffüllen, falls nicht mit Montag begonnen wird
    for ($i = 1; $i < $wochentag; $i++) {
      $r .= "<td></td>";
    }

    for ($i = $wochentag; $i <= 7; $i++) {
      $tagknopf = new Knopf(Check::fuehrendeNull($nr));
      if ($nr == $tag) {
        $tagknopf->addKlasse("dshUiTagGewaehlt");
      }
      $tagknopf->addFunktion("onclick", "ui.datumsanzeige.tageswahl.aktion('{$this->id}', $nr, $monat, $jahr)");
      $r .= "<td>$tagknopf</td>";
      $nr ++;
    }
    $r .= "</tr>";

    $wochentag = 1;
    while ($nr <= $letzter) {
      if ($wochentag == 8) {
        $r .= "</tr>";
        $wochentag = 1;
      }
      if ($wochentag == 1) {$r .= "<tr><td class=\"dshUiTageswahlKaledenderwoche\">".date("W", mktime(0, 0, 0, $monat, $nr, $jahr))."</td>";}
      $tagknopf = new Knopf(Check::fuehrendeNull($nr));
      if ($nr == $tag) {
        $tagknopf->addKlasse("dshUiTagGewaehlt");
      }
      $tagknopf->addFunktion("onclick", "ui.datumsanzeige.tageswahl.aktion('{$this->id}', $nr, $monat, $jahr)");
      $r .= "<td>$tagknopf</td>";
      $nr ++;
      $wochentag ++;
    }

    // leer auffüllen, falls nicht am Sonntag geendet
    for ($i = $wochentag; $i <= 7; $i++) {
      $r .= "<td></td>";
      $nr++;
    }
    $r .= "</tr>";

    $r .= "</table>";
    return $r;

  }

  public function __toString() : string {
    // Werte richtig setzen
    $datum = explode(".", $this->wert);
    if (count($datum) != 3) {
      $datum[0] = date("d");
      $datum[1] = date("m");
      $datum[2] = date("Y");
    }
    $code  = "<span class=\"dshUiDatumwahlFeld dshUiFeld\">";
    $code .= "<{$this->codeAuf(false, "id", "value", "class")} id=\"{$this->id}T\" value=\"{$datum[0]}\" class=\"dshUiEingabefeld dshUiDatumfeld dshUiDatumfeldT".join(" ", array_merge(array(""), $this->klassen))."\">{$this->codeZu()} . ";
    $code .= "<{$this->codeAuf(false, "id", "value", "class")} id=\"{$this->id}M\" value=\"{$datum[1]}\" class=\"dshUiEingabefeld dshUiDatumfeld dshUiDatumfeldM".join(" ", array_merge(array(""), $this->klassen))."\">{$this->codeZu()} . ";
    $code .= "<{$this->codeAuf(false, "id", "value", "class")} id=\"{$this->id}J\" value=\"{$datum[2]}\" class=\"dshUiEingabefeld dshUiDatumfeld dshUiDatumfeldJ".join(" ", array_merge(array(""), $this->klassen))."\">{$this->codeZu()} ";
    $code .= "<span class=\"dshUiDatumwahl\" id=\"{$this->id}Datumwahl\"></span>";
    $code .= "</span>";
    return $code;
  }
}

class Schieber extends Eingabe {
  protected $tag = "span";

  public function __construct($id) {
    parent::__construct($id);
    $this->removeKlasse("dshUiEingabefeld");
    $this->aktionen->addFunktionPrioritaet("onclick", 3, "ui.schieber.aktion('{$this->id}')");
  }

  public function __toString() : string {
    if($this->wert === 1) {
      $wert = "1";
    } else {
      $wert = "0";
    }

    $code  = "<{$this->codeAuf(false, "id", "value", "class")} id=\"{$this->id}Schieber\" class=\"dshUiSchieberAussen dshUiSchieber$wert ".join(" ", $this->klassen)."\"><span class=\"dshUiSchieber\"></span>{$this->codeZu()}";
    $code .= new VerstecktesFeld($this->id, $wert);
    return $code;
  }
}

class Toggle extends Schieber {
  protected $tag = "button";
  protected $typ = "button";

  /** @var string $text :) */
  protected $text;

  /**
   * Erstellt einen neuen Toggle-Knopf
   * @param string $id   :)
   * @param string $text :)
   */
  public function __construct($id, $text) {
    parent::__construct($id);
    $this->aktionen->reset();
    $this->text = $text;
    $this->addKlasse("dshUiKnopf");
    $this->addKlasse("dshUiToggle");
    $this->aktionen->addFunktionPrioritaet("onclick", 3, "ui.toggle.aktion('{$this->id}')");
  }

  /**
   * Gibt einen Klon mit passenden Klassen zurück
   * @return self
   */
  public function toStringVorbereitung() : self {
    if ($this->getAktionen()->hatAusloeser("href")) {
      $this->setTag("a");
    }
    return $this;
  }

  /**
   * Gibt den HTML-Code eines Toggle-Knopfes aus
   * @return string [description]
   */
  public function __toString() : string {
    $this->toStringVorbereitung();
    if($this->wert === "1") {
      $wert = "1";
      $this->addKlasse("dshUiToggled");
    } else {
      $wert = "0";
      $this->removeKlasse("dshUiToggled");
    }
    $code  = "<{$this->codeAuf(false, "id", "value")} id=\"{$this->id}Toggle\">{$this->text}{$this->codeZu()}";
    $code .= new VerstecktesFeld($this->id, $wert);
    return $code;
  }

  /**
   * Setzt, ob der Toggle toggled ist.
   * @param  bool $toggled :)
   * @return self
   */
  public function setToggled($toggled) : self {
    if($toggled) {
      $this->setWert("1");
    } else {
      $this->setWert("0");
    }
    return $this;
  }
}

class IconToggle extends Toggle {
  /** @var Icon $icon :) */
  protected $icon;

  /**
   * Erstellt einen neuen Toggle-Knopf
   * @param string $id   :)
   * @param string $text :)
   * @param Icon $icon :)
   */
  public function __construct($id, $text, $icon) {
    parent::__construct($id, $text);
    $this->icon = $icon;
    $this->addKlasse("dshUiKnopfIcon");
  }

  /**
   * Gibt den HTML-Code eines Toggle-Knopfes aus
   * @return string [description]
   */
  public function __toString() : string {
    $this->toStringVorbereitung();
    if($this->wert === "1") {
      $wert = "1";
      $this->addKlasse("dshUiToggled");
    } else {
      $wert = "0";
      $this->removeKlasse("dshUiToggled");
    }
    $code  = "<{$this->codeAuf(false, "id", "value")} id=\"{$this->id}Toggle\">$this->icon $this->text{$this->codeZu()}";
    $code .= new VerstecktesFeld($this->id, $wert);
    return $code;
  }
}

class IconToggleGross extends IconToggle {

  /**
   * Erstellt einen neuen Toggle-Knopf
   * @param string $id   :)
   * @param string $text :)
   * @param Icon $icon :)
   */
  public function __construct($id, $text, $icon) {
    parent::__construct($id, $text, $icon);
    $this->addKlasse("dshUiKnopfGross");
  }

  /**
   * Gibt den HTML-Code eines Toggle-Knopfes aus
   * @return string [description]
   */
  public function __toString() : string {
    $this->toStringVorbereitung();
    if($this->wert === "1") {
      $wert = "1";
      $this->addKlasse("dshUiToggled");
    } else {
      $wert = "0";
      $this->removeKlasse("dshUiToggled");
    }

    $toggleinhalt = new InhaltElement($this->text);
    $toggleinhalt->setTag("span");
    $toggleinhalt->addKlasse("dshUiToggleGrossText");
    $code  = "<{$this->codeAuf(false, "id", "value")} id=\"{$this->id}Toggle\">$this->icon $toggleinhalt{$this->codeZu()}";
    $code .= new VerstecktesFeld($this->id, $wert);

    return $code;
  }
}

class MiniIconToggle extends IconToggle {
  protected $position;

  /**
   * Erstellt einen neuen Toggle-Knopf
   * @param string $id   :)
   * @param string $text :)
   * @param Icon $icon :)
   */
  public function __construct($id, $text, $icon, $position = "OR") {
    parent::__construct($id, $text, $icon);
    $this->addKlasse("dshUiKnopfIconMini");
    $this->addKlasse("dshUiHinweisTraeger");
    $this->position = $position;
  }

  /**
   * Gibt den HTML-Code eines Toggle-Knopfes aus
   * @return string [description]
   */
  public function __toString() : string {
    $this->toStringVorbereitung();
    if($this->wert === "1") {
      $wert = "1";
      $this->addKlasse("dshUiToggled");
    } else {
      $wert = "0";
      $this->removeKlasse("dshUiToggled");
    }

    $hinweis = new Hinweis($this->text, $this->position);
    $code  = "<{$this->codeAuf(false, "id", "value")} id=\"{$this->id}Toggle\">$hinweis $this->icon{$this->codeZu()}";
    $code .= new VerstecktesFeld($this->id, $wert);

    return $code;
  }
}

class Textfeld extends PlatzhalterEingabe {
  protected $typ = "text";

  /**
   * Erstellt eine neue Eingabe
   *
   * @param   string $id ID des Eingabeelements
   */
  public function __construct($id) {
    parent::__construct($id);
  }
}

class Zahlenfeld extends PlatzhalterEingabe {
  protected $typ = "number";

  /** @var string Minimalwert des Zahlbereichs */
  private $min = null;
  /** @var string Maximalwert des Zahlbereichs */
  private $max = null;
  /** @var string Schrittweite des Zahlbereichs */
  private $schritt = null;

  /**
   * Erstellt eine neue Eingabe
   *
   * @param   string $id ID des Eingabeelements
   * @param   string $min Minimalwert des Zahlbereichs
   * @param   string $max Maximalwert des Zahlbereichs
   * @param   string $schritt Schrittweite des Zahlbereichs
   */
  public function __construct($id, $min = null, $max = null, $schritt = null) {
    parent::__construct($id);
    $this->min      = $min;
    $this->max      = $max;
    $this->schritt  = $schritt;
    $this->addKlasse("dshUiZahlfeld");
  }

  /**
	 * Gibt den Code des öffnenden Tags zurück (Ohne < >)
	 * @param   boolean $klammer True => mit < >; False => Ohne < >
	 * @param 	string ...$nicht Attribute, die ignoriert werden sollen
	 * @return 	string Der Code des öffnenden Tags
	 */
	public function codeAuf($klammer = true, ...$nicht) : string {
    $rueck = "";
    if($klammer) {
      $rueck = "<";
    }
    $rueck .= parent::codeAuf(false, ...$nicht);

    if($this->min !== null && !in_array("min", $nicht))
      $rueck .= " min=\"{$this->min}\"";
    if($this->max !== null && !in_array("max", $nicht))
      $rueck .= " max=\"{$this->max}\"";
    if($this->schritt !== null && !in_array("step", $nicht))
      $rueck .= " step=\"{$this->schritt}\"";

    if($klammer) {
      $rueck .= ">";
    }
    return $rueck;
  }
}

class Farbfeld extends Textfeld {
  protected $tag = "span";

  /** @var bool Ob der Farbpicker sichtbar ist */
  protected $picker;

  protected $feldid;

  /**
   * Erstellt eine neue Eingabe
   *
   * @param string $id      ID des Eingabeelements
   * @param bool   $picker  Ob der Farbpicker sichtbar ist
   */
  public function __construct($id, $picker = true) {
    parent::__construct("{$id}Auswahl");
    $this->addKlasse("dshUiFarbfeld");
    $this->picker = $picker;
    $this->feldid = $id;
  }

  public function __toString() : string {
    $fbl = [
      ["rgba(239,154,154,1)", "rgba(244,143,177,1)", "rgba(206,147,216,1)", "rgba(179,157,219,1)",  "rgba(159,168,218,1)",  "rgba(144,202,249,1)",  "rgba(129,212,250,1)",  "rgba(128,222,234,1)",  "rgba(128,203,196,1)",  "rgba(165,214,167,1)",  "rgba(197,225,165,1)",  "rgba(230,238,156,1)",  "rgba(255,245,157,1)",  "rgba(255,224,130,1)",  "rgba(255,204,128,1)",  "rgba(255,171,145,1)",  "rgba(188,170,164,1)",  "rgba(238,238,238,1)"],
      ["rgba(229,115,115,1)", "rgba(240,98,146,1)",  "rgba(186,104,200,1)", "rgba(149,117,205,1)",  "rgba(121,134,203,1)",  "rgba(100,181,246,1)",  "rgba(79,195,247,1)",   "rgba(77,208,225,1)",   "rgba(77,182,172,1)",   "rgba(129,199,132,1)",  "rgba(174,213,129,1)",  "rgba(220,231,117,1)",  "rgba(255,241,118,1)",  "rgba(255,213,79,1)",   "rgba(255,183,77,1)",   "rgba(255,138,101,1)",  "rgba(161,136,127,1)",  "rgba(224,224,224,1)"],
      ["rgba(239,83,80,1)",   "rgba(236,64,122,1)",  "rgba(171,71,188,1)",  "rgba(126,87,194,1)",   "rgba(92,107,192,1)",   "rgba(66,165,245,1)",   "rgba(41,182,246,1)",   "rgba(38,198,218,1)",   "rgba(38,166,154,1)",   "rgba(102,187,106,1)",  "rgba(156,204,101,1)",  "rgba(212,225,87,1)",   "rgba(255,238,88,1)",   "rgba(255,202,40,1)",   "rgba(255,167,38,1)",   "rgba(255,112,67,1)",   "rgba(141,110,99,1)",   "rgba(189,189,189,1)"],
      ["rgba(244,67,54,1)",   "rgba(233,30,99,1)",   "rgba(156,39,176,1)",  "rgba(103,58,183,1)",   "rgba(63,81,181,1)",    "rgba(33,150,243,1)",   "rgba(3,169,244,1)",    "rgba(0,188,212,1)",    "rgba(0,150,136,1)",    "rgba(76,175,80,1)",    "rgba(139,195,74,1)",   "rgba(205,220,57,1)",   "rgba(255,235,59,1)",   "rgba(255,193,7,1)",    "rgba(255,152,0,1)",    "rgba(255,87,34,1)",    "rgba(121,85,72,1)",    "rgba(158,158,158,1)"],
      ["rgba(229,57,53,1)",   "rgba(216,27,96,1)",   "rgba(142,36,170,1)",  "rgba(94,53,177,1)",    "rgba(57,73,171,1)",    "rgba(30,136,229,1)",   "rgba(3,155,229,1)",    "rgba(0,172,193,1)",    "rgba(0,137,123,1)",    "rgba(67,160,71,1)",    "rgba(124,179,66,1)",   "rgba(192,202,51,1)",   "rgba(253,216,53,1)",   "rgba(255,179,0,1)",    "rgba(251,140,0,1)",    "rgba(244,81,30,1)",    "rgba(109,76,65,1)",    "rgba(117,117,117,1)"],
      ["rgba(211,47,47,1)",   "rgba(194,24,91,1)",   "rgba(123,31,162,1)",  "rgba(81,45,168,1)",    "rgba(48,63,159,1)",    "rgba(25,118,210,1)",   "rgba(2,136,209,1)",    "rgba(0,151,167,1)",    "rgba(0,121,107,1)",    "rgba(56,142,60,1)",    "rgba(104,159,56,1)",   "rgba(175,180,43,1)",   "rgba(251,192,45,1)",   "rgba(255,160,0,1)",    "rgba(245,124,0,1)",    "rgba(230,74,25,1)",    "rgba(93,64,55,1)",     "rgba(97,97,97,1)"],
      ["rgba(211,47,47,1)",   "rgba(194,24,91,1)",   "rgba(123,31,162,1)",  "rgba(81,45,168,1)",    "rgba(48,63,159,1)",    "rgba(25,118,210,1)",   "rgba(2,136,209,1)",    "rgba(0,151,167,1)",    "rgba(0,121,107,1)",    "rgba(56,142,60,1)",    "rgba(104,159,56,1)",   "rgba(175,180,43,1)",   "rgba(251,192,45,1)",   "rgba(255,160,0,1)",    "rgba(245,124,0,1)",    "rgba(230,74,25,1)",    "rgba(93,64,55,1)",     "rgba(97,97,97,1)"],
      ["rgba(183,28,28,1)",   "rgba(136,14,79,1)",   "rgba(74,20,140,1)",   "rgba(49,27,146,1)",    "rgba(26,35,126,1)",    "rgba(13,71,161,1)",    "rgba(1,87,155,1)",     "rgba(0,96,100,1)",     "rgba(0,77,64,1)",      "rgba(27,94,32,1)",     "rgba(51,105,30,1)",    "rgba(130,119,23,1)",   "rgba(245,127,23,1)",   "rgba(255,111,0,1)",    "rgba(230,81,0,1)",     "rgba(191,54,12,1)",    "rgba(62,39,35,1)",     "rgba(33,33,33,1)"]
    ];
    $r  = "{$this->codeAuf("id", "type")}";
      $r .= "<div class=\"dshUiFarbbeispiele\">";
        for($x = 0; $x < 18; $x++) {
          $r .= "<div class=\"dshUiFarbbeispieleSchattierung\">";
          for($y = 0; $y < 8; $y++) {
            $r .= "<span class=\"dshUiFarbbeispiel\" style=\"background-color:{$fbl[$y][$x]}\" tabindex=\"0\" onclick=\"ui.farbbeispiel.aktion(this)\"></span>";
          }
          $r .= "</div>";
        }
        $verborgen = "";
        if(!$this->picker) {
          $verborgen = " dshUiUnsichtbar";
        }
        $r .= "<input id=\"{$this->feldid}\" class=\"dshUiFeld$verborgen\" value=\"{$this->wert}\" type=\"color\" oninput=\"ui.farbbeispiel.aktion(this)\">";
      $r .= "</div>";
    $r .= "{$this->codeZu()}";

    return $r;
  }

  public function setWert($wert) : self {
    if (Check::istRgbaFarbe($wert)) {
      return parent::setWert(Generieren::RgbaZuHex($wert));
    } else {
      return parent::setWert($wert);
    }
  }
}

class Passwortfeld extends Textfeld {
  protected $typ = "password";
  /** @var Passwortfeld $bezug Passwortfeld, mit dem das Passwort verglichen werden soll */
  protected $bezug = null;

  /**
   * Erstellt eine neue Eingabe
   *
   * @param   string $id ID des Eingabeelements
   * @param   Textfeld $bezug Textfeld, mit dem das Passwort verglichen werden soll
   */
  public function __construct($id, $bezug = null) {
    parent::__construct($id);
    $this->bezug = $bezug;
    if($this->bezug !== null) {
      $this->aktionen->addFunktionPrioritaet("oninput", 3, "ui.passwort.aktion('{$this->bezug->getID()}', '{$this->id}')");
      $this->bezug->getAktionen()->addFunktionPrioritaet("oninput", 3, "ui.passwort.aktion('{$this->bezug->getID()}', '{$this->id}')");
    }
  }

  /**
   * Gibt das Bezugsfeld des Eingabefelds zurück
   * @return  Passwortfeld
   */
  public function getBezugsfeld() : Passwortfeld {
    return $this->bezug;
  }
}

class Mailfeld extends Textfeld {
  protected $typ = "text";

  public function __construct($id) {
    parent::__construct($id);
    $this->aktionen->addFunktionPrioritaet("oninput", 3,  "ui.mail.aktion('{$this->id}')");
  }
}

class Spamschutz extends Textfeld {
  protected $typ = "text";

  /** @var char[] Text auf dem Captcha */
  private $text;
  /** @var string Spamschutzid */
  private $spamid;
  /** @var string Typ der erstellten Formen */
  private $bildtyp;

  const BILDTYPEN = ["Kreise", "Rechtecke", "Linie"];

  public function __construct($id, $stellen = 5) {
    parent::__construct($id);
    $this->addKlasse("dshUiSpamschutz");

    // Zufallstext erstellen
    $auswahlzeichen = "abdefhijkmnpqrtyABDEFGHJKLMNPRTY2345678";
    $text = [];
    while(count($text) < $stellen) {
      $stelle = rand(1,strlen($auswahlzeichen));
      $text[] = substr($auswahlzeichen,$stelle-1,1);
    }
    $this->text = $text;

    // Spamschutzid erstellen
    do {
      $this->spamid = uniqid();
    } while(isset($_SESSION["SPAMSCHUTZ_{$this->spamid}"]));
    unset($_SESSION["SPAMSCHUTZ_{$this->spamid}"]);
    $_SESSION["SPAMSCHUTZ_{$this->spamid}"] = implode('', $text);

    // Bildtypen setzen
    $this->bildtyp = self::BILDTYPEN[rand(0,15) % 3];
  }

  /**
   * Erstellt das Textfeld und das Captcha-Bild
   * @return string :)
   */
  public function __toString() : string {
    global $ROOT;
    $code = "";
    $breite = (count($this->text))*35+10;
    $hoehe = 50;
    $bild = imagecreatetruecolor($breite, $hoehe);
    $hintergrund = imagecolorallocate($bild, 255, 255, 255);
    // Bildschrimhintergrund
    imagefilledrectangle($bild, 0, 0,count($this->text)*40, 50,$hintergrund);

    // Hintergrund füllen
    if ($this->bildtyp == "Linie") {
      $anzahl = (($breite*$hoehe)/10) / rand(3,5);
    } else {
      $anzahl = (($breite*$hoehe)/40) / rand(2,4);
    }
    for ($i=0; $i<$anzahl; $i++) {
      $g = rand(0,255);
      $b = max(0, $g+rand(-50,0));
      $r = max(0, $g+rand(-50,0));
      while (($r + $g + $b) > 400) {
        $r = max(0, $r-rand(5,20));
        $g = max(0, $g-rand(5,20));
        $b = max(0, $b-rand(5,20));
      }
      $farbe = imagecolorallocate($bild, $r, $g, $b);
      $x = rand(0,$breite);
      $y = rand(0,$hoehe);
      if ($this->bildtyp == "Rechtecke") {
        $formbreite = rand(5,20);
        $formhoehe = rand(5,20);
        imagefilledrectangle($bild, $x, $y, $x+$formbreite, $y+$formhoehe, $farbe);
      } else if ($this->bildtyp == "Kreise") {
        $formbreite = rand(5,20);
        $formhoehe = rand(5,20);
        imagefilledellipse ($bild, $x, $y, $formbreite, $formhoehe, $farbe);
      } else {
        $formbreite = rand(-70,70);
        $formhoehe = rand(-70,70);
        imageline ($bild, $x, $y, $x+$formbreite, $y+$formhoehe, $farbe);
      }
    }

    $schrift = "$ROOT/dateien/Kern/fonts/dshStandard-b.ttf";
    $schriftgroesse = 25;
    $x = -22;
    $y = 35;
    foreach ($this->text as $t) {
      $r = rand(1,255);
      $g = rand(1,255);
      $b = rand(1,255);
      while (($r + $g + $b) < 550) {
        $r = min(255, $r+rand(5,50));
        $g = min(255, $g+rand(5,50));
        $b = min(255, $b+rand(5,50));
      }
      $farbe = imagecolorallocate($bild, $r, $g, $b);
      $x += 35;
      $winkel = rand(-20,20);
      $abstandx = $x + rand(-5,3);
      $abstandy = $y + rand(-3,3);
      imagettftext($bild, $schriftgroesse, $winkel, $abstandx, $abstandy, $farbe, $schrift, $t);
    }
    ob_start();
    imagepng($bild);
    $b64 = ob_get_contents();
    ob_end_clean();
    $b64 = base64_encode($b64);
    imagedestroy($bild);

    $code .= "<div class=\"dshUiSpamschutzA\">";
      $code .= "<img id=\"{$this->id}Spamschutz\" src=\"data:image/png;base64, {$b64}\" class=\"dshUiSpamschutzBild\" style=\"float: left; margin-right: 10px;\">";
      $code .= "<div class=\"dshUiSpamschutzEingabe\">";
        $code .= "<span class=\"dshUiSpamschutzHinweis\">Bitte die Zeichen aus dem Bild in das Eingabefeld übertragen.</span>";
        $code .= "{$this->codeAuf()}{$this->codeZu()}";
        $code .= new VerstecktesFeld("{$this->id}Spamid", $this->spamid);
      $code .= "</div>";
    $code .= "</div>";
    return $code;
  }

}

class Textarea extends Textfeld {
  protected $tag = "textarea";

  /**
   * Erstellt eine neue Eingabe
   *
   * @param   string $id ID der Textarea
   */
  public function __construct($id) {
    parent::__construct($id);
    $this->addKlasse("dshUiTextarea");
  }

  public function __toString() : string {
    return "<{$this->codeAuf(false, "value")}>{$this->wert}{$this->codeZu()}";
  }
}

class Editor extends Textarea {
  protected $tag = "textarea";

  /**
   * Erstellt eine neue Eingabe
   *
   * @param   string $id ID der Textarea
   */
  public function __construct($id) {
    parent::__construct($id);
    $this->addKlasse("dshUiTextarea");
  }
}


class Option extends Eingabe {
  protected $tag = "option";

  /** @var string $text Beschreibung der Option */
  protected $text;
  /** @var string $gesetzt :) */
  protected $gesetzt;

  /**
   * @param string $text Text der Option
   * @param string $wert Wert der Option
   */
  public function __construct($text = null, $wert = "") {
    parent::__construct(null);
    $this->gesetzt = false;
    $this->text = $text;
    $this->wert = $wert;
    $this->removeKlasse("dshUiEingabefeld");
  }

  /**
   * Setzt den Text der Option
   * @param  string $text :)
   * @return self         :)
   */
  public function setText($text) : self {
    $this->text = $text;
    return $this;
  }

  /**
   * Gibt an, ob das Objekt aktiv ist
   * @param  boolean $gesetzt :)
   * @return self             :)
   */
  public function setGesetzt($gesetzt) : self {
    $this->gesetzt = $gesetzt;
    return $this;
  }


  /**
   * Gibt den Code der Option zurück
   * @return string       Code der Option
   */
  public function __toString() : string {
    if ($this->gesetzt) {
      $wahl = " selected";
    } else {
      $wahl = "";
    }
    return "<{$this->codeAuf(false)}$wahl>{$this->text}{$this->codeZu()}";
  }
}

class Toggleoption extends Option {
  protected $tag = "span";

  public function __construct($id) {
    parent::__construct($id);
    $this->addKlasse("dshUiKnopf");
    $this->addKlasse("dshUiToggle");
  }

  /**
   * Gibt den Code der Option zurück
   * @return string       Code der Option
   */
  public function __toString() : string {
    if ($this->gesetzt) {
      $this->addKlasse("dshUiToggled");
    } else {
      $this->removeKlasse("dshUiToggled");
    }
    if ($this->getAktionen()->hatAusloeser("href")) {
      $this->setTag("a");
    }
    return "{$this->codeAuf()}{$this->text}{$this->codeZu()}";
  }
}

class Multitoggle extends Eingabe {
  protected $tag = "span";
  /** @var Toggle[]  */
  private $toggles;

  public function __construct($id) {
    parent::__construct($id);
    $toggles = [];
  }

  /**
   * Fügt einen Toggle zum Multitoggle hinzu
   * @param  [type] $t [description]
   * @return self      [description]
   */
  public function add($t) : self {
    $this->toggles[] = $t;
    return $this;
  }

  public function __toString () : string {
    return join(" ", $this->toggles);
  }
}

class Togglegruppe extends Eingabe {
  protected $tag = "span";

  /** @var Toggleoption[] Optionen der Togglebuttons */
  private $optionen;

  /**
   * @param string $id       ID der Togglebuttons
   * @param string $text Text der ersten Option
   * @param string $textwert Wert der ersten Option
   * @param string $wert     Wert der Togglebuttons
   * @param string $klasse   CSS-Klasse der Togglebuttons
   * @param Aktion $aktion   Aktion der Togglebuttons
   */
  public function __construct($id) {
    parent::__construct($id);
    $this->setAttribut("tabindex", null);
    $this->removeKlasse("dshUiEingabefeld");
  }

  /**
   * Fügt der Togglegruppe-Box eine Option hinzu
   * @param Toggleoption $option :)
   */
  public function addOption($option) {
    $option->setTag("span");
    $option->addKlasse("dshUiToggle");
    $this->optionen[] = $option;
  }

  /**
   * Gibt die Togglebuttons aus
   * @return string Code der Togglebuttons
   */
  public function __toString() : string {
    $code   = "<{$this->codeAuf(false, "value", "id")} id=\"{$this->getID()}Feld\">";
    $knopfId = 0;
    $anzahl = count($this->optionen);

    for ($i=0; $i<$anzahl; $i++) {
      $o = $this->optionen[$i];
      if ($o->getWert() == $this->getWert()) {
        $o->setGesetzt(true);
        $knopfId = $i;
      }
      else {
        $o->setGesetzt(false);
      }
      $o->setID("{$this->id}Knopf$i");
      $o->getAktionen()->setFunktion("onclick", 3, "ui.togglegruppe.aktion('$this->id', '$i', '$anzahl', '{$o->getWert()}')");

      $code .= "$o ";
    }

    if ($anzahl > 0) {$code = substr($code, 0, strlen($code)-1);}

    $code .= new VerstecktesFeld($this->id, $this->optionen[$knopfId]->getWert());
    $code .= new VerstecktesFeld("{$this->id}KnopfId", $knopfId);
    $code .= $this->codeZu();
    return $code;
  }
}

class Auswahl extends Eingabe {
  protected $tag = "select";
  /** @var Option[] Optionen der Selectbox */
  private $optionen;

  /**
   * Erzeugt eine neue Artwahl
   * @param string $id   :)
   * @param string $wert :)
   */
  public function __construct($id, $wert = null) {
    parent::__construct($id);
    $this->optionen = [];
    $this->wert     = $wert;
    $this->addKlasse("dshUiSelectfeld");
  }

  /**
   * Fügt der Select-Box eine Option hinzu
   * @param string $text      Text der Option
   * @param string $wert      Wert der Option
   * @param bool   $selected  Ob die Option ausgewählt ist
   */
  public function add($text, $wert, $selected = false) {
    $this->optionen[] = new Option($text, $wert);
    if($selected) {
      $this->wert = $wert;
    }
  }

  /**
   * Gibt die Selectbox aus
   * @return string Code der Selectbox
   */
  public function __toString() : string {
    $code   = $this->codeAuf();
    if($this->wert === null) {
      $code  .= "<option selected disabled value=\"\" style=\"display:none\">Auswählen</option>";
    }
    foreach ($this->optionen as $opt) {
      if ($opt->getWert() == $this->wert) {
        $opt->setGesetzt(true);
      } else {
        $opt->setGesetzt(false);
      }
      $code .= $opt;
    }
    $code .= $this->codeZu();
    return $code;
  }
}
?>

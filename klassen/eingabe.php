<?php
namespace UI\Elemente;

/**
 * @author DSH
 *
 * Abstrakte Klasse für alle Eingabeelemente
 */
abstract class Eingabe extends Element {
  protected $tag = "input";

  /** @var string Wert des Eingabefelds */
  protected $wert = null;
  /** @var string Typ des Eingabefelds */
  protected $typ = null;

  /**
   * Erstellt eine neue Eingabe
   *
   * @param string $id ID des Eingabeelements
   */
  public function __construct($id) {
    $this->id = $id;
  }

  /**
	 * Gibt den Code des öffnenden Tags zurück (Ohne < >)
	 * @param string ...$nicht Attribute, die ignoriert werden sollen
	 * @return string Der Code des öffnenden Tags
	 */
	public function codeAuf(...$nicht) : string {
    $rueck = parent::codeAuf(...$nicht);
    if($this->wert !== null && !in_array("value", $nicht))
      $rueck .= " value=\"{$this->wert}\"";
    if($this->typ !== null && !in_array("type", $nicht))
      $rueck .= " type=\"{$this->typ}\"";

    return $rueck;
  }

  /**
   * Setzt den Wert des Eingabefelds
   * @param string $wert :)
   * @return self
   */
  public function setWert($wert) : self {
    $this->wert = $wert;
    return $this;
  }

  /**
   * Gibt den Wert des Eingabefelds zurück
   * @return string
   */
  public function getWert() : string {
    return $this->wert;
  }
}

abstract class PlatzhalterEingabe extends Eingabe {
  /** @var string Platzhalter des Eingabefelds */
  protected $platzhalter = null;

  /**
   * Erstellt eine neue Eingabe
   *
   * @param string $id ID des Eingabeelements
   */
  public function __construct($id) {
    parent::__construct($id);
    $this->dazuKlasse("dshUiEingabefeld");
  }


  /**
   * Gibt den Code des öffnenden Tags zurück (Ohne < >)
   * @param string ...$nicht Attribute, die ignoriert werden sollen
   * @return string Der Code des öffnenden Tags
   */
  public function codeAuf(...$nicht) : string {
    $rueck = parent::codeAuf(...$nicht);
    if($this->platzhalter !== null && !in_array("placeholder", $nicht))
      $rueck .= " placeholder=\"{$this->platzhalter}\"";

    return $rueck;
  }

  /**
   * Setzt den Platzhalter des Eingabefelds
   * @param string $platzhalter :)
   * @return self
   */
  public function setPlatzhalter($platzhalter) : self {
    $this->platzhalter = $platzhalter;
    return $this;
  }

  /**
   * Gibt den Platzhalter des Eingabefelds zurück
   * @return string
   */
  public function getPlatzhalter() : string {
    return $this->platzhalter;
  }
}

class Uhrzeitfeld extends Eingabe {
  protected $typ = "text";
  /** @var boolean Ob bei der Ausgabe Sekunden angezeigt werden sollen */
  protected $zeigeSekunden = false;

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

    $sekunden = "0";
    if($this->zeigeSekunden)
      $sekunden = "1";

    $this->wegAktion("onchange", "onkeyup");
    $this->dazuAktion("onchange", "ui.datumsanzeige.checkUhrzeit(\"{$this->getID()}\", $sekunden)");
    $this->dazuAktion("onkeyup",  "ui.datumsanzeige.checkUhrzeit(\"{$this->getID()}\", $sekunden)");

    $code    = "<{$this->codeAuf("id", "value", "class")} id=\"{$this->getId()}Std\" value=\"{$uhrzeit[0]}\" class=\"dshUiEingabefeld dshUiUhrzeitfeldStd".join(" ", array_merge(array(""), $this->klassen))."\">{$this->codeZu()}";
    $code   .= " : ";
    $code   .= "<{$this->codeAuf("id", "value", "class")} id=\"{$this->getId()}Min\" value=\"{$uhrzeit[1]}\" class=\"dshUiEingabefeld dshUiUhrzeitfeldMin".join(" ", array_merge(array(""), $this->klassen))."\">{$this->codeZu()}";

    if ($this->zeigeSekunden) {
      $code .= " : ";
      $code .= "<{$this->codeAuf("id", "value", "class")} id=\"{$this->getId()}Sek\" value=\"$uhrzeit[2]\" class=\"dshUiEingabefeld dshUiUhrzeitfeldSek".join(" ", array_merge(array(""), $this->klassen))."\">{$this->codeZu()}";
    }

    return $code;
  }
}

class Datumfeld extends Eingabe {
  protected $typ = "text";

  public function __toString() : string {
    // Werte richtig setzen
    $datum = explode(".", $this->wert);
    if (count($datum) != 3) {
      $datum[0] = date("d");
      $datum[1] = date("m");
      $datum[2] = date("Y");
    }

    $this->wegAktion("onfocus", "onblur", "onchange", "onkeyup");
    $this->dazuAktion("onfocus",  "ui.datumsanzeige.tageswahl.generieren(\"{$this->getId()}\", true)");
    $this->dazuAktion("onblur",   "ui.datumsanzeige.tageswahl.generieren(\"{$this->getId()}\", false)");
    $this->dazuAktion("onchange", "ui.datumsanzeige.checkTag(\"{$this->getId()}\")");
    $this->dazuAktion("onkeyup",  "ui.datumsanzeige.checkTag(\"{$this->getId()}\")");

    $code  = "<{$this->codeAuf("id", "value", "class")} id=\"{$this->getId()}T\" value=\"{$datum[0]}\" class=\"dshUiEingabefeld dshUiDatumfeldT".join(" ", array_merge(array(""), $this->klassen))."\">{$this->codeZu()} ";
    $code .= "<{$this->codeAuf("id", "value", "class")} id=\"{$this->getId()}M\" value=\"{$datum[1]}\" class=\"dshUiEingabefeld dshUiDatumfeldM".join(" ", array_merge(array(""), $this->klassen))."\">{$this->codeZu()} ";
    $code .= "<{$this->codeAuf("id", "value", "class")} id=\"{$this->getId()}J\" value=\"{$datum[2]}\" class=\"dshUiEingabefeld dshUiDatumfeldJ".join(" ", array_merge(array(""), $this->klassen))."\">{$this->codeZu()} ";
    $code .= "<div class=\"dshUiDatumwahl\" id=\"{$this->getId()}Datumwahl\"></div>";

    return $code;
  }
}

class Schieber extends Eingabe {
  protected $tag = "span";

  public function __toString() : string {
    $wert = "0";
    if($this->wert === 1)
      $wert = "1";

    $this->wegAktion("onclick");
    $this->dazuAktion("onclick",  "ui.schieber.aktion(\"{$this->getId()}\")");

    $code  = "<{$this->codeAuf("id", "value", "class")} id=\"{$this->getId()}Schieber\" class=\"dshUiSchieberAussen dshUiSchieber$wert".join(" ", array_merge(array(""), $this->klassen))."\"><span class=\"dshUiSchieber\"></span>{$this->codeZu()}";
    $code .= "<input type=\"hidden\" id=\"{$this->getId()}\" value=\"$wert\">";

    return $code;
  }
}

class Textfeld extends PlatzhalterEingabe {
  protected $typ = "text";

  /**
   * Erstellt eine neue Eingabe
   *
   * @param string $id ID des Eingabeelements
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
   * @param string $id ID des Eingabeelements
   * @param string $min Minimalwert des Zahlbereichs
   * @param string $max Maximalwert des Zahlbereichs
   * @param string $schritt Schrittweite des Zahlbereichs
   */
  public function __construct($id, $min = null, $max = null, $schritt = null) {
    parent::__construct($id);
    $this->min      = $min;
    $this->max      = $max;
    $this->schritt  = $schritt;
    $this->dazuKlasse("dshUiZahlfeld");
  }

  /**
   * Gibt den Code des öffnenden Tags zurück (Ohne < >)
   * @param string ...$nicht Attribute, die ignoriert werden sollen
   * @return string Der Code des öffnenden Tags
   */
  public function codeAuf(...$nicht) : string {
    $rueck = parent::codeAuf(...$nicht);
    if($this->min !== null && !in_array("min", $nicht))
      $rueck .= " min=\"{$this->min}\"";
    if($this->max !== null && !in_array("max", $nicht))
      $rueck .= " max=\"{$this->max}\"";
    if($this->schritt !== null && !in_array("step", $nicht))
      $rueck .= " step=\"{$this->schritt}\"";

    return $rueck;
  }
}

class Farbfeld extends Textfeld {
  protected $typ = "color";

  /**
   * Erstellt eine neue Eingabe
   *
   * @param string $id ID des Eingabeelements
   */
  public function __construct($id) {
    parent::__construct($id);
    $this->dazuKlasse("dshUiFarbfeld");
  }
}

class Passwortfeld extends Textfeld {
  protected $typ = "password";
  /** @var Textfeld $bezug Textfeld, mit dem das Passwort verglichen werden soll */
  protected $bezug = null;

  /**
   * Erstellt eine neue Eingabe
   *
   * @param string $id ID des Eingabeelements
   * @param Textfeld $bezug Textfeld, mit dem das Passwort verglichen werden soll
   */
  public function __construct($id, $bezug = null) {
    parent::__construct($id);
    $this->bezug = $bezug;
  }

  /**
   * Setzt das Bezugsfeld des Eingabefelds
   * @param Textfeld $bezug :)
   * @return self
   */
  public function setBezugsfeld($bezug) : self {
    $this->bezug = $bezug;
    return $this;
  }

  /**
   * Gibt das Bezugsfeld des Eingabefelds zurück
   * @return Textfeld
   */
  public function getBezugsfeld() : Textfeld {
    return $this->bezug;
  }

  public function __toString() : string {
    if($this->bezug !== null) {
      $this->wegAktion("onchange");
      $this->dazuAktion("onchange", "ui.passwort.aktion(\"{$this->bezug->getId()}\", \"{$this->getId()}\")");
    }

    return parent::__toString();
  }
}

class Mailfeld extends Textfeld {
  protected $typ = "text";

  public function __toString() : string {
    $this->wegAktion("onchange");
    $this->dazuAktion("onchange", "ui.mail.aktion(\"{$this->getId()}\")");
    return parent::__toString()."</td><td><span id=\"{$this->getId()}Pruefen\" class=\"dshUiPruefen0\"></span>";
  }
}

class Textarea extends Textfeld {
  protected $tag = "textarea";

  /**
   * Erstellt eine neue Eingabe
   *
   * @param string $id ID der Textarea
   */
  public function __construct($id) {
    parent::__construct($id);
    $this->dazuKlasse("dshUiTextarea");
  }

  public function __toString() : string {
    return "<{$this->codeAuf("value")}>{$this->wert}{$this->codeZu()}";
  }
}




class Option {
  /** @var string Beschreibung der Option */
  private $text;
  /** @var string Wert der Option */
  private $wert;

  /**
   * @param string $text Text der Option
   * @param string $wert Wert der Option
   */
  public function __construct($text, $wert) {
    $this->text = $text;
    $this->wert = $wert;
  }

  /**
   * Gibt den Wert der Option zurück
   * @return string Wert der Option
   */
  public function getWert() : string {
    return $this->wert;
  }

  /**
   * Gibt den Text der Option zurück
   * @return string Text der Option
   */
  public function getText() : string {
    return $this->text;
  }

  /**
   * Gibt den Code der Option zurück
   * @param string $wert Wert, der gewählt wurde
   * @return string       Code der Option
   */
  public function ausgabe($wert = "") : string {
    if ($this->wert == $wert) {
      $wahl = " selected";
    }
    else {$wahl = "";}
    return "<option value=\"{$this->wert}\"$wahl>{$this->text}</option>";
  }
}

class Togglegruppe extends Eingabe {
  /** @var Option[] Optionen der Togglebuttons */
  private $optionen;

  /**
   * @param string $id       ID der Togglebuttons
   * @param string $text Text der ersten Option
   * @param string $textwert Wert der ersten Option
   * @param string $wert     Wert der Togglebuttons
   * @param string $klasse   CSS-Klasse der Togglebuttons
   * @param Aktion $aktion   Aktion der Togglebuttons
   */
  public function __construct($id, $text, $textwert, $wert = "", $klasse = "", $aktion = null) {
    parent::__construct($id, $wert, $klasse, $aktion);
    $this->optionen = array();
    $this->optionen[] = new Option ($text, $textwert);
  }

  /**
   * Fügt der Select-Box eine Option hinzu
   * @param string $text Text der Option
   * @param string $wert Wert der Option
   */
  public function dazu($text, $wert) {
    $this->optionen[] = new Option ($text, $wert);
  }

  /**
   * Gibt die Togglebuttons aus
   * @return string Code der Togglebuttons
   */
  public function __toString() : string {
    $code   = "";
    $knopfId = 0;
    $anzahl = count($this->optionen);

    for ($i=0; $i<$anzahl; $i++) {
      if ($this->optionen[$i]->getWert() == $this->wert) {
        $knopfId = $i;
        $toggled = " dshUiToggled";
      }
      else {$toggled = "";}
      $aktionen = $this->aktion->klonen();
      $aktionen->dazu("onclick", "ui.toggle.aktion(\"$this->getId()\", \"$i\", \"$anzahl\", \"{$this->optionen[$i]->getWert()}\")", true);
      $code .= "<span id=\"{$this->getId()}Knopf$i\" class=\"dshUiToggle $toggled $this->klasse\"{$aktionen->ausgabe()}>{$this->optionen[$i]->getText()}</span> ";
    }

    $code .= "<input type=\"hidden\" id=\"$this->getId()\" name=\"$this->getId()\" value=\"{$this->optionen[$knopfId]->getWert()}\">";
    $code .= "<input type=\"hidden\" id=\"{$this->getId()}KnopfId\" name=\"{$this->getId()}KnopfId\" value=\"$knopfId\">";
    return $code;
  }
}

class Auswahl extends Eingabe {
  /** @var Option[] Optionen der Selectbox */
  private $optionen;

  /**
   * @param string $id       ID der Selectbox
   * @param string $text Text der ersten Option
   * @param string $textwert Wert der ersten Option
   * @param string $wert     Wert der Selectbox
   * @param string $klasse   CSS-Klasse der Selectbox
   * @param Aktion $aktion   Aktion der Selectbox
   */
  public function __construct($id, $text, $textwert, $wert = "", $klasse = "", $aktion = null) {
    parent::__construct($id, $wert, $klasse, $aktion);
    $this->optionen = array();
    $this->optionen[] = new Option ($text, $textwert);
  }

  /**
   * Fügt der Select-Box eine Option hinzu
   * @param string $text Text der Option
   * @param string $wert Wert der Option
   */
  public function dazu($text, $wert) {
    $this->optionen[] = new Option ($text, $wert);
  }

  /**
   * Gibt die Selectbox aus
   * @return string Code der Selectbox
   */
  public function __toString() : string {
    $code   = "<select id=\"$this->getId()\" name=\"$this->getId()\" class=\"dshUiEingabefeld dshUiSelectfeld $this->klasse\"{$this->aktion->ausgabe()}>";
    foreach ($this->optionen as $opt) {
      $code .= $opt->ausgabe($this->wert);
    }
    $code .= "</select>";
    return $code;
  }
}
?>

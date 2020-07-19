<?php
namespace UI\Elemente;

/**
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
   * @param   string $id ID des Eingabeelements
   */
  public function __construct($id) {
    parent::__construct();
    $this->id = $id;
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
    $this->addKlasse("dshUiEingabefeld");
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

    $self = clone $this;

    $self->aktionen->addFunktionPrioritaet("onchange", 3, "ui.datumsanzeige.checkUhrzeit('{$self->id}', $sekunden)");
    $self->aktionen->addFunktionPrioritaet("onkeyup",  3, "ui.datumsanzeige.checkUhrzeit('{$self->id}', $sekunden)");

    $code    = "<{$self->codeAuf(false, "id", "value", "class")} id=\"{$self->id}Std\" value=\"{$uhrzeit[0]}\" class=\"dshUiEingabefeld dshUiUhrzeitfeldStd".join(" ", array_merge(array(""), $self->klassen))."\">{$self->codeZu()}";
    $code   .= " : ";
    $code   .= "<{$self->codeAuf(false, "id", "value", "class")} id=\"{$self->id}Min\" value=\"{$uhrzeit[1]}\" class=\"dshUiEingabefeld dshUiUhrzeitfeldMin".join(" ", array_merge(array(""), $self->klassen))."\">{$self->codeZu()}";

    if ($self->zeigeSekunden) {
      $code .= " : ";
      $code .= "<{$self->codeAuf(false, "id", "value", "class")} id=\"{$self->id}Sek\" value=\"$uhrzeit[2]\" class=\"dshUiEingabefeld dshUiUhrzeitfeldSek".join(" ", array_merge(array(""), $self->klassen))."\">{$self->codeZu()}";
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

    $self = clone $this;

    $self->aktionen->addFunktionPrioritaet("onfocus",  3, "ui.datumsanzeige.tageswahl.generieren('{$self->id}', true)");
    $self->aktionen->addFunktionPrioritaet("onblur",   3, "ui.datumsanzeige.tageswahl.generieren('{$self->id}', false)");
    $self->aktionen->addFunktionPrioritaet("onchange", 3, "ui.datumsanzeige.checkTag('{$self->id}')");
    $self->aktionen->addFunktionPrioritaet("onkeyup",  3, "ui.datumsanzeige.checkTag('{$self->id}')");

    $code  = "<{$self->codeAuf(false, "id", "value", "class")} id=\"{$self->id}T\" value=\"{$datum[0]}\" class=\"dshUiEingabefeld dshUiDatumfeldT".join(" ", array_merge(array(""), $self->klassen))."\">{$self->codeZu()} ";
    $code .= "<{$self->codeAuf(false, "id", "value", "class")} id=\"{$self->id}M\" value=\"{$datum[1]}\" class=\"dshUiEingabefeld dshUiDatumfeldM".join(" ", array_merge(array(""), $self->klassen))."\">{$self->codeZu()} ";
    $code .= "<{$self->codeAuf(false, "id", "value", "class")} id=\"{$self->id}J\" value=\"{$datum[2]}\" class=\"dshUiEingabefeld dshUiDatumfeldJ".join(" ", array_merge(array(""), $self->klassen))."\">{$self->codeZu()} ";
    $code .= "<div class=\"dshUiDatumwahl\" id=\"{$self->id}Datumwahl\"></div>";

    return $code;
  }
}

class Schieber extends Eingabe {
  protected $tag = "span";

  public function __toString() : string {
    $wert = "0";
    $self = clone $this;
    if($self->wert === 1) {
      $wert = "1";
    }

    $self->aktionen->addFunktionPrioritaet("onclick", 3, "ui.schieber.aktion('{$self->id}')");

    $code  = "<{$self->codeAuf(false, "id", "value", "class")} id=\"{$self->id}Schieber\" class=\"dshUiSchieberAussen dshUiSchieber$wert".join(" ", array_merge(array(""), $self->klassen))."\"><span class=\"dshUiSchieber\"></span>{$self->codeZu()}";
    $code .= "<input type=\"hidden\" id=\"{$self->id}\" value=\"$wert\">";

    return $code;
  }
}

class Toggle extends Schieber {
  /** @var string $text :) */
  protected $text;

  /**
   * Erstellt einen neuen Toggle-Knopf
   * @param string $id   :)
   * @param string $text :)
   */
  public function __construct($id, $text) {
    parent::__construct();
    $this->id = $id;
    $this->text = $text;
  }

  /**
   * Gibt den HTML-Code eines Toggle-Knopfes aus
   * @return string [description]
   */
  public function __toString() : string {
    $self = clone $this;
    $self->addKlasse("dshUiToggle");
    $wert = "0";
    if($self->wert === 1) {
      $wert = "1";
      $self->addKlasse("dshUiToggled");
    }

    $self->aktionen->addFunktionPrioritaet("onclick", 3, "ui.toggle.aktion('{$this->id}')");

    $code  = "<{$self->codeAuf(false, "id", "value")} id=\"{$self->id}Toggle\">$self->text{$this->codeZu()}";

    $code .= "<input type=\"hidden\" id=\"{$self->id}\" value=\"$wert\">";

    return $code;
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
  }

  /**
   * Gibt den HTML-Code eines Toggle-Knopfes aus
   * @return string [description]
   */
  public function __toString() : string {
    $self = clone $this;
    $self->addKlasse("dshUiToggleIcon");
    $wert = "0";
    if($self->wert === 1) {
      $wert = "1";
      $self->addKlasse("dshUiToggled");
    }

    $self->aktionen->addFunktionPrioritaet("onclick", 3, "ui.toggle.aktion('{$this->id}')");

    $code  = "<{$self->codeAuf(false, "id", "value")} id=\"{$self->id}Toggle\">$self->icon $self->text{$this->codeZu()}";

    $code .= "<input type=\"hidden\" id=\"{$self->id}\" value=\"$wert\">";

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
  }

  /**
   * Gibt den HTML-Code eines Toggle-Knopfes aus
   * @return string [description]
   */
  public function __toString() : string {
    $self = clone $this;
    $self->addKlasse("dshUiToggleGross");
    $wert = "0";
    if($self->wert === 1) {
      $wert = "1";
      $self->addKlasse("dshUiToggled");
    }

    $self->aktionen->addFunktionPrioritaet("onclick", 3, "ui.toggle.aktion('{$this->id}')");

    $toggleinhalt = new InhaltElement($this->text);
    $toggleinhalt->setTag("span");
    $toggleinhalt->addKlasse("dshUiToggleGrossText");
    $code  = "<{$self->codeAuf(false, "id", "value")} id=\"{$self->id}Toggle\">$self->icon $toggleinhalt{$this->codeZu()}";
    $code .= "<input type=\"hidden\" id=\"{$self->id}\" value=\"$wert\">";

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
  protected $typ = "color";

  /**
   * Erstellt eine neue Eingabe
   *
   * @param   string $id ID des Eingabeelements
   */
  public function __construct($id) {
    parent::__construct($id);
    $this->addKlasse("dshUiFarbfeld");
  }
}

class Passwortfeld extends Textfeld {
  protected $typ = "password";
  /** @var Textfeld $bezug Textfeld, mit dem das Passwort verglichen werden soll */
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
  }

  /**
   * Setzt das Bezugsfeld des Eingabefelds
   * @param   Textfeld $bezug :)
   * @return  self
   */
  public function setBezugsfeld($bezug) : self {
    $this->bezug = $bezug;
    return $this;
  }

  /**
   * Gibt das Bezugsfeld des Eingabefelds zurück
   * @return  Textfeld
   */
  public function getBezugsfeld() : Textfeld {
    return $this->bezug;
  }

  public function __toString() : string {
    $self = clone $this;
    if($self->bezug !== null) {
      $self->aktionen->addFunktionPrioritaet("onchange", 3, "ui.passwort.aktion('{$self->bezug->id}', \"{$self->id}')");
    }

    return "{$self->codeAuf()}{$self->codeZu()}";
  }
}

class Mailfeld extends Textfeld {
  protected $typ = "text";

  public function __toString() : string {
    $self = clone $this;
    $self->aktionen->addFunktionPrioritaet("onchange", 3,  "ui.mail.aktion('{$self->id}')");

    return "{$self->codeAuf()}{$self->codeZu()}";
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
  public function __construct($id) {
    parent::__construct($id);
    $this->gesetzt = false;
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
    }
    else {$wahl = "";}
    return "<{$this->codeAuf(false)}$wahl>{$this->$text}{$this->codeZu()}";
  }
}

class ToggleOption extends Option {
  protected $tag = "span";

  public function __construct($id) {
    parent::__construct($id);
    $this->addKlasse("dshUiToggle");
  }

  /**
   * Gibt den Code der Option zurück
   * @return string       Code der Option
   */
  public function __toString() : string {
    $self = clone $this;
    if ($this->gesetzt) {
      $self->addKlasse("dshUiToggled");
    }
    return "{$self->codeAuf()}{$self->text}{$self->codeZu()}";
  }
}

class Togglegruppe extends Eingabe {
  /** @var ToggleOption[] Optionen der Togglebuttons */
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
  }

  /**
   * Fügt der Togglegruppe-Box eine Option hinzu
   * @param ToggleOption $option :)
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
    $code   = "<span class=\"dshUiTogglegruppe\">";
    $knopfId = 0;
    $anzahl = count($this->optionen);
    $self = clone $this;

    for ($i=0; $i<$anzahl; $i++) {
      $self = clone $this->optionen[$i];
      if ($self->getWert() == $this->getWert()) {
        $self->setGesetzt(true);
        $knopfId = $i;
      }
      else {
        $self->setGesetzt(false);
      }
      $self->setID("{$self->id}Knopf$i");
      $self->getAktionen()->addFunktionPrioritaet("onclick", 3, "ui.toggle.aktion('$self->id', '$i', '$anzahl', '{$self->getWert()}')");

      $code .= $self." ";
    }

    if ($anzahl > 0) {$code = substr($code, 0, strlen($code)-1);}

    $code .= "<input type=\"hidden\" id=\"$this->id\" name=\"$this->id\" value=\"{$this->optionen[$knopfId]->getWert()}\">";
    $code .= "<input type=\"hidden\" id=\"{$this->id}KnopfId\" name=\"{$this->id}KnopfId\" value=\"$knopfId\">";
    $code .= "</span>";
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
  public function add($text, $wert) {
    $this->optionen[] = new Option ($text, $wert);
  }

  /**
   * Gibt die Selectbox aus
   * @return string Code der Selectbox
   */
  public function __toString() : string {
    $code   = "<select id=\"$this->id\" name=\"$this->id\" class=\"dshUiEingabefeld dshUiSelectfeld $this->klasse\"{$this->aktion->ausgabe()}>";
    foreach ($this->optionen as $opt) {
      $code .= $opt->ausgabe($this->wert);
    }
    $code .= "</select>";
    return $code;
  }
}
?>

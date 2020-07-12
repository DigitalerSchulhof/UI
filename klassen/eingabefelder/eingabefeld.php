<?php
namespace UI;
use UI\Aktion;

abstract class Eingabe {
  /** @var string ID des Eingabefelds */
  protected $id;
  /** @var string Typ des Eingabefelds */
  protected $wert;
  /** @var string Extraklassen des Eingabefelds */
  protected $klasse;
  /** @var Aktion Aktion des Eingabefelds */
  protected $aktion;

  /**
   * Erstellt eine neue Eingabe
   * @param string $id          ID des Eingabefelds
   * @param string $wert        Standardwert des Eingabefelds
   * @param string $klasse      Extraklassen des Eingabefelds
   * @param Aktion $aktion      Aktion des Eingabefeld
   */
  public function __construct($id, $wert = "", $klasse = "", $aktion = null) {
    $this->id           = $id;
    $this->wert         = $wert;
    $this->klasse       = $klasse;
    if($aktion === null) {
      $aktion = new Aktion();
    }
    $this->aktion       = $aktion;
  }

  /**
   * Gibt den HTML-Code zurück
   * @return string Der HTML-Code
   */
  abstract public function ausgabe() : string;

  /**
   * Setzt den Wert des Eingabefelds
   * @param string $wert :)
   * @return Eingabe
   */
  public function setWert($wert) : Eingabe {
    $this->wert;
    return $this;
  }

  /**
  * Gibt die ID des Eingabefelds aus
  * @return string ID des Eingabefelds
  */
  public function getId() : string {
    return $this->id;
  }
}

class Uhrzeitfeld extends Eingabe {
    /**
     * Gibt den HTML-Code zurück
     * @param  boolean $sekunden Wahr wenn Sekunden angezeigt werden sollen
     * @return string Der HTML-Code
     */
    public function ausgabe($sekunden = false) : string {
      $uhrzeit = explode(":", $this->wert);
      if ((count($uhrzeit) != 3) && (count($uhrzeit) != 2)) {
        $datum[0] = date("H");
        $datum[1] = date("i");
        if (count($datum) == 2) {
          $datum[2] = date("s");
        }
      }

      $this->aktion->dazu("onchange", "ui.datumsanzeige.checkUhrzeit('$this->id', $sekunde)", true);
      $this->aktion->dazu("onkeyup",  "ui.datumsanzeige.checkUhrzeit('$this->id', $sekunde)", true);

      $code   = "<input type=\"text\" id=\"{$this->id}S\" name=\"{$this->id}Std\" value=\"{$datum[0]}\" class=\"dshUiEingabefeld dshUiUhrzeitfeldStd $this->klasse\"{$this->aktion->ausgabe()}> ";
      $code .= " <input type=\"text\" id=\"{$this->id}Min\" name=\"{$this->id}Min\" value=\"{$datum[1]}\" class=\"dshUiEingabefeld dshUiUhrzeitfeldMin $this->klasse\"{$this->aktion->ausgabe()}>";

      if ($sekunde) {
        $code .= " <input type=\"text\" id=\"$this->id"."Sek\" name=\"$this->id"."Sek\" value=\"".$datum[1]."\" class=\"dshUiUhrzeitfeldSek $this->klasse\"".$aktion->ausgabe().">";
      }
      return $code;
    }
  }
class Datumfeld extends Eingabe {
    public function ausgabe() : string {
      $datum = explode(".", $this->wert);
      if (count($datum) != 3) {
        $datum[0] = date("d");
        $datum[1] = date("m");
        $datum[2] = date("Y");
      }

      $this->aktion->dazu("onfocus", "ui.datumsanzeige.tageswahl.generieren('$this->id', true)", true);
      $this->aktion->dazu("onblur", "ui.datumsanzeige.tageswahl.generieren('$this->id', false)", true);
      $this->aktion->dazu("onchange", "ui.datumsanzeige.checkTag('$this->id')", true);
      $this->aktion->dazu("onkeyup", "ui.datumsanzeige.checkTag('$this->id')", true);

      $code = "<input type=\"text\" id=\"$this->id"."T\" name=\"$this->id"."T\" value=\"".$datum[0]."\" class=\"dshUiEingabefeld dshUiDatumfeldT $this->klasse\"".$this->aktion->ausgabe()."> ";
      $code .= "<input type=\"text\" id=\"$this->id"."M\" name=\"$this->id"."M\" value=\"".$datum[1]."\" class=\"dshUiEingabefeld dshUiDatumfeldM $this->klasse\"".$this->aktion->ausgabe()."> ";
      $code .= "<input type=\"text\" id=\"$this->id"."J\" name=\"$this->id"."J\" value=\"".$datum[2]."\" class=\"dshUiEingabefeld dshUiDatumfeldJ $this->klasse\"".$this->aktion->ausgabe()."> ";
      $code .= "<div class=\"dshUiDatumwahl\" id=\"$this->id"."Datumwahl\"></div>";
      return $code;
    }
  }
class Farbfeld extends Eingabe {
    public function ausgabe() : string {
      return "<input placeholder=\"$this->platzhalter\" type=\"color\" id=\"$this->id\" name=\"$this->id\" value=\"$this->wert\" class=\"dshUiEingabefeld dshUiFarbfeld $this->klasse{$this->aktion->ausgabe()}\">";
    }
  }
class Schieber extends Eingabe {
    public function ausgabe() : string {
      $schieberwert = 0;

      $this->aktion->dazu("onclick", "ui.schieber.aktion('$this->id')", true);

      if ($this->wert == 1) {$schieberwert = 1;}
      $code = "<span class=\"dshUiSchieberAussen $this->klasse\"".$this->aktion->ausgabe()."><span class=\"dshUiSchieber dshUiSchieberInnen$schieberwert\" id=\"$this->id"."Schieber\"></span></span>";
      return "<input type=\"hidden\" id=\"$this->id\" name=\"$this->id\" value=\"$schieberwert\">";
    }
  }
class Textfeld extends Eingabe {
    /** @var string Platzhalter des Eingabefelds */
    protected $platzhalter;

    /**
     * Erstellt eine neue Eingabe
     * @param string $id          ID des Eingabefelds
     * @param string $wert        Standardwert des Eingabefelds
     * @param string $klasse      Extraklassen des Eingabefelds
     * @param string $platzhalter Platzhalter des Eingabefelds
     * @param Aktion $aktion      Aktion des Eingabefeld
     */
    public function __construct($id, $wert = "", $klasse = "", $platzhalter = "", $aktion = null) {
      parent::__construct($id, $wert, $klasse, $aktion);
      $this->platzhalter = $platzhalter;
    }

    public function ausgabe() : string {
      return "<input placeholder=\"$this->platzhalter\" type=\"text\" id=\"$this->id\" name=\"$this->id\" value=\"$this->wert\" class=\"dshUiEingabefeld $this->klasse\"{$this->aktion->ausgabe()}>";
    }
  }

class Passwortfeld extends Textfeld {
     /**
      * Gibt den HTML-Code zurück
      * @param Passwortfeld $bezugsfeld Eingabefeld, mit dem verglichen wird
      * @return string Der HTML-Code
      */
    public function ausgabe($bezugsfeld = null) : string {
      $pruefen =  "";
      $zusatz = "";
      if ($bezugsfeld !== null) {
        $this->aktion->dazu("onchange", "ui.passwort.aktion('".$bezugsfeld->getId()."', '$this->id')");
      }
      return "<input placeholder=\"$this->platzhalter\" type=\"password\" id=\"$this->id\" name=\"$this->id\" value=\"$this->wert\" class=\"dshUiEingabefeld $this->klasse\"$pruefen{$this->aktion->ausgabe()}>$zusatz";
    }
  }
class Mailfeld extends Textfeld {
    public function ausgabe() : string {
      $this->aktion->dazu("onchange", "ui.mail.aktion('$this->id')");
      return "<input placeholder=\"$this->platzhalter\" type=\"password\" id=\"$this->id\" name=\"$this->id\" value=\"$this->wert\" class=\"dshUiEingabefeld $this->klasse\" ".$this->aktion->ausgabe()."></td><td><span id=\"".$this->id."Pruefen\" class=\"dshUiPruefen0\"></span>";
    }
  }
class Textarea extends Textfeld {
    public function ausgabe() : string {
      return "<textarea placeholder=\"$this->platzhalter\" id=\"$this->id\" name=\"$this->id\" class=\"dshUiEingabefeld dshUiTextbereich $this->klasse\"{$this->aktion->ausgabe()}>$this->wert</textarea>";
    }
  }

class Toggle extends Eingabe {
  /** @var string[] Enthält den Text der Toggles */
  private $text;

	/**
	* @param string $id
	* @param string[] $text
	* @param Aktion $event
	* @param string $wert
	* @param string $klasse
	*/
  public function __construct($id, $text, $event, $wert = 0, $klasse = "") {
    if ($wert >= count($text)) {$wert = 0;}
    parent::__construct($id, $wert, $klasse, $event);
    $this->text = $text;
  }

  public function ausgabe() : string {
    $code = "";
    if ($this->wert >= count($text)) {$this->wert = 0;}
    $anzahl = count($text);
    for ($i=0; $i<$anzahl; $i++) {
      $toggled = "";
      if ($this->wert == $i) {
        $toggled = " dshKnopfToggled";
      }
      $this->aktion.dazu("onclick", "ui.toggle.aktion('$this->id', '$i', '$anzahl')", true);
      $code .= "<span id=\"$this->id"."$i\"class=\"dshUiKnopf$toggled $this->klasse\"".$this->event->ausgabe()."\">$this->$text</span> ";
    }

    return $code."<input type=\"hidden\" id=\"$this->id\" name=\"$this->id\" value=\"$this->wert\">";
  }
}
class Zahlfeld extends Eingabe {
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
  public function __construct($id, $max = null, $min = 0, $schritt = 1, $wert = "", $klasse = "") {
    parent::__construct($id, $wert, $klasse);
    $this->min = $min;
    $this->max = $max;
    $this->schritt = $schritt;
  }

  public function ausgabe() : string {
    $code = "<input type=\"number\" id=\"$this->id\" name=\"$this->id\"";
    $code .= " value=\"$this->wert\" class=\"dsh_eingabefeld $this->klasse\"";
    if ($min != null) {$code .= " min=\"$this->min\"";}
    if ($max != null) {$code .= " max=\"$this->max\"";}
    if ($schritt != null) {$code .= " step=\"$this->schritt\"";}
    return $code.">";
  }
}
?>
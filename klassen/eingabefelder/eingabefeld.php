<?php
namespace UI;

/**
*Eingabefelder erstellen
*/
class Eingabefeld {
  /** @var string Enthält die Id des Eingabefeldes */
  protected $id;
  /** @var string Enthält den Typ des Eingabefeldes */
  protected $wert;
  /** @var string Enthält die CSS-Klasse des Eingabefeldes */
  protected $klasse;

	/**
	* @param string $id
	* @param string $wert
	* @param string $klasse
	*/
  public function __construct($id, $wert="", $klasse="") {
    $this->id = $id;
    $this->wert = $wert;
    $this->klasse = $klasse;
  }

  /**
	* Gibt das Eingabefeld als Textfeld aus
	* @return string HTML-Code für ein Eingabefeld
	*/
  public function textfeld () : string {
    return "<input type=\"text\" id=\"$this->id\" name=\"$this->id\" value=\"$this->wert\" class=\"dshUiEingabefeld $this->klasse\">";
  }

  /**
	* Gibt das Eingabefeld als Passwort aus
  * @param Eingabefeld $bezugsfeld gibt das Eingabefeld an, mit demselben Inhalt an
	* @return string HTML-Code für ein Eingabefeld
	*/
  public function passwortfeld ($bezugsfeld = null) : string {
    $pruefen =  "";
    $zusatz = "";
    if ($bezugsfeld !== null) {
      $pruefen = " onchange=\"dshUiCheckPasswortFeld('".$bezugsfeld->getId()."', '$this->id')\"";
      $zusatz = "</td><td><span id=\"".$this->id."Pruefen\" class=\"dshUiPruefen0\"></span>"
    }
    return "<input type=\"password\" id=\"$this->id\" name=\"$this->id\" value=\"$this->wert\" class=\"dshUiEingabefeld $this->klasse\"$pruefen>$zusatz";
  }

  /**
	* Gibt das Eingabefeld als Mailfeld aus
	* @return string HTML-Code für ein Eingabefeld
	*/
  public function mailfeld () : string {
    return "<input type=\"password\" id=\"$this->id\" name=\"$this->id\" value=\"$this->wert\" class=\"dshUiEingabefeld $this->klasse\" onchange=\"dshUiCheckMailFeld('$this->id')\"></td><td><span id=\"".$this->id."Pruefen\" class=\"dshUiPruefen0\"></span>";
  }

  /**
	* Gibt das Eingabefeld als Farbfeld aus
	* @return string HTML-Code für ein Eingabefeld
	*/
  public function farbfeld () : string {
    return "<input type=\"color\" id=\"$this->id\" name=\"$this->id\" value=\"$this->wert\" class=\"dshUiEingabefeld dshUiFarbfeld $this->klasse\">";
  }

  /**
	* Gibt das Eingabefeld als Datumfeld aus
	* @return string HTML-Code für ein Eingabefeld
	*/
  public function datumfeld () : string {
    $datum = explode(".", $this->wert);
    if (count($datum) != 3) {
      $datum[0] = date("d");
      $datum[1] = date("m");
      $datum[2] = date("Y");
    }

    $code = "<input type=\"text\" id=\"$this->id"."T\" name=\"$this->id"."T"\" value=\"".$datum[0]."\" class=\"dshUiEingabefeld dshUiDatumfeldT $this->klasse\" onfocus=\"dshUiDatumsanzeige('$this->id', true)\" onblur=\"dshUiDatumsanzeige('$this->id', false)\" onchange=\"dshUiCheckDatumFeld('$this->id')\" onkeyup=\"dshUiCheckDatumFeld('$this->id')\"> ";
    $code .= "<input type=\"text\" id=\"$this->id"."M\" name=\"$this->id"."M\" value=\"".$datum[1]."\" class=\"dshUiEingabefeld dshUiDatumfeldM $this->klasse\" onfocus=\"dshUiDatumsanzeige('$this->id', true)\" onblur=\"dshUiDatumsanzeige('$this->id', false)\" onchange=\"dshUiCheckDatumFeld('$this->id')\" onkeyup=\"dshUiCheckDatumFeld('$this->id')\"> ";
    $code .= "<input type=\"text\" id=\"$this->id"."J\" name=\"$this->id"."J\" value=\"".$datum[2]."\" class=\"dshUiEingabefeld dshUiDatumfeldJ $this->klasse\" onfocus=\"dshUiDatumsanzeige('$this->id', true)\" onblur=\"dshUiDatumsanzeige('$this->id', false)\" onchange=\"dshUiCheckDatumFeld('$this->id')\" onkeyup=\"dshUiCheckDatumFeld('$this->id')\"> ";
    $code .= "<div class=\"dshUiDatumwahl\" id=\"$this->id"."Datumwahl\"></div>";
    return $code;
  }

  /**
	* Gibt das Eingabefeld als Uhrzeitfeld aus
	* @param boolean $sekunde soll ein Feld für Sekunden angegeben werden?
	* @return string HTML-Code für ein Eingabefeld
	*/
  public function uhrzeitfeld ($sekunde = false) : string {
    $uhrzeit = explode(":", $this->wert);
    if ((count($uhrzeit) != 3) && (count($uhrzeit) != 2)) {
      $datum[0] = date("H");
      $datum[1] = date("i");
      if (count($datum) == 2) {
        $datum[2] = date("s");
      }
    }

    $code = "<input type=\"text\" id=\"$this->id"."Std\" name=\"$this->id"."Std"\" value=\"".$datum[0]."\" class=\"dshUiEingabefeld dshUiUhrzeitfeldStd $this->klasse\" onchange=\"dshUiCheckUhrzeitFeld('$this->id', $sekunde)\" onkeyup=\"dshUiCheckUhrzeitFeld('$this->id', $sekunde)\"> ";
    $code .= "<input type=\"text\" id=\"$this->id"."Min\" name=\"$this->id"."Min\" value=\"".$datum[1]."\" class=\"dshUiEingabefeld dshUiUhrzeitfeldMin $this->klasse\" onchange=\"dshUiCheckUhrzeitFeld('$this->id', $sekunde)\" onkeyup=\"dshUiCheckUhrzeitFeld('$this->id', $sekunde)\">";
    if ($sekunde) {
      $code .= " <input type=\"text\" id=\"$this->id"."Sek\" name=\"$this->id"."Sek\" value=\"".$datum[1]."\" class=\"dshUiUhrzeitfeldSek $this->klasse\" onchange=\"dshUiCheckUhrzeitFeld('$this->id', $sekunde)\" onkeyup=\"dshUiCheckUhrzeitFeld('$this->id', $sekunde)\">";
    }
    return $code;
  }

  /**
	* Gibt das Textbereich als Uhrzeitfeld aus
	* @return string HTML-Code für ein Eingabefeld
	*/
  public function textbereich () : string {
    return "<textarea id=\"$this->id\" name=\"$this->id\" class=\"dshUiEingabefeld dshUiTextbereich $this->klasse\">$this->wert</textarea>";
  }

  /**
	* Gibt das Eingabefeld als Schieber aus
	* @return string HTML-Code für ein Eingabefeld
	*/
  public function schieber () : string {
    $schieberwert = 0;
    if ($this->wert == 1) {$schieberwert = 1;}
    $code = "<span class=\"dshUiSchieberAussen $this->klasse\" onclick=\"dshUiSchieber('$this->id')\"><span class=\"dshUiSchieber dshUiSchieberInnen$schieberwert\" id=\"$this->id"."Schieber\"></span></span>";
    return "<input type=\"hidden\" id=\"$this->id\" name=\"$this->id\" value=\"$schieberwert\">";
  }

  /**
	* Gibt die ID des Eingabefeldes aus
	* @return string ID des Eingabefeldes
	*/
  public function getId() : string {
    return $this->id;
  }
}
?>

<?php
namespace UI\Fenster;

/**
*Eingabefelder erstellen
*/
class Fenster {
  /** @var string Enthält die Id des Eingabefeldes */
  protected $breite;
  /** @var string Enthält den Typ des Eingabefeldes */
  protected $titel;
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
	* @return string HTML-Code für ein Eingabefeld
	*/
  public function passwortfeld () : string {
    return "<input type=\"password\" id=\"$this->id\" name=\"$this->id\" value=\"$this->wert\" class=\"dshUiEingabefeld $this->klasse\">";
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

    $code = "<input type=\"text\" id=\"$this->id"."T\" name=\"$this->id"."T"\" value=\"".$datum[0]."\" class=\"dshUiEingabefeld dshUiDatumfeldT $this->klasse\" onfocus=\"dshUiDatumsanzeige('$this->id', true)\" onblur=\"dshUiDatumsanzeige('$this->id', false)\"> ";
    $code .= "<input type=\"text\" id=\"$this->id"."M\" name=\"$this->id"."M\" value=\"".$datum[1]."\" class=\"dshUiEingabefeld dshUiDatumfeldM $this->klasse\" onfocus=\"dshUiDatumsanzeige('$this->id', true)\" onblur=\"dshUiDatumsanzeige('$this->id', false)\"> ";
    $code .= "<input type=\"text\" id=\"$this->id"."J\" name=\"$this->id"."J\" value=\"".$datum[2]."\" class=\"dshUiEingabefeld dshUiDatumfeldJ $this->klasse\" onfocus=\"dshUiDatumsanzeige('$this->id', true)\" onblur=\"dshUiDatumsanzeige('$this->id', false)\"> ";
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

    $code = "<input type=\"text\" id=\"$this->id"."Std\" name=\"$this->id"."Std"\" value=\"".$datum[0]."\" class=\"dshUiEingabefeld dshUiUhrzeitfeldStd $this->klasse\"> ";
    $code .= "<input type=\"text\" id=\"$this->id"."Min\" name=\"$this->id"."Min\" value=\"".$datum[1]."\" class=\"dshUiEingabefeld dshUiUhrzeitfeldMin $this->klasse\">";
    if ($sekunde) {
      $code .= " <input type=\"text\" id=\"$this->id"."Sek\" name=\"$this->id"."Sek\" value=\"".$datum[1]."\" class=\"dshUiUhrzeitfeldSek $this->klasse\">";
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
}
?>

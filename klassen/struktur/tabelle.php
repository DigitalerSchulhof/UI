<?php
namespace UI;

class Tabelle extends Element {
  protected $tag = "table";

  /** @var string[] $titel Titel der Tabellenspalten */
  protected $titel;

  /** @var string[][] $zellen Zellen der Tabelle als assoziatives Array */
  protected $zellen;

  /** @var string $sortierung Spalte nach der sortiert werden soll */
  protected $sortierung;

  public function __construct($id, $titel, ...$zeilen) {
    parent::__construct();
    $this->id = $id;
    $this->titel = $titel;
    $this->zellen = [];
    foreach ($zeilen as $z) {
      if ($this->pruefeZeile($z)) {
        $this->zellen[] = $z;
      }
    }
    $this->addKlasse("dshUiTabelle");
    $this->addKlasse("dshUiTabelleListe");
  }

  /**
   * Prüft, ob die eingegebene Zeile nur Spalten hat, die auch existieren
   * @param  string[] $zeile zu prüfende Zeile
   * @return bool            :)
   */
  private function pruefeZeile($zeile) : bool {
    if (!is_array($zeile)) {
      return false;
    }
    foreach ($zeile as $k => $z) {
      if (!in_array($k, $this->titel)) {
        return false;
      }
    }
    return true;
  }

  /**
   * Fügt Zeilen in die Tabelle hinzu
   * @param  string[] $zellen Zeilen der Tabelle
   * @return self             :)
   */
  public function addZeile(...$zellen) : self {
    foreach ($zellen as $z) {
      if ($this->pruefeZeile($z)) {
        $this->zellen[] = $z;
      }
    }
    return $this;
  }

  /**
   * Gibt die Tabelle in HTML-Code zurück
   * @return string :)
   */
  public function __toString() : string {
    $code  = $this->codeAuf();
    $spaltennr = 0;
    $code .= "<thead id=\"{$this->id}Kopf\"><tr>";
    $anzzeilen = count($this->zellen);
    $anzspalten = count($this->titel);
    foreach ($this->titel as $t) {
      if ($anzzeilen > 0) {
        $aufsteigend = new Sortierknopf("ASC", $this->id, $spaltennr);
        $absteigend = new Sortierknopf("DESC", $this->id, $spaltennr);
        $code .= "<th>$t{$aufsteigend}{$absteigend}</th>";
      } else {
        $code .= "<th>$t</th>";
      }
      $spaltennr++;
    }
    $code .= "</tr></thead><tbody id=\"{$this->id}Koerper\">";
    $zeilenr = 0;
    foreach($this->zellen as $z) {
      $code .= "<tr>";
      $spaltennr = 0;
      foreach ($this->titel as $t) {
        $code .= "<td id=\"{$this->id}Z{$zeilenr}S$spaltennr\">{$z[$t]}</td>";
        $spaltennr++;
      }
      $code .= "</tr>";
      $zeilenr ++;
    }
    if ($anzzeilen == 0) {
      $code .= "<tr><td colspan=\"$anzspalten\" class=\"dshUiNotiz\">-- keine Datensätze --</td></tr>";
    }
    $code .= "</tbody>{$this->codeZu()}";
    $code .= new VerstecktesFeld("{$this->id}ZAnzahl", $zeilenr);
    $code .= new VerstecktesFeld("{$this->id}SAnzahl", $spaltennr);
    return $code;
  }
}

class FormularFeld {
  /** @var InhaltElement Bezeichnung des Eingabefeldes */
  private $label;
  /** @var Eingabe */
  private $eingabe;
  /** @var bool true, wenn Feld Optional, false sonst*/
  private $optional;

  /**
   * Erstellt ein neues FormularFeld
   * @param InhaltElement $label   :)
   * @param Eingabe       $eingabe :)
   */
  public function __construct($label, $eingabe) {
    $this->label = $label;
    $this->label->setTag("label");
    $this->eingabe = $eingabe;
    $this->optional = false;
    if ($eingabe->getID() === null) {
      throw new \Exception("Keine ID übergeben");
    }
  }

  /**
   * Setzt den Optionalwert auf den übergebenen Wert
   * @param  bool $optional true, wenn das Feld Optional ist, false sonst
   * @return self           :)
   */
  public function setOptional($optional) : self {
    $this->optional = $optional;
    return $this;
  }

  /**
   * Gibt das FormularFeld als HTML-Code zurück
   * @return string :)
   */
  public function __toString() : string {
    if ($this->eingabe->getID() === null) {
      throw new \Exception("Keine ID übergeben");
    }
    $this->label->setAttribut("for", $this->eingabe->getID());
    $code = "<tr><th>{$this->label}</th>";
    $this->eingabe->setKlasse($this->optional, "dshUiEingabefeldOptional");
    $code .= "<td>{$this->eingabe}";
    if ($this->optional) {
      $code .= new Notiz("Optional");
    }
    $code .= "</td></tr>";
    return $code;
  }
}

class FormularTabelle extends Element implements \ArrayAccess{
  protected $tag = "form";

  /** @var FormularFeld[] $zeilen :) */
  protected $zeilen;

  /** @var Knopf[] $knoepfe :) */
  protected $knoepfe;

  public function __construct(...$zeilen) {
    parent::__construct();
    $this->zeilen = $zeilen;
    $this->addKlasse("dshUiFormular");
    $this->aktionen->setFunktion("onsubmit", -1, "return false");
    $this->knoepfe = [];
  }

  /**
   * Fügt dem Formular neue Knöpfe hinzu
   * @param  Knopf[] $knopf [description]
   * @return self          [description]
   */
  public function addKnopf(...$knopf) : self {
    foreach ($knopf as $k) {
      $this->knoepfe[] = $k;
    }
    return $this;
  }

  /**
   * Fügt Zeilen in die Tabelle hinzu
   * @param  string[] $zellen Zeilen der Tabelle
   * @return self             :)
   */
  public function addZeile(...$zeilen) : self {
    foreach ($zeilen as $z) {
      $this->zeilen[] = $z;
    }
    return $this;
  }

  /**
   * Kurz für <code>$this->getAktionen()->addFunktion("submit", $submit);</code>
   * @param  string $submit :)
   * @return self
   */
  public function addSubmit($submit) : self {
    $this->aktionen->addFunktionPrioritaet("onsubmit", 0, "try{{$submit}}catch(_){console.error(_)}");
    return $this;
  }

  /**
   * Gibt die Tabelle in HTML-Code zurück
   * @return string :)
   */
  public function __toString() : string {
    $code  = $this->codeAuf();
    $code .= "<table class=\"dshUiTabelle dshUiTabelleFormular\"><tbody>";
    foreach($this->zeilen as $z) {
      $code .= $z;
    }
    if (count($this->knoepfe) > 0) {
      $code .= "<tr class=\"dshUiTabelleFormularKnoepfe\"><td colspan=\"2\">";
      foreach ($this->knoepfe as $k) {
        $code .= $k." ";
      }
      $code .= "</td></tr>";
    }
    $code .= "</tbody></table>";
    $code .= (new Icon(Konstanten::AUSFUELLEN))->addKlasse("dshUiFormularAusfuellen");
    $code .= $this->codeZu();
    return $code;
  }

  /*
   * ArrayAccess Methoden
   */

  public function offsetSet($o, $v) {
    if(!($v instanceof \UI\FormularFeld) && !($v instanceof \UI\Knopf)) {
      throw new \TypeError("Der übergebene Wert ist kein FormularFeld und kein Knopf");
    }
    if(!is_int($o) && !is_null($o)) {
      throw new \TypeError("Der übergebene Offset ist keine Ganzzahl und nicht null");
    }
    if($v instanceof \UI\FormularFeld) {
      if(is_null($o)) {
        $this->zeilen[]   = $v;
      } else {
        $this->zeilen[$o] = $v;
      }
    } else if($v instanceof \UI\Knopf) {
      if(is_null($o)) {
        $this->knoepfe[]   = $v;
      } else {
        $this->knoepfe[$o] = $v;
      }
    }
  }

  public function offsetExists($o) {
    throw new \Exception("Nicht implementiert! Spezifische Methoden nutzen.");
  }

  public function offsetUnset($o) {
    throw new \Exception("Nicht implementiert! Spezifische Methoden nutzen.");
  }

  public function offsetGet($o) {
    throw new \Exception("Nicht implementiert! Spezifische Methoden nutzen.");
  }
}
?>

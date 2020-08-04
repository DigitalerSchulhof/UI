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

  public function __construct($id, $titel, ...$zellen) {
    parent::__construct();
    $this->id = $id;
    $this->titel = $titel;
    $this->zellen = [];
    foreach ($zellen as $z) {
      if ($this->pruefeZeile($z)) {
        $this->zellen[] = $z;
      }
    }
    $this->addKlasse("dshUiTabelle");
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
    $self = clone $this;
    $self->addKlasse("dshUiTabelleListe");
    $code  = $self->codeAuf();
    $spaltennr = 0;
    $code .= "<thead id=\"{$this->id}Kopf\"><tr>";
    foreach ($self->titel as $t) {
      $aufsteigend = new Sortierknopf("ASC", $self->id, $spaltennr);
      $absteigend = new Sortierknopf("DESC", $self->id, $spaltennr);
      $code .= "<th>$t{$aufsteigend}{$absteigend}</th>";
      $spaltennr++;
    }
    $code .= "</tr></thead><tbody id=\"{$this->id}Koerper\">";
    $zeilenr = 0;
    foreach($self->zellen as $z) {
      $code .= "<tr>";
      $spaltennr = 0;
      foreach ($self->titel as $t) {
        $code .= "<td id=\"{$this->id}Z{$zeilenr}S$spaltennr\">{$z[$t]}</td>";
        $spaltennr++;
      }
      $code .= "</tr>";
      $zeilenr ++;
    }
    $code .= "</tbody>{$self->codeZu()}";
    $code .= new VerstecktesFeld("{$self->id}ZAnzahl", $zeilenr);
    $code .= new VerstecktesFeld("{$self->id}SAnzahl", $spaltennr);
    return $code;
  }
}

class FormularFeld {
  /** @var InhaltElement Bezeichnung des Eingabefeldes */
  private $label;
  /** @var Eingabe */
  private $eingabe;

  /**
   * Erstellt ein neues FormularFeld
   * @param InhaltElement $label   :)
   * @param Eingabe       $eingabe :)
   */
  public function __construct($label, $eingabe) {
    $this->label = $label;
    $this->label->setTag("label");
    $this->eingabe = $eingabe;
    if ($eingabe->getID() === null) {
      throw new \Exception("Keine ID übergeben");
    }
  }

  /**
   * Gibt das FormularFeld als HTML-Code zurück
   * @return string :)
   */
  public function __toString() : string {
    $self = clone $this;
    if ($self->eingabe->getID() === null) {
      throw new \Exception("Keine ID übergeben");
    }
    $self->label->setAttribut("for", $self->eingabe->getID());
    return "<tr><th>{$self->label}</th><td>{$self->eingabe}</td></tr>";
  }
}

class FormularTabelle extends Element {
  protected $tag = "form";

  /** @var FormularFeld[] $zeilen :) */
  protected $zeilen;

  /** @var Knopf[] $knoepfe :) */
  protected $knoepfe;

  public function __construct(...$zeilen) {
    parent::__construct();
    $this->zeilen = $zeilen;
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
   * Gibt die Tabelle in HTML-Code zurück
   * @return string :)
   */
  public function __toString() : string {
    $self = clone $this;
    $self->addKlasse("dshUiFormular");
    $self->getAktionen()->addFunktionPrioritaet("onsubmit", -1, "return false");
    $code  = $self->codeAuf();
    $code .= "<table class=\"dshUiTabelle dshUiTabelleFormular\"><tbody>";
    foreach($self->zeilen as $z) {
      $code .= $z;
    }
    $code .= "<tr><td colspan=\"2\">";
    foreach ($this->knoepfe as $k) {
      $code .= $k." ";
    }
    $code .= "</td></tr>";
    $code .= "</tbody></table>";
    $code .= (new Icon(Konstanten::AUSFUELLEN))->addKlasse("dshUiFormularAusfuellen");
    $code .= $self->codeZu();
    return $code;
  }
}

?>

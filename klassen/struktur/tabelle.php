<?php
namespace UI;

class Tabelle extends Element {
  protected $tag = "table";

  /** @var string[] $titel Titel der Tabellenspalten */
  protected $titel;

  /** @var string[][] $zellen Zellen der Tabelle als assoziatives Array */
  protected $zellen;

  protected $darstellung;

  const DARSTELLUNGEN = ["Liste", "Formular"];

  /** @var string $sortierung Spalte nach der sortiert werden soll */
  protected $sortierung;

  public function __construct($id, $titel, ...$zellen) {
    parent::__construct();
    $this->id = $id;
    $this->titel = $titel;
    $this->zellen = array();
    foreach ($zellen as $z) {
      if ($this->pruefeZeile($z)) {
        $this->zellen[] = $z;
      }
    }
    $this->darstellung = "Liste";
    $this->addKlasse("dshUiTabelle");
  }

  public function setDarstellung($darstellung) : self {
    if (!in_array($darstellung, self::DARSTELLUNGEN)) {
      $darstellung = self::DARSTELLUNGEN[0];
    }
    $this->darstellung = $darstellung;
    return $this;
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
   * @return string [description]
   */
  public function __toString() : string {
    $self = clone $this;
    $self->addKlasse("dshUiTabelle{$this->darstellung}");
    $code  = $self->codeAuf();
    $zeilenr = 0;
    $code .= "<tr id=\"{$this->id}$zeilenr\">";
    foreach ($self->titel as $t) {
      $aufsteigend = new Sortierknopf("ASC", $self->id, $t);
      $absteigend = new Sortierknopf("DESC", $self->id, $t);
      $code .= "<th>$t{$aufsteigend}{$absteigend}</th>";
    }
    $code .= "</tr>";
    $zeilenr ++;
    foreach($self->zellen as $z) {
      $code .= "<tr id=\"{$this->id}$zeilenr\">";
      foreach ($self->titel as $t) {
        $code .= "<td>{$z[$t]}</th>";
      }
      $code .= "</tr>";
      $zeilenr ++;
    }
    $code .= $self->codeZu();
    return $code;
  }

}
?>

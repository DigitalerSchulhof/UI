<?php
namespace UI;

class Reiterkoerper extends Zeile {
  private $typ = "A1";

  /**
   * Erstellt einen neuen Reiterkörper
   * @param Spalte $element Element, das dem Reiterkörper hinzugefügt werden solls
   */
  public function __construct(...$spalte) {
    parent::__construct(...$spalte);
  }
}

class Reiterkopf extends InhaltElement {
  protected $tag = "span";

  /** @var Reitersegment Übergeordnetes Reitersegment */
  private $reitersegment;
  /** @var int Nummer innerhalb des Reiters */
  private $nr;

  /**
   * Erzugt einen neuen Reiterkopf
   * @param string $text Textinhalt
   * @param Icon|null $icon Icon des Reiterkopfes
   * Wenn <code>null</code>: Weggelassen
   * @param int|null $meldezahl Meldezahl des Reiterkopfes
   * Wenn <code>null</code>: Weggelassen
   */
  public function __construct($text, $icon = null, $meldezahl = null) {
    parent::__construct($text);
    $this->nr             = null;
    $this->icon           = $icon;
    $this->meldezahl      = $meldezahl;
    $this->reitersegment  = null;
  }

  /**
   * Setzt die Nr für den Reiterkopf neu
   * @param int $nr :)
   */
  public function setNr($nr) {
    $this->nr = $nr;
  }

  /**
   * Setzt das zugehörige Reitersegment
   * @param  Reitersegment $reitersegment :)
   * @return self                         :)
   */
  public function setReitersegment($reitersegment) : self {
    $this->reitersegment = $reitersegment;
    return $this;
  }

  /**
   * Gibt einen Klon mit passenden Events zurück
   * @return self
   */
  public function toStringVorbereitung() : self {
    $this->getAktionen()->setFunktion("onclick", 3, "ui.reiter.aktion('{$this->reitersegment->getReiter()->getID()}', '$this->nr', '{$this->reitersegment->getReiter()->getAnzahl()}')");
    return $this;
  }

  /**
   * HTML-Code des Reiterkopfes
   * @return string :)
   */
  public function __toString() : string {
    $this->toStringVorbereitung();
    $icon = $this->icon;
    if($icon !== null) {
      $icon = "$icon ";
    }
    $zahl = $this->meldezahl;
    if($zahl !== null) {
      $zahl = " ".new Meldezahl($zahl);
    }
    return "{$this->codeAuf()}$icon{$this->inhalt}$zahl{$this->codeZu()}";
  }
}

class Reitersegment {
  /** @var Reiter Übergeordneter Reiter */
  private $reiter;
  /** @var int Nummer innerhalb aller Reitersegmente */
  private $nr;
  /** @var Reiterkopf Kopf des Reiters */
  private $reiterkopf;
  /** @var Reiterkoerper Körper des Reiters */
  private $reiterkoerper;

  /**
   * Erstellt ein Reitersegment
   * @param Reiterkopf    $kopf    Kopf des Reitersegments
   * @param Reiterkoerper $koerper Körper des Reitersegments
   */
  public function __construct($kopf, $koerper) {
    $this->reiter = null;
    $this->nr = null;
    $kopf->setReitersegment($this);
    $this->reiterkopf = $kopf;
    $this->reiterkoerper = $koerper;
  }

  /**
   * Reiterkopf hinzufügen
   * @param  Reiterkopf $kopf :)
   * @return self             :)
   */
  public function setKopf($kopf) : self {
    $this->reiterkopf = $kopf;
    return $this;
  }

  /**
   * Reiterkörper hinzufügen
   * @param  Reiterkoerper $koerper :)
   * @return self                   :)
   */
  public function setKoerper($koerper) : self {
    $this->reiterkoerper = $koerper;
    return $this;
  }

  /**
   * Setzt die Nr für das gesamte Reitersegment neu
   * @param int $nr :)
   */
  public function setNr($nr) {
    $this->nr = $nr;
    $this->reiterkopf->setNr($nr);
  }

  /**
   * Setzt den zugehörigen Reiter
   * @param  Reiter $reiter :)
   * @return self                         :)
   */
  public function setReiter($reiter) : self {
    $this->reiter = $reiter;
    return $this;
  }

  /**
   * Gibt den Übergeordneten Reiter zurück
   * @return Reiter :)
   */
  public function getReiter() : Reiter {
    return $this->reiter;
  }

  /**
   * Gibt den Reiterkopf zurück
   * @return Reiterkopf :)
   */
  public function getKopf() : Reiterkopf {
    return $this->reiterkopf;
  }

  /**
   * Gibt den Reiterkörper zurück
   * @return Reiterkoerper :)
   */
  public function getKoerper() : Reiterkoerper {
    return $this->reiterkoerper;
  }

}

class Reiter extends Element implements \ArrayAccess {
  protected $tag = "div";

  /** @var Reitersegment[] Enthält alle Reitersegmente */
  private $reitersegmente;
  /** @var int Enthält den aktuell aktiven Reiter */
  private $gewaehlt;

  /**
   * Erstellt einen Reiter
   * @param string $id ID des Reiters
   */
  public function __construct($id) {
    parent::__construct();
    $this->id = $id;
    $this->addKlasse("dshUiReiter");
    $this->reitersegmente = [];
    $this->gewaehlt = -1;
  }

  /**
   * Gibt die Anzahl der beinhalteten Reitersegmente zurück
   * @return int Anzahl Reitersegmente
   */
  public function getAnzahl() : int {
    return count($this->reitersegmente);
  }

  /**
   * Neues Reitersegment anlegen und eingliedern
   * @param  Reitersegment $reitersegment :)
   * @return self
   */
  public function addReitersegment($reitersegment) : self {
    $reitersegment->setReiter($this);
    $nr = count($this->reitersegmente);
    $reitersegment->getKoerper()->setID($this->id."Koerper".$nr);
    $reitersegment->getKopf()->setID($this->id."Kopf".$nr);
    $reitersegment->setNr($nr);
    $this->reitersegmente[] = $reitersegment;
    if ($this->gewaehlt == -1) {
      $this->gewaehlt = 0;
    }
    return $this;
  }

  /**
   * Setzt den gewählten Tab auf aktiv
   * @param  int  $nr Nr des aktiven Tabs
   * @return self     :)
   */
  public function setGewaehlt($nr) : self {
    if ($nr < count($this->reitersegmente) && $nr >= 0) {
      $this->gewaehlt = $nr;
    } else if (count($this->reitersegmente) > 0) {
      $this->gewaehlt = 0;
    } else {
      $this->gewaehlt = -1;
    }
    return $this;
  }


  /**
   * Gibt den Code eines Reiters zurück
   * @return string :)
   */
  public function __toString() : string {
    $code = $this->codeAuf();
    $koepfe = "";
    $koerper = "";
    for ($i=0; $i<count($this->reitersegmente); $i++) {
      $oben = $this->reitersegmente[$i]->getKopf();
      $unten = $this->reitersegmente[$i]->getKoerper();
      if ($i == $this->gewaehlt) {
        $oben->addKlasse("dshUiReiterKopfAktiv");
        $unten->addKlasse("dshUiReiterKoerperAktiv");
        $oben->removeKlasse("dshUiReiterKopfInaktiv");
        $unten->removeKlasse("dshUiReiterKoerperInaktiv");
      } else {
        $oben->addKlasse("dshUiReiterKopfInaktiv");
        $unten->addKlasse("dshUiReiterKoerperInaktiv");
        $oben->removeKlasse("dshUiReiterKopfAktiv");
        $unten->removeKlasse("dshUiReiterKoerperAktiv");
      }
      $koepfe .= $oben;
      $koerper .= $unten;
    }
    $code .= "<div class=\"dshUiReiterKoepfe\">$koepfe</div>";
    $code .= "<div class=\"dshUiReiterKoerper\">$koerper</div>";
    return $code.$this->codeZu();
  }

  /*
   * AccesAcces-Methoden
   */

  public function offsetSet($o, $v) {
    if(!($v instanceof Reitersegment)) {
      throw new \TypeError("Das übergebene Element ist nicht vom Typ \\UI\\Reitersegment");
    }
    if(!is_null($o)) {
      throw new \Exception("Nicht implementiert!");
    }
    $this->addReitersegment($v);
  }

  public function offsetExists($o) {
    throw new \Exception("Nicht implementiert!");
  }

  public function offsetUnset($o) {
    throw new \Exception("Nicht implementiert!");
  }

  public function offsetGet($o) {
    throw new \Exception("Nicht implementiert!");
  }

}
?>

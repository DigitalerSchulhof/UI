<?php
namespace UI;

class Reiterkoerper extends Zeile {
  private $typ = "A1";

  /** @var Reitersegment Übergeordnetes Reitersegment */
  private $reitersegment;
  /** @var int Nummer innerhalb des Reiters */
  private $nr;

  /**
   * Erstellt einen neuen Reiterkörper
   * @param Element $element Element, das dem Reiterkörper hinzugefügt werden soll
   * @param string $klasse   CSS-Klasse des Reiterkörpers
   */
  public function __construct($element = null, $klasse = "") {
    parent::__construct($element, "A1", $klasse);
    $this->reitersegment = null;
  }

  /**
   * Setzt die Nr für den Reiterkörper neu
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
}

class Reiterkopf extends InhaltElement {
  protected $tag = "span";

  /** @var Reitersegment Übergeordnetes Reitersegment */
  private $reitersegment;
  /** @var int Nummer innerhalb des Reiters */
  private $nr;

  /**
   * @param string        $inhalt :)
   */
  public function __construct($inhalt) {
    parent::__construct($inhalt);
    $this->nr = null;
    $this->reitersegment = null;
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
    $self = clone $this;
    $self->getAktionen()->addFunktionPrioritaet("onclick", 3, "ui.reiter.aktion('{$self->reitersegment->getReiter()->getID()}', '$self->nr', '{$self->reitersegment->getReiter()->getAnzahl()}')");

    return $self;
  }

  /**
   * HTML-Code des Reiterkopfes
   * @return string :)
   */
  public function __toString() : string {
    $self = $this->toStringVorbereitung();
    return "{$self->codeAuf()}{$self->inhalt}{$self->codeZu()}";
  }
}

class Reitericonkopf extends Reiterkopf {
  /** @var Icon $icon :) */
  private $icon;

  /**
   * @param string $inhalt :)
   * @param Icon   $icon   :)
   */
  public function __construct($inhalt, $icon) {
    parent::_construct($inhalt);
    $this->icon = $icon;
  }

  /**
   * HTML-Code des Reiterkopfes
   * @return string :)
   */
  public function __toString() : string {
    $self = $this->toStringVorbereitung();
    return "{$self->codeAuf()}{$self->icon} {$self->inhalt}{$self->codeZu()}";
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
    $koerper->setReitersegment($this);
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
    $this->reiterkoerper->setNr($nr);
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

class Reiter extends Element {
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
    $this->reitersegmente = array();
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
   * Gibt den Code eines Reiters zurück
   * @return string :)
   */
  public function __toString() : string {
    $code = $this->codeAuf();
    $koepfe = "";
    $koerper = "";
    for ($i=0; $i<count($this->reitersegmente); $i++) {
      $oben = clone $this->reitersegmente[$i]->getKopf();
      $unten = clone $this->reitersegmente[$i]->getKoerper();
      if ($i == $this->gewaehlt) {
        $oben->addKlasse("dshUiReiterKopfAktiv");
        $unten->addKlasse("dshUiReiterKoerperAktiv");
      } else {
        $oben->addKlasse("dshUiReiterKopfInaktiv");
        $unten->addKlasse("dshUiReiterKoerperInaktiv");
      }
      $koepfe .= $oben;
      $koerper .= $unten;
    }
    $code .= "<div class=\"dshUiReiterKoepfe\">$koepfe</div>";
    $code .= "<div class=\"dshUiReiterKoerper\">$koerper</div>";
    return $code.$this->codeZu();
  }
}
?>

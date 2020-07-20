<?php
namespace UI;

class Reiterkopf extends UI\InhaltElement {
  protected $tag = "span";

  /** @var Reitersegment Übergeordnetes Reitersegment */
  private $reitersegment;
  /** @var int Nummer innerhalb des Reiters */
  private $nr;

  /**
   * @param Reitersegment $reitersegment :)
   * @param int           $nr :)
   * @param string        $inhalt :)
   */
  public function __construct($reitersegment, $nr, $inhalt) {
    parent::__construct($inhalt);
    $this->nr = $nr;
    $this->reitersegment = $reitersegment;
  }

  /**
   * Setzt die Nr für den Reiterkopf neu
   * @param int $nr :)
   */
  public function setNr($nr) {
    $this->reiterid = $nr;
  }

  /**
   * Gibt einen Klon mit passenden Events zurück
   * @return self
   */
  public function toStringVorbereitung() : self {
    $self = clone $this;
    $self->getAktionen()->addFunktionPrioritaet("onclick", 3, "ui.reiter.aktion('{$self->reitersegment->getReiter()->getID()}', '$self->nr', '$self->reitersegment->getReiter()->getAnzahl()')");

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
   * @param int    $nr :)
   * @param string $inhalt :)
   * @param Icon   $icon   :)
   */
  public function __construct($reitersegment, $nr, $inhalt, $icon) {
    parent::_construct($reitersegment, $nr, $inhalt);
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
   * @param int           $anzahl        :)
   * @param int           $nr            :)
   * @param string        $reiterid      :)
   * @param Reiterkopf    $kopf          :)
   * @param Reiterkoerper $reiterkoerper :)
   */
  public function __construct($reiter, $nr, $kopf, $reiterkoerper) {
    $this->nr = $nr;
    $this->reiterid = $reiterid;
    $this->reiterkopf = $kopf;
    $this->reiterkoerper = $koerper;
  }

  /**
   * Setzt die Nr für das gesamte Reitersegment neu
   * @param int $nr :)
   */
  public function setNr($nr) {
    $this->reiterid = $nr;
    $this->reiterkopf->setNr($nr);
    $this->reiterkoerper->setNr($nr);
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

class Reiter extends UI\Element {
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
    $this->id = $id;
    $this->addKlasse("dshUiReiter");
  }

  /**
   * Gibt die Anzahl der beinhalteten Reitersegmente zurück
   * @return int Anzahl Reitersegmente
   */
  public function getAnzahl() : int {
    return count($this->reitersegmente);
  }

  public function __toString() : string {
    $code = $this->codeAuf();
    $koepfe = "";
    $koerper = "";
    for ($i=0; $i<count($this->reitersegmente); $i++) {
      $oben = clone $this->reitersegmente[$i]->getKopf();
      $unten = clone $this->reitersegmente[$i]->getKoerper();
      if ($i == $gewaehlt) {
        $oben->addKlasse("dshUiReiterKopfGewaehlt");
        $unten->addKlasse("dshUiReiterKoerperGewaehlt");
      } else {
        $oben->addKlasse("dshUiReiterKopfInaktiv");
        $unten->addKlasse("dshUiReiterKoerperInaktiv");
      }
      $kopefe .= $oben;
      $koerper .= $unten;
    }
    $code .= "<div class=\"dshUiReiterKoepfe\">$koepfe</div>";
    $code .= "<div class=\"dshUiReiterKoerper\">$koerper</div>";
    return $code.$this->codeZu();
  }
}
?>

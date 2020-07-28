<?php
namespace UI;

/**
*Schaltflächen erstellen
*/
class Knopf extends InhaltElement {
  protected $tag = "button";

  /** @var string Zulässige Knopfarten */
  const ARTEN = ["Standard", "Erfolg", "Warnung", "Fehler", "Information", "Laden", "Passiv", "Eingeschraenkt", "Gesperrt"];

  /** @var string Knopfart */
  protected $art;

	/**
	* @param string $inhalt :)
	* @param string $art :)
	*/
  public function __construct($inhalt, $art = null) {
    parent::__construct($inhalt);
    if(!in_array($art, self::ARTEN)) {
      $art = self::ARTEN[0];
    }
    $this->art = $art;
    $this->addKlasse("dshUiKnopf");
    $this->setAttribut("role", "button");
  }

  /**
   * Gibt einen Klon mit passenden Klassen zurück
   * @return self
   */
  public function toStringVorbereitung() : self {
    $self = clone $this;
    if ($self->aktionen->count() === 0) {
      $self->addKlasse("dshUiKnopfLeer");
      $self->setAttribut("aria-disabled", "true");
      $self->setAttribut("tabindex", "-1");
    } else {
      $self->setAttribut("aria-disabled", "false");
      $self->setAttribut("tabindex", "0");
    }

    if ($self->getAktionen()->hatAusloeser("href")) {
      $self->setTag("a");
    }

    $self->addKlasse("dshUiKnopf{$self->art}");
    $self->setInhalt((new InhaltElement($self->inhalt))->setTag("span"));

    return $self;
  }

  public function __toString() : string {
    $self = $this->toStringVorbereitung();
    return "{$self->codeAuf()}{$self->inhalt}{$self->codeZu()}";
  }

  /**
   * Shortcut zu Aktionen addFunktion
   * @param 	string|string[] $ausloeser Auslöser der Funktionen<br>Wenn array, dann für jeden Auslöser hinzugefügt
	 * @param 	string ...$funktionen Was passiert, wenn der Auslöser auftritt
	 * @return 	self
	 */
  public function addFunktion($ausloeser, ...$funktionen) : self {
    $this->aktionen->addFunktion($ausloeser, ...$funktionen);
    return $this;
  }
}

class IconKnopf extends Knopf {
  /** @var Icon Icon der Schaltlfäche */
  protected $icon;

  /**
  * @param Icon   $icon :)
  * @param string $inhalt :)
  * @param string $art :)
  */
  public function __construct($icon, $inhalt, $art = null) {
    parent::__construct($inhalt, $art);
    if($art === "Laden") {
      $icon = new Icon(Konstanten::LADEN." fa-spin");
    }
    $this->icon = $icon;
    $this->addKlasse("dshUiKnopfIcon");
  }
  
  public function __toString() : string {
    $self = $this->toStringVorbereitung();
    return "{$self->codeAuf()}{$self->icon}{$self->inhalt}{$self->codeZu()}";
  }
}

class GrossIconKnopf extends IconKnopf {
  /**
  * @param Icon   $icon :)
  * @param string $inhalt :)
  * @param string $art :)
  * @param string $typ :)
  */
  public function __construct($icon, $inhalt, $art = null) {
    parent::__construct($icon, $inhalt, $art);
    $this->addKlasse("dshUiKnopfGross");
  }

  public function __toString() : string {
    $self = $this->toStringVorbereitung();

    return "{$self->codeAuf()}{$self->icon}{$self->inhalt}{$self->codeZu()}";
  }
}

class MiniIconKnopf extends IconKnopf {
  /**
  * @param Icon   $icon :)
  * @param string $inhalt :)
  * @param string $art :)
  * @param string $typ :)
  * @param string $position Position des Hinweises - ["OR", "OL", "UR", "UL"]
  */
  public function __construct($icon, $inhalt, $art = null, $position = "OL") {
    parent::__construct($icon, $inhalt, $art);
    $this->hinweis = new Hinweis($inhalt, $position);
    $this->addKlasse("dshUiKnopfIconMini");
  }

  public function __toString() : string {
    $self = $this->toStringVorbereitung();

    return "{$self->codeAuf()}{$self->icon}{$self->codeZu()}";
  }
}


class Sortierknopf extends MiniIconKnopf {
  /** @var string $richtung Richtung der Sortierung */
  protected $richtung;
  /** @var string $tabelleID ID der Tabelle auf die sich das Sortieren bezieht */
  protected $tabelleID;
  /** @var string $spaltenname Spalte, auf die sich das das Sortieren bezieht */
  protected $spaltenname;

  /** @var string Zulässige Richtungen */
  const RICHTUNGEN = ["ASC", "DESC"];

  /**
  * @param string $richtung (ASC oder DESC)
  * @param string $tabelle  TabellenID
  * @param string $spalte   Name der zu sortierenden Spalte
  */
  public function __construct($richtung, $tabelleId, $spaltenname) {
    if (!in_array($richtung, self::RICHTUNGEN)) {
      $richtung = self::RICHTUNGEN[0];
    }
    $this->richtung = $richtung;
    if ($richtung == "DESC") {
      $inhalt = "";
      $icon = new Icon(Konstanten::DESC);
      $this->addKlasse("dshUiSortierenDESC");
    } else {
      $inhalt = "";
      $icon = new Icon(Konstanten::ASC);
      $this->addKlasse("dshUiSortierenASC");
    }
    parent::__construct($icon, $inhalt, null, "OL");
    $this->hinweis = null;
    $this->tabelleId = $tabelleId;
    $this->spaltenname = $spaltenname;
  }

  /**
   * Gibt einen Klon mit passenden Klassen zurück
   * @return self
   */
  public function toStringVorbereitung() : self {
    $self = clone $this;
    $self->aktionen->addFunktion("onclick", "ui.tabelle.sortieren('{$this->richtung}', '{$this->tabelleId}', '{$this->spaltenname}')");
    $self->addKlasse("dshUiSortierknopf");
    return $self;
  }
}
?>

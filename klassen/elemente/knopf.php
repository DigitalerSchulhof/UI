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

  /** @var bool Ist Submit-Knopf */
  protected $submit;

	/**
	* @param string $inhalt :)
	* @param string $art :)
	*/
  public function __construct($inhalt, $art = null, $aktion = null) {
    parent::__construct($inhalt);
    if(!in_array($art, self::ARTEN)) {
      $art = self::ARTEN[0];
    }
    $this->art = $art;
    $this->submit = false;
    if ($aktion != null) {
      $this->addFunktion("onclick", $aktion);
    }
    $this->addKlasse("dshUiKnopf");
    $this->setAttribut("role", "button");
  }

  /**
   * Setzt, ob der Knopf ein Submit-Knopf ist
   * @param  bool $submit :)
   * @return self
   */
  public function setSubmit($submit) : self {
    $this->submit = $submit;
    return $this;
  }

  /**
   * Gibt zurück, ob der Knopf ein Submit-Knopf ist
   * @return bool
   */
  public function istSubmit() : bool {
    return $this->submit;
  }

  /**
   * Gibt einen Klon mit passenden Klassen zurück
   * @return self
   */
  public function toStringVorbereitung() : self {
    if ($this->aktionen->count() === 0 && !$this->istSubmit()) {
      $this->addKlasse("dshUiKnopfLeer");
      $this->setAttribut("aria-disabled", "true");
      $this->setAttribut("tabindex", "-1");
      $this->aktionen->addFunktion("onclick", "this.blur()");
    } else {
      $this->setAttribut("aria-disabled", "false");
      $this->setAttribut("tabindex", "0");
    }

    if ($this->getAktionen()->hatAusloeser("href")) {
      $this->setTag("a");
    }
    if($this->istSubmit()) {
      $this->setAttribut("type", "submit");
    }

    $this->addKlasse("dshUiKnopf{$this->art}");
    $this->setInhalt((new InhaltElement($this->inhalt))->setTag("span"));

    return $this;
  }

  public function __toString() : string {
    $this->toStringVorbereitung();
    return "{$this->codeAuf()}{$this->inhalt}{$this->codeZu()}";
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

  /**
   * Gibt den Standard-OK Knopf zurück
   * @return Knopf
   */
  public static function ok() : Knopf {
    return new Knopf("OK", "Standard", "ui.laden.aus()");
  }

  /**
   * Gibt den Standard-Abbrechen Knopf zurück
   * @return Knopf
   */
  public static function abbrechen() : Knopf {
    return new Knopf("Abbrechen", "Standard", "ui.laden.aus()");
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
    $this->toStringVorbereitung();
    return "{$this->codeAuf()}{$this->icon}{$this->inhalt}{$this->codeZu()}";
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
    $this->toStringVorbereitung();
    return "{$this->codeAuf()}{$this->icon}{$this->inhalt}{$this->codeZu()}";
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
    $this->toStringVorbereitung();

    return "{$this->codeAuf()}{$this->icon}{$this->codeZu()}";
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

    $this->aktionen->addFunktionPrioritaet("onclick", 3, "ui.tabelle.sortieren('{$this->richtung}', '{$this->tabelleId}', '{$this->spaltenname}')");
    $this->addKlasse("dshUiSortierknopf");
  }
}
?>

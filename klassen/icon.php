<?php
namespace UI;

class Icon extends Element {
  protected $tag = "i";
  /** @var string Fontawesome Klassen des Icons */
  private $icon;

	/**
   * @param string $icon Fontawesome Klassen des Icons
   * Bsp: <code>"fas fa-user"</code> bzw. <code>\UI\Konstanten::PERSON</code>
   */
  public function __construct($icon) {
    parent::__construct();
    $this->icon = $icon;
    $this->addKlasse("dshUiIcon");
    $this->addKlasse($icon);
  }

  /**
   * Setzt das Icon auf den gegebenen Wert
   * @param string $icon Wert des Icons
   * @return self
   */
  public function setIcon($icon) : self {
    $this->removeKlasse($this->icon);
    $this->icon = $icon;
    $this->addKlasse($icon);
  }

  public function __toString() : string {
    return "{$this->codeAuf()}{$this->codeZu()}";
  }

  /**
   * Gibt das Standardicon für Löschen zurück
   * @return Icon :)
   */
  public static function loeschen() : Icon {
    return new Icon(Konstanten::LOESCHEN);
  }

  /**
   * Gibt das Standardicon für Papierkorb zurück
   * @return Icon :)
   */
  public static function papierkorb() : Icon {
    return new Icon(Konstanten::PAPIERKORB);
  }

  /**
   * Gibt das Standardicon für Bearbeiten zurück
   * @return Icon :)
   */
  public static function bearbeiten() : Icon {
    return new Icon(Konstanten::BEARBEITEN);
  }

  /**
   * Gibt das Standardicon für Neues zurück
   * @return Icon :)
   */
  public static function neu() : Icon {
    return new Icon(Konstanten::NEU);
  }
}
?>

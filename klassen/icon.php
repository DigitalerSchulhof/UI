<?php
namespace UI;

class Icon {
  /** @var string Art der Meldung */
  private $icon;

	/**
   * @param string $icon Art des Icons
   */
  public function __construct($icon) {
    $this->icon = $icon;
  }

  /**
   * Setzt das Icon auf den gegebenen Wert
   * @param string $icon Wert des Icons
   */
  public function setIcon($icon) {
    $this->icon = $icon;
  }

  public function __toString() : string {
    return "<i class=\"{$this->icon}\"></i>";
  }
}
?>

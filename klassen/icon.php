<?php
namespace UI;

class Icon {
  /** @var string Fontawesome Klassen des Icons */
  private $icon;

	/**
   * @param string $icon Fontawesome Klassen des Icons
   * Bsp: <code>"fas fa-user"</code> bzw. <code>\UI\Konstanten::PERSON</code>
   */
  public function __construct($icon) {
    $this->icon = $icon;
  }

  /**
   * Setzt das Icon auf den gegebenen Wert
   * @param string $icon Wert des Icons
   * @return self
   */
  public function setIcon($icon) : self {
    $this->icon = $icon;
  }

  public function __toString() : string {
    return "<i class=\"{$this->icon}\"></i>";
  }
}
?>

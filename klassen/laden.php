<?php
namespace UI;


class Ladesymbol {

  public function __construct() {
  }

  /**
	* Gibt das  Ladesymbol als HTML-Code aus
	* @return string HTML-Code der Meldung
	*/
  public function ausgabe () : string {
    return "<div class=\"dshUiLadesymbol\"><div></div><div></div><div></div><div></div></div>";
  }
}
?>

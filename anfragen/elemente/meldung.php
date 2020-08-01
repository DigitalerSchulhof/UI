<?php
Anfrage::post("art", "titel", "inhalt");
Anfrage::post(false, "icon");

if($icon === "undefined") {
  $icon = null;
}

if($icon !== null) {
  $icon = new UI\Icon($icon);
}

Anfrage::setTyp("Code");
Anfrage::setRueck("Code", new UI\Meldung($titel, $inhalt, $art, $icon));
// $knoepfe = [];
//
// for ($i = 0; $i < $aktionen; $i++) {
//   $knopfinhalt = "knopfinhalt$i";
//   $knopfziel = "knopfziel$i";
//   $knopfart = "knopfart$i";
//   Anfrage::post($knopfinhalt, $knopfziel);
//   Anfrage::post(false, $knopfart);
//   Anfrage::checkFehler();
//
//   $knopf = new UI\Knopf($$knopfinhalt, $$knopfart);
//   $knopf->addFunktion("onclick", $$knopfziel);
//   $knoepfe[] = $knopf;
// }
//
// echo Anfrage::antwort("Meldung", null, $r->__toString(), $knoepfe);
?>

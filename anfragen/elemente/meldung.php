<?php
Anfrage::post("art", "titel", "inhalt");
Anfrage::post(false, "icon", "aktionen");
if($icon !== null) {
  $icon = new UI\Icon($icon);
}

$r = new UI\Meldung($titel, $inhalt, $art, $icon);

$knoepfe = [];

for ($i=0; $i<$aktionen; $i++) {
  $knopfinhalt = "knopfinhalt$i";
  $knopfziel = "knopfziel$i";
  $knopfart = "knopfart$i";
  Anfrage::post($knopfinhalt, $knopfziel);
  Anfrage::post(false, $knopfart);
  Anfrage::hatFehler();

  $knopf = new UI\Knopf($$knopfinhalt, $$knopfart);
  $knopf->addFunktion("onclick", $$knopfziel);
  $knoepfe[] = $knopf;
}

echo Anfrage::antwort("Meldung", null, $r->__toString(), $knoepfe);
?>

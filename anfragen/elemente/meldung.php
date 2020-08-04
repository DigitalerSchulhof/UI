<?php
Anfrage::post("art", "titel", "inhalt");
Anfrage::post(false, "icon", "aktionen");

if($icon === "undefined") {
  $icon = null;
}

if($icon !== null) {
  $icon = new UI\Icon($icon);
}

$knoepfe = [];

if(isset($aktionen)) {
  $aktionen = json_decode($aktionen, true);
  foreach($aktionen as $a) {
    $knopf      = new UI\Knopf($a["inhalt"], $a["art"] ?? null);
    $knopf      ->addFunktion("onclick", $a["ziel"]);
    $knoepfe[]  = $knopf;
  }
}

Anfrage::setTyp("Meldung");
Anfrage::setRueck("Meldung", new UI\Meldung($titel, $inhalt, $art, $icon));
Anfrage::setRueck("Knöpfe", $knoepfe);
?>

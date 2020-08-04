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
    if(isset($a["typ"])) {
      switch ($a["typ"]) {
        case "Abbrechen":
          $a["inhalt"]  = $a["inhalt"]  ?? "Abbrechen";
          $a["art"]     = $a["art"]     ?? "Standard";
          $a["ziel"]    = $a["ziel"]    ?? "ui.laden.aus()";
          break;
        case "OK":
          $a["inhalt"]  = $a["inhalt"]  ?? "OK";
          $a["art"]     = $a["art"]     ?? "Erfolg";
          $a["ziel"]    = $a["ziel"]    ?? "ui.laden.aus()";
        default:
          Anfrage::addFehler(2, true);
          break;
      }
    }
    $knopf      = new UI\Knopf($a["inhalt"], $a["art"] ?? null);
    $knopf      ->addFunktion("onclick", $a["ziel"]);
    $knoepfe[]  = $knopf;
  }
}

Anfrage::setTyp("Meldung");
Anfrage::setRueck("Meldung", new UI\Meldung($titel, $inhalt, $art, $icon));
Anfrage::setRueck("Knöpfe", $knoepfe);
?>

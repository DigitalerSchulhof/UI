<?php
Anfrage::post("komponente");

if (!in_array($komponente, ["IconKnopf"])) {
  Anfrage::addFehler(4, true);
}

$code = "";
switch ($komponente) {
  case "IconKnopf":
    Anfrage::post("inhalt", "icon");
    Anfrage::post(false, "art", "klickaktion");
    $knopf = new UI\IconKnopf(new UI\Icon($icon), $inhalt, $art);
    if ($klickaktion != null) {$knopf->addFunktion("onklick", $klickaktion);}
    $code = (string) $knopf;
    break;
}

Anfrage::setTyp("Code");
Anfrage::setRueck("Code", $code);
?>

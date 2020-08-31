<?php
Anfrage::post("komponente");

if (!in_array($komponente, ["IconKnopf", "IconKnopfPerson"])) {
  Anfrage::addFehler(4, true);
}

$code = "";
switch ($komponente) {
  case "IconKnopf":
    Anfrage::post("inhalt", "icon");
    Anfrage::post(false, "art", "klickaktion");
    $knopf = new UI\IconKnopf(new UI\Icon($icon), $inhalt, $art);
    if ($klickaktion != null) {$knopf->addFunktion("onclick", $klickaktion);}
    $code = (string) $knopf;
    break;
  case "IconKnopfPerson":
    Anfrage::post("inhalt", "personart");
    Anfrage::post(false, "id", "klickaktion");
    $knopf = new UI\IconKnopfPerson($inhalt, $personart);
    if ($id != null) {$knopf->setID($id);}
    if ($klickaktion != null) {$knopf->addFunktion("onclick", $klickaktion);}
    $code = (string) $knopf;
    $code .= " ";
    break;
}

Anfrage::setRueck("Code", $code);
?>

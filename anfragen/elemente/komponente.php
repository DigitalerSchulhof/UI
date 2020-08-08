<?php
Anfrage::post("komponente");

if (!in_array($komponente, ["IconKnopf"])) {
  Anfrage::addFehler(4, true);
}

switch ($komponente) {
  case "IconKnopf":
    Anfrage::post("inhalt", "icon");
    break;
}

Anfrage::setTyp("Code");
Anfrage::setRueck("Code", "");
?>

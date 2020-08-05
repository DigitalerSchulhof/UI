<?php
Anfrage::post("modul", "id", "parameter");

if (!Check::istZahl($id)) {
  Anfrage::addFehler(2);
}
if (!Check::istLatein($meldung)) {
  Anfrage::addFehler(3);
}
Anfrage::checkFehler();

$gefunden = false;
$knoepfe = [];

if (!is_file("$ROOT/module/$modul/meldungen.php")) {
  Anfrage::addFehler(4, true);
} else {
    include("$ROOT/module/$modul/meldungen.php");
}

if (!$gefunden) {
  Anfrage::setTyp("Meldung");
  Anfrage::setRueck("Meldung", new UI\Meldung("Meldung nicht gefunden", "Das was hier stehen sollte, muss erst noch geschrieben werden ...", "Fehler"));
  Anfrage::setRueck("Knöpfe", [new UI\Knopf::ok()]);
}
?>

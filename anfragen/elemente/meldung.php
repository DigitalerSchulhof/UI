<?php
Anfrage::post("meldemodul", "meldeid", "meldeparameter");

if (!UI\Check::istZahl($meldeid)) {
  Anfrage::addFehler(2);
}
if (!UI\Check::istLatein($meldemodul)) {
  Anfrage::addFehler(3);
}
Anfrage::checkFehler();

$gefunden = false;
$knoepfe = [];
$parameter = json_decode($meldeparameter, true);

if (!is_file(__DIR__."/../../../$meldemodul/meldungen.php")) {
  Anfrage::addFehler(4, true);
} else {
  include(__DIR__."/../../../$meldemodul/meldungen.php");
}

if (!$gefunden) {
  Anfrage::setTyp("Meldung");
  Anfrage::setRueck("Meldung", new UI\Meldung("Meldung nicht gefunden", "Das was hier stehen sollte, muss erst noch geschrieben werden ...", "Fehler"));
}
?>

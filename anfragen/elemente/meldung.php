<?php
Anfrage::post("meldemodul", "meldeid", "meldeparameter");

if (!UI\Check::istZahl($meldeid)) {
  Anfrage::addFehler(2);
}
if (!Kern\Check::istModul($meldemodul)) {
  Anfrage::addFehler(3);
}
Anfrage::checkFehler();

$gefunden = false;
$knoepfe = [];
$parameter = json_decode($meldeparameter, true);

if($parameter !== null) {
  extract($parameter);
}
global $parameter;

function parameter(...$ps) {
  global $parameter;
  foreach($ps as $p) {
    global $$p;
    $$p = $parameter[$p];
  }
}

if (!is_file("$DSH_MODULE/$meldemodul/funktionen/meldungen.php")) {
  Anfrage::addFehler(4, true);
} else {
  include("$DSH_MODULE/$meldemodul/funktionen/meldungen.php");
}

if (Anfrage::getRueck("Meldung") === null) {
  Anfrage::setRueck("Meldung", new UI\Meldung("Meldung nicht gefunden", "Das was hier stehen sollte, muss erst noch geschrieben werden...", "Fehler"));
}

Anfrage::setRueck("Meldung", (string) Anfrage::getRueck("Meldung"));
Anfrage::setRueck("Knoepfe", join("", Anfrage::getRueck("Knöpfe") ?? [UI\Knopf::ok()]));
?>

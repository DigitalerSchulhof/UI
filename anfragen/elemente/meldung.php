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

function parameter(...$ps) {
  global $parameter;
  if(is_array($parameter)) {
    global $parameter;
    foreach($ps as $p) {
      global $$p;
      if(!isset($parameter[$p])) {
        Anfrage::addFehler(-3, true);
      }
      $$p = $parameter[$p];
    }
  } else {
    Anfrage::addFehler(-3, true);
  }
}

if (!is_file("$DSH_MODULE/$meldemodul/funktionen/meldungen.php")) {
  Anfrage::addFehler(4, true);
} else {
  $knoepfe = [];
  include("$DSH_MODULE/$meldemodul/funktionen/meldungen.php");
}

if (Anfrage::getRueck("Meldung") === null) {
  Anfrage::setRueck("Meldung", new UI\Meldung("Meldung nicht gefunden", "Das was hier stehen sollte, muss erst noch geschrieben werden...", "Fehler"));
}

Anfrage::setRueck("Meldung", (string) UI\Zeile::standard(Anfrage::getRueck("Meldung")));
Anfrage::setRueck("Knoepfe", join("", Anfrage::getRueck("Knöpfe") ?? [UI\Knopf::ok()]));
?>

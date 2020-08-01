<?php
Anfrage::post("id", "tag", "monat", "jahr");

if(!Check::istDatum("$tag.$monat.$jahr")) {
  Anfrage::addFehler(1);
}

$r = new UI\Datumfeld($id);
$r->setWert("$tag.$monat.$jahr");
Anfrage::setTyp("Code");
Anfrage::setRueck("Code", (string) $r->tageswahlGenerieren());
?>

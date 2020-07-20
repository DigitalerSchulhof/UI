<?php

  Anfrage::post("id", "tag", "monat", "jahr");

  if(!Check::istDatum("$tag.$monat.$jahr")) {
    Anfrage::fehler(1);
  }

  $r = new UI\Datumfeld($id);
  $r->setWert("$tag.$monat.$jahr");
  echo $r->tageswahlGenerieren();
?>
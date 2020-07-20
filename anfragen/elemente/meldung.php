<?php

  Anfrage::post("art", "titel", "inhalt");
  Anfrage::post(false, "icon");
  $icon = null;
  if($icon !== "undefined") {
    $icon = new UI\Icon($icon);
  }
  $r = new UI\Meldung($titel, $inhalt, $art, $icon);
  echo $r;
?>
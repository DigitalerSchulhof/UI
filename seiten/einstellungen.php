<?php
$SEITE = new Kern\Seite("UI", "kern.module.einstellungen");

$spalte = new UI\Spalte("A1", new UI\SeitenUeberschrift("UI"));

$spalte[] = new UI\Meldung("Keine Einstellungen", "Für das Modul »UI« müssen keine allgemeienen Einstellungen vorgenommen werden.", "Information");

$SEITE[] = new UI\Zeile($spalte);
?>

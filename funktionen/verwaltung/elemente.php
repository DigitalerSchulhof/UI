<?php

use Kern\Verwaltung\Liste;
use Kern\Verwaltung\Element;
use UI\Icon;

$technik    = Liste::addKategorie(new \Kern\Verwaltung\Kategorie("technik", "Technik"));

if($DSH_BENUTZER->hatRecht("technik.style"))    $technik[] = new Element("Style",     "Style",              new Icon("fas fa-palette"),         "Schulhof/Verwaltung/Style");
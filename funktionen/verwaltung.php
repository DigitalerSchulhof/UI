<?php

use Kern\Verwaltung\Liste;
use Kern\Verwaltung\Element;
use UI\Icon;

$technik    = Liste::getKategorie("technik");

$technik[] = new Element("Style",     "Style",              new Icon("fas fa-palette"),         "Schulhof/Verwaltung/Style");
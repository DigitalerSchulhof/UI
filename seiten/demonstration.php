<?php
  $SEITE = new Kern\Seite("UI-Demonstration", false, false);

  $spalte = new UI\Spalte();
  $spalte->addElement("<h1>Meldungen</h1>");

  foreach(UI\Meldung::ARTEN as $art)
    $spalte->addElement(new UI\Meldung("Meldung $art", "<code>.dshUiMeldung{$art}</code><br>Der Inhalt ist mehrzeilig.", $art));

  $spalte->addElement("<h1>Knöpfe</h1>");
  $spalte->addElement("<h2>Knöpfe (Aktiv)</h2>");

  foreach(UI\Knopf::ARTEN as $art) {
    $knopf = new UI\Knopf($art, $art);
    $knopf->addFunktion("onclick", "alert('$art')");
    $spalte->addElement($knopf." ");
  }
  $spalte->addElement("<br><br><br>");
  foreach(UI\Knopf::ARTEN as $art) {
    $knopf = new UI\IconKnopf(new UI\Icon(UI\Konstanten::STANDARD), $art, $art);
    $knopf->addFunktion("onclick", "alert('$art')");
    $spalte->addElement($knopf." ");
  }
  $spalte->addElement("<br><br><br>");
  foreach(UI\Knopf::ARTEN as $art){
    $knopf = new UI\GrossIconKnopf(new UI\Icon(UI\Konstanten::STANDARD), $art, $art);
    $knopf->addFunktion("onclick", "alert('$art')");
    $spalte->addElement($knopf." ");
  }
  $spalte->addElement("<br><br><br>");
  foreach(UI\Knopf::ARTEN as $art){
    $knopf = new UI\MiniIconKnopf(new UI\Icon(UI\Konstanten::STANDARD), $art, $art);
    $knopf->addFunktion("onclick", "alert('$art')");
    $spalte->addElement($knopf." ");
  }
  $spalte->addElement("<h2>Knöpfe (Inaktiv)</h2>");
  foreach(UI\Knopf::ARTEN as $art) {
    $knopf = new UI\Knopf($art, $art);
    $spalte->addElement($knopf." ");
  }
  $spalte->addElement("<br><br><br>");
  foreach(UI\Knopf::ARTEN as $art) {
    $knopf = new UI\IconKnopf(new UI\Icon(UI\Konstanten::STANDARD), $art, $art);
    $spalte->addElement($knopf." ");
  }
  $spalte->addElement("<br><br><br>");
  foreach(UI\Knopf::ARTEN as $art){
    $knopf = new UI\GrossIconKnopf(new UI\Icon(UI\Konstanten::STANDARD), $art, $art);
    $spalte->addElement($knopf." ");
  }
  $spalte->addElement("<br><br><br>");
  foreach(UI\Knopf::ARTEN as $art){
    $knopf = new UI\MiniIconKnopf(new UI\Icon(UI\Konstanten::STANDARD), $art, $art);
    $spalte->addElement($knopf." ");
  }
  // $spalte = new UI\Spalte();
  $spalte->addElement("<h1>Eingabefelder</h1>");
  $spalte->addElement("<h3>Datum und Uhrzeit</h3>");
  $spalte->addElement(new UI\Datumfeld("dshUiDemoUhrzeitfeld")." ".new UI\Uhrzeitfeld("dshUiDemoUhrzeitfeld")."<br>");

  $spalte->addElement("<h3>Text</h3>");
  $textfeld = (new UI\Textfeld("dshUiDemoTextfeld"))->setPlatzhalter("Platzhalter...");
  $spalte->addElement($textfeld."<br>");

  $spalte->addElement("<h3>Zahlen</h3>");
  $spalte->addElement((new UI\Zahlenfeld("dshUiDemoZahlenfeld"))->setPlatzhalter("Platzhalter...")."<br>");

  $spalte->addElement("<h3>Farben</h3>");
  $spalte->addElement(new UI\Farbfeld("dshUiDemoFarbfeld")."<br>");

  $spalte->addElement("<h3>Passwort</h3>");
  $spalte->addElement((new UI\Passwortfeld("dshUiDemoPasswortfeld", $textfeld))->setPlatzhalter("Platzhalter...")."<br>");

  $spalte->addElement("<h3>E-Mail</h3>");
  $spalte->addElement((new UI\Mailfeld("dshUiDemoMailfeld"))->setPlatzhalter("Platzhalter...")."<br>");

  $spalte->addElement("<h3>Textarea</h3>");
  $spalte->addElement((new UI\Textarea("dshUiDemoTextarea"))->setPlatzhalter("Platzhalter...")."<br>");

  $spalte->addElement("<h3>Knöpfe</h3>");
  $spalte->addElement(new UI\Schieber("dshUiDemoSchieber")."<br>");
  $spalte->addElement("<br><br>");
  $spalte->addElement(new UI\Toggle("dshUiDemoToggle", "Text")."<br>");
  $spalte->addElement("<br><br>");
  $spalte->addElement(new UI\IconToggle("dshUiDemoIconToggle", "Text", new UI\Icon(UI\Konstanten::PERSON))."<br>");
  $spalte->addElement("<br><br>");
  $spalte->addElement(new UI\IconToggleGross("dshUiDemoIconToggleGross", "Text", new UI\Icon(UI\Konstanten::PERSON))."<br>");
  $spalte->addElement("<br><br>");

  $SEITE[] = new UI\Zeile($spalte, $spalte);
?>

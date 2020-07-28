ui.meldung = {
  erfolg:           (t, b) => ui.meldung.meldung("Erfolg", t, b),
  fehler:           (t, b) => ui.meldung.meldung("Fehler", t, b),
  warnung:          (t, b) => ui.meldung.meldung("Warnung", t, b),
  information:      (t, b) => ui.meldung.meldung("Information", t, b),
  laden:            (t, b) => ui.meldung.meldung("Laden", t, b),
  eingeschraenkt:   (t, b) => ui.meldung.meldung("Eingeschraenkt", t, b),
  gesperrt:         (t, b) => ui.meldung.meldung("Gesperrt", t, b),

  meldung: (art, titel, inhalt, icon) => core.ajax("UI", 1, null, {art: art, titel: titel, inhalt: inhalt, icon: icon})
};
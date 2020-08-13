ui.meldungen = {
  erfolg:           (t, b) => ui.meldungen.meldung("Erfolg", t, b),
  fehler:           (t, b) => ui.meldungen.meldung("Fehler", t, b),
  warnung:          (t, b) => ui.meldungen.meldung("Warnung", t, b),
  information:      (t, b) => ui.meldungen.meldung("Information", t, b),
  laden:            (t, b) => ui.meldungen.meldung("Laden", t, b),
  eingeschraenkt:   (t, b) => ui.meldungen.meldung("Eingeschraenkt", t, b),
  gesperrt:         (t, b) => ui.meldungen.meldung("Gesperrt", t, b),

  meldung: (art, titel, inhalt, icon) => {
    return new Promise((fertig) => {
      core.ajax("UI", 1, null, {art: art, titel: titel, inhalt: inhalt, icon: icon}).then((r) => fertig(r.Code));
    });
  }
};
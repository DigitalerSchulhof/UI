ui.generieren = {
  monatname: {
    lang: (monat) => {
      monat = parseInt(monat);
      switch (monat) {
        case 1: return "Januar";
        case 2: return "Februar";
        case 3: return "März";
        case 4: return "April";
        case 5: return "Mai";
        case 6: return "Juni";
        case 7: return "Juli";
        case 8: return "August";
        case 9: return "September";
        case 10: return "Oktober";
        case 11: return "November";
        case 12: return "Dezember";
        default: return "";
      }
    },
    kurz: (monat) => {
      monat = parseInt(monat);
      switch (monat) {
        case 1: return "JAN";
        case 2: return "FEB";
        case 3: return "MÄR";
        case 4: return "APR";
        case 5: return "MAI";
        case 6: return "Juni";
        case 7: return "Juli";
        case 8: return "AUG";
        case 9: return "SEP";
        case 10: return "OKT";
        case 11: return "NOV";
        case 12: return "DEZ";
        default: return "";
      }
    }
  },
  tagesname: {
    lang: (tag) => {
      switch (tag) {
        case 0: return "Sonntag";
        case 1: return "Montag";
        case 2: return "Dienstag";
        case 3: return "Mittwoch";
        case 4: return "Donnerstag";
        case 5: return "Freitag";
        case 6: return "Samstag";
        case 7: return "Sonntag";
        default: return "";
      }
    },
    kurz: (tag) => {
      switch (tag) {
        case 0: return "SO";
        case 1: return "MO";
        case 2: return "DI";
        case 3: return "MI";
        case 4: return "DO";
        case 5: return "FR";
        case 6: return "SA";
        case 7: return "SO";
        default: return "";
      }
    }
  },
  fuehrendeNull: (x) => {
    if (ui.check.natZahl(x)) {
      if ((x.toString()).length < 2) {
        return "0"+x;
      } else {
        return ""+x;
      }
    } else {
      return false;
    }
  },
  laden: {
    icon: () => "<div class=\"dshUiLadenIcon\"><div></div><div></div><div></div><div></div></div>",
    balken: {
      speicher: (id, belegt, gesamt, art) => {
        var belegt = belegt || null;
        var gesamt = gesamt || null;
        var art = art || null;
        var zusatzklasse = "";
        if (art !== null) {
          zusatzklasse = " dshUiLadenBalken"+art;
        }
        var code = "<div id=\""+id+"\" class=\"dshUiLadenBalkenAussen"+zusatzklasse+"\"><div class=\"dshUiLadenBalkenInnen\"></div></div>";
        if ((belegt !== null) && (gesamt != null)) {
          code += "<p class=\"dshUiLadenErklaerung\">"+ui.generieren.laden.speicher(belegt, gesammt)+"</p>";
        }
        return code;
      },
      zeit: (id, beginn, aktuell, ende, art) => {
        var beginn = beginn || null;
        var aktuell = aktuell || null;
        var ende = ende || null;
        var art = art || null;
        var zusatzklasse = "";
        if (art !== null) {
          zusatzklasse = " dshUiLadenBalken"+art;
        }
        var code = "<div id=\""+id+"\" class=\"dshUiLadenBalkenAussen"+zusatzklasse+"\"><div class=\"dshUiLadenBalkenInnen\"></div></div>";
        if ((beginn !== null) && (aktuell != null) && (ende != null)) {
          code += "<p class=\"dshUiLadenErklaerung\">"+ui.generieren.laden.zeit(beginn, aktuell, ende)+"</p>";
        }
        return code;
      }
    },
    speicher: (x, gesamt) => "<span>"+ui.generieren.speicherplatz(x)+" ("+ui.generieren.prozent(x, gesamt)+"%) von "+ui.generieren.speicherplatz(gesamt)+" belegt. Frei: ("+(100-ui.generieren.prozent(x, gesamt))+"%)</span>",
    zeit: (beginn, aktuell, ende) => "<span>Begonnen um "+ui.generieren.zeit(beginn)+". Zeit bis: "+ui.generieren.zeit(ende)+". Frei: ("+(100-ui.generieren.prozent(x, gesamt))+"%)</span>"
  },
  prozent: (x, gesamt) => Math.round(x/gesamt*10000)/100,
  zeit: (x) => {
    var datum = new Date(x);
    return datum.getHours()+":"+datum.getMinutes()+" Uhr";
  },
  komma: (x) => (Math.round(bytes*100)/100).toString().replace('.', ','),
  speicherplatz: (bytes) => {
    if (bytes/1000 > 1) {
      bytes = bytes/1000;
      if (bytes/1000 > 1) {
        bytes = bytes/1000;
        if (bytes/1000 > 1) {
          bytes = bytes/1000;
          if (bytes/1000 > 1) {
            bytes = bytes/1000;
            if (bytes/1000 > 1) {
              bytes = bytes/1000;
              if (bytes/1000 > 1) {
                bytes = bytes/1000;
                bytes = (Math.round(bytes*100)/100).toString().replace('.', ',');
                return bytes+" EB";
              }
              bytes = (Math.round(bytes*100)/100).toString().replace('.', ',');
              return bytes+" PB";
            }
            bytes = (Math.round(bytes*100)/100).toString().replace('.', ',');
            return bytes+" TB";
          }
          bytes = (Math.round(bytes*100)/100).toString().replace('.', ',');
          return bytes+" GB";
        }
        bytes = (Math.round(bytes*100)/100).toString().replace('.', ',');
        return bytes+" MB";
      }
      bytes = (Math.round(bytes*100)/100).toString().replace('.', ',');
      return bytes+" KB";
    }
    return bytes+" B";
  }
}

ui.check = {
  zahl: (x) => x.toString().match(/^-?[0-9]+$/),
  natZahl: (x) => x.toString().match(/^[0-9]+$/),
  mail: (x) => x.toString().match(/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]{2,}$/),
}

ui.datumsanzeige = {
  aktion: (id, an) => {
    var feld = $("#"+id+"Datumwahl");
    if (!an) {
      feld.style.display = "none";
    } else {
      var tag   = $("#"+id+"T").value;
      var monat = $("#"+id+"M").value;
      var jahr  = $("#"+id+"J").value;

      ui.datumsanzeige.tageswahl.generieren(id, tag, monat, jahr).then((r) => {
        feld.innerHTML = r;
        feld.style.display = "block";
      });
    }
  },
  monataendern: (id, tag, monat, jahr) => {
    var feld = $("#"+id+"Datumwahl");
    var datum = new Date (jahr, monat-1, tag);
    ui.datumsanzeige.tageswahl.generieren(id, datum.getDate(), datum.getMonth()+1, datum.getFullYear()).then((r) => feld.innerHTML = r);
  },
  checkTag: (id) => {
    var jetzt = new Date();
    var tag = $("#"+id+"T").value;
    if (!ui.check.natZahl(tag)) {tag = jetzt.getDate();}
    var monat = $("#"+id+"M").value;
    if (!ui.check.natZahl(monat)) {monat = jetzt.getMonth()+1;}
    var jahr = $("#"+id+"J").value;
    if (!ui.check.natZahl(jahr)) {jahr = jetzt.getFullYear();}

    jetzt = new Date(jahr, monat-1, tag);

    $("#"+id+"T").value = ui.generieren.fuehrendeNull(jetzt.getDate());
    $("#"+id+"M").value = ui.generieren.fuehrendeNull(jetzt.getMonth()+1);
    $("#"+id+"J").value = ui.generieren.fuehrendeNull(jetzt.getFullYear());
  },
  checkUhrzeit: (id, sekunden) => {
    var jetzt = new Date();
    var stunde = $("#"+id+"Std").value;
    if (!ui.check.natZahl(stunde)) {stunde = jetzt.getHours();}
    var minute = $("#"+id+"Min").value;
    if (!ui.check.natZahl(minute)) {minute = jetzt.getMinutes();}
    if (sekunden) {
      var sekunde = $("#"+id+"Sek").value;
      if (!ui.check.natZahl(sekunde)) {sekunde = jetzt.getSeconds();}
      jetzt = jetzt = new Date(2020, 03, 19, stunde, minute, sekunde);
      $("#"+id+"Sek").value = ui.generieren.fuehrendeNull(jetzt.getSeconds());
    }
    else {
      jetzt = jetzt = new Date(2020, 03, 19, stunde, minute);
    }
    $("#"+id+"Std").value = ui.generieren.fuehrendeNull(jetzt.getHours());
    $("#"+id+"Min").value = ui.generieren.fuehrendeNull(jetzt.getMinutes());
  },
  tageswahl: {
    generieren: (id, tag, monat, jahr) => core.ajax("UI", 0, null, {id: id, tag: tag, monat: monat, jahr: jahr}),
    aktion: (id, tag, monat, jahr) => {
      var datum = new Date (jahr, monat-1, tag);
      var tag = $("#"+id+"T").value   = ui.generieren.fuehrendeNull(datum.getDate());
      var monat = $("#"+id+"M").value = ui.generieren.fuehrendeNull(datum.getMonth()+1);
      var jahr = $("#"+id+"J").value  = ui.generieren.fuehrendeNull(datum.getFullYear());
      $("#"+id+"Datumwahl").style.display = 'none';
    }
  }
}

ui.schieber = {
  aktion: (id) => {
    var wert = $("#"+id).value;
    var neuerwert = 0;
    if (wert == 0) {
      neuerwert = 1;
    }
    $("#"+id).value = neuerwert;
    $("#"+id+"Schieber").classList.add("dshUiSchieber"+neuerwert);
    $("#"+id+"Schieber").classList.remove("dshUiSchieber"+wert);
  }
}

ui.toggle = {
  aktion: (id) => {
    var wert = $("#"+id).value;
    var neuerwert = 0;
    if (wert == 0) {
      neuerwert = 1;
    }
    $("#"+id).value = neuerwert;
    if(neuerwert == 1) {
      $("#"+id+"Toggle").classList.add("dshUiToggled");
    } else {
      $("#"+id+"Toggle").classList.remove("dshUiToggled");
    }
  }
}

ui.mail = {
  aktion: (id) => {
    var mail = $("#"+id).value;
    if (ui.check.mail(mail)) {
      $("#"+id).classList.add("dshUiPruefen1");
      $("#"+id).classList.remove("dshUiPruefen0");
    } else {
      $("#"+id).classList.add("dshUiPruefen0");
      $("#"+id).classList.remove("dshUiPruefen1");
    }
  }
}

ui.passwort = {
  aktion: (idvergleich, idpruefen) => {
    var vergleich = $("#"+idvergleich).value;
    var pruefen = $("#"+idpruefen).value;
    if (vergleich == pruefen) {
      $("#"+idpruefen).classList.add("dshUiPruefen1");
      $("#"+idpruefen).classList.remove("dshUiPruefen0");
    } else {
      $("#"+idpruefen).classList.add("dshUiPruefen0");
      $("#"+idpruefen).classList.remove("dshUiPruefen1");
    }
  }
}

ui.togglegruppe = {
  aktion: (id, nr, anzahl, wert) => {
    $("#"+id).value = wert;
    $("#"+id+"KnopfId").value = nr;
    for (var i=0; i<anzahl; i++) {
      $("#"+id+"Knopf"+i).classList.remove("dshUiToggled");
    }
    $("#"+id+"Knopf"+nr).classList.add("dshUiToggled");
  }
}

ui.reiter = {
  aktion: (id, nr, anzahl) => {
    for (var i=0; i<anzahl; i++) {
      $("#"+id+"Koerper"+i).classList.remove("dshUiReiterKoerperAktiv");
      $("#"+id+"Koerper"+i).classList.add("dshUiReiterKoerperInaktiv");
      $("#"+id+"Kopf"+i).classList.remove("dshUiReiterKopfAktiv");
      $("#"+id+"Kopf"+i).classList.add("dshUiReiterKopfInaktiv");
    }
    $("#"+id+"Koerper"+nr).classList.remove("dshUiReiterKoerperInaktiv");
    $("#"+id+"Koerper"+nr).classList.add("dshUiReiterKoerperAktiv");
    $("#"+id+"Kopf"+nr).classList.remove("dshUiReiterKopfInaktiv");
    $("#"+id+"Kopf"+nr).classList.add("dshUiReiterKopfAktiv");
  }
}

ui.laden = {
  balken: {
    prozent: (id, x) => {
      $("#"+id+"Innen").style.width = x+"%";
    }
  },
  an: (titel, beschreibung) => console.log(titel, beschreibung)
}

ui.fenster = {
  schliessen: () => {
    $("#dshUiBlende").style.display = "none";
  },
  anzeigen: () => {
    $("#dshUiBlende").style.display = "block";
  }
}

var ui = {
  generieren: {
    monatsname: {
      lang: (monat) => {
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
          default: return false;
        }
      },
      kurz: (monat) => {
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
          default: return false;
        }
      }
    },
    tagesname: {
      lang: (tag) => {
        switch (tag) {
          case 0: return "Sonntag"
          case 1: return "Montag";
          case 2: return "Dienstag";
          case 3: return "Mittwoch";
          case 4: return "Donnerstag";
          case 5: return "Freitag";
          case 6: return "Samstag";
          case 7: return "Sonntag";
          default: return false;
        }
      },
      kurz: (tag) => {
        switch (tag) {
          case 0: return "SO"
          case 1: return "MO";
          case 2: return "DI";
          case 3: return "MI";
          case 4: return "DO";
          case 5: return "FR";
          case 6: return "SA";
          case 7: return "SO";
          default: return false;
        }
      }
    },
    fuehrendeNull: (x) => {
      if (ui.check.natZahl(x)) {
        if (x.length < 2) {
          return "0"+x;
        } else {
          return ""+x;
        }
      } else {
        return false;
      }
    },
    laden: {
      icon: () => {return "<div class=\"dshuUiLadenIcon\"><div></div><div></div><div></div><div></div></div>";},
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
      speicher: (x, gesamt) => {
        return "<span>"+ui.generieren.speicherplatz(x)+" ("+ui.generieren.prozent(x, gesamt)+"%) von "+ui.generieren.speicherplatz(gesamt)+" belegt. Frei: ("+(100-ui.generieren.prozent(x, gesamt))+"%)</span>";
      },
      zeit: (beginn, aktuell, ende) => {
        return "<span>Begonnen um "+ui.generieren.zeit(beginn)+". Zeit bis: "+ui.generieren.zeit(ende)+". Frei: ("+(100-ui.generieren.prozent(x, gesamt))+"%)</span>";
      }
    },
    prozent: (x, gesamt) => {
      return (Math.round(x/gesamt*10000)/100);
    },
    zeit: (x) => {
      var datum = new Date(x);
      return datum.getHours()+":"+datum.getMinutes()+" Uhr";
    },
    komma: (x) => {
      return (Math.round(bytes*100)/100).toString().replace('.', ',');
    },
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
  },
  check: {
    zahl: (x) => x.match(/^-?[0-9]+$/),
    natZahl: (x) => x.match(/^[0-9]+$/),
    mail: (x) => x.match(/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]{2,}$/),
  },
  datumsanzeige: {
    aktion: () => {
      var feld = $("#"+id+"Datumwahl");
      if (!an) {
        feld.style.display = "none";
      } else {
        var tag   = $("#"+id+"T").value;
        var monat = $("#"+id+"M").value;
        var jahr  = $("#"+id+"J").value;

        feld.innerHTML = ui.datumsanzeige.tageswahl.generieren(id, tag, monat, jahr);
        feld.style.display = "block";
      }
    },
    checkTag: (id) => {
      var jetzt = new Date();
      var tag = $("#"+id+"T").value;
      if (!ui.check.natZahl(tag)) {tag = jetzt.getDate();}
      var monat = $("#"+id+"M").value;
      if (!ui.check.natZahl(monat)) {monat = jetzt.getMonth()+1;}
      var jahr = $("#"+id+"J").value;
      if (!ui.check.natZahl(jahr)) {jahr = jetzt.getFullYear();}

      jetzt = new Date(jahr, monat, tag);

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
        jetzt = jetzt = new Date(2020, 07, 01, stunde, minute, sekunde);
        $("#"+id+"Sek").value = ui.generieren.fuehrendeNull(jetzt.getSeconds());
      }
      else {
        jetzt = jetzt = new Date(2020, 07, 01, stunde, minute);
      }
      $("#"+id+"Std").value = ui.generieren.fuehrendeNull(jetzt.getHours());
      $("#"+id+"Min").value = ui.generieren.fuehrendeNull(jetzt.getMinutes());
    },
    tageswahl: {
      generieren: () => {
        var code = "<table>";
        code += "<tr><th onclick=\"dshUiTageswahlGenerieren('"+id+"', '"+tag+"', '"+(monat-1)+"', '"+jahr+"')\"><i class=\"fas fa-angle-double-left\"></i></th>";
        code += "<th>"+ui.generieren.monatsname.lang(monat)+" "+jahr+"</th>";
        code += "<th colspan=\"5\" onclick=\"dshUiTageswahlGenerieren('"+id+"', '"+tag+"', '"+(monat+1)+"', '"+jahr+"')\"><i class=\"fas fa-angle-double-right\"></i></th>";
        code += "<tr>";
        code += "</tr>";
        for (var i=1; i<=7; i++) {
          code += "<td>"+ui.generieren.tagesname.kurz(i)+"</td>";
        }
        code += "</tr>";

        var erster = new Date(jahr, monat-1, 1);
        var wochentag = erster.getDay() + 1;
        var letzter = new Date (jahr, monat, 0).getDate();
        if (tag > letzer) {tag = letzer;}
        var nr = 1;
        var klassenzusatz = "";

        code += "<tr>";
        // leer auffüllen, falls nicht mit Montag begonnen wird
        for (var i=1; i<wochentag; i++) {
          code += "<td class=\"dshUiTageswahlButtonBlind\"></td>";
        }
        for (var i=wochentag; i<=7; i++) {
          if (nr == tag) {
            klassenzusatz = " dshUiTagGewaehlt";
          } else {
            klassenzusatz = "";
          }
          code += "<td class=\"dshUiTageswahlButton"+klassenzusatz+"\" onclick=\"dshUiTageswahl('"+id+"', '"+nr+"', '"+monat+"', '"+jahr+"')\">"+nr+"</td>";
          nr ++;
        }
        code += "</tr>";
        wochentag = 1;

        while (nr <= letzter) {
          if (wochentag == 8) {
            code += "</tr>";
            wochentag = 1;
          }
          if (wochentag == 1) {code += "<tr>";}
          if (nr == tag) {
            klassenzusatz = " dshUiTagGewaehlt";
          } else {
            klassenzusatz = "";
          }
          code += "<td class=\"dshUiTageswahlButton"+klassenzusatz+"\" onclick=\"dshUiTageswahl('"+id+"', '"+nr+"', '"+monat+"', '"+jahr+"')\">"+nr+"</td>";
          nr ++;
          wochentag ++;
        }

        // leer auffüllen, falls nicht am Sonntag geendet
        for (var i=wochentag; i<=7; i++) {
          code += "<td class=\"dshUiTageswahlButtonBlind\"></td>";
          nr ++;
        }
        code += "</tr>";

        code += "</table>";
        return code;
      },
      aktion: (id) => {
        var tag = $("#"+id+"T").value   = ui.generieren.fuehrendeNull(tag);
        var monat = $("#"+id+"M").value = ui.generieren.fuehrendeNull(monat);
        var jahr = $("#"+id+"J").value  = ui.generieren.fuehrendeNull(jahr);
        $("#"+id+"Datumwahl").display = none;
      }
    }
  },
  schieber: {
    aktion: (id) => {
      var wert = $("#"+id).value;
      var neuerwert = 0;
      if (wert == 0) {
        neuerwert = 1;
      }
      $("#"+id).value = neuerwerts;
      $("#"+id+"Schieber").classList.add("dshUiSchieberInnen"+neuerwert);
      $("#"+id+"Schieber").classList.remove("dshUiSchieberInnen"+wert);
    }
  },
  mail: {
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
  },
  passwort: {
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
  },
  togglegruppe: {
    aktion: (id, nr, anzahl, wert) => {
      $("#"+id).value = wert;
      $("#"+id+"KnopfId").value = nr;
      for (var i=0; i<anzahl; i++) {
        $("#"+id+"Knopf"+i).classList.remove("dshKnopfToggled");
      }
      $("#"+id+"Knopf"+nr).classList.add("dshKnopfToggled");
    }
  },
  laden: {
    balken: {
      prozent: (id, x) => {
        $("#"+id+"Innen").style.width = x+"%";
      }
    }
  },
  fenster: {
    schliessen: () => {
      $("#dshUiBlende").style.display = "none";
    },
    anzeigen: () {
      $("#dshUiBlende").style.display = "block";
    }
}

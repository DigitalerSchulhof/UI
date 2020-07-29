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
  },
  rgba2hex: (orig) => {
    if(orig === undefined || orig === null) {
      return false;
    }
    var a, isPercent,
    rgb = orig.replace(/\s/g, '').match(/^rgba?\((\d+),(\d+),(\d+),?([^,\s)]+)?/i),
    alpha = (rgb && rgb[4] || "").trim(),
    hex = rgb ?
    (rgb[1] | 1 << 8).toString(16).slice(1) +
    (rgb[2] | 1 << 8).toString(16).slice(1) +
    (rgb[3] | 1 << 8).toString(16).slice(1) : orig;

    return "#"+hex;
  },
  hex2rgba: (hex) => {
    var c;
    if(/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)) {
      c = hex.substring(1).split('');
      if(c.length == 3){
          c = [c[0], c[0], c[1], c[1], c[2], c[2]];
      }
      c = '0x'+c.join('');
      return 'rgba('+[(c>>16)&255, (c>>8)&255, c&255].join(',')+',1)';
    }
    return false;
  }
};

ui.check = {
  zahl: (x) => x.toString().match(/^-?[0-9]+$/),
  natZahl: (x) => x.toString().match(/^[0-9]+$/),
  mail: (x) => x.toString().match(/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]{2,}$/),
};

ui.datumsanzeige = {
  offen: false,
  aktion: (id, an) => {
    var feld = $("#"+id+"Datumwahl");
    if (!an) {
      feld.style.display = "none";
      ui.datumsanzeige.offen = false;
    } else {
      var tag   = $("#"+id+"T").value;
      var monat = $("#"+id+"M").value;
      var jahr  = $("#"+id+"J").value;

      ui.datumsanzeige.tageswahl.generieren(id, tag, monat, jahr).then((r) => {
        feld.innerHTML = r;
        feld.style.display = "block";
        ui.datumsanzeige.offen = true;
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
    if(isNaN(jetzt.getDate())) {
      jetzt = new Date();
    }

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
      jetzt = new Date(2020, 07, 09, stunde, minute, sekunde);
      $("#"+id+"Sek").value = ui.generieren.fuehrendeNull(jetzt.getSeconds());
    } else {
      jetzt = new Date(2020, 03, 19, stunde, minute);
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
};

document.addEventListener("click", (e) => {
  if(ui.datumsanzeige.offen) {
    for(let p of e.path) {
      if(p === document) {
        document.querySelectorAll(".dshUiDatumwahl")[0].style.display = "none";
        break;
      }
      if(p.classList.contains("dshUiDatumwahlFeld")) {
        break;
      }
    }
  }
});

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
};

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
};

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
};

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
};

ui.togglegruppe = {
  aktion: (id, nr, anzahl, wert) => {
    $("#"+id).value = wert;
    $("#"+id+"KnopfId").value = nr;
    for (var i=0; i<anzahl; i++) {
      $("#"+id+"Knopf"+i).classList.remove("dshUiToggled");
    }
    $("#"+id+"Knopf"+nr).classList.add("dshUiToggled");
  }
};

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
};

ui.laden = {
  balken: {
    prozent: (id, x) => {
      $("#"+id+"Innen").style.width = x+"%";
    }
  },
  an: (titel, beschreibung) => console.log(titel, beschreibung)
};

ui.fenster = {
  schliessen: () => {
    $("#dshUiBlende").style.display = "none";
  },
  anzeigen: () => {
    $("#dshUiBlende").style.display = "block";
  }
};

ui.meldung = {
  brclick: function (ev) {
    let t  = ev.target;
    if(!t.classList.contains("dshUiMeldung")) {
      return;
    }
    let ts = getComputedStyle(t);
    if(ev.offsetX < 0) {
      let i2 = t.querySelectorAll("i.i2.icon")[0];
      if(ts["border-right-width"] === "23px") {
        t.style["border-right-width"] = "";
        i2.style.right = "";
      } else {
        t.style["border-right-width"] = "23px";
        let r = getComputedStyle(i2).right;
        i2.style.right = (-Math.abs(r.substr(0, r.length - 2))) + "px";
      }
    }
    if(ev.offsetX > t.clientWidth) {
      let i1 = t.querySelectorAll("i.i1.icon")[0];
      if(ts["border-left-width"] === "23px") {
        t.style["border-left-width"] = "2px";
        let l = getComputedStyle(i1).left;
        i1.style.left = Math.abs(l.substr(0, l.length - 2)) + "px";
      } else {
        t.style["border-left-width"] = "";
        i1.style.left = "";
      }
    }
    if(t.classList.contains("dshUiMeldungLaden") && (ev.offsetY < 0 || ev.offsetY > t.clientHeight)) {
      if(t.style.transform === "rotateY(180deg)") {
        t.style.transform = "";
      } else {
        t.style.transform = "rotateY(180deg)";
      }
    }
  }
};

ui.farbbeispiel = {
  aktion: (t) => {
    let fb = t.closest(".dshUiFarbbeispiele");
    fb.nextSibling.style["background-color"] = t.style["background-color"] || ui.generieren.hex2rgba(t.value);
    if(t.tagName !== "INPUT") {
      fb.querySelectorAll("input[type=color]")[0].value = ui.generieren.rgba2hex(t.style["background-color"]);
    }
  }
};

window.addEventListener("resize", (e) => {
  if(document.body.clientWidth < 203) {
    for(let b of document.querySelectorAll(".dshUiFarbbeispiele")) {
      let w = (100 / b.querySelectorAll(".dshUiFarbbeispieleSchattierung").length * 3) + "%";
      for(let s of b.querySelectorAll(".dshUiFarbbeispieleSchattierung")) {
        s.style["flex-basis"] = w;
        s.classList.add("jsmod");
      }
    }
  } else if(document.body.clientWidth < 374) {
    for(let b of document.querySelectorAll(".dshUiFarbbeispiele")) {
      let w = (100 / b.querySelectorAll(".dshUiFarbbeispieleSchattierung").length * 2) + "%";
      for(let s of b.querySelectorAll(".dshUiFarbbeispieleSchattierung")) {
        s.style["flex-basis"] = w;
        s.classList.add("jsmod");
      }
    }
  } else {
    for(let s of document.querySelectorAll(".dshUiFarbbeispieleSchattierung.jsmod")) {
      s.style["flex-basis"] = "";
      s.classList.remove("jsmod");
    }
  }
});

ui.tabelle = {
  sortieren: (richtung, id, spalte) => {
    if (richtung != "ASC" && richtung != "DESC") {return;}

    // Zeilen einlesen
    var zAnzahl = $("#"+id+"ZAnzahl").value;
    var sAnzahl = $("#"+id+"SAnzahl").value;
    var umsortieren = new Array(zAnzahl);
    // initialisieren
    for (var z=0; z<zAnzahl; z++) {
      umsortieren[z] = new Array(2);
      umsortieren[z][0] = z;
      umsortieren[z][1] = $("#"+id+"Z"+z+"S"+spalte).innerHTML;
    }

    if (richtung == "ASC") {
      umsortieren.sort(ui.tabelle.aufsteigend);
    } else {
      umsortieren.sort(ui.tabelle.absteigend);
    }

    // Tabelle neu zusammenbauen
    var code = "";
    for (var z=0; z<zAnzahl; z++) {
      code += "<tr>";
      var spaltennr = 0;
      for (var s=0; s<sAnzahl; s++) {
        code += "<td id=\""+id+"Z"+z+"S"+s+"\">"+($("#"+id+"Z"+umsortieren[z][0]+"S"+s).innerHTML)+"</td>";
        spaltennr++;
      }
      code += "</tr>";
    }
    $("#"+id+"Koerper").innerHTML = code;
  },
  aufsteigend: (a, b) => {
    if (a[1] < b[1]) {
    return -1;
    }
    if (a[1] > b[1]) {
      return 1;
    }
    return 0;
  },
  absteigend: (a, b) => {
    if (a[1] > b[1]) {
    return -1;
    }
    if (a[1] < b[1]) {
      return 1;
    }
    return 0;
  }
};
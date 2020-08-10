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
    icon: (inhalt) => {
      var code = "<div class=\"dshUiLaden\">";
      code += "<div class=\"dshUiLadenIcon\"><div></div><div></div><div></div><div></div></div>";
      code += "<span class=\"dshUiLadenStatus\">"+inhalt+"...</span>";
      code += "</div>";
      return code;
    },
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
  zahl: (x)     => x.toString().match(/^-?[0-9]+$/),
  natZahl: (x)  => x.toString().match(/^[0-9]+$/),
  mail: (x)     => x.toString().match(/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]{2,}$/),
  toggle: (x)   => x.match(/^(0|1)$/)
};

ui.datumsanzeige = {
  offen: false,
  aktion: (id, an) => {
    var feld = $("#"+id+"Datumwahl");
    if (!an) {
      feld.ausblenden();
      ui.datumsanzeige.offen = false;
    } else {
      var tag   = $("#"+id+"T").getWert();
      var monat = $("#"+id+"M").getWert();
      var jahr  = $("#"+id+"J").getWert();

      ui.datumsanzeige.tageswahl.generieren(id, tag, monat, jahr).then((r) => {
        feld.setHTML(r.Code).einblenden();
        ui.datumsanzeige.offen = true;
      });
    }
  },
  monataendern: (id, tag, monat, jahr) => {
    var feld = $("#"+id+"Datumwahl");
    var datum = new Date (jahr, monat-1, tag);
    ui.datumsanzeige.tageswahl.generieren(id, datum.getDate(), datum.getMonth()+1, datum.getFullYear()).then((r) => feld.setHTML(r.Code));
  },
  checkTag: (id) => {
    var jetzt = new Date();
    var tag = $("#"+id+"T").getWert();
    if (!ui.check.natZahl(tag)) {tag = jetzt.getDate();}
    var monat = $("#"+id+"M").getWert();
    if (!ui.check.natZahl(monat)) {monat = jetzt.getMonth()+1;}
    var jahr = $("#"+id+"J").getWert();
    if (!ui.check.natZahl(jahr)) {jahr = jetzt.getFullYear();}

    jetzt = new Date(jahr, monat-1, tag);
    if(isNaN(jetzt.getDate())) {
      jetzt = new Date();
    }

    $("#"+id+"T").setWert(ui.generieren.fuehrendeNull(jetzt.getDate()));
    $("#"+id+"M").setWert(ui.generieren.fuehrendeNull(jetzt.getMonth()+1));
    $("#"+id+"J").setWert(ui.generieren.fuehrendeNull(jetzt.getFullYear()));
  },
  checkUhrzeit: (id, sekunden) => {
    var jetzt = new Date();
    var stunde = $("#"+id+"Std").getWert();
    if (!ui.check.natZahl(stunde)) {stunde = jetzt.getHours();}
    var minute = $("#"+id+"Min").getWert();
    if (!ui.check.natZahl(minute)) {minute = jetzt.getMinutes();}
    if (sekunden) {
      var sekunde = $("#"+id+"Sek").getWert();
      if (!ui.check.natZahl(sekunde)) {sekunde = jetzt.getSeconds();}
      jetzt = new Date(2020, 07, 09, stunde, minute, sekunde);
      $("#"+id+"Sek").setWert(ui.generieren.fuehrendeNull(jetzt.getSeconds()));
    } else {
      jetzt = new Date(2020, 03, 19, stunde, minute);
    }
    $("#"+id+"Std").setWert(ui.generieren.fuehrendeNull(jetzt.getHours()));
    $("#"+id+"Min").setWert(ui.generieren.fuehrendeNull(jetzt.getMinutes()));
  },
  tageswahl: {
    generieren: (id, tag, monat, jahr) => core.ajax("UI", 0, null, {id: id, tag: tag, monat: monat, jahr: jahr}),
    aktion: (id, tag, monat, jahr) => {
      var datum = new Date (jahr, monat-1, tag);
      var tag   = $("#"+id+"T").setWert(ui.generieren.fuehrendeNull(datum.getDate()));
      var monat = $("#"+id+"M").setWert(ui.generieren.fuehrendeNull(datum.getMonth()+1));
      var jahr  = $("#"+id+"J").setWert(ui.generieren.fuehrendeNull(datum.getFullYear()));
      $("#"+id+"Datumwahl").ausblenden();
    }
  }
};

document.addEventListener("click", (e) => {
  if(ui.datumsanzeige.offen) {
    let el = $(e.target);
    do {
      if(el.ist("html")) {
        $(".dshUiDatumwahl").ausblenden();
        break;
      }
      if(el.ist(".dshUiDatumwahlFeld")) {
        break;
      }
    } while((el = el.parent()));
  }
});

ui.schieber = {
  aktion: (id) => {
    var wert = $("#"+id).getWert();
    var neuerwert = 0;
    if (wert == 0) {
      neuerwert = 1;
    }
    $("#"+id).setWert(neuerwert);
    $("#"+id+"Schieber").addKlasse("dshUiSchieber"+neuerwert);
    $("#"+id+"Schieber").removeKlasse("dshUiSchieber"+wert);
  }
};

ui.toggle = {
  aktion: (id) => {
    var wert = $("#"+id).getWert();
    var neuerwert = 0;
    if (wert == 0) {
      neuerwert = 1;
    }
    $("#"+id).setWert(neuerwert);
    if(neuerwert == 1) {
      $("#"+id+"Toggle").addKlasse("dshUiToggled");
    } else {
      $("#"+id+"Toggle").removeKlasse("dshUiToggled");
    }
  }
};

ui.mail = {
  aktion: (id) => {
    var mail  = $("#"+id).getWert();
    var ok    = ui.check.mail(mail);
    $("#"+id).setKlasse(ok,  "dshUiPruefen1");
    $("#"+id).setKlasse(!ok, "dshUiPruefen0");
  }
};

ui.passwort = {
  aktion: (idvergleich, idpruefen) => {
    var vergleich = $("#"+idvergleich).getWert();
    var pruefen = $("#"+idpruefen).getWert();
    $("#"+idpruefen).setKlasse(vergleich == pruefen, "dshUiPruefen1");
    $("#"+idpruefen).setKlasse(vergleich != pruefen, "dshUiPruefen0");
  }
};

ui.togglegruppe = {
  aktion: (id, nr, anzahl, wert) => {
    $("#"+id).setWert(wert);
    $("#"+id+"KnopfId").setWert(nr);
    for (var i = 0; i < anzahl; i++) {
      $("#"+id+"Knopf"+i).removeKlasse("dshUiToggled");
    }
    $("#"+id+"Knopf"+nr).addKlasse("dshUiToggled");
  }
};

ui.reiter = {
  aktion: (id, nr, anzahl) => {
    for (var i = 0; i < anzahl; i++) {
      $("#"+id+"Koerper"+i).removeKlasse("dshUiReiterKoerperAktiv").addKlasse("dshUiReiterKoerperInaktiv");
      $("#"+id+"Kopf"+i).removeKlasse("dshUiReiterKopfAktiv").addKlasse("dshUiReiterKopfInaktiv");
    }
    $("#"+id+"Koerper"+nr).removeKlasse("dshUiReiterKoerperInaktiv").addKlasse("dshUiReiterKoerperAktiv");
    $("#"+id+"Kopf"+nr).removeKlasse("dshUiReiterKopfInaktiv").addKlasse("dshUiReiterKopfAktiv");
  }
};

ui.laden = {
  iseAn: false,
  fokusVor: null,
  autoschliessen: null,
  balken: {
    prozent: (id, x) => {
      $("#"+id+"Innen").setCss("width", x+"%");
    }
  },
  an: (titel, inhalt) => {
    $("#dshLadenFensterTitel").setHTML(titel);
    var code = ui.generieren.laden.icon(inhalt);
    $("#dshLadenFensterInhalt").setHTML(code);
    $("#dshLadenFensterAktionen").setHTML("");
    $("#dshBlende").einblenden();
    ui.laden.istAn    = true;

    let knopefe = $("#dshBlende").finde(".dshUiKnopf");
    if(knopefe.length !== 0) {
      ui.laden.fokusVor = $(document.activeElement);
      knopefe[0].focus();
    }
    $("[tabindex]").each(function () {
      if(this.parentSelector("#dshBlende").length === 0) {
        this.setAttr("tabindexAlt", this.getAttr("tabindex"));
        this.setAttr("tabindex",    "-1");
      }
    });
  },
  aendern: (titel, inhalt, aktionen) => {
    var inhalt = inhalt || "";
    var aktionen = aktionen || "";
    if (titel !== null) {
      $("#dshLadenFensterTitel").setHTML(titel);
    }
    $("#dshLadenFensterInhalt").setHTML(inhalt);
    $("#dshLadenFensterAktionen").setHTML(aktionen);
    $("#dshBlende").einblenden();
    ui.laden.istAn = true;

    let dae = $(document.activeElement);
    if(dae.parentSelector("#dshBlende").length === 0) {
      ui.laden.fokusVor = dae;
      let knopefe = $("#dshBlende").finde(".dshUiKnopf");
      if(knopefe.length !== 0) {
        ui.laden.fokusVor = $(document.activeElement);
        knopefe[0].focus();
      }
    }
    $("[tabindex]:not([tabindexAlt])").each(function () {
      if(this.parentSelector("#dshBlende").length === 0) {
        this.setAttr("tabindexAlt", this.getAttr("tabindex"));
        this.setAttr("tabindex",    "-1");
      }
    });
  },
  aus: () => {
    $("#dshLadenFensterTitel", "#dshLadenFensterInhal", "#dshLadenFensterAktionen").setHTML("");
    $("#dshBlende").ausblenden();

    if(ui.autoschliessen !== null) {
      clearTimeout(ui.autoschliessen);
      ui.autoschliessen = null;
    }

    ui.laden.istAn = false;
    if(ui.laden.fokusVor !== null) {
      ui.laden.fokusVor[0].focus();
      if(ui.laden.fokusVor.ist("input")) {
        ui.laden.fokusVor[0].select();
      }
    }
    ui.laden.fokusVor = null;
    $("[tabindexAlt]").each(function () {
      this.setAttr("tabindex",    this.getAttr("tabindexAlt"));
      this.setAttr("tabindexAlt", "");
    })
  },
  meldung: (modul, id, laden, parameter) => {
    var laden = laden || ["Laden", "Bitte warten"];
    var parameter = parameter || null;
    if (!Array.isArray(laden)) {
      var laden = ["Laden", "Bitte warten"];
    }
    core.ajax("UI", 1, laden, {meldemodul: modul, meldeid: id, meldeparameter:parameter});
  },
  komponente: (komponenteninfo) => {
    return core.ajax("UI", 2, null, komponenteninfo);
  }
};

document.addEventListener("keydown", (e) => {
  if(ui.laden.istAn && [37, 39].includes(e.keyCode)) {
    let ae = $(document.activeElement);
    if(ae.ist("#dshLaden #dshLadenFensterAktionen>.dshUiKnopf")) {
      if(e.keyCode === 37) {
        let vor = ae.siblingVor();
        if(vor.length) {
          vor[0].focus();
        }
      } else if(e.keyCode === 39) {
        let nach = ae.siblingNach();
        if(nach.length) {
          nach[0].focus();
        }
      }
    }
  }
});

ui.fenster = {
  schiebend: null,
  maxz: 10000,
  schliessen: (id) => {
    var fenster = document.getElementById(id);
    fenster.parentNode.removeChild(fenster);
  },
  anzeigen: (code) => {
    var neu = document.createElement("DIV");
    neu.innerHTML = code;
    $(neu).kinder().setCss("z-index", ui.fenster.maxz++);
    document.getElementById("dshFenstersammler").appendChild(neu);
  }
};

document.addEventListener("mousedown", (e) => {
  if($(e.target).parentSelector(".dshUiFenster").existiert()) {
    $(e.target).parentSelector(".dshUiFenster").setCss("z-index", ++ui.fenster.maxz);
    if(e.which === 1 && $(e.target).ist(".dshUiFenster:not(#dshLaden) .dshUiFensterTitelzeile") || $(e.target).parentSelector(".dshUiFenster:not(#dshLaden) .dshUiFensterTitelzeile").length > 0) {
      let f = $(e.target).parentSelector(".dshUiFenster");
      f.addKlasse("dshUiFensterSchiebend");
      ui.fenster.schiebend = f;
      ui.fenster.schiebend.mx = parseInt((f.getCss("transform").match(/translateX\(((?:-)?\d+)px\)/) || ["hi","0"])[1]);
      ui.fenster.schiebend.my = parseInt((f.getCss("transform").match(/translateY\(((?:-)?\d+)px\)/) || ["hi","0"])[1]);
    }
  }
});

document.addEventListener("mousemove", (e) => {
  if(ui.fenster.schiebend !== null) {
    ui.fenster.schiebend.mx = ui.fenster.schiebend.mx+e.movementX;
    ui.fenster.schiebend.my = ui.fenster.schiebend.my+e.movementY;
    ui.fenster.schiebend.setCss("transform", "translateX("+ui.fenster.schiebend.mx+"px) translateY("+ui.fenster.schiebend.my+"px)");
  }
});

document.addEventListener("mouseup", (e) => {
  if(ui.fenster.schiebend !== null) {
    ui.fenster.schiebend.removeKlasse("dshUiFensterSchiebend");
    ui.fenster.schiebend = null;
  }
});

ui.meldung = {
  brclick: (ev) => {
    let t  = $(ev.target);
    if(!t.ist(".dshUiMeldung")) {
      return;
    }
    let ts = getComputedStyle(t[0]);
    if(ev.offsetX < 0) {
      let i2          = t.finde("i.i2.dshUiIcon");
      let fehlercodes = t.finde(".dshFehlercode");
      if(ts["border-right-width"] === "23px") {
        t.setCss("border-right-width", "");
        i2.setCss("right", "");
        fehlercodes.setCss("right", "");
      } else {
        t.setCss("border-right-width", "23px");
        let r = getComputedStyle(i2[0]).right;
        i2.setCss("right", (-Math.abs(r.substr(0, r.length - 2))) + "px");
        fehlercodes.setCss("right", "-23px");
      }
    }
    if(ev.offsetX > t[0].clientWidth) {
      let i1 = t.finde("i.i1.dshUiIcon");
      if(ts["border-left-width"] === "23px") {
        t.setCss("border-left-width", "2px");
        let l = getComputedStyle(i1[0]).left;
        i1.setCss("left", Math.abs(l.substr(0, l.length - 2)) + "px");
      } else {
        t.setCss("border-left-width", "");
        i1.setCss("left", "");
      }
    }
    if(t.ist(".dshUiMeldungLaden") && (ev.offsetY < 0 || ev.offsetY > t[0].clientHeight)) {
      if(t.getCss("transform") === "rotateY(180deg)") {
        t.setCss("transform", "");
      } else {
        t.setCss("transform", "rotateY(180deg)");
      }
    }
    t.finde(".dshUiFehlermeldung").toggleCss("opacity", "1");
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
    $(".dshUiFarbbeispiele").each((b) => {
      let s = b.finde(".dshUiFarbbeispieleSchattierung");
      let w = (100 / s.length * 3) + "%";
      s.setCss("flex-basis", w).addKlasse("jsmod");
    });
  } else if(document.body.clientWidth < 374) {
    $(".dshUiFarbbeispiele").each((b) => {
      let s = b.finde(".dshUiFarbbeispieleSchattierung");
      let w = (100 / s.length * 2) + "%";
      s.setCss("flex-basis", w).addKlasse("jsmod");
    });
  } else {
    $(".dshUiFarbbeispieleSchattierung.jsmod").setCss("flex-basis", "").removeKlasse("jsmod");
  }
});

ui.tabelle = {
  sortieren: (richtung, id, spalte) => {
    if (richtung != "ASC" && richtung != "DESC") {return;}

    // Zeilen einlesen
    var zAnzahl = $("#"+id+"ZAnzahl").getWert();
    var sAnzahl = $("#"+id+"SAnzahl").getWert();
    var umsortieren = new Array(zAnzahl);
    // initialisieren
    for (var z=0; z<zAnzahl; z++) {
      umsortieren[z] = new Array(2);
      umsortieren[z][0] = z;
      umsortieren[z][1] = $("#"+id+"Z"+z+"S"+spalte).getHTML();
    }

    if (richtung == "ASC") {
      umsortieren.sort(ui.tabelle.aufsteigend);
    } else {
      umsortieren.sort(ui.tabelle.absteigend);
    }
    var neu = {};
    // Tabelle neu zusammenbauen
    for (var z=0; z<zAnzahl; z++) {
      for (var s=0; s<sAnzahl; s++) {
        neu["#"+id+"Z"+z+"S"+s] = $("#"+id+"Z"+umsortieren[z][0]+"S"+s).getHTML();
      }
    }
    for(let s in neu) {
      $(s).setHTML(neu[s]);
    }
    // $("#"+id+"Koerper").setHTML(code);
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

import $, { eQuery } from "ts/eQuery";
import ajax, { AjaxAntwort, AnfrageErfolg, AntwortCode } from "ts/ajax";
import { AnfrageAntworten } from "ts/AnfrageAntworten";
import { scriptAn } from "ts/laden";

type monatnameLang = "Januar" | "Februar" | "März" | "April" | "Mai" | "Juni" | "Juli" | "August" | "September" | "Oktober" | "November" | "Dezember";
type monatnameKurz = "JAN" | "FEB" | "MÄR" | "APR" | "MAI" | "JUN" | "JUL" | "AUG" | "SEP" | "OKT" | "NOV" | "DEZ"

type tagnameLang = "Montag" | "Dienstag" | "Mittwoch" | "Donnerstag" | "Freitag" | "Samstag" | "Sonntag";
type tagnameKurz = "MO" | "DI" | "MI" | "DO" | "FR" | "SA" | "SO";


const ui = {
  check: {
    zahl: (x: number | string): boolean => x.toString().match(/^-?[0-9]+$/) !== null,
    natZahl: (x: number | string): boolean => x.toString().match(/^[0-9]+$/) !== null,
    mail: (x: string): boolean => x.match(/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]{2,}$/) !== null,
    toggle: (x: string): boolean => x.match(/^(0|1)$/) !== null,
  },
  generieren: {
    monatname: {
      lang: (monat: number): monatnameLang | undefined => {
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
          default: return undefined;
        }
      },
      kurz: (monat: number): monatnameKurz | undefined => {
        switch (monat) {
          case 1: return "JAN";
          case 2: return "FEB";
          case 3: return "MÄR";
          case 4: return "APR";
          case 5: return "MAI";
          case 6: return "JUN";
          case 7: return "JUL";
          case 8: return "AUG";
          case 9: return "SEP";
          case 10: return "OKT";
          case 11: return "NOV";
          case 12: return "DEZ";
          default: return undefined;
        }
      }
    },
    tagname: {
      lang: (tag: number): tagnameLang | undefined => {
        switch (tag) {
          case 0: return "Sonntag";
          case 1: return "Montag";
          case 2: return "Dienstag";
          case 3: return "Mittwoch";
          case 4: return "Donnerstag";
          case 5: return "Freitag";
          case 6: return "Samstag";
          case 7: return "Sonntag";
          default: return undefined;
        }
      },
      kurz: (tag: number): tagnameKurz | undefined => {
        switch (tag) {
          case 0: return "SO";
          case 1: return "MO";
          case 2: return "DI";
          case 3: return "MI";
          case 4: return "DO";
          case 5: return "FR";
          case 6: return "SA";
          case 7: return "SO";
          default: return undefined;
        }
      }
    },
    speicherplatz: {
      lang: (bytes: number): string | "Zu viel" => {
        const einheiten = ["Byte", "Kilobyte", "Megabyte", "Gigabyte", "Terabyte", "Petabyte", "Exabyte"];
        for (const e of einheiten) {
          if (bytes / 1000 < 1) {
            return ui.generieren.komma((Math.round(bytes * 100) / 100)) + " " + e;
          }
          bytes = bytes / 1000;
        }
        return "Zu viel"; // That's what she said!
      },
      kurz: (bytes: number): string | "Zu viel" => {
        const einheiten = ["B", "KB", "MB", "GB", "TB", "PB", "EB"];
        for (const e of einheiten) {
          if (bytes / 1000 < 1) {
            return ui.generieren.komma((Math.round(bytes * 100) / 100)) + " " + e;
          }
          bytes = bytes / 1000;
        }
        return "Zu viel";
      }
    },
    fuehrendeNull: (x: string | number): string | undefined => {
      if (ui.check.natZahl(x)) {
        if ((x.toString()).length < 2) {
          return "0" + x;
        } else {
          return "" + x;
        }
      } else {
        return undefined;
      }
    },
    prozent: (x: number, gesamt: number): number => Math.round(x / gesamt * 10000) / 100,
    zeit: (x: number | Date): string => {
      let datum: Date;
      if (typeof x === "number") {
        datum = new Date(x);
      } else {
        datum = x;
      }
      return datum.getHours() + ":" + datum.getMinutes() + " Uhr";
    },
    minuten: (x: number): number => Math.floor((x / 1000) / 60),
    komma: (x: number): string => (Math.round(x * 100) / 100).toString().replace(".", ","),
    rgba2hex: (rgba: string): string | undefined => {
      const rgb = rgba.replace(/\s/g, "").match(/^rgba?\((\d+),(\d+),(\d+),?([^,\s)]+)?/i);
      if (rgb === null) {
        return undefined;
      }
      return "#" +
        (parseInt(rgb[1]) | 1 << 8).toString(16).slice(1) +
        (parseInt(rgb[2]) | 1 << 8).toString(16).slice(1) +
        (parseInt(rgb[3]) | 1 << 8).toString(16).slice(1);
    },
    hex2rgba: (hex: string): string | undefined => {
      let c;
      if (/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)) {
        c = hex.substring(1).split("");
        if (c.length == 3) {
          c = [c[0], c[0], c[1], c[1], c[2], c[2]];
        }
        c = "0x" + c.join("");
        return "rgba(" + [(parseInt(c) >> 16) & 255, (parseInt(c) >> 8) & 255, parseInt(c) & 255].join(",") + ",1)";
      }
      return undefined;
    },
    laden: {
      icon: (inhalt: string): string => {
        let code = "<div class=\"dshUiLaden\">";
        code += "<div class=\"dshUiLadenIcon\"><div></div><div></div><div></div><div></div></div>";
        code += "<span class=\"dshUiLadenStatus\">" + inhalt + "...</span>";
        code += "</div>";
        return code;
      },
      balken: {
        speicher: (id: string, belegt: number, gesamt: number, art: string): string => {
          belegt = belegt || null;
          gesamt = gesamt || null;
          art = art || null;
          let zusatzklasse = "";
          if (art !== null) {
            zusatzklasse = " dshUiLadenBalken" + art;
          }
          let code = "<div id=\"" + id + "\" class=\"dshUiLadenBalkenAussen" + zusatzklasse + "\"><div class=\"dshUiLadenBalkenInnen\"></div></div>";
          if ((belegt !== null) && (gesamt != null)) {
            code += "<p class=\"dshUiLadenErklaerung\">" + ui.generieren.laden.speicher(belegt, gesamt) + "</p>";
          }
          return code;
        },
        zeit: (id: string, beginn: number, aktuell: number, ende: number, art: string): string => {
          beginn = beginn || null;
          aktuell = aktuell || null;
          ende = ende || null;
          art = art || null;
          let zusatzklasse = "";
          if (art !== null) {
            zusatzklasse = " dshUiLadenBalken" + art;
          }
          let code = "<div id=\"" + id + "\" class=\"dshUiLadenBalkenAussen" + zusatzklasse + "\"><div class=\"dshUiLadenBalkenInnen\"></div></div>";
          if ((beginn !== null) && (aktuell != null) && (ende != null)) {
            code += "<p class=\"dshUiLadenErklaerung\">" + ui.generieren.laden.zeit(beginn, aktuell, ende) + "</p>";
          }
          return code;
        }
      },
      speicher: (x: number, gesamt: number): string => "<span>" + ui.generieren.speicherplatz.kurz(x) + " (" + ui.generieren.prozent(x, gesamt) + "%) von " + ui.generieren.speicherplatz.kurz(gesamt) + " belegt. Frei: (" + (100 - ui.generieren.prozent(x, gesamt)) + "%)</span>",
      // TODO: "Frei:" ?
      zeit: (beginn: number, aktuell: number, ende: number): string => "<span>Begonnen um " + ui.generieren.zeit(beginn) + ". Zeit bis: " + ui.generieren.zeit(ende) + ". Frei: (" + (100 - ui.generieren.prozent(aktuell, ende)) + "%)</span>"
    }
  },
  // meldungen: {
  //   erfolg: (t: string, b: string): CodeAntwort => ui.meldungen.meldung("Erfolg", t, b),
  //   fehler: (t: string, b: string): CodeAntwort => ui.meldungen.meldung("Fehler", t, b),
  //   warnung: (t: string, b: string): CodeAntwort => ui.meldungen.meldung("Warnung", t, b),
  //   information: (t: string, b: string): CodeAntwort => ui.meldungen.meldung("Information", t, b),
  //   laden: (t: string, b: string): CodeAntwort => ui.meldungen.meldung("Laden", t, b),
  //   eingeschraenkt: (t: string, b: string): CodeAntwort => ui.meldungen.meldung("Eingeschraenkt", t, b),
  //   gesperrt: (t: string, b: string): CodeAntwort => ui.meldungen.meldung("Gesperrt", t, b),
  //   meldung: (art: string, titel: string, inhalt: string, icon?: string): CodeAntwort => ajax("UI", 1, false, { art: art, titel: titel, inhalt: inhalt, icon: icon })
  // },
  elemente: {
    togglegruppe: {
      aktion: (id: string, nr: number, anzahl: number, wert: string): void => {
        $("#" + id).setWert(wert);
        $("#" + id + "KnopfId").setWert(nr.toString());
        for (let i = 0; i < anzahl; i++) {
          $("#" + id + "Knopf" + i).removeKlasse("dshUiToggled");
        }
        $("#" + id + "Knopf" + nr).addKlasse("dshUiToggled");
      }
    },
    toggle: {
      aktion: (id: string): void => {
        const wert = $("#" + id).getWert();
        const neuerwert = (parseInt(wert) - 1).toString();
        $("#" + id).setWert(neuerwert);
        if (neuerwert === "1") {
          $("#" + id + "Toggle").addKlasse("dshUiToggled");
        } else {
          $("#" + id + "Toggle").removeKlasse("dshUiToggled");
        }
      }
    },
    tabelle: {
      sortieren: (id: string, richtung?: "ASC" | "DESC", spalte?: string): void => {
        const tabelle = $("#" + id);
        if (tabelle.existiert()) {
          const feld = $("#" + id + "Ladebereich");
          const sortSeite = $("#" + id + "Seite").getWert();
          const sortDatenproseite = $("#" + id + "DatenProSeite").getWert();
          const sortRichtung = richtung || $("#" + id + "SortierenRichtung").getWert();
          const sortSpalte = spalte || $("#" + id + "SortierenSpalte").getWert();
          const i = feld.kinder(".dshUiTabelleI");
          i.addKlasse("dshUiTabelleLaedt");
          if (!i.kinder("table").existiert()) {
            console.error("Die ID »" + id + "« ist keine Tabelle", id);
            return;
          }
          const s = i.kinder("table").getAttr("data-sortierfunktion").split(".");

          // String zu Funktion
          let sortierfunktion = window;
          while (s.length > 0) {
            // @ts-ignore
            sortierfunktion = sortierfunktion[s.shift()];
          }
          // @ts-ignore
          sortierfunktion({ sortSeite: sortSeite, sortDatenproseite: sortDatenproseite, sortRichtung: sortRichtung, sortSpalte: sortSpalte }, id).then((r) => {
            if (r.Code) {
              feld.setHTML(r.Code);
              scriptAn(feld);
            }
          });
        }
      }
    },
    schieber: {
      aktion: (id: string): void => {
        const wert = $("#" + id).getWert();
        const neuerwert = (parseInt(wert) - 1).toString();
        $("#" + id).setWert(neuerwert);
        $("#" + id + "Schieber").addKlasse("dshUiSchieber" + neuerwert);
        $("#" + id + "Schieber").removeKlasse("dshUiSchieber" + wert);
      }
    },
    reiter: {
      aktion: (id: string, nr: number, anzahl: number): void => {
        for (let i = 0; i < anzahl; i++) {
          $("#" + id + "Koerper" + i).removeKlasse("dshUiReiterKoerperAktiv").addKlasse("dshUiReiterKoerperInaktiv");
          $("#" + id + "Kopf" + i).removeKlasse("dshUiReiterKopfAktiv").addKlasse("dshUiReiterKopfInaktiv");
        }
        $("#" + id + "Koerper" + nr).removeKlasse("dshUiReiterKoerperInaktiv").addKlasse("dshUiReiterKoerperAktiv");
        $("#" + id + "Kopf" + nr).removeKlasse("dshUiReiterKopfInaktiv").addKlasse("dshUiReiterKopfAktiv");
      }
    },
    passwort: {
      aktion: (idvergleich: string, idpruefen: string): void => {
        const vergleich = $("#" + idvergleich).getWert();
        const pruefen = $("#" + idpruefen).getWert();
        $("#" + idpruefen).setKlasse(vergleich == pruefen, "dshUiPruefen1");
        $("#" + idpruefen).setKlasse(vergleich != pruefen, "dshUiPruefen0");
      }
    },
    meldung: {
      aktion: (ev: MouseEvent): void => {
        const t = $(ev.target);
        if (!t.ist(".dshUiMeldung")) {
          return;
        }
        const ts = getComputedStyle(t[0]);
        if (ev.offsetX < 0) {
          if (t.hatAttr("brleft")) {
            new Function(t.getAttr("brleft")).apply(t);
          } else {
            const i2 = t.finde("i.i2.dshUiIcon");
            const fehlercodes = t.finde(".dshFehlercode");
            if ((ts as typeof ts & { "border-right-width": string })["border-right-width"] === "23px") {
              t.setCss("border-right-width", "");
              i2.setCss("right", "");
              fehlercodes.setCss("right", "");
            } else {
              t.setCss("border-right-width", "23px");
              const r = getComputedStyle(i2[0]).right;
              i2.setCss("right", (-Math.abs(parseInt(r.substr(0, r.length - 2)))) + "px");
              fehlercodes.setCss("right", "-23px");
            }
          }
        }
        if (ev.offsetX > t[0].clientWidth) {
          if (t.hatAttr("brright")) {
            new Function(t.getAttr("brright")).apply(t);
          } else {
            const i1 = t.finde("i.i1.dshUiIcon");
            if ((ts as typeof ts & { "border-left-width": string })["border-left-width"] === "23px") {
              t.setCss("border-left-width", "2px");
              const l = getComputedStyle(i1[0]).left;
              i1.setCss("left", Math.abs(parseInt(l.substr(0, l.length - 2))) + "px");
            } else {
              t.setCss("border-left-width", "");
              i1.setCss("left", "");
            }
          }
        }
        if (t.ist(".dshUiMeldungLaden") && (ev.offsetY < 0 || ev.offsetY > t[0].clientHeight)) {
          if (t.getCss("transform") === "rotateY(180deg)") {
            t.setCss("transform", "");
          } else {
            t.setCss("transform", "rotateY(180deg)");
          }
        }
        t.finde(".dshUiFehlermeldung").toggleCss("opacity", "1");
      }
    },
    mail: {
      aktion: (id: string): void => {
        const mail = $("#" + id).getWert();
        const ok = ui.check.mail(mail);
        $("#" + id).setKlasse(ok, "dshUiPruefen1");
        $("#" + id).setKlasse(!ok, "dshUiPruefen0");
      }
    },
    laden: {
      istAn: false as boolean,
      fokusVor: null as eQuery,
      autoschliessen: null as number,

      setFokusVor: (fokusVor: eQuery): void => {
        ui.elemente.laden.fokusVor = fokusVor;
      },

      an: (titel: string, inhalt: string): void => {
        if (titel !== null) {
          $("#dshLadenFensterTitel").setHTML(titel);
        }
        const code = ui.generieren.laden.icon(inhalt);
        if (inhalt !== null) {
          $("#dshLadenFensterInhalt").setHTML(code);
        }
        $("#dshLadenFensterAktionen").setHTML("");
        $("#dshBlende").einblenden();
        ui.elemente.laden.istAn = true;
        $("#dshLaden")[0].offsetHeight;
        $("#dshLaden").setCss("opacity", "1");
        const knopefe = $("#dshBlende").finde(".dshUiKnopf");
        if (knopefe.length !== 0) {
          ui.elemente.laden.fokusVor = $(document.activeElement);
          knopefe[knopefe.length - 1].focus();
        }
        $("[tabindex]:not([tabindexAlt])").each(function () {
          if (this.parent("#dshBlende").length === 0) {
            this.setAttr("tabindexAlt", this.getAttr("tabindex"));
            this.setAttr("tabindex", "-1");
          }
        });
      },

      aendern: (titel: string, inhalt: string, aktionen?: string): void => {
        inhalt = inhalt || "";
        aktionen = aktionen || "";
        if (titel !== null) {
          $("#dshLadenFensterTitel").setHTML(titel);
        }
        $("#dshLadenFensterInhalt").setHTML(inhalt);
        $("#dshLadenFensterAktionen").setHTML(aktionen);
        $("#dshBlende").einblenden();
        ui.elemente.laden.istAn = true;

        const dae = $(document.activeElement);
        if (dae.parent("#dshBlende").length === 0) {
          ui.elemente.laden.fokusVor = dae;
          const knopefe = $("#dshBlende").finde(".dshUiKnopf");
          if (knopefe.length !== 0) {
            ui.elemente.laden.fokusVor = $(document.activeElement);
            knopefe[knopefe.length - 1].focus();
          }
        }
        $("[tabindex]:not([tabindexAlt])").each(function () {
          if (this.parent("#dshBlende").length === 0) {
            this.setAttr("tabindexAlt", this.getAttr("tabindex"));
            this.setAttr("tabindex", "-1");
          }
        });
      },

      aus: (): void => {
        $("#dshLadenFensterTitel", "#dshLadenFensterInhal", "#dshLadenFensterAktionen").setHTML("");
        $("#dshBlende").ausblenden();

        if (ui.elemente.laden.autoschliessen !== null) {
          clearTimeout(ui.elemente.laden.autoschliessen);
          ui.elemente.laden.autoschliessen = null;
        }

        ui.elemente.laden.istAn = false;
        if (ui.elemente.laden.fokusVor !== null) {
          ui.elemente.laden.fokusVor[0].focus();
          if (ui.elemente.laden.fokusVor.ist("input")) {
            (ui.elemente.laden.fokusVor[0] as HTMLInputElement).select();
          }
        }
        ui.elemente.laden.fokusVor = null;
        $("[tabindexAlt]").each(function () {
          this.setAttr("tabindex", this.getAttr("tabindexAlt"));
          this.setAttr("tabindexAlt", null);
        });
      },

      meldung: (modul: string, id: number, laden?: string, parameter?: { [key: string]: any }): void => {
        parameter = parameter || {};
        ajax("UI", 1, { titel: laden, beschreibung: "Die Meldung wird geladen" }, { meldemodul: modul, meldeid: id, meldeparameter: parameter }).then((r) => ui.elemente.laden.aendern(null, r.Meldung, r.Knoepfe));
      },

      komponente: (komponenteninfo: { [key: string]: any; }): AjaxAntwort<AnfrageAntworten["UI"][2]> => ajax("UI", 2, false, komponenteninfo),
    },
    formular: {
      anzeigenwenn: (id: string, vergleichswert: string, klasse: string): void => {
        const wert = $("#" + id).getWert();
        const felder = $("." + klasse);
        if (wert == vergleichswert) {
          felder.einblenden("table-row");
        } else {
          felder.ausblenden();
        }
      }
    },
    fenster: {
      schiebend: null as eQuery & { my: number, mx: number },
      maxz: 100000,
      ladend: 0,
      schliessen: (id: string): void => {
        const fenster = document.getElementById(id);
        fenster.parentNode.removeChild(fenster);
      },
      minimieren: (id: string): void => {
        $("#" + id).toggleKlasse("dshUiFensterMinimiert");
        const ae = $(document.activeElement);
        if (ae.parent(".dshUiFenster").existiert()) {
          ae[0].blur();
        }
      },
      anzeigen: (code: string, ueberschreiben: boolean): void => {
        const fenster = eQuery.parse(code);
        const fensterid = fenster.getID();
        if (ueberschreiben && $("#" + fensterid).existiert()) {
          const f = $("#" + fensterid);

          fenster.setCss(f.getCss(["top", "left", "opacity"]));
          f.ersetzen(fenster[0]);
        }
        if (!$("#" + fensterid).existiert()) {
          let top = 30 + window.pageYOffset, left = 30;
          while ($(".dshUiFenster").filter(e => e.getCss("top") == top + "px" && e.getCss("left") == left + "px").existiert()) {
            top += 10;
            left += 10;
          }
          fenster.setCss({ top: top + "px", left: left + "px" });
          $("#dshFenstersammler").anhaengen(fenster);
          fenster[0].offsetHeight;
          fenster.setCss("opacity", "1");
          scriptAn(fenster);
        }
        ui.elemente.laden.setFokusVor(null);
        $("#" + fensterid).setCss("z-index", (++ui.elemente.fenster.maxz).toString())[0].focus();
        if (--ui.elemente.fenster.ladend === 0) {
          ui.elemente.fenster.ladesymbol(false);
        }
      },
      laden: <M extends keyof AnfrageAntworten, Z extends keyof AnfrageAntworten[M], A extends AnfrageAntworten[M][Z] & AntwortCode>(modul: M, ziel: Z, daten: { [key: string]: any; }, meldung?: number | { modul: string; meldung: number; }, host?: string | string[], ueberschreiben?: boolean): Promise<void | AnfrageErfolg & A> => {
        ui.elemente.fenster.ladend++;
        ui.elemente.fenster.ladesymbol(true);
        return ajax<M, Z, A>(modul, ziel, null, daten, meldung, host).then((r) => {
          ui.elemente.fenster.anzeigen(r.Code, ueberschreiben);
        });
      },
      ladesymbol: (an: boolean): void => {
        if (an) {
          $("#dshUiFensterLadesymbol").setCss("bottom", "0");
        } else {
          $("#dshUiFensterLadesymbol").setCss("bottom", "");
        }
      }
    },
    farbbeispiel: {
      aktion: (t: HTMLElement): void => {
        const fb = t.closest(".dshUiFarbbeispiele");
        if (t.tagName !== "INPUT") {
          fb.querySelectorAll<HTMLInputElement>("input[type=color]")[0].value = ui.generieren.rgba2hex((t.style as typeof t.style & { "background-color": string })["background-color"]);
        }
      }
    },
    datumsanzeige: {
      offen: false as boolean,
      aktion: (id: string, an: boolean): void => {
        const feld = $("#" + id + "Datumwahl");
        if (!an) {
          feld.ausblenden();
          ui.elemente.datumsanzeige.offen = false;
        } else {
          const tag = parseInt($("#" + id + "T").getWert());
          const monat = parseInt($("#" + id + "M").getWert());
          const jahr = parseInt($("#" + id + "J").getWert());

          ui.elemente.datumsanzeige.tageswahl.generieren(id, tag, monat, jahr).then((r) => {
            feld.setHTML(r.Code).einblenden();
            ui.elemente.datumsanzeige.offen = true;
          });
        }
      },
      monataendern: (id: string, tag: number, monat: number, jahr: number): void => {
        const feld = $("#" + id + "Datumwahl");
        const datum = new Date(jahr, monat - 1, tag);
        ui.elemente.datumsanzeige.tageswahl.generieren(id, datum.getDate(), datum.getMonth() + 1, datum.getFullYear()).then((r) => feld.setHTML(r.Code));
      },
      checkTag: (id: string): void => {
        const jetzt = new Date();
        let dateTag: number, dateMonat: number, dateJahr: number;

        const tag = $("#" + id + "T").getWert();
        const monat = $("#" + id + "M").getWert();
        const jahr = $("#" + id + "J").getWert();


        if (tag !== undefined && ui.check.natZahl(tag)) {
          dateTag = parseInt(tag);
        } else {
          dateTag = jetzt.getDate();
        }
        if (monat !== undefined && ui.check.natZahl(monat)) {
          dateMonat = parseInt(monat);
        } else {
          dateMonat = jetzt.getMonth() + 1;
        }
        if (jahr !== undefined && ui.check.natZahl(jahr)) {
          dateJahr = parseInt(jahr);
        } else {
          dateJahr = jetzt.getFullYear();
        }

        let date = new Date(dateJahr, dateMonat - 1, dateTag);
        if (isNaN(date.getDate())) {
          date = new Date();
        }

        $("#" + id + "T").setWert(ui.generieren.fuehrendeNull(jetzt.getDate()));
        $("#" + id + "M").setWert(ui.generieren.fuehrendeNull(jetzt.getMonth() + 1));
        $("#" + id + "J").setWert(ui.generieren.fuehrendeNull(jetzt.getFullYear()));
      },
      checkUhrzeit: (id: string, sekunden: boolean): void => {
        const jetzt = new Date();
        let dateStunde: number, dateMinute: number;

        const stunde = $("#" + id + "Std").getWert();
        const minute = $("#" + id + "Min").getWert();

        if (stunde !== undefined && ui.check.natZahl(stunde)) {
          dateStunde = parseInt(stunde);
        } else {
          dateStunde = jetzt.getHours();
        }
        if (minute !== undefined && ui.check.natZahl(minute)) {
          dateMinute = parseInt(minute);
        } else {
          dateMinute = jetzt.getMinutes();
        }

        let date: Date;
        if (sekunden) {
          let dateSekunde: number;
          const sekunde = $("#" + id + "Sek").getWert();
          if (sekunde !== undefined && ui.check.natZahl(sekunde)) {
            dateSekunde = parseInt(sekunde);
          } else {
            dateSekunde = jetzt.getSeconds();
          }

          date = new Date(2020, 7, 9, dateStunde, dateMinute, dateSekunde);
          $("#" + id + "Sek").setWert(ui.generieren.fuehrendeNull(date.getSeconds()));
        } else {
          date = new Date(2020, 3, 19, dateStunde, dateMinute);
        }
        $("#" + id + "Std").setWert(ui.generieren.fuehrendeNull(jetzt.getHours()));
        $("#" + id + "Min").setWert(ui.generieren.fuehrendeNull(jetzt.getMinutes()));
      },

      tageswahl: {
        generieren: (id: string, tag: number, monat: number, jahr: number): AjaxAntwort<AnfrageAntworten["UI"][0]> => ajax("UI", 0, false, { id: id, tag: tag, monat: monat, jahr: jahr }),
        aktion: (id: string, tag: number, monat: number, jahr: number): void => {
          const datum = new Date(jahr, monat - 1, tag);
          $("#" + id + "T").setWert(ui.generieren.fuehrendeNull(datum.getDate()));
          $("#" + id + "M").setWert(ui.generieren.fuehrendeNull(datum.getMonth() + 1));
          $("#" + id + "J").setWert(ui.generieren.fuehrendeNull(datum.getFullYear()));
          $("#" + id + "Datumwahl").ausblenden();
        }
      },
    }
  }
};

export default ui;


// Fenster
document.addEventListener("keydown", (e) => {
  if ([27].includes(e.keyCode)) {
    const ae = $(document.activeElement);
    if (ae.ist(".dshUiFenster")) {
      ui.elemente.fenster.schliessen(ae.getID());
    }
  }
});

document.addEventListener("mousedown", (e) => {
  if ($(e.target).parent(".dshUiFenster").existiert()) {
    $(e.target).parent(".dshUiFenster").setCss("z-index", (++ui.elemente.fenster.maxz).toString());
    if (e.which === 1 && ($(e.target).ist(".dshUiFenster:not(#dshLaden) .dshUiFensterTitelzeile") || $(e.target).parent(".dshUiFenster:not(#dshLaden) .dshUiFensterTitelzeile").existiert())) {
      const f = $(e.target).parent(".dshUiFenster");
      f.addKlasse("dshUiFensterSchiebend");
      ui.elemente.fenster.schiebend = f as eQuery & { my: number, mx: number };
      ui.elemente.fenster.schiebend.my = parseInt(f.getCss("top"));
      ui.elemente.fenster.schiebend.mx = parseInt(f.getCss("left"));
    }
  }
});

document.addEventListener("mousemove", (e) => {
  if (ui.elemente.fenster.schiebend !== null) {
    ui.elemente.fenster.schiebend.mx += e.movementX;
    ui.elemente.fenster.schiebend.my += e.movementY;
    ui.elemente.fenster.schiebend.setCss({ top: ui.elemente.fenster.schiebend.my + "px", left: ui.elemente.fenster.schiebend.mx + "px" });
  }
});

document.addEventListener("mouseup", () => {
  if (ui.elemente.fenster.schiebend !== null) {
    ui.elemente.fenster.schiebend.removeKlasse("dshUiFensterSchiebend");
    ui.elemente.fenster.schiebend = null;
  }
});

// Farbbeispiel
window.addEventListener("resize", () => {
  if (document.body.clientWidth < 203) {
    $(".dshUiFarbbeispiele").each(function () {
      const s = this.finde(".dshUiFarbbeispieleSchattierung");
      const w = (100 / s.length * 3) + "%";
      s.setCss("flex-basis", w).addKlasse("jsmod");
    });
  } else if (document.body.clientWidth < 374) {
    $(".dshUiFarbbeispiele").each(function () {
      const s = this.finde(".dshUiFarbbeispieleSchattierung");
      const w = (100 / s.length * 2) + "%";
      s.setCss("flex-basis", w).addKlasse("jsmod");
    });
  } else {
    $(".dshUiFarbbeispieleSchattierung.jsmod").setCss("flex-basis", "").removeKlasse("jsmod");
  }
});

// Datumsanzeige
document.addEventListener("click", (e: MouseEvent) => {
  if (ui.elemente.datumsanzeige.offen) {
    let el = $(e.target);
    do {
      if (el.ist("html")) {
        $(".dshUiDatumwahl").ausblenden();
        break;
      }
      if (el.ist(".dshUiDatumwahlFeld")) {
        break;
      }
    } while ((el = el.parent()));
  }
});
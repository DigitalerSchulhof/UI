import ajax, { AjaxAntwort, ANTWORTEN } from "ts/ajax";
import { AnfrageDaten } from "ts/AnfrageDaten";
import $, { eQuery } from "ts/eQuery";
import { laden } from "../generieren";

let istAn = false;
let fokusVor: eQuery = null;
let autoschliessen: number = null;

export const setFokusVor = (element: eQuery): void => {
  fokusVor = element;
};

export const an = (titel: string, inhalt: string): void => {
  if (titel !== null) {
    $("#dshLadenFensterTitel").setHTML(titel);
  }
  const code = laden.icon(inhalt);
  if (inhalt !== null) {
    $("#dshLadenFensterInhalt").setHTML(code);
  }
  $("#dshLadenFensterAktionen").setHTML("");
  $("#dshBlende").einblenden();
  istAn = true;
  $("#dshLaden")[0].offsetHeight;
  $("#dshLaden").setCss("opacity", "1");
  const knopefe = $("#dshBlende").finde(".dshUiKnopf");
  if (knopefe.length !== 0) {
    fokusVor = $(document.activeElement);
    knopefe[knopefe.length - 1].focus();
  }
  $("[tabindex]:not([tabindexAlt])").each(function () {
    if (this.parent("#dshBlende").length === 0) {
      this.setAttr("tabindexAlt", this.getAttr("tabindex"));
      this.setAttr("tabindex", "-1");
    }
  });
};

export const aendern = (titel: string, inhalt: string, aktionen?: string): void => {
  inhalt = inhalt || "";
  aktionen = aktionen || "";
  if (titel !== null) {
    $("#dshLadenFensterTitel").setHTML(titel);
  }
  $("#dshLadenFensterInhalt").setHTML(inhalt);
  $("#dshLadenFensterAktionen").setHTML(aktionen);
  $("#dshBlende").einblenden();
  istAn = true;

  const dae = $(document.activeElement);
  if (dae.parent("#dshBlende").length === 0) {
    fokusVor = dae;
    const knopefe = $("#dshBlende").finde(".dshUiKnopf");
    if (knopefe.length !== 0) {
      fokusVor = $(document.activeElement);
      knopefe[knopefe.length - 1].focus();
    }
  }
  $("[tabindex]:not([tabindexAlt])").each(function () {
    if (this.parent("#dshBlende").length === 0) {
      this.setAttr("tabindexAlt", this.getAttr("tabindex"));
      this.setAttr("tabindex", "-1");
    }
  });
};

export const aus = (): void => {
  $("#dshLadenFensterTitel", "#dshLadenFensterInhal", "#dshLadenFensterAktionen").setHTML("");
  $("#dshBlende").ausblenden();

  if (autoschliessen !== null) {
    clearTimeout(autoschliessen);
    autoschliessen = null;
  }

  istAn = false;
  if (fokusVor !== null) {
    fokusVor[0].focus();
    if (fokusVor.ist("input")) {
      (fokusVor[0] as HTMLInputElement).select();
    }
  }
  fokusVor = null;
  $("[tabindexAlt]").each(function () {
    this.setAttr("tabindex", this.getAttr("tabindexAlt"));
    this.setAttr("tabindexAlt", null);
  });
};

export const meldung = (modul: string, id: number, laden?: string, parameter?: { [key: string]: any }): void => {
  parameter = parameter || {};
  ajax("UI", 1, { titel: laden, beschreibung: "Die Meldung wird geladen" }, { meldemodul: modul, meldeid: id, meldeparameter: parameter }).then((r) => aendern(null, r.Meldung, r.Knoepfe));
};

export const komponente = (komponenteninfo: AnfrageDaten["UI"][2]): AjaxAntwort<ANTWORTEN["UI"][2]> => ajax("UI", 2, false, komponenteninfo);

export const keydown = (e: KeyboardEvent): void => {
  if (istAn && [37, 39].includes(e.keyCode)) {
    const ae = $(document.activeElement);
    if (ae.ist("#dshLaden #dshLadenFensterAktionen>.dshUiKnopf")) {
      if (e.keyCode === 37) {
        const vor = ae.siblingVor();
        if (vor.length) {
          vor[0].focus();
        }
      } else if (e.keyCode === 39) {
        const nach = ae.siblingNach();
        if (nach.length) {
          nach[0].focus();
        }
      }
    }
  }
};
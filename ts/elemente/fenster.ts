import ajax, { AjaxAntwort, AntwortCode, ANTWORTEN } from "ts/ajax";
import $, { eQuery } from "ts/eQuery";
import { scriptAn } from "ts/laden";
import * as uiLaden from "./laden";

let schiebend: eQuery & { my: number, mx: number } = null;
let maxz = 100000;
let ladend = 0;

export const schliessen = (id: string): void => {
  const fenster = document.getElementById(id);
  fenster.parentNode.removeChild(fenster);
};

export const minimieren = (id: string): void => {
  $("#" + id).toggleKlasse("dshUiFensterMinimiert");
  const ae = $(document.activeElement);
  if (ae.parent(".dshUiFenster").existiert()) {
    ae[0].blur();
  }
};

export const anzeigen = (code: string, ueberschreiben: boolean): void => {
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
  uiLaden.setFokusVor(null);
  $("#" + fensterid).setCss("z-index", (++maxz).toString())[0].focus();
  if (--ladend === 0) {
    ladesymbol(false);
  }
};

export const laden = <M extends keyof ANTWORTEN, Z extends keyof ANTWORTEN[M], A extends ANTWORTEN[M][Z] & AntwortCode>(modul: M, ziel: Z, daten?: { [key: string]: any; }, meldung?: number | { modul: string; meldung: number; }, host?: string | string[], ueberschreiben?: boolean): AjaxAntwort<ANTWORTEN[M][Z]> => {
  ladend++;
  ladesymbol(true);
  const r = ajax<M, Z, A>(modul, ziel, false, daten, meldung, host);
  r.then((r) => {
    anzeigen(r.Code, ueberschreiben);
  });
  return r;
};

export const ladesymbol = (an: boolean): void => {
  if (an) {
    $("#dshUiFensterLadesymbol").setCss("bottom", "0");
  } else {
    $("#dshUiFensterLadesymbol").setCss("bottom", "");
  }
};

export const keydown = (e: KeyboardEvent): void => {
  if ([27].includes(e.keyCode)) {
    const ae = $(document.activeElement);
    if (ae.ist(".dshUiFenster")) {
      schliessen(ae.getID());
    }
  }
};

export const mousedown = (e: MouseEvent): void => {
  if ($(e.target).parent(".dshUiFenster").existiert()) {
    $(e.target).parent(".dshUiFenster").setCss("z-index", (++maxz).toString());
    if (e.which === 1 && ($(e.target).ist(".dshUiFenster:not(#dshLaden) .dshUiFensterTitelzeile") || $(e.target).parent(".dshUiFenster:not(#dshLaden) .dshUiFensterTitelzeile").existiert())) {
      const f = $(e.target).parent(".dshUiFenster");
      f.addKlasse("dshUiFensterSchiebend");
      schiebend = f as eQuery & { my: number, mx: number };
      schiebend.my = parseInt(f.getCss("top"));
      schiebend.mx = parseInt(f.getCss("left"));
    }
  }
};

export const mousemove = (e: MouseEvent): void => {
  if (schiebend !== null) {
    schiebend.mx += e.movementX;
    schiebend.my += e.movementY;
    schiebend.setCss({ top: schiebend.my + "px", left: schiebend.mx + "px" });
  }
};

export const mouseup = (): void => {
  if (schiebend !== null) {
    schiebend.removeKlasse("dshUiFensterSchiebend");
    schiebend = null;
  }
};

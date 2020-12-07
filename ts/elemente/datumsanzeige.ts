import ajax, { AjaxAntwort, ANTWORTEN } from "ts/ajax";
import $ from "ts/eQuery";
import { natZahl } from "../check";
import { fuehrendeNull } from "../generieren";

export let offen = false;

export const aktion = (id: string, an: boolean): void => {
  const feld = $("#" + id + "Datumwahl");
  if (!an) {
    feld.ausblenden();
    offen = false;
  } else {
    const tag = parseInt($("#" + id + "T").getWert());
    const monat = parseInt($("#" + id + "M").getWert());
    const jahr = parseInt($("#" + id + "J").getWert());

    tageswahl.generieren(id, tag, monat, jahr).then((r) => {
      feld.setHTML(r.Code).einblenden();
      offen = true;
    });
  }
};

export const monataendern = (id: string, tag: number, monat: number, jahr: number): void => {
  const feld = $("#" + id + "Datumwahl");
  const datum = new Date(jahr, monat - 1, tag);
  tageswahl.generieren(id, datum.getDate(), datum.getMonth() + 1, datum.getFullYear()).then((r) => feld.setHTML(r.Code));
};

export const checkTag = (id: string): void => {
  const jetzt = new Date();
  let dateTag: number, dateMonat: number, dateJahr: number;

  const tag = $("#" + id + "T").getWert();
  const monat = $("#" + id + "M").getWert();
  const jahr = $("#" + id + "J").getWert();


  if (tag !== undefined && natZahl(tag)) {
    dateTag = parseInt(tag);
  } else {
    dateTag = jetzt.getDate();
  }
  if (monat !== undefined && natZahl(monat)) {
    dateMonat = parseInt(monat);
  } else {
    dateMonat = jetzt.getMonth() + 1;
  }
  if (jahr !== undefined && natZahl(jahr)) {
    dateJahr = parseInt(jahr);
  } else {
    dateJahr = jetzt.getFullYear();
  }

  let date = new Date(dateJahr, dateMonat - 1, dateTag);
  if (isNaN(date.getDate())) {
    date = new Date();
  }

  $("#" + id + "T").setWert(fuehrendeNull(jetzt.getDate()));
  $("#" + id + "M").setWert(fuehrendeNull(jetzt.getMonth() + 1));
  $("#" + id + "J").setWert(fuehrendeNull(jetzt.getFullYear()));
};

export const checkUhrzeit = (id: string, sekunden: boolean): void => {
  const jetzt = new Date();
  let dateStunde: number, dateMinute: number;

  const stunde = $("#" + id + "Std").getWert();
  const minute = $("#" + id + "Min").getWert();

  if (stunde !== undefined && natZahl(stunde)) {
    dateStunde = parseInt(stunde);
  } else {
    dateStunde = jetzt.getHours();
  }
  if (minute !== undefined && natZahl(minute)) {
    dateMinute = parseInt(minute);
  } else {
    dateMinute = jetzt.getMinutes();
  }

  let date: Date;
  if (sekunden) {
    let dateSekunde: number;
    const sekunde = $("#" + id + "Sek").getWert();
    if (sekunde !== undefined && natZahl(sekunde)) {
      dateSekunde = parseInt(sekunde);
    } else {
      dateSekunde = jetzt.getSeconds();
    }

    date = new Date(2020, 7, 9, dateStunde, dateMinute, dateSekunde);
    $("#" + id + "Sek").setWert(fuehrendeNull(date.getSeconds()));
  } else {
    date = new Date(2020, 3, 19, dateStunde, dateMinute);
  }
  $("#" + id + "Std").setWert(fuehrendeNull(jetzt.getHours()));
  $("#" + id + "Min").setWert(fuehrendeNull(jetzt.getMinutes()));
};

const tageswahl = {
  generieren: (id: string, tag: number, monat: number, jahr: number): AjaxAntwort<ANTWORTEN["UI"][0]> => ajax("UI", 0, false, { id: id, tag: tag, monat: monat, jahr: jahr }),
  aktion: (id: string, tag: number, monat: number, jahr: number): void => {
    const datum = new Date(jahr, monat - 1, tag);
    $("#" + id + "T").setWert(fuehrendeNull(datum.getDate()));
    $("#" + id + "M").setWert(fuehrendeNull(datum.getMonth() + 1));
    $("#" + id + "J").setWert(fuehrendeNull(datum.getFullYear()));
    $("#" + id + "Datumwahl").ausblenden();
  }
};

export const click = (e: MouseEvent): void => {
  if (offen) {
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
};
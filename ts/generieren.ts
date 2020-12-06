import { natZahl } from "./check";

type monatnameLang = "Januar" | "Februar" | "März" | "April" | "Mai" | "Juni" | "Juli" | "August" | "September" | "Oktober" | "November" | "Dezember";
type monatnameKurz = "JAN" | "FEB" | "MÄR" | "APR" | "MAI" | "JUN" | "JUL" | "AUG" | "SEP" | "OKT" | "NOV" | "DEZ"
export const monatname = {
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
};

type tagnameLang = "Montag" | "Dienstag" | "Mittwoch" | "Donnerstag" | "Freitag" | "Samstag" | "Sonntag";
type tagnameKurz = "MO" | "DI" | "MI" | "DO" | "FR" | "SA" | "SO";
export const tagname = {
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
};

export const speicherplatz = {
  lang: (bytes: number): string | "Zu viel" => {
    const einheiten = ["Byte", "Kilobyte", "Megabyte", "Gigabyte", "Terabyte", "Petabyte", "Exabyte"];
    for (const e of einheiten) {
      if (bytes / 1000 < 1) {
        return komma((Math.round(bytes * 100) / 100)) + " " + e;
      }
      bytes = bytes / 1000;
    }
    return "Zu viel"; // That's what she said!
  },
  kurz: (bytes: number): string | "Zu viel" => {
    const einheiten = ["B", "KB", "MB", "GB", "TB", "PB", "EB"];
    for (const e of einheiten) {
      if (bytes / 1000 < 1) {
        return komma((Math.round(bytes * 100) / 100)) + " " + e;
      }
      bytes = bytes / 1000;
    }
    return "Zu viel";
  }
};

export const fuehrendeNull = (x: string | number): string => {
  if (natZahl(x)) {
    if ((x.toString()).length < 2) {
      return "0" + x;
    } else {
      return "" + x;
    }
  } else {
    return "";
  }
};

export const prozent = (x: number, gesamt: number): number => Math.round(x / gesamt * 10000) / 100;

export const zeit = (x: number | Date): string => {
  let datum: Date;
  if (typeof x === "number") {
    datum = new Date(x);
  } else {
    datum = x;
  }
  return datum.getHours() + ":" + datum.getMinutes() + " Uhr";
};
export const minuten = (x: number): number => Math.floor((x / 1000) / 60);

export const komma = (x: number): string => (Math.round(x * 100) / 100).toString().replace(".", ",");


export const rgba2hex = (rgba: string): string => {
  const rgb = rgba.replace(/\s/g, "").match(/^rgba?\((\d+),(\d+),(\d+),?([^,\s)]+)?/i);
  if (rgb === null) {
    return "";
  }
  return "#" +
    (parseInt(rgb[1]) | 1 << 8).toString(16).slice(1) +
    (parseInt(rgb[2]) | 1 << 8).toString(16).slice(1) +
    (parseInt(rgb[3]) | 1 << 8).toString(16).slice(1);
};
export const hex2rgba = (hex: string): string | undefined => {
  let c;
  if (/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)) {
    c = hex.substring(1).split("");
    if (c.length === 3) {
      c = [c[0], c[0], c[1], c[1], c[2], c[2]];
    }
    c = "0x" + c.join("");
    return "rgba(" + [(parseInt(c) >> 16) & 255, (parseInt(c) >> 8) & 255, parseInt(c) & 255].join(",") + ",1)";
  }
  return undefined;
};

export const laden = {
  icon: (inhalt?: string): string => {
    let code = "<div class=\"dshUiLaden\">";
    code += "<div class=\"dshUiLadenIcon\"><div></div><div></div><div></div><div></div></div>";
    code += "<span class=\"dshUiLadenStatus\">" + inhalt + "...</span>";
    code += "</div>";
    return code;
  },
  balken: {
    speicher: (id: string, belegt: number | null, gesamt: number | null, art: string | null): string => {
      belegt = belegt || null;
      gesamt = gesamt || null;
      art = art || null;
      let zusatzklasse = "";
      if (art !== null) {
        zusatzklasse = " dshUiLadenBalken" + art;
      }
      let code = "<div id=\"" + id + "\" class=\"dshUiLadenBalkenAussen" + zusatzklasse + "\"><div class=\"dshUiLadenBalkenInnen\"></div></div>";
      if ((belegt !== null) && (gesamt !== null)) {
        code += "<p class=\"dshUiLadenErklaerung\">" + laden.speicher(belegt, gesamt) + "</p>";
      }
      return code;
    },
    zeit: (id: string, beginn: number | null, aktuell: number | null, ende: number | null, art: string | null): string => {
      beginn = beginn || null;
      aktuell = aktuell || null;
      ende = ende || null;
      art = art || null;
      let zusatzklasse = "";
      if (art !== null) {
        zusatzklasse = " dshUiLadenBalken" + art;
      }
      let code = "<div id=\"" + id + "\" class=\"dshUiLadenBalkenAussen" + zusatzklasse + "\"><div class=\"dshUiLadenBalkenInnen\"></div></div>";
      if ((beginn !== null) && (aktuell !== null) && (ende !== null)) {
        code += "<p class=\"dshUiLadenErklaerung\">" + laden.zeit(beginn, aktuell, ende) + "</p>";
      }
      return code;
    }
  },
  speicher: (x: number, gesamt: number): string => "<span>" + speicherplatz.kurz(x) + " (" + prozent(x, gesamt) + "%) von " + speicherplatz.kurz(gesamt) + " belegt. Frei: (" + (100 - prozent(x, gesamt)) + "%)</span>",
  zeit: (beginn: number, aktuell: number, ende: number): string => "<span>Begonnen um " + zeit(beginn) + ". Zeit bis: " + zeit(ende) + ". Frei: (" + (100 - prozent(aktuell, ende)) + "%)</span>"
};
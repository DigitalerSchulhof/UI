import { AnfrageAntwortCode } from "ts/ajax";
import $ from "ts/eQuery";
import { scriptAn } from "ts/laden";

export interface SortierParameter {
  sortSeite: number;
  sortDatenproseite: number;
  sortRichtung: "ASC" | "DESC";
  sortSpalte: string;
}

export const sortieren = (id: string, richtung?: "ASC" | "DESC", spalte?: string): void => {
  const tabelle = $("#" + id);
  if (tabelle.existiert()) {
    const feld = $("#" + id + "Ladebereich");
    const sortSeite = Number($("#" + id + "Seite").getWert());
    const sortDatenproseite = Number($("#" + id + "DatenProSeite").getWert());
    const sortRichtung = richtung || $("#" + id + "SortierenRichtung").getWert() as "ASC" | "DESC";
    const sortSpalte = spalte || $("#" + id + "SortierenSpalte").getWert();
    const i = feld.kinder(".dshUiTabelleI");
    i.addKlasse("dshUiTabelleLaedt");
    if (!i.kinder("table").existiert()) {
      console.error("Die ID »" + id + "« ist keine Tabelle", id);
      return;
    }
    const s = i.kinder("table").getAttr("data-sortierfunktion")?.split(".");
    if (s !== undefined) {
      const fnc = [...s];
      // String zu Funktion
      let sortierfunktion = window as Record<string, any>;
      while (fnc.length > 0) {
        sortierfunktion = sortierfunktion[fnc.shift() || ""] as () => void | undefined | Record<string, any>;
        if (sortierfunktion === undefined) {
          console.error("Sortierfunktion »" + s.join(".") + "« nicht gefunden");
        }
      }
      (sortierfunktion as (sortieren: SortierParameter, id: string) => Promise<AnfrageAntwortCode>)({ sortSeite: sortSeite, sortDatenproseite: sortDatenproseite, sortRichtung: sortRichtung, sortSpalte: sortSpalte }, id).then((r) => {
        if (r.Code) {
          feld.setHTML(r.Code);
          scriptAn(feld);
        }
      });
    }
  }
};
import $ from "ts/eQuery";
import { scriptAn } from "ts/laden";

export const sortieren = (id: string, richtung?: "ASC" | "DESC", spalte?: string): void => {
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
};
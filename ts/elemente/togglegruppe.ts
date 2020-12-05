import $ from "ts/eQuery";

export const aktion = (id: string, nr: number, anzahl: number, wert: string): void => {
  $("#" + id).setWert(wert);
  $("#" + id + "KnopfId").setWert(nr.toString());
  for (let i = 0; i < anzahl; i++) {
    $("#" + id + "Knopf" + i).removeKlasse("dshUiToggled");
  }
  $("#" + id + "Knopf" + nr).addKlasse("dshUiToggled");
};
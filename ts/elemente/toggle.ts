import $ from "ts/eQuery";

export const aktion = (id: string): void => {
  const wert = $("#" + id).getWert();
  const neuerwert = (1 - parseInt(wert)).toString();
  $("#" + id).setWert(neuerwert);
  if (neuerwert === "1") {
    $("#" + id + "Toggle").addKlasse("dshUiToggled");
  } else {
    $("#" + id + "Toggle").removeKlasse("dshUiToggled");
  }
};
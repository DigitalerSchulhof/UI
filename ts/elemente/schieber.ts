import $ from "ts/eQuery";

const aktion = (id: string): void => {
  const wert = $("#" + id).getWert();
  const neuerwert = (parseInt(wert) - 1).toString();
  $("#" + id).setWert(neuerwert);
  $("#" + id + "Schieber").addKlasse("dshUiSchieber" + neuerwert);
  $("#" + id + "Schieber").removeKlasse("dshUiSchieber" + wert);
};

export default aktion;
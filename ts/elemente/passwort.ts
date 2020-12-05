import $ from "ts/eQuery";

export const aktion = (idvergleich: string, idpruefen: string): void => {
  const vergleich = $("#" + idvergleich).getWert();
  const pruefen = $("#" + idpruefen).getWert();
  $("#" + idpruefen).setKlasse(vergleich == pruefen, "dshUiPruefen1");
  $("#" + idpruefen).setKlasse(vergleich != pruefen, "dshUiPruefen0");
};
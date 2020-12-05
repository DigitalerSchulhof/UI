import $ from "ts/eQuery";

const aktion = (idvergleich: string, idpruefen: string): void => {
  const vergleich = $("#" + idvergleich).getWert();
  const pruefen = $("#" + idpruefen).getWert();
  $("#" + idpruefen).setKlasse(vergleich == pruefen, "dshUiPruefen1");
  $("#" + idpruefen).setKlasse(vergleich != pruefen, "dshUiPruefen0");
};

export default aktion;
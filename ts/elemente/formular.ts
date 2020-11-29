import $ from "ts/eQuery";

export const anzeigenwenn = (id: string, vergleichswert: string, klasse: string): void => {
  const wert = $("#" + id).getWert();
  const felder = $("." + klasse);
  if (wert == vergleichswert) {
    felder.einblenden("table-row");
  } else {
    felder.ausblenden();
  }
};
import $ from "ts/eQuery";

const aktion = (id: string, nr: number, anzahl: number): void => {
  for (let i = 0; i < anzahl; i++) {
    $("#" + id + "Koerper" + i).removeKlasse("dshUiReiterKoerperAktiv").addKlasse("dshUiReiterKoerperInaktiv");
    $("#" + id + "Kopf" + i).removeKlasse("dshUiReiterKopfAktiv").addKlasse("dshUiReiterKopfInaktiv");
  }
  $("#" + id + "Koerper" + nr).removeKlasse("dshUiReiterKoerperInaktiv").addKlasse("dshUiReiterKoerperAktiv");
  $("#" + id + "Kopf" + nr).removeKlasse("dshUiReiterKopfInaktiv").addKlasse("dshUiReiterKopfAktiv");
};

export default aktion;
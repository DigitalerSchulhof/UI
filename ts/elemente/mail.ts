import $ from "ts/eQuery";
import { mail as checkMail } from "./../check";

const aktion= (id: string): void => {
  const mail = $("#" + id).getWert();
  const ok = checkMail(mail);
  $("#" + id).setKlasse(ok, "dshUiPruefen1");
  $("#" + id).setKlasse(!ok, "dshUiPruefen0");
};

export default aktion;
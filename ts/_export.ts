import * as laden from "./elemente/laden";
import * as meldung from "./elemente/meldung";
import * as togglegruppe from "./elemente/togglegruppe";
import * as tabelle from "./elemente/tabelle";
import * as formular from "./elemente/formular";
import * as fenster from "./elemente/fenster";
import * as datumsanzeige from "./elemente/datumsanzeige";
import * as schieber from "./elemente/schieber";
import * as toggle from "./elemente/toggle";
import * as farbbeispiel from "./elemente/farbbeispiel";
import * as passwort from "./elemente/passwort";
import * as mail from "./elemente/mail";
import * as reiter from "./elemente/reiter";

export type PersonenArt = "s" | "l" | "v" | "e" | "x";
export type PersonenGeschlecht = "w" | "m" | "d";
export type ToggleWert = "0" | "1";
export type ProfilArt = "person" | "nutzerkonto";

export type IconArt = string;

export interface Daten {
  0: {
    id: string;
    tag: number;
    monat: number;
    jahr: number;
  },
  1: {
    meldemodul: string;
    meldeid: number;
    meldeparameter: { [key: string]: any };
  },
  2: {
    komponente: "IconKnopf",
    inhalt: string,
    icon: string
    art?: IconArt,
    klickaktion?: string;
  } | {
    komponente: "IconKnopfPerson"
    inhalt: string;
    personart: PersonenArt,
    id?: string;
    klickaktion?: string;
  }
}

export default {
  laden: laden,
  meldung: meldung,
  togglegruppe: togglegruppe,
  tabelle: tabelle,
  formular: formular,
  fenster: fenster,
  datumsanzeige: datumsanzeige,
  schieber: schieber,
  toggle: toggle,
  farbbeispiel: farbbeispiel,
  passwort: passwort,
  mail: mail,
  reiter: reiter,
};
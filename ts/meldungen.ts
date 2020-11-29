import ajax, { AnfrageErfolg } from "ts/ajax";

export const erfolg = (t: string, b: string): Promise<AnfrageErfolg & { Code: string }> => meldung("Erfolg", t, b);
export const fehler = (t: string, b: string): Promise<AnfrageErfolg & { Code: string }> => meldung("Fehler", t, b);
export const warnung = (t: string, b: string): Promise<AnfrageErfolg & { Code: string }> => meldung("Warnung", t, b);
export const information = (t: string, b: string): Promise<AnfrageErfolg & { Code: string }> => meldung("Information", t, b);
export const laden = (t: string, b: string): Promise<AnfrageErfolg & { Code: string }> => meldung("Laden", t, b);
export const eingeschraenkt = (t: string, b: string): Promise<AnfrageErfolg & { Code: string }> => meldung("Eingeschraenkt", t, b);
export const gesperrt = (t: string, b: string): Promise<AnfrageErfolg & { Code: string }> => meldung("Gesperrt", t, b);

const meldung = (art: string, titel: string, inhalt: string, icon?: string): Promise<AnfrageErfolg & { Code: string }> => ajax<{ Code: string }>("UI", 1, false, { art: art, titel: titel, inhalt: inhalt, icon: icon });
export default meldung;
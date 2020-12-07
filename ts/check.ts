export const zahl = (x: number | string): boolean => x.toString().match(/^-?[0-9]+$/) !== null;
export const natZahl = (x: number | string): boolean => x.toString().match(/^[0-9]+$/) !== null;
export const mail = (x: string): boolean => x.match(/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]{2,}$/) !== null;
export const toggle = (x: string): boolean => x.match(/^(0|1)$/) !== null;
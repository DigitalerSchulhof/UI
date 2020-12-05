import $ from "ts/eQuery";

import { rgba2hex } from "../generieren";

export const aktion = (t: HTMLElement): void => {
  const fb = t.closest(".dshUiFarbbeispiele");
  if (t.tagName !== "INPUT") {
    fb.querySelectorAll<HTMLInputElement>("input[type=color]")[0].value = rgba2hex((t.style as typeof t.style & { "background-color": string })["background-color"]);
  }
};

export const resize = (): void => {
  if (document.body.clientWidth < 203) {
    $(".dshUiFarbbeispiele").each(function () {
      const s = this.finde(".dshUiFarbbeispieleSchattierung");
      const w = (100 / s.length * 3) + "%";
      s.setCss("flex-basis", w).addKlasse("jsmod");
    });
  } else if (document.body.clientWidth < 374) {
    $(".dshUiFarbbeispiele").each(function () {
      const s = this.finde(".dshUiFarbbeispieleSchattierung");
      const w = (100 / s.length * 2) + "%";
      s.setCss("flex-basis", w).addKlasse("jsmod");
    });
  } else {
    $(".dshUiFarbbeispieleSchattierung.jsmod").setCss("flex-basis", "").removeKlasse("jsmod");
  }
};
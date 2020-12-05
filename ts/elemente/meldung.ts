import $ from "ts/eQuery";

const aktion = (ev: MouseEvent): void => {
  const t = $(ev.target);
  if (!t.ist(".dshUiMeldung")) {
    return;
  }
  const ts = getComputedStyle(t[0]);
  if (ev.offsetX < 0) {
    if (t.hatAttr("brleft")) {
      new Function(t.getAttr("brleft")).apply(t);
    } else {
      const i2 = t.finde("i.i2.dshUiIcon");
      const fehlercodes = t.finde(".dshFehlercode");
      if ((ts as typeof ts & { "border-right-width": string })["border-right-width"] === "23px") {
        t.setCss("border-right-width", "");
        i2.setCss("right", "");
        fehlercodes.setCss("right", "");
      } else {
        t.setCss("border-right-width", "23px");
        const r = getComputedStyle(i2[0]).right;
        i2.setCss("right", (-Math.abs(parseInt(r.substr(0, r.length - 2)))) + "px");
        fehlercodes.setCss("right", "-23px");
      }
    }
  }
  if (ev.offsetX > t[0].clientWidth) {
    if (t.hatAttr("brright")) {
      new Function(t.getAttr("brright")).apply(t);
    } else {
      const i1 = t.finde("i.i1.dshUiIcon");
      if ((ts as typeof ts & {"border-left-width": string})["border-left-width"] === "23px") {
        t.setCss("border-left-width", "2px");
        const l = getComputedStyle(i1[0]).left;
        i1.setCss("left", Math.abs(parseInt(l.substr(0, l.length - 2))) + "px");
      } else {
        t.setCss("border-left-width", "");
        i1.setCss("left", "");
      }
    }
  }
  if (t.ist(".dshUiMeldungLaden") && (ev.offsetY < 0 || ev.offsetY > t[0].clientHeight)) {
    if (t.getCss("transform") === "rotateY(180deg)") {
      t.setCss("transform", "");
    } else {
      t.setCss("transform", "rotateY(180deg)");
    }
  }
  t.finde(".dshUiFehlermeldung").toggleCss("opacity", "1");
};

export default aktion;
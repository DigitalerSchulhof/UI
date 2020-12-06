import * as datumsanzeige from "./elemente/datumsanzeige";
import * as farbbeispiel from "./elemente/farbbeispiel";
import * as fenster from "./elemente/fenster";
import * as laden from "./elemente/laden";

document.addEventListener("click", datumsanzeige.click);

window.addEventListener("resize", farbbeispiel.resize);

document.addEventListener("keydown", fenster.keydown);
document.addEventListener("mousedown", fenster.mousedown);
document.addEventListener("mousemove", fenster.mousemove);
document.addEventListener("mouseup", fenster.mouseup);

document.addEventListener("keydown", laden.keydown);
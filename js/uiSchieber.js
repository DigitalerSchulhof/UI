function dshUiSchieber(id) {
  var wert = document.getElementById(id).value;
  var neuerwert = 0;
  if (wert == 0) {
    document.getElementById(id).value = 1;
  }
  document.getElementById(id+'Schieber').className = "dshUiSchieberInnen"+neuerwert;
}

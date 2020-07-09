function dshUiDatumsanzeige(id, an) {
  var feld = document.getElementById(id+'Datumwahl');
  if (!an) {
    feld.style.display = "none";
  } else {
    var tag = document.getElementById(id+'T').value;
    var monat = document.getElementById(id+'M').value;
    var jahr = document.getElementById(id+'J').value;

    dshUiTageswahlGenerieren(id, tag, monat, jahr);
    feld.style.display = "block";
  }
}

function dshUiTageswahlGenerieren(id, tag, monat, jahr) {
  var code = "<table>";
  code += "<tr><th onclick=\"dshUiTageswahlGenerieren('"+id+"', '"+tag+"', '"+(monat-1)+"', '"+jahr+"')\"><i class=\"fas fa-angle-double-left\"></i></th>";
  code += "<th>"+dshUiMonatsname(monat)+" "+jahr+"</th>";
  code += "<th colspan=\"5\" onclick=\"dshUiTageswahlGenerieren('"+id+"', '"+tag+"', '"+(monat+1)+"', '"+jahr+"')\"><i class=\"fas fa-angle-double-right\"></i></th>";
  code += "</tr>";
  code += "<tr>";
  for (var i=1; i<=7; i++) {
    code += "<td>"+dshUiTagesnameKurz(i)+"</td>";
  }
  code += "</tr>";

  var erster = new Date(jahr, monat-1, 1);
  var wochentag = getDay(erster) + 1;
  var letzter = getDate(new Date (jahr, monat, 0));
  var nr = 1;
  var klassenzusatz = "";

  code += "<tr>";
  // leer auffüllen, falls nicht mit Montag begonnen wird
  for (var i=1; i<wochentag; i++) {
    code += "<td class=\"dshUiTageswahlButtonBlind\"></td>";
  }
  for (var i=wochentag; i<=7; i++) {
    if (nr == tag) {
      klassenzusatz = " dshUiTagGewaehlt";
    } else {
      klassenzusatz = "";
    }
    code += "<td class=\"dshUiTageswahlButton"+klassenzusatz+"\" onclick=\"dshUiTageswahl('"+id+"', '"+nr+"', '"+monat+"', '"+jahr+"')\">"+nr+"</td>";
    nr ++;
  }
  code += "</tr>";
  wochentag = 1;

  while (nr <= letzter) {
    if (wochentag == 8) {
      code += "</tr>";
      wochentag = 1;
    }
    if (wochentag == 1) {code += "<tr>";}
    if (nr == tag) {
      klassenzusatz = " dshUiTagGewaehlt";
    } else {
      klassenzusatz = "";
    }
    code += "<td class=\"dshUiTageswahlButton"+klassenzusatz+"\" onclick=\"dshUiTageswahl('"+id+"', '"+nr+"', '"+monat+"', '"+jahr+"')\">"+nr+"</td>";
    nr ++;
    wochentag ++;
  }

  // leer auffüllen, falls nicht am Sonntag geendet
  for (var i=wochentag; i<=7; i++) {
    code += "<td class=\"dshUiTageswahlButtonBlind\"></td>";
    nr ++;
  }
  code += "</tr>";

  code += "</table>";
  document.getElementById(id+'Datumwahl').innerHTML = code;
}

function dshUiTageswahl(id, tag, monat, jahr) {
  var tag = document.getElementById(id+'T').value = dshUiFuehrendeNull(tag);
  var monat = document.getElementById(id+'M').value = dshUiFuehrendeNull(monat);
  var jahr = document.getElementById(id+'J').value = dshUiFuehrendeNull(jahr);
  document.getElementById(id+'Datumwahl').display = none;
}

function dshUiMonatsname(monat) {
  switch (monat) {
    case 1: return "Januar";
    case 2: return "Februar";
    case 3: return "März";
    case 4: return "April";
    case 5: return "Mai";
    case 6: return "Juni";
    case 7: return "Juli";
    case 8: return "August";
    case 9: return "September";
    case 10: return "Oktober";
    case 11: return "November";
    case 12: return "Dezember";
    default: retrun false;
  }
}

function dshUiMonatsnameKurz(monat) {
  switch (monat) {
    case 1: return "JAN";
    case 2: return "FEB";
    case 3: return "MÄR";
    case 4: return "APR";
    case 5: return "MAI";
    case 6: return "Juni";
    case 7: return "Juli";
    case 8: return "AUG";
    case 9: return "SEP";
    case 10: return "OKT";
    case 11: return "NOV";
    case 12: return "DEZ";
    default: retrun false;
  }
}

function dshUiTagesname(tag) {
  switch (tag) {
    case 0: return "Sonntag"
    case 1: return "Montag";
    case 2: return "Dienstag";
    case 3: return "Mittwoch";
    case 4: return "Donnerstag";
    case 5: return "Freitag";
    case 6: return "Samstag";
    case 7: return "Sonntag";
    default: retrun false;
  }
}

function dshUiTagesnameKurz(tag) {
  switch (tag) {
    case 0: return "SO"
    case 1: return "MO";
    case 2: return "DI";
    case 3: return "MI";
    case 4: return "DO";
    case 5: return "FR";
    case 6: return "SA";
    case 7: return "SO";
    default: retrun false;
  }
}

function dshUiIstZahl(x) {
	return x.match(/^[0-9]+$/);
}

function dshUiFuehrendeNull(x) {
	if (dshUiIstZahl(x)) {
		if (x.length < 2) {
			return "0"+x;
		} else {
			return x;
		}
	} else {
		return false;
	}
}

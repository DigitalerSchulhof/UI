<?php
namespace UI;

class Check {
  /**
   * Gibt zurück, ob ein Datum gültig ist
   * @param  string $datum :)
   * @return bool
   */
  public static function istDatum($datum) : bool {
    return self::macheDatum($datum) !== false;
  }

  /**
   * Gibt mktime eines Datums zurück
   * @param  string $datum :)
   * @return int|false
   */
  public static function macheDatum($datum) {
    $d = explode(".", $datum);
    if (count($d) !== 3) {return false;}
    $tag   = $d[0];
    $monat = $d[1];
    $jahr  = $d[2];
    return mktime(0, 0, 0, $monat, $tag, $jahr);
  }

  public static function istEditor($x) {
    return preg_match("/^[-_ \.\/\@äöüÄÖÜßáéíóúàèìòùÁÉÍÓÚÀÈÌÒÙæÆâêîôûÂÊÎÔÛøØÅÇËÃÏÕãåçëïõÿñ0-9a-zA-Z,?!]*$/", $x) === 1;
  }

  public static function istZahl($x, $min = null, $max = null) {
    if (preg_match("/^[0-9]+$/", $x) !== 1) {
      return false;
    }
    $fehler = false;
    if ($min !== null) {
      if ($x < $min) {
        $fehler = true;
      }
    }
    if ($max !== null) {
      if ($x > $max) {
        $fehler = true;
      }
    }
  	return !$fehler;
  }

  public static function istLatein($x, $min = 1, $max = null) {
    if (preg_match("/^[-_0-9a-zA-Z]+$/", $x) !== 1) {
      return false;
    }
    $fehler = false;
    if ($min !== null) {
      if (strlen($x) < $min) {
        $fehler = true;
      }
    }
    if ($max !== null) {
      if (strlen($x) > $max) {
        $fehler = true;
      }
    }
  	return !$fehler;
  }

  public static function istRgbaFarbe($x) {
    $null255 = "(1?[0-9]{1,2}|2([0-4]{0,1}[0-9]{1}|5[0-5]{1}))";
    if (preg_match("/^rgba\($null255, ?$null255, ?$null255, ?(0?\.[0-9]+|1)\)$/", $x) !== 1) {
      return false;
    }
    return true;
  }

  public static function istHexFarbe($x) {
    if (preg_match("/^#[0-9ABCDEFabcdef]{6}$/", $x) !== 1) {
      return false;
    }
    return true;
  }

  public static function istText($x, $min = 1, $max = null) {
    if (preg_match("/^[-_ \.\/\@äöüÄÖÜßáéíóúàèìòùÁÉÍÓÚÀÈÌÒÙæÆâêîôûÂÊÎÔÛøØÅÇËÃÏÕãåçëïõÿñ0-9a-zA-Z]*$/", $x) !== 1) {
      return false;
    }
    $fehler = false;
    if ($min !== null) {
      if (strlen($x) < $min) {
        $fehler = true;
      }
    }
    if ($max !== null) {
      if (strlen($x) > $max) {
        $fehler = true;
      }
    }
  	return !$fehler;
  }

  public static function istTitel($x, $min = 0, $max = null) {
    if (preg_match("/^[- \._äöüÄÖÜßáéíóúàèìòùÁÉÍÓÚÀÈÌÒÙæÆâêîôûÂÊÎÔÛøØÅÇËÃÏÕãåçëïõÿñ0-9a-zA-Z]*$/", $x) !== 1) {
      return false;
    }
    $fehler = false;
    if ($min !== null) {
      if (strlen($x) < $min) {
        $fehler = true;
      }
    }
    if ($max !== null) {
      if (strlen($x) > $max) {
        $fehler = true;
      }
    }
  	return !$fehler;
  }

  public static function istName($x, $min = 1, $max = null) {
    if (preg_match("/^[- _äöüÄÖÜßáéíóúàèìòùÁÉÍÓÚÀÈÌÒÙæÆâêîôûÂÊÎÔÛøØÅÇËÃÏÕãåçëïõÿñ0-9a-zA-Z]*$/", $x) !== 1) {
      return false;
    }
    $fehler = false;
    if ($min !== null) {
      if (strlen($x) < $min) {
        $fehler = true;
      }
    }
    if ($max !== null) {
      if (strlen($x) > $max) {
        $fehler = true;
      }
    }
    return !$fehler;
  }

  /**
   * Gibt zurück, ob eine Mailadresse gültig ist
   * @param  string $mail :)
   * @return bool
   */
  public static function istMail($mail) : bool {
    return preg_match('/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]{2,}$/', $mail) === 1;
  }

  /**
   * Gibt zurück, ob der übergebene Wert 0 oder 1 ist
   * @param  string $wert :)
   * @return bool
   */
  public static function istToggle($wert) : bool {
    if (preg_match('/^(0|1)$/', $wert) != 1) {
  		return false;
  	}
  	return true;
  }

  /**
   * Gibt zurück, ob der übergebene Wert eine ID-Liste ist
   * @param  string $wert :)
   * @return bool
   */
  public static function istIDListe($wert) : bool {
    if (preg_match('/^[0-9]*(,[0-9]+)*$/', $wert) != 1) {
  		return false;
  	}
  	return true;
  }

  public static function fuehrendeNull($x) {
    $check = new Check();
  	if ($check->istZahl($x)) {
  		if (strlen($x) < 2) {
  			return "0".$x;
  		} else {
  			return $x;
  		}
  	} else {
  		return false;
  	}
  }

  /**
   * Erstellt aus einem Float-Wert einen Prozentstring
   * @param  float $wert :)
   * @return array ["wert"] enthält den Wert, ["anzeige"] enthält den String, ["style"] enthält den Wert mit %-Zeichen
   */
  public static function prozent($teil, $ganz) : array {
    $rueckgabe = [];
    $rueckgabe["wert"] = ($teil/$ganz)*100;
    $rueckgabe["anzeige"] = str_replace(".", ",", round($rueckgabe["wert"], 2))." %";
    $rueckgabe["style"] = "{$rueckgabe["wert"]}%";
    return $rueckgabe;
  }

  /**
   * Gibt den Speicher in der größtmöglichen Einheit aus
   * @param  int    $bytes :)
   * @return string        :)
   */
  public static function speicher ($bytes) : string {
    if ($bytes/1000 >= 1) {
      $bytes = $bytes/1000;
      if ($bytes/1000 >= 1) {
        $bytes = $bytes/1000;
        if ($bytes/1000 >= 1) {
          $bytes = $bytes/1000;
          if ($bytes/1000 >= 1) {
            $bytes = $bytes/1000;
            if ($bytes/1000 >= 1) {
              $bytes = $bytes/1000;
              if ($bytes/1000 >= 1) {
                $bytes = $bytes/1000;
                $bytes = str_replace('.', ',', round($bytes, 2));
                return $bytes." EB";
              }
              $bytes = str_replace('.', ',', round($bytes, 2));
              return $bytes." PB";
            }
            $bytes = str_replace('.', ',', round($bytes, 2));
            return $bytes." TB";
          }
          $bytes = str_replace('.', ',', round($bytes, 2));
          return $bytes." GB";
        }
        $bytes = str_replace('.', ',', round($bytes, 2));
        return $bytes." MB";
      }
      $bytes = str_replace('.', ',', round($bytes, 2));
      return $bytes." KB";
    }
    return $bytes." B";
  }

  /**
   * Gibt die Zeit in der größtmöglichen Einheit aus
   * @param  int    $bytes :)
   * @return string        :)
   */
  public static function zeit ($sekunden) : string {
    if ($sekunden < 60) {
      return "weniger als eine Minute";
    }
    if ($sekunden / 60 > 1) {
      $minuten = $sekunden / 60;
      if ($minuten / 60 > 1) {
        $stunden = $minuten / 60;
        // Stunden ausgeben
        if (floor($stunden) == 1) {
          return "eine Stunde";
        } else {
          return floor($stunden)." Stunden";
        }
      }
      // MINUTEN AUSGEBEN
      if (floor($minuten) == 1) {
        return "eine Minute";
      } else {
        return floor($minuten)." Minuten";
      }
    }
  }

}

?>

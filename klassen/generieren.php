<?php
namespace UI;

class Generieren {
  /**
   * Gibt den langen Namen des Monats zurück
   * @param  int $monat :)
   * @return string
   */
  public static function monatnameLang($monat) : string {
    switch ($monat) {
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
      default: return "";
    }
  }
  /**
   * Gibt den kurzen Namen des Monats zurück
   * @param  int $monat :)
   * @return string
   */
  public static function monatnameKurz($monat) : string {
    switch ($monat) {
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
      default: return "";
    }
  }

  /**
   * Gibt den langen Namen des Tags zurück
   * @param  int $tag :)
   * @return string
   */
  public static function tagnameLang($tag) : string {
    switch ($tag) {
      case 0: return "Sonntag";
      case 1: return "Montag";
      case 2: return "Dienstag";
      case 3: return "Mittwoch";
      case 4: return "Donnerstag";
      case 5: return "Freitag";
      case 6: return "Samstag";
      case 7: return "Sonntag";
      default: return "";
    }
  }

  /**
   * Gibt den kurzen Namen des Tags zurück
   * @param  int $tag :)
   * @return string
   */
  public static function tagnameKurz($tag) : string {
    switch ($tag) {
      case 0: return "SO";
      case 1: return "MO";
      case 2: return "DI";
      case 3: return "MI";
      case 4: return "DO";
      case 5: return "FR";
      case 6: return "SA";
      case 7: return "SO";
      default: return "";
    }
  }

  public static function HexZuRgba($x) : string {
    $r = hexdec(substr($x, 1,2));
    $g = hexdec(substr($x, 3,2));
    $b = hexdec(substr($x, 5,2));
    return "rgba($r,$g,$b,1)";
  }
}
?>
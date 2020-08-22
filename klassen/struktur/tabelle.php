<?php
namespace UI\Tabelle {
  class Zeile implements \ArrayAccess {
    /** @var \UI\Icon Icon der Zeile */
    protected $icon;

    /** @var int ID der Zelle als Referenz für das Contextmenü */
    protected $id;

    /** @var string[] Zellen - Assoziatives Array [Spaltenname => Inhalt] */
    protected $zellen;

    /** @var \UI\MiniIconKnopf[] $aktionen Aktionen */
    protected $aktionen;

    /**
     * Erzeugt eine neue Zeile einer Tabelle
     * @param int $id Die ID der Zeile
     */
    public function __construct($id = null) {
      $this->icon     = null;
      $this->id       = $id;
      $this->zellen   = array();
      $this->aktionen = [];
    }

    /**
     * Setzt das Icon der Zeile
     * @param  \UI\Icon $icon :)
     * @return self
     */
    public function setIcon($icon) : self {
      $this->icon = $icon;
      return $this;
    }

    /**
     * Gibt das Icon der Zeile zurück
     * @return \UI\Icon|null
     */
    public function getIcon() : ?\UI\Icon {
      return $this->icon;
    }

    /**
     * Setzt die ID (als Referenz für das Contextmenü)
     * @param  int $id :)
     * @return self
     */
    public function setID($id) : self {
      $this->id = $id;
    }

    /**
     * Gibt die ID der Zeile zurück
     * @return int|null
     */
    public function getID() : ?int {
      return $this->id;
    }

    /**
     * Fügt einen oder mehrer Knöpfe als Aktionen hinzu
     * @param  \UI\MiniIconKnopf $aktionen :)
     * @return self
     */
    public function addAktion(...$aktionen) : self {
      $this->aktionen = array_merge($this->aktionen, $aktionen);
      return $this;
    }

    /**
     * Gibt die Aktionsknöpfe zurück
     * @return \UI\MiniIconKnopf[]
     */
    public function getAktionen() : array {
      return $this->aktionen;
    }

    /*
     * ArrayAccess Methoden
     */

    public function offsetSet($o, $v) {
      if(!is_string($o)) {
        throw new \TypeError("Ungültige Spaltenbezeichnung!");
      }
      $this->zellen[$o] = (string) $v;
    }

    public function offsetExists($o) {
      return isset($this->zellen[$o]);
    }

    public function offsetUnset($o) {
      throw new \Exception("Nicht implementiert!");
    }

    public function offsetGet($o) {
      if(!is_string($o)) {
        throw new \TypeError("Ungültige Spaltenbezeichnung!");
      }
      return $this->zellen[$o] ?? null;
    }
  }
}

namespace UI {
  class Tabelle extends Element implements \ArrayAccess{
    protected $tag = "table";

    /** @var string[] $spalten Spaltenbeschreibungen */
    protected $spalten;

    /** @var Tabelle\Zeile[] $zeilen Zeilen */
    protected $zeilen;

    /** @var Icon $icon Standard-Icon von Zeilen */
    protected $icon;

    /** @var bool $hatIcon Hat die Tabelle ein Icon in der ersten Spalte? */
    protected $hatIcon;

    /** @var int Seite auf der die Tabelle angezeigt wird **/
    protected $seite;

    /** @var int Gesamtzahl an Seiten, die der Tabelle zur Verfügung stehen */
    protected $seitenanzahl;

    /** @var int Anzahl an Datensätzen, die auf einer Seite angezeigt werden können */
    protected $datensaetzeProSeite;

    /** @var string Javascript-Funktion zum neuladen der Tabelle */
    protected $sortierfunktion;

    /** @var string Sortierrichtung der Tabelle */
    protected $sortierrichtung;

    /** @var string Spalte, nach der die Tabelle sortiert ist */
    protected $sortierspalte;

    /** @var bool Wenn true wird sortieren zu Beginn ausgeführt */
    protected $autoladen;


    /**
     * Erzeugt eine neue Tabelle
     * @param string $id :)
     * @param Icon $icon Standard-Icon für Zeilen
     * @param string[] $spalten Spaltenüberschriften
     */
    public function __construct($id, $icon = null, ...$spalten) {
      parent::__construct();
      $this->id       = $id;
      $this->spalten  = $spalten;
      $this->zeilen   = [];
      $this->icon     = $icon;
      $this->hatIcon  = true;
      $this->addKlasse("dshUiTabelle");
      $this->addKlasse("dshUiTabelleListe");
      $this->seite = 1;
      $this->seitenanzahl = 1;
      $this->datensaetzeProSeite = 25;
      $this->sortierfunktion = "null";
      $this->autoladen = false;
      $this->sortierrichtung = "ASC";
      $this->sortierspalte = 0;
    }

    /**
     * Setzt das Attribut autoladen
     * @param  bool $autoladen true = Autoladen, false tabelle statisch
     * @return self            :)
     */
    public function setAutoladen($autoladen) : self {
      $this->autoladen = $autoladen;
      return $this;
    }

    /**
     * Setzt das Attribut sortierfunktion (JS-Funktion zum Sortieren)
     * @param  string $sortierfunktion
     * @return self            :)
     */
    public function setSortierfunktion($sortierfunktion) : self {
      $this->sortierfunktion = $sortierfunktion;
      return $this;
    }

    /**
     * Setzt alle Variablen zur Verwendung von Seiten
     * @param array  $tanfrage Rückgabe einer Tabellenanfrage   :)
     * @param string $sortierfunktion    JS-Funktion zum Sortieren
     * @return self
     */
    public function setSeiten($tanfrage, $sortierfunktion) : self {
      $this->seite = $tanfrage["Seite"];
      $this->seitenanzahl = $tanfrage["Seitenanzahl"];
      $this->datensaetzeProSeite = $tanfrage["DatenProSeite"];
      $this->sortierrichtung = $tanfrage["Richtung"];
      $this->sortierspalte = $tanfrage["Spalte"];
      $this->sortierfunktion = $sortierfunktion;
      return $this;
    }

    /**
     * Setzt, ob die Tabelle ein Icon in der ersten Spalte hat hat
     * @param  bool $icon :)
     * @return self
     */
    public function setHatIcon($icon) : self {
      $this->hatIcon = $icon;
      return $this;
    }

    /**
     * Setzt das Standard-Icon der Zeilen
     * @param  Icon $icon :)
     * @return self
     */
    public function setIcon($icon) : self {
      $this->icon = $icon;
      return $this;
    }

    /**
     * Gibt das Standard-Icon der Zeilen zurück
     * @return Icon|null
     */
    public function getIcon() : ?Icon {
      return $this->icon;
    }

    /**
     * Fügt eine oder mehrere Spaltenüberschriften hinzu
     * @param  string[] $spalten :)
     * @return self
     */
    public function addSpalte(...$spalten) : self {
      $this->spalten = array_merge($this->spalten, $spalten);
      return $this;
    }

    public function __toString() : string {
      $code = "";
      if($this->autoladen) {
        $code = "<div id=\"{$this->id}Ladebereich\" class=\"dshUiTabelleO\">";
      }
      $this->setAttribut("data-sortierfunktion", $this->sortierfunktion);
      $code .=  "<div class=\"dshUiTabelleI\">";
      $code .= $this->codeAuf();
      $code .= "<thead><tr>";
      $anzzeilen  = count($this->zeilen);
      $anzspalten = count($this->spalten);

      if(!$this->autoladen && $this->hatIcon && $anzzeilen > 0) {
        $code .= "<th class=\"dshUiTabelleIconSpalte\"></th>";
      }

      foreach($this->spalten as $nr => $s) {
        if($anzzeilen > 0) {

          $istVerschieden = false;
          $spalteWert = null;
          foreach($this->zeilen as $z) {
            if($spalteWert !== null) {
              if($spalteWert !== (string) $z[$s]) {
                $istVerschieden = true;
                break;
              }
            }
            $spalteWert = (string) $z[$s];
          }
          $code .= "<th>$s";
          if($istVerschieden) {
            $code .= new Sortierknopf($this->id, "ASC", $nr);
            $code .= new Sortierknopf($this->id, "DESC", $nr);
          }
          $code .= "</th>";
        } else {
          $code .= "<th>$s</th>";
        }
      }

      $hatAktionen = false;
      foreach($this->zeilen as $z) {
        if(count($z->getAktionen()) > 0) {
          // Mind. 1 Zeile hat > 0 Aktionen
          $hatAktionen = true;
          break;
        }
      }

      if(!$this->autoladen && $hatAktionen) {
        $code .= "<th class=\"dshUiTabelleIconSpalte\"></th>";
      }

      $code .= "</tr></thead><tbody>";
      if($this->autoladen) {
        $code .= "<tr><td colspan=\"$anzspalten\" class=\"dshUiTabelleLeer dshUiNotiz\">– Der Inhalt wird geladen –</td></tr>";
      } else {
        if($anzzeilen === 0) {
          $code .= "<tr><td colspan=\"$anzspalten\" class=\"dshUiTabelleLeer dshUiNotiz\">– Keine Datensätze –</td></tr>";
        } else {
          foreach($this->zeilen as $znr => $z) {
            $code .= "<tr>";
            if($this->hatIcon) {
              $icon = $z->getIcon() ?? $this->getIcon();
              $code .= "<td class=\"dshUiTabelleIconSpalte\">$icon</td>";
            }
            foreach ($this->spalten as $snr => $s) {
              $code .= "<td>".($z[$s] ?? "")."</td>";
            }
            if($hatAktionen) {
              $a = join(" ", $z->getAktionen());
              if($z->getID() !== null) {
                $a .= (new \UI\VerstecktesFeld(null, $z->getID()))->addKlasse("dshTabelleZeileID");
              }
              $code .= "<td class=\"dshUiTabelleIconSpalte\">$a</td>";
            }
            $code .= "</tr>";
          }
        }
      }

      $spanz = $anzspalten;
      if ($this->hatIcon) {$spanz++;}
      if ($hatAktionen) {$spanz++;}

      $code .= "<tr><td class=\"dshUiTabelleFuss\" colspan=\"$spanz\">";
      $seitenfeld = new Auswahl("{$this->id}Seite", $this->seite);
      $seitenfeld->addFunktion("oninput", "ui.tabelle.sortieren('{$this->id}')");
      $vis = "";
      if($this->seite == 1) {
        $vis = "; visibility: collapse";
      }
      $nachlinks = "<i class=\"fas fa-caret-left\" style=\"padding: 10px;cursor:pointer$vis\" onclick=\"$('#{$this->id}Seite').setWert(".($this->seite-1).");ui.tabelle.sortieren('{$this->id}')\"></i>";
      $vis = "";
      if($this->seite == $this->seitenanzahl) {
        $vis = "; visibility: collapse";
      }
      $nachrechts = "<i class=\"fas fa-caret-right\" style=\"padding: 10px;cursor:pointer$vis\" onclick=\"$('#{$this->id}Seite').setWert(".($this->seite+1).");ui.tabelle.sortieren('{$this->id}')\"></i>";
      for ($i=1; $i<=$this->seitenanzahl; $i++) {
        $seitenfeld->add("$i / {$this->seitenanzahl}", $i);
      }
      if($this->seitenanzahl > 1) {
        $code .= "<span style=\"float:left\">$nachlinks$seitenfeld$nachrechts</span>";
      } else {
        $code .= "<span style=\"display: none;float:left\">$seitenfeld</span>";
      }
      $proSeite = new Auswahl("{$this->id}DatenProSeite", $this->datensaetzeProSeite);
      $proSeite->addFunktion("oninput", "ui.tabelle.sortieren('{$this->id}')");
      $proSeite->add("25", "25");
      $proSeite->add("50", "50");
      $proSeite->add("75", "75");
      $proSeite->add("100", "100");
      $proSeite->add("alle", "alle");
      if($anzzeilen === 0) {
        $code .= "<span style=\"display: none\">";
      }
      $code .= "$proSeite pro Seite";
      $code .= new VerstecktesFeld("{$this->id}SortierenRichtung", $this->sortierrichtung);
      $code .= new VerstecktesFeld("{$this->id}SortierenSpalte", $this->sortierspalte);
      if($anzzeilen === 0) {
        $code .= "</span>";
      }
      $code .= "</td></tr>";
      $code .= "</tbody>{$this->codeZu()}";
      $code .= "</div>";

      if ($this->autoladen) {
        $code .= "<script>ui.tabelle.sortieren('{$this->id}')</script>";
        $code .= "</div>";
      }
      return $code;
    }

    /*
     * ArrayAccess Methoden
     */

    public function offsetSet($o, $v) {
      if(!($v instanceof Tabelle\Zeile)) {
        throw new \TypeError("Die übergebe Zeile ist nicht vom Typ \\UI\\Tabele\\Zeile");
      }
      if(!is_null($o)) {
        throw new \Exception("Nicht implementiert!");
      }
      $this->zeilen[] = $v;
    }

    public function offsetExists($o) {
      throw new \Exception("Nicht implementiert!");
    }

    public function offsetUnset($o) {
      throw new \Exception("Nicht implementiert!");
    }

    public function offsetGet($o) {
      throw new \Exception("Nicht implementiert!");
    }
  }

  class FormularFeld extends InhaltElement {
    protected $tag = "tr";
    /** @var InhaltElement Bezeichnung des Eingabefeldes */
    private $label;
    /** @var Eingabe */
    private $eingabe;
    /** @var bool true, wenn Feld Optional, false sonst*/
    private $optional;

    /**
     * Erstellt ein neues FormularFeld
     * @param InhaltElement $label   :)
     * @param Eingabe       $eingabe :)
     */
    public function __construct($label, $eingabe) {
      parent::__construct();
      $this->label = $label;
      $this->label->setTag("label");
      $this->eingabe = $eingabe;
      $this->optional = false;
      if ($eingabe->getID() === null) {
        throw new \Exception("Keine ID übergeben");
      }
    }

    /**
     * Setzt den Optionalwert auf den übergebenen Wert
     * @param  bool $optional true, wenn das Feld Optional ist, false sonst
     * @return self           :)
     */
    public function setOptional($optional) : self {
      $this->optional = $optional;
      return $this;
    }

    /**
     * Gibt das FormularFeld als HTML-Code zurück
     * @return string :)
     */
    public function __toString() : string {
      if ($this->eingabe->getID() === null) {
        throw new \Exception("Keine ID übergeben");
      }
      $this->label->setAttribut("for", $this->eingabe->getID());
      $code = $this->codeAuf()."<th>{$this->label}</th>";
      $this->eingabe->setKlasse($this->optional, "dshUiEingabefeldOptional");
      $code .= "<td>{$this->eingabe}";
      if ($this->optional) {
        $code .= new Notiz("Optional");
      }
      $code .= "</td>".$this->codeZu();
      return $code;
    }
  }

  class FormularTabelle extends Element implements \ArrayAccess{
    protected $tag = "form";

    /** @var FormularFeld[] $zeilen :) */
    protected $zeilen;

    /** @var Knopf[] $knoepfe :) */
    protected $knoepfe;

    public function __construct(...$zeilen) {
      parent::__construct();
      $this->zeilen = $zeilen;
      $this->addKlasse("dshUiFormular");
      $this->aktionen->setFunktion("onsubmit", -1, "return false");
      $this->knoepfe = [];
    }

    /**
     * Fügt dem Formular neue Knöpfe hinzu
     * @param  Knopf[] $knopf [description]
     * @return self          [description]
     */
    public function addKnopf(...$knopf) : self {
      foreach ($knopf as $k) {
        $this->knoepfe[] = $k;
      }
      return $this;
    }

    /**
     * Fügt Zeilen in die Tabelle hinzu
     * @param  string[] $zellen Zeilen der Tabelle
     * @return self             :)
     */
    public function addZeile(...$zeilen) : self {
      foreach ($zeilen as $z) {
        $this->zeilen[] = $z;
      }
      return $this;
    }

    /**
     * Kurz für <code>$this->addFunktion("submit", $submit);</code>
     * @param  string $submit :)
     * @return self
     */
    public function addSubmit($submit) : self {
      $this->aktionen->addFunktionPrioritaet("onsubmit", 0, "try{{$submit}}catch(_){console.error(_)}");
      return $this;
    }

    /**
     * Gibt die Tabelle in HTML-Code zurück
     * @return string :)
     */
    public function __toString() : string {
      $code  = "<div class=\"dshUiTabelleO\">";
      $code .= "<div class=\"dshUiTabelleI\">";
      $code .= $this->codeAuf();
      $code .= "<table class=\"dshUiTabelle dshUiTabelleFormular\"><tbody>";
      foreach($this->zeilen as $z) {
        $code .= $z;
      }
      if (count($this->knoepfe) > 0) {
        $code .= "<tr class=\"dshUiTabelleFormularKnoepfe\"><td colspan=\"2\">";
        foreach ($this->knoepfe as $k) {
          $code .= $k." ";
        }
        $code .= "</td></tr>";
      }
      $code .= "</tbody></table>";
      $code .= (new Icon(Konstanten::AUSFUELLEN))->addKlasse("dshUiFormularAusfuellen");
      $code .= $this->codeZu();
      $code .= "</div></div>";
      return $code;
    }

    /*
     * ArrayAccess Methoden
     */

    public function offsetSet($o, $v) {
      if(!($v instanceof \UI\FormularFeld) && !($v instanceof \UI\Knopf)) {
        throw new \TypeError("Der übergebene Wert ist kein FormularFeld und kein Knopf");
      }
      if(!is_int($o) && !is_null($o)) {
        throw new \TypeError("Der übergebene Offset ist keine Ganzzahl und nicht null");
      }
      if($v instanceof \UI\FormularFeld) {
        if(is_null($o)) {
          $this->zeilen[]   = $v;
        } else {
          $this->zeilen[$o] = $v;
        }
      } else if($v instanceof \UI\Knopf) {
        if(is_null($o)) {
          $this->knoepfe[]   = $v;
        } else {
          $this->knoepfe[$o] = $v;
        }
      }
    }

    public function offsetExists($o) {
      throw new \Exception("Nicht implementiert! Spezifische Methoden nutzen.");
    }

    public function offsetUnset($o) {
      throw new \Exception("Nicht implementiert! Spezifische Methoden nutzen.");
    }

    public function offsetGet($o) {
      throw new \Exception("Nicht implementiert! Spezifische Methoden nutzen.");
    }
  }
}
?>
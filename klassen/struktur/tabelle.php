<?php
namespace UI\Tabelle {
  class Zeile implements \ArrayAccess {
    /** @var \UI\Icon Icon der Zeile */
    protected $icon;

    /** @var string[] Zellen - Assoziatives Array [Spaltenname => Inhalt] */
    protected $zellen;

    /** @var \UI\MiniIconKnopf[] $aktionen Aktionen */
    protected $aktionen;

    public function __construct() {
      $this->icon     = null;
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
      throw new \Exception("Nicht implementiert!");
    }

    public function offsetUnset($o) {
      throw new \Exception("Nicht implementiert!");
    }

    public function offsetGet($o) {
      if(!is_string($o)) {
        throw new \TypeError("Ungültige Spaltenbezeichnung!");
      }
      return $this->zellen[$o];
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
      $code = "<div class=\"dshUiTabelleO\">";
      $code .= $this->codeAuf();
      $code .= "<thead id=\"{$this->id}Kopf\"><tr>";
      $anzzeilen  = count($this->zeilen);
      $anzspalten = count($this->spalten);

      if($this->hatIcon && $anzzeilen > 0) {
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
            $code .= new Sortierknopf("ASC", $this->id, $nr);
            $code .= new Sortierknopf("DESC", $this->id, $nr);
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

      if($hatAktionen) {
        $code .= "<th class=\"dshUiTabelleIconSpalte\"></th>";
      }

      $code .= "</tr></thead><tbody id=\"{$this->id}Koerper\">";

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
            $code .= "<td id=\"{$this->id}Z{$znr}S$snr\">{$z[$s]}</td>";
          }
          if($hatAktionen) {
            $a = join(" ", $z->getAktionen());
            $code .= "<td class=\"dshUiTabelleIconSpalte\">{$a}</td>";
          }
          $code .= "</tr>";
        }
      }

      $code .= "</tbody>{$this->codeZu()}";
      $code .= new VerstecktesFeld("{$this->id}ZAnzahl", $anzzeilen);
      $code .= new VerstecktesFeld("{$this->id}SAnzahl", $anzspalten);
      $code .= "</div>";
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

  class FormularFeld {
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
      $code = "<tr><th>{$this->label}</th>";
      $this->eingabe->setKlasse($this->optional, "dshUiEingabefeldOptional");
      $code .= "<td>{$this->eingabe}";
      if ($this->optional) {
        $code .= new Notiz("Optional");
      }
      $code .= "</td></tr>";
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
     * Kurz für <code>$this->getAktionen()->addFunktion("submit", $submit);</code>
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
      $code .= "</div>";
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
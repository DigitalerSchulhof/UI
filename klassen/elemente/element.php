<?php
namespace UI;

/**
 * Das Element ist die Grundklasse jeder UI-Komponente. Es enthält für jedes Element notwendige Attribute wie ID, Klassen, Tag, ... und wesentliche Funktionen um diese zu manipulieren, und das Element schließlich auszugeben.
 */
abstract class Element {
	/** @var string Tag des Elements */
	protected $tag = null;
	/** @var string[] CSS-Klassen des Elements */
	protected $klassen = [];
	/** @var string ID des Elements - Weggelassen wenn <code>null</code> */
	protected $id = null;
	/** @var Aktionen Aktionen des Elements */
	protected $aktionen = null;
  /** @var String Attribute des Elements
   * [Attribut] => Wert
   */
  protected $attribute;
  /** @var String Extra Styles des Elements
   * [Property] => Wert
   */
  protected $styles;
  /** @var Hinweis Hinweis des Elements */
  protected $hinweis;

	/**
	 * Erzeugt ein neues UI-Element
	 */
	public function __construct() {
		$this->aktionen  = new Aktionen();
    $this->attribute = [];
    $this->styles    = [];
    $this->hinweis   = null;
	}

	/**
	 * Fügt eine oder mehrere CSS-Klasse(n) hinzu
	 * @param 	string ...$klassen Hinzuzufügende CSS-Klasse(n)
	 * @return 	self
	 */
	public function addKlasse(...$klassen) : self {
    foreach ($klassen as $k) {
      if (!in_array($k, $this->klassen)) {
        $this->klassen[] = $k;
      }
    }
		return $this;
	}

  /**
   * Ermöglicht den direkten Zugriff auf das Hinzufügen von Aktionen
   * @param  string $ausloeser  :)
   * @param  string $funktionen :)
   * @return self
   */
  public function addFunktion($ausloeser, ...$funktionen) : self {
    $this->aktionen->addFunktion($ausloeser, ...$funktionen);
    return $this;
  }

	/**
	 * Entfernt eine oder mehrere CSS-Klasse(n)
	 * @param 	string ...$klassen Zu entfernende Klasse(n)
	 * @return 	self
	 */
	public function removeKlasse(...$klassen) : self {
		$this->klassen = array_diff($this->klassen, $klassen);
		return $this;
	}

	/**
	 * Gibt zurück, ob eine CSS-Klasse vorhanden ist
	 * @param 	string $klasse Die zu prüfende Klasse
	 * @return 	boolean Ob die Klasse vorhanden ist
	 */
	public function hatKlasse($klasse) : boolean {
		return in_array($klasse, $this->klassen);
	}

  /**
   * Setzt oder entfernt je nach <code>$set</code> eine oder mehrere CSS-Klassen
   * @param  bool   $set     Ob die CSS-Klassen gesetzt oder entfernt werden sollen
   * @param  string ...$klassen :)
   * @return self
   */
  public function setKlasse($set, ...$klassen) : self {
    if($set) {
      return $this->addKlasse(...$klassen);
    }
    return $this->removeKlasse(...$klassen);
  }


	/**
	 * Setzt ein Attribut
	 * @param 	string $attribut :)
	 * @param 	string $wert :)
	 * @return 	self
	 */
	public function setAttribut($attribut, $wert = "true") : self {
    if($wert === null) {
      unset($this->attribute[$attribut]);
    } else {
      $this->attribute[$attribut] = $wert;
    }
		return $this;
	}

  /**
   * Gibt den Wert eines Attributes zurück. <code>null</code> wenn es nicht gesetzt ist.
   * @param  string $attribut :)
   * @return mixed
   */
  public function getAttribut($attribut) {
    return $this->attribute[$attribut] ?? null;
  }


	/**
	 * Setzt einen extra Style
	 * @param 	string $property CSS-Property
	 * @param 	string $wert :)
	 * @return 	self
	 */
	public function setStyle($property, $wert = "true") : self {
    if($wert === null) {
      unset($this->styles[$property]);
    } else {
      $this->styles[$property] = $wert;
    }
		return $this;
	}

  /**
   * Gibt den Wert einer CSS-Property zurück. <code>null</code> wenn diese nicht gesetzt ist.
   * @param  string $property :)
   * @return mixed
   */
  public function getStyle($property) {
    return $this->styles[$property] ?? null;
  }

	/**
	 * Setzt die ID
	 * @param 	string $id :)
	 * @return 	self
	 */
	public function setID($id) : self {
		$this->id = $id;
		return $this;
	}

	/**
	 * Gibt die ID zurück
	 * @return 	string
	 */
	public function getID() : string {
		return $this->id;
	}

	/**
	 * Setzt den Tag
	 * @param 	string $tag :)
	 * @return 	self
	 */
	public function setTag($tag) : self {
		$this->tag = $tag;
		return $this;
	}

	/**
	 * Gibt den Tag zurück
	 * @return 	string
	 */
	public function getTag() : string {
		return $this->tag;
	}

	/**
	 * Überschreibt den Hinweis
	 * @param  Hinweis $hinweis :)
	 * @return self
	 */
	public function setHinweis($hinweis) : self {
		$this->hinweis = $hinweis;
		return $this;
	}

	/**
	 * Gibt die Aktionen als Objekt zurück
	 * @return 	Aktionen
	 */
	public function getAktionen() : Aktionen {
		return $this->aktionen;
	}

	/**
	 * Überschreibt die Aktionen
	 * @param 	Aktionen $aktionen :)
	 * @return 	self
	 */
	public function setAktionen($aktionen) : self {
		$this->aktionen = $aktionen;
		return $this;
	}

  /**
   * Gibt sich selbst zurück. Der Übersicht haber
   * @return self
   */
  public function self() : self {
    return $this;
  }

  /**
   * Setzt den Titel des Elements
   * Kurzform für Element::setAttribut("title", $titel);
   * @param  string $titel :)
   * @return self
   */
  public function setTitel($titel) : self {
    $this->setAttribut("title", $titel);
    return $this;
  }

  public function toStringVorbereitung() : self {

  }

  /**
	 * Gibt den Code des öffnenden Tags zurück
	 * @param   boolean $klammer True => mit < >; False => Ohne < >
	 * @param 	string ...$nicht Attribute, die ignoriert werden sollen
	 * @return 	string Der Code des öffnenden Tags
	 */
	public function codeAuf($klammer = true, ...$nicht) : string {
    $rueck = "";
    if($klammer) {
      $rueck = "<";
    }

    if($this->hinweis !== null && !in_array("hinweis", $nicht)) {
      $this->addKlasse("dshUiHinweisTraeger");
    }

	  $rueck .= $this->tag;
		if($this->tag === null)
			$rueck = "";

		if($this->id !== null && !in_array("id", $nicht)) {
			$rueck .= " id=\"{$this->id}\"";
    }

		if(count($this->klassen) > 0 && !in_array("class", $nicht)) {
			$rueck .= " class=\"".join(" ", $this->klassen)."\"";
    }

		if($this->aktionen->count() > 0 && !in_array("aktionen", $nicht)) {
			$rueck .= " {$this->aktionen}";
    }

    if(!in_array("attribute", $nicht)) {
      foreach($this->attribute as $attribut => $wert) {
        $rueck .= " $attribut=\"$wert\"";
      }
    }

    if(!in_array("style", $nicht) && count($this->styles) > 0) {
      $rueck .= " style=\"";
      foreach($this->styles as $p => $w) {
        $rueck .= "$p:$w;";
      }
      $rueck .= "\"";
    }

    if($klammer) {
      $rueck .= ">";
    }

    if($this->hinweis !== null && !in_array("hinweis", $nicht)) {
      $rueck .= $this->hinweis;
    }

		return $rueck;
	}

	/**
	 * Gibt den Code des schließenden Tags zurück (Mit < >)
	 * @return 	string Der Code des schließenden Tags
	 */
	public function codeZu() : string {
		if($this->tag === null)
			return "";
		// Siehe: https://html.spec.whatwg.org/multipage/syntax.html#elements-2
		if(in_array($this->tag, array("area", "base", "br", "col", "embed", "hr", "img", "input", "link", "meta", "param", "source", "track", "wbr")))
			return "";
		return "</{$this->tag}>";
	}


	/**
   * Gibt den HTML-Code des Elements zurück
   *
   * @return 	string HTML-Code
   */
	 public function __toString() : string {
     return "{$this->codeAuf()}{$this->codeZu()}";
   }
}

/**
 * Generisches Element mit Inhalt ohne festen Verwendungszweck
 */
class InhaltElement extends Element {
  protected $tag = "span";
	/** @var string Inhalt des Absatzes */
	protected $inhalt;

	/**
	 * Erzeugt ein neues UI-Element
	 * @param 	string $inhalt Inhalt des Absatzes
	 */
	 public function __construct($inhalt = "") {
		 parent::__construct();
		 $this->inhalt 	= $inhalt;
	}

  /**
   * Setzt den Inhalt
   * @param string $inhalt :)
   * @return self
   */
  public function setInhalt($inhalt) : self {
    $this->inhalt = $inhalt;
    return $this;
  }

	public function __toString() : string {
		return "{$this->codeAuf()}{$this->inhalt}{$this->codeZu()}";
	}
}

class Box extends Element implements \ArrayAccess {
  protected $tag = "div";
  /** @var string[] Kinder der Box */
  protected $kinder;

  /**
   * Erzeugt eine neue Box
   * @param string ...$kinder :)
   */
  public function __construct(...$kinder) {
    parent::__construct();
    $this->kinder = $kinder;
  }

  public function __toString() : string {
    return "{$this->codeAuf()}".join("", $this->kinder)."{$this->codeZu()}";
  }

  /*
   * ArrayAccess Methoden
   */

  public function offsetSet($o, $v) {
    if(!is_null($o)) {
      throw new \Exception("Nicht implementiert!");
    }
    $this->kinder[]   = (string) $v;
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

?>

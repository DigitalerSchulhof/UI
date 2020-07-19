<?php
namespace UI\Elemente;

/**
 * Das Element ist die Grundklasse jeder UI-Komponente. Es enthält für jedes Element notwendige Attribute wie ID, Klassen, Tag, ... und wesentliche Funktionen um diese zu manipulieren, und das Element schließlich auszugeben.
 */
abstract class Element {
	/** @var string Tag des Elements */
	protected $tag = null;
	/** @var string[] CSS-Klassen des Elements */
	protected $klassen = array();
	/** @var string ID des Elements - Weggelassen wenn <code>null</code> */
	protected $id = null;
	/** @var Aktionen Aktionen des Elements */
	protected $aktionen = null;

	/**
	 * Erzeugt ein neues UI-Element
	 */
	public function __construct() {
		$this->aktionen = new Aktionen();
	}

	/**
	 * Fügt eine oder mehrere CSS-Klasse(n) hinzu
	 * @param 	string ...$klassen Hinzuzufügende CSS-Klasse(n)
	 * @return 	self
	 */
	public function addKlasse(...$klassen) : self {
		$this->klassen = array_merge($this->klassen, $klassen);
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

  public function toStringVorbereitung() : self {

  }

  /**
	 * Gibt den Code des öffnenden Tags zurück (Ohne < >)
	 * @param   boolean $klammer True => mit < >; False => Ohne < >
	 * @param 	string ...$nicht Attribute, die ignoriert werden sollen
	 * @return 	string Der Code des öffnenden Tags
	 */
	public function codeAuf($klammer = true, ...$nicht) : string {
    $rueck = "";
    if($klammer) {
      $rueck = "<";
    }

	  $rueck .= $this->tag;
		if($this->tag === null)
			$rueck = "";

		if($this->id !== null && !in_array("id", $nicht))
			$rueck .= " id='{$this->id}'";

		if(count($this->klassen) > 0 && !in_array("class", $nicht))
			$rueck .= " class='".join(" ", $this->klassen)."'";

		if($this->aktionen !== null && $this->aktionen->count() > 0 && !in_array("aktionen", $nicht))
			$rueck .= " {$this->aktionen}";

    if($klammer) {
      $rueck .= ">";
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

	public function __toString() : string {
		return "{$this->codeAuf()}{$this->inhalt}{$this->codeZu()}";
	}
}

?>

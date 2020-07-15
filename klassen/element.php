<?php
namespace UI\Elemente;

/**
 * @author DSH
 *
 * Das Element ist die Grundklasse jeder UI-Komponente. Es enthält für jedes Element notwendige Attribute wie ID, Klassen, Tag, ... und wesentliche Funktionen um diese zu manipulieren, und das Element schließlich auszugeben.
 */
abstract class Element {
	/** @var string Tag des Elements */
	protected $tag = null;
	/** @var string[] CSS-Klassen des Elements */
	protected $klassen = array();
	/** @var string ID des Elements - Weggelassen wenn <code>null</code> */
	protected $id = null;
	/** @var string[][] Aktionen des Elements */
	protected $aktionen = array();

	/**
	 * Erzeugt ein neues UI-Element
	 */
	public function __construct() {

	}

	/**
	 * Fügt eine oder mehrere CSS-Klasse(n) hinzu
	 * @param string ...$klassen Hinzuzufügende CSS-Klasse(n)
	 * @return self
	 */
	public function dazuKlasse(...$klassen) : self {
		$this->klassen = array_merge($this->klassen, $klassen);
		return $this;
	}

	/**
	 * Entfernt eine oder mehrere CSS-Klasse(n)
	 * @param string ...$klassen Zu entfernende Klasse(n)
	 * @return self
	 */
	public function wegKlasse(...$klassen) : self {
		$this->klassen = array_diff($this->klassen, $klassen);
		return $this;
	}

	/**
	 * Gibt zurück, ob eine CSS-Klasse vorhanden ist
	 * @param string $klasse Die zu prüfende Klasse
	 * @return boolean Ob die Klasse vorhanden ist
	 */
	public function hatKlasse($klasse) : boolean {
		return in_array($klasse, $this->klassen);
	}

	/**
	 * Setzt die ID
	 * @param string $id :)
	 * @return self
	 */
	public function setID($id) : self {
		$this->id = $id;
		return $this;
	}

	/**
	 * Gibt die ID zurück
	 * @return string
	 */
	public function getID() : string {
		return $this->id;
	}

	/**
	 * Setzt den Tag
	 * @param string $tag :)
	 * @return self
	 */
	public function setTag($tag) : self {
		$this->tag = $tag;
		return $this;
	}

	/**
	 * Gibt den Tag zurück
	 * @return string
	 */
	public function getTag() : string {
		return $this->tag;
	}

	/**
	 * Fügt einen oder mehrere Eventhandler hinzu
	 * @param string $listener Event, auf das gehört werden soll
	 * @param string ...$handler Was passiert, wenn das Event auftritt
	 * @return self
	 */
	public function dazuAktion($listener, ...$handler) : self {
		$this->aktionen[$listener] = array_merge($this->aktionen[$listener] ?? array(), $handler);
		return $this;
	}

	/**
	 * Entfernt alle Aktionen des(/der) übergebenen Events
	 * @param string ...$listener Zu entfernende(s) Eventlistener
	 * @return self
	 */
	public function wegAktion(...$listener) : self {
		foreach($listener as $listener)
			unset($this->aktionen[$listener]);
		return $this;
	}

	/**
	 * Gibt den Code des öffnenden Tags zurück (Ohne < >)
	 * @param string ...$nicht Attribute, die ignoriert werden sollen
	 * @return string Der Code des öffnenden Tags
	 */
	public function codeAuf(...$nicht) : string {
		$rueck = $this->tag;
		if($this->tag === null)
			$rueck = "";

		if($this->id !== null && !in_array("id", $nicht))
			$rueck .= " id='{$this->id}'";

		if(count($this->klassen) > 0 && !in_array("class", $nicht))
			$rueck .= " class='".join(" ", $this->klassen)."'";

		foreach($this->aktionen as $listener => $handler) {
			if(in_array($listener, $nicht))
				continue;
			$rueck .= " $listener='";
			foreach($handler as $hi => $h) {
				if($hi > 0)
					$rueck .= ";";
				$rueck .= "$h";
			}
			$rueck .= "'";
		}

		return $rueck;
	}

	/**
	 * Gibt den Code des schließenden Tags zurück (Mit < >)
	 * @return string Der Code des schließenden Tags
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
   * Folgende Werte sind verfügbar:
   * - $this->class: 			CSS-Klassenliste in "class="-Form
   * - $this->id:					Elemente-ID in "id="-Form
   * - $this->attribute:	$this->class und $this->id zusammengesetzt mit Leerzeichen am Anfgang
   *
   * @return string HTML-Code
   */
	 public function __toString() : string {
     return "<{$this->codeAuf()}>{$this->codeZu()}";
   }
}

/**
 * @author DSH
 *
 * Generisches Element mit Inhalt ohne festen Verwendungszweck
 */
class InhaltElement extends Element {
	/** @var string Inhalt des Absatzes */
	protected $inhalt;

	/**
	 * Erzeugt ein neues UI-Element
	 * @param string $tag Tag des Elements
	 * @param string $inhalt Inhalt des Absatzes
	 */
	 public function __construct($tag, $inhalt = "") {
		 $this->tag			= $tag;
		 $this->inhalt 	= $inhalt;
	}

	public function __toString() : string {
		return "<{$this->codeAuf()}>{$this->inhalt}{$this->codeZu()}";
	}
}

?>
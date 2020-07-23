<?php
namespace UI;

/**
 * @author DSH
 *
 * Aktionen enthällt alle on*-Aktionen eines Elements, sowie was dann passiert.
 * Auslöser haben Funktionen, die als <code>auslöser="funktion;funktion;funktion" auslöser="funktion;funktion"</code> ausgegeben werden.
 * Funktionen haben eine Priorität, die zugleich als Platz gilt. Je höher die Priorität, desto weiter vorne steht die Funktion bei der Ausgabe der Aktionen.
 */
class Aktionen {
	/** @var string[][][] Aktionen
	 * $aktionen[Auslöser][Prioritäten][Funktionen]
	 *
	 * Reservierte Prioritäten:
	 * - 3: 	Spezifische Funktionen für Eingabefelder, die vor anderen Funktionen passieren müssen
	 *
	 * - -1: 	Standard
	 */
	protected $aktionen = null;

	public function __construct() {
		$this->aktionen = array();
	}

	/**
	 * Überschreibt die Funktionen mit der angegebenen Priorität. <b>Zuvor übergebene Funktionen dieser Priorität dieses Auslösers gehen verloren!</b><br>Funktionen können über {@see UI\Aktionen::addAktion} angehängt werden.
	 * @param 	string $ausloeser Auslöser der Funktionen
	 * @param 	int $prioritaet Priorität der Funktionen
	 * @param 	string ...$funktionen Was passiert, wenn der Auslöser auftritt - <code>null</code> zum Leeren
	 */
	public function setFunktion($ausloeser, $prioritaet, ...$funktionen) : self {
		if($funktionen[0] === null && isset($this->aktionen[$ausloeser][$prioritaet])) {
			unset($this->aktionen[$ausloeser][$prioritaet]);
			return $this;
		}
		$ak = $this->aktionen[$ausloeser] ?? array();
		$ak[$prioritaet] = $funktionen;
		$this->aktionen[$ausloeser] = $ak;
		return $this;
	}

  /**
   * Gibt das Array [Priorität][Funktionen] zurück
   * @param  string $ausloeser :)
   * @return string [Priorität][Funktionen]
   */
  public function getFunktionen($ausloeser) {
    return $this->aktionen[$ausloeser] ?? array();
  }

  /**
   * Prüft ob der Auslöser vorhanden ist
   * @param  string  $ausloeser :)
   * @return boolean            :)
   */
  public function hatAusloeser($ausloeser) : bool {
    return isset($this->aktionen[$ausloeser]);
  }

	/**
	 * Fügt die Funktionen mit Priorität <code>-1</code> an den Auslöser an.
	 * @param 	string $ausloeser Auslöser der Funktionen
	 * @param 	string ...$funktionen Was passiert, wenn der Auslöser auftritt
	 * @return 	self
	 */
	public function addFunktion($ausloeser, ...$funktionen) : self {
		return $this->addFunktionPrioritaet($ausloeser, -1, ...$funktionen);
	}

	/**
	 * Fügt die Funktionen mit der Priorität an den Auslöser an.
	 * @param 	string $ausloeser Auslöser der Funktionen
	 * @param 	int $prioritaet Priorität der Funktionen
	 * @param 	string ...$funktionen Was passiert, wenn der Auslöser auftritt
	 * @return 	self
	 */
	public function addFunktionPrioritaet($ausloeser, $prioritaet, ...$funktionen) : self {
		$ak 	= $this->aktionen[$ausloeser] ?? array();
		$akp 	= $ak[$prioritaet] ?? array();
		$this->aktionen[$ausloeser][$prioritaet] = array_merge($akp, $funktionen);
		return $this;
	}

	/**
	 * Löscht alle Funktionen des übergebenen Auslösers
	 * @param 	string ...$ausloeser Auslöser, dessen(/deren) Funktionen gelöscht werden sollen
	 * @return 	self
	 */
	public function removeFunktionen(...$ausloeser) : self {
		foreach($ausloeser as $asl)
			unset($this->aktionen[$asl]);
		return $this;
	}

	/**
	 * Gibt die Anzahl an Auslösern zurück
	 * @return int
	 */
	public function count() : int {
		return count($this->aktionen);
	}

	/**
	 * Gibt die Aktionen als <code>auslöser="funktion;funktion;funktion" auslöser="funktion;funktion"</code> zurück
	 * @return 	string
	 */
	public function __toString() : string {
		$rueck = "";
		$ai = 0;
		foreach($this->aktionen as $ausloeser => $prioritaeten) {
			krsort($prioritaeten);
			if(count($prioritaeten) > 0) {
				// Kein " " beim Ersten
				if($ai++ > 0) {
					$rueck .= " ";
				}
				$rueck .= "$ausloeser=\"";
				foreach($prioritaeten as $funktionen) {
					foreach($funktionen as $fi => $funktion) {
						if($fi > 0) {
							$rueck .= ";";
						}
						$rueck .= $funktion;
					}
					$rueck .= "\"";
				}
			}
		}
		return $rueck;
	}
}

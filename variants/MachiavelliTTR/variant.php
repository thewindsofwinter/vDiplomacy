<?php
/*
	Copyright (C) 2018 Enriador & Oliver Auth

	This file is part of the Machiavelli variant for vDiplomacy

	The Machiavelli variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Machiavelli variant for vDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with vDiplomacy. If not, see <http://www.gnu.org/licenses/>.

	---

	Changelog:
	1.0: initial version
	1.1: Build anywhere fixed.
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class MachiavelliTTRVariant extends WDVariant {
	public $id          =115;
	public $mapID       =115;
	public $name        ='MachiavelliTTR';
	public $fullName    ='Machiavelli - To the Renaissance';
	public $description ='Italy during the birth of the Renaissance.';
	public $author      ='Enriador (original design by S. Craig Taylor & James B. Wood).';
	public $adapter     ='Enriador & Oliver Auth';
	public $version     ='1';
	public $codeVersion ='1.1.2';
	public $homepage    ='https://www.reddit.com/r/diplomacy/comments/bc49nu/machiavellitotherenaissance/';

	public $countries=array('Aragon','Florence','Avignon','Milan','Genoa','Papacy','Venice');

	public function __construct() {
		parent::__construct();

		$this->variantClasses['drawMap']            = $this->name;
		$this->variantClasses['adjudicatorPreGame'] = $this->name;

		// Custom icon units
		$this->variantClasses['OrderInterface']     = $this->name;

		// Build anywhere
		$this->variantClasses['OrderInterface']     = $this->name;
		$this->variantClasses['processOrderBuilds'] = $this->name;
		$this->variantClasses['userOrderBuilds']    = $this->name;

		// Neutral units:
		$this->variantClasses['OrderArchiv']        = $this->name;
		$this->variantClasses['processGame']        = $this->name;
		$this->variantClasses['processMembers']     = $this->name;
	}

	public function countryID($countryName)
	{
		if ($countryName == 'Neutral units') return count($this->countries)+1;
		return parent::countryID($countryName);
	}


	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1253);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1253);
		};';
	}
}

?>

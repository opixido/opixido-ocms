<?php
#
# This file is part of oCMS.
#
# oCMS is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# oCMS is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with oCMS. If not, see <http://www.gnu.org/licenses/>.
#
# @author Celio Conort / Opixido 
# @copyright opixido 2009
# @link http://code.google.com/p/opixido-ocms/
# @package ocms
#



class bloc {
	
	
	public $contenu = array();
	public $visible = true;
	public $nom = 'default';
	public $toAddBefore = array();
	public $toAddAfter = array();
	
	/**
	 * gensite
	 *
	 * @var gensite
	 */
	public $site;
	
	/**
	 * Constructeur
	 *
	 * @param genSite $site
	 */
	function __construct($site) {
		
		$this->site = $site;
		
		
		
	}
	
	/**
	 * Si il est bien visible, on décale le contenu
	 *
	 */	
	function afterInit() {
		
		if($this->visible) {
			
		} 
		
	}
	
	/**
	 * Sets this bloc not to render
	 *
	 */
	function hide() {
		
		$this->visible = false;
		
	}
	
	
	/**
	 * Makes this box visible
	 *
	 */
	function show() {
		
		$this->visible = true;
		
	}	
	
	/**
	 * returns content
	 *
	 */
	function genBloc () {
		
		$html = '<div id="bloc_'.$this->nom.'">';
		if($this->visible ) {
			foreach($this->contenu as $v ) {
				
				$html .= ($v);
				
				
			}
		}
		$html .= '</div>';
		return $html;
		
	}
	
	
	/**
	 * Ajoute une boite à la fin
	 *
	 * @param string $nom Nom de la boite
	 * @param string $html code HTML
	 */
	
	function add($nom,$html) {
		
		if(ake($this->toAddBefore,$nom)) {
			
			foreach($this->toAddBefore[$nom] as $k=>$v) {
				$this->contenu[$k] = $v;
			}
		}
				
		$this->contenu[$nom] = '<div class="bloc" id="bloc_'.$this->nom.'_'.$nom.'">'.$html.'</div>';
		
		if(ake($this->toAddAfter,$nom)) {
			
			foreach($this->toAddAfter[$nom] as $k=>$v) {
				$this->contenu[$k] = $v;
			}
		}
		
	}
	
	/**
	 * Ajoute une boite $nom apres la boite $other
	 *
	 * @param string $other
	 * @param string $nom
	 * @param string $html
	 */
	function addAfter($other,$nom,$html) {
		
		if(ake($other,$this->contenu)) {
			
		}
		else {
			$this->toAddAfter[$other][$nom] ='<div class="bloc" id="col_'.$nom.'">'.$html.'</div>';		
		}
	}
	
	/**
	 * Ajoute une boite $nom apres la boite $other
	 *
	 * @param string $other
	 * @param string $nom
	 * @param string $html
	 */
	function addBefore($other,$nom,$html) {
		
		if(ake($other,$this->contenu)) {
			
		}
		else {
			$this->toAddBefore[$other][$nom] ='<div class="bloc" id="col_'.$nom.'">'.$html.'</div>';		
		}
	}
		
	/**
	 * Ajoute en premier
	 *
	 * @param string $nom
	 * @param string $html
	 */
	
	function addAtTop($nom,$html) {
		
		$this->contenu = array_merge(array($nom=>'<div class="bloc" id="bloc_'.$this->nom.'_'.$nom.'">'.$html.'</div>'),$this->contenu);
		
	}
	
	
	/**
	 * Vide tout
	 *
	 */
	function clean() {
	
		$this->contenu = array();		
	}
	
	/**
	 * Ajoute un petit délimiteur
	 *
	 */
	function addSmallDelim($size=6) {
		
		global $nbDelim;
		$nbDelim++;
		$this->addAtEnd('delim_'.$nbDelim,'
			<div class="clearer">&nbsp;</div>
			<div class="col_delim">			
			</div>
			<div class="clearer">&nbsp;</div>');
		
	}
	
	/**
	 * Ajoute un grand délimiteur
	 *
	 */	
	function addBigDelim() {
		
		
	}
	
	
	function remove($nom) {
		
		unset($this->contenu[$nom]);
		
	}
	
	

	
}

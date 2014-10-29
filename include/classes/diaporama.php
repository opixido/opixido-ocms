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
# @copyright opixido 2012
# @link http://code.google.com/p/opixido-ocms/
# @package ocms
#


class Diaporama {


	private $images = array();
	private $nbImages = 0;
	public $isLightBox = false;
	public $w = 56;
	public $h = 56;
	public $maxW = false;
	public $maxH = false;


	function __construct($isLightBox=false) {

		$this->isLightBox = $isLightBox;
		$GLOBALS['nbDiaporamas']++;
		$this->id=$GLOBALS['nbDiaporamas'];
		if($this->isLightBox) {

		}

	}


	function addImage($genfile,$legende="",$credits="",$alt="") {

		if($credits == '') {
		//	$credits = t('credits_def');
		} else if(!strpos(mb_strtoupper($credits),'CMN') ) {
			$credits .= ' / ';//.t('credits_def');
		}

		$this->nbImages++;
		$this->images[$this->nbImages]['genfile'] = $genfile;
		$this->images[$this->nbImages]['legende'] = $legende;
		$this->images[$this->nbImages]['credits'] = $credits;
		$this->images[$this->nbImages]['alt'] = $alt;

	}


	/**
	 * G�n�re le HTML des grandes images
	 * et celui des petites au passage ... mais il le retournera plus tard
	 * ainsi ca �vite de faire deux boucles ...
	 *
	 * @return string HTML
	 */
	function genGrandes() {

		$k = 0;
		$html = '';
		if(!count($this->images)) {
			return;
		}
		foreach($this->images as $photo) {
			$k++;
			/**
			 * Div de la grande image
			 */

			$this->ids[] = $this->id.'_'.$k;
			if($this->isLightBox) {
				
				//debug($photo);
				$url = $this->maxW || $this->maxH ? $photo['genfile']->getThumbUrl($this->maxW,$this->maxH) : $photo['genfile']->getWebUrl();
				$this->thumbs .= '
								<li><a 
								href="'.$url.'" 
								rel="lightbox'.$this->id.'" ><img class="thumb" 
										src="'.$photo['genfile']->getThumbUrlExact($this->w,$this->h).'"  
										alt="" 
										title="'.$photo['credits'].'"
										onclick="return false;"
										 /></a></li>
								';
				
				
			} else {
				
				$html .= '<div class="m_photo" id="m_photo_'.$this->id.'_'.$k.'">
				
				<img src="'.getServerUrl().$photo['genfile']->getThumbUrl(308,308).'" alt="" ';
					
				if(strlen($photo['credits'])) {
				
					$html .= '	onmouseover="show(\'diapocred'.$this->id.'_'.$k.'\');" onmouseout="hide(\'diapocred'.$this->id.'_'.$k.'\');" />
					<div class="credits" onmouseover="show(\'diapocred'.$this->id.'_'.$k.'\');" id="diapocred'.$this->id.'_'.$k.'">'.$photo['credits'].'</div>
						';
				} else {
					
					$html .= ' /> <br/> ';
				}
				
				$html .= '</div>';
				
				/**
				 * On stock les Identifiants pour les masquer en JS
				 */
				
	
				/**
				 * Vignettes
				 */
	
				$this->thumbs .= '<a '.($k==1 ? ' id="diapo_sel" ' : '').' href="#m_photo_'.$this->id.'_'.$k.'" 
					onclick="showPhotos('.$this->id.',\''.$this->id.'_'.$k.'\',this);return false;">'.$k.'</a>'."\n";
			
			}
			
		}

		/**
		 * On ne masque pas la premiere
		 */

		$cur = array_shift($this->ids);


		/**
		 * Javascript Associé
		 */
		$html .= '
		<script type="text/javascript">
		';
		
		if($this->id == 1) {
			$html .= 'currentPhoto = new Array();
			';
		}
		
		$html .= '
		currentPhoto['.$this->id.'] = "'.$cur.'";
		';

		/**
		 * On masque toutes les autres
		 */
		foreach($this->ids as $id) {
			$html .= 'gid("m_photo_'.$id.'").style.display = "none";';
		}

		$html .= '
		</script>
		';

		return $html;
	}



	/**
	 * Retourne le code HTML des vignettes
	 *
	 * @return string HTML
	 */
	function genVignettes() {
		if(!count($this->images)) {
			return;
		}
		
		if($this->isLightBox) {
				return '<ul class="vignettes">'.$this->thumbs.'</ul>';
		} else 
		if(count($this->images) > 1) {
			return $this->thumbs;			
		} else {
			return '';
		}

	}


	/**
	 * Retourne la totale
	 *
	 * @return string HTML
	 */
	function gen() {

		if(!count($this->images)) {
			return;
		}
		$html = $this->genGrandes();
		$html .= $this->genVignettes();
		return $html;


	}





}
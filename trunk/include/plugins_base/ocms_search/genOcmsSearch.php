<?php



class genOcmsSearch {


		/**
		 * GenSite
		 *
		 * @var GenSite
		 */
	var $site;

	var $nbPerType = array();

	function __construct($site,$params) {

		$this->site = $site;
		$this->site->g_headers->addCss('recherche.css');
		//$this->site->g_headers->addScript('recherche.js');

		$t = getmicrotime();

		$GLOBALS['gb_obj']->includeFile('class.ocms_search.php','plugins/ocms_search');

		$this->site->plugins['menuDroite']->visible = true;

		$this->type = $_REQUEST['type'] ? $_REQUEST['type']  : 'tous';
		$s = new indexSearch();

		$html = '' ;

		$requete = $s->cleanWord($_GET['q']) ;

			$res = $s->search($_GET['q']);


			TrySql('INSERT INTO os_recherches VALUES("",'.sql($_GET['q']).','.count($res).')');

/*
			$html .= ''."\n";


			$this->nbPerType['tous'] = count($res);
			//$html .= ' [ en '.round(getmicrotime()-$t,4).'sec ] '."\n";

			$form = new simpleForm('','get','f_rech');
			$form->add('text',$_GET['q'],t('rechercher'),'q','rech_q');
			$form->add('submit',t('envoyer'));
			$form->add('html','<div class="clearer">&nbsp;</div>');
			$html .= ('

			'.$form->gen().'');
*/
			if ( $requete ) {

			$nbResReal = 0;

			$maxRank = ceil($res[0]['RANK2'] /2);

			foreach($res as $row ) {

					$infos = getRowFromId($row['obj'],$row['fkid'],true);
					if(count($infos)) {


						$nbResReal++;
						$this->nbPerType[$row['obj']]++;

						if($this->type == 'tous' || $this->type ==  $row['obj']) {

								$url = getUrlFromSearch($row,$infos);
								//$url = getUrlFromId(getRubFromGabarit(''));

							$html .= ('<li class="'.$row['obj'].'" >'."\n");
//['.t($row['obj']).'] 
							$html .= ('
							<a href="'.$url.'" class="search_'.$row['obj'].'" ><div class="img">'.getImgFromRow($row['obj'],$infos,50,50).'</div>
							
							'.GetTitleFromRow($row['obj'],$infos).' <!--['.$row['RANK2'].']-->' .
							'<p>'.strip_tags(getDescFromRow($row['obj'],$infos,20)).'</p><div class="clearer"></div></a>'.
							'</li>')."\n";

						}

					//}
				}
			}
			$html .= ('</ul>');


			if(count($this->nbPerType)) {

				$htmlD = '<h3>'.t('rech_types').'</h3><ul class="liste">';
				foreach($this->nbPerType as $k => $v) {

					$htmlD .= '
					<li>
					<a '.($this->type == $k ? 'class="selected"' : '').'
						id="link_'.$k.'"
						href="'.getUrlWithParams(array('type'=>$k)).'/?q='.$_GET['q'].'"
						onclick=" showResultType(\''.$k.'\',this);return false" >'.t('type_'.$k).' : '.$v.'</a>
					</li>';


				}
				$htmlD .= '</ul>';

				//$this->site->g_rubrique->plugins['menuDroite']->addAtEnd('rech',$htmlD);
			}

			if($nbResReal == 0) {
				$nbResReal = t('pas_de');
			}

			//$html = '<div id="recherche_res"><div><p>'.$nbResReal.' '.t('resultats').'</p></div>' ;

			$xhtml = '<div id="recherche">' ;

				$xhtml .= '<h4 class="titre">Recherche</h4>' ;

				$xhtml .= '<p class="mots-clefs">Résultat(s) de recherche pour&nbsp;: "<span>' . htmlentities($_GET [ 'q' ],ENT_QUOTES,'utf-8') . '</span>"</p>' ;

				$xhtml .= '<ul id="resultListe" class="liste liste_grande">'."\n" . $html ;

			$xhtml .= '</div>' ;

		}

		$this->html = $xhtml;

	}


	function gen() {

		return $this->html ;

	}


				
	function ocms_getPicto() {
		
		return ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_ARBO_SIZE.'/actions/system-search.png';
	}
	

}



?>
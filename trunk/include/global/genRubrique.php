<?php

class genRubrique {

	/**
	 * gensite
	 *
	 * @var gensite
	 */
	private $site;
	
	/**
	 * identifiant en cours
	 *
	 * @var int
	 */
	public $rubrique_id;

	private $cache_id;

	private $contexteOnTop;

	private $contextBoxes;

	public $showParagraphes;
	public $showBoxLexique;
	public $showBoxDwl;
	public $showBoxLinks;
	public $html_after_paras;
	public $hasBddInfo;
	public $doGenMain = true;
	public $rubrique = array();
	public $fk_rubrique_version_id = 0;
	
	public $bddClasse = '';
	/**
	 * Liste des objets plugins
	 *
	 * @var array
	 */
	var $plugins = array();
	
	/**
	 * $row du gabarit
	 *
	 * @var array
	 */
	var $gabarit;

	/**
	 * Genere la rubrique avec les classes, paragraphes, ...

		Objets accessibles :
			Lexique : $this->g_boxLexique  (Methode addMot($idMot))
			Telechargements : $this->dwlBox (Method add())
			En savoir plus : $this->linksBox (Method add())

		@param showParagraphes bool Definit si l'on utilise les paragraphes ou non
		@param showBoxLexique = Definit si l'on affiche la boite Lexique
		@param showBoxDwl = Definit si l'on affiche la boite Telechargement
		@param showBoxLinks = Definit si l'on affiche la boite "En savoir plus"

		@param contexteOnTop = Definit si l'on affiche les contextes a "top"  ou "right"

		@param html_after_paras = Code HTML ajoute apres la generation des paragraphes


	*/
	function __construct(genSite $site)
	{

		global $gb_obj, $co;
		
		$this->site = &$site;
		$this->doGenMain = true;
		$this->html_after_paras = '';
		$this->showParagraphes = true;
		$this->showBoxLexique = false;
		$this->showBoxDwl = true;
		$this->showBoxLinks = true;
		$this->hasBddInfo = false;

		$this->contextBoxes = array();

		/* Recuperation de l'ID */
		$this->rubrique_id = $this->site->getCurId();

		/* Et de son contenu */
		$this->rubrique = GetRowFromId('s_rubrique', $this->rubrique_id);
		
		$this->fk_rubrique_version_id = $this->rubrique['fk_rubrique_version_id'];
		
		$_REQUEST['para'] = ake($_REQUEST,'para') ? $_REQUEST['para'] : '';

		/* Si elle a un gabarit (plutot conseille pour afficher quelque chose */
		if ($this->rubrique['fk_gabarit_id'] > 0) {
			$this->gabarit = GetRowFromId('s_gabarit', $this->rubrique['fk_gabarit_id'], 1);
		}

		if(strlen(trim($this->gabarit['gabarit_classe']))) {
			$this->hasBddInfo = true;
		} 
		
		else if(GABARIT_DEF) {
			$this->gabarit = GetRowFromId('s_gabarit', GABARIT_DEF, 1);
			$this->rubrique['fk_gabarit_id'] = $this->gabarit['gabarit_id'];
			$this->hasBddInfo = true;
		}
		





		/* Definition des Headers de la page relatifs a cette rubrique */

		$this->road = $this->site->g_url->buildRoad($this->rubrique_id);

		$title = $this->getFullTitle();
		

		$this->site->g_headers->setTitle($title);

		$this->site->g_headers->setMetaKeywords($this->site->GetLgValue('rubrique_keywords', $this->rubrique,false));

		$this->site->g_headers->setMetaDescription($this->site->GetLgValue('rubrique_desc', $this->rubrique,false));

		$this->date_publi = strtotime($this->rubrique['rubrique_date_publi']);





		/*
			****************
			Cache ou pas ?
		*/



		/* Les paragraphes */
		if($this->hasBddInfo) {

			$this->use_cache = false;
			$this->use_cache_contexte = false;
			$this->getParagraphes();

		//	$this->getSubRubs();

		} else {

			$this->cache_id = 'rub_' . $this->rubrique_id .'.'.$_REQUEST['para'].'_' . $this->fk_rubrique_version_id . '_main';
			$this->cache_id_contexte = 'rub_' . $this->rubrique_id . '_' . $this->fk_rubrique_version_id . '_contexte';

			$this->cache_contexte = new genCache($this->cache_id_contexte,$this->date_publi);
			$this->cache = new genCache($this->cache_id, $this->date_publi);
			$this->use_cache = true;
			$this->use_cache_contexte = true;

			if (!$this->cache->cacheExists() ) {
				$this->use_cache = false;
				$this->getParagraphes();
			}
			if (!$this->cache_contexte->cacheExists() ) {
				$this->use_cache_contexte = false;


			}
		}


	}



	/**
	 * Chargement des plugins
	 */

	function loadPlugins() {

		$p = GetPlugins();

		$t = getmicrotime();
		
		foreach($p as $v) {

			$GLOBALS['gb_obj']->includeFile('config.php',PLUGINS_FOLDER.''.$v.'/');


		}
		
		foreach($p as $v) {
			$GLOBALS['gb_obj']->includeFile('front.php',PLUGINS_FOLDER.''.$v.'/');

			$adminClassName = $v.'Front';
			if(class_exists($adminClassName)) {
				$this->plugins[$v] = new $adminClassName($this->site);
			}
			
		}
		$GLOBALS['times']['LoadingPlugins'] = getmicrotime()-$t;
		$GLOBALS['times']['Plugins'] += $GLOBALS['times']['LoadingPlugins'];
		reset($p);

	}

	/**
	* Verifie qu'un plugin est actif ou non
	*
	*
	* @return : true si le plugin est actif, false sinon
	*/
	public function isActivePlugin($plugin){
		return isset($this->plugins[$plugin]);
	}

	/**
			On est sur la vrai rubrique ou bien celle modifiable ?
	*/
	function isRealRubrique()
	{


		if ($this->site->g_url->action == "editer") {
			return false;
		}
		return true;
	}

	/**
		 Gestion des classes externes
	*/
	function afterInit()
	{
		global $co, $gb_obj;


		$this->loadPlugins();


		if ($this->hasBddInfo) {
			$startTimeBdd = getmicrotime();
			//debug($startTimeBdd);

			$className = $this->gabarit['gabarit_classe'];
			$deco = $this->gabarit['gabarit_bdd_deco'];

			ob_start();
			
			$dossier = ($this->gabarit['gabarit_plugin']) ? path_concat('plugins',$this->gabarit['gabarit_plugin'])   :'bdd';
			
			$gb_obj->includeFile($className . '.php', $dossier);


			if(class_exists($className)) {



				if ($deco) {
					/* On se connecte a une autre base de donnee

					//$curco = $co;


					$this->bddClasse = new $className($this->site, $this->rubrique['rubrique_gabarit_param'], $this);
					$this->bddClasse->deco = true;

					if(method_exists($this->bddClasse,'genBeforePara')) {
							$this->htmlInClassBeforePara .= $this->bddClasse->genBeforePara();
					}

					$this->htmlInClass = $this->bddClasse->gen();

					$this->bddClasse->Disconnect();

					*/

					debug('DECO ???');



				} else {

					/* On reste dans la meme base */


					$this->bddClasse = new $className($this->site, $this->rubrique['rubrique_gabarit_param']. ','.$this->gabarit['gabarit_classe_param'], $this);

					$this->bddClasse->deco = false;

				}

			} else {
				derror('La classe associee n\'existe pas : '.$className);
			}

			$htTemp = ob_get_contents();

			ob_end_clean();

			if (strlen(trim($htTemp))) {

				$this->htmlInClass = $htTemp;
			}

			$GLOBALS['times']['BDD'] += (getmicrotime() - $startTimeBdd);
			$GLOBALS['times']['Plugins'] += $GLOBALS['times']['BDD'];

		}

		/*
		if(is_object($this->g_boxAdmin))
		$this->site->g_headers->addFirstBody($this->g_boxAdmin->gen());
		

		$this->cache_road = new genCache('road_'.$this->rubrique_id,GetParam('date_update_arbo'));

		if(!$this->cache_road->cacheExists() || $this->hasBddInfo) {
			$this->road = $this->site->g_url->buildRoad($this->site->getCurId());

			$this->rubrique_niveau = count($this->road) - 1;
		}
		*/

		if($this->rubrique_niveau  == 5 )
			$this->IsMultiPage();




		$this->Execute('afterInit');





	}


	function Execute($what) {

		$p = GetPlugins();
		
		$html = '';
		
		$t = getmicrotime();
		
		foreach($p as $v) {
			if(ake($this->plugins,$v) && method_exists($this->plugins[$v],$what)) {
				$html .= $this->plugins[$v]->{$what}();
			}
		}

		if(method_exists($this->bddClasse,$what)) {
			$html .= $this->bddClasse->{$what}();
		}
		
		$GLOBALS['times']['Execute'.$what] = getmicrotime() - $t;
		$GLOBALS['times']['Plugins'] += $GLOBALS['times']['Execute'.$what];

		return $html;
	}


	/**
	 * GenTop
	 *
	 */
	function genTop() {

		return $this->Execute('genTop');


	}




	/**
	 * Execute la methode genOutside de la classe associee si presente
	 * et retourne le contenu
	 *
	 */
	function genOutside() {

		return $this->Execute('genOutside');


	}


	/**
	 * 
	 * Execute la methode genOutside de la classe associee si presente
	 * et retourne le contenu
	 *
	 */
	function gen1() {

		return $this->Execute('gen1');

	}







	function getFullTitle() {


		$i = 1;
		

		$revRoad = array_reverse($this->road);

		$html = '';
		
		$revRoad = array_slice($revRoad,0,-2);
		
		$nbr = count($this->road);
		/* On the road again ... */
		foreach($revRoad as $k => $v) {

			if(akev($v,'id')) {
				$row = getRowFromId('s_rubrique',$v['id']);
				$titre = getLgValue('rubrique_titre',$row);
			} else {
				$titre = $v['titre'];
			}


			if ($i < $nbr || $nbr == 1) {
				$html .= '' . $titre . '';
				$html .= ' - '; // Separateur
			} else {

			}

			$i++;
		}
		

		return substr($html,0,-2);


	}

	
	
	function genContextBoxes() {

			/* On parcourt Chaque boite */
			$gfa = new genFrontAdmin('s_rubrique',$this->site->getCurId());
			$gfa->authorized = !$this->site->g_rubrique->isRealRubrique();
			$html_in_cont = '';
			foreach($this->contextBoxes as $box) {


				$html_in_cont .= $gfa->startField($box->champ,array(),0);
				$html_in_cont .= $gfa->endField();

				$html_in_cont .= $box->gen();
			}

			return $html_in_cont;

	}
	


	/**
		Generation des paragraphes
	*/

	function genMain()
	{


		
		
		if(!$this->doGenMain) {
			return ;
		}
		
		global $co;

		$html = '';
		

		$html .= $this->Execute('genBeforePara');

		if ($this->showParagraphes) {

			if ($this->hasBddInfo || !$this->cache->cacheExists()) {


				if(method_exists($this->bddClasse,'genParagraphes')) {

					$html .= $this->bddClasse->genParagraphes();

				} else {

					$par = new genParagraphes($this->site,$this->paragraphes);
					$html .= $par->gen();

				}

				/* Liens de nav en bas */
				$html .= $this->html_after_paras;

			} else {
				/**
				 * Recuperation du cache
				 *
				 */
				$html = $this->cache->getCache();
			}
		}

		$html .= $this->Execute('gen');

		
		return $html;
	}
	/**
		Selectionne les paragraphes
	*/
	function getParagraphes()
	{


		$sql = 'SELECT * FROM s_paragraphe AS P LEFT JOIN s_para_type AS PT ON P.fk_para_type_id = PT.para_type_id
				WHERE P.fk_rubrique_id = ' . sql($this->rubrique_id,'int') . '

				ORDER BY paragraphe_ordre ASC
				';

		// debug($sql);
		$this->paragraphes = GetAll($sql);
	}


	/**
		Selectionne les sous rubriques

	*/
	function getSubRubs()
	{

		if($this->isRealRubrique()) {
			$tid = $this->rubrique_id;
		} else {
			$tid = $this->rubrique['fk_rubrique_version_id'];
		}

		if($this->site->g_url->minisite && $this->site->g_url->rootHomeId == $tid) {
			return array();
		}

		$sql = 'SELECT * FROM s_rubrique WHERE fk_rubrique_id = "' . mes($tid) . '" '.sqlRubriqueOnlyReal().'  '.sqlRubriqueOnlyOnline() .' order by rubrique_ordre';
		$res = GetAll($sql);

		$this->subRubs = $res;

		return $res;

	}



	/**
		Ajoute un objet BOX a la page
		@param box_obj = objet box
	*/
	function addBox($box_obj)
	{


		$this->contextBoxes[] = &$box_obj;
	}

}



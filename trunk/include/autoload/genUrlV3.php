<?php

#
# This file is part of oCMS.
#
# oCMS is free software: you cgan redistribute it and/or modify
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

class genUrlV3 {

    /**
     * Tableau qui stocke les url des rubriques
     *
     * @var array
     */
    public $tabUrl;

    /**
     * Langue en cours
     * @var string
     */
    private $lg;

    /**
     * Liste des sous rubriques virtuelles supplémentaires
     * @var array
     */
    public $roadSup;

    /**
     * Si les paramètres passés doivent être différents dans une autre langue
     */
    private $otherLgParamsUrl;

    /**
     * Liste des paramètres passés dans l'URL
     */
    public $paramsUrl = array();

    /**
     * Rubrique de niveau 0
     */
    public $topRubId;

    /**
     * Est-ce un minisite ?
     * @var bool
     */
    public $minisite;
    public $paramsAsIndex = array();

    /**
     *
     * @var array Rubriques parentes sélectionnées
     */
    private $selectedArbo = array();

    /**
     * Recursion deja effectuées
     */
    private $recursDones = array();

    /**
     * Identifiant de la rubrique en cours
     */
    public $rubId = 0;

    /**
     * Liste des dossiers parsés avec rubriques et paramètres
     * @var type
     */
    public $parsedUrl = false;
    public $rootHomeId = 0;
	
	public $downArbo = true;

    /**
     *  Constructeur de la classe genUrl
     */
    function __construct($lg = '', $downArbo = true) {

        $this->lg = $lg;

        $this->minisite = false;
        $GLOBALS['tabUrl'] = array();
        $GLOBALS['urlCached'] = array();
        $this->parseUrl();

        $this->rootRow = $this->getSiteRoot();
        $this->rootHomeId = $this->rootRow['rubrique_id'];
        $this->rubId = $this->getRubId();

        $this->roadSup = array();
        $this->colorLevel = 'sd';
		
		$this->downArbo = $downArbo;
		
    }

    function getTopRubId() {
        return $this->topRubId;
    }

    /**
     * Langue courante
     *
     * @return string Langue actuelle
     */
    function getLg() {
        return $this->lg;
    }

    /**
     * Methode pour definir quelle rubrique contient l'ensemble du site
     * C'est la rubrique qui est définit comme
     * rubrique_type = 'siteroot'
     * et dont l'url correspond au $_SERVER['HTTP_HOST'] puis en concatenant  dirname($_SERVER["SCRIPT_NAME"]);
     *
     */
    function getSiteRoot() {

        global $_Gconfig;
        $host = $_SERVER["HTTP_HOST"];
        $path = dirname($_SERVER["SCRIPT_NAME"]);

        $sql = 'SELECT * FROM s_rubrique
					WHERE rubrique_type
					IN ("' . RTYPE_SITEROOT . '","' . RTYPE_MENUROOT . '")
					' . sqlRubriqueOnlyReal() . ' ';
        $cRes = GetAll($sql);
        /**
         * Sélection de tous les "siteroot"
         * Et mise en cache
         *
         * @todo Intérêt ?
         */
        foreach ($cRes as $res) {

            $rub = $GLOBALS['tabUrl'][$res['rubrique_id']] = array(
                'fkRub' => $res['fk_rubrique_id'],
                'gabarit' => $res['fk_gabarit_id'],
                /* 'isFolder'=>$res['rubrique_is_folder'], */
                'param' => $res['rubrique_gabarit_param'],
                'option' => $res['rubrique_option'],
                'template' => $res['rubrique_template'],
                'type' => $res['rubrique_type'],
                'webroot' => ($res['rubrique_type'] == RTYPE_SITEROOT ? $this->getDefWebRoot($res['rubrique_url_' . LG_DEF]) : '' )
            );
            reset($_Gconfig['LANGUAGES']);
            foreach ($_Gconfig['LANGUAGES'] as $lg) {
                $GLOBALS['tabUrl'][$res['rubrique_id']]['link_' . $lg] = $res['rubrique_link_' . $lg];
                $GLOBALS['tabUrl'][$res['rubrique_id']]['titre_' . $lg] = $res['rubrique_titre_' . $lg];
                $GLOBALS['tabUrl'][$res['rubrique_id']]['url' . $lg] = $res['rubrique_url_' . $lg];
            }
        }


        /**
         * Sélection du siteroot à partir du nom de domaine/sous domaine
         */
        $sql = 'SELECT * FROM s_rubrique
				 WHERE rubrique_type
				 LIKE "' . RTYPE_SITEROOT . '"
				 ' . sqlRubriqueOnlyOnline() . '
				 ' . lgFieldsLike("rubrique_url", '%;' . mes($host) . ';%', ' OR ') . '
				  ';
        $row = GetSingle($sql);

        if (count($row)) {
            $this->homeId = $this->rootHomeId = $this->root_id = $row['rubrique_id'];
            $this->curWebRoot = $this->getDefWebRoot($row['rubrique_url_' . LG_DEF]);
            $this->TEMPLATE = $row['rubrique_template'];
            return $row;
        } else {

            $this->homeId = $this->rootHomeId = $this->root_id = $cRes[0]['rubrique_id'];
            $rId = $this->getRubId();
            $this->reversRecursRub($rId);

            while ($rId) {

                $R = $GLOBALS['tabUrl'][$rId];

                if ($R['template']) {

                    $this->homeId = $this->rootHomeId = $this->root_id = $rId;
                    $this->curWebRoot = $this->getDefWebRoot($R['url' . LG_DEF]);
                    $this->TEMPLATE = $R['template'];

                    return getRowFromId('s_rubrique', $rId);
                }
                $rId = $R['fkRub'];
            }

            $sql = 'SELECT * FROM s_rubrique WHERE rubrique_type LIKE "' . RTYPE_SITEROOT . '"
						' . sqlRubriqueOnlyOnline() . ' LIMIT 0,1';
            $row = GetSingle($sql);


            if (count($row)) {
                $this->homeId = $this->rootHomeId = $this->root_id = $row['rubrique_id'];
                $this->curWebRoot = $this->getDefWebRoot($row['rubrique_url_' . LG_DEF]);
                $this->TEMPLATE = $row['rubrique_template'];

                return $row;
            } else if (!isLoggedAsAdmin()) {
                diebug('NO_SITE_ROOT');
            }
        }
    }

    function getDefWebRoot($str) {

        $et = explode(';', $str);
        foreach ($et as $v) {
            if (strtolower($v) == strtolower($_SERVER['HTTP_HOST'])) {
                return $v;
            }
        }
        return $_SERVER['HTTP_HOST'];
        if (strlen($et[0])) {
            return $et[0];
        } else {
            return $et[1];
        }
    }

    /**
     * Retourne le cache des URLs
     *
     * @return unknown
     */
    function getTabUrl() {
        return $GLOBALS['tabUrl'];
    }

    /**
     * Retourne les chemins supplémentaires ajoutés au chemin de fer
     *
     * @return unknown
     */
    function getRoadSup() {
        return $this->roadSup;
    }

    /**
     * Parse les élements de l'URL et récupère chaque partie sous forme de tableau
     *
     * @return array
     */
    function parseUrl() {

        if ($this->parsedUrl) {
            return $this->parsedUrl;
        }


        global $_Gconfig;
        $x_url = explode('?', $_SERVER['REQUEST_URI']);


        $x_url = str_replace('/index.html', '/', $x_url);
        $x_url = $x_url[0];
        $x_url = explode('/_action/', $x_url);
        $this->action = ake($x_url, 1) ? $x_url[1] : '';

        $this->splitAction();
		
        $dossiers = str_replace(BU, '', $x_url[0]);
        $dossiers = trim($dossiers, '/');
        $dossiers = explode('/', $dossiers);

        global $_Gconfig;
        if ($_Gconfig['onlyOneLgForever']) {

            if (!defined('LG')) {
                define("LG", $_Gconfig['LANGUAGES'][0]);
                define('TRADLG', false);
            }
            $this->lg = LG;
            mylocale(LG);
        } else {
            $templg = array_shift($dossiers);

            /**
             * Si on est dans une seconde langue ( /fr-de/ )
             */
            if (strpos($templg, '-')) {
                $templg = explode('-', $templg);
                $this->lg = $templg[0];
                if (!in_array($this->lg, $_Gconfig['LANGUAGES'])) {
                    $this->lg = $this->getBrowserLang();
                }
                $this->tradlg = $templg[1];
                define('TRADLG', $this->tradlg);
            } else if ($templg) {
                /**
                 * Si on a a priori la langue en paramètres
                 */
                $this->lg = $templg;
                if (!in_array($this->lg, $_Gconfig['LANGUAGES'])) {
                    $this->lg = $this->getBrowserLang();
                }
                if (!defined('LG')) {
                    define("LG", $this->lg);
                    define('TRADLG', false);
                }
                mylocale($this->lg);
            } else {
                $this->lg = empty($this->lg) ? $this->getBrowserLang() : $this->lg;
                if (!defined('LG')) {
                    define("LG", $this->lg);
                    define('TRADLG', false);
                }
                mylocale($this->lg);
            }
        }


        $this->parsedUrl = $this->trimTab(array( 0 => implode($dossiers, '/')));

        return $this->parsedUrl;
    }

    /**
     * Sépare les actions du reste de l'URL
     *
     */
    function splitAction() {

        $this->action = explode('/', $this->action);
        $this->action = end($this->action);
    }

    /**
     * Sépare les paramètres /bdd/ du reste de l'URL
     *
     * @param unknown_type $params
     */
    function splitParams($params) {
        $p = array();
        $this->paramsUrl = array();
        foreach ($params as $k => $v) {
            $vv = explode(getParam('param_val_sep'), $v);
            //if(!empty($vv[1])) {
            if (empty($vv[1]))
                $vv[1] = '';
            $this->paramsUrl[$vv[0]] = $_REQUEST[$vv[0]] = $_GET[$vv[0]] = urldecode($vv[1]);
            //}
            /*
              if($k%2 == 0) {
              $currentK = $v;
              $_REQUEST['_param_'.$k] = $_GET['_param_'.$k] = urldecode($v);
              }
              else {
              $this->paramsUrl[$currentK] = $_REQUEST[$currentK] = $_GET[$currentK] = urldecode($v);
              }
             */
        }
    }

    /**
     * Retourne l'URL courante dans la langue $lg
     *
     * @param unknown_type $lg
     * @return unknown
     */
    function getUrlInLg($lg) {
        return $this->buildUrlFromId(0, $lg, $this->paramsUrl);
    }

    /**
     * Methode qui va parser l'URL et retourne l'identifiant de la rubrique
     * selectionnée
     *
     * @return unknown
     */
    function getRubId() {

        global $homeId, $_Gconfig;

        if (IN_ADMIN) {
            $this->lg = LG_DEF;
            return false;
        }

        if ($this->rubId) {
            return $this->rubId;
        }

        if (!$this->rubId) {

            if (count($this->parsedUrl) == 0) {
                /**
                 * Racine du site ou minisite
                 */
                if ($this->action == 'editer') {
                    $sql = 'SELECT * FROM s_rubrique WHERE fk_rubrique_version_id = ' . $this->rootHomeId;
                    $row = GetSingle($sql);
                    $this->rubId = $row['rubrique_id'];
                } else {
                    $this->rubId = $this->rootHomeId;
                }
                return $this->rubId;
				
            } else {

                /**
                 * Sinon on sélectionne les rubriques correspondantes
                 */
                global $_Gconfig;
				$rubriqueId = false;
				
				//on explode l'url
				$temp_url = explode('/', $this->parsedUrl[1]);
				$count_temp_url = count($temp_url);
				
				for($i = 0; $i < $count_temp_url; $i++){
					
					/**
					 * On recherche si on a une rubrique correspondant à l'url
					 */
					/**
                     * Sélection des rubriques
                     */
                    $select = 'SELECT
                                    rubrique_id, 
                                    fk_rubrique_id,
                                    fk_rubrique_version_id,
                                    
                                    ';
                    reset($_Gconfig['LANGUAGES']);
					
					/**
                     * Avec URL dans toutes les langues
                     */
                    foreach ($_Gconfig['LANGUAGES'] as $lg) {
                        $select .= 'rubrique_url_' . $lg . ' AS rubrique_url_' . $lg . ' ,  ';
                        $select .= 'rubrique_titre_' . $lg . ' AS rubrique_titre_' . $lg . ' ,  ';
                        $select .= 'rubrique_link_' . $lg . ' AS rubrique_link_' . $lg . ' ,  ';
                    }

					$select .= ' R1.rubrique_etat from s_rubrique as R1 ';
					
					/**
                     * Recherche de toute l'url
                     */
                    $where = ' WHERE R1.rubrique_url_' . $this->lg . '= ' . sql(implode($temp_url, '/')) . ' ';
					
					if ($this->action == 'editer') {
                        $where .= sqlRubriqueOnlyVersions('R1');
                    } else {
                        $where .= sqlRubriqueOnlyReal('R1');
                    }
					
					$rubs = GetAll($select . $where);
					
					
					/**
                     * Aucun résultat, le dernier dossier est un paramètre
                     */
                    if (count($rubs) == 0) {
                    	
						/**
						 * On le stocke
						 */
                        $this->paramsAsIndex[] = $temp_url[(count($temp_url)-1)];
						unset($temp_url[(count($temp_url)-1)]);
						
                    }else{
                    
						/**
						 * On a trouvé une ou plusieurs rubriques on casse la boucle
						 */
						break;
						
                    }
                       
				}

				/**
				 * On split les paramètre
				 */
				$this->splitParams($this->paramsAsIndex);
				
				$r = false;
				/**
				 * Si on a toujours au moins une rubrique c'est qu'il y a des chances pour que la rubrique que l'on veut soit dans les résultat
				 */				 
				if(count($rubs)){
					
					/**
					 * Pour chaque rubrique on regarde si c'est le bon root qui est chargé.
					 */
					foreach($rubs as $rub){
						
						/**
						 * Si le site root est bien le bon on prends la rubrique et on casse la boucle;
						 */
						 $root_id = $this->getFirstRub($rub['fk_rubrique_id']);
						 
						if($root_id == $this->rootHomeId){
						
							$r = $rub;
							break;
							
						}
						
					}
					
				}
				
				$r = array_shift($rubs);
				
				/**
				 * Si on a toujours une rubrique
				 */
				if($r){
					
					/**
					 * On stocke l'url
					 */
					$rubriqueId = choose($r['fk_rubrique_version_id'], $r['rubrique_id']);
					
					
					/**
					 * On stock la rubrique et les rubrique parents avec une fonction recursive
					 */
					$this->stockRub($r['rubrique_id']);
										
					if ($this->action == 'editer') {
                    	$r = getSingle('SELECT * FROM s_rubrique WHERE fk_rubrique_version_id = ' . sql($rubriqueId));
                   		$rubriqueId = $r['rubrique_id'];
                	}
					
				}

				$this->rubId = $rubriqueId;
				
                /**
                 * On a pas trouvé la rubrique
                 * c'est donc une erreur 404
                 */
                if (!$this->rubId) {

                    header('HTTP/1.1 404 Not Found');

                    $GLOBALS['_gensite']->isCurrent404 = true;

                    if (stristr($_SERVER['REQUEST_URI'], 'css') || stristr($_SERVER['REQUEST_URI'], 'js' || stristr($_SERVER['REQUEST_URI'], 'jpeg') || stristr($_SERVER['REQUEST_URI'], 'jpg') || stristr($_SERVER['REQUEST_URI'], 'gif' || stristr($_SERVER['REQUEST_URI'], 'png')))) {
                        $this->die404();
                    }

                    $this->rubId = getRubFromGabarit('genSitemap');

                    if (!$this->rubId) {
                        // $this->die404();
                    }

                    return $this->rubId;
                }
                return $this->rubId;
				
			}
        }


        /**
         * Si vraiment on a pas trouvé de page => 404
         * @deprecated Normalement on a deja retourne un 404 plus haut
         */
        if ($this->rubId == '') {
            header('HTTP/1.1 404 Not Found');
            $GLOBALS['_gensite']->isCurrent404 = true;
            $this->rubId = getRubFromGabarit('genSitemap');
            if (!$this->rubId) {
                $this->die404();
            }
            return $this->rubId;
        }

        return $this->rubId;
    }

	function getFirstRub($id){
		$sql = "SELECT * FROM s_rubrique WHERE rubrique_id = ".$id;
		$r = GetSingle($sql);
		if($r['rubrique_type'] == 'siteroot'){
			
		}else{
			$this->getFirstRub($r['fk_rubrique_id']);
		}
	}

	function stockRub($id){
		
		global $_Gconfig;
		
		$select = 'SELECT
			        rubrique_id, 
			        fk_rubrique_id,
			        rubrique_type,
			        fk_gabarit_id,
			        rubrique_gabarit_param,
			        rubrique_option,
			        rubrique_template,
			        fk_rubrique_version_id,
               ';
		reset($_Gconfig['LANGUAGES']);

        /**
         * Avec URL dans toutes les langues
         */
        foreach ($_Gconfig['LANGUAGES'] as $lg) {
            $select .= 'rubrique_url_' . $lg . ' AS rubrique_url_' . $lg . ' ,  ';
            $select .= 'rubrique_titre_' . $lg . ' AS rubrique_titre_' . $lg . ' ,  ';
            $select .= 'rubrique_link_' . $lg . ' AS rubrique_link_' . $lg . ' ,  ';
        }
		
		$select .= ' R1.rubrique_etat from s_rubrique as R1 ';

        /**
         * Recherche du dossier en cours
         */
        $where = ' WHERE R1.rubrique_id = ' . sql($id) . ' ';
		
		$r = GetSingle($select . $where);
		
		$GLOBALS['tabUrl'][$r['rubrique_id']] = array(
            'fkRub' => $r['fk_rubrique_id'],
            'gabarit' => $r['fk_gabarit_id'],
            'param' => $r['rubrique_gabarit_param'],
            'option' => $r['rubrique_option'],
            'template' => $r['rubrique_template'],
            'type' => $r['rubrique_type'],
            'selected' => true
        );

        if ($r['rubrique_type'] == RTYPE_SITEROOT) {
            $rub['webroot'] = $GLOBALS['tabUrl'][$r['rubrique_id']]['webroot'] = $this->getDefWebRoot($r['rubrique_url_' . LG_DEF]);
        }

        reset($_Gconfig['LANGUAGES']);
        foreach ($_Gconfig['LANGUAGES'] as $lg) {
            $GLOBALS['tabUrl'][$r['rubrique_id']]['link_' . $lg] = $r['rubrique_link_' . $lg];
            $GLOBALS['tabUrl'][$r['rubrique_id']]['titre_' . $lg] = $r['rubrique_titre_' . $lg];
            $GLOBALS['tabUrl'][$r['rubrique_id']]['url' . $lg] = $r['rubrique_url_' . $lg];
        }
		
		$this->selectedArbo[] = $r['rubrique_id'];
		
		if(isset($r['fk_rubrique_id'])){
			//si on a une rubrique parent on fait la même chose
			$this->stockRub($r['fk_rubrique_id']);
		}else{
			//sinon on est sur la home
			$this->topRubId = $this->rootHomeId = $r['rubrique_id'];
		}
		
	}

    function die404() {
        echo '<h1>Error 404</h1><p>The page can not be found</p><p><a href="/">Go back</a></p>';
        die();
    }

    /**
     * Retourne la seconde langue acceptable
     * @deprecated
     *
     * @return unknown
     */
    function otherLg() {
        return getOtherLg();
        global $_Gconfig;

        if (LG != LG_DEF)
            return LG_DEF;
        else
            return $_Gconfig['LANGUAGES'][1];

        return ($this->lg == 'fr' ? 'en' : 'fr');
    }

    /**
     * alias de
     * @uses myLocale
     *
     * @param unknown_type $lg
     */
    function setLocale($lg) {
        myLocale($lg);
    }

    /**
     * Vide les cases vide d'un tableau
     *
     * @param unknown_type $tab
     * @return unknown
     */
    function trimTab($tab) {
        $newTab = array();
        global $_Gconfig;

        $cpt = 2;
        foreach ($tab as $value) {
            if (!empty($value)) {
                if ($cpt > 1) {
                    $newTab[$cpt - 1] = ($value); //nicename ???? @todo 
                }
                $cpt++;
            }
        }

        return $newTab;
    }

    /**
     * Retourne l'URL courante dans l'autre langue
     * @deprecated
     *
     * @return unknown
     */
    function getUrlForOtherLg() {
        //debug($GLOBALS['tabUrl']);
        $p = is_array($this->otherLgParamsUrl) ? $this->otherLgParamsUrl : $this->paramsUrl;

        //debug($this->otherLgParamsUrl);
        return $this->buildUrlFromId(0, $this->otherLg(), $p);
    }

    /**
     * Construit l'URL complète vers une rubrique dans une langue donnée avec les paramètrse et les actions voulus
     *
     * @param int $rubId
     * @param str $lg
     * @param array $params
     * @param array $action
     * @return string
     */
    function buildUrlFromId($rubId = 0, $lg = '', $params = array(), $action = '') {

        global $_Gconfig;

        if ($rubId == 0) {
            $rubId = $this->getRubId();
        }

        if (!$rubId) {
            return;
        }
        $cachename = md5($rubId . $lg . var_export($params, true));

        if (ake($GLOBALS['urlCached'], $cachename) && !$action) {

            return $GLOBALS['urlCached'][$cachename];
		 
        }

        if (!array_key_exists($rubId, $GLOBALS['tabUrl'])) {
            $this->reversRecursRub($rubId, $action == 'editer' ? false : true);
        }


        if (isset($GLOBALS['tabUrl'][$rubId]) && $GLOBALS['tabUrl'][$rubId]['type'] == 'link') {
            $url = GetLgValue('link', $GLOBALS['tabUrl'][$rubId], false);

            $GLOBALS['urlCached'][$cachename] = $url;
            return $url;
        } else {
            $url = $this->buildUrl($rubId, $lg);
            if (is_array($url) && count($url) > 1) {
                $params = array_merge($url[1], $params);
                $url = $url[0];
            }
            $url = path_concat(BU, $url);
            $url .= $this->addParams($params);

            if (strlen($action)) {
                $url = path_concat($url, '_action', $action);
            }
        }

        /**
         * Si on est dans un mini site en sous domaine
         */
        if ($this->curLinkRoot && false) {
            //$url = path_concat('http://',$this->curLinkRoot['url'.LG].$_Gconfig['minisite_sous_domaine'],$url);

            $url = path_concat('http://', $GLOBALS['tabUrl'][$rubId]['webroot'], $url);
        } else {

            $rub = $rubId;

            while (isset($GLOBALS['tabUrl'][$rub]) && !akev($GLOBALS['tabUrl'][$rub], 'webroot') && $rub > 0 && $rub != 'NULL') {

                $rub = $GLOBALS['tabUrl'][$rub]['fkRub'];
            }


            if (isset($GLOBALS['tabUrl'][$rub]) && akev($GLOBALS['tabUrl'][$rub], 'webroot') && isset($GLOBALS['tabUrl'][$this->getRubId()]) && $GLOBALS['tabUrl'][$rub]['webroot'] != akev($GLOBALS['tabUrl'][$this->getRubId()], 'webroot')) {
                $url = path_concat($_Gconfig['protocole'] . '://', $GLOBALS['tabUrl'][$rub]['webroot'], $url);
            }
        }

        /**
         * Utile si on a plusieurs /bdd/.../bdd/

          $bddPart = explode(''.GetParam('fake_folder_param').'',$url);
          if(count($bddPart) > 2) {
          $url = $bddPart[0].''.GetParam('fake_folder_param').''.$bddPart[1];
          if($bddPart[2]) {
          $url.= ''.$bddPart[2];
          }
          }
         */
        $GLOBALS['urlCached'][$cachename] = $url;

        return $url;
    }

    /**
     * Retourne l'URL de la page courante avec des paramètres différents
     *
     * @param array $params
     * @return string
     */
    function getUrlWithParams($params) {
        return $this->buildUrlFromId(0, '', $params);
    }

    function getUrlWithMoreParams($params) {
        return $this->buildUrlFromId(0, '', array_merge($this->paramsUrl, $params));
    }

    /**
     * Retourne l'URL courante telle quelle
     *
     * @return string
     */
    function getCurUrl() {
        return $this->buildUrlFromId(0, '', $this->paramsUrl);
    }

    /**
     * Ajoute des paramètres à l'URL courant
     *
     * @param unknown_type $params
     * @return unknown
     */
    function addParams($params) {
        if (is_array($params) && count($params) > 0) {
            // $url = '' . GetParam('fake_folder_param') . '';
            $url = '';
            foreach ($params as $k => $v) {
                if (is_array($v)) {
                    $k = $k . '__list';
                    //$v = implode('_-_',$v);
                    $v = serialize($v);
                }
                if ($k && $v) {
                    $url = path_concat($url, $k . getParam('param_val_sep') . $v);
                } else if ($k) {
                    $url = path_concat($url, $k);
                } else if ($v) {
                    $url = path_concat($url, $v);
                }
            }
            return $url;
        }
        return '';
    }

    /**
     * Redefinit certains paramètre pour l'autre langue
     * @deprecated
     *
     * @param unknown_type $params
     */
    function setOtherLgParams($params) {

        $this->otherLgParamsUrl = $params;
    }

    /**
     * Tant qu'on a une rubrique au dessus, on remonte
     *
     * @param unknown_type $rubId
     * @return unknown
     */
    function reversRecursRub($rubId, $onlyOnline = true) {
        global $_Gconfig;

        if (!$rubId)
            return;

        if (!is_array(akev($GLOBALS['tabUrl'], $rubId))) {
            $sql = 'select R1.* ,
				   R1.rubrique_id as rubId,
				   R2.rubrique_id as p_rubId,

				   R2.fk_rubrique_id as p_fkRubId
				   from s_rubrique as R1, s_rubrique as R2
				   where R1.fk_rubrique_id = R2.rubrique_id				   
				   and R1.rubrique_id = ' . sql($rubId);
            if ($onlyOnline) {
                $sql .= '' . sqlRubriqueOnlyOnline('R1') . '';
            }
            $res = GetSingle($sql);



            if (!empty($res) && !is_array(akev($GLOBALS['tabUrl'], akev($res, 'rubId')))) {

                if (isset($res['rubId'])) {
                    $rub = $GLOBALS['tabUrl'][$res['rubId']] = array(
                        'fkRub' => $res['p_rubId'],
                        'gabarit' => $res['fk_gabarit_id'],
                        'param' => $res['rubrique_gabarit_param'],
                        /* 'isFolder'=>$res['rubrique_is_folder'], */
                        'option' => $res['rubrique_option'],
                        'template' => $res['rubrique_template'],
                        'type' => $res['rubrique_type'],
                        'p_fkRubId' => $res['p_fkRubId'],
                        'selected' => in_array($res['rubId'], $this->selectedArbo)
                    );

                    if ($res['rubrique_type'] == RTYPE_SITEROOT) {
                        $rub['webroot'] = $GLOBALS['tabUrl'][$res['rubId']]['webroot'] = $this->getDefWebRoot($res['rubrique_url_' . LG_DEF]);
                    }

                    reset($_Gconfig['LANGUAGES']);
                    foreach ($_Gconfig['LANGUAGES'] as $lg) {
                        $GLOBALS['tabUrl'][$res['rubId']]['link_' . $lg] = $res['rubrique_link_' . $lg];
                        $GLOBALS['tabUrl'][$res['rubId']]['titre_' . $lg] = $res['rubrique_titre_' . $lg];
                        $GLOBALS['tabUrl'][$res['rubId']]['url' . $lg] = $res['rubrique_url_' . $lg];
                    }
                }
            } else if (!empty($res)) {
                $rub = $GLOBALS['tabUrl'][$res['rubId']];
            }
        } else {
            $rub = $GLOBALS['tabUrl'][$rubId];
        }

        if (isset($rub) && akev($rub, 'p_fkRubId') != NULL) {
            return $this->reversRecursRub($rub['fkRub']);
        }

        return $GLOBALS['tabUrl'];
    }

    /**
     * Construction de l'URL d'une page
     *
     * @param int $rubId Identifiant de la page
     * @param str $lg Langue
     * @return string URL
     */
    function buildUrl($rubId, $lg) {
        global $_Gconfig;

        $lg = strlen($lg) ? $lg : $this->lg;
        $reallg = $lg;

        /**
         * Si la langue demandée n'est pas dans la liste des langues par défaut
         * c'est que c'est une traduction ponctuelle
         * donc : /fr-it/
         */
        if (!in_array($lg, $_Gconfig['LANGUAGES'])) {
            $lg = LG . '-' . $lg;
            $reallg = LG;
        }

        $url = '';
        $key = $rubId;

        if ($_Gconfig['onlyOneLgForever']) {
            $lg = '';
        }

        /**
         * Si on ne demande pas la page racine
         */
        $this->curLinkRoot = array();
        if ($rubId != $this->root_id) {
			 if ($GLOBALS['tabUrl'][$key]['type'] != 'menuroot') {
                /**
                 * Distinction pour les "mini sites" en "siteroot" au milieu du site avec des regles d'URL à part
                 */
                if ($GLOBALS['tabUrl'][$key]['type'] != 'siteroot') {
                    $url = path_concat($GLOBALS['tabUrl'][$key]['url' . $reallg],'/');
                } else {
                    $this->curLinkRoot = $GLOBALS['tabUrl'][$key];
                    break;
                }
            }
        }
		
        /**
         * Si jamais on demande aux pages de pointer vers la premiere sous page
         */
        if (isset($GLOBALS['tabUrl'][$rubId]) && $GLOBALS['tabUrl'][$rubId]['type'] == 'folder' && $rubId != $this->root_id && $GLOBALS['tabUrl'][$rubId]['type'] != RTYPE_SITEROOT && $this->downArbo) {

            $subId = $rubId;
            //$subs = $this->recursRub($subId,1,1);
            if (rubHasOption($GLOBALS['tabUrl'][$rubId]['option'], 'dynSubRubs')) {
                $subs = getGabaritSubRubs(getRowFromId('s_rubrique', $rubId), $GLOBALS['tabUrl'][$rubId]['gabarit']);
                if ($subs) {
                    return array(path_concat('/' . $lg, $url), array($subs[0]['PARAM'] => $subs[0]['VALUE']));
                } else {
                    return array(path_concat('/' . $lg, $url));
                }
            } else {
                $subs = $this->recursRub($subId, 1, 1);
            }

            /**
             * On parcourt $SUBS et pour chaque sous rubrique ayant au moins une sous rubrique on recommence
             */
            while (count($subs)) {
                $subtab = array_shift($subs);

                $subId = $subtab['id'];
                if ($subtab['type'] != 'menuroot') {
                    $url = path_concat($url, $subtab['url' . $reallg]);
                }
                if ($subtab['type'] == 'folder') {
                    $subs = $this->recursRub($subId, 1, 1);
                } else {
                    break;
                }
            }

            if ($subId != $rubId) {
                return $this->buildUrl($subId, $lg);
            }
        }

        return path_concat('/' . $lg, $url);
    }

    /**
     * Methode parcourant toute l'arborescence du site et affiche toutes les urls
     *
     * @param int $rubId Rubrique mère
     * @param int $curLevel Niveau actuel, laisser à 1 par défaut
     * @param int $maxLevel Combien de récursion atteindre ?
     * @return array Tableau
     */
    function recursRub($rubId, $curLevel = 1, $maxLevel = 99) {

        if (!$rubId)
            return false;



        global $_Gconfig;

        $tabTemp = array();
        if (ake($GLOBALS, 'recursDone') && ake($GLOBALS['recursDone'], $rubId . '-' . $maxLevel)) {
            return $GLOBALS['recursDone'][$rubId . '-' . $maxLevel];
        }



        /**
         * Sélection de toutes ses sous rubriques
         */
        $sql = 'SELECT
			   R2.*
			   from  s_rubrique as R2
			   where R2.fk_rubrique_id
			   ' . sqlParam($rubId) . '
			   ' . sqlRubriqueOnlyOnline('R2') . '
			   ' . sqlRubriqueOnlyReal('R2') . '
			   AND rubrique_type != "menu"
			   AND rubrique_type != "menuroot"
			   ORDER BY R2.rubrique_ordre ASC';

        $res = GetAll($sql);


        if (!count($res)) {

            $r = getRowFromId('s_rubrique', $rubId);

            if (rubHasOption($r['rubrique_option'], 'dynSubRubs')) {
                $subs = getGabaritSubRubs($r, $r['fk_gabarit_id']);
                if ($subs) {
                    foreach ($subs as $v) {
                        $k = getUrlFromId($rubId) . '_' . $v['VALUE'];
                        $u = getUrlFromId($rubId, LG, array($v['PARAM'] => $v['VALUE']));
                        $tabTemp[$k] = array(
                            'id' => $rubId,
                            'fkRub' => $rubId,
                            'url' => $u,
                            'urlfr' => $u,
                            'titre' => $v['NAME'],
                            'type' => 'fake'
                        );
                        if (akev($_REQUEST, $v['PARAM']) == $v['VALUE']) {
                            $tabTemp[$k]['selected'] = true;
                        }
                    }
                }

                return $tabTemp;
            }
        }

        /**
         * On parcourt toutes les sous rubriques
         */
        foreach ($res as $sRub) {


            if ($sRub['rubrique_etat'] != 'en_ligne')
                $sRub['rubrique_titre_fr'] .= ' ' . t('invisible_rub');

            /**
             * La rubrique en cours est elle selectionnee ?
             */
            $sel = in_array($sRub['rubrique_id'], $this->selectedArbo);



            /**
             * On stock le tabUrl du GenUrl
             * Un cache temporaire
             */
            $doIt = true;

            if (rubHasOption($sRub['rubrique_option'], 'dynVisibility')) {

                $res = getGabaritVisibility($sRub['fk_gabarit_id']);

                if (!$res) {
                    $doIt = false;
                }
            }

            if ($doIt) {
                if (!ake($GLOBALS['tabUrl'], $sRub['rubrique_id'])) {
                    $GLOBALS['tabUrl'][$sRub['rubrique_id']] = array(
                        'fkRub' => $rubId,
                        'gabarit' => $sRub['fk_gabarit_id'],
                        'param' => $sRub['rubrique_gabarit_param'],
                        'option' => $sRub['rubrique_option'],
                        'type' => $sRub['rubrique_type'],
                        /* 'isFolder' => $sRub['rubrique_is_folder'], */
                        'selected' => $sel
                    );

                    reset($_Gconfig['LANGUAGES']);

                    foreach ($_Gconfig['LANGUAGES'] as $lg) {
                        $GLOBALS['tabUrl'][$sRub['rubrique_id']]['link_' . $lg] = $sRub['rubrique_link_' . $lg];
                        $GLOBALS['tabUrl'][$sRub['rubrique_id']]['titre_' . $lg] = $sRub['rubrique_titre_' . $lg];
                        $GLOBALS['tabUrl'][$sRub['rubrique_id']]['url' . $lg] = $sRub['rubrique_url_' . $lg];
                    }
                }
                /**
                 * On récupere l'URL de cet élément
                 */
                $mu = $this->buildUrlFromId($sRub['rubrique_id']);

                /**
                 * Second tableau de stockage à retourner
                 */
                $tabTemp[$mu] = array('id' => $sRub['rubrique_id'],
                    'url' => $mu,
                    'titre' => GetLgValue('rubrique_titre', $sRub, false),
                    'type' => $sRub['rubrique_type'],
                    'selected' => $sel
                );
                reset($_Gconfig['LANGUAGES']);
                foreach ($_Gconfig['LANGUAGES'] as $lg) {
                    $tabTemp[$mu]['url' . $lg] = $sRub['rubrique_url_' . $lg];
                }



                /**
                 * Et on fait la récursion
                 */
                if ($curLevel < $maxLevel) {
                    if (rubHasOption($sRub['rubrique_option'], 'dynSubRubs')) {
                        $rid = $sRub['rubrique_id'];
                        $subs = getGabaritSubRubs($sRub, $sRub['fk_gabarit_id']);
                        if ($subs) {
                            foreach ($subs as $v) {
                                $u = getUrlFromId($rid, LG, array($v['PARAM'] => $v['VALUE']));
                                $tabTemp[$mu]['sub'][getUrlFromId($rid) . '_' . $v['VALUE']] = array(
                                    'id' => $rid,
                                    'fkRub' => $rid,
                                    'url' => $u,
                                    'urlfr' => $u,
                                    'titre' => $v['NAME'],
                                    'type' => 'fake'
                                );
                            }
                        }
                    } else {
                        $tabTemp[$mu]['sub'] = $this->recursRub($sRub['rubrique_id'], $curLevel + 1, $maxLevel);
                    }
                }
            }
        }

        $GLOBALS['recursDone'][$rubId] = $tabTemp;
        return $tabTemp;
    }

    /* Methode permettant de construire le "chemin de fer" de la page en-cours */

    function buildRoad($curId = 0, $includeMenuRoot = false) {
        //global $rootId;

        if (!$curId) {
            $curId = $GLOBALS['site']->getCurId();
        }

        if (!array_key_exists($curId, $GLOBALS['tabUrl']))
            $this->reversRecursRub($curId);

        $lg = $this->lg;
        $key = $curId;
        $road = array();



        if ($curId != $this->rootHomeId) { //getParam('rub_home_id')
            $i = 1;
            while (array_key_exists($key, $GLOBALS['tabUrl']) && $key && $i <= 100) {

                $i++;

                if ($includeMenuRoot || $GLOBALS['tabUrl'][$key]['type'] != RTYPE_MENUROOT) {
                    $road[] = array('id' => $key,
                        'titre' => getLgValue('titre', $GLOBALS['tabUrl'][$key]),
                        'url' => $this->buildUrlFromId($key));
                }
                $key = $GLOBALS['tabUrl'][$key]['fkRub'];
            }



            $road[] = array('titre' => t('cp_txt_home'),
                'url' => $this->buildUrlFromId($this->rootHomeId)); //getParam('rub_home_id')

            $road = array_reverse($road);

            foreach ($this->roadSup as $r) {
                $road[] = $r;
            }
        }

        return $road;
    }

    /**
     * La rubrique $row a t'elle une rubrique Après ?
     *
     * @param unknown_type $row
     * @return unknown
     */
    function hasNextRub($row) {

        $sql = 'SELECT rubrique_ordre FROM s_rubrique WHERE fk_rubrique_id ="' . $row['fk_rubrique_id'] . '"  ' . sqlRubriqueOnlyReal() . ' AND rubrique_ordre > "' . $row['rubrique_ordre'] . '" ORDER BY rubrique_ordre';

        $res = GetAll($sql);
        if (count($res)) {
            return true;
        }
        return false;
    }

    /**
     * La rubrique $row a t'elle une rubrique AVANT ?
     *
     * @param unknown_type $row
     * @return unknown
     */
    function hasPreviousRub($row) {
        $sql = 'SELECT rubrique_ordre FROM s_rubrique WHERE fk_rubrique_id ="' . $row['fk_rubrique_id'] . '"  ' . sqlRubriqueOnlyReal() . ' AND rubrique_ordre < "' . $row['rubrique_ordre'] . '" ORDER BY rubrique_ordre';

        $res = GetAll($sql);
        if (count($res)) {
            return true;
        }
    }

    /* Methode qui permet d'ajouter un element au tableau des rubriques hors bdd */

    function addRoad($titre, $url) {
        $this->roadSup[] = array('titre' => $titre, 'url' => $url);
    }

    /* Methode qui retourne le niveau de profondeur */

    function getDepth($rubid = 0) {
        $rubid = $rubid ? $rubid : $this->getRubId();

        if (!array_key_exists($curId, $GLOBALS['tabUrl']))
            $this->reversRecursRub($curId);
    }

    /* Methode qui permet de recuperer la langue du navigateur client */

    function getBrowserLang() {
        global $_Gconfig;
        $langs = explode(",", $_SERVER["HTTP_ACCEPT_LANGUAGE"]);

        foreach ($langs as $value) {
            $choice = substr($value, 0, 2);
            if (in_array($choice, $_Gconfig['LANGUAGES'])) {

                return $choice;
            }
        }
        return LG_DEF;
    }

}
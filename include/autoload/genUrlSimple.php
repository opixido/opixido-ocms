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
/**
 * Gestion des URLs du site
 * Rappatriement / renvoi
 *
 */

class genUrlSimple {

    /**
     * Tableau qui stocke les url des rubriques et les cl��rang�e de ces rubriques
     *
     * @var array
     */
    public $tabUrl;
    private $lg;   //La langue en cours de la page
    private $roadSup;  //Le tableau gerant les rubriques qui ne sont pas dans la bdd
    private $otherLgParamsUrl;
    public $paramsUrl;
    public $topRubId;
    public $minisite;
    private $selectedArbo = array();
    private $recursDones = array();

    function getTopRubId() {
        return $this->topRubId;
    }

    /**
     *  Constructeur de la classe genUrl 
     */
    function genUrlSimple($lg = '') {


        define('BU', str_replace('/index.php', '', $_SERVER["SCRIPT_NAME"]));

        $this->minisite = false;
        $GLOBALS['tabUrl'] = array();

        $this->rootRow = $this->getSiteRoot();
        $this->rootHomeId = $this->rootRow['rubrique_id'];

        //$this->isMiniSite();
        /*
          if($this->isNewUrl()){
          header ('HTTP/1.1 301 Moved Permanently');
          header('location:' .$this->isNewUrl());
          exit();
          }
         */

        $this->lg = $lg;


        $this->roadSup = array();
        $this->colorLevel = 'sd';
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




        /**
         * Selecting every SITEROOT AND MENUROOT
         */
        $sql = 'SELECT * FROM s_rubrique 
					WHERE rubrique_type 
					IN ("' . RTYPE_SITEROOT . '","' . RTYPE_MENUROOT . '") ' . sqlRubriqueOnlyReal() . ' ';
        $cRes = GetAll($sql);


        foreach ($cRes as $res) {

            $rub = $GLOBALS['tabUrl'][$res['rubrique_id']] = array(
                'fkRub' => $res['fk_rubrique_id'],
                'gabarit' => $res['fk_gabarit_id'],
                'param' => $res['rubrique_gabarit_param'],
                'dyntitle' => $res['rubrique_dyntitle'],
                'dynvisibility' => $res['rubrique_dynvisibility'],
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
         * SELECTING CURRENT SITEROOT
         */
        $sql = 'SELECT * FROM s_rubrique WHERE rubrique_type LIKE "' . RTYPE_SITEROOT . '" ' . sqlRubriqueOnlyOnline() . ' ' . lgFieldsLike("rubrique_url", '%;' . mes($host) . ';%', ' OR ') . ' ';
        $row = GetSingle($sql);

        if (count($row)) {
            $this->homeId = $this->rootHomeId = $this->root_id = $row['rubrique_id'];
            $this->curWebRoot = $this->getDefWebRoot($row['rubrique_url_' . LG_DEF]);
            $this->TEMPLATE = $row['rubrique_template'];
            return $row;
        } else {
            $sql = 'SELECT * FROM s_rubrique WHERE rubrique_type LIKE "' . RTYPE_SITEROOT . '" ' . sqlRubriqueOnlyOnline() . ' LIMIT 0,1';
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


        if (is_array($_GET['oBDD'])) {
            foreach ($_GET['oBDD'] as $k => $v) {
                $_GET[$k] = $_REQUEST[$k] = $v;
                $this->paramsUrl[$k] = $v;
            }
        }

        if ($_GET['oACT']) {
            $this->action = $_GET['oACT'];
        }

        return $x_url;
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
            return '';
        }
        if (!$this->rubId) {

            $this->parseUrl();
            if ($_REQUEST['oLG']) {
                $this->lg = $_REQUEST['oLG'];
            }



            if (!$this->lg || !in_array($this->lg, $_Gconfig['LANGUAGES'])) {
                $this->lg = $this->getBrowserLang();
            }

            define("LG", $this->lg);
            $GLOBALS['ocmsLG'] = LG;

            mylocale($this->lg);

            $this->getTabUrl();


            if ($_REQUEST['oID']) {
                $this->rubId = (int) $_REQUEST['oID'];
            } else {
                $this->rubId = $this->homeId;
            }


            $sql = 'SELECT * FROM s_rubrique AS R1 
						WHERE 
						( rubrique_id = ' . sql($this->rubId) . ' 
						OR 
						fk_rubrique_version_id = ' . sql($this->rubId) . ' 
						)
						';

            if ($this->action == 'editer') {

                $where .= sqlRubriqueOnlyVersions('R1');
            } else {

                $where .= sqlRubriqueOnlyReal('R1');
            }



            $sql = $sql . $where;
            //debug( $sql );
            $res = GetSingle($sql);




            /**
             * On a pas trouvé la rubrique 
             * c'est donc une erreur 404
             */
            if (count($res) == 0) {
                header('HTTP/1.1 404 Not Found');

                $GLOBALS['_gensite']->isCurrent404 = true;

                if (stristr($_SERVER['REQUEST_URI'], 'css') || stristr($_SERVER['REQUEST_URI'], 'js' || stristr($_SERVER['REQUEST_URI'], 'jpeg') || stristr($_SERVER['REQUEST_URI'], 'jpg') || stristr($_SERVER['REQUEST_URI'], 'gif' || stristr($_SERVER['REQUEST_URI'], 'png')))) {
                    $this->die404();
                }

                $this->rubId = getRubFromGabarit('genSitemap');

                if (!$this->rubId) {
                    $this->die404();
                }

                return $this->rubId;
            }

            $this->rubId = $res['rubrique_id'];





            while ($res['rubrique_type'] != RTYPE_SITEROOT && $i < 100) {

                $i++;
                $id = $res['rubrique_id'];

                $GLOBALS['tabUrl'][$id] = array(
                    'fkRub' => $res['fk_rubrique_id'],
                    'gabarit' => $res['fk_gabarit_id'],
                    'param' => $res['rubrique_gabarit_param'],
                    'dyntitle' => $res['rubrique_dyntitle'],
                    'dynvisibility' => $res['rubrique_dynvisibility'],
                    'type' => $res['rubrique_type']
                );

                $this->selectedArbo[] = $res['rubrique_id'];
                reset($_Gconfig['LANGUAGES']);
                foreach ($_Gconfig['LANGUAGES'] as $lg) {
                    $GLOBALS['tabUrl'][$id]['link_' . $lg] = $res['rubrique_link_' . $lg];
                    $GLOBALS['tabUrl'][$id]['titre_' . $lg] = $res['rubrique_titre_' . $lg];
                    $GLOBALS['tabUrl'][$id]['url' . $lg] = $res['rubrique_url_' . $lg];
                }

                $sql = 'SELECT * FROM s_rubrique WHERE rubrique_id = ' . sql($res['fk_rubrique_id']) . ' ' . sqlRubriqueOnlyReal();
                $res = GetSingle($sql);
            }
        }



        return $this->rubId;
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
        $cpt = 1;

        foreach ($tab as $value) {

            if (!empty($value)) {
                if ($cpt > 1) {
                    $newTab[$cpt - 1] = niceName($value);
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


        if ($GLOBALS['tabUrl'][$rubId]['type'] == 'link') {
            $url = GetLgValue('link', $GLOBALS['tabUrl'][$rubId], false);

            return $url;
        } else {
            $url = $this->buildUrl($rubId, $lg);

            $url = path_concat(BU, $url) . $this->addParams($params);

            if ($action) {
                $url .= $this->addAction($action);
            }
        }


        $rub = $rubId;

        while (!$GLOBALS['tabUrl'][$rub]['webroot'] && $rub > 0 && $rub != 'NULL') {

            $rub = $GLOBALS['tabUrl'][$rub]['fkRub'];
        }


        if ($GLOBALS['tabUrl'][$rub]['webroot'] != $GLOBALS['tabUrl'][$this->getRubId()]['webroot']) {
            $url = path_concat('http://', $GLOBALS['tabUrl'][$rub]['webroot'], $url);
        }




        $GLOBALS['urlCached'][$cachename] = $url;
        return $url;
    }

    function addAction($action) {

        return '&amp;oACT=' . $action;
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
            $url = '';
            foreach ($params as $k => $v) {
                if (is_array($v)) {
                    $k = $k . '__list';
                    $v = implode('_-_', $v);
                }
                $url .= '&oBDD[' . $k . ']=';
                $url .= $v;
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
    function reversRecursRub($rubId) {
        global $_Gconfig;

        if (!$rubId)
            return;

        if (!is_array($GLOBALS['tabUrl'][$rubId])) {
            $sql = 'select R1.* ,
				   R1.rubrique_id as rubId,
				   R2.rubrique_id as p_rubId,
				  
				   R2.fk_rubrique_id as p_fkRubId
				   from s_rubrique as R1, s_rubrique as R2
				   where R1.fk_rubrique_id = R2.rubrique_id
				   ' . sqlRubriqueOnlyOnline('R1') . '
				   and R1.rubrique_id = ' . $rubId;
            $res = GetSingle($sql);


            if (!is_array($GLOBALS['tabUrl'][$res['rubId']])) {
                $rub = $GLOBALS['tabUrl'][$res['rubId']] = array(
                    'fkRub' => $res['p_rubId'],
                    'gabarit' => $res['fk_gabarit_id'],
                    'param' => $res['rubrique_gabarit_param'],
                    'dyntitle' => $res['dyntitle'],
                    'dynvisibility' => $res['dynvisibility'],
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
            } else {
                $rub = $GLOBALS['tabUrl'][$res['rubId']];
            }
        } else {
            $rub = $GLOBALS['tabUrl'][$rubId];
        }

        if ($rub['p_fkRubId'] != NULL) {
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
            $lg = LG() . '-' . $lg;
            $reallg = LG();
        }

        $url = '';
        $key = $rubId;




        /**
         * Si jamais on demande aux pages de pointer vers la premiere sous page
         */
        if ($_Gconfig['rubLinkToSub'] && $rubId != $this->root_id && $GLOBALS['tabUrl'][$rubId]['type'] != RTYPE_SITEROOT) {
            $subId = $rubId;
            $subs = $this->recursRub($subId, 1, 1);

            /**
             * On parcourt $SUBS et pour chaque sous rubrique ayant au moins une sous rubrique on recommence
             */
            while (count($subs)) {
                $subtab = array_shift($subs);

                $subId = $subtab['id'];
                if ($subtab['type'] != RTYPE_MENUROOT) {
                    $url = path_concat($url, $subtab['url' . $reallg]);
                }
                $subs = $this->recursRub($subId, 1, 1);
            }
            if ($subId != $rubId) {
                return $this->buildUrl($subId, $lg);
            }
        }
        return 'index.php?oLG=' . $lg . '&amp;oID=' . $rubId;
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


        if ($curWebRoot == '') {
            $curWebRoot = $this->curWebRoot;
        }

        global $_Gconfig;


        if (ake($GLOBALS['recursDone'], $rubId . '-' . $maxLevel)) {
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



        /**
         * On parcourt toutes les sous rubriques
         */
        foreach ($res as $sRub) {


            if ($res['rubrique_type'] == RTYPE_SITEROOT && $res['rubrique_url_' . LG_DEF]) {
                $curWebRoot = ''; //$this->getDefWebRoot($res['rubrique_url_'.LG_DEF]);
            }

            /*
              if($sRub['rubrique_etat'] != 'en_ligne')
              $sRub['rubrique_titre_fr'] .=  ' '.t('invisible_rub');
             */
            /**
             * La rubrique en cours est elle selectionnee ?
             */
            $sel = in_array($sRub['rubrique_id'], $this->selectedArbo);

            /**
             * On stock le tabUrl du GenUrl
             * Un cache temporaire
             */
            $doIt = true;

            if ($sRub['rubrique_dynvisibility']) {

                $res = getGabaritVisibility($sRub['fk_gabarit_id']);

                if (!$res) {
                    $doIt = false;
                }
            }

            if ($doIt) {
                if (!is_array($GLOBALS['tabUrl'][$sRub['rubrique_id']])) {
                    $GLOBALS['tabUrl'][$sRub['rubrique_id']] = array(
                        'fkRub' => $rubId,
                        'gabarit' => $sRub['fk_gabarit_id'],
                        'param' => $sRub['rubrique_gabarit_param'],
                        'dyntitle' => $sRub['dyntitle'],
                        'dynvisibility' => $sRub['dynvisibility'],
                        'type' => $sRub['rubrique_type'],
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
                    $tabTemp[$mu]['sub'] = $this->recursRub($sRub['rubrique_id'], $curLevel + 1, $maxLevel);
                }
            }
        }

        $GLOBALS['recursDone'][$rubId] = $tabTemp;
        return $tabTemp;
    }

    /* Methode permettant de construire le "chemin de fer" de la page en-cours */

    function buildRoad($curId) {
        //global $rootId;

        if (!array_key_exists($curId, $GLOBALS['tabUrl']))
            $this->reversRecursRub($curId);

        $lg = strlen($lg) ? $lg : $this->lg;
        $key = $curId;
        $road = array();

        if ($curId != $this->rootHomeId) { //getParam('rub_home_id')
            while (array_key_exists($key, $GLOBALS['tabUrl']) && $key && $i <= 1000) {
                $i++;
                if ($GLOBALS['tabUrl'][$key]['type'] == RTYPE_MENUROOT) {
                    continue;
                }

                $road[] = array('id' => $key,
                    'titre' => getLgValue('titre', $GLOBALS['tabUrl'][$key]),
                    'url' => $this->buildUrlFromId($key));
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

?>

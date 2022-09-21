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
class genUrl
{

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
     * Liste des paramètres passés dans l'URL par ordre
     */
    public $paramsOrdered = array();

    /**
     * Rubrique de niveau 0
     */
    public $topRubId;

    /**
     * Est-ce un minisite ?
     * @var bool
     */
    public $minisite;

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
     *  Constructeur de la classe genUrl
     */
    function __construct($lg = '')
    {


        $this->lg = $lg;

        $this->minisite = false;
        $GLOBALS['tabUrl'] = array();
        $GLOBALS['urlCached'] = array();
        $this->parseUrl();


        $this->rootRow = $this->getSiteRoot();
        $this->rootHomeId = $this->rootRow['rubrique_id'];


        $this->roadSup = array();
        $this->colorLevel = 'sd';
    }

    function getTopRubId()
    {
        return $this->topRubId;
    }

    /**
     * Langue courante
     *
     * @return string Langue actuelle
     */
    function getLg()
    {
        return $this->lg;
    }

    /**
     * Methode pour definir quelle rubrique contient l'ensemble du site
     * C'est la rubrique qui est définit comme
     * rubrique_type = 'siteroot'
     * et dont l'url correspond au $_SERVER['HTTP_HOST'] puis en concatenant  dirname($_SERVER["SCRIPT_NAME"]);
     *
     */
    function getSiteRoot()
    {

        global $_Gconfig;
        $host = $_SERVER["HTTP_HOST"];
        $path = dirname($_SERVER["SCRIPT_NAME"]);

        $sql = 'SELECT * FROM s_rubrique 
					WHERE rubrique_type 
					IN ("' . RTYPE_SITEROOT . '","' . RTYPE_MENUROOT . '") 
					' . sqlRubriqueOnlyReal() . ' ';

        $cRes = GetAll($sql);


        foreach ($cRes as $res) {

            $rub = $GLOBALS['tabUrl'][$res['rubrique_id']] = array(
                'fkRub' => $res['fk_rubrique_id'],
                'gabarit' => $res['fk_gabarit_id'],
                /* 'isFolder'=>$res['rubrique_is_folder'], */
                'param' => $res['rubrique_gabarit_param'],
                'option' => $res['rubrique_option'],
                'template' => $res['rubrique_template'],
                'type' => $res['rubrique_type'],
                'webroot' => ($res['rubrique_type'] == RTYPE_SITEROOT ? $this->getDefWebRoot($res['rubrique_url_' . LG_DEF]) : '')
            );


            reset($_Gconfig['LANGUAGES']);
            foreach ($_Gconfig['LANGUAGES'] as $lg) {
                $GLOBALS['tabUrl'][$res['rubrique_id']]['link_' . $lg] = $res['rubrique_link_' . $lg];
                $GLOBALS['tabUrl'][$res['rubrique_id']]['titre_' . $lg] = $res['rubrique_titre_' . $lg];
                $GLOBALS['tabUrl'][$res['rubrique_id']]['url' . $lg] = $res['rubrique_url_' . $lg];
            }
        }


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

            //debug($row);
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

    function getDefWebRoot($str)
    {

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
    function getTabUrl()
    {
        return $GLOBALS['tabUrl'];
    }

    /**
     * Retourne les chemins supplémentaires ajoutés au chemin de fer
     *
     * @return unknown
     */
    function getRoadSup()
    {
        return $this->roadSup;
    }

    /**
     * Definit si l'on est dans un minisite
     *
     * @return unknown
     */
    function isMiniSite()
    {
        global $_Gconfig;

        $host = niceName($_SERVER["HTTP_HOST"]);

        if (strstr($host, $_Gconfig['minisite_sous_domaine']) && 'http://' . $_SERVER["HTTP_HOST"] . '/' != WEB_URL) {

            $this->minisite = true;


            $this->minisite_nom = str_replace($_Gconfig['minisite_sous_domaine'], '', $host);


            //print('Mini site nom : '.$this->minisite_nom);

            $sql = 'SELECT * FROM s_rubrique WHERE rubrique_url_fr = "' . $this->minisite_nom . '" AND rubrique_type = "' . RTYPE_SITEROOT . '"';

            $row = GetSingle($sql);

            if (count($row)) {
                $this->minisite_row = $row;
                //$this->rootHomeId = $this->root_id = $row['rubrique_id'];
            } else {
                /* print('Mini site Inconnu ! : '.$this->minisite_nom);
                  die();
                 */
                $this->minisite = false;
                return false;
            }
            /*
              $sql = 'SELECT * FROM s_rubrique WHERE fk_rubrique_id = "'.$this->root_id.'" '.sqlRubriqueOnlyReal().' ORDER BY rubrique_ordre ASC';
              $res = GetAll($sql);



              global $rootId ;
              $row = current($res);

              $rootId = $row['rubrique_id'];

              global $footRootId;
              $row = next($res);
              $footRootId = $row['rubrique_id'];

              global $headRootId;

              $headRootId = '999999999999999';
             */
        }
        return $this->minisite;
    }

    /**
     * Parse les élements de l'URL et récupère chaque partie sous forme de tableau
     *
     * @return array
     */
    function parseUrl()
    {

        global $_Gconfig;
        $x_url = explode('?', $_SERVER['REQUEST_URI']);


        $x_url = str_replace('/index.html', '/', $x_url);

        $x_url = $x_url[0];
        $x_url = explode('/_action/', $x_url);


        $this->action = ake($x_url, 1) ? $x_url[1] : '';

        $this->splitAction();

        $x_url = explode('/' . GetParam('fake_folder_param') . '', $x_url[0]);

        $x_url[0] = str_replace(BU, '', $x_url[0]);

        $params = ake($x_url, 1) ? $x_url[1] : '';

        $this->splitParams($params);

        $x_url = $x_url[0];

        $x_url = explode('/', $x_url);

        $this->parsedUrl = $x_url;


        global $_Gconfig;
        if ($_Gconfig['onlyOneLgForever']) {
            if (!defined('LG')) {
                define("LG", $_Gconfig['LANGUAGES'][0]);
                $GLOBALS['ocmsLG'] = LG;
                define('TRADLG', false);
            }
            $this->lg = LG();
            mylocale(LG());
        } else {
            $templg = akev($this->parsedUrl, 1);
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
            } else if (count($this->parsedUrl) > 1 && $templg) {

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


        $this->parsedUrl = $this->trimTab($this->parsedUrl);


        return $x_url;
    }

    /**
     * Sépare les actions du reste de l'URL
     *
     */
    function splitAction()
    {

        $this->action = explode('/', $this->action);
        $this->action = end($this->action);
    }

    /**
     * Sépare les paramètres /bdd/ du reste de l'URL
     *
     * @param unknown_type $params
     */
    function splitParams($params)
    {

        $params = explode(getParam('param_key_sep'), $params);
        $paramNom = '';
        foreach ($params as $param) {
            if ($param) {
                $t = explode(getParam('param_val_sep'), $param);
                $paramNom = akev($t, 0);
                $this->paramsOrdered[] = $param;
                $param = akev($t, 1);
                if (substr($paramNom, -6) == '__list') {
                    $paramNom = substr($paramNom, 0, -6);
                    $this->paramsUrl[$paramNom] = $_REQUEST[$paramNom] = $_GET[$paramNom] = explode('_-_', urldecode($param));
                } else {
                    $this->paramsUrl[$paramNom] = $_REQUEST[$paramNom] = $_GET[$paramNom] = urldecode($param);
                }
            }
        }
    }

    /**
     * Retourne l'URL courante dans la langue $lg
     *
     * @param unknown_type $lg
     * @return unknown
     */
    function getUrlInLg($lg)
    {

        return $this->buildUrlFromId(0, $lg, $this->paramsUrl);
    }

    /**
     * Methode qui va parser l'URL et retourne l'identifiant de la rubrique
     * selectionnée
     *
     * @return unknown
     */
    function getRubId()
    {

        global $homeId, $_Gconfig;
        if (IN_ADMIN) {
            $this->lg = LG_DEF;
            return false;
        }

        if (!$this->rubId) {

            if (count($this->parsedUrl) == 0) {
                /**
                 * Racine du site ou minisite
                 */
                if ($this->action == 'editer') {
                    $sql = 'SELECT * FROM s_rubrique
							 WHERE fk_rubrique_version_id = ' . $this->rootHomeId;
                    $row = GetSingle($sql);
                    #debug($row);
                    $this->rubId = $row['rubrique_id'];
                } else {
                    $this->rubId = $this->rootHomeId;
                }
                return $this->rubId;
            } else {

                /**
                 * Sinon on sélectionne les rubriques correspondantes
                 */
                $select = 'SELECT ';
                $nbUrls = count($this->parsedUrl);
                for ($i = 1; $i <= $nbUrls; $i++) {
                    $select .= ' R' . $i . '.rubrique_id AS r' . $i . '_rubrique_id ,  ';
                    $select .= ' R' . $i . '.fk_rubrique_id AS r' . $i . '_fk_rubrique_id ,  ';
                    $select .= ' R' . $i . '.rubrique_type AS r' . $i . '_rubrique_type ,  ';
                    $select .= ' R' . $i . '.fk_gabarit_id AS r' . $i . '_fk_gabarit_id ,  ';
                    $select .= ' R' . $i . '.rubrique_gabarit_param AS r' . $i . '_rubrique_gabarit_param ,  ';
                    $select .= ' R' . $i . '.rubrique_option AS r' . $i . '_rubrique_option ,  ';
                    $select .= ' R' . $i . '.rubrique_template AS r' . $i . '_rubrique_template ,  ';


                    global $_Gconfig;
                    reset($_Gconfig['LANGUAGES']);
                    foreach ($_Gconfig['LANGUAGES'] as $lg) {
                        $select .= ' R' . $i . '.rubrique_url_' . $lg . ' AS r' . $i . '_rubrique_url_' . $lg . ' ,  ';
                        $select .= ' R' . $i . '.rubrique_titre_' . $lg . ' AS r' . $i . '_rubrique_titre_' . $lg . ' ,  ';
                        $select .= ' R' . $i . '.rubrique_link_' . $lg . ' AS r' . $i . '_rubrique_link_' . $lg . ' ,  ';
                    }

                    reset($_Gconfig['LANGUAGES']);
                }


                $select .= ' R1.rubrique_etat  from s_rubrique as R1 ';


                $from = '';
                $where = ' where R1.rubrique_url_' . $this->lg . '=\'' . $this->parsedUrl[count($this->parsedUrl)] . '\'';

                for ($i = 2; $i <= $nbUrls; $i++) {
                    $j = $i - 1;
                    $from .= ', s_rubrique as R' . $i;
                    $where .= ' and R' . $j . '.fk_rubrique_id = R' . $i . '.rubrique_id 
							and R' . $i . '.rubrique_url_' . $this->lg . '=\'' . $this->parsedUrl[count($this->parsedUrl) - $j] . '\' ';
                }

                $CUR = $nbUrls + 1;

                $from .= ', s_rubrique as R' . $CUR;
                $where .= ' and R' . $CUR . '.rubrique_type IN ("' . RTYPE_MENUROOT . '","' . RTYPE_SITEROOT . '") AND R' . $nbUrls . '.fk_rubrique_id = R' . $CUR . '.rubrique_id  ';


                if ($this->minisite) {
                    global $rootId, $headRootId, $footRootId;
                    $where .= ' AND R' . $nbUrls . '.fk_rubrique_id IN ("' . $this->minisite_row['rubrique_id'] . '") ';
                    //debug($where);
                }


                if ($this->action == 'editer') {

                    $where .= sqlRubriqueOnlyVersions('R1');
                } else {
                    $where .= sqlRubriqueOnlyReal('R1');
                }


                $sql = $select . $from . $where;

                $res = GetSingle($sql);
                //echo $sql;


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
                } else {


                    $this->topRubId = $res['r' . $nbUrls . '_rubrique_id'];
                    $this->rubId = $res['r1_rubrique_id'];


                    /**
                     * Sinon on met en cache ce qu'on a trouvé pour la construction des URLs
                     */
                    for ($i = 1; $i <= $nbUrls; $i++) {

                        $wr = $res['r' . $i . '_rubrique_type'] == RTYPE_SITEROOT ? $this->getDefWebRoot($res['r' . $i . '_rubrique_url_' . LG_DEF]) : '';


                        $this->selectedArbo[] = $res['r' . $i . '_rubrique_id'];
                        if (!akev($GLOBALS['tabUrl'], $res['r' . $i . '_rubrique_id'])) {

                            $GLOBALS['tabUrl'][$res['r' . $i . '_rubrique_id']] = array(
                                'fkRub' => $res['r' . $i . '_fk_rubrique_id'],
                                'gabarit' => $res['r' . $i . '_fk_gabarit_id'],
                                'param' => $res['r' . $i . '_rubrique_gabarit_param'],
                                'option' => $res['r' . $i . '_rubrique_option'],
                                'template' => $res['r' . $i . '_rubrique_template'],
                                'type' => $res['r' . $i . '_rubrique_type'],
                                'selected' => true,
                                /* 'isFolder'=> $res['r'.$i.'_rubrique_is_folder'],	 */
                            );


                            reset($_Gconfig['LANGUAGES']);
                            foreach ($_Gconfig['LANGUAGES'] as $lg) {
                                $GLOBALS['tabUrl'][$res['r' . $i . '_rubrique_id']]['link_' . $lg] = $res['r' . $i . '_rubrique_link_' . $lg];
                                $GLOBALS['tabUrl'][$res['r' . $i . '_rubrique_id']]['titre_' . $lg] = $res['r' . $i . '_rubrique_titre_' . $lg];
                                $GLOBALS['tabUrl'][$res['r' . $i . '_rubrique_id']]['url' . $lg] = $res['r' . $i . '_rubrique_url_' . $lg];
                            }
                        }
                    }
                }
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

        //debug($this->rubId);
        return $this->rubId;
    }

    function die404($e = 'Page not found')
    {

        echo '<h1>Error 404</h1><p>' . $e . '</p><p><a href="/">Go back</a></p>';
        die();
    }

    /**
     * Retourne la seconde langue acceptable
     * @return unknown
     * @deprecated
     *
     */
    function otherLg()
    {

        return getOtherLg();
        global $_Gconfig;

        if (LG() != LG_DEF)
            return LG_DEF;
        else
            return $_Gconfig['LANGUAGES'][1];

        return ($this->lg == 'fr' ? 'en' : 'fr');
    }

    /**
     * alias de
     * @param unknown_type $lg
     * @uses myLocale
     *
     */
    function setLocale($lg)
    {
        myLocale($lg);
    }

    /**
     * Vide les cases vide d'un tableau
     *
     * @param unknown_type $tab
     * @return unknown
     */
    function trimTab($tab)
    {
        $newTab = array();
        global $_Gconfig;
        if ($_Gconfig['onlyOneLgForever']) {
            $cpt = 2;
        } else {
            $cpt = 1;
        }
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
     * @return unknown
     * @deprecated
     *
     */
    function getUrlForOtherLg()
    {
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
    function buildUrlFromId($rubId = 0, $lg = '', $params = array(), $action = '')
    {

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
            $this->reversRecursRub($rubId);
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
            //$url = path_concat('http://',$this->curLinkRoot['url'.LG()].$_Gconfig['minisite_sous_domaine'],$url);

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
         *
         * $bddPart = explode(''.GetParam('fake_folder_param').'',$url);
         * if(count($bddPart) > 2) {
         * $url = $bddPart[0].''.GetParam('fake_folder_param').''.$bddPart[1];
         * if($bddPart[2]) {
         * $url.= ''.$bddPart[2];
         * }
         * }
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
    function getUrlWithParams($params)
    {
        return $this->buildUrlFromId(0, '', $params);
    }

    function getUrlWithMoreParams($params)
    {
        return $this->buildUrlFromId(0, '', array_merge($this->paramsUrl, $params));
    }

    /**
     * Retourne l'URL courante telle quelle
     *
     * @return string
     */
    function getCurUrl()
    {
        return $this->buildUrlFromId(0, '', $this->paramsUrl);
    }

    /**
     * Ajoute des paramètres à l'URL courant
     *
     * @param unknown_type $params
     * @return unknown
     */
    function addParams($params)
    {
        if (is_array($params) && count($params) > 0) {
            $url = '' . GetParam('fake_folder_param') . '';

            foreach ($params as $k => $v) {
                if (is_array($v)) {
                    $k = $k . '__list';
                    //$v = implode('_-_',$v);
                    $v = serialize($v);
                }
                $url .= $k;
                if ($v) {
                    $url .= getParam('param_val_sep') . urlencode($v);
                } else {
                    //$url .= getParam('param_val_sep');
                }
                $url .= getParam('param_key_sep');
            }
            return $url;
        }
        return '';
    }

    /**
     * Redefinit certains paramètre pour l'autre langue
     * @param unknown_type $params
     * @deprecated
     *
     */
    function setOtherLgParams($params)
    {

        $this->otherLgParamsUrl = $params;
    }

    /**
     * Tant qu'on a une rubrique au dessus, on remonte
     *
     * @param unknown_type $rubId
     * @return unknown
     */
    function reversRecursRub($rubId)
    {
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
				   ' . sqlRubriqueOnlyOnline('R1') . '
				   and R1.rubrique_id = ' . sql($rubId);
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
    function buildUrl($rubId, $lg)
    {
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

        if ($_Gconfig['onlyOneLgForever']) {
            $lg = '';
        }

        /**
         * Si on ne demande pas la page racine
         */
        $this->curLinkRoot = array();
        if ($rubId != $this->root_id) {
            while (array_key_exists($key, $GLOBALS['tabUrl'])) {
                if ($GLOBALS['tabUrl'][$key]['type'] != 'menuroot') {
                    /**
                     * Distinction pour les "mini sites" en "siteroot" au milieu du site avec des regles d'URL à part
                     */
                    if ($GLOBALS['tabUrl'][$key]['type'] != 'siteroot') {
                        $url = path_concat($GLOBALS['tabUrl'][$key]['url' . $reallg], $url);
                    } else {
                        $this->curLinkRoot = $GLOBALS['tabUrl'][$key];

                        break;
                    }
                }
                $key = $GLOBALS['tabUrl'][$key]['fkRub'];
            }
        }


        /**
         * Si jamais on demande aux pages de pointer vers la premiere sous page
         */
        if (isset($GLOBALS['tabUrl'][$rubId]) && $GLOBALS['tabUrl'][$rubId]['type'] == 'folder' && $rubId != $this->root_id && $GLOBALS['tabUrl'][$rubId]['type'] != RTYPE_SITEROOT) {

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
    function recursRub($rubId, $curLevel = 1, $maxLevel = 99)
    {

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
                        $u = getUrlFromId($rubId, LG(), array($v['PARAM'] => $v['VALUE']));
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
                                $u = getUrlFromId($rid, LG(), array($v['PARAM'] => $v['VALUE']));
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

    function buildRoad($curId = 0, $includeMenuRoot = false)
    {
        //global $rootId;

        if (!$curId) {
            $curId = $GLOBALS['site']->getCurId();
        }

        if (!array_key_exists($curId, $GLOBALS['tabUrl'])) {
            $this->reversRecursRub($curId);
        }

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
    function hasNextRub($row)
    {

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
    function hasPreviousRub($row)
    {
        $sql = 'SELECT rubrique_ordre FROM s_rubrique WHERE fk_rubrique_id ="' . $row['fk_rubrique_id'] . '"  ' . sqlRubriqueOnlyReal() . ' AND rubrique_ordre < "' . $row['rubrique_ordre'] . '" ORDER BY rubrique_ordre';

        $res = GetAll($sql);
        if (count($res)) {
            return true;
        }
    }

    /* Methode qui permet d'ajouter un element au tableau des rubriques hors bdd */

    function addRoad($titre, $url, $id = null)
    {
        $this->roadSup[] = array('titre' => $titre, 'url' => $url, 'id' => $id);
    }

    /* Methode qui retourne le niveau de profondeur */

    function getDepth($rubid = 0)
    {
        $rubid = $rubid ? $rubid : $this->getRubId();

        if (!array_key_exists($curId, $GLOBALS['tabUrl']))
            $this->reversRecursRub($curId);
    }

    /* Methode qui permet de recuperer la langue du navigateur client */

    function getBrowserLang()
    {
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

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

class genUrlV2
{

    /**
     * Tableau qui stocke les url des rubriques
     *
     * @var array
     */
    public $tabUrl;
    /**
     * Liste des sous rubriques virtuelles supplémentaires
     * @var array
     */
    public $roadSup;
    /**
     * Liste des paramètres passés dans l'URL
     */
    public $paramsUrl = array();
    /**
     * Liste des paramètres passés dans l'URL
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
    public $paramsAsIndex = array();
    /**
     * Identifiant de la rubrique en cours
     */
    public $rubId = 0;
    /**
     * Liste des dossiers parsés avec rubriques et paramètres
     * @var boolean
     */
    public $parsedUrl = false;
    public $rootHomeId = 0;
    public $homeId = 0;

    public $curWebRoot = '';
    public $TEMPLATE = '';
    public $rootRow = [];
    public $curLinkRoot;
    /**
     * cache du chemin de fer construit
     *
     * @var array
     */
    public $builtRoad = array();
    /**
     * Tableau des menus de notre siteroot actuel
     *
     * @var array
     */
    public $menus = array();
    /**
     * Langue en cours
     * @var string
     */
    private $lg;
    /**
     * Si les paramètres passés doivent être différents dans une autre langue
     */
    private $otherLgParamsUrl;
    /**
     *
     * @var array Rubriques parentes sélectionnées
     */
    private $selectedArbo = array();

    /**
     * Seconde langue de traduction
     * @var string
     */
    public $tradlg;

    /**
     * Action en cours
     * @var string
     */
    public $action = '';

    /**
     * @var int
     */
    public $actionId;

    /**
     * @var int racine du site
     * */
    public $root_id;


    public $iniGet;
    public $iniPost;

    /**
     * Constructeur de la classe genUrl
     * @param string $lg Langue courante si deja connue
     */
    function __construct($lg = '')
    {

        if (empty($GLOBALS['GlobalObjCache']['tabUrl'])) {
            $GLOBALS['GlobalObjCache']['tabUrl'] = array();
        }
        $this->lg = $lg;

        $this->minisite = false;
        //$GLOBALS['GlobalObjCache']['tabUrl'] = array();
        $GLOBALS['urlCached'] = array();
        $this->roadSup = array();
        if (!IN_ADMIN) {

            $this->parseUrl();
            if (!empty($_GET['_version'])) {
                $this->getFromVersion();
            } else {
                $this->rootRow = $this->getSiteRoot();
                $this->rootHomeId = $this->rootRow['rubrique_id'];
                $this->rubId = $this->getRubId();
            }
        } else {
            if (!$lg) {
                $this->lg = $lg = LG_DEF;
                if (!defined('LG')) {
                    define('LG', $lg);
                    $GLOBALS['ocmsLG'] = LG;
                }
            }
        }
    }

    /**
     * Parse les élements de l'URL et récupère chaque partie sous forme de tableau
     *
     * @return array
     */
    function parseUrl()
    {

        if ($this->parsedUrl) {
            return $this->parsedUrl;
        }

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
                $GLOBALS['ocmsLG'] = LG;
                define('TRADLG', false);
            }
            $this->lg = LG();
            mylocale(LG());
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
                    $this->soft404();
                }
                if (!defined('LG')) {
                    define("LG", $this->lg);
                    define('TRADLG', false);
                    $GLOBALS['ocmsLG'] = LG;
                }
                mylocale($this->lg);
            } else {
                $this->lg = empty($this->lg) ? $_Gconfig['LANGUAGES'][0] : $this->lg;
                if (!defined('LG')) {
                    define("LG", $this->lg);
                    define('TRADLG', false);
                    $GLOBALS['ocmsLG'] = LG;
                }
                mylocale($this->lg);
            }
        }

        $this->parsedUrl = $this->trimTab($dossiers);


        return $this->parsedUrl;
    }

    /**
     * Sépare les actions du reste de l'URL
     *
     */
    function splitAction()
    {
        $this->action = explode('/', $this->action);
        $this->actionId = end($this->action);
        $this->action = reset($this->action);
    }

    function getBrowserLang()
    {
        global $_Gconfig;
        if (empty($_SERVER["HTTP_ACCEPT_LANGUAGE"])) {
            return LG_DEF;
        }
        $langs = explode(",", $_SERVER["HTTP_ACCEPT_LANGUAGE"]);

        foreach ($langs as $value) {
            $choice = substr($value, 0, 2);
            if (in_array($choice, $_Gconfig['LANGUAGES'])) {

                return $choice;
            }
        }
        return LG_DEF;
    }

    public function soft404()
    {
        $GLOBALS['site']->isCurrent404 = true;
        $GLOBALS['site']->execute('ocms_is404');
        header((isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0') . " 404 Not Found", true, 404);


        if (strpos($_SERVER['REQUEST_URI'], 'css') || strpos($_SERVER['REQUEST_URI'], 'js') || strpos($_SERVER['REQUEST_URI'], 'jpeg') || strpos($_SERVER['REQUEST_URI'], 'jpg') || strpos($_SERVER['REQUEST_URI'], 'gif') || strpos($_SERVER['REQUEST_URI'], 'png') || strpos($_SERVER['REQUEST_URI'], 'svg')) {
            $this->die404();
        }

        $this->rubId = getRubFromGabarit('gen404');

        if (!$this->rubId) {
            global $_Gconfig;
            if (!empty($_Gconfig['doDie404'])) {
                $this->die404();
            }
        }
    }

    function die404($msg = '')
    {
        $GLOBALS['site']->isCurrent404 = true;
        $GLOBALS['site']->execute('ocms_die404');
        header((isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0') . " 404 Not Found", true, 404);
        echo '<html><head><link href="//cdnjs.cloudflare.com/ajax/libs/zurb-ink/1.0.5/ink.min.css" rel="stylesheet"> </head><body><article style="padding:15px"><h1>Error 404</h1><p>The page can not be found</p><p><a href="/">Go back</a></p><p>' . $msg . '</p></article></body></html>';
        die();
    }

    /**
     * Vide les cases vide d'un tableau
     *
     * @param array $tab
     * @return array
     */
    function trimTab($tab)
    {
        $newTab = array();

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

    public function getFromVersion()
    {
        $this->rubId = (int)$_GET['_version'];
        $rubId = $this->rubId;
        $i = 0;
        while ($rubId) {
            $i++;
            $r = GetSingle('SELECT ocms_version, fk_rubrique_id, rubrique_type FROM s_rubrique WHERE rubrique_id = ' . sql($rubId));

            $this->selectedArbo[] = $r['ocms_version'];
            if (!$r['fk_rubrique_id'] || $r['rubrique_type'] == RTYPE_SITEROOT) {
                $rubId = false;
                $this->root_id = $this->rootHomeId = $r['ocms_version'];
                $this->rootRow = getRowFromId('s_rubrique', $r['ocms_version']);
                $this->getSiteRoot($this->rootRow);
            } else {
                $rubId = $r['fk_rubrique_id'];
            }
            if ($i == 100) {
                break;
            }
        }
        return $this->rubId;
    }

    /**
     * Methode pour definir quelle rubrique contient l'ensemble du site
     * C'est la rubrique qui est définit comme
     * rubrique_type = 'siteroot'
     * et dont l'url correspond au $_SERVER['HTTP_HOST'] puis en concatenant  dirname($_SERVER["SCRIPT_NAME"]);
     *
     */
    function getSiteRoot($row = null)
    {

        global $_Gconfig;
        $host = $_SERVER["HTTP_HOST"];
        if (!$row) {
            /**
             * sélection du siteroot à partir du
             * nom de domaine/sous domaine
             */
            $sql = 'SELECT * FROM s_rubrique
				 WHERE rubrique_type
				 = "' . RTYPE_SITEROOT . '"
				 ' . sqlRubriqueOnlyOnline() . '
				 ' . lgFieldsLike("rubrique_url", '%;' . mes($host) . ';%', ' OR ') . '
				  ';
            $row = GetSingle($sql);
        }

        if (!isset($row['rubrique_id'])) {
            $sql = 'SELECT * FROM s_rubrique
				 WHERE rubrique_type
				 = "' . RTYPE_SITEROOT . '"
				 ' . sqlRubriqueOnlyOnline() . '
                     ORDER BY rubrique_id ASC
				  ';
            $res = DoSql($sql);
            if ($res->NumRows() === 1) {
                $row = $res;
            } else {
                $this->root_id = 1;
                $html = '<ul>';
                foreach ($res as $row) {

                    $url = explode(';', $row['rubrique_url_' . $this->lg]);

                    if (!empty($url[1])) {
                        $html .= '<li><a href="http://' . $url[1] . '">' . $row['rubrique_titre_' . $this->lg] . '</a></li>';
                    }
                }
                $html .= '</ul>';
                $this->die404($html . 'NO_SITEROOT_MATCHING_AND_MORE_THAN_ONE');
            }
        }

        if (($row)) {
            if (is_object($row)) {
                $row = $res = $row->fetchRow();
            } else {
                $res = $row;
            }
            $GLOBALS['GlobalObjCache']['tabUrl'][$res['rubrique_id']] = array(
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
                $GLOBALS['GlobalObjCache']['tabUrl'][$res['rubrique_id']]['link_' . $lg] = $res['rubrique_link_' . $lg];
                $GLOBALS['GlobalObjCache']['tabUrl'][$res['rubrique_id']]['titre_' . $lg] = $res['rubrique_titre_' . $lg];
                $GLOBALS['GlobalObjCache']['tabUrl'][$res['rubrique_id']]['url' . $lg] = $res['rubrique_url_' . $lg];
            }
            /**
             * Siteroot trouvé tout va bien
             */
            $this->homeId = $this->rootHomeId = $this->root_id = $row['rubrique_id'];
            $this->curWebRoot = $this->getDefWebRoot($row['rubrique_url_' . LG_DEF]);
            $this->TEMPLATE = $row['rubrique_template'];


            /**
             * Sélection des menus de notre siteroot
             */
            $sql = 'SELECT rubrique_url_' . LG() . ',  rubrique_id FROM s_rubrique'
                . ' WHERE '
                . 'fk_rubrique_id = ' . $this->rootHomeId . ' '
                . ' AND rubrique_type = ' . sql(RTYPE_MENUROOT) . ''
                . '  ' . sqlMenuOnlyOnline();
            global $co;
            $this->menus = $co->getAssoc($sql);

            return $row;
        } else {

            /**
             * Le nom de domaine doit etre déclaré !
             */
            $this->die404('NO_SITEROOT_FOUND');
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
        //return $_SERVER['HTTP_HOST'];
        if (strlen($et[0])) {
            return $et[0];
        } else {
            return $et[1];
        }
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

        if ($this->rubId) {
            return $this->rubId;
        }

        if (!empty($_GET['_version'])) {
            $this->getFromVersion();
        }
        /*
          if ($this->action === 'editer' && $this->actionId) {
          $this->rubId = $this->actionId;
          return $this->rubId;
          } */
        if (!$this->rubId) {

            if (count($this->parsedUrl) == 0) {
                /**
                 * Racine du site ou minisite
                 */
                if ($this->action == 'editer') {
                    $sql = 'SELECT * FROM s_rubrique WHERE ' . MULTIVERSION_FIELD . ' = ' . $this->rootHomeId;
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
                $parentRub = false;
                $k = 0;


                foreach ($this->parsedUrl as $dossier) {

                    /**
                     * Sélection des rubriques
                     */
                    $select = 'SELECT
                                    rubrique_id,
                                    fk_rubrique_id,
                                    rubrique_type,
                                    fk_gabarit_id,
                                    rubrique_gabarit_param,
                                    rubrique_option,
                                    rubrique_template,
                                    ' . MULTIVERSION_STATE . ',
                                    ' . MULTIVERSION_FIELD . ',

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

                    $select .= ' R1.' . MULTIVERSION_STATE . ' from s_rubrique as R1 ';

                    /**
                     * Recherche du dossier en cours
                     */
                    $where = ' WHERE R1.rubrique_url_' . $this->lg . '= ' . sql(urldecode($dossier)) . ' ';

                    if ($k == 0) {
                        /**
                         * La premiere rubrique doit etre dans la racine
                         * ou dans un menu à la racine
                         */
                        $where .= ' AND fk_rubrique_id IN '
                            . ' (' . $this->rootHomeId . ' , '
                            . ' ' . implode(",", $this->menus) . ')';
                    } else {
                        /**
                         * Si au moins un parent, on restreint
                         */
                        $where .= ' AND fk_rubrique_id = ' . sql($parentRub);
                    }

                    if ($this->action == 'editer' && $this->actionId) {
                        $where .= sqlRubriqueOnlyVersions('R1') . ' AND R1.rubrique_id = ' . sql($this->actionId);
                    } else {
                        $where .= sqlRubriqueOnlyReal('R1');
                        $where .= sqlRubriqueOnlyOnline('R1');
                    }

                    $r = GetSingle($select . $where);

                    /**
                     * Aucun résultat, on est dans les paramètres à partir d'ici ...
                     */
                    if (!$r) {
                        if (!empty($parentRub) && $GLOBALS['GlobalObjCache']['tabUrl'][$parentRub]['gabarit']) {
                            $this->paramsAsIndex = array_slice($this->parsedUrl, $k);
                            $this->splitParams($this->paramsAsIndex);
                        } else {
                            $parentRub = false;
                        }
                        /**
                         * Donc la rubrique précédente était la bonne !
                         */
                        break;
                    }

                    $this->selectedArbo[] = $r['rubrique_id'];


                    $parentRub = choose($r[MULTIVERSION_FIELD], $r['rubrique_id']);


                    $GLOBALS['GlobalObjCache']['tabUrl'][$r['rubrique_id']] = array(
                        'fkRub' => $r['fk_rubrique_id'],
                        'gabarit' => $r['fk_gabarit_id'],
                        'param' => $r['rubrique_gabarit_param'],
                        /* 'isFolder'=>$res['rubrique_is_folder'], */
                        'option' => $r['rubrique_option'],
                        'template' => $r['rubrique_template'],
                        'type' => $r['rubrique_type']
                    );

                    if ($r['rubrique_type'] == RTYPE_SITEROOT) {
                        $rub['webroot'] = $GLOBALS['GlobalObjCache']['tabUrl'][$r['rubrique_id']]['webroot'] = $this->getDefWebRoot($r['rubrique_url_' . LG_DEF]);
                    }

                    reset($_Gconfig['LANGUAGES']);
                    foreach ($_Gconfig['LANGUAGES'] as $lg) {
                        $GLOBALS['GlobalObjCache']['tabUrl'][$r['rubrique_id']]['link_' . $lg] = $r['rubrique_link_' . $lg];
                        $GLOBALS['GlobalObjCache']['tabUrl'][$r['rubrique_id']]['titre_' . $lg] = $r['rubrique_titre_' . $lg];
                        $GLOBALS['GlobalObjCache']['tabUrl'][$r['rubrique_id']]['url' . $lg] = $r['rubrique_url_' . $lg];
                    }
                    $k++;
                }

                if ($this->action === 'editer') {
                    $r = getSingle('SELECT * FROM s_rubrique WHERE rubrique_id = ' . sql($this->actionId));
                    $parentRub = $r['rubrique_id'];
                }

                $this->rubId = $parentRub;

                /**
                 * On a pas trouvé la rubrique
                 * c'est donc une erreur 404
                 */
                if (!$this->rubId) {
                    $this->soft404();

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

    /**
     * Sépare les paramètres /bdd/ du reste de l'URL
     *
     * @param unknown_type $params
     */
    function splitParams($params)
    {
        $p = array();
        $this->paramsUrl = array();
        $this->iniGet = $_GET;
        $this->iniPost = $_POST;
        foreach ($params as $k => $v) {
            $vv = explode(getParam('param_val_sep'), $v);
            if (empty($vv[1])) {
                $vv[1] = '';
            }
            $this->paramsUrl[$vv[0]] = $_REQUEST[$vv[0]] = $_GET[$vv[0]] = urldecode($vv[1]);
            $this->paramsOrdered[] = $v;
        }
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
     * Retourne le cache des URLs
     *
     * @return unknown
     */
    function getTabUrl()
    {
        return $GLOBALS['GlobalObjCache']['tabUrl'];
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

        if (!array_key_exists($rubId, $GLOBALS['GlobalObjCache']['tabUrl'])) {
            $this->reversRecursRub($rubId, $action == 'editer' ? false : true);
        }


        if (isset($GLOBALS['GlobalObjCache']['tabUrl'][$rubId]) && $GLOBALS['GlobalObjCache']['tabUrl'][$rubId]['type'] == 'link') {
            $url = GetLgValue('link', $GLOBALS['GlobalObjCache']['tabUrl'][$rubId], false);

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
            if (!empty($this->curLinkRoot['webroot'])) {
                $url = path_concat('//', $this->curLinkRoot['webroot'], $url);
            } else {
                debug($this->curLinkRoot);
            }
        } else {

            $rub = $rubId;

            while (
                !empty($GLOBALS['GlobalObjCache']['tabUrl'][$rub]) &&
                empty($GLOBALS['GlobalObjCache']['tabUrl'][$rub]['webroot']) && $rub > 0 && $rub != 'NULL') {
                $rub = $GLOBALS['GlobalObjCache']['tabUrl'][$rub]['fkRub'];
            }

            /**
             * On doit avoir un webroot de trouvé
             * Et il doit etre différent du HTTP_HOST actuel
             */
            if (empty($_Gconfig['neverAddHostInUrl']) && !empty($GLOBALS['GlobalObjCache']['tabUrl'][$rub]['webroot']) &&
                (IN_ADMIN || (
                        $GLOBALS['GlobalObjCache']['tabUrl'][$rub]['webroot'] != $_SERVER['HTTP_HOST']))
            ) {
                $url = path_concat('//', $GLOBALS['GlobalObjCache']['tabUrl'][$rub]['webroot'], $url);
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
     * Tant qu'on a une rubrique au dessus, on remonte
     *
     * @param unknown_type $rubId
     * @return unknown
     */
    function reversRecursRub($rubId, $onlyOnline = true)
    {
        global $_Gconfig;

        if (!$rubId)
            return;

        if (!is_array(akev($GLOBALS['GlobalObjCache']['tabUrl'], $rubId))) {
            $sql = 'SELECT R1.* ,
				   R1.rubrique_id AS rubId,
				   R2.rubrique_id AS p_rubId,

				   R2.fk_rubrique_id AS p_fkRubId
				   FROM s_rubrique AS R1, s_rubrique AS R2
				   WHERE R1.fk_rubrique_id = R2.rubrique_id
				   AND R1.rubrique_id = ' . sql($rubId);
            if ($onlyOnline && empty($_GET['_version'])) {
                $sql .= '' . sqlRubriqueOnlyOnline('R1', !$onlyOnline, true) . '';
            }
            $res = GetSingle($sql);


            if (!empty($res) && !is_array(akev($GLOBALS['GlobalObjCache']['tabUrl'], akev($res, 'rubId')))) {

                if (isset($res['rubId'])) {
                    $rub = $GLOBALS['GlobalObjCache']['tabUrl'][$res['rubId']] = array(
                        'fkRub' => $res['p_rubId'],
                        'gabarit' => $res['fk_gabarit_id'],
                        'param' => $res['rubrique_gabarit_param'],
                        /* 'isFolder'=>$res['rubrique_is_folder'], */
                        'option' => $res['rubrique_option'],
                        'template' => $res['rubrique_template'],
                        'type' => $res['rubrique_type'],
                        'p_fkRubId' => $res['p_fkRubId']
                    );

                    if ($res['rubrique_type'] == RTYPE_SITEROOT) {
                        $rub['webroot'] = $GLOBALS['GlobalObjCache']['tabUrl'][$res['rubId']]['webroot'] = $this->getDefWebRoot($res['rubrique_url_' . LG_DEF]);
                    }

                    reset($_Gconfig['LANGUAGES']);
                    foreach ($_Gconfig['LANGUAGES'] as $lg) {
                        $GLOBALS['GlobalObjCache']['tabUrl'][$res['rubId']]['link_' . $lg] = $res['rubrique_link_' . $lg];
                        $GLOBALS['GlobalObjCache']['tabUrl'][$res['rubId']]['titre_' . $lg] = $res['rubrique_titre_' . $lg];
                        $GLOBALS['GlobalObjCache']['tabUrl'][$res['rubId']]['url' . $lg] = $res['rubrique_url_' . $lg];
                    }
                }
            } else if (!empty($res)) {
                $rub = $GLOBALS['GlobalObjCache']['tabUrl'][$res['rubId']];
            }
        } else {
            $rub = $GLOBALS['GlobalObjCache']['tabUrl'][$rubId];
        }

        if (isset($rub) && akev($rub, 'fkRub') != NULL) {
            return $this->reversRecursRub($rub['fkRub']);
        }

        return $GLOBALS['GlobalObjCache']['tabUrl'];
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
            while (array_key_exists($key, $GLOBALS['GlobalObjCache']['tabUrl'])) {
                if ($GLOBALS['GlobalObjCache']['tabUrl'][$key]['type'] != 'menuroot') {
                    /**
                     * Distinction pour les "mini sites" en "siteroot" au milieu du site avec des regles d'URL à part
                     */
                    if ($GLOBALS['GlobalObjCache']['tabUrl'][$key]['type'] != 'siteroot') {
                        $url = path_concat($GLOBALS['GlobalObjCache']['tabUrl'][$key]['url' . $reallg], $url);
                    } else {
                        $this->curLinkRoot = $GLOBALS['GlobalObjCache']['tabUrl'][$key];

                        break;
                    }
                }
                $key = $GLOBALS['GlobalObjCache']['tabUrl'][$key]['fkRub'];
            }
        }


        /**
         * Si jamais on demande aux pages de pointer vers la premiere sous page
         */
        if (isset($GLOBALS['GlobalObjCache']['tabUrl'][$rubId]) && $GLOBALS['GlobalObjCache']['tabUrl'][$rubId]['type'] == 'folder' && $rubId != $this->root_id && $GLOBALS['GlobalObjCache']['tabUrl'][$rubId]['type'] != RTYPE_SITEROOT) {

            $subId = $rubId;
            //$subs = $this->recursRub($subId,1,1);
            if (rubHasOption($GLOBALS['GlobalObjCache']['tabUrl'][$rubId]['option'], 'dynSubRubs')) {
                $subs = getGabaritSubRubs(getRowFromId('s_rubrique', $rubId), $GLOBALS['GlobalObjCache']['tabUrl'][$rubId]['gabarit']);
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
			   FROM  s_rubrique AS R2
			   WHERE R2.fk_rubrique_id
			   ' . sqlParam($rubId) . '
			   ' . sqlRubriqueOnlyOnline('R2') . '
			   ' . sqlRubriqueOnlyReal('R2') . '
			   AND rubrique_type != "menu"
			   AND rubrique_type != "menuroot"
			   ORDER BY R2.rubrique_ordre ASC';

        $res = DoSql($sql);


        if (!($res->NumRows())) {

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


            if ($sRub[MULTIVERSION_STATE] != 'en_ligne')
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
                if (!ake($GLOBALS['GlobalObjCache']['tabUrl'], $sRub['rubrique_id'])) {
                    $GLOBALS['GlobalObjCache']['tabUrl'][$sRub['rubrique_id']] = array(
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
                        $GLOBALS['GlobalObjCache']['tabUrl'][$sRub['rubrique_id']]['link_' . $lg] = $sRub['rubrique_link_' . $lg];
                        $GLOBALS['GlobalObjCache']['tabUrl'][$sRub['rubrique_id']]['titre_' . $lg] = $sRub['rubrique_titre_' . $lg];
                        $GLOBALS['GlobalObjCache']['tabUrl'][$sRub['rubrique_id']]['url' . $lg] = $sRub['rubrique_url_' . $lg];
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

    /**
     * Ajoute des paramètres à l'URL courant
     *
     * @param unknown_type $params
     * @return unknown
     */
    function addParams($params)
    {
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
     * Retourne l'URL courante dans l'autre langue
     * @return unknown
     * @deprecated
     *
     */
    function getUrlForOtherLg()
    {
        //debug($GLOBALS['GlobalObjCache']['tabUrl']);
        $p = is_array($this->otherLgParamsUrl) ? $this->otherLgParamsUrl : $this->paramsUrl;

        //debug($this->otherLgParamsUrl);
        return $this->buildUrlFromId(0, $this->otherLg(), $p);
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

    /* Methode permettant de construire le "chemin de fer" de la page en-cours */

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

    function buildRoad($curId = 0, $includeMenuRoot = false)
    {

        if (!$curId) {
            $curId = $GLOBALS['site']->getCurId();
        }

        if (!empty($this->builtRoad[$curId])) {
            return $this->builtRoad[$curId];
        }

        //if (!array_key_exists($curId, $GLOBALS['GlobalObjCache']['tabUrl'])) {
        $this->reversRecursRub($curId);
        //}


        $key = $curId;
        $road = array();

        if ($curId != $this->rootHomeId) {
            $i = 1;
            while (array_key_exists($key, $GLOBALS['GlobalObjCache']['tabUrl']) && $key && $i <= 100) {

                $i++;

                if ($includeMenuRoot || $GLOBALS['GlobalObjCache']['tabUrl'][$key]['type'] != RTYPE_MENUROOT) {
                    $road[] = array('id' => $key,
                        'titre' => getLgValue('titre', $GLOBALS['GlobalObjCache']['tabUrl'][$key]),
                        'url' => $this->buildUrlFromId($key));
                }
                $key = $GLOBALS['GlobalObjCache']['tabUrl'][$key]['fkRub'];
            }

            $road = array_reverse($road);

            foreach ($this->roadSup as $r) {
                $road[] = $r;
            }
        }
        $this->builtRoad[$curId] = $road;
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

        $sql = 'SELECT rubrique_ordre FROM s_rubrique WHERE fk_rubrique_id ="' . $row['fk_rubrique_id'] . '" '
            . ' ' . sqlRubriqueOnlyReal() . ''
            . '  AND rubrique_ordre > "' . $row['rubrique_ordre'] . '" '
            . ' LIMIT 0,1';

        $res = DoSql($sql);
        if (($res->NumRows() > 0)) {
            return true;
        }
        return false;
    }

    /* Methode qui permet d'ajouter un element au tableau des rubriques hors bdd */

    /**
     * La rubrique $row a t'elle une rubrique AVANT ?
     *
     * @param unknown_type $row
     * @return unknown
     */
    function hasPreviousRub($row)
    {
        $sql = 'SELECT rubrique_ordre FROM s_rubrique WHERE '
            . ' fk_rubrique_id ="' . $row['fk_rubrique_id'] . '" '
            . ' ' . sqlRubriqueOnlyReal() . ' '
            . ' AND rubrique_ordre < "' . $row['rubrique_ordre'] . '"'
            . ' LIMIT 0, 1';

        $res = DoSql($sql);
        if (($res->NumRows() > 0)) {
            return true;
        }
    }

    /* Methode qui retourne le niveau de profondeur */

    function addRoad($titre, $url, $id = false)
    {
        $this->roadSup[] = array('titre' => $titre, 'url' => $url, 'id' => $id);
        $this->builtRoad[$GLOBALS['site']->getCurId()] = false;
    }

    /* Methode qui permet de recuperer la langue du navigateur client */

    function getDepth($rubid = 0)
    {
        $rubid = $rubid ? $rubid : $this->getRubId();

        if (!array_key_exists($rubid, $GLOBALS['GlobalObjCache']['tabUrl']))
            $this->reversRecursRub($rubid);
    }

}

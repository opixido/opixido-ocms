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

class genSite {

    public $rubrique_id;
    public $lg;
    /**
     * Gen URL
     *
     * @var genUrl
     */
    public $g_url;
    
    /**
     * Les headers
     *
     * @var genHeaders GenHEaders
     */
    public $g_headers;
    /**
     * Gen Menu
     *
     * @var genMenu
     */
    public $g_menu;
    /**
     * Objet g_rubrique
     *
     * @var genRubrique
     */
    public $g_rubrique;
    /**
     * La page en cours a t'elle été trouvée ?
     * 
     * @var boolean
     */
    public $isCurrent404 = false;

    /**
     * Génération du site
     * Gestion des headers / footer / rubrique  / menu / url
     *
     * @return genSite
     */
    function __construct() {


	global $lg, $otherLg;
	global $lglocale;

	$GLOBALS['_gensite'] = &$this;

	loadParams();
    }

    /**
     * Only load basic files
     * Usefull for ajax actions etc ...
     *
     */
    public function initLight() {

	global $_Gconfig;

	if(!defined('LG')) {
	    define("LG", LG_DEF);
	    myLocale(LG_DEF);
	    define("LGDEF", false);
	}
	//$GLOBALS['gb_obj']->includeFile($_Gconfig['URL_MANAGER'].'.php','global/ondemand');
	$this->g_url = new $_Gconfig['URL_MANAGER'](LG);
    }

    /**
     * Full init
     *
     */
    public function init() {

	global $_Gconfig;

	$this->g_url = new $_Gconfig['URL_MANAGER']();
	
	$this->rubrique_id = $this->g_url->getRubId();


	$this->lg = $this->g_url->getLg();

	$lg = $this->lg;

	if ($lg) {

	    mylocale($lg);
	} else {
	    mylocale(LG_DEF);
	}

	if (!defined('LG')) {
	    define("LG", $lg);
	}

	loadTrads($this->lg);

	$this->pluginLoadConf();

	
	/**
	 * Liste des menus
	 */
	$sql = 'SELECT * FROM s_rubrique
			WHERE 1
			AND rubrique_type LIKE "' . RTYPE_MENUROOT . '"
			' . sqlMenuOnlyOnline() . ' 
			AND fk_rubrique_id = "' . $this->g_url->rootHomeId . '"
			ORDER BY rubrique_ordre ASC';


	$res = GetAll($sql);

	$this->menus = array();
	foreach ($res as $row) {
	    $this->menus[$row['rubrique_url_' . LG_DEF]] = new genMenu($this, $row['rubrique_url_' . LG_DEF], $row['rubrique_id'], $row);
	}




	$baseLgLoc = $lg . '_' . strtoupper($lg);
	$lglocale = array($baseLgLoc . '.UTF-8', $baseLgLoc . '.utf8', $baseLgLoc . '@euro', $baseLgLoc, $lg);

	mylocale($lglocale);


	// Headers HTML
	$this->g_headers = new genHeaders($this);

	// Gestion de la rubrique
	$this->g_rubrique = new genRubrique($this);

	$this->plugins = &$this->g_rubrique->plugins;
	$GLOBALS['plugins'] = &$this->g_rubrique->plugins;
    }

    /**
     * Charge tous les config.php des plugins
     */
    function pluginLoadConf() {

	$p = GetPlugins();

	foreach ($p as $v) {
	    $GLOBALS['gb_obj']->includeFile('config.php', PLUGINS_FOLDER . '' . $v . '/');
	}
    }

    /**
     * Gere les actions front office
     */
    function handleAction() {
	if (strlen($this->g_url->action)) {
	    $ga = new GenAction($this->g_url->action, 's_rubrique', $this->rubrique_id);
	    $ga->DoIt();
	    //debug('valid');
	}
    }
    

    /**
     * Apres la construction, l'initialisation
     */
    function afterInit() {
	$this->g_rubrique->afterInit();
    }

    function gen() {

	/**
	 * 	Genere le site
	 * 	Avec ou sans popup, en PDF ou non, ...
	 * 	TODO : Gérer de maniere plus dynamique les differents type d'affichage
	 */
	$this->g_rubrique->execute('beforeGen');
	$html = "";

	if ($this->g_url->TEMPLATE) {
	    $tpl = $this->g_url->TEMPLATE;
	} else {
	    $tpl = 'default';
	}
	if (ake($_REQUEST, 'ocms_mode')) {
	    $mode = str_replace('.', '', niceName($_REQUEST['ocms_mode']));
	} else {
	    $mode = 'html';
	}

	include($GLOBALS['gb_obj']->getIncludePath($tpl . '.' . $mode . '.php', 'exports'));

	if (akev($_REQUEST, 'ocms_charset')) {
	    $html = utf8_decode($html);
	}
    }

    /**
     * 	Retourne l'ID courrant de la rubrique
     */
    function getCurId() {

	return $this->rubrique_id;
    }

    /**
     * 	Retourne la traduction dans la langue actuelle, ou une autre langue si absente
     * 	@k = nom du champ sans la langue (rubrique_titre au lieu de rubrique_titre_fr)
     * 	@tab = Tableau avec les differentes valeurs
     * 	@addspan = Par defaut on ajoute <span lang="XX">TRAD</span> pour definir si on change de langue
     */
    function getLgValue($k, $tab, $addspan=true) {

	return getLgValue($k, $tab, $addspan);
    }

    /**
     * 	Retourne la traduction dans une autre langue
     * */
    function getOtherLgValue($k, $tab) {

	return getOtherLgValue($k, $tab);
    }

    /**
     * 	Retourne la langue courrante (ou constante LG)
     */
    function getLg() {

	return $this->lg;
    }

    /**
     * 	Retourne la seconde langue acceptable
     * */
    function getOtherLg() {

	return getOtherLg();
    }

    /**
     * Retourne un array avec la liste des tous les MENU ROOT
     *
     * @return array Liste de tous les menus root
     */
    function getMenus($under=false) {
	$sql = 'SELECT * FROM s_rubrique AS R WHERE 1 ' . sqlMenuOnlyOnline('R');
	if ($under) {
	    $sql .= ' AND fk_rubrique_id = ' . $under;
	}
	$res = GetAll($sql);

	#debug($sql);

	return $res;
    }

    /**
     * 	Plutot que de continuer la génération du site,
     * 	On exporte @contenu avec le content type @ct, dans le charset @charset
     * 	Si on telecharge , avec le nom @nom et @download = true
     * 	Utilisé pour les export CSV, PDF, ...
     *
     *
     * @param string $contenu Code complet à efficher
     * @param string $ct	Content Type
     * @param string $charset	Jeu de caractère
     * @param string $nom		Nom du fichier si donwload = true
     * @param bool $download	Définit si l'on place le contenu comme attachement
     * @param string $sup_headers	Headers supplémentaires
     * @param bool $compress	Compression gzip utilisée ou non
     */
    function doExport($contenu, $ct='text/plain', $charset='utf-8', $nom='export.csv', $download=true, $sup_headers='', $compress=false) {


	ob_end_clean();
	if ($compress) {
	    ob_start("ob_gzhandler");
	}
	header("HTTP/1.1 200 OK");
	header('Content-type: ' . $ct . '; charset=' . $charset);
	header('Cache-Control:');
	header('Pragma:');
	header('Content-Length: ' . mb_strlen($contenu));

	if ($download)
	    header('Content-Disposition: attachment; filename="' . $nom . '"');

	if (strlen($sup_headers)) {

	    $sup_headers = explode("\n", $sup_headers);
	    foreach ($sup_headers as $v)
		header($v);
	}

	print($contenu);

	if ($compress) {

	    ob_end_flush();
	}
	if (!$download && function_exists('saveAgCache'))
	    saveAgCache($contenu);
	die();
    }

}

?>

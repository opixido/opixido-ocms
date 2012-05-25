<?

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

class genAdmin {

    /**
     * GenSecurity
     *
     * @var GenSecurity
     */
    var $gs;

    /**
     * gencontrolpanel
     *
     * @var genControlPanel
     */
    var $control_panel;

    /**
     * Plugins
     *
     * @var array
     */
    var $plugins = array();
    public $real_rub_id = false;
    public $real_fk_rub = false;
    var $row = array();
    public $sa;

    function genAdmin($table = "", $id = 0) {

        /* Always do on loading */

        $GLOBALS['g_admin'] = $this;
        $this->table = $table;
        $this->id = $id;
        $this->sa = new smallAdmin($this);

        $t = getTables();

        if ($table && !in_array($table, $t)) {
            echo '<a href="?">' . ta('this_table_doesnt_exist') . '</a>';
            die();
        }

        $_SESSION['XHRlastCurId'] = akev($_REQUEST, 'curId') ? $_REQUEST['curId'] : akev($_SESSION, 'XHRlastCurId');

        global $gs_obj, $lg;

        $actions = akev($_REQUEST, 'genform_action');
        if ($actions && count($actions) && !isset($_REQUEST['genform_action']['edit'])) {
            $_REQUEST['resume'] = 1;
        }


        $this->gs = &$gs_obj;

        $this->firstId = $id;

        $GLOBALS['rteElements'] = '';
        $GLOBALS['rteIncluded'] = false;
        $GLOBALS['codeFieldPrinted'] = '';

        global $_Gconfig;
        global $gr_on;
        foreach ($_Gconfig['orderedTable'] as $tableO => $champO) {
            $_Gconfig['rowActions'][$tableO]['moveTableRowUp'] = true;
            $_Gconfig['rowActions'][$tableO]['moveTableRowDown'] = true;
            $gr_on['insert'][$tableO][] = 'insertTableRowOrder';
            $gr_on['beforeDelete'][$tableO][] = 'deleteTableRowOrder';
        }

        $this->loadPlugins();



        /**
         * Ajout de l'action de géocodage si des champs sont définis
         */
        if (is_array($_Gconfig['mapsFields']) && count($_Gconfig['mapsFields'])) {
            $_Gconfig['globalActions'][] = 'autoGeocodeAllFields';
        }

        $this->checkActions();

        /* Si on clique sur le logo, on revient  vide */

        if ((!count($_POST) && akev($_GET, 'curTable') && !isset($_GET['delId']) && !isset($_GET['goBack']) ) || isset($_GET['home'])) {
            $_SESSION[gfuid()]['levels'] = array();
            $_SESSION[gfuid()]['nbLevels'] = 0;
            $gl = new GenLocks();
            $gl->unsetAllLocks();
        }

        /* On quitte et detruit la session */
        if (akev($_REQUEST, 'destroy') || akev($_REQUEST, 'logout')) {
            $this->destroySession();
        }


        /* On export la table en CSV */
        if (akev($_REQUEST, 'export')) {
            $this->exportCsv();
        }

        $this->doRecord();

        $this->FormToInclude = $this->whichForm();
        if ($_POST && $_REQUEST['curTable'] && $_REQUEST['curId']) {
            $this->genRecord->checkDoOn('recorded');
        }

        /* On vide la table */
        if (akev($_REQUEST, 'vider') && akev($_REQUEST, 'confirm')) {
            $this->emptyTable();
        }

        if (akev($_REQUEST, 'hideRub') || akev($_REQUEST, 'showRub')) {

            $this->handleOpenRubs();
        }








        $this->arboRubs = $this->sa->getRubs();

        $this->insideRealRubId = $this->getRealRubriqueId();

        /* Auto open rubs ! */
        if ($this->isInRubrique()) {
            $id = $this->insideRealRubId;

            while (true) {
                if (!$id) {
                    break;
                } else {

                    $_SESSION['visibleRubs'][$id] = true;
                    $id = $this->reverserubs[$id];
                }
            }
        }
    }

    /**
     * Est dans la rubrique X
     *
     * @return unknown
     */
    function isInRubrique() {
        if ($this->table == 's_rubrique') {
            return true;
        } else if (
                isset($_SESSION[gfuid()]['levels'][1])
                && !empty($_SESSION[gfuid()]['levels'][1]['curTable'])
                && $_SESSION[gfuid()]['levels'][1]['curTable'] == 's_rubrique') {
            return true;
        }

        return false;
    }

    /**
     * LoadPlugins
     *
     * @return unknown
     */
    function LoadPlugins() {

        $plugs = GetPlugins();

        foreach ($plugs as $v) {

            $GLOBALS['gb_obj']->includeFile('admin.php', PLUGINS_FOLDER . '' . $v . '/');

            $adminClassName = $v . 'Admin';
            if (class_exists($adminClassName)) {

                $this->plugins[$v] = new $adminClassName($this);
                if (method_exists($this->plugins[$v], 'ocms_getParams')) {
                    global $_Gconfig;
                    $_Gconfig['pluginsParams'][] = $v;
                }
            }
        }
    }

    public function getRealRubriqueId() {


        if (isset($this->real_rub_id))
            return $this->real_rub_id;
        if (is_array($_SESSION[gfuid()]['levels'])) {
            @reset($_SESSION[gfuid()]['levels']);
            foreach ($_SESSION[gfuid()]['levels'] as $lev) {

                if (akev($lev, 'curTable') == 's_rubrique') {
                    @reset($_SESSION[gfuid()]['levels']);

                    if ($this->rubver[$lev['curId']])
                        return $this->rubver[$lev['curId']];

                    else
                        return $lev['curId'];
                }
            }
            @reset($_SESSION[gfuid()]['levels']);
        }
        return false;
    }

    function gen() {

        global $gb_obj;

        //include(gen_include_path.'/admin_html/inc.header.php');


        $gb_obj->includeFile('inc.header.php', 'admin_html');





        if ($this->isInRubrique()) {
            p('<div id="menug" class="row-fluid">');
            p('<div class="well span3">');
            $this->getArboRubs();
            p('</div>');
            p('<div id="contenu" class="span9">');
        } else {
            p('<div><div id="contenu">');
        }





        $this->GetHeaderTitle();


        if (isset($_REQUEST['include_action'])) {
            p('<div style="border:1px dashed #cc0000;background:lightgray;padding:10px;"><h1>ACTIONS</h1>');
            $GLOBALS['gb_obj']->includeFile($_REQUEST['include_action'], 'include_actions');
            //die();
            p('</div>');
        }



        p('<div id="contenupadd">');

        $this->includeForm();

        p('</div>');

        p('</div></div>');


        $gb_obj->includeFile('inc.footer.php', 'admin_html');
    }

    function GetTools() {

        if ($this->table != 's_rubrique') {
            global $_Gconfig;
            p('<div id="tools" >');

            if ($this->gs->can('add', $this->table)) {
                p('<a class="btn btn-primary btn-large" href="?curTable=' . $this->table . '&amp;curId=new"><img src="' . ADMIN_PICTOS_FOLDER . '' . ADMIN_PICTOS_ARBO_SIZE . '/actions/document-new.png" alt=""  /> ' . t('ajouter_elem') . '</a></div>');
            }

            if (ake($_Gconfig['tableActions'], $this->table)) {



                foreach ($_Gconfig['tableActions'][$this->table] as $action) {

                    if ($this->gs->can($action, $this->table) && $action != akev($_REQUEST, 'tableAction')) {

                        p('<a class="btn" href="?curTable=' . $this->table . '&tableAction=' . $action . '"> <img src="' . ADMIN_PICTOS_FOLDER . '' . ADMIN_PICTOS_ARBO_SIZE . '/actions/' . $action . '.png" alt=""  /> ' . t($action) . '</a>');
                    }
                }
            }

            p('</div>');


            if (!empty($_REQUEST['tableAction']) && in_array($_REQUEST['tableAction'], $_Gconfig['tableActions'][$this->table])) {

                if ($this->gs->can($action, $this->table)) {
                    p('<div class="tableActions well">');

                    p('<h3>' . t('tableAction') . ' ' . t($_REQUEST['tableAction']) . '</h3>');

                    $_REQUEST['tableAction']();

                    p('</div>');
                } else {
                    debug(t('action_non_autorisee'));
                }
            }
        }
    }

    function GetHeader() {





        // p('    </div>');
    }

    /**
     * Verifie si l'on doit executer une action ou non
     * Si oui declenche l'action
     */
    function checkActions() {
        if (ake('genform_relinvaction', $_REQUEST)) {

            foreach ($_REQUEST['genform_relinvaction'] as $action => $v) {
                foreach ($v as $table => $value) {
                    $this->action = new GenAction($action, $table, $value);
                    $this->action->DoIt();
                }
            }
        }

        if (ake('genform_action', $_REQUEST)) {


            while (list($action, ) = each($_REQUEST['genform_action'])) {


                $this->action = new GenAction($action, $this->table, $this->id, $this->row);

                $this->action->DoIt();


                if (isset($_REQUEST['fromList']) && $this->action->canReturnToList()) {
                    $_REQUEST['curId'] = '';
                    $this->id = '';
                    $_REQUEST['resume'] = '';
                } else
                if ($action != 'edit') {
                    $_REQUEST['resume'] = '1';
                }
            }
        }


        if (!empty($_REQUEST['mass_action'])) {
            foreach ($_REQUEST['massiveActions'] as $k => $v) {

                $action = new GenAction($_REQUEST['mass_action'], $this->table, $v);
                $action->DoIt();
            }
        }
    }

    function doRecord() {

        if (akev($_REQUEST, 'genform__add_sub_table') && akev($_REQUEST, 'genform__add_sub_id')) {
            $_SESSION[gfuid()]['genform__add_sub_table'] = $_REQUEST['genform__add_sub_table'];
            $_SESSION[gfuid()]['genform__add_sub_id'] = $_REQUEST['genform__add_sub_id'];
        }


        $this->genRecord = new genRecord($this->table, $this->id, true);

        $this->id = $this->genRecord->doRecord();
    }

    function getArboRubs() {

        p('<div id="arbo_rubs" >');
        $this->sa->getArboActions();
        p('<div id="arbo">');
        $this->sa->recurserub('NULL', 0, "1");
        p('</div>');
        p('</div>');
    }

    function getLeftMenu() {

        global $adminMenus;
        if (!is_array($adminMenus)) {
            derror('Pas de menus definis');
            return;
        }
        while (list($k, $v) = each($adminMenus)) {
            p('<h1>' . t('menu_' . $k) . '</h1>');
            p('<ul class="text1">');


            foreach ($v as $m) {
                if ($this->gs->can('view', $m)) {
                    p('<li>');
                    $cl = ($this->table == $m) ? "class='badge'" : "";
                    p('<a ' . $cl . ' href="index.php?curTable=' . $m . '&"><img src="' . ADMIN_PICTOS_FOLDER . '' . ADMIN_PICTOS_ARBO_SIZE . '/actions/document-open.png" alt="-" /> ' . t($m) . '</a>');
                    p('</li>');
                }
            }
            p('</ul>');
            p('<br/>');
        }
    }

    function genPlugins() {

        if (!is_array($this->plugins)) {
            return false;
        }
        foreach ($this->plugins as $plugin) {
            if (method_exists($plugin, 'gen')) {
                $plugin->gen();
            }
        }
    }

    function includeForm() {

        global $form;
        $toInclude = $this->FormToInclude;


        $this->genPlugins();

        if (@method_exists($this->action->obj, 'gen')) {
            $this->action->obj->gen();
        }

        switch ($toInclude) {

            case "home":

                $gl = new GenLocks();
                $gl->unsetAllLocks();

                $GLOBALS['inScreen'] = 'home';

                /* global $gb_obj;
                  $gb_obj->includeFile('home.php','admin_html');
                 */
                $this->control_panel = new genControlPanel();


                // $this->plugins['stats']->genAfter();
                foreach ($this->plugins as $k => $v) {

                    if (is_object($v) && method_exists($v, 'genAfter')) {
                        $v->genAfter();
                    }
                }

                p($this->control_panel->gen());


                break;
            case "search":


                $gl = new GenLocks();
                $gl->unsetAllLocks();



                $GLOBALS['inScreen'] = 'search';

                $this->GetTools();


                $search = new GenSearchv2($this->table);

                $search->printAll();



                break;

            case 'searchv2':


                $gl = new GenLocks();
                $gl->unsetAllLocks();



                $GLOBALS['inScreen'] = 'searchv2';

                $this->GetTools();


                $search = new GenSearchV2($this->table);

                $search->printAll();



                break;

            case "form":

                $GLOBALS['inScreen'] = 'form';

                $gl = new GenLocks();
                $tl = $gl->getLock($this->table, $this->id);

                if (is_array($tl)) {
                    dinfo(t('erreur_lock_existe'));


                    global $editMode;
                    $editMode = true;

                    $form = new GenForm($this->table, "", $this->id, "");
                    $form->editMode = true;
                    //debug($tl);
                } else {

                }


                $form = new GenForm($this->table, "", $this->id, "");

                $form->genHeader();

                $form->genActions();

                $form->genPages();

                $form->genFooter();


                break;

            case "resume":

                $gl = new GenLocks();

                $gl->unsetAllLocks();

                $GLOBALS['inScreen'] = 'resume';

                p('<div id="resume">');


                $form = new GenForm($this->table, "", $this->id, "");

                $form->separator = '<br/>';
                global $editMode;



                $editMode = 1;

                $form->editMode = 1;

                p('<div class="row-fluid">');

                p('<div class="span9">');
                //$form->genHeader();

                $form->genPages();

                $this->showLog();

                // $form->genFooter();
                p('</div>');

                p('<div class="span3">');
                $form->genActions();
                p('</div>');

                p('</div>');

                break;

            case "arbo":

                $GLOBALS['inScreen'] = 'arbo';

                $arbo = new genArbo(akev($_REQUEST, 'rubId'));
                $arbo->gen();

                break;
        }
        /* if($toInclude && is_file($toInclude))
          include ( $toInclude );
          else
          debug('Page en construction');
         */
    }

    function showLog() {
        //if($this->table == 's_rubrique') {
        $sql = 'SELECT * FROM s_log_action
				 LEFT JOIN s_admin AS A ON fk_admin_id = A.admin_id
				 WHERE log_action_fk_id = "' . $this->id . '"
					AND log_action_table = "' . $this->table . '"
				    ORDER BY log_action_time DESC, log_action_id DESC LIMIT 0,30';

        $res = GetAll($sql);

        p('<table id="table_log_action" summary="" class="table table-striped table-bordered table-condensed">');
        p('<caption>' . t('table_log_action') . '</caption>');
        $k = 0;
        foreach ($res as $row) {

            //if($lastAction !=  $row['log_action_action'] || $lastAdmin != $row['admin_id']) {
            $k++;
            $lastAction = $row['log_action_action'];
            $lastAdmin = $row['admin_id'];
            p('<tr ' . ($k % 2 ? '' : 'class="odd"') . '><td>' . $row['admin_nom'] . '</td><td>' . $row['admin_email'] . '</td><td>' . t('action_' . $row['log_action_action']) . '</td><td>' . niceDateTime($row['log_action_time']) . '</td></tr>');
            //}
        }
        p('</table>');

        //}
    }

    public static function handleOpenRubs() {

        /* AFFICHER MASQUER DES RUBRIQUES DU MENU */


        $visibleRubs = akev($_SESSION, 'visibleRubs');


        if (!empty($_REQUEST['showRub'])) {
            $visibleRubs[$_REQUEST['showRub']] = true;
        } else if (akev($_REQUEST, 'hideRub') > 0) {
            unset($visibleRubs[$_REQUEST['hideRub']]);
        }


        $_SESSION['visibleRubs'] = $visibleRubs;
    }

    function destroySession() {

        /* On vire tout, c'est pratique pour les tests */
        $this->gs->clearAuth();
        session_destroy();
        $_SESSION = "";
        $_SESSION = array();

        header('location:./');
    }

    function exportCsv() {

        /* Export la table courante en CSV */

        $sql = 'SELECT * FROM ' . $this->table;
        $res = GetAll($sql);

        header('Content-Disposition: attachment; filename="' . $this->table . '.csv"');
        header("Content-Type: text/comma-separated-values");


        foreach ($res as $row) {
            $i = 0;

            $f = new GenForm($this->table, 'post', 0, $row);
            $f->editMode = true;
            $f->onlyData = true;
            while (list($k, ) = each($row)) {
                if ($i % 2)
                    print($this->csvenc($f->gen($k)) . ';');
                $i++;
            }

            print("\n");
        }

        die();
    }

    function csvenc($str) {
        /* pour ï¿½iter les problï¿½es en CSV on rï¿½ncode les retours ï¿½la ligne et les ";" */

        return str_replace(array(";", "\n", "\r"), array(":", " ", " "), $str);
    }

    function addMessage($str) {
        /* Pour l'information */
        $this->messages[] = $str;
    }

    function emptyTable() {
        $sql = 'TRUNCATE TABLE ' . $this->table;
        DoSql($sql);

        $this->addMessage(t('table_videe') . " " . t($this->table));
    }

    function GetRecordTitle($table, $id, $sep = " ", $pk = "") {
        /*
         * Formate proprement le titre d'une table
         *
         * */

        if (!$id || $id == 'new') {
            return 'Nouveau ' . t($table);
        }

        global $tabForms;
        if (strlen($pk) == 0) {
            $pk = GetPrimaryKey($table);
        }
        $sql = "SELECT " . GetTitleFromTable($table, ' , ') . " FROM " . $table . " WHERE " . $pk . " = '" . $id . "'";
        $row = GetSingle($sql);

        $r = "";
        /*
          if(is_array($row)) {
          $i=0;
          foreach($row as $v) {
          $i++;
          if($i%2)
          $r .= $v.$sep;
          }

          $r= substr($r,0,-1*(int)strlen($sep));
          }
          return $r;
         */

        return GetTitleFromRow($table, $row);
    }

    function GetHeaderTitle() {

        if (empty($_REQUEST['curTable'])) {
            return '';
        }

        p('<div id="titre" class="well">');



        $urlOnline = getObjUrl();
        global $tabForms;
        if ($urlOnline) {
            p('<a class="btn" style="float:right" href="' . $urlOnline . '" target="_blank"><img src="' . ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_FORM_SIZE . '/actions/document-properties.png" alt=' . alt(t('voir_enligne')) . ' /></a>');
        }

        /*         * k
         * Nouveau et rechercher
         */
        if ($this->id && $this->table != 's_rubrique' && $_SESSION[gfuid()]['levels'][1]['curTable'] != 's_rubrique') {

            p('<div id="toolsright" >

                    <form class="form-inline">

                            <input type="hidden" name="curTable" value=' . alt($_REQUEST['curTable']) . ' />
                            <input type="hidden" name="doSimpleSearch" value="1" />
                           <div class="control-group">
                           <div class="controls">
                           <div class="input-append">
                            <a class="btn  btn-mini" title=' . alt(t('add_another') . ' ' . t($_REQUEST['curTable'])) . '
                                    href="?curTable=' . $this->table . '&curId=new">
                                    <img src="' . ADMIN_PICTOS_FOLDER . '' . ADMIN_PICTOS_FORM_SIZE . '/actions/document-new.png"
                                            alt=' . alt(t('add_another')) . '  /></a>
                            <input type="text" class="span2" name="searchTxt" placeholder=' . alt(t('search')) . ' title=' . alt(t('search') . ' ' . t($_REQUEST['curTable'])) . ' /><button class="btn"><img src="' . ADMIN_PICTOS_FOLDER . '' . ADMIN_PICTOS_ARBO_SIZE . '/actions/system-search.png" alt=' . alt(t('search')) . ' /></button>
                        </div>
                        </div>
                        </div>
                    </form>
            </div>');
        }

        //debug($_SESSION[gfuid()]);

        if ($_SESSION[gfuid()]['nbLevels'] > 0) {
            $table = !empty($_SESSION[gfuid()]['levels'][1]['curTable']) ? $_SESSION[gfuid()]['levels'][1]['curTable'] : $_REQUEST['curTable'];
            $root_id = !empty($_SESSION[gfuid()]['levels'][1]['curId']) ? $_SESSION[gfuid()]['levels'][1]['curId'] : $_REQUEST['curId'];
        } else if (isset($_REQUEST['curTable'])) {
            $table = $_REQUEST['curTable'];
            $root_id = $_REQUEST['curId'];
        } else
            $table = false;




        /**
         * Retour moteur de recherche
         */
        if ($table) {
            $src = !empty($tabForms[$table]['picto']) ? str_replace(ADMIN_PICTOS_BIG_SIZE, ADMIN_PICTOS_FORM_SIZE, $tabForms[$table]['picto']) : t('src_desktop');
            $src = getPicto($table, ADMIN_PICTOS_FORM_SIZE);
            p('<a class="btn btn-mini" title=' . alt(t('recherche') . ' ' . t($table)) . ' href="?curTable=' . $table . '&amp" ><img class="inputimage" src="' . $src . '"  alt="" /></a>');
        }


        if (!$_REQUEST['curId']) {
            p('<h1 style="display:inline;font-size:130%;font-weight:normal;padding:0;margin:0;">' . ta($_REQUEST['curTable']) . '</h1>');
        }
        if ($root_id && $root_id != 'new') {
            p('<a class="btn btn-mini" title=' . alt(t('road_fiche_resume') . ' : ' . limitWords(strip_tags($this->GetRecordTitle($table, $root_id)), 10)) . ' href="?curTable=' . $table . '&amp;curId=' . $root_id . '&resume=1" ><img class="inputimage"  src="' . t('src_view') . '" alt="" /></a>');
        }
        if ($this->id || $this->id == 'new') {

            if (akev($_SESSION[gfuid()], 'nbLevels') > 0) {

                for ($p = 1; $p <= $_SESSION[gfuid()]['nbLevels'] + 1; $p++) {
                    $v = $_SESSION[gfuid()]['levels'][$p];
                    if (isset($v['curTable'])) {
                        $src = getPicto($v['curTable'], ADMIN_PICTOS_FORM_SIZE);
                        p('<a class="btn btn-mini" href="?gfuid=' . gfuid() . '&backToLevel=' . $p . '"><img class="inputimage" src="' . $src . '" alt="" />' . limitWords(strip_tags($this->GetRecordTitle($v["curTable"], $v["curId"], " ", $v["curTableKey"])), 15) . " [" . $v["curId"] . "] </a> ");
                    }
                }
                $src = getPicto($this->table, ADMIN_PICTOS_FORM_SIZE);
                p(' <span class="well"><img class="inputimage" src="' . $src . '" alt="" /> ' . limitWords(strip_tags($this->GetRecordTitle($this->table, $this->id)), 10) . '</span> ');
                //p('<a href="?' . time() . '" ><img class="inputimage" src="' . t('src_back') . '" alt="Retour" /></a> ');
//                while (list($k, $v) = each($_SESSION[gfuid()]['levels'])) {
//                    if (isset($v['curTable'])) {
//                        p('<span class="titreListe">' . limitWords(strip_tags($this->GetRecordTitle($v["curTable"], $v["curId"], " ", $v["curTableKey"])), 15) . " [" . $v["curId"] . "] </span> &raquo;");
//                    }
//                }
            } else {

                if ($this->FormToInclude == 'resume') {
                    //p('<a href="?curTable=' . $_REQUEST['curTable'] . '" style="margin:0;padding:0"><img style="vertical-align:middle;margin:0;" src="' . t('src_first') . '" alt="Retour" /></a>');
                } else if ($this->id == 'new') {
                    // p('<a href="?curTable=' . $_REQUEST['curTable'] . '" style="margin:0;padding:0"><img style="vertical-align:middle;margin:0;" src="' . t('src_first') . '" alt="Retour" /></a>');
                } else {
                    $src = getPicto($table, ADMIN_PICTOS_FORM_SIZE);
                    p('<span class="well"><img class="inputimage" src="' . $src . '" alt="" /> ' . limitWords(strip_tags($this->GetRecordTitle($this->table, $this->id, " ")), 15) . "</span>");
                }
            }
        }
        p('</div>');
        if (ake('genform_action', $_REQUEST)) {

            reset($_REQUEST['genform_action']);
            while (list($action, ) = each($_REQUEST['genform_action'])) {

                $this->action = new GenAction($action, $this->table, $this->id, $this->row);
                $this->action->GenIt();
            }
        }
    }

    function whichForm() {

        /* Quel formulaire on inclu */

        global $tabForms, $formsRep, $fieldError, $genMessages;
        $comingBack = 0;


        /**
         * On reste sur le meme formulaire
         * Car il y a un champ mal remplit ou bien on a demander à  rester
         */
        if ((is_array($fieldError) && empty($_REQUEST['newTable'])) || akev($_POST, 'genform_stay')) {
            $gl = new GenLocks();

            $gl->setLock($this->table, $this->id);

            return ("form");
        } else {
            /**
             *  Si on vient de cliquer sur un bouton Ajouter depuis un autre formulaire
             * */
            if (isset($_REQUEST['newTable'])) {
                /*
                  On stock les infos actuelles dans la session
                 */

                $_SESSION[gfuid()]['nbLevels']++;
                $_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]['curTable'] = $_REQUEST['curTable'];
                $_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]['curTableKey'] = $_REQUEST['curTableKey'];
                $_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]['curId'] = $_REQUEST['curId'];
                $_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]['fieldToUpdate'] = akev($_REQUEST, 'fieldToUpdate');
                $_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]['curPage'] = $_REQUEST['curPage'];
                $_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]['tableToUpdate'] = akev($_REQUEST, 'tableToUpdate');
                $_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]['insertOtherField'] = $_REQUEST['insertOtherField'];



                /*
                  Et pour aller sur le nouveau formulaire on fait ca
                 */
                $_REQUEST['curTable'] = $_REQUEST['newTable'];
                $_REQUEST['curId'] = $_REQUEST['newId'] ? $_REQUEST['newId'] : "";
                $_REQUEST['curTableKey'] = "";
                $_REQUEST['curPage'] = "";
                $_REQUEST['newTable'] = "";
                $_REQUEST['tableToUpdate'] = "";
                $_REQUEST['insertOtherField'] = "";
            }
            /**
             *  Sinon, si on revient d'un formulaire vers un autre
             * */ else if (isset($_SESSION[gfuid()]['nbLevels']) && $_SESSION[gfuid()]['nbLevels'] > 0) {
                /*
                  On recupere nos variables de sessions
                 */
                $beforeRequest = $_REQUEST;

                $_REQUEST['curTable'] = $_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]['curTable'];
                $_REQUEST['curTableKey'] = $_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]['curTableKey'];
                $_REQUEST['curPage'] = $_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]['curPage'];
                $_REQUEST['tableToUpdate'] = $_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]['tableToUpdate'];
                $_REQUEST['insertOtherField'] = $_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]['insertOtherField'];

                $sql = "";

                /*
                  On modifie la Table de relation
                 */
                if ($_REQUEST['tableToUpdate'] && $_REQUEST['curId'] && $_REQUEST['curId'] != 'new') {
                    $sql = "INSERT INTO " . $_REQUEST['tableToUpdate'] .
                            " ( fk_" . $_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]['fieldToUpdate'] . " , fk_" .
                            $_REQUEST['curTableKey'] . " )  VALUES  ( " .
                            $_REQUEST['curId'] . " , " .
                            $_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]['curId'] . " ) ";
                } else if ($_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]['fieldToUpdate'] &&
                        $_REQUEST['curId'] && $_REQUEST['curId'] != "new") {
                    /*
                      On modifie la Clef externe simple
                     */
                    $sql = "UPDATE " . $_REQUEST['curTable'] . " SET " .
                            $_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]['fieldToUpdate'] . " = " .
                            $_REQUEST['curId'] . " WHERE " . $_REQUEST['curTableKey'] . " = '" .
                            $_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]['curId'] . "'";


                    /* On reorganise / reordone */
                    $ord = new GenOrder($_REQUEST['curTable'], $_REQUEST['curId']);
                    $ord->OrderAfterInsertLastAtBottom();
                }

                /*
                  Si on a pas annulé on fait vraiment cette requete
                 */

                if (!isset($_POST['genform_cancel']) && $sql) {
                    DoSql($sql);
                }

                $gl = new GenLocks();

                $gl->unsetLock($this->table, $this->id);


                if (!isset($_POST['nextPage']) && !isset($_POST['prevPage'])) {
                    $_REQUEST['curId'] = $_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]['curId'];

                    $_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']] = "";
                    $comingBack = 1;

                    $_SESSION[gfuid()]['nbLevels']--;
                    $_REQUEST['curTable'] = $_REQUEST['curTable'];
                } else {
                    $_REQUEST = $beforeRequest;
                }
            }


            global $_Gconfig;
            /**
             *  Si on finit apres avoir soumis le formulaire
             * */
            if (( ake('genform_cancel', $_POST)
                    || ake('genform_ok', $_POST)
                    || ake('genform_ok_x', $_POST)
                    || ake('genform_cancel', $_POST)
                    || isset($_REQUEST['resume']) ) && !$comingBack) {


                /**
                 * Si c'est une table en "updateAfterInsert" et qu'on vient de la créer, on revient dessus
                 */
                if (in_array($this->table, $_Gconfig['updateAfterInsert']) && $this->firstId == 'new') {

                    $gl = new GenLocks();

                    $gl->setLock($this->table, $this->id);

                    return('form');
                }
                /**
                 * Sinon on retourne au résumé si on a fait OK ou CANCEL ou qu'on a demandé le résumé
                 */
                if ((ake('genform_ok', $_POST)
                        || ake('genform_ok_x', $_POST)
                        || ake('genform_cancel', $_POST)
                        || ake('genform_cancel_x', $_POST)
                        || $_REQUEST['resume']
                )) {
                    if (!isset($_REQUEST['resume'])) {
                        //$this->genRecord->checkDoOn('saved');
                    }
                    return('resume');
                } else {
                    if (in_array($_REQUEST['curTable'], $_Gconfig['multiVersionTable'])) {
                        return ("searchv2");
                    } else {
                        return ("search");
                    }
                }
            } else if (isset($_REQUEST['curTable']) || isset($_POST['prevPage']) || isset($_POST['nextPage'])) {

                /**
                 * Si on inclu vraiment le formulaire
                 *
                 * */
                $_REQUEST['curPage'] = isset($_REQUEST['curPage']) ? $_REQUEST['curPage'] : '0';

                /* Enfin on retourne le formulaire */
                if (isset($_REQUEST['curId'])) {
                    $this->table = $_REQUEST['curTable'];
                    $this->id = $_REQUEST['curId'];

                    $gl = new GenLocks();

                    $gl->setLock($this->table, $this->id);

                    return "form";
                }

                //return ($formsRep.$tabForms[$_REQUEST['curTable']]['pages'][$_REQUEST['curPage']]);
                else if ($_REQUEST['curTable']) {
                    if (in_array($_REQUEST['curTable'], $_Gconfig['multiVersionTable'])) {
                        return ("searchv2");
                    } else {
                        return ("search");
                    }
                }
            } else if (isset($_REQUEST['arbo'])) {

                return ("arbo");
            } else {
                /* Sinon  quoi ? */
                $_REQUEST['curTable'] = "";
                $_REQUEST['curId'] = "";

                return ("home");
            }
        }
    }

}

/**
 * Retourne l'identifiant unique pour l'action en cours
 * @return string
 */
function gfuid() {
    if (akev($_REQUEST, 'gfuid')) {
        $uid = $_REQUEST['gfuid'];
    } else {
        $uid = $_REQUEST['gfuid'] = "guid_" . str_replace('.', '', getmicrotime());
    }
    if (!ake($_SESSION, $uid)) {
        $_SESSION[$uid] = array();
    }
    return $uid;
}

class smallAdmin {

    public $parent;

    function __construct($parent) {
        $this->parent = $parent;


        if (( (isset($_GET['bas_1'])) || (isset($_GET['haut_1'])) ) && (isset($_GET['rubId']))) {
            $this->updateRubriqueOrder();
        }
    }

    /**
     * Toute l'arbrescence des rubriques sur la gauche
     *
     * @param unknown_type $id
     * @param unknown_type $nivv
     * @param unknown_type $dolinka
     */
    function recurserub($id = 0, $nivv = 0, $dolinka = 0) {


        $lighta = "<span style='color:#999'>";
        $lightb = "</span>";

        $pictoAr['siteroot'] = ADMIN_PICTOS_FOLDER . '' . ADMIN_PICTOS_ARBO_SIZE . '/apps/system-software-update.png';
        ;
        $pictoAr['menuroot'] = ADMIN_PICTOS_FOLDER . '' . ADMIN_PICTOS_ARBO_SIZE . '/apps/preferences-system-windows.png';
        $pictoAr['page'] = ADMIN_PICTOS_FOLDER . '' . ADMIN_PICTOS_ARBO_SIZE . '/actions/document-template.png';
        $pictoAr['link'] = ADMIN_PICTOS_FOLDER . '' . ADMIN_PICTOS_ARBO_SIZE . '/actions/edit-redo.png';
        $pictoAr['folder'] = ADMIN_PICTOS_FOLDER . '' . ADMIN_PICTOS_ARBO_SIZE . '/places/folder.png';
        $ht = '';
        $bs = '';
        $ajout = '';
        $souldBeOrder = 1;

        /**
         * Selectionne toutes les sous rubriques
         */
        if ($id == 0 && isset($this->parent->gs->myroles['s_rubrique']) && is_array($this->parent->gs->myroles['s_rubrique']['rows'])) {
            /**
             * Pour les utilisateurs simples
             * avec des accès sous-rubriques
             */
            $q = "SELECT
						G.*,
	        			R1.* ,
	        			R2.rubrique_ordre as r2_ordre,
	        			R2.rubrique_etat AS r2_etat
	        			FROM  s_rubrique as R2 , s_rubrique AS R1 LEFT JOIN s_gabarit AS G ON G.gabarit_id = R1.fk_gabarit_id
	        			WHERE R1.rubrique_id IN(" . implode(',', $this->parent->gs->myroles['s_rubrique']['rows']) . ") " . sqlRubriqueVersions('R2.rubrique_id', 'R1') . "
	        			ORDER BY r2_ordre ASC ";
        } else {
            /**
             * Pour les super admins
             */
            $q = "SELECT
	        			G.*,
	        			R1.* ,
	        			R2.rubrique_ordre as r2_ordre,
	        			R2.rubrique_etat AS r2_etat
	        			FROM  s_rubrique as R2 , s_rubrique AS R1 LEFT JOIN s_gabarit AS G ON G.gabarit_id = R1.fk_gabarit_id
	        			WHERE R1.fk_rubrique_id " . sqlParam($id) . " " . sqlRubriqueVersions('R2.rubrique_id', 'R1') . "
	        			ORDER BY r2_ordre ASC ";
        }

        $query = GetAll($q);


        $tot = count($query) - 1;

        foreach ($query as $knb => $aff) {

            $fakeRubs = false;
            $real_rub = $aff['fk_rubrique_version_id'];
            $version_rub = $aff['rubrique_id'];

            /**
             * Si l'utilisateur peut modifier cette rubrique
             */
            if ($this->parent->gs->can('edit', 's_rubrique', $aff)) {

                p('<ul id="arbo_' . $id . '">');

                /**
                 * Titre par défaut si vide
                 */
                if (!strlen($aff['rubrique_titre_' . LG_DEF])) {
                    $aff['rubrique_titre_' . LG_DEF] = "[TITRE VIDE]";
                }


                /**
                 * Si jamais l'ordre n'était pas bon pour une raison X
                 */
                if ($souldBeOrder != $aff["r2_ordre"]) {
                    /**
                     * Si jamais l'ordre est faux, on réordonne
                     */
                    $sql = 'UPDATE s_rubrique SET rubrique_ordre = "' . $souldBeOrder . '" WHERE `rubrique_id` = "' . $real_rub . '"';
                    $res = DoSql($sql);

                    $aff['r2_ordre'] = $souldBeOrder;
                }

                /**
                 * On fait un lien ?
                 *
                 */
                $dolink = true;

                /**
                 * permet de connaitre pour la rubriqque en cours son niveau maximal
                 */

                $cls=$cla=$cl = '';

                $cls = $cla = $cl = '';


                /**
                 * Cette rubrique est-elle sélectionnée ?
                 */
                if ($this->parent->insideRealRubId == $real_rub ||
                        ( $aff['fk_rubrique_version_id'] == akev($_SESSION, 'XHRlastCurId') && !isset($_REQUEST['curId']))
                        || $version_rub == akev($_REQUEST, 'curId')
                ) {

                    $cl = 'class="alert alert-info" style="padding:0;margin:0"';
                    $cla = 'class=""';
                    $cls = 'class=""';
                    $_SESSION['XHRlastCurId'] = $aff['fk_rubrique_version_id'];
                }

                p('<li ' . $cl . '><span ' . $cls . '>');

                /**
                 *  Ancres pour liens directs quand le menu est plus long que la page
                 */
                if ($aff["fk_rubrique_id"] == 0)
                    p('<a name="rub' . $aff["rubrique_id"] . '" />');

                /**
                 * Classe transparente si rubrique masquée
                 */
                $classColor = '';
                $classColor = $aff['r2_etat'] == 'en_ligne' ? '' : ' pasenligne';



                /**
                 * Dossier ouvert / fermé :
                 * plus ou moins
                 */
                $paramShow = isset($_SESSION['visibleRubs'][$real_rub]) ? 'hideRub=' . $real_rub : 'showRub=' . $real_rub;
                $plusmoins = isset($_SESSION['visibleRubs'][$real_rub]) ? '<img src="./img/moins.gif" alt="" />' : '<img src="./img/plus.gif" alt="" />';

                /**
                 * Code JS pour ouverture
                 */
                $xhr = 'onclick="XHR_menuArbo(this.href,this);return false;"';


                $imageToShow = '';

                /**
                 * URL d'accès
                 */
                $url = '?' . $paramShow . '&amp;curTable=s_rubrique';


                $picto = $pictoAr[$aff['rubrique_type']];

                if ($aff['gabarit_classe']) {
                    if ($aff['gabarit_plugin']) {
                        $f = path_concat(PLUGINS_FOLDER, $aff['gabarit_plugin']);
                    } else {
                        $f = 'bdd';
                    }
                    $GLOBALS['gb_obj']->includeFile($aff['gabarit_classe'] . '.php', $f);
                    //debug( $GLOBALS['gb_obj']->includeFile($aff['gabarit_classe'].'.php',$f));
                    //$res = ${$aff['gabarit_classe'].'::getPicto'}();
                    //debug($aff['gabarit_classe']);
                    //$aaaa = eval(''.$aff['gabarit_classe'].'::getPicto();');
                    if (method_exists($aff['gabarit_classe'], 'ocms_getPicto')) {
                        //	            		debug('PICTO');
                        $picto = call_user_func(array($aff['gabarit_classe'], 'ocms_getPicto'), $aff);
                    }

                    if (method_exists($aff['gabarit_classe'], 'ocms_getSubRubs')) {
                        $subRubs = call_user_func(array($aff['gabarit_classe'], 'ocms_getSubRubs'), $aff);
                        $this->parent->arboRubs[$real_rub] = $subRubs;
                        $fakeRubs = true;
                    }
                }



                if (isset($this->parent->arboRubs[$real_rub]) && count($this->parent->arboRubs[$real_rub]) > 0) {
                    p('<a class="plusmoins ' . $classColor . '"
							' . $xhr . ' href="' . $url . '"
							>
							' . $plusmoins . '<img src="' . $picto . '" alt="" /></a>');
                } else {
                    p('<a class="plusmoins ' . $classColor . '"
							>
							<img src="./img/pixel.gif" width="16" height="16" alt="" /><img src="' . $picto . '" alt="" /></a>');
                }
                /**
                 * Sommes nous en fin de rubrique ?

                  $m = "SELECT max(rubrique_ordre) as maxi FROM s_rubrique WHERE fk_rubrique_id ".sqlParam($id).' '.sqlRubriqueOnlyReal();
                  $max = GetSingle($m);
                  $maxxi = $max["maxi"];
                 */
                /**
                 * Si on a des sous-rubriques on affiche le plus/moins
                 * Sinon ... non
                 */
                if ($this->parent->insideRealRubId == $aff['fk_rubrique_version_id'] ||
                        ( $aff['fk_rubrique_version_id'] == $_SESSION['XHRlastCurId'] && !isset($_REQUEST['curId']))) {


                    if ($knb == 0) {
                        p('<script type="text/javascript">$("#goHautLink").attr("href","#").addClass("disabled");</script>');
                    }

                    if ($knb == $tot) {
                        p('<script type="text/javascript">$("#goBasLink").attr("href","#").addClass("disabled");</script>');
                    }

                    if ($fakeRubs) {
                        p('<script type="text/javascript">$("#addSubLink").attr("href","#").addClass("disabled");</script>');
                    }
                }


                /**
                 * Lien texte
                 */
                $linka = '<a ' . $cla . ' href="index.php?curTable=s_rubrique&amp;showRub=' . $real_rub . '&amp;curId=' . $version_rub . '&amp;resume=1"
			 onmouseover="swapactions(\'imm_' . $real_rub . '\',this)">';


                $link = (($aff['rubrique_titre_' . LG_DEF]));
                $link = getLgValue('rubrique_titre', $aff);
                $linkb = '</a>';


                if ($dolink)
                    echo $linka . $link . $linkb . '<span class="img_move" id="imm_' . $real_rub . '">' . $ht . $bs . " " . $ajout . "</span>";
                else
                    echo $lighta . $link . $lightb;

                p('</span>');

                p('<br/>');


                if (isset($_SESSION['visibleRubs'][$real_rub])) {
                    /**
                     * On parcourt en dessous
                     */
                    if ($fakeRubs && $subRubs) {
                        p('<ul class="fakeSubs">');
                        foreach ($subRubs as $v) {
                            p('<li ><a onclick="return doblank(this)" href="' . getUrlFromId($real_rub, LG, array($v['PARAM'] => $v['VALUE'])) . '"  >' . $v['NAME'] . '</a></li>');
                        }
                        p('</ul>');
                    } else {
                        $this->recurserub($real_rub, $nivv + 1, $dolink);
                    }
                }

                $nextlink = 0;

                p('</ul>');
            } else {
                /**
                 * On avait pas accès à cette rubrique,
                 * On parcourt en dessous voir si on a accès
                 */
                $this->recurserub($real_rub, $nivv + 1, $dolink);
            }

            $souldBeOrder++;
        }
    }

    public function getRubs() {

        $sql = 'Select rubrique_id,fk_rubrique_id,fk_rubrique_version_id FROM s_rubrique';
        $res = GetAll($sql);
        $rubs = array();
        $this->parent->rubver = array();
        $this->parent->reverserubs = array();
        foreach ($res as $row) {
            $rubs[$row['fk_rubrique_id']][] = $row['rubrique_id'];
            $this->parent->reverserubs[$row['rubrique_id']] = $row['fk_rubrique_id'];
            $this->parent->rubver[$row['rubrique_id']] = $row['fk_rubrique_version_id'];
            if ($this->parent->table == 's_rubrique' && $this->parent->id == $row['rubrique_id']) {
                $this->parent->real_rub_id = $row['fk_rubrique_version_id'];
                $this->parent->real_fk_rub = $row['fk_rubrique_id'];
            }
        }

        return $rubs;
    }

    function updateRubriqueOrder() {

        $go = new GenOrder('s_rubrique', $_GET['rubId'], $_GET['fkrubId']);

        if (isset($_GET['bas_1'])) {
            $go->getDown();
        }

        if (isset($_GET['haut_1'])) {
            $go->getUp();
        }

        DoSql('UPDATE s_param SET param_valeur = UNIX_TIMESTAMP() WHERE param_id = "date_update_arbo"');
    }

    function getArboActions() {
        p('<div id="arbo_actions" class="btn-group">');

        $ht = '';
        $bs = '';
        $ajout = '';

        if ($this->parent->id) {

            $ht = '<a class="btn btn-mini" id="goHautLink" onclick="XHR_menuArbo(this.href,this);return false;" href="index.php?showRub=' . $this->parent->real_rub_id . '&amp;haut_1=1&amp;curTable=' . $this->parent->table . '&amp;curId=' . $this->parent->id . '&amp;rubId=' . $this->parent->real_rub_id . '&amp;resume=1&amp;fkrubId=' . $this->parent->real_fk_rub . '" title="Monter d\'un niveau"><img src="' . ADMIN_PICTOS_FOLDER . '' . ADMIN_PICTOS_ARBO_SIZE . '/actions/go-up.png" alt="" /> ' . t('monter') . ' </a>';

            $bs = '<a class="btn btn-mini"  id="goBasLink" onclick="XHR_menuArbo(this.href,this);return false;" href="index.php?showRub=' . $this->parent->real_rub_id . '&amp;bas_1=1&amp;curTable=' . $this->parent->table . '&amp;curId=' . $this->parent->id . '&amp;rubId=' . $this->parent->real_rub_id . '&amp;resume=1&amp;fkrubId=' . $this->parent->real_fk_rub . '" title="Descendre d\'un niveau"><img src="' . ADMIN_PICTOS_FOLDER . '' . ADMIN_PICTOS_ARBO_SIZE . '/actions/go-down.png" alt="" /> ' . t('descendre') . '</a>';

            $ajout = '';
//
//
//	    // On autorise jusqu'a X niveau d'arborescence
//
//
//	    $sql = '
//			SELECT * FROM s_rubrique AS R1, s_rubrique AS R2, s_rubrique AS R3 ,  s_rubrique AS R4 ,s_rubrique AS R5,s_rubrique AS R6
//			WHERE R6.rubrique_id = "' . $this->parent->real_rub_id . '"
//			AND R6.fk_rubrique_id = R5.rubrique_id
//			AND R5.fk_rubrique_id = R4.rubrique_id
//			AND R4.fk_rubrique_id = R3.rubrique_id
//			AND R3.fk_rubrique_id = R2.rubrique_id
//			AND R2.fk_rubrique_id = R1.rubrique_id
//			AND R1.fk_rubrique_id IS NULL';
//	    //$res = GetAll($sql);
//	    //if(!count($res) || true) {
//	    if (is_array($this->parent->arboRubs) && $this->parent->real_rub_id) {
//
//		$a = akev($this->parent->arboRubs, $this->parent->real_rub_id);
//		if (!$a) {
//		    $a = array();
//		}
//		$ajout = ' &nbsp; <a id="addSubLink" href="index.php?curTable=s_rubrique&amp;curId=new&amp;genform__add_sub_table=s_rubrique&amp;genform__add_sub_id=' . $this->parent->real_rub_id . '&amp;genform_default__rubrique_ordre=' . ((count($a) / 2) + 1) . '" title="Ajouter une sous rubrique "><img src="' . ADMIN_PICTOS_FOLDER . '' . ADMIN_PICTOS_ARBO_SIZE . '/actions/document-new.png" alt="" /> ' . t('ajout_sub_rub') . '</a>';
//	    }
//
            $ajout = '<a id="addSubLink" class="btn btn-mini"  href="index.php?curTable=s_rubrique&amp;curId=new&amp;genform__add_sub_table=s_rubrique&amp;genform__add_sub_id=' . $this->parent->real_rub_id . '" title="Ajouter une sous rubrique " ><img src="' . ADMIN_PICTOS_FOLDER . '' . ADMIN_PICTOS_ARBO_SIZE . '/actions/document-new.png" alt="" /> ' . t('ajout_sub_rub') . '</a>';
        } else {
            $ht = t('select_rub_below');
        }

        /* construction simplifiée de l'aroborescence  */
        if (isset($_REQUEST['curId']) && $_REQUEST['curId'] != 'new')
            $arbo = '<a class="btn btn-mini"  href="index.php?arbo=1&amp;rubId=' . $this->parent->id . '&amp;fkrubId=' . $this->parent->real_fk_rub . '" title="' . t('arborescence') . '"><img src="' . ADMIN_PICTOS_FOLDER . '' . ADMIN_PICTOS_ARBO_SIZE . '/actions/arbo.png" alt="" height="16" /></a>';
        else
            $arbo = '';

        p($ht . $bs . $ajout . $arbo . '</div><br/>');
    }

}

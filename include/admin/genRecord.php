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
/*
 *
 *
 * Classe pour gerer les inscriptions dans les tables
 *
 */

class genRecord
{

    /**
     * On force l'autorisation de suppression ?
     * @var bool
     */
    public $forceDeletionPrivilege = false;
    public $deleted = false;
    public $id;
    public $table;
    public $row;

    function __construct($table, $id, $fromGenAdmin = 0)
    {
        $this->JustInserted = false;
        $this->table = $table;
        $this->id = $id;
        $this->$fromGenAdmin = $fromGenAdmin;
        global $gs_obj;

        $this->gs = &$gs_obj;

        $this->pk = $_REQUEST['curTableKey'] = GetPrimaryKey($this->table);
    }

    function doRecord()
    {
        /**
         * Insert / Delete actions
         */
        /*
          Si on vient d'un formulaire ou qu'on veut supprimer quelquechose
          On doit aussi avoir une table, et ne pas avoir annuler
         */


        $fk_id = false;
        if ((ake('genform_fromForm', $_POST) || isset($_REQUEST['delId'])) && $this->table && !ake('genform_cancel', $_POST) && !ake('genform_cancel_x', $_POST)) {
            /*
              On  a pas encore d'identifiant
             */
            if ($this->id == "new") {
                /*  $sql ='SELECT '.$_REQUEST['curTableKey'].' FROM '.$this->table.' LIMIT 0,1';
                  $res = mquery($sql);
                 */

                $this->JustInserted = true;
                $chp = '';
                if (isset($_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]) && isset($_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]['insertOtherField']) || isset($_SESSION[gfuid()]['genform__add_sub_table'])) {
                    global $relinv;
                    reset($relinv);

                    $otherTable = !empty($_SESSION[gfuid()]['genform__add_sub_table']) ? $_SESSION[gfuid()]['genform__add_sub_table'] : $_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]['curTable'];
                    $fk_id = !empty($_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]['curId']) ?
                        $fk_id = $_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]['curId'] :
                        $_SESSION[gfuid()]['genform__add_sub_id'];


                    if (!empty($relinv[$otherTable])) {
                        foreach ($relinv[$otherTable] as $v) {
                            if ($v[0] == $this->table) {
                                $chp = $v[1];
                            }
                        }
                    }
                    if (!empty($_SESSION[gfuid()]['newTableFk'])) {
                        $chp = $_SESSION[gfuid()]['newTableFk'];
                        $_SESSION[gfuid()]['newTableFk'] = '';
                    }
                }

                if (!$this->gs->can('add', $this->table, '', $this->id, $chp, $fk_id)) {
                    $this->gs->showError();
                    die();
                }

                /* Est-ce que cette table est en auto-increment ? */
                $auto = false;
                $tableInfo = MetaColumns($this->table);

                if ($tableInfo[strtoupper($_REQUEST['curTableKey'])]->auto_increment > 0) {
                    $auto = true;
                }


                /* On fait confiance a l'auto_increment */
                if ($auto || $_REQUEST['genform_' . $_REQUEST['curTableKey']] != '') {
                    if (empty($_REQUEST['genform_' . $_REQUEST['curTableKey']])) {
                        $_REQUEST['genform_' . $_REQUEST['curTableKey']] = '';
                    }
                    $sql = "INSERT INTO " . $this->table . " (" . $_REQUEST['curTableKey'] . ") VALUES ('" . $_REQUEST['genform_' . $_REQUEST['curTableKey']] . "')";


                    $oldId = InsertId();
                    $res = DoSql($sql);
                    if ($res) {
                        $iid = $_REQUEST['genform_' . $_REQUEST['curTableKey']] ? $_REQUEST['genform_' . $_REQUEST['curTableKey']] : InsertId();
                    }

                    $this->id = $iid;
                } else {
                    /* Sinon on se base sur le microtime pour generer un id (bah oui pkoi pas ?) */
                    $nbWhile = 0;
                    while (true) {
                        if ($nbWhile > 100)
                            trigger_error("Plus assez de place dans la table, et pas auto_increment", E_USER_ERROR);
                        $nbWhile++;
                        $this->id = str_replace(".", "", getmicrotime());
                        $sql = "INSERT INTO " . $this->table . " (" . $_REQUEST['curTableKey'] . ") VALUES ('" . $this->id . "')";
                        if (DoSql($sql))
                            break;
                    }
                }

                /**
                 * Si on est dans une relOne on ajoute l'enregistrement
                 * dans l'autre table
                 */
                if (!empty($_REQUEST['relOne']) && $_SESSION[gfuid()]['nbLevels'] == 0) {
                    insertEmptyRecord($_REQUEST['relOne'], $this->id);
                }

                $_GET['curId'] = $_POST['curId'] = $_REQUEST['curId'] = $this->id;

                if (!empty($_SESSION[gfuid()]['sqlWaitingForInsert'])) {
                    foreach ($_SESSION[gfuid()]['sqlWaitingForInsert'] as $v) {
                        doSql(str_replace('[INSERTID]', $this->id, $v));
                    }
                }
                $_SESSION[gfuid()]['sqlWaitingForInsert'] = array();

                /* Si on vient de rajouter un element qui pointe vers le nbLevel precedent */


                if (!empty($otherTable) && $fk_id && $chp) {
                    if (!$this->gs->can('edit', $this->table, '', $this->id, $chp, $fk_id) && !$this->JustInserted) {
                        $this->gs->showError();
                        die();
                    }

                    $query = ' UPDATE ' . $this->table . ' SET ' . $chp . ' =  "' . $fk_id . '" WHERE ' . $this->pk . ' = ' . sql($this->id);
                    DoSql($query);

                    $_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]['insertOtherField'] = "";
                    $_SESSION[gfuid()]['genform__add_sub_id'] = $_SESSION[gfuid()]['genform__add_sub_table'] = "";
                }

                global $genMessages;

                $genMessages->add(t($this->table) . ' ' . t('ajout_ok'), 'info');

                $ret = $this->onInsert();

//                if ($ret > 0) {
//                    $_REQUEST['curId'] = $this->id = $ret;
//                }

                $this->gs->notifyAdd($this->table, $this->id);
            } else if (isset($_REQUEST['delId']) && $_REQUEST['delId'] != "new") {

                $this->id = $_REQUEST['delId'];
                $this->DeleteRow($_REQUEST['delId']);
            }

            $res = $this->recordData();

            if (is_array($res)) {
                global $fieldError;
                $fieldError = $res;
            }

            $this->onSave();
        }

        if (isset($_REQUEST['genform_downfk'])) {
            $t = explode("__", $_REQUEST['genform_downfk']);
            $ord = new GenOrder($t[0], $t[1], 0, $t[2]);
            $ord->GetDown();

            $ord->ReOrder();
        } else if (isset($_REQUEST['genform_upfk'])) {
            $t = explode("__", $_REQUEST['genform_upfk']);
            $ord = new GenOrder($t[0], $t[1], 0, $t[2]);

            $ord->GetUp();

            $ord->ReOrder();
        }


        if (akev($_REQUEST, 'genform_stay') == 'ajaxsave') {
            echo $_REQUEST['curId'];
            die();
        }


        if (akev($_REQUEST, 'genform_stay') == 'autosave') {
            echo '<style>*{font-size:11px;padding:2px;margin:0;border:0;font-family:sans-serif;text-align:right;}</style>' . t('saved_at') . date('H:i:s') . '';
            echo '
        	<script>
        		top.document.getElementById("curId").value = "' . $_REQUEST['curId'] . '";
        	</script>
        	';
            die();
        }

        $_SESSION[gfuid()]['curFields'] = array();
        // mail('conort@gmail.com','clean',gfuid());

        return $this->id;
    }

    /**
     * Que faire lorsque l'on sauvegarde
     *
     * @return unknown
     */
    function onSave()
    {

        $res = $this->checkDoOn('save');

        return $res;
    }

    /**
     * Que faire lorsque l'on insert
     *
     * @return unknown
     */
    function onInsert()
    {

        global $_Gconfig;

        $chps = getTabField($this->table);


        if (!empty($chps[$_Gconfig['field_date_crea']])) {
            DoSql('UPDATE ' . $this->table . ' SET ' . $_Gconfig['field_date_crea'] . ' = NOW() 
            		WHERE ' . $this->pk . ' = "' . $this->id . '"');
        }

        if (!empty($chps[$_Gconfig['field_creator']]) && $this->gs) {
            DoSql('UPDATE ' . $this->table . ' SET ' . $_Gconfig['field_creator'] . ' = ' . sql($this->gs->adminid) . '
            		 WHERE ' . $this->pk . ' = "' . $this->id . '"');
        }


        /**
         * If it's a versioned Object we insert the base object and like this one to the base with VERSION_FIELD
         */
        if (in_array($this->table, $_Gconfig['versionedTable'])) {
            DoSql('INSERT INTO ' . $this->table . ' (' . $this->pk . ') VALUES ("")', 'FAILED TO INSERT VERSIONED OBJECT');

            $onlineId = InsertId();

            DoSql('UPDATE ' . $this->table . ' SET ' . VERSION_FIELD . ' = "' . $onlineId . '" WHERE ' . $this->pk . ' = "' . $this->id . '"'
                , 'FAILED TO UPDATE VERSION FIELD WITH : ' . $onlineId);
        } else if (isMultiVersion($this->table)) {

            /*

              DoSql('INSERT INTO '.$this->table.' ('.$this->pk.') VALUES ("")','FAILED TO INSERT FIRST VERSIONED OBJECT');

              $onlineId = InsertId();

              DoSql('UPDATE '.$this->table.' SET '.MULTIVERSION_FIELD.' = "'.$onlineId.'"
              WHERE '.$this->pk .' = "'.$this->id.'"'
              ,'FAILED TO UPDATE VERSION FIELD WITH : '.$onlineId);

             */

            DoSql('UPDATE ' . $this->table . ' SET ' . MULTIVERSION_FIELD . ' = ' . $this->id . ' WHERE ' . getPrimaryKey($this->table) . ' = ' . $this->id);
        }

        $res = $this->checkDoOn('insert');
    }

    /**
     * Que faire lorsque l'on supprime
     *
     * @return unknown
     */
    function onDelete()
    {
        global $_Gconfig;
        return $this->checkDoOn('delete');
    }

    /**
     * Que faire AVANT la suppression (l'element existe encore, et on peut retourne FALSE pour annuler)
     *
     * @return unknown
     */
    function onBeforeDelete()
    {
        global $_Gconfig;

        if (in_array($this->table, $_Gconfig['versionedTable'])) {
            $sql = 'SELECT ' . VERSION_FIELD . ' FROM ' . $this->table . ' WHERE ' . $this->pk . ' = "' . mes($this->id) . '"';
            $row = GetSingle($sql);

            $gr = new GenRecord($this->table, $row[VERSION_FIELD]);
            $gr->DeleteRow($row[VERSION_FIELD]);
        }


        return $this->checkDoOn('beforeDelete');
    }

    /**
     * Que faire lors de la modification
     *
     * @return unknown
     */
    function onUpdate()
    {
        global $_Gconfig;
        $chps = getTabField($this->table);
        if (isset($chps[$_Gconfig['field_date_maj']])) {
            DoSql('UPDATE ' . $this->table . ' SET ' . $_Gconfig['field_date_maj'] . ' = NOW() WHERE ' . $this->pk . ' = "' . $this->id . '"');
        }


        updateParam('date_update_' . $this->table, time());

        return $this->checkDoOn('update');
    }

    /**
     * Execution des actions
     *
     * @param unknown_type $action
     * @return unknown
     */
    function checkDoOn($action)
    {
        global $gr_on;
        //if ($action != 'save')

        $status = true;

        logAction($action, $this->table, $this->id);

        if (is_array($gr_on)) {

            if (array_key_exists($action, $gr_on) &&
                array_key_exists($this->table, $gr_on[$action])
            ) {

                if (!is_array($gr_on[$action][$this->table])) {
                    $gr_on[$action][$this->table] = array($gr_on[$action][$this->table]);
                }

                foreach ($gr_on[$action][$this->table] as $v) {

                    if (function_exists($v)) {
                        $status = call_user_func($v, $this->id, $this->row, $this, $this->table);
                    } else {
                        error('Fonction non definie pour l\'action : ' . $action . ' sur ' . $this->table);
                    }
                }
            }

            if (array_key_exists($action, $gr_on) && array_key_exists('ANY_TABLE', $gr_on[$action])) {

                if (!is_array($gr_on[$action]['ANY_TABLE'])) {
                    $gr_on[$action]['ANY_TABLE'] = array($gr_on[$action]['ANY_TABLE']);
                }
                foreach ($gr_on[$action]['ANY_TABLE'] as $v) {
                    if (function_exists($v)) {

                        $status = call_user_func($v, $this->id, $this->row, $this, $this->table);
                    } else {
                        error('Fonction non definie pour l\'action : ' . $action . ' sur ' . $this->table);
                    }
                }
            }
        }

        return $status;
    }

    function DeleteRow($id)
    {
        /*
         * Supprime un enregistrement, ses liaisons, et ses fichiers
         */

        global $orderFields, $_Gconfig;

        if (($id > 0 || $id != '') && $id != 'new') {

            if (!$this->forceDeletionPrivilege && !$this->gs->can('del', $this->table, '', $id)) {
                $this->gs->showError();
            }

            $this->onBeforeDelete();

            $obj = new GenForm($this->table, '', $id);


            foreach ($obj->tab_default_field as $champ => $v) {
                if (isUploadField($champ) && strlen($obj->tab_default_field[$champ])) {
                    /**
                     *
                     * @unlink ($uploadRep.$obj->tab_default_field[$champ])
                     */
                    $gf = new GenFile($this->table, $champ, $id);
                    $gf->deleteFile();
                }
            }


            /**
             * Suppression des relations inverses (RELINV)
             * A faire AVANT la suppression definitive
             */
            global $relinv;
            reset($relinv);
            if (!empty($relinv[$this->table])) {
                foreach ($relinv[$this->table] as $v) {
                    $sql = 'SELECT * FROM ' . $v[0] . ' WHERE ' . $v[1] . ' = "' . $id . '"';
                    $res = GetAll($sql);
                    $tpk = getPrimaryKey($v[0]);
                    foreach ($res as $row) {
                        $gr = new genRecord($v[0], $row[$tpk]);
                        $gr->DeleteRow($row[$tpk]);
                    }
                }
                reset($relinv);
            }

            /**
             * Suppresion definitive
             */
            $sql = "DELETE FROM " . $this->table . " WHERE " . getPrimaryKey($this->table) . " = " . sql($id) . "";
            DoSql($sql);

            $_REQUEST["curId"] = $this->id = "";

            if (!empty($orderFields[$this->table]) && !empty($orderFields[$this->table][1])) {
                $fk_id = $obj->tab_default_field[$orderFields[$this->table][1]];
                $ord = new GenOrder($this->table, 0, $fk_id);
                $ord->reorderAfterDelete($obj->tab_default_field[$orderFields[$this->table][0]]);
                $ord->reOrder();
            }


            /**
             * Suppresion des tables de relation (TABLEREL)
             */
            global $tablerel;
            reset($tablerel);
            foreach ($tablerel as $k => $v) {
                if (in_array($this->table, $v)) {
                    $v_inv = array_flip($v);
                    $fkch = $v_inv[$this->table];
                    $sql = 'DELETE FROM ' . $k . ' WHERE ' . $fkch . ' = "' . $id . '"';
                    DoSql($sql);
                }
            }

            if (!empty($_Gconfig['relOne'][$this->table])) {
                foreach ($_Gconfig['relOne'][$this->table] as $relTable => $relPk) {
                    $r = getRowFromId($relTable, $id);
                    if ($r) {
                        $gr = new genRecord($relTable, $id);
                        $gr->DeleteRow($id);
                    }
                }
            }

            /**
             * Suppression des traductions
             */
            DoSql('DELETE FROM s_traduction WHERE fk_id = "' . $id . '" AND fk_table = "' . $this->table . '"');

            reset($tablerel);
            $this->deleted = true;
        }
        // dinfo(t('suppression_ok').t($this->table).' /  '.$id);
        $this->onDelete();
    }

    public function initRelOne()
    {

        global $_Gconfig, $tabForms;

        $this->tab_default_field = getRowFromId($this->table, $this->id);
        $this->tables = array($this->table);
        $this->wheres = array(' ' . getPrimaryKey($this->table) . ' = ' . sql($this->id));

        if (empty($_Gconfig['relOne'][$this->table])) {
            return;
        }

        foreach ($_Gconfig['relOne'][$this->table] as $table => $clef) {
            if (!empty($this->tab_default_field[$clef])) {
                $this->tables[] = $table;
                $this->wheres[] = ' ' . $clef . ' = ' . getPrimaryKey($this->table);
                $this->tab_field = array_merge($this->tab_field, getTabField($table));
            }
        }
    }

    function recordData()
    {
        global $neededFields,
               $uploadRep, $mailFields,
               $uploadFields, $relinv,
               $tablerel, $orderFields,
               $specialUpload, $functionField, $rteFields;

        global $genMessages, $_Gconfig;

        if ($this->deleted) {
            return;
        }

        $this->onUpdate();
        $this->tab_field = getTabField($this->table);
        $this->initRelOne();

        $pre_query = "UPDATE " . implode(',', $this->tables) . " SET ";
        $query = "";


        $tab_field = $this->tab_field;

        reset($_POST);

        if (!$this->gs->can('edit', $this->table, array(), $this->id) && !$this->JustInserted) {
            $this->gs->showError();
            die();
        }

        $fk_id = false;

        $isError = false;
        /* Boucle sur le tableau POST */

        reset($_POST);
        // debug('New Record');


        foreach ($_POST as $key_name => $value) {


            /* Est - ce un champ valable ? */
            if (substr($key_name, 0, 8) == "genform_" && !isset($_POST['genform_cancel_x'])) {
                /* Nom du champ */
                $name = str_replace("genform_", "", $key_name);

                if (isset($tab_field[$name]) && !$tab_field[$name]->not_null && !strlen($value)) {
                    $value = 'NULL';
                }

                if (array_key_exists($name, $functionField)) {
                    if (array_key_exists('after', $functionField[$name])) {
                        $value = call_user_func($functionField[$name]['after'], $value);
                    }
                }

                /*
                  On enregistre tout ca, et on va ins?er quelquechose ...
                 */
                if (strstr($key_name, "genform_modfk_") !== false && !strstr($key_name, "_value") !== false) {
                    /* FK SIMPLE */
                    $tab = explode("__", $key_name);
                    // if($this->table != $tab[1]) {
                    $_REQUEST['newTable'] = $tab[1];
                    if (isset($_POST[$key_name . "_value"])) {
                        $_REQUEST['newId'] = $_POST[$key_name . "_value"];
                    } else {
                        $_REQUEST['newId'] = akev($_POST, "genform_" . akev($tab, 2));
                    }
                    // }
                } else if (strstr($key_name, "genform_delfk_") !== false && !strstr($key_name, "_value") !== false && $value != "") {

                    $tab = explode("__", $key_name);
                    // if($this->table != $tab[1]) {
                    $table_to_del = $tab[1];
                    $table_to_del_pk = GetPrimaryKey($table_to_del);
                    if (!$table_to_del_pk) {
                        continue;
                    }
                    $id_to_del = false;
                    if (!empty($_POST[$key_name . "_value"])) {
                        $id_to_del = $_POST[$key_name . "_value"];
                    } else if (!empty($tab[2])) {
                        $id_to_del = $_POST["genform_" . $tab[2]];
                    }
                    //debug('DELETE  : '.$id_to_del.' from '.$table_to_del);
                    if (($id_to_del > 0 || $id_to_del != "") && $this->gs->can('del', $table_to_del, $id_to_del)) {
                        $del_sql = 'DELETE FROM ' . $table_to_del . ' WHERE ' . $table_to_del_pk . ' = "' . $id_to_del . '"';
                        DoSql($del_sql);

                        $genMessages->add(t('suppression_ok') . ' ' . t($table_to_del), 'info');
                    }
                    // }
                } else if (strstr($key_name, "genform_add_") !== false) {
                    /* FK SIMPLE */
                    $tab = explode("_-_", $key_name);
                    if ($this->table != $tab[1]) {
                        $_REQUEST['newTable'] = $tab[1];
                        $_REQUEST['fieldToUpdate'] = $tab[2];
                        $_REQUEST['newId'] = "new";
                    }
                } else if (strstr($key_name, "genform_addfk_") !== false) {
                    /* TABLE FK DISTANTE */
                    $tab = explode("__", $key_name);
                    // if($this->table != $tab[1]) {
                    $_REQUEST['newTable'] = $tab[1];
                    $_SESSION[gfuid()]['newTableFk'] = $tab[2];
                    $_REQUEST['insertOtherField'] = "1";
                    $_REQUEST['newId'] = "new";
                    // }
                } else if (strstr($key_name, "genform_addrel_") !== false) {
                    /* TABLE DE RELATION */
                    $tab = explode("__", $key_name);
                    if ($this->table != $tab[1]) {
                        $_REQUEST['newTable'] = $tab[3];
                        $_REQUEST['fieldToUpdate'] = $tab[2];
                        $_REQUEST['tableToUpdate'] = $tab[1];
                        $_REQUEST['newId'] = "new";
                    }
                } else if (strstr($key_name, "genform_editrel_") !== false && $value > 0) {
                    /* TABLE DE RELATION */
                    $tab = explode("__", $key_name);
                    if ($this->table != $tab[1]) {
                        $_REQUEST['newTable'] = $tab[3];
                        /* $_REQUEST['fieldToUpdate'] = $tab[2];
                          $_REQUEST['tableToUpdate'] = $tab[1]; */
                        /* debug($key_name);
                          debug('edit : '.$value); */
                        $_REQUEST['newId'] = $value;
                    }
                } else if (strstr($key_name, "genform_rel") !== false && strstr($key_name, "_temoin") !== false) {
                    $key_name = str_replace("_temoin", "", $key_name);
                    $value = akev($_POST, $key_name);

                    $tab = explode("__", $key_name);
                    $found = false;
                    reset($tablerel[$tab[1]]);

                    foreach ($tablerel[$tab[1]] as $k => $v) {
                        if ($v == $this->table && !$found) {
                            $fk1 = $k;
                            $found = true;
                        } else {
                            $fk2 = $k;
                            $fk_table = $v;
                        }
                    }
                    reset($tablerel[$tab[1]]);

                    if (empty($_REQUEST['genform_cancel_x'])) {
                        $sql = "DELETE FROM " . $tab[1] . " WHERE " . $fk1 . " = " . $this->id;
                        DoSql($sql);

                        $order = 1;

                        foreach ($value as $k => $v) {
                            if ($v) {
                                $orderField = '';
                                $orderValue = '';
                                if (array_key_exists($tab[1], $orderFields)) {
                                    $orderField = ',' . $orderFields[$tab[1]][0];
                                    $orderValue = ',' . $order;
                                }
                                $sql = 'INSERT INTO ' . $tab[1] . ' ( ' . $fk1 . ' , ' . $fk2 . '' . $orderField . ')  VALUES (' . $this->id . ',' . $v . '' . $orderValue . ') ';
                                DoSql($sql);
                                $order++;
                            }
                        }
                    }

                    if (isNeeded($this->table, $tab[1]) && (!count($value) || $value == "")) {
                        $isError = 1;
                        $fieldError[$tab[1]] = 1;
                    }
                } else if (strstr($key_name, "genform_tagrel") !== false && strstr($key_name, "_temoin") !== false) {
                    /**
                     * Tablerel gérée avec des tags !
                     */
                    $key_name = str_replace("_temoin", "", $key_name);
                    $value = akev($_POST, $key_name);

                    $tab = explode("__", $key_name);
                    $found = false;
                    reset($tablerel[$tab[1]]);

                    foreach ($tablerel[$tab[1]] as $k => $v) {
                        if ($v == $this->table && !$found) {
                            $fk1 = $k;
                            $found = true;
                        } else {
                            $fk2 = $k;
                            $fk_table = $v;
                        }
                    }
                    reset($tablerel[$tab[1]]);

                    if (empty($_REQUEST['genform_cancel_x'])) {
                        $sql = "DELETE FROM " . $tab[1] . " WHERE " . $fk1 . " = " . $this->id;
                        DoSql($sql);

                        $order = 1;

                        foreach ($value as $k => $v) {
                            if ($v) {
                                /**
                                 * Nouvel élément
                                 */
                                if (strpos($k, '-') !== false) {
                                    $c = $_Gconfig['tablerelAsTags'][$tab[1]]['allowAdd'];
                                    if ($c) {

                                        $rinsert = array($c => $v);
                                        global $co;
                                        $co->Autoexecute($fk_table, $rinsert, 'INSERT');
                                        $v = InsertId();
                                    }
                                } else {
                                    $v = $k;
                                }
                                $orderField = '';
                                $orderValue = '';
                                if (array_key_exists($tab[1], $orderFields)) {
                                    $orderField = ',' . $orderFields[$tab[1]][0];
                                    $orderValue = ',' . $order;
                                }
                                $sql = 'INSERT INTO ' . $tab[1] . ' ( ' . $fk1 . ' , ' . $fk2 . '' . $orderField . ')  VALUES (' . $this->id . ',' . $v . '' . $orderValue . ') ';
                                DoSql($sql);
                                $order++;
                            }
                        }
                    }
                    if (isNeeded($this->table, $tab[1]) && (!count($value) || $value == "")) {
                        $isError = 1;
                        $fieldError[$tab[1]] = 1;
                    }
                } else if (!empty($_SESSION[gfuid()]['curFields']) && in_array($name, $_SESSION[gfuid()]['curFields'])) {
                    /* Est-il dans la liste des champs que j'ai le droit de modifier */
                    if (isset($tab_field[$name])) {

                        if (is_array($value)) {
                            $value = implode(',', $value);
                        }

                        /* Nombre reel */
                        if ($tab_field[$name]->type == "real") {
                            $val1 = (real)$value;
                            if (preg_match("/[A-Za-z]/", $value)) {
                                // echo "Found letters";
                                $isError = 1;
                                $fieldError[$name] = 1;
                            }
                        } /* Entier non FK */ else if ($tab_field[$name]->type == "int" && !strstr($name, "fk") !== false && $value != "") {
                            $val1 = (int)$value;
                            $value = str_replace(" ", "", $value);
                            $value = str_replace(".", "", $value);
                            $value = str_replace(",", "", $value);
                            if (preg_match("/[A-Za-z]/", $value) && $value != "NULL") {
                                // echo "Found letters";
                                $isError = 1;
                                $fieldError[$name] = 1;
                            }

                            /* DATE */
                        } else if ($tab_field[$name]->type == "date" && false) {
                            $value = $_POST['genform_' . $name . '_year'] . '-' . $_POST['genform_' . $name . '_month'] . '-' . $_POST['genform_' . $name . '_day'];
                            $dates = explode("-", $value);

                            /* TIME */
                        } else if ($tab_field[$name]->type == "time") {
                            $_POST['genform_' . $name . '_min'] = $_POST['genform_' . $name . '_min'] != '' ? $_POST['genform_' . $name . '_min'] : '00';
                            $_POST['genform_' . $name . '_sec'] = $_POST['genform_' . $name . '_sec'] != '' ? $_POST['genform_' . $name . '_sec'] : '00';
                            $_POST['genform_' . $name . '_hour'] = $_POST['genform_' . $name . '_hour'] != '' ? $_POST['genform_' . $name . '_hour'] : '00';


                            $value = $_POST['genform_' . $name . '_hour'] . ':' . $_POST['genform_' . $name . '_min'] . ':' . $_POST['genform_' . $name . '_sec'];
                        } else if ($tab_field[$name]->type == "datetime") {

                            /* $_POST['genform_' . $name . '_hh'] = $_POST['genform_' . $name . '_hh'] != '' ? $_POST['genform_' . $name . '_hh'] : '00';
                              $_POST['genform_' . $name . '_mm'] = $_POST['genform_' . $name . '_mm'] != '' ? $_POST['genform_' . $name . '_mm'] : '00';
                              $_POST['genform_' . $name . '_ss'] = $_POST['genform_' . $name . '_ss'] != '' ? $_POST['genform_' . $name . '_ss'] : '00';
                             */

                            $value = $_POST['genform_' . $name] . ' ' . $_POST['genform_' . $name . '_hh'] . ':' . $_POST['genform_' . $name . '_mm'] . ':' . $_POST['genform_' . $name . '_ss'];

                            $dates = explode("-", $value);
                        }
                    } else if (arrayInWord($mailFields, $name) && 0) {
                        if ($_POST['genform_' . $name . '_beforeat'] && $_POST['genform_' . $name . '_afterat']) {
                            $value = $_POST['genform_' . $name . '_beforeat'] . "@" . $_POST['genform_' . $name . '_afterat'];
                        } else {
                            $value = "";
                        }
                    } else if (is_array($value)) {
                        $value = implode(",", $value);
                    }
                    /**
                     * Fout la merde
                     */
                    // $value = addmyslashes($value);

                    if ($value == DEFAULT_URL_VALUE)
                        $value = "";

                    $value = trim($value);
                    if (($value == "" || $value == "0" || $value == "0.0" || $value == "NULL" || $value == "::" || (is_array($value) && count($value) == 0)) && isNeeded($this->table, $name)) {
                        $isError = 1;
                        $fieldError[$name] = 1;
                    }

                    /*
                     * On supprime un fichier
                     */

                    if (substr($name, -4) == "_del" || substr($name, -4) == "_del_x") {
                        $name = substr($name, 0, -4);
                        $_REQUEST['genform_stay'] = 1;

                        /**
                         *
                         * @unlink ($uploadRep.$myobj->tab_default_field[$name]);
                         */
                        $gf = new GenFile($this->table, $name, $this->id);
                        $gf->deleteFile();
                        $value = "";
                    }

                    if (substr($name, -11) == "_fromfolder") {
                        /**
                         * gestion des fichiers en lien vers un dossier
                         */
                        if ($value) {
                            if ($value == "-1") {
                                $value = '';
                            } else {
                                $value = '**' . $value;
                            }
                            $name = substr($name, 0, -11);
                        } else {
                            $name = '';
                            $value = '';
                        }
                    }

                    /**
                     * Gestion des fichiers � copier depuis le dossier upload
                     */
                    if ((substr($name, -10) == "_importftp") && $value != "0" && $value != "NULL") {
                        $name = substr($name, 0, -10);

                        $gf = new GenFile($this->table, $name, $this->id, $value, false);
                        if ($gf->uploadFile(path($_Gconfig['ftpUpload_path'], $value))) {
                            $value = $gf->getRealName();
                        }
                    } else if (substr($name, -10) == "_importftp") {
                        $name = '';
                        $value = '';
                    }

                    /**
                     * Gestion des fichiers � copier depuis le filemanager
                     */
                    if ((substr($name, -14) == "_importmanager") && $value != "0" && $value != "NULL" && $value) {
                        $name = substr($name, 0, -14);
                        $value = urldecode($value);
                        debug($name);
                        debug($value);
                    } else if (substr($name, -14) == "_importmanager") {
                        $name = '';
                        $value = '';
                    }


                    /**
                     * CHAMPS RTE
                     */
                    if (@in_array($name, $rteFields)) {
                        if (strlen(trim(strip_tags($value))) > 1 && strpos($value, '<p>') === false) {
                            $value = '<p>' . $value . '</p>';
                        }

                        $value = str_replace(
                            array('<b>', '</b>', '<u>', '</u>', '<i>', '</i>'), array('<strong>', '</strong>', '<span style="text-decoration:underline">', '</span>', '<em>', '</em>'), $value);
                        $value = strip_tags($value, '<p><a><abbr><accronym><sup><sub><ul><li><ol><br><br/><strong><em><span>');
                    }


                    /**
                     * CHAMPS RTE
                     */
                    if (in_array($name, $_Gconfig['passwordFields'])) {
                        if (strlen($value) > 0) {
                            $value = password_hash($value, PASSWORD_BCRYPT);
                        } else {
                            $name = '';
                        }
                    }


                    $aid = $this->JustInserted ? 'new' : $this->id;
                    if (!$this->gs->can('edit', $this->table, '', $aid, $name, $value) && !$this->gs->can('edit', $this->table, '', $aid, getBaseLgField($name), $value)) {
                        $this->gs->showError();
                        die();
                    }


                    if (strlen($name)) {
                        $this->curValues[$name] = $value;

                        $query .= $this->updateQuery($name, $value);
                    }
                }
            }
        }


        if ($_FILES) {
            reset($_FILES);

            foreach ($_FILES as $k => $v) {
                if (!$v['error'] && $v['name']) {
                    $name = str_replace("genform_", "", $k);

                    $gf = new GenFile($this->table, $name, $this->id, $v['name'], false);

                    if ($gf->uploadFile($v['tmp_name'])) {
                        $nameToRecord = $gf->getRealName();

                        $query .= $this->updateQuery($name, $nameToRecord); //." = '".$nameToRecord."', ";
                    } else {
                        derror('Probleme lors de la copie du fichier ' . $v['tmp_name']);
                    }
                }
            }
        }

        /* SI c'est un champ en RTE */
        if (isset($_SESSION["genform_" . $this->table]) && !isset($_POST['genform_cancel_x'])) {

            foreach ($_SESSION["genform_" . $this->table] as $k => $v) {
                if (!$this->gs->can('edit', $this->table, '', $this->id, $k, $v) && !$this->JustInserted) {
                    $this->gs->showError();
                    die();
                }
                // $query .=  $k.' = "'.addmyslashes($v).'" , ';
                $query .= $this->updateQuery($k, ((($v)))); //
            }

            $_SESSION["genform_" . $this->table] = "";
        }

        $res = false;
        if ($query && $this->id != "new" && $this->id != "") {
            $query = $pre_query . substr($query, 0, strlen($query) - 2);
            // $query .= " WHERE " . $_REQUEST['curTableKey'] . ' = "' . $this->id . '"';
            $query .= ' WHERE ' . implode(' AND ', $this->wheres);

            $res = DoSql($query);
        }

        if (!empty($orderFields[$this->table])) {
            if (!$fk_id) {
                // $myobj = new GenForm($this->table,"",$this->id);
                $myobj = getRowFromId($this->table, $this->id);
                $fk_id = akev($myobj, akev($orderFields[$this->table], 1));
            }
            $ord = new GenOrder($this->table, $this->id, $fk_id);

            if ($this->JustInserted) {
                $ord->OrderAfterInsertLastAtBottom();
            }

            $ord->ReOrder();
        }

        $this->checkDoOn('afterUpdate');
        $GLOBALS['fieldErrorTable'] = $this->table;
        if ($isError)
            return $fieldError;
        else
            return $res;
    }

    function updateQuery($name, $valeur)
    {
        $tab_field = $this->tab_field;

        if (false && updateLgField($this->table, $this->id, $name, $valeur)) {

        } else

            if ($valeur == "BLEU") {
                file_put_contents('debug.txt', 'ok2' . "\r\n", FILE_APPEND);
                return ' ' . $name . ' = ' . sql($valeur) . ' , ';
            } else {
                return ' ' . $name . ' = ' . sql($valeur) . ' , ';
            }
    }

}

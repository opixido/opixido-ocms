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

class genAction
{

    public $action;
    public $table;
    public $id;
    public $row;
    public $obj;

    public function __construct($action, $table, $id, $row = array())
    {

        $this->action = $action;
        $this->table = $table;
        $this->id = $id;
        $this->row = $row;
        global $gs_obj, $_Gconfig;

        if (count($this->row) < 1) {
            $this->row = GetRowFromId($table, $id);
        }

        if ($gs_obj->can($this->action, $this->table, $this->row, $this->id) || in_array($this->action, $_Gconfig['forceRowActions'][$this->table])) {

            $cname = 'genAction' . ucfirst($action);
            if (class_exists($cname)) {

                $this->obj = new $cname($this->action, $this->table, $this->id, $this->row);
            } else {
                global $genMessages;
                $genMessages->add(t('action_inexistante') . ' : ' . $cname);
            }
        } else {

            return false;
        }
    }

    public function doIt()
    {
        if (is_object($this->obj)) {
            if ($this->obj->checkCondition()) {
                $gr = new genRecord($this->table, $this->id);
                $gr->checkDoOn($this->action);
                logAction($this->action, $this->table, $this->id);
                DoSql('UPDATE s_param SET param_valeur = ' . sql(time()) . ' WHERE param_id = "date_update_' . $this->table . '" ');
                $res = $this->obj->doIt();
                $gr->checkDoOn('after_' . $this->action);
                return $res;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function genIt()
    {
        if (!empty($this->obj) && is_object($this->obj)) {
            if (method_exists($this->obj, 'genIt') && $this->obj->checkCondition()) {

                $gr = new genRecord($this->table, $this->id);
                $gr->checkDoOn($this->action);
                logAction($this->action, $this->table, $this->id);
                return $this->obj->genIt();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function checkCondition()
    {
        if (!empty($this->obj)) {
            return $this->obj->checkCondition();
        } else {
            return false;
        }
    }

    public function canReturnToList()
    {
        if (is_object($this->obj)) {
            if (property_exists($this->obj, 'canReturnToList')) {
                return $this->obj->canReturnToList;
            }
        } else {
            return false;
        }
    }

}

class baseAction
{

    public $action;
    public $table;
    public $id;
    public $row;
    public $canReturnToList = false;

    public function __construct($action, $table, $id, $row = array())
    {
        $this->action = $action;
        $this->table = $table;
        $this->id = $id;
        $this->row = $row;
        if (!is_array($this->row) || !count($this->row)) {
            $this->row = getRowFromId($this->table, $this->id);
        }
    }

    public function checkCondition()
    {
        debug('NO "checkCondition" SPECIFIED / PLEASE CREATE A "checkCondition" METHOD');
        return false;
    }

    public function doIt()
    {
        debug('NO "doIt" SPECIFIED / PLEASE CREATE A "doIt" METHOD');
        return false;
    }

}

class ocms_action extends baseAction
{

}

class genActionEdit
{

    public $action;
    public $table;
    public $id;
    public $row;
    public $canReturnToList = false;

    public function __construct($action, $table, $id, $row = array())
    {
        $this->action = $action;
        $this->table = $table;
        $this->id = $id;
    }

    public function checkCondition()
    {
        return $GLOBALS['gs_obj']->can('edit', $this->table, $this->id);
    }

    public function doIt()
    {

    }

}

class genActionView
{

    public $action;
    public $table;
    public $id;
    public $row;
    public $canReturnToList = false;

    public function __construct($action, $table, $id, $row = array())
    {
        $this->action = $action;
        $this->table = $table;
        $this->id = $id;
    }

    public function checkCondition()
    {
        return $GLOBALS['gs_obj']->can('view', $this->table, $this->id) && empty($_REQUEST['resume']);
    }

    public function doIt()
    {

    }

}

class genActionDel
{

    public $action;
    public $table;
    public $id;
    public $row;
    public $canReturnToList = true;

    public function __construct($action, $table, $id, $row = array())
    {
        $this->action = $action;
        $this->table = $table;
        $this->id = $id;
        $this->row = getRowFromId($table, $id);
    }

    public function checkCondition()
    {
        return $GLOBALS['gs_obj']->can('del', $this->table, $this->id);
    }

    public function doIt()
    {

        $gr = new genRecord($this->table, $this->id);
        $gr->DeleteRow($this->id);

        updateNextCacheUpdate();
        saveTemoinChange();
        
        //dinfo(t('element_supprime'));
        if (!$_REQUEST['fromList']) {
            global $_Gconfig;
            if (in_array($this->table, $_Gconfig['multiVersionTable'])) {
                if (!empty($this->row[MULTIVERSION_FIELD]) && $this->row[MULTIVERSION_FIELD] != $this->id) {
                    header('location:?curTable=' . $this->table . '&resume=1&curId=' . $this->row[MULTIVERSION_FIELD]);
                    die();
                }
            }
            header('location:?curTable=' . $this->table . '&relOne=' . akev($_REQUEST, 'relOne'));
            die();
        }

    }

    public function getForm()
    {

        p('<a class="btn" href="#" data-title-del=' . alt(t('confirm_delete')) . ' onclick="if(prompt($(this).attr(\'data-title-del\'),\'NON\') == \'OUI\')
					{ window.location=
						\'index.php?relOne=' . akev($_REQUEST, 'relOne') . '&curTable=' . $this->table . '&curId=' . $this->id . '&genform_action[del]=1\';
						return false;
					}
					 else {
					 	return false;
					}"
				 >
				<img src="' . t('src_del') . '" alt="" /> ' . t('delete') . '</a>');
    }

}

class genActionMoveRubrique
{

    public $action;
    public $table;
    public $id;
    public $row;

    public function __construct($action, $table, $id, $row = array())
    {
        $this->action = $action;
        $this->table = $table;
        $this->id = $id;

        if (!count($row) || !$row['ocms_version'])
            $this->row = getRowFromId($this->table, $this->id);
        else
            $this->row = $row;
    }

    public function checkCondition()
    {

        return true;
    }

    public function getSmallForm()
    {
        return false;
    }

    public function doIt()
    {


        if (!empty($_REQUEST['move_rubrique'])) {

            if (!$GLOBALS['gs_obj']->can('edit', 's_rubrique', array(), $_REQUEST['move_rubrique'])) {
                return;
            }
            $row = GetSingle('SELECT MAX(rubrique_ordre) AS MX FROM s_rubrique WHERE fk_rubrique_id ' . sqlParam($_REQUEST['move_rubrique']));

            $row['MX'] = $row['MX'] ? $row['MX'] : 1;

            $sql = 'UPDATE
					s_rubrique 
					SET 
					fk_rubrique_id = ' . sql($_REQUEST['move_rubrique']) . ' 
					, rubrique_ordre = ' . $row['MX'] . '
					WHERE ' . MULTIVERSION_FIELD . ' = ' . sql($this->row[MULTIVERSION_FIELD]) . '
					
					';
            DoSql($sql);

            updateNextCacheUpdate();
            saveTemoinChange();
        
            dinfo(ta('rubrique_deplacee'));
        } else {
            $this->getPostForm();
        }
    }

    public function getPostForm()
    {
        p('<form id="form_move" style="margin-top:50px;text-align:left;overflow:auto" method="post" class="well" action="index.php">
                <input type="hidden" name="curId" value="' . $this->id . '" />
                <input type="hidden" name="curTable" value="' . $this->table . '" />
                ');

        p('<h1>' . t('deplacer') . ' ' . $this->row['rubrique_titre_' . LG()] . t('deplacer_sous') . '</h1>');
        p('<fieldset class="well" >');


        if (!empty($_REQUEST['relOne'])) {
            global $_Gconfig;
            $parent = $_Gconfig['relOneParent']['s_rubrique'][$_REQUEST['relOne']];
            if (!is_int($parent)) {

                $sql = 'SELECT * FROM s_rubrique AS R, s_gabarit AS G '
                    . 'WHERE R.fk_gabarit_id = G.gabarit_id AND '
                    . 'G.gabarit_classe LIKE ' . sql($parent) . ' '
                    . '' . sqlOnlyReal('s_rubrique');
                $parents = DoSql($sql);

                if ($parents->NumRows() == 1) {
                    p('<h1>' . t('moveRubrique_aucun_autre_parent_possible') . '</h1>');
                } else {
                    foreach ($parents as $row) {
                        if ($GLOBALS['gs_obj']->can('edit', 's_rubrique', $row, $row['rubrique_id'])) {
                            print('<label><input type="radio" name="move_rubrique" value="' . $row[MULTIVERSION_FIELD] . '"> ' . getLgValue('rubrique_titre', $row) . '</label>');
                        }
                    }
                }
            } else {
                p('<h1>' . t('moveRubrique_aucun_autre_parent_possible') . '</h1>');
            }
        } else {

            if (!empty($GLOBALS['gs_obj']->myroles['s_rubrique']) && is_array($GLOBALS['gs_obj']->myroles['s_rubrique']['rows'])) {
                if ($GLOBALS['gs_obj']->can('edit', 's_rubrique', 'all', 'all')) {
                    p('<label><input type="radio" name="move_rubrique" value="NULL"/>[' . t('deplacer_a_la_racine') . ']</label>');
                }
                global $co;
                $liste = $co->getAssoc('SELECT  fk_row_id,fk_row_id AS R FROM '
                    . ' s_admin_rows '
                    . ' WHERE fk_admin_id = ' . sql($GLOBALS['gs_obj']->adminid) . ' '
                    . ' AND fk_table = "s_rubrique"');

                $res = array();
                foreach ($liste as $rub) {
                    $res = array_merge(getArboOrdered($rub, 99999), $res);
                }
            } else {
                $res = getArboOrdered('NULL', 99999);
            }


            foreach ($res as $row) {
                if ($row['rubrique_id'] && $row['rubrique_id'] != $this->row['ocms_version']) {
                    if ($row['rubrique_type'] == 'siteroot') {
                        echo '<hr style="border-top:1px solid #555"/>';
                    }
                    //$row = addRowToTab($row,$row['level']);
                    print('<label>' . str_repeat('&nbsp; &nbsp; &nbsp;', $row['level']) . '<input type="radio" name="move_rubrique" value="' . $row[MULTIVERSION_FIELD] . '"> ' . getLgValue('rubrique_titre', $row) . '</label>');
                }
            }
        }

        p('</fieldset>');
        p('<button style="position:fixed;right:20px;top:150px" name="genform_action[moveRubrique]" class="btn btn-primary"  type="submit" >' . t('deplacer_valider') . '</button>');
        p('<a style="position:fixed;right:20px;top:190px" href="?curTable=s_rubrique&curId=' . $this->id . '&resume=1" class="btn btn"  type="submit" >' . t('deplacer_annuler') . '</a>'
            . '</form><style type="text/css">#contenant{display:none}#form_move label input {vertical-align: middle;margin:0}#form_move label {font-size: 12px;line-height: 14px;}</style>');
    }

}

class genActionHideVersion
{

    public $action;
    public $table;
    public $id;
    public $row;
    public $canReturnToList = true;

    public function __construct($action, $table, $id, $row = array())
    {


        $this->action = $action;
        $this->table = $table;
        $this->id = $id;

        if (!count($row) || !ake($row, ONLINE_FIELD)) {
            $this->row = getRowFromId($table, $id);
        } else {
            $this->row = $row;
        }

        $this->onlineRow = getRowfromid($table, $this->row[VERSION_FIELD]);
    }

    public function checkCondition()
    {

        if ($this->onlineRow[ONLINE_FIELD])
            return true;
        else
            return false;
    }

    public function doIt()
    {

        $res = DoSql('UPDATE ' . $this->table . ' SET ' . ONLINE_FIELD . ' = "0"
				WHERE ' . getPrimaryKey($this->table) . ' = "' . $this->onlineRow[getPrimaryKey($this->table)] . '" ');

        if ($res) {
            dinfo(t('element_plus_visible'));
        } else {
            derror(t('erreur_hideversion'));
        }
        updateNextCacheUpdate();
        saveTemoinChange();
    }

}

class genActionHideObject
{

    public $action;
    public $table;
    public $id;
    public $row;
    public $canReturnToList = true;

    public function __construct($action, $table, $id, $row = array())
    {


        $this->action = $action;
        $this->table = $table;
        $this->id = $id;
        if (!count($row) || !ake($row, ONLINE_FIELD)) {
            $this->row = getRowFromId($table, $id);
        } else {
            $this->row = $row;
        }
    }

    public function checkCondition()
    {

        if ($this->row[ONLINE_FIELD])
            return true;
        else
            return false;
    }

    public function doIt()
    {

        $res = DoSql('UPDATE ' . $this->table . ' SET ' . ONLINE_FIELD . ' = "0"
				WHERE ' . getPrimaryKey($this->table) . ' = "' . $this->id . '" ');

        if ($res) {
            dinfo(t('element_plus_visible'));
        } else {
            derror(t('erreur_hideversion'));
        }
        
        updateNextCacheUpdate();
        saveTemoinChange();
    }

}

class genActionShowObject
{

    public $action;
    public $table;
    public $id;
    public $row;
    public $canReturnToList = true;

    public function __construct($action, $table, $id, $row = array())
    {
        $this->action = $action;
        $this->table = $table;
        $this->id = $id;
        if (!count($row) || !ake($row, ONLINE_FIELD)) {
            $this->row = getRowFromId($table, $id);
        } else {
            $this->row = $row;
        }
    }

    public function checkCondition()
    {

        if ($this->row[ONLINE_FIELD] != "1")
            return true;
        else
            return false;
    }

    public function doIt()
    {

        $errors = array();
        $r = new row($this->table, $this->row);
        foreach ($r->row as $k => $v) {
            if (isNull($v) && isNeeded($this->table, $k)) {
                $errors[] = $k;
            }
        }
        global $tablerel_reverse;
        genTableRelReverse();

        if (!empty($tablerel_reverse[$this->table])) {
            foreach ($tablerel_reverse[$this->table] as $k => $v) {
                $res = $r->{$v['tablerel']};
                if (isNull($res) && isNeeded($this->table, $v['tablerel'])) {
                    $errors[] = $v['tablerel'];
                }
            }
        }
        if (count($errors)) {
            dinfo(t('show_merci_de_remplir_les_champs_suivants'));
            $h = '';
            foreach ($errors as $v) {
                $h .= t($v) . '<br/>';
            }
            dinfo($h);
            return false;
        }

        $res = DoSql('UPDATE ' . $this->table . ' SET ' . ONLINE_FIELD . ' = "1"
				WHERE ' . getPrimaryKey($this->table) . ' = "' . $this->id . '" ');

        if ($res) {
            dinfo(t('element_visible'));
        } else {
            derror(t('erreur_showversion'));
        }
        
        updateNextCacheUpdate();
        saveTemoinChange();        


    }

}

class objDuplication
{

    public $action;
    public $table;
    public $id;
    public $row;
    public $noCopyField = array();

    public function __construct($table, $id, $row = array())
    {

        global $_Gconfig;

        $this->table = $table;
        $this->id = $id;
        if (!is_array($row) || !count($row)) {
            $this->row = getRowFromId($table, $id);
        } else {
            $this->row = $row;
        }

        $this->noCopyField = array_merge(array(getPrimaryKey($table)), $_Gconfig['noCopyField']);
    }

    function duplicateTo($new_id = 'new')
    {

        global $uploadFields, $_Gconfig, $relinv, $tablerel, $tablerel_reverse;

        genTableRelReverse();

        /**
         * New ID or current ID ?
         */
        if (($new_id == 'new' && empty($tablerel[$this->table]))) {
            $new_id = insertEmptyRecord($this->table);
        } else {
            $r = getRowFromId($this->table, $new_id);
            if (!$r) {
                $new_id = insertEmptyRecord($this->table, $new_id);
            }
        }

        $newId = $new_id;

        if (!$newId) {
            derror('No ID');
            return false;
        }

        $tabField = getTabField($this->table);

        /**
         * Préparation de la requête
         * @old $sql = 'UPDATE '.$this->table.' SET ';
         */
        $record = array();
        /**
         * On copie d'abord les champs normaux
         */
        $oldfiles = array();

        foreach ($this->row as $k => $v) {
            /**
             * Si il n'est pas dans la liste des fichiers à ne pas copier
             * et que la clef n'est pas numérique (duplicat ADODB)
             */
            if (
                !in_array($k, $this->noCopyField) && !in_array($this->table . '.' . $k, $this->noCopyField) && !is_int($k)
            ) {
                /**
                 * Si c'est un champ d'upload
                 * on le met de côté
                 */
                if (isUploadField($k) && array_key_exists($k, $tabField) && ($v && !strpos($v, '/')) ) {
                    $oldfile = new genFile($this->table, $k, $this->id, $v);
                    $oldfiles[] = array('path' => $oldfile->getSystemPath(),
                        'valeur' => $v,
                        'champ' => $k);
                    /**
                     * On ne rempli pas ce champ
                     * Sinon l'uploadFile essai de supprimer un fichier
                     * inexistant
                     */
                    //$record[$k] = $v;
                } else {
                    $record[$k] = $v;
                }
            }
        }

        global $co;
        $co->autoExecute($this->table, $record, 'UPDATE', getPrimaryKey($this->table) . ' = ' . sql($newId, 'int'));


        /**
         * On copie tous les fichiers de l'ancienne version vers la nouvelle
         */
        if (count($oldfiles)) {
            foreach ($oldfiles as $oldfile) {
                $newfile = new genFile($this->table, $oldfile['champ'], $newId, basename($oldfile['valeur']), false);
                if (file_exists($oldfile['path']) && is_file($oldfile['path'])) {
                    $newfile->uploadFile($oldfile['path'], true);
                } else if (empty($oldfile['valeur'])) {
                    $newfile->deleteFile(true);
                }
            }
        }

        unset($oldfiles);


        /**
         * On duplique les traduction supplémentaires
         */
        $this->deleteAndDupli('s_traduction', 'fk_id', $this->id, $newId, 'fk_table = "' . $this->table . '" AND ');


        /**
         * On duplique les tables liées (relations inverses) 1<=n
         */
        if (!empty($relinv[$this->table])) {
            foreach ($relinv[$this->table] as $k => $v) {

                if (!@in_array($k, $this->noCopyField) && !@in_array($this->table . '.' . $k, $this->noCopyField)) {
                    $this->deleteAndDupli($v[0], $v[1], $this->id, $newId);
                }
            }
        }

        $currentReloneTable = '';
        if (!empty($_Gconfig['relOne'][$this->table])) {
            foreach ($_Gconfig['relOne'][$this->table] as $table => $clef) {
                if (!empty($this->row[$clef])) {
                    $currentReloneTable = $table;
                    $d = new objDuplication($table, $this->id);
                    $d->duplicateTo($newId);
                }
            }
        }

        /**
         * On duplique les tables de relations n<=>n
         */
        if (!empty($tablerel_reverse[$this->table])) {
            foreach ($tablerel_reverse[$this->table] as $k => $v) {
               
                $tablerelNotToDuplicateTable = $_Gconfig['tablerelNotToDuplicate'][$this->table] ?? array();
                $tablerelNotToDuplicateRelOne = $_Gconfig['tablerelNotToDuplicate'][$this->table . '.' . $currentReloneTable] ?? array();

                if (!@in_array($v['tablerel'], $tablerelNotToDuplicateTable)

                    /**
                     * Prise en compte des tablerel dupliquées depuis une relone mais pas une autre
                     */
                    && !@in_array($v['tablerel'], $tablerelNotToDuplicateRelOne)) {
                    $this->deleteAndDupli($v['tablerel'], $v['myfk'], $this->id, $newId);
                }
            }
        }

        return $newId;
    }

    /**
     * Duplique toutes les liaisons vers un enregistrement
     *
     * @param string $table
     * @param string $fkchamp Clef externe vers l'enregistrement principal
     * @param mixed $idfrom identifiant de l'enregistrement principal source
     * @param mixed $idto identifiant du nouvel enregistrement principal
     * @param string $fk_cond condition SQL
     */
    function deleteAndDupli($table, $fkchamp, $idfrom, $idto, $fk_cond = '')
    {

        //debug('DELETE AND DUPLI : '.$table.' - '.$fk_champ.' - '.$idfrom.' - '.$idto);
        //return;
        global $tablerel;

        $pk = GetPrimaryKey($table);

        /**
         * Suppression
         */
        if ($table == 's_traduction') {

            $sql = 'DELETE  FROM ' . $table . ' WHERE ' . $fk_cond . ' ' . $fkchamp . ' = ' . sql($idto, 'int') . ' ';
            $res = DoSql($sql);
        } else {

            /**
             * Si c'est une table de relation
             * On ne supprime que les enregistrements de la table de relation
             */
            if (ake($table, $tablerel)) {
                $sql = 'DELETE FROM ' . $table . ' WHERE ' . $fk_cond . ' ' . $fkchamp . ' = "' . mes($idto, 'int') . '" ';
                DoSql($sql);

                /**
                 * Si c'est une table de relation inverse $relinv
                 * on supprime les enregistrements de cette table et tout ce qui le concerne
                 */
            } else {


                $sql = 'SELECT * FROM ' . $table . ' WHERE ' . $fk_cond . ' ' . $fkchamp . ' = "' . mes($idto, 'int') . '" ';
                $res = GetAll($sql);

                foreach ($res as $row) {
                    $gr = new genRecord($table, $row[$pk]);
                    $gr->deleteRow($row[$pk]);
                }
            }
        }


        /**
         * On sélectionne tous les enregistrement
         *
         */
        $sql = 'SELECT * FROM ' . $table . ' WHERE  ' . $fk_cond . ' ' . $fkchamp . ' = "' . mes($idfrom, 'int') . '" ';
        $res = GetAll($sql);

        //debug($res);

        global $co;
        foreach ($res as $row) {

            if (!empty($tablerel[$table])) {

                $row[$fkchamp] = $idto;
                $co->autoexecute($table, $row, 'INSERT');
            } else {
                //$record = array();
                $row[$fkchamp] = $idto;

                $ob = new objDuplication($table, $row[getPrimaryKey($table)], $row);
                $id = $ob->duplicateTo('new');
            }
        }


        return;
    }

}

class genActionValidateVersion
{

    public $action;
    public $table;
    public $id;
    public $row;
    public $canReturnToList = true;

    public function __construct($action, $table, $id, $row = array())
    {

        $this->action = $action;
        $this->table = $table;
        $this->id = $id;

        $this->row = getRowFromId($table, $id);
    }

    public function checkCondition()
    {
        global $_Gconfig;

        if (ake($this->row, $_Gconfig['field_date_maj'])) {

            $r = getRowFromId($this->table, $this->row[VERSION_FIELD]);

            if (strtotime($this->row[$_Gconfig['field_date_maj']]) > strtotime($r[$_Gconfig['field_date_maj']])) {
                return true;
            } else {
                return false;
            }
        }

        return true;
    }

    public function doIt()
    {

        global $uploadFields, $_Gconfig, $relinv, $tablerel, $tablerel_reverse;


        $newId = $this->row[VERSION_FIELD];

        $od = new objDuplication($this->table, $this->id, $this->row);
        $od->noCopyField[] = VERSION_FIELD;
        $od->noCopyField[] = ONLINE_FIELD;
        $newId = $od->duplicateTo($newId);

        $record = array();
        $record[ONLINE_FIELD] = 1;

        global $co;
        ($co->AutoExecute($this->table, $record, 'UPDATE', ' ' . getPrimaryKey($this->table) . ' = ' . sql($newId)));

        $record[ONLINE_FIELD] = 0;
        ($co->AutoExecute($this->table, $record, 'UPDATE', ' ' . getPrimaryKey($this->table) . ' = ' . sql($this->id)));

        if (ake($this->row, $_Gconfig['field_date_maj'])) {

            DoSql('UPDATE ' . $this->table . ' SET ' . $_Gconfig['field_date_maj'] . ' = NOW() WHERE ' . getPrimaryKey($this->table) . ' = ' . sql($this->row[VERSION_FIELD]));
        }

        dinfo(t('modifications_en_ligne'));
        return;
    }

}

class genActionAskValidation extends ocms_action
{

    public $canReturnToList = true;

    function checkCondition()
    {

        global $_Gconfig;
        if ($this->row[ONLINE_FIELD] == 1 || $this->row[ONLINE_FIELD] == -1) {
            return false;
        }
        if (ake($this->row, $_Gconfig['field_date_maj'])) {
            $r = getRowFromId($this->table, $this->row[VERSION_FIELD]);
            if (strtotime($this->row[$_Gconfig['field_date_maj']]) > strtotime($r[$_Gconfig['field_date_maj']])) {
                return true;
            } else {
                return false;
            }
        }
        return true;
    }

    function doIt()
    {
        global $_Gconfig;
        $m = includeMail();

        $m->AddAddress(t('email_validations'));

        $m->Subject = '[' . t('base_title') . '] ' . t('askvalidation_subject');

        $trads['URL'] = getServerUrl() . BU . '/admin/?curTable=' . $this->table . '&curId=' . $this->id . '&genform_action[view]=1';
        $trads['MESSAGE'] = $_REQUEST['message'];
        $trads['CREATOR'] = $GLOBALS['gs_obj']->adminnom;
        //debug($_REQUEST['message']);
        $m->Body = tf('askvalidation_body', $trads);

        $m->Send();

        DoSql('UPDATE ' . $this->table . ' SET ' . ONLINE_FIELD . ' = -1 WHERE ' . getPrimaryKey($this->table) . ' = ' . sql($this->id));

        dinfo(t('askvalidation_done'));
    }

    function getSmallForm()
    {

        return '<a onclick="mess = prompt(\'' . t('message') . '\');if(mess == null) return false; this.href += \'&message=\'+mess;" href="?genform_action%5BaskValidation%5D=1&curTable=' . $this->table . '&curId=' . $this->id . '&action=askValidation&fromList=1">
					<img alt=' . alt(t('ask_validation')) . ' src="' . BU . '/admin/pictos_stock/tango/22x22/apps/system-software-update.png"/>
				</a>';
    }

    function getForm()
    {
        echo '<a onclick="mess = prompt(\'' . t('message') . '\');if(mess == null) return false; this.href += \'&message=\'+mess;"  class="abutton" href="?genform_action%5BaskValidation%5D=1&curTable=' . $this->table . '&curId=' . $this->id . '">
				<img src="' . BU . '/admin/pictos_stock/tango/22x22/apps/system-software-update.png"/>
				' . t('ask_validation') . '
				</a>';
    }

}

class genActionRefuseValidation extends ocms_action
{

    public $canReturnToList = true;

    function checkCondition()
    {
        global $_Gconfig;
        if ($this->row[ONLINE_FIELD] == "-1") {
            if (ake($this->row, $_Gconfig['field_date_maj'])) {
                $r = getRowFromId($this->table, $this->row[VERSION_FIELD]);
                if (strtotime($this->row[$_Gconfig['field_date_maj']]) > strtotime($r[$_Gconfig['field_date_maj']])) {
                    return true;
                } else {
                    return false;
                }
            }
            return true;
        }

        return false;
    }

    function doIt()
    {
        global $_Gconfig;
        $m = includeMail();

        $admin = getRowFromId('s_admin', $this->row[$_Gconfig['field_creator']]);

        $m->AddAddress($admin['admin_email']);

        $m->Subject = '[' . t('base_title') . '] ' . t('refusevalidation_subject');

        //$trads['URL'] = getServerUrl().BU.'/admin/?curTable='.$this->table.'&curId='.$this->id.'&genform_action[view]=1';
        $trads['MESSAGE'] = $_REQUEST['message'];
        $trads['CREATOR'] = $GLOBALS['gs_obj']->adminnom;
        $trads['TITRE'] = GetTitleFromRow($this->table, $this->row);

        //debug($_REQUEST['message']);
        $m->Body = tf('refusevalidation_body', $trads);

        $m->Send();

        DoSql('UPDATE ' . $this->table . ' SET ' . ONLINE_FIELD . ' = 0 WHERE ' . getPrimaryKey($this->table) . ' = ' . sql($this->id));

        dinfo(t('refusevalidation_done'));
    }

    function getSmallForm()
    {

        return '<a onclick="mess = prompt(\'' . t('message') . '\');if(mess == null) return false; this.href += \'&message=\'+mess;" href="?genform_action%5BrefuseValidation%5D=1&curTable=' . $this->table . '&curId=' . $this->id . '&action=refuseValidation&fromList=1">
					<img alt=' . alt(t('refuse_validation')) . ' src="' . BU . '/admin/pictos_stock/tango/22x22/actions/system-log-out.png"/>
				</a>';
    }

    function getForm()
    {
        echo '<a onclick="mess = prompt(\'' . t('message') . '\');if(mess == null) return false; this.href += \'&message=\'+mess;"  class="abutton" href="?genform_action%5BrefuseValidation%5D=1&curTable=' . $this->table . '&curId=' . $this->id . '">
				<img src="' . BU . '/admin/pictos_stock/tango/22x22/actions/system-log-out.png"/>
				' . t('refuse_validation') . '
				</a>';
    }

}

class genActionValidate
{

    public $action;
    public $table;
    public $id;
    public $row;
    public $canReturnToList = true;

    public function __construct($action, $table, $id, $row = array())
    {

        global $_Gconfig;
        $this->noCopyField = array_merge($_Gconfig['noCopyField'], array('rubrique_id', 'fk_rubrique_id', 'ocms_version', 'rubrique_etat', 'rubrique_ordre'));
        $this->relTableToCopy = array('s_paragraphe');
        $this->action = $action;
        $this->table = $table;
        $this->id = $id;
        $this->row = $row;

        if (!count($row)) {
            $this->row = getRowFromId($table, $id);
            /* getRowFromId($gf = new GenForm($this->table,'',$this->id);
              $this->row = $gf->tab_default_field; */
        }

        if (!$this->row['ocms_version']) {
            $sql = 'SELECT * FROM s_rubrique WHERE ocms_version = "' . $this->id . '"';
            $this->row = GetSingle($sql);
            $this->id = $this->row['rubrique_id'];
        }
    }

    public function checkCondition()
    {
        if (($this->row['rubrique_etat'] == 'redaction' || $this->row['rubrique_etat'] == 'attente') && $this->row['ocms_version'] != 'NULL') {
            return true;
        } else {
            return false;
        }
    }

    public function doIt()
    {

        global $genMessages, $uploadFields, $_Gconfig;

        if (!$this->checkCondition()) {
            return 'error';
        }


        $dupli = new objDuplication('s_rubrique', $this->id, $this->row);

        $dupli->noCopyField = array_merge($dupli->noCopyField, $this->noCopyField);

        $dupli->duplicateTo($this->row['ocms_version']);


        /* BORDEL DE DUPLICATION */
        $sql = 'UPDATE s_rubrique SET ';

        $sql .= ' rubrique_etat = "en_ligne" , rubrique_date_publi = NOW() ';

        $sql .= ' WHERE rubrique_id = "' . mes($this->row['ocms_version'], 'int') . '" ';
        DoSql($sql);


        $sql = 'UPDATE s_rubrique SET rubrique_etat = "redaction" WHERE rubrique_id = "' . $this->id . '"';
        DoSql($sql);

        $sql = 'UPDATE s_param SET param_valeur = UNIX_TIMESTAMP() WHERE param_id = "date_update_arbo" ';
        DoSql($sql);


        dinfo(t('modifications_en_ligne'));
        
        updateNextCacheUpdate();
        saveTemoinChange();
    }

}

class genActionVoir_modifs
{

    function doIt()
    {

    }

    function checkCondition()
    {
        return true;
    }

}

class genActionAsk_for_validation
{

    public $action;
    public $table;
    public $id;
    public $row;
    public $canReturnToList = true;

    public function __construct($action, $table, $id, $row = array())
    {


        $this->action = $action;
        $this->table = $table;
        $this->id = $id;
        $this->row = $row;

        if (isRealRubrique($this->row)) {
            $this->version_row = getVersionForRubrique($this->id);
        } else {
            $this->version_row = $this->row;
            $this->row = getRealForRubrique($this->row);
        }
        if (!count($row)) {
            $gf = new GenForm($this->table, '', $this->id);
            $this->row = $gf->tab_default_field;
        }
    }

    public function checkCondition()
    {

        if ($this->version_row['rubrique_etat'] == 'redaction') {
            return true;
        } else {
            return false;
        }
    }

    public function doIt()
    {

        global $genMessages;

        if (!$this->checkCondition() && false) {
            return 'error';
        }
        /* BORDEL DE DUPLICATION */
        $sql = 'UPDATE s_rubrique SET ';


        $sql .= ' rubrique_etat = "attente" ';
        /*
          $genMessages->add(($this->row));
          $genMessages->add(getOnlineRubid($this->row));
         */
        $sql .= ' WHERE ocms_version = "' . mes(getOnlineRubid($this->row), 'int') . '" ';
        DoSql($sql);


        //debug($this->row);
        $mails = $this->GetAdminMails(getOnlineRubid($this->row));

        //debug($mails);
        //$genMessages->add($mails);
        sendMails(
            $mails, t('mail_ask_validation'), array('id' => $this->id, 'titre' => GetRubTitle($this->row),
            'url' => GetRubUrl($this->row),
            'personne' => GetCurrentLogin()));

        //debug($mails);
        foreach ($mails as $curMail) {
            $genMessages->add(t('rubrique_demande_valider_ok') . ' ' . $curMail['admin_nom'] . ' (' . $curMail['admin_email'] . ')', 'info');
        }
        //mail('celio@opixido.com','VALIDATION','VALIDATION '.$this->id);
    }

    private function GetAdminMails($rubid, $mails = array())
    {

        global $adminTypesToMail, $onlyData;

        //debug("-->".$rubid);

        $sql = 'SELECT R.fk_rubrique_id , A.admin_nom, A.admin_email FROM s_rubrique AS R, s_admin AS A, r_admin_rubrique AS RA
				WHERE  RA.fk_admin_id = A.admin_id
				AND RA.fk_rubrique_id = R.rubrique_id
				AND A.admin_type IN ("' . implode('","', $adminTypesToMail) . '")
				AND R.rubrique_id = ' . mes($rubid) . ' ';

        $res = GetAll($sql);

        if (count($res) > 0) {
            foreach ($res as $row) {
                $mails[] = $row;
            }
            $newrubid = $row['fk_rubrique_id'];
        } else {
            $sql = 'SELECT * FROM s_rubrique WHERE rubrique_id = "' . mes($rubid) . '"';
            $row = GetSingle($sql);
            $newrubid = $row['fk_rubrique_id'];
        }

        if ($newrubid != 'NULL' && $newrubid > 0) {
            $mails = $this->GetAdminMails($newrubid, $mails);
        }

        return $mails;
    }

}

class genActionUnvalidate
{

    public $action;
    public $table;
    public $id;
    public $row;
    public $canReturnToList = true;

    public function __construct($action, $table, $id, $row = array())
    {


        $this->action = $action;
        $this->table = $table;
        $this->id = $id;
        $this->row = $row;

        /* $row is real rubrique and $version_row is fake */

        if (isRealRubrique($this->row)) {
            $this->version_row = getVersionForRubrique($this->id);
        } else {
            $this->version_row = $this->row;
            $this->row = getRealForRubrique($this->row);
        }

        if (!count($row)) {
            $gf = new GenForm($this->table, '', $this->id);
            $this->row = $gf->tab_default_field;
        }
    }

    public function checkCondition()
    {

        $sql = 'SELECT rubrique_etat FROM s_rubrique WHERE rubrique_id = "' . GetOnlineRubId($this->row) . '"';
        $row = GetSingle($sql);

        if ($row['rubrique_etat'] == 'en_ligne') {

            return true;
        } else {

            return false;
        }
    }

    public function doIt()
    {

        global $genMessages;

        if (!$this->checkCondition()) {
            $genMessages->add(t('rubrique_pas_en_ligne'), 'error');
            return 'error';
        }
        /* BORDEL DE DUPLICATION */
        $sql = 'UPDATE s_rubrique SET ';

        $sql .= ' rubrique_etat = "redaction" ';

        $sql .= ' WHERE rubrique_id = "' . mes(GetOnlineRubId($this->row), 'int') . '" OR ocms_version = "' . mes(GetOnlineRubId($this->row), 'int') . '"';

        DoSql($sql);

        UpdateArboTime();
        updateNextCacheUpdate();
        saveTemoinChange();


        $genMessages->add(t('rubrique_devalider_ok'), 'info');
    }

}

class genActionRefuse
{

    public $action;
    public $table;
    public $id;
    public $row;
    public $canReturnToList = true;

    public function __construct($action, $table, $id, $row = array())
    {


        $this->action = $action;
        $this->table = $table;
        $this->id = $id;
        $this->row = $row;

        /* $row is real rubrique and $version_row is fake */

        if (!count($row)) {
            $gf = new GenForm($this->table, '', $this->id);
            $this->row = $gf->tab_default_field;
        }

        if (isRealRubrique($this->row)) {
            $this->version_row = getVersionForRubrique($this->id);
        } else {
            $this->version_row = $this->row;
            $this->row = getRealForRubrique($this->row);
        }

        //debug($this->version_row);
    }

    public function checkCondition()
    {

        /* $sql = 'SELECT rubrique_etat FROM s_rubrique WHERE rubrique_id = "'.GetOnlineRubId($this->row).'"';
          $row = GetSingle($sql);
         */

        if ($this->version_row['rubrique_etat'] == 'attente') {
            return true;
        } else {
            return false;
        }
    }

    public function doIt()
    {

        global $genMessages;

        if (!$this->checkCondition()) {
            $genMessages->add(t('rubrique_pas_en_attente'), 'error');
            return 'error';
        }
        /* BORDEL DE DUPLICATION */
        $sql = 'UPDATE s_rubrique SET ';

        $sql .= ' rubrique_etat = "redaction" ';

        $sql .= ' WHERE ocms_version = "' . mes(GetOnlineRubId($this->row), 'int') . '"';

        DoSql($sql);

        $genMessages->add(t('rubrique_refuser_ok'), 'info');
    }

}

class genActionTranslate
{

    public $action;
    public $table;
    public $id;
    public $row;

    public function __construct($action, $table, $id, $row = array())
    {

        $this->action = $action;
        $this->table = $table;
        $this->id = $id;
        $this->row = $row;
    }

    function checkCondition()
    {


        return true;
    }

    function doIt()
    {

        addTranslations($this->table, $this->id, $_REQUEST['translate_in']);

        dinfo(t('added_language') . ' ' . $_REQUEST['translate_in']);
    }

    function getForm()
    {

        p('<label class="button" for="gen_actions_' . $action . '" >');
        //$this->genButton( 'genform_action['.$action.']', '1', " id='gen_actions_".$action."' class='inputimage'  type='image' src='".t('src_'.$action)."' border='0' title='".t($action)."' " );
        //p(t($action));
        $sql = 'SELECT * FROM s_langue ORDER BY langue_nom  ASC';
        $res = GetAll($sql);
        p(t('translate'));
        p('<form method="post" action="?curTable=' . $this->table . '&curId=' . $this->id . '"><select name="translate_in">');
        foreach ($res as $row) {
            p('<option value="' . $row['langue_id'] . '">' . $row['langue_nom'] . '</option>');
        }
        p('</select>');
        p('<input type="submit" name="genform_action[translate]" value="' . t('go') . '" /></form>');
        p('</label>');
    }

}

/* * ********************************
 *
 *    GESTION DES PLUGINS
 *
 * ******************************* */

// INSTALLATION
class genActionInstallPlugin
{

    public $action;
    public $table;
    public $id;
    public $row;
    public $canReturnToList = true;

    // CONSTRUCTEUR
    public function __construct($action, $table, $id, $row = array())
    {

        $this->action = $action;
        $this->table = $table;
        $this->id = $id;
        $this->row = $row;


        if (!ake('plugin_installe', $this->row)) {
            $this->row = getRowFromId($table, $id);
        }
    }

    // CHECK CONDITION
    function checkCondition()
    {

        // on vérifie que le plugin n'est pas déjà  installé

        $ok = !$this->row['plugin_installe'];

        return $ok;
    }

    // ACTION DO IT
    function doIt()
    {

        // on appelle le fichier "install.php"
        $filename = gen_include_path . '/plugins/' . $this->row['plugin_nom'] . '/install.php';
        if (file_exists($filename) && file_get_contents($filename) != '')
            include($filename);
        else
            dinfo('Fichier "install.php" inexistant ou vide.');

        // install BDD
        $filename = gen_include_path . '/plugins/' . $this->row['plugin_nom'] . '/install.sql';

        if (file_exists($filename) && file_get_contents($filename) != '') {


            $quers = importSqlFile($filename);;
            foreach ($quers as $sql) {
                if (!DoSql($sql)) {
                    debug($sql);
                }
            }

            recheckTranslations();

            dinfo('Fichier install.sql exécuté');
        } else {
            dinfo('Fichier "install.sql" inexistant ou vide.');
        }

        $filename = gen_include_path . '/plugins/' . $this->row['plugin_nom'] . '/datas.xml';

        if (file_exists($filename) && $x = simplexml_load_file($filename)) {
            dinfo('Found datas.xml');
            foreach ($x as $table => $v) {
                $tabFields = getTabField($table);
                $sql = 'REPLACE ' . $table . ' SET ';
                foreach ($v as $champ => $valeur) {
                    if ($tabFields[$champ]) {
                        $sql .= (' ' . $champ . ' = ' . sql($valeur) . ' ,');
                    }
                }

                DoSql(substr($sql, 0, -1));
            }
        } else {
            dinfo('no datas.xml');
        }
        $sql = 'UPDATE s_plugin SET plugin_installe=1, plugin_actif = 1 WHERE plugin_nom=' . sql($this->row['plugin_nom']);
        $res = doSql($sql);

        clearCache();
    }

}

// DESINTALLATION
class genActionUninstallPlugin
{

    public $action;
    public $table;
    public $id;
    public $row;
    public $canReturnToList = true;

    // CONSTRUCTEUR
    public function __construct($action, $table, $id, $row = array())
    {

        $this->action = $action;
        $this->table = $table;
        $this->id = $id;
        $this->row = $row;

        if (!ake('plugin_installe', $this->row)) {
            $this->row = getRowFromId($table, $id);
        }
    }

    // CHECK CONDITION
    function checkCondition()
    {

        // on vérifie que le plugin n'est pas déj�  installé
        $ok = $this->row['plugin_installe'];

        return $ok;
    }

    // ACTION DO IT
    function doIt()
    {


        // on appelle le fichier "uninstall.php"
        $filename = gen_include_path . '/plugins/' . $this->row['plugin_nom'] . '/uninstall.php';
        if (file_exists($filename) AND file_get_contents($filename) != '')
            include($filename);
        else
            dinfo('Fichier "uninstall.php" inexistant ou vide.');

        // install BDD
        $filename = gen_include_path . '/plugins/' . $this->row['plugin_nom'] . '/uninstall.sql';
        if (file_exists($filename) AND file_get_contents($filename) != '') {

            $quers = importSqlFile($filename);
            foreach ($quers as $sql) {
                if (!DoSql($sql)) {
                    echo("<p class=\"error\">Error at the line $linenumber: " . trim($dumpline) . "</p>\n");
                    echo("<p>Query: " . trim(nl2br(htmlentities($query))) . "</p>\n");
                    echo("<p>MySQL: " . mysql_error() . "</p>\n");
                    debug($query);
                }
            }
            dinfo('Fichier uninstall.sql exécuté');
        } else
            dinfo('Fichier "uninstall.sql" inexistant ou vide.');

        $_SESSION['activePlugins'] = false;

        // si tout est ok, on passe le champ plugin_installe a TRUE
        $sql = 'UPDATE s_plugin SET plugin_installe=0 , plugin_actif = 0 WHERE plugin_nom=' . sql($this->row['plugin_nom']);
        $res = doSql($sql);
    }

}

class genActionShowMV extends baseAction
{

    function checkCondition()
    {
        return true;
    }

    function doIt()
    {

    }

    public function getStateLabel($state)
    {
        if ($state === MV_STATE_ARCHIVE) {
            return 'default';
        } else if ($state === MV_STATE_DRAFT) {
            return 'info';
        } else if ($state === MV_STATE_OFFLINE) {
            return 'primary';
        } else if ($state === MV_STATE_ONLINE) {
            return 'success';
        }
    }

    public function getForm()
    {

        global $_Gconfig;

        $sql = 'SELECT * FROM ' . $this->table . ' WHERE 1 ';
        if ($this->row[MULTIVERSION_FIELD]) {

            $mainId = $this->row[MULTIVERSION_FIELD];
        } else {
            $mainId = $this->id;
        }

        $sql .= ' AND ' . MULTIVERSION_FIELD . ' = ' . sql($mainId) . ' ORDER BY FIELD(' . MULTIVERSION_STATE . ',' . sql(MV_STATE_ONLINE) . ',' . sql(MV_STATE_OFFLINE) . ',' . sql(MV_STATE_DRAFT) . ',' . sql(MV_STATE_DRAFT) . ' ), ' . $_Gconfig['field_date_maj'] . ' DESC';

        $res = GetAll($sql);

        $label = $this->getStateLabel($this->row[MULTIVERSION_STATE]);


        p('<div class="well">');

        echo '<div class="alert alert-' . $label . '">' . t('mv_version_' . $this->row[MULTIVERSION_STATE]) . '</div>';

        p('<table class="table table-striped table-bordered"><tr><th colspan="2">' . t('mv_versions') . '</th></tr>');

        foreach ($res as $k => $row) {

            if ($row[getPrimaryKey($this->table)] != $this->id) {
                p('<tr class=""><td>');
                p('<a href="?curTable=' . $this->table . '&amp;curId=' . $row[getPrimaryKey($this->table)] . '&amp;resume=1">' . htmlentities(GetTitleFromRow($this->table, $row), ENT_QUOTES, 'utf-8'));
            } else {
                p('<tr class="info"><td>');
                p(htmlentities(GetTitleFromRow($this->table, $row), ENT_QUOTES, 'utf-8'));
            }


            if (!empty($row[MULTIVERSION_FIELDNAME])) {
                p('<br/><span class="badge">' . $row[MULTIVERSION_FIELDNAME] . '</span>');
            } else {
                p('<br/><span class="badge" title=' . alt(niceDateTime($row[$_Gconfig['field_date_maj']])) . '>' . niceTextDate($row[$_Gconfig['field_date_maj']]) . '</span>');
            }

            if ($row[getPrimaryKey($this->table)] != $this->id) {
                p('</a>');
            }


            $label = $this->getStateLabel($row[MULTIVERSION_STATE]);


            p('<td><span class="label label-' . $label . '">' . t('enum_' . $row[MULTIVERSION_STATE]) . '</span></td>');
            //p('<td><img src="' . t('src_enum_' . $row[MULTIVERSION_STATE]) . '" alt=' . alt(t('enum_' . $row[MULTIVERSION_STATE])) . ' /></td>');
            p('</td></tr>');
        }

        p('	</table></div>');
    }

    public function getSmallForm()
    {
        return false;
    }

}

/**
 * Duplication d'une version
 */
class genActionDuplicateMV extends baseAction
{

    function checkCondition()
    {
        return true;
    }

    function doIt()
    {

        $od = new objDuplication($this->table, $this->id, $this->row);
        $newId = $od->duplicateTo('new');

        $record = array();
        $record[MULTIVERSION_FIELD] = $this->row[MULTIVERSION_FIELD] ? $this->row[MULTIVERSION_FIELD] : $this->id;
        $record[MULTIVERSION_STATE] = MV_STATE_DRAFT;
        $record[MULTIVERSION_FIELDNAME] = $_REQUEST[MULTIVERSION_FIELDNAME];

        global $co;
        ($co->AutoExecute($this->table, $record, 'UPDATE', ' ' . getPrimaryKey($this->table) . ' = ' . sql($newId)));
        header('location:?curTable=' . $this->table . '&curId=' . $newId);
        die();
    }

}

function checkDeleteMv($id, $row, $gr, $table)
{
    if (isMultiVersion($table)) {
        if (!$row) {
            $row = getRowFromId($table, $id);
        }

        if ($row[getPrimaryKey($table)] === $row[MULTIVERSION_FIELD]) {
            $sql = 'SELECT ' . getPrimaryKey($table) . ' FROM ' . $table . ' WHERE ' . getPrimaryKey($table) . ' != ' . sql($row[MULTIVERSION_FIELD]) . ' '
                . ' AND ' . MULTIVERSION_FIELD . ' = ' . sql($row[MULTIVERSION_FIELD]);
            $res = getAll($sql);

            foreach ($res as $row) {
                $gr = new genRecord($table, $row[getPrimaryKey($table)]);
                $gr->DeleteRow($row[getPrimaryKey($table)]);
            }
        }
    }
}

/**
 * Mettre en ligne les modifications d'une version MV
 */
class genActionPublishMV extends baseAction
{

    function checkCondition()
    {
        if ($this->row[MULTIVERSION_STATE] !== MV_STATE_ONLINE) {
            return true;
        } else {
            return false;
        }
    }

    function doIt()
    {
        global $_Gconfig;
        if ($this->row[MULTIVERSION_FIELD] != $this->id) {
            /**
             * Si on publie un brouillon ou une archive
             */
            $d = new objDuplication($this->table, $this->id);
            $d->duplicateTo($this->row[MULTIVERSION_FIELD]);
        }
        /**
         * Quoi qu'il en soit en met à "en_ligne" la version réelle
         */
        $r = array(MULTIVERSION_STATE => MV_STATE_ONLINE, $_Gconfig['field_date_maj'] => time());
        global $co;
        $co->autoExecute($this->table, $r, 'UPDATE', getPrimarykey($this->table) . ' = ' . sql($this->row[MULTIVERSION_FIELD]));

        $sql = 'UPDATE s_param SET param_valeur = UNIX_TIMESTAMP() WHERE param_id = "date_update_arbo" ';
        DoSql($sql);

        //if (!isNull($this->row[$_Gconfig['field_date_online']])) {
        updateNextCacheUpdate();
        saveTemoinChange();
        //}

        dinfo(t('modifications_en_ligne'));
    }

}

/**
 * Lien vers la modification du brouillon depuis la version réelle
 */
class genActionEditOtherMV extends baseAction
{

    /**
     *
     * @var rubrique
     */
    public $rubrique;

    public function __construct($action, $table, $id, $row = array())
    {
        parent::__construct($action, $table, $id, $row);
        $this->rowObj = new row($this->table, $this->id);
    }

    public function checkCondition()
    {
        return ($this->row[MULTIVERSION_STATE] === 'en_ligne');
    }

    public function getForm()
    {

        $drafts = $this->rowObj->getDrafts();

        /**
         * Si on a déjà un brouillon on peut le modifier direction
         */
        if ($drafts->NumRows() > 0) {
            /**
             * Si plusieurs brouillons on modifie le plus récent
             */
            $draft = $drafts->FetchRow();
            echo '<div class="well">';
            echo '<a href="?curTable=' . $this->table . '&curId=' . $draft[getPrimaryKey($this->table)] . '" class="btn btn-primary">' . ta('mv_edit_draft') . '</a>';
            echo '</div>';
        } else {
            /**
             * Sinon on propose l'ajout d'un brouillon
             */
            echo '<div class="well">';
            echo '<a href="?curTable=' . $this->table . '&genform_action[duplicateMV]=1&curId=' . $this->id . '" class="btn btn-primary">' . ta('mv_duplicate_and_edit_draft') . '</a>';
            echo '</div>';
        }
    }

    function getSmallForm()
    {

        $drafts = $this->rowObj->getDrafts();

        /**
         * Si on a déjà un brouillon on peut le modifier direction
         */
        if ($drafts->NumRows() > 0) {
            /**
             * Si plusieurs brouillons on modifie le plus récent
             */
            $draft = $drafts->FetchRow();

            return '<a href="?curTable=' . $_REQUEST['curTable'] . '&curId=' . $draft[getPrimaryKey($this->table)] . '"><img src="' . t('src_edit') . '" alt=' . alt(t('edit')) . ' title=' . alt(t('edit')) . '></a>';
        } else {
            /**
             * Sinon on propose l'ajout d'un brouillon
             */
            return '<a class="" href="?curTable=' . $_REQUEST['curTable'] . '&genform_action[duplicateMV]=1&curId=' . $this->id . '" ><img src="' . t('src_edit') . '" alt=' . alt(t('edit')) . ' title=' . alt(t('edit')) . '></a>';
        }
    }

}

/**
 * Masquer une version "en ligne"
 */
class genActionUnpublishMV extends baseAction
{

    function checkCondition()
    {
        if ($this->row[MULTIVERSION_STATE] === MV_STATE_ONLINE) {
            return true;
        } else {
            return false;
        }
    }

    function doIt()
    {

        $sql = 'UPDATE ' . $this->table . '
                        SET ' . MULTIVERSION_STATE . ' = ' . sql(MV_STATE_OFFLINE) . '
                        WHERE ' . getPrimaryKey($this->table) . ' = ' . sql($this->id);

        DoSql($sql);

        updateNextCacheUpdate();
        saveTemoinChange();

        dinfo(t('rubrique_masquee'));
    }

}

class genActionDelete extends baseAction
{

    function checkCondition()
    {
        return true;
    }

    function doIt()
    {
        updateNextCacheUpdate();
        saveTemoinChange();
    }

    function getSmallForm()
    {

    }

}

/**
 * Suppression d'une seule version
 */
class genActionDeleteMv extends baseAction
{

    function checkCondition()
    {
        if ($this->row[MULTIVERSION_FIELD] != $this->id) {
            return true;
        }
        return false;
    }

    function doIt()
    {
        updateNextCacheUpdate();
        saveTemoinChange();
    }

}

class genActionMoveTableRowUp extends baseAction
{

    public $canReturnToList = true;

    function checkCondition()
    {
        global $_Gconfig;
        if ($this->row[$_Gconfig['arboredTable'][$this->table]] > 0) {
            return false;
        }
        if ($this->row[$_Gconfig['orderedTable'][$this->table]] == 1) {
            return false;
        }
        return true;
    }

    function doIt()
    {
        global $_Gconfig;
        $chp = $_Gconfig['orderedTable'][$this->table];
        $pk = getPrimaryKey($this->table);
        $sql = 'SELECT ' . $pk . ',' . $chp . '
						FROM ' . $this->table . '
						WHERE ' . $chp . ' < ' . $this->row[$chp] . ' ';

        if ($fk = $_Gconfig['arboredTable'][$this->table]) {
            $sql .= ' AND ( ' . $fk . ' = 0 OR ' . $fk . ' IS NULL ) ';
        }

        $sql .= '		ORDER BY ' . $chp . ' DESC
						LIMIT 0,1
						';

        $row = GetSingle($sql);

        DoSql('UPDATE ' . $this->table . ' SET ' . $chp . ' = ' . ($row[$chp]) . ' WHERE ' . $pk . ' = ' . $this->id);

        DoSql('UPDATE ' . $this->table . ' SET ' . $chp . ' = ' . ($this->row[$chp]) . ' WHERE ' . $pk . ' = ' . $row[$pk]);
    }

    function getForm()
    {

    }

}

class genActionMoveTableRowDown extends baseAction
{

    public $canReturnToList = true;

    function checkCondition()
    {
        global $_Gconfig;
        if ($this->row[$_Gconfig['arboredTable'][$this->table]] > 0) {
            return false;
        }
        if (!$GLOBALS['currentOrderedMax']) {
            $sql = 'SELECT MAX(' . $_Gconfig['orderedTable'][$this->table] . ') AS MAXI FROM ' . $this->table . ' ';
            if ($fk = $_Gconfig['arboredTable'][$this->table]) {
                $sql .= ' WHERE ( ' . $fk . ' = 0 OR ' . $fk . ' IS NULL ) ';
            }
            $sql .= ' LIMIT 0,1';
            $row = GetSingle($sql);
            $GLOBALS['currentOrderedMax'] = $row['MAXI'];
        }
        if ($this->row[$_Gconfig['orderedTable'][$this->table]] == $GLOBALS['currentOrderedMax']) {
            return false;
        }
        return true;
    }

    function doIt()
    {
        global $_Gconfig;
        $chp = $_Gconfig['orderedTable'][$this->table];
        $pk = getPrimaryKey($this->table);
        $sql = 'SELECT ' . $pk . ',' . $chp . '
						FROM ' . $this->table . '
						WHERE ' . $chp . ' > ' . $this->row[$chp] . ' ';

        if ($fk = $_Gconfig['arboredTable'][$this->table]) {
            $sql .= ' AND ( ' . $fk . ' = 0 OR ' . $fk . ' IS NULL ) ';
        }

        $sql .= '		ORDER BY ' . $chp . ' ASC
						LIMIT 0,1
						';
        $row = GetSingle($sql);

        DoSql('UPDATE ' . $this->table . ' SET ' . $chp . ' = ' . ($row[$chp]) . ' WHERE ' . $pk . ' = ' . $this->id);

        DoSql('UPDATE ' . $this->table . ' SET ' . $chp . ' = ' . ($this->row[$chp]) . ' WHERE ' . $pk . ' = ' . $row[$pk]);
    }

    function getForm()
    {

    }

}

function insertTableRowOrder($id, $quoi, $gr, $table)
{
    global $_Gconfig;

    $r = getRowFromId($table, $id);


    $sql = 'SELECT MAX(' . $_Gconfig['orderedTable'][$table] . ') AS MAXI FROM ' . $table;
    if ($fk = $_Gconfig['arboredTable'][$table]) {
        if ($r[$fk] > 0) {
            return false;
        }
        $sql .= ' WHERE ( ' . $fk . ' = 0 OR ' . $fk . ' IS NULL ) ';
    }
    $sql .= ' LIMIT 0,1';

    $row = GetSingle($sql);

    DoSql('UPDATE ' . $table . ' SET ' . $_Gconfig['orderedTable'][$table] . ' = ' . ($row['MAXI'] + 1) . '
				WHERE ' . getPrimaryKey($table) . ' = ' . $id);
}

function deleteTableRowOrder($id, $quoi, $gr, $table)
{
    global $_Gconfig;

    $row = getRowFromId($table, $id);

    $sql = 'UPDATE ' . $table . ' SET ' . $_Gconfig['orderedTable'][$table] . ' = (' . $_Gconfig['orderedTable'][$table] . ' - 1)
				WHERE ' . $_Gconfig['orderedTable'][$table] . ' > ' . $row[$_Gconfig['orderedTable'][$table]];

    if ($fk = $_Gconfig['arboredTable'][$table]) {
        $sql .= ' AND ( ' . $fk . ' = ' . sql($row[$fk]) . ' OR ' . $fk . ' IS NULL ) ';
    }

    //$sql .= ' LIMIT 0,1';

    doSql($sql);
}

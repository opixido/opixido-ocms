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
 * GenOrder
 */

class GenOrder
{

    public $order_field;
    public $table;
    public $id;
    public $fk_id;
    public $fk_champ;

    public function __construct($table, $id = 0, $fk_id = 0, $fk_champ = '')
    {


        global $_Gconfig;
        if (!isset($_Gconfig['fullArboRev'])) {
            foreach ($_Gconfig['fullArbo'] as $root_table => $v) {
                $v = array_pop($v);
                $_Gconfig['fullArboRev'][$v[0]] = array($v[1], $v[2]);
            }
        }

        global $orderFields;
        $this->table = $table;
        $this->id = $id;
        $this->fk_id = $fk_id;


        if (!empty($orderFields[$this->table]) && is_array($orderFields[$this->table])) {
            $this->DoIt = true;
        } else {
            $this->DoIt = false;
            return;
        }

        $this->order_field = $orderFields[$this->table][0];
        if ($fk_champ == '') {
            $this->fk_champ = akev($orderFields[$this->table], 1);
        } else {
            $this->fk_champ = $fk_champ;
        }
        $this->pk = GetPrimaryKey($this->table);

        if (($this->id > 0) && strlen($this->fk_champ)) {
            $sql = 'SELECT ' . $this->fk_champ . ',' . $this->order_field . ' FROM ' . $this->table . ' WHERE ' . $this->pk . ' = "' . $this->id . '"';
            $row = GetSingle($sql);

            $this->fk_id = akev($row, $this->fk_champ);
            $this->curOrderValue = $row[$this->order_field];
        }
    }

    function GetUp()
    {
        global $_Gconfig;

        if (!$this->DoIt) {
            return;
        }

        logAction('get_up', $this->table, $_REQUEST['curId']);

        $sql = 'SELECT ' . $this->order_field . ',' . $this->pk . '
        	FROM ' . $this->table . '
        	WHERE ' . $this->order_field . ' < ' . $this->curOrderValue . '
        	AND ' . $this->fk_champ . ' = "' . $this->fk_id . '" ';


        if (!empty($_Gconfig['fullArboRev'][$this->table])) {
            if ($this->fk_champ == $_Gconfig['fullArboRev'][$this->table][0]) {
                $sql .= ' AND ' . $_Gconfig['fullArboRev'][$this->table][1] . ' IS NULL ';
            }
        }

        $sql .= '
        	' . $this->specialClause() . '
        	ORDER BY ' . $this->order_field . ' DESC LIMIT 0,1';

        //echo $sql;
        $row = GetSingle($sql);
        //debug($row[$this->pk] ." -> ".$this->curOrderValue);
        //debug($this->id ." -> ".$row[$this->order_field]);
        if (count($row)) {

            $sql1 = 'UPDATE ' . $this->table . ' SET ' . $this->order_field . ' = ' . $this->curOrderValue . ' WHERE ' . $this->pk . ' = ' . $row[$this->pk];
            $sql2 = 'UPDATE ' . $this->table . ' SET ' . $this->order_field . ' = ' . $row[$this->order_field] . ' WHERE ' . $this->pk . ' = ' . $this->id;

            DoSql($sql1);
            DoSql($sql2);
        }
    }

    function GetDown()
    {

        global $_Gconfig;
        if (!$this->DoIt)
            return;

        logAction('get_down', $this->table, $_REQUEST['curId']);


        $sql = 'SELECT ' . $this->order_field . ',' . $this->pk . '
        	FROM ' . $this->table . '
        	WHERE ' . $this->order_field . ' > ' . $this->curOrderValue . '
        	AND ' . $this->fk_champ . ' = "' . $this->fk_id . '"';

        if (!empty($_Gconfig['fullArboRev'][$this->table])) {
            if ($this->fk_champ == $_Gconfig['fullArboRev'][$this->table][0]) {
                $sql .= ' AND ' . $_Gconfig['fullArboRev'][$this->table][1] . ' IS NULL ';
            }
        }

        $sql .= '
        	' . $this->specialClause() . '
        	ORDER BY ' . $this->order_field . ' ASC LIMIT 0,1';

        $row = GetSingle($sql);


        //debug($row[$this->pk] ." -> ".$this->curOrderValue);
        //debug($this->id ." -> ".$row[$this->order_field]);
        $pk = $this->pk;
        if (isMultiVersion($this->table)) {
            $pk = MULTIVERSION_FIELD;
        }

        if (count($row)) {


            $sql1 = 'UPDATE ' . $this->table . ' SET ' . $this->order_field . ' = ' . $this->curOrderValue . ' WHERE ' . $pk . ' = ' . $row[$this->pk];
            $sql2 = 'UPDATE ' . $this->table . ' SET ' . $this->order_field . ' = ' . $row[$this->order_field] . ' WHERE ' . $pk . ' = ' . $this->id;


            DoSql($sql1);
            DoSql($sql2);
        }
    }

    /**
     * Reorder after a delete inside a list
     * @param type $order
     * @return type
     */
    public function reOrderAfterDelete($order)
    {

        if (!$this->DoIt) {
            return;
        }
        if ($this->fk_id && strlen($this->fk_champ)) {
            $sql = 'UPDATE ' . $this->table . ' SET ' . $this->order_field . ' = ' . $this->order_field . ' - 1 WHERE '
                . ' ' . $this->order_field . ' > ' . $order;
            $sql .= ' AND ' . $this->fk_champ . ' = "' . $this->fk_id . '"';
            DoSql($sql);
        }
    }

    function reOrder()
    {

        /*
         * Reorder the whole table
         */

        if (!$this->DoIt) {
            return;
        }

        if ($this->fk_id) {

            $sql = 'SELECT * FROM ' . $this->table . ' WHERE 1 ';

            if (strlen($this->fk_champ)) {

                $sql .= ' AND ' . $this->fk_champ . ' = "' . $this->fk_id . '"';
            }

            $sql .= ' ' . $this->specialClause() . ' ';

            $sql .= ' ORDER BY ' . $this->order_field . ' , ' . GetTitleFromTable($this->table, " , ");

            $res = GetAll($sql);


            /*
             * On parcourt le groupe d'enregistrements en question
             */

            $this->reorderRes($res);
        }
    }

    private function specialClause()
    {

        if ($this->table == 's_rubrique') {
            return ' ' . sqlRubriqueOnlyReal() . ' ';
        } else {
            return '';
        }
    }

    function ReorderRes($res = false)
    {
        if (!$this->DoIt || !$this->fk_id)
            return;

        if (!$GLOBALS['gs_obj']->can('edit', 'anything')) {
            return;
        }

        if (!$res) {
            $res = GetAll('SELECT ' . $this->order_field . ', ' . $this->pk . ' FROM ' . $this->table . ' '
                . ' WHERE ' . $this->fk_champ . ' = ' . sql($this->fk_id) . ' ORDER BY ' . $this->order_field);
        }

        $normalOrder = 0;
        $ord = array();
        foreach ($res as $row) {
            $normalOrder++;
            /* Si l'ordre ne correspond pas on le change */
            if ($row[$this->order_field] != $normalOrder) {
                $ord[$row[$this->pk]] = $normalOrder;
            }
        }
        if ($ord) {
            /**
             * On fait tout en une seule requete
             * BEAUCOUP plus rapide quand on en a beaucoup de résultats
             * à trier
             */
            $ids = implode(',', array_keys($ord));
            $sql = "UPDATE " . $this->table . " SET " . $this->order_field . " = CASE  " . $this->pk;
            foreach ($ord as $id => $ordinal) {
                $sql .= sprintf(" WHEN %d THEN %d ", $id, $ordinal);
            }
            $sql .= " END WHERE " . $this->pk . " IN ($ids)";
            DoSql($sql);
        }
    }

    function orderAfterInsertLastAtTop()
    {


        if (!$this->DoIt)
            return;

        if (strlen($this->fk_champ)) {
            $sql = ('UPDATE ' . $this->table . ' SET ' . $this->order_field . ' = ' . $this->order_field . ' + 1
	            				WHERE ' . $this->fk_champ . ' =  "' . $this->fk_id . '" 		
	            ');
            $sql .= $this->specialClause();

            DoSql($sql);

            $sql = ('UPDATE ' . $this->table . ' SET ' . $this->order_field . ' = 1
	            				WHERE ' . GetPrimaryKey($this->table) . ' =  "' . $this->id . '" 		
	            ');


            doSql($sql);
        }

        $this->reorderRes();
    }

    function OrderAfterInsertLastAtBottom()
    {


        if (!$this->DoIt)
            return;

        $sql = 'SELECT MAX(' . $this->order_field . ') AS MAXI FROM ' . $this->table . ' WHERE 1 ';

        if (strlen($this->fk_champ)) {

            $sql .= ' AND ' . $this->fk_champ . ' = "' . $this->fk_id . '"';
        }

        $sql .= $this->specialClause();

        $sql .= 'LIMIT 0,1';

        $max = GetSingle($sql);
        $max = $max['MAXI'] + 1;


        DoSql('UPDATE ' . $this->table . ' SET ' . $this->order_field . ' = ' . $max . ' WHERE ' . $this->pk . ' = ' . $this->id);


        $this->reorderRes();
    }

}

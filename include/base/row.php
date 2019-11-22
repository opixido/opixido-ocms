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

class row
{

    public $table = '';
    public $id = 0;
    public $tabField = array();
    public $relOne = false;

    function __construct($table, $roworid)
    {

        $this->table = $table;

        if (is_array($roworid) && count($roworid)) {
            $this->row = $roworid;
            $this->id = $this->row[getPrimaryKey($table)];
        } else {
            $this->id = $roworid;
            $this->row = getRowFromId($this->table, $this->id);
        }

        if (!$this->row) {
            $this->id = 0;
            return false;
        }
        $this->id = $this->row[getPrimaryKey($table)];

        $this->tabField = getTabField($this->table);
        $this->site = akev($GLOBALS, 'site');

        if (!$this->id) {
            $this->id = 0;
            return false;
        }
    }

    public function isRelOne($otherTable)
    {
        $this->relOne = $otherTable;
        $this->tabField = getTabField($this->table . '/' . $this->relOne);
    }

    /**
     * Returns nice value for the specified $field
     *
     *
     * @param string $field
     * @param bool $raw returns the raw value of the field, not parsed
     * @return mixed
     */
    function get($field, $raw = false)
    {

        if (!$this->row) {
            $this->id = 0;
            return false;
        }

        /**
         * Raw value ...
         */
        if ($raw) {
            return $this->row[$field];
        }

        /**
         * Check field types
         */
        global $relations, $relinv, $tablerel, $orderFields;

        /**
         * Upload => genfile
         */
        if (isUploadField($field)) {

            if (isBaseLgField($field, $this->table, $this->tabField)) {
                $f = $field . '_' . LG();
                if (empty($this->row[$field . '_' . LG()])) {
                    $olg = getOtherLg();
                    if (!empty($this->row[$field . '_' . $olg])) {
                        $f = $field . '_' . $olg;
                    }
                }
            } else {
                $f = $field;
            }
            $table = $this->tabField[$f]->table;
            $this->$field = new genFile($table, $f, $this->id, $this->row);
        } /**
         * LG() Field
         */ else if (isBaseLgField($field, $this->table, $this->tabField)) {
            $this->$field = getLgValue($field, $this->row);
        } /**
         * Foreign key
         */ else if (!empty($relations[$this->table][$field])) {

            $fk_table = $relations[$this->table][$field];
            $coup = mb_substr($fk_table, strpos($this->table, '_') + 1);
            $classe = false;
            if (class_exists($fk_table)) {
                $classe = $fk_table;
            } else if (class_exists($coup)) {
                $classe = $coup;
            }

            if ($classe) {
                $this->$field = new $classe($this->row[$field]);
            } else {
                $this->$field = new row($fk_table, $this->row[$field]);
            }
        } else if (!empty($tablerel[$field])) {

            /**
             * Table de relation
             */
            $found = false;
            reset($tablerel[$field]);

            foreach ($tablerel[$field] as $k => $v) {

                if ($v == $this->table && !$found) {
                    $found = true;
                    $pk1 = $k;
                } else {
                    $pk2 = $k;
                    $fk_table = $v;
                }
            }

            if ($found) {

                $sql = 'SELECT T.*
						FROM ' . $fk_table . ' AS T, ' . $field . ' AS R
						WHERE T.' . getPrimaryKey($fk_table) . ' = R.' . $pk2 . '
						AND R.' . $pk1 . ' = ' . sql($this->id) . '';

                $sql .= sqlOnlyRealAndOnline($fk_table, 'T');

                if (!empty($orderFields[$field])) {
                    $sql .= ' ORDER BY R.' . $orderFields[$field][0];
                }

                $this->$field = GetAll($sql);
            } else {

            }


            /**
             * Relation inverse
             */
        } else if (!empty($relinv[$this->table][$field])) {


            $foreignTable = $relinv[$this->table][$field][0];

            $sql = 'SELECT *
				    FROM ' . $foreignTable . '
				    WHERE ' . $relinv[$this->table][$field][1] . ' = ' . $this->id;

            if (!empty($orderFields[$foreignTable])) {

                $sql .= ' ORDER BY ' . $orderFields[$foreignTable][0];
            }

            $this->$field = GetAll($sql);

            /**
             * Raw value
             */
        } else {

            $type = '';
            if (!empty($this->tabField[$field])) {
                $type = $this->tabField[$field]->type;
            }

            if ($type == 'date' || $type == 'datetime') {

                $this->$field = new DateTime($this->row[$field]);
            }

            if (substr($type, 0, 4) == 'set(') {
                $this->$field = explode(',', $this->row[$field]);
            }

            if (array_key_exists($field, $this->row)) {
                return $this->row[$field];
            }

            return false;
        }

        return $this->$field;
    }

    function __get($name)
    {
        return $this->get($name);
    }


    /**
     *
     * @return ADOdb_RECORDSET
     * @global type $_Gconfig
     */
    public function getDrafts()
    {
        global $_Gconfig;
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE '
            . '     ' . MULTIVERSION_FIELD . ' = ' . sql($this->row[MULTIVERSION_FIELD]) . ' AND '
            . ' ' . MULTIVERSION_STATE . ' = ' . sql(MV_STATE_DRAFT) . ' ORDER BY ' . $_Gconfig['field_date_maj'] . ' DESC';

        return doSql($sql);
    }
}

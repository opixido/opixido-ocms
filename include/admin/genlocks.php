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

class genLocks
{

    public function __construct()
    {


        global $gs_obj;
        $this->gs = &$gs_obj;
    }

    public function setLock($table, $id, $row = array())
    {


        /* debug('Set lock : '.$table.' : '.$id);
          return;
         */
        //return true;
        if ($id == 'new')
            return true;

        $this->table = $table;
        $this->id = $id;
        $this->row = $row;


        $sql = 'INSERT INTO s_lock
			 (fk_admin_id,lock_table,lock_id,lock_time)
			 VALUES
			 (' . sql($this->gs->adminid) . ' , "' . mes($this->table) . '", "' . mes($this->id) . '",UNIX_TIMESTAMP())
			 ';

        return TrySql($sql);
    }

    public function unsetLock($table, $id, $row = array())
    {
        //return true;
        if ($id == 'new')
            return true;

        /* debug('UnSet lock : '.$table.' : '.$id);
          return;
         */
        $this->table = $table;
        $this->id = $id;
        $this->row = $row;

        $sql = 'DELETE from s_lock
			 WHERE fk_admin_id = ' . sql($this->gs->adminid) . '
			 AND lock_table = "' . mes($this->table) . '"
			 AND  lock_id = "' . mes($this->id) . '" ';

        return DoSql($sql, 'Suppression du Lock');
    }

    public function unsetAllLocks()
    {
        //return true;
        /* debug('Unset All locks : ');
          return; */

        $sql = 'DELETE FROM s_lock WHERE fk_admin_id = ' . sql($this->gs->adminid) . ' ';

        return DoSql($sql, 'Suppression des locks');
    }

    public function getLock($table, $id, $row = array())
    {
        //return false;
        $this->table = $table;
        $this->id = $id;
        $this->row = $row;

        $sql = 'SELECT * FROM s_lock
			WHERE lock_table = "' . mes($this->table) . '"
			AND lock_id = "' . mes($this->id) . '"
			AND lock_time >  ' . (time() - (int)GetParam('lock_timeout')) . '
			AND fk_admin_id <> ' . sql($this->gs->adminid) . '';


        $res = GetAll($sql);
        /* debug($sql);
          debug($res); */
        if (count($res)) {
            return $res;
        } else {
            return false;
        }
    }

}


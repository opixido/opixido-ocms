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

/**
 * Insert a new record in database
 *
 * @param string $table
 * @param mixed $id Id if specified otherwise trying to generate one via auto_increment or MAX()
 * @return mixed new ID or false
 */
function insertEmptyRecord($table, $id = false, $champs = array()) {


    /**
     * if $id in parameters, inserting with this ID
     */
    if ($id) {

        $sql = 'INSERT INTO ' . $table . ' (' . getPrimaryKey($table) . ' ';
        $postSql = ' ) VALUES  ( ' . sql($id) . ' ';

        foreach ($champs as $k => $v) {
            $sql .= ' , ' . $k;
            $postSql .= ' ,' . sql($v);
        }

        $res = DoSql($sql . $postSql . ' ) ');

        if ($res) {
            return $id;
        } else {
            return false;
        }
    }


    /**
     * trying to guess if table has auto_increment
     */
    $auto = false;
    $tableInfo = MetaColumns($table);

    if ($tableInfo[strtoupper(getPrimaryKey($table))]->auto_increment > 0) {
        $auto = true;
    }

    /**
     * If table has auto increment ...
     */
    if ($auto) {

        $sql = 'INSERT INTO ' . $table . ' (' . getPrimaryKey($table) . ' ';
        $postSql = ' ) VALUES  ( "" ';

        foreach ($champs as $k => $v) {
            $sql .= ' , ' . $k;
            $postSql .= ' ,' . sql($v);
        }

        $res = DoSql($sql . $postSql . ' ) ');

        if ($res) {
            return InsertId();
        } else {
            return false;
        }
    }
    /**
     * else if a specific function is defined for this table
     */ else if ($_Gconfig['insertRules'][$table]) {

        return $_Gconfig['insertRules'][$table]();

        /**
         * Otherwise : select max()+1 on primary key
         */
    } else {

        $sql = 'SELECT MAX(' . getPrimaryKey($table) . ') AS MAXI FROM ' . $table;
        $row = GetSingle($sql);

        return insertEmptyRecord($table, $row['MAXI'] + 1);
    }
}

function sqlParam($param) {
    if (in_array($param, array('NULL', 'NOT NULL'))) {
        return ' IS ' . $param;
    } else if ((int) $param == $param) {
        return ' = ' . $param . ' ';
    } else {
        return ' LIKE "' . $param . '" ';
    }
}

/**
 * Retourne le SQL de sélection d'un champ de langue
 * evite de passer par le getLGvalue
 *
 * @param unknown_type $champ
 * @return unknown
 */
function sqlLgValue($champ, $alias = '') {
    if ($alias) {
        $alias = $alias . '.';
    }
    return ' IF(LENGTH(TRIM(' . $alias . $champ . '_' . LG . '))>=1,' . $alias . $champ . '_' . LG . ',' . $alias . $champ . '_' . LG_DEF . ')  ';
}

/**
 * Retourne le CODE SQL de sélection des champs de langue directement pour les TITRES d'une table
 * Evite de passer par le getLgValue et le getTitleFromRow
 *
 * @param unknown_type $table
 * @param unknown_type $sep
 * @return unknown
 */
function sqlLgTitle($table, $sep = ' - ') {

    global $tabForms, $tablerel;

    $sql .= 'CONCAT(""';

    $nb = count($tabForms[$table]['titre']);

    foreach ($tabForms[$table]['titre'] as $k => $v) {

        $sql .= ',';

        if (isBaseLgField($v, $table)) {
            //$sql .= ' IF(LENGTH(TRIM('.$champ.'_'.LG.'))>=1,'.$champ.'_'.LG.','.$champ.'_'.LG_DEF.') ';
            $sql .= sqlLgValue($v);
        }

        if ($k < ($nb - 1)) {
            $sql .= ' , ' . sql($sep) . '';
        }
    }

    $sql .= ')';

    return $sql;
}

global $getRowFromId_cacheRow;
$getRowFromId_cacheRow = array();

function getRowFromId($table, $id, $onlyOnline = false) {

    global $getRowFromId_cacheRow;

    if (!array_key_exists($table . "_-_" . $id, $getRowFromId_cacheRow) || !$getRowFromId_cacheRow[$table . "_-_" . $id] || IN_ADMIN) {
        $sql = 'SELECT * FROM ' . $table . ' WHERE ' . GetPrimaryKey($table) . ' = ' . sql($id) . ' ' . sqlOnlyOnline($table);
        $row = GetSingle($sql);
        if (IN_ADMIN) {
            return $row;
        } else {
            $getRowFromId_cacheRow[$table . "_-_" . $id] = $row;
        }
    }


    return $getRowFromId_cacheRow[$table . "_-_" . $id];
}

function sqlOnlyOnline($table, $alias = '') {

    global $_Gconfig;

    if (IN_ADMIN) {
        return '';
    }
    $sql = '';

    $t = getTabField($table);

    if (strlen($alias)) {
        $alias = $alias . '.';
        //$sql .= $alias;
    }

    if (ake('date_online', $t) && ake('date_offline', $t) && $t['date_online'] && $t['date_offline']) {
        $sql .= ' AND ( date_online <= NOW() OR date_online = "0000-00-00" )
		AND (date_offline >= NOW() OR date_offline = "0000-00-00"  ) ';
    }
    if (!@in_array($table, $_Gconfig['hideableTable']) && !@in_array($table, $_Gconfig['versionedTable'])) {
        return $sql;
    }
    $sql = ' AND ';

    $sql .= $alias . ONLINE_FIELD . ' = "1" ';

    if (in_array($table, $_Gconfig['versionedTable'])) {

        $sql .= 'AND ' . $alias . '' . VERSION_FIELD . ' IS NULL ';
    }

    return $sql;
}

function sqlVersionOnline($table = '', $alias = '') {

    $sql = '';

    if (strlen($alias)) {
        $alias = $alias . '.';
        $sql .= $alias;
    }

    if (!strlen($table) || in_array($table, $_Gconfig['versionedTable'])) {
        $sql .= 'AND ' . $alias . VERSION_FIELD . ' IS NULL AND ' . $alias . ONLINE_FIELD . ' = "1" ';
    }

    return $sql;
}

function GetRowFromFieldLike($table, $champ, $val) {
    $sql = 'SELECT * FROM ' . $table . ' WHERE ' . mes($champ) . ' = "' . mes($val) . '" ';
    return GetSingle($sql);
}

/**
 * Retourne le premier enregistrement d'une requete SQL
 *
 * @param string  $sql
 * @param bool $cache
 * @param string $connexion
 * @return array
 */
function GetSingle($sql, $cache = 0, $connexion = '') {
    global $co, $sqlTime, $nbRSql, $nbRetSql, $co_bdd;

    if (!$co) {
        return false;
    }
    if($cache != 2)
    $cache = false;
    $t = getmicrotime();
    $nbRSql++;
    $cache = 0;
    debugEvent($sql);

    if (strlen($connexion)) {
        if (!$cache)
            $res = $co_bdd->GetRow($sql);
        else
            $res = $co_bdd->CacheGetRow($sql);
    } else {
        if (!$cache)
            $res = $co->GetRow($sql);
        else
            $res = $co->CacheGetRow($sql);
    }

    debugEnd();

    $sqlTime += (getmicrotime() - $t);

    if (is_array($res)) {
        $nbRetSql++;
    } else {
        sqlError($sql);
    }

    return $res;
}

/**
 * Retourne l'ensemble des résultats d'une requete
 *
 * @param string $sql
 * @param bool $cache
 * @param string $connexion
 * @return array
 */
function GetAll($sql, $cache = 0, $connexion = '') {
    global $co, $sqlTime, $nbRSql, $nbRetSql, $co_bdd;

    if (!$co) {
        return false;
    }

    if (function_exists('debugEvent')) {
        debugEvent($sql);
    }
    $cache = false;
    $t = getmicrotime();
    $nbRSql++;

    /* if(!$cache)
      $res = $co->GetAll($sql);
      else
      $res = $co->CacheGetAll($sql);
     */
    if (strlen($connexion)) {

        if (!$cache)
            $res = $co_bdd->GetAll($sql);
        else
            $res = $co_bdd->CacheGetAll($sql);
    } else {

        if (!$cache)
            $res = $co->GetAll($sql);
        else
            $res = $co->CacheGetAll($sql);
    }

    debugEnd();

    $sqlTime += (getmicrotime() - $t);
    if (!is_array($res)) {

        sqlError($sql);

        return array();
    } else {

        $nbRetSql += count($res);

        return $res;
    }
}

function GetAllArr($sql, $arr) {
    global $co;
    return $co->GetAll($sql, $arr);
}

/**
 * Retoure les champs d'une table
 *
 * @param unknown_type $table
 * @return unknown
 */
function MetaColumns($table) {
    global $co;

    return $co->MetaColumns($table);
}

/**
 * My Mysql_escape_string
 *
 * @param unknown_type $str
 * @return unknown
 */
function mes($str) {
    return str_replace(array("'", '"'), array("\'", '\"'), $str);
    //return mysqli_escape_string($str);
}

/**
 * Exécute une requete SQL
 *
 * @param string $sql
 * @param string $msg Message en cas d'erreur
 * @return ADORecordSet $res
 */
function dosql($sql, $msg = '') {
    global $co;
    debugEvent($sql);
    $res = $co->execute($sql);
    debugEnd();
    if (!$res) {
        sqlError($sql, $msg);
        return false;
    }

    return $res;
}

/**
 * Retourne le nombre d'enregistrements Mysql affectés par la dernière requête
 *
 * @return int
 */
function Affected_Rows() {
    global $co;
    return $co->Affected_Rows();
}

/**
 * Execute une requete SQL mais ne retourne aucun message d'erreur si elle l'aboutit pas
 *
 * @param string $sql
 * @return unknown
 */
function TrySql($sql) {
    global $co;
    $res = $co->execute($sql);
    return $res;
}

/**
 * retourne la liste des tables
 *
 * @return unknown
 */
function getTables() {
    global $co;
    if (empty($_SESSION['cache'][UNIQUE_SITE])) {
        $_SESSION['cache'][UNIQUE_SITE] = array();
    }
    if (!ake($_SESSION['cache'][UNIQUE_SITE], 'tables')) {
        $_SESSION['cache'][UNIQUE_SITE]['tables'] = $co->MetaTables('TABLES');
    }
    return $_SESSION['cache'][UNIQUE_SITE]['tables'];
}

/**
 * Vide le cache des tables, champs, etc ...
 *
 */
function clearCache() {
    $_SESSION['cache'] = array();
    $_SESSION['cache'][UNIQUE_SITE] = array();
}

/**
 * Retourne le dernier identifiant inséré
 *
 * @return unknown
 */
function InsertId() {
    global $co;
    return $co->Insert_ID();
}

$_SESSION['cache'][UNIQUE_SITE]['tabfield'] = choose(akev($_SESSION['cache'], 'tabfield'), array(''));

/**
 * Retourne la liste des champs de la table
 *
 * @param string  $table
 * @return array
 */
function getTabField($table) {
    global $co;

    //return $co->MetaColumns($table,false);
    if (empty($_SESSION['cache'][UNIQUE_SITE]['tabField_' . $table])) {

        $t = MetaColumns($table);
        if (!is_array($t)) {
            derror('GetTabField : Badtable : "' . $table . '"');
            return array();
        }
        while (list($k, $v) = each($t)) {
            $t2[strtolower($k)] = $v;
        }

        //reset($t);
        $_SESSION['cache'][UNIQUE_SITE]['tabField_' . $table] = $t2;
        return $t2;
    }

    return $_SESSION['cache'][UNIQUE_SITE]['tabField_' . $table];
}

/**
 * Ajoute un paramètre à une chaine SQL
 *
 * @param string $param
 * @param string $type int ou string
 * @return unknown
 */
function sql($param, $type = 'string') {

    if (is_object($param) && get_class($param) == 'ADORecordSet_empty') {
        echo "\n\n---------------------------------\n\n";
        debug_print_backtrace();
        return '""';
    }
    if ($type == 'int') {
        $param = (int) $param;
    } else if ($param == 'NULL') {
        return $param;
    } else {

        $param = str_replace('\\', '\\\\', $param);
        $param = (str_replace('"', '\"', $param));
    }
    return '"' . $param . '"';
}

if (empty($_SESSION['cache'][UNIQUE_SITE])) {
    $_SESSION['cache'][UNIQUE_SITE] = array();
}

$_SESSION['cache'][UNIQUE_SITE]['pks'] = choose(akev($_SESSION['cache'][UNIQUE_SITE], 'pks'), array(''));

if (!function_exists('getPrimaryKey')) {

    function getPrimaryKey($table) {


        if (strlen($table)) {
            if (empty($_SESSION['cache'][UNIQUE_SITE]['pks'][$table])) {
                global $co;
                $t = $co->MetaPrimaryKeys($table);
                if (count($t) == 1)
                    $_SESSION['cache'][UNIQUE_SITE]['pks'][$table] = $t[0];
                else
                    $_SESSION['cache'][UNIQUE_SITE]['pks'][$table] = false;
            }
        }
        return $_SESSION['cache'][UNIQUE_SITE]['pks'][$table];
    }

}

/**
 * Checks if a rubrique has this particular option
 *
 * @param strong $set the "rubrique_option" field value
 * @param string $option The option to check
 * @return unknown
 */
function rubHasOption($set, $option) {
    if (strstr($set, $option) !== false) {
        return true;
    }
    return false;
}


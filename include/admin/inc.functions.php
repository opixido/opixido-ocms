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
/* AS USUAL ... IE NEEDS A FIX .... */
if (count($_POST)) {
    reset($_POST);
    while (list($k, $v) = each($_POST)) {
        if (substr($k, -2) == "_x" || substr($k, -2) == "_y") {
            $_POST[substr($k, 0, -2)] = $v;
        }
    }
    reset($_POST);
}

function getNomForOrder($titre)
{


    if (is_array($titre)) {
        reset($titre);

        $nomSee = $titre[0];
    } else {
        $nomSee = $titre;
    }

    return $nomSee;
}


if (array_key_exists('editTrads', $_GET)) {
    if ($_GET['editTrads'])
        $_SESSION['editTrads'] = true;
    else
        unset($_SESSION['editTrads']);
}

function getEditTrad($nom)
{
    $html = '';
    if (isset($_SESSION['editTrads'])) {
        $html .= '<a href="javascript:return false;" onclick="gid(\'ET_' . $nom . '\').style.display=\'inline\';return false" >+</a> <input onclick="return false" id="ET_' . $nom . '" type="text" name="ET_' . $nom . '" value=' . alt(t($nom)) . ' style="display:none" onchange="XHR_editTrad(this)" />';
        return $html;
    }
}

function getTableListing($table, $from = '')
{

    global $_Gconfig;


    if (!empty($GLOBALS['tableListing'][$from . $table])) {
        return $GLOBALS['tableListing'][$from . $table];
    }

    if ($from) {
        $liste = akev($_Gconfig['specialListing'], $from . '.' . $table);

    } else {
        $liste = akev($_Gconfig['specialListing'], $table);
    }

    if ($liste) {
        $GLOBALS['tableListing'][$from . $table] = $_Gconfig['specialListing'][$from . '.' . $table]();
        return $GLOBALS['tableListing'][$from . $table];
    } else {

        $sql = 'SELECT G.* FROM ' . $table . ' AS G WHERE 1 ';

        $nomSql = GetTitleFromTable($table, ' , ');

        if (in_array($table, $_Gconfig['versionedTable'])) {
            $sql .= ' AND(  ' . VERSION_FIELD . ' = ""  OR  ' . VERSION_FIELD . ' IS NULL   OR  ' . VERSION_FIELD . ' = 0  )';
        } else if (isMultiVersion($table)) {
            $sql .= ' ' . sqlOnlyReal($table);
        }

        $sql .= ' ORDER BY ' . $nomSql;
        $result = GetAll($sql);

        $GLOBALS['tableListing'][$table] = $result;

        return $GLOBALS['tableListing'][$table];
    }
}

/**
 * Retourne la liste des TABFORMS de TITRE pour les placer dans une VALUE
 *
 * @param unknown_type $titre
 * @param unknown_type $row
 * @return unknown
 */
function getNomForValue($titre, $row)
{

    if (is_array($titre)) {
        reset($titre);
        $nomSee = '';
        while (list(, $chp) = each($titre)) {
            $nomSee .= " " . $row[$chp] . "";
        }
    } else {
        $nomSee = $row[$titre];
    }

    return $nomSee;
}

function tradAdmin($txt, $rel, $table = '')
{
    /* Real name forced : TABLE_NAME.FIELD_NAME */

    // $t1 = t( $this->table_name . '.' . $txt );
    $prefix = str_replace('t_', '', $table);

    $txt_sans_table = str_replace($prefix . '_', '', $txt);

    $intt = strpos($txt, '_');

    $t3 = $intt ? str_replace('_', ' ', ucfirst(t(substr($txt, $intt + 1)))) : $txt;

    if (strstr($txt, $table . '_p_')) {
        if (!tradExists($txt)) {
            $txt = str_replace($table . '_p_', 'A_page_', $txt);
        }
    }

    $tradsInOrder = array($table . '.' . $txt,
        $txt . '.' . $table,
        $txt,
        $txt_sans_table,
        $t3
    );

    foreach ($tradsInOrder as $v) {
        if (tradExists($v))
            return t($v);
    }
    return t($txt);

    return $t3;
}

function rubriqueIsAPage($rubtype)
{
    if (is_object($rubtype))
        $rubtype = $rubtype->tab_default_field['rubrique_type'];
    return in_array($rubtype, array('siteroot', 'page'));
}

function GetTitleFromTableOLD($table, $separator = " ")
{
    global $tabForms;
    $fields = getTabField($table);

    /**
     * Si on a plusieurs champs titre
     */
    if (!is_array($tabForms[$table]['titre'])) {
        $tabForms[$table]['titre'] = array($tabForms[$table]['titre']);
    }

    $titre = '';
    /**
     * On parcourt tous les champs
     */
    foreach ($tabForms[$table]['titre'] as $k => $v) {

        /**
         * On ne met le séparateur qu'à partir du second
         */
        $sep = $k == 0 ? '' : $separator;

        /**
         * Si le champ existe c'est un champ normal
         */
        if ($fields[$v]) {
            $titre .= $sep . '' . $v;
            /**
             * sinon c'est un champ de langue
             */
        } else {
            /**
             * On parcourt toutes les langues
             */
            global $_Gconfig;
            foreach ($_Gconfig['LANGUAGES'] as $lg) {
                $titre .= $sep . '' . $v . '_' . $lg;
                $sep = $separator;
            }
        }
    }

    return $titre;
}

function truncate($str, $len = 100)
{
    $sstr = strip_tags($str);
    if (mb_strlen($sstr) > $len)
        return mb_substr($sstr, 0, $len, 'utf8') . " ...";
    else
        return $str;
}

/*
  function getTables() {
  global $bdd;
  $tables = array();
  $res = mysql_list_tables($bdd) or print(mysql_error());
  while ($row = mysql_fetch_row($res)) {
  $tables[$row[0]] = getTabField($row[0]);
  }
  return $tables;

  }
 */

function GetOnlyEditableVersion($table, $aliase = '')
{

    global $multiVersionField, $_Gconfig;
    if (strlen($aliase))
        $aliase = $aliase . ".";
    if (in_array($table, $_Gconfig['versionedTable'])) {

        return ' AND ( ' . $aliase . VERSION_FIELD . ' IS NOT NULL AND ' . $aliase . VERSION_FIELD . ' != 0 ) ';
    } else if (in_array($table, $_Gconfig['multiVersionTable'])) {

        return ' AND ' . $aliase . MULTIVERSION_FIELD . ' = ' . $aliase . getPrimaryKey($table);
    } else if (isset($_Gconfig['relOne'][$table])) {
        $i = 0;
        foreach ($_Gconfig['relOne'][$table] as $clef => $tabl) {
            $i++;
            return ' AND ' . $aliase . '' . $clef . ' = T' . $i . '.' . getPrimaryKey($tabl);
        }
    }
}

function GetOnlyVisibleVersion($table, $aliase = '')
{

    global $multiVersionField, $_Gconfig;
    if (strlen($aliase))
        $aliase = $aliase . ".";
    if (in_array($table, $_Gconfig['versionedTable'])) {

        return ' AND ( ' . $aliase . VERSION_FIELD . ' IS NULL ) ';
    } else if (in_array($table, $_Gconfig['multiVersionTable'])) {

        return ' AND ' . $aliase . ONLINE_FIELD . ' = "1"';
    }
}

function mymail($to, $sujet = '', $text = '', $headers = '')
{
    mail($to, $sujet, $text, $headers);
}

function sendMails($mails, $mail_tpl, $vars)
{

    foreach ($mails as $mail) {
        if (is_array($mail)) {
            $mail = $mail['admin_email'];
        }
        mymail($mail, $mail_tpl, implode("\n->", $vars));
    }
}

function isMultiVersion($table)
{
    global $_Gconfig;
    return (in_array($table, $_Gconfig['multiVersionTable']));
}

function tradExists($str, $lg = false)
{
    global $admin_trads;
    if ($lg) {
        return (array_key_exists($str, $admin_trads) && $admin_trads[$str][$lg] != '');
    } else {
        return array_key_exists($str, $admin_trads);
    }
}

function GetRubTitle($row)
{

    return GetTitleFromRow('s_rubrique', $row);
}

function GetRubUrl($row)
{
    //return 'index.php?r='.$row['rubrique_id'];
    $gurl = new GenUrlV2();
    $url = $gurl->buildUrlFromId($row['rubrique_id']) . GetParam('fake_folder_action') . '/' . GetParam('action_editer ');
    return $url;
}

function GetCurrentLogin()
{
    global $gs_obj;

    return $gs_obj->adminnom;
}

if (!function_exists("ta")) {

    function ta($nom)
    {

        global $frontAdminTrads, $admin_trads;

        $trads = $admin_trads;

        if (isset($trads[$nom][LG()])) {
            return $trads[$nom][LG()];
        } else if (isset($trads[$nom][LG_DEF])) {
            return $trads[$nom][LG_DEF];
        } else if (!strstr($nom, ".")) {

            $t = explode("_", $nom);
            $text = "";
            $GLOBALS['MISSINGS'][$nom] = true;
            if (count($t) > 1) {
                for ($p = 1; $p < count($t); $p++) {
                    $text .= ucfirst($t[$p]) . " ";
                }
                return $text;
            } else {
                return ucfirst($nom);
            }
        } else {
            return $nom;
        }
    }

}

function decodePassword($str)
{
    if (!strlen($str)) {
        return;
    }
    global $gs_obj;
    return $gs_obj->decrypt($str);
    return $str;
}

function encodePassword($str)
{
    if (!strlen($str)) {
        return;
    }

    global $gs_obj;
    return $gs_obj->encrypt($str);
    return $str;
}

function importSqlFile($FILENAME)
{


    $linespersession = 300000000;   // Lines to be executed per one import session
    $delaypersession = 0;      // You can specify a sleep time in milliseconds after each session
    // Works only if JavaScript is activated. Use to reduce server overrun
    // Allowed comment delimiters: lines starting with these strings will be dropped by BigDump

    $comment[] = '#';    // Standard comment lines are dropped by default
    $comment[] = '-- ';
    // $comment[]='---';      // Uncomment this line if using proprietary dump created by outdated mysqldump
    // $comment[]='/*!';         // Or add your own string to leave out other proprietary things
    // Connection character set should be the same as the dump file character set (utf8, latin1, cp1251, koi8r etc.)
    // See http://dev.mysql.com/doc/refman/5.0/en/charset-charsets.html for the full list

    $db_connection_charset = '';

    $delimiter = ';';

    // *******************************************************************************************
    // If not familiar with PHP please don't change anything below this line
    // *******************************************************************************************

    define('VERSION', '0.27b');
    define('DATA_CHUNK_LENGTH', 16384);  // How many chars are read per time
    define('MAX_QUERY_LINES', 300);      // How many lines may be considered to be one query (except text lines)
    define('TESTMODE', false);    // Set to true to process the file without actually accessing the database

    $file = fopen($FILENAME, 'r');


    @ini_set('auto_detect_line_endings', true);
    @set_time_limit(0);


    $error = false;
    // ****************************************************
    // START IMPORT SESSION HERE
    // ****************************************************
    $_REQUEST["start"] = 0;
    $_REQUEST["foffset"] = 0;
    $_REQUEST["totalqueries"] = 0;
    $_REQUEST["fn"] = $FILENAME;
    $QUERIESTODO = array();
    if (!$error && isset($_REQUEST["start"]) && isset($_REQUEST["foffset"])) {

        $gzipmode = false;

        // Start processing queries from $file

        if (!$error) {
            $query = "";
            $queries = 0;
            $totalqueries = $_REQUEST["totalqueries"];
            $linenumber = $_REQUEST["start"];
            $querylines = 0;
            $inparents = false;

            // Stay processing as long as the $linespersession is not reached or the query is still incomplete

            while ($linenumber < $_REQUEST["start"] + $linespersession || $query != "") {

                // Read the whole next line

                $dumpline = "";
                while (!feof($file) && substr($dumpline, -1) != "\n") {
                    if (!$gzipmode)
                        $dumpline .= fgets($file, DATA_CHUNK_LENGTH);
                    else
                        $dumpline .= gzgets($file, DATA_CHUNK_LENGTH);
                }
                if ($dumpline === "")
                    break;

                // Handle DOS and Mac encoded linebreaks (I don't know if it will work on Win32 or Mac Servers)

                $dumpline = str_replace("\r\n", "\n", $dumpline);
                $dumpline = str_replace("\r", "\n", $dumpline);

                // DIAGNOSTIC
                // echo ("<p>Line $linenumber: $dumpline</p>\n");
                // Skip comments and blank lines only if NOT in parents

                if (!$inparents) {
                    $skipline = false;
                    reset($comment);
                    foreach ($comment as $comment_value) {
                        if (!$inparents && (trim($dumpline) == "" || strpos($dumpline, $comment_value) === 0)) {
                            $skipline = true;
                            break;
                        }
                    }
                    if ($skipline) {
                        $linenumber++;
                        continue;
                    }
                }

                // Remove double back-slashes from the dumpline prior to count the quotes ('\\' can only be within strings)

                $dumpline_deslashed = str_replace("\\\\", "", $dumpline);

                // Count ' and \' in the dumpline to avoid query break within a text field ending by ;
                // Please don't use double quotes ('"')to surround strings, it wont work

                $parents = substr_count($dumpline_deslashed, "'") - substr_count($dumpline_deslashed, "\\'");
                if ($parents % 2 != 0)
                    $inparents = !$inparents;

                // Add the line to query

                $query .= $dumpline;

                // Don't count the line if in parents (text fields may include unlimited linebreaks)

                if (!$inparents)
                    $querylines++;

                // Stop if query contains more lines as defined by MAX_QUERY_LINES

                if ($querylines > MAX_QUERY_LINES) {
                    echo("<p class=\"error\">Stopped at the line $linenumber. </p>");
                    echo("<p>At this place the current query includes more than " . MAX_QUERY_LINES . " dump lines. That can happen if your dump file was ");
                    echo("created by some tool which doesn't place a semicolon followed by a linebreak at the end of each query, or if your dump contains ");
                    echo("extended inserts. Please read the BigDump FAQs for more infos.</p>\n");
                    $error = true;
                    break;
                }

                // Execute query if end of query detected (; as last character) AND NOT in parents

                if (preg_match('/' . preg_quote($delimiter) . '$/', trim($dumpline)) && !$inparents) {

                    $QUERIESTODO[] = str_replace('[LG()]', (defined('LG_TEMP') ? LG_TEMP : LG_DEF), $query);

                    /*
                      if (!DoSql(trim($query)))
                      { echo ("<p class=\"error\">Error at the line $linenumber: ". trim($dumpline)."</p>\n");
                      echo ("<p>Query: ".trim(nl2br(htmlentities($query)))."</p>\n");
                      echo ("<p>MySQL: ".mysql_error()."</p>\n");
                      $error=true;
                      debug($query);
                      break;
                      } else {
                      //$this->info($query);
                      }
                     */
                    $totalqueries++;
                    $queries++;
                    $query = "";
                    $querylines = 0;
                }
                $linenumber++;
            }
        }

        // Get the current file position

        if (!$error) {
            if (!$gzipmode)
                $foffset = ftell($file);
            else
                $foffset = gztell($file);
            if (!$foffset) {
                echo("<p class=\"error\">UNEXPECTED: Can't read the file pointer offset</p>\n");
                $error = true;
            }
        }
    }

    return $QUERIESTODO;
}

class gabarit extends row
{

    public function __construct($roworid)
    {
        parent::__construct('s_gabarit', $roworid);
    }

    public function includeClasse()
    {
        if ($this->row['gabarit_plugin']) {
            $f = path_concat(PLUGINS_FOLDER, $this->row['gabarit_plugin']);
        } else {
            $f = 'bdd';
        }
        $GLOBALS['gb_obj']->includeFile($this->row['gabarit_classe'] . '.php', $f);
    }

    public function showSubRubs($rubrique_id = false)
    {
        if (property_exists($this->row['gabarit_classe'], 'ocms_hiddenSubRubs')) {
            $class = $this->row['gabarit_classe'];
            $type = $class::$ocms_hiddenSubRubs;
            if (is_array($type)) {
                $sql = 'SELECT rubrique_id, ocms_version FROM s_rubrique'
                    . '  LEFT JOIN s_gabarit ON fk_gabarit_id = gabarit_id WHERE '
                    . ' fk_rubrique_id = ' . sql($rubrique_id) . ' AND ( (  '
                    . '  gabarit_classe NOT IN ("' . implode('","', $type) . '") ) OR fk_gabarit_id IS NULL ) ';
                global $co;

                $forceSubRubs = $co->getAssoc($sql);

                if ($forceSubRubs) {
                    return $forceSubRubs;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
        return true;
    }

}

function showSubRubs($row)
{
    if ($row['fk_gabarit_id']) {
        $g = new gabarit($row['fk_gabarit_id']);
        $g->includeClasse();
        $r = $g->showSubRubs($row['rubrique_id']);
        return $r;
    }
    return true;
}

function getArboOrdered($start = 'NULL', $maxlevel = 99999, $curlevel = 0, $tab = array(), $res = false)
{

    if ($curlevel == 0) {
        $r = getRowFromId('s_rubrique', $start);

        if (!empty($r) && isRealRubrique($r)) {
            $tab[] = addRowToTab($r, $curlevel);
            $curlevel++;
        }
    }

    if (!$res) {
        $sql = 'SELECT * FROM s_rubrique WHERE fk_rubrique_id ' . sqlParam($start) . ' ' . sqlRubriqueOnlyReal() . ' ORDER BY rubrique_ordre ASC ';
        $res = GetAll($sql);
    } else {
        $sql = 'SELECT * FROM s_rubrique WHERE rubrique_id IN(' . implode(',', $res) . ' ) ORDER BY rubrique_ordre ASC ';
        $res = GetAll($sql);
    }
    foreach ($res as $row) {
        //if($row['rubrique_id'] != $this->row['fk_rubrique_version_id']) {
        $tab[] = array_merge($row, array('level' => $curlevel)); //addRowToTab($row,$curlevel);
        //$tab['sub']  = $this->getArboOrdered($row['rubrique_id'],$maxlevel,$curlevel+1);
        if ($res = showSubRubs($row)) {
            if (is_array($res)) {
                $tab = getArboOrdered($row['rubrique_id'], $maxlevel, $curlevel + 1, $tab, $res);
            } else {
                $tab = getArboOrdered($row['rubrique_id'], $maxlevel, $curlevel + 1, $tab);
            }
        }
        //}
    }
    return $tab;
}

function addRowToTab($row, $curlevel)
{
    return array('id' => $row['rubrique_id'], 'titre' => $row['rubrique_titre_' . LG_DEF], 'type' => $row['rubrique_type'], 'level' => $curlevel);
}

function getListingRubrique()
{

    $tab = getArboOrdered();

    foreach ($tab as $k => $v) {
        $tab[$k]['rubrique_titre_' . LG()] = str_repeat('&nbsp;&nbsp;&nbsp;', $v['level'] - 1) . ' ' . $v['rubrique_titre_' . LG()];
    }

    return $tab;
}

function backupDbOld()
{
    $backup = new MySQLDump();
    $backup->connect('localhost', 'user', 'lasergun', 'hercules');
    if (!$backup->connected) {
        die('Error: ' . $backup->mysql_error);
    }
    //get all tables in db
    $backup->list_tables();

    //reset buffer
    $buffer = '';

    //go through all tables and dump them to buffer
    foreach ($backup->tables as $table) {

        // $backup->dump_table($table);
        //$buffer .= $backup->output;
        $backup->output = "";
        $backup->list_values($table);
        $buffer .= $backup->output;
    }

    ob_clean();


    header('Content-type: application/force-download;charset=iso8859-1;');
    header('Content-disposition:attachment;filename=export.sql;');

    //echo '<pre>';
    echo $buffer;
    die();
    //echo '</pre>';
}

global $_Gconfig;
$_Gconfig['globalActions'][] = 'backupDb';

function backupDbOld2()
{

    global $_bdd_user, $_bdd_host, $_bdd_pwd, $_bdd_bdd;
    @include(INCLUDE_PATH . '/config/config.server.php');
    //MySQL connection parameters
    $dbhost = $_bdd_host;
    $dbuser = $_bdd_user;
    $dbpsw = $_bdd_pwd;
    $dbname = $_bdd_bdd;

    //Connects to mysql server
    $connessione = @mysql_connect($dbhost, $dbuser, $dbpsw);

    //Includes class

    ob_clean();
//Creates a new instance of MySQLDump: it exports a compressed and base-16 file
    $dumper = new MySQLDump($dbname, 'filename.sql', false, false);

//Use this for plain text and not compressed file
//$dumper = new MySQLDump($dbname,'filename.sql',false,false);
//Dumps all the database
    echo $dumper->doDump();
    die();
}

function backupDb()
{
    global $_bdd_user, $_bdd_host, $_bdd_pwd, $_bdd_bdd;
    ob_clean();
    $file = nicename(t('base_title') . '-database-' . nicename(date('r')) . '.sql');
    header("Content-Type: application/force-download; name=\"" . basename($file) . "\"");
    header("Content-Transfer-Encoding: binary");
    header("Content-Disposition: attachment; filename=\"" . basename($file) . "\"");
    header("Expires: 0");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    passthru('mysqldump -u ' . escapeshellarg($_bdd_user) . ' --password="' . ($_bdd_pwd) . '" -h ' . escapeshellarg($_bdd_host) . ' -B ' . escapeshellarg($_bdd_bdd));
    die();


    echo include($GLOBALS['gb_obj']->getIncludePath('config.server.php', 'config'));

    //MySQL connection parameters
    $dbhost = $_bdd_host;
    $dbuser = $_bdd_user;
    $dbpsw = $_bdd_pwd;
    $dbname = $_bdd_bdd;


    $nodata = false;      #!DO NOT DUMP TABLES DATA
    $nostruct = false;      #!DO NOT DUMP TABLES STRUCTURE
    $gzip = false;      #!DO GZIP OUTPUT
    ob_clean();
    $link = mysql_connect("$_bdd_host", $dbuser, "$_bdd_pwd", false, MYSQL_CLIENT_COMPRESS);
    //require_once(getcwd()."/class_mysqldump.php");

    $dump = new MySQLDump();
    $dump->omitDataTables[] = 's_log_action';
    $dump->omitDataTables[] = 'os_obj';
    $dump->omitDataTables[] = 'os_recherches';
    $dump->omitDataTables[] = 'os_rel';

    $dump->omitDataTables[] = 'os_word';
    $dbdata = $dump->dumpDatabase($_bdd_bdd, $nodata, $nostruct);
    mysql_close($link);
    if ($gzip == false) {
        $dump->sendAttachFile($dbdata, 'text/html', 'sql_dump.sql');
    } else {
        $dump->sendAttachFileGzip($dbdata, 'sql_dump.sql.gz');
    }

    die();
}

/**
 * Dump data from MySQL database
 *
 * @name    MySQLDump
 * @author  Sergey Shilko <imp_on@softhome.net>,
 *          based on code by Marcus Vinícius
 * @version 1.1 2007-04-20
 * @example
 *
 * $dump = new MySQLDump();
 * print $dump->dumpDatabase("mydb",false,false);
 *
 */
@set_time_limit(720); #720sec
session_cache_expire(720);   #720 min expire

class MySQLDump
{

    public $omitDataTables = array();

    /**
     * Dump data and structure from MySQL database
     *
     * @param string $database
     * @return string
     */
    function dumpDatabase($database, $nodata = false, $nostruct = false)
    {

        // Set content-type and charset
        #header ('Content-Type: text/html; charset=iso-8859-1');
        // Connect to database
        $db = @mysql_select_db($database);

        @ini_set('memory_limit', '128M');

        if (!empty($db)) {

            // Get all table names from database
            $c = 0;
            $result = mysql_list_tables($database);
            for ($x = 0; $x < mysql_num_rows($result); $x++) {
                $table = mysql_tablename($result, $x);
                if (!empty($table)) {
                    $arr_tables[$c] = mysql_tablename($result, $x);
                    $c++;
                }
            }
            // List tables
            $dump = '';

            $dump .= "-- \n";
            //   $dump .= '-- MySQL DATABASE DUMPER. Copyright Sergey Shilko &reg;\n\n 2007'."\n";
            $dump .= "-- \n\n";
            $structure = '';
            $AA = '';
            $data = '';
            for ($y = 0; $y < count($arr_tables); $y++) {

                // DB Table name
                $table = $arr_tables[$y];
                if ($nostruct == false) {

                    // Structure Header
                    $structure .= "-- ------------------------------------------------ \n";
                    $structure .= "-- Table structure for table `{$table}` started >>> \n";

                    // Dump Structure
                    $structure .= "DROP TABLE IF EXISTS `{$table}`; \n";
                    $structure .= "CREATE TABLE `{$table}` (\n";
                    $result = mysql_db_query($database, "SHOW FIELDS FROM `{$table}`");
                    while ($row = mysql_fetch_object($result)) {

                        $structure .= "  `{$row->Field}` {$row->Type}";
                        if ($row->Default != 'CURRENT_TIMESTAMP') {
                            $structure .= (!empty($row->Default)) ? " DEFAULT '{$row->Default}'" : false;
                        } else {
                            $structure .= (!empty($row->Default)) ? " DEFAULT {$row->Default}" : false;
                        }
                        $structure .= ($row->Null != "YES") ? " NOT NULL" : false;
                        if ($row->Null == "YES") {
                            $NULLS[$row->Field] = $row->Field;
                        } else {

                        }


                        $structure .= (!empty($row->Extra)) ? " {$row->Extra}" : false;
                        $structure .= ",\n";
                    }

                    $structure = ereg_replace(",\n$", "", $structure);

                    // Save all Column Indexes in array
                    unset($index);
                    $result = mysql_db_query($database, "SHOW KEYS FROM `{$table}`");
                    while ($row = mysql_fetch_object($result)) {

                        if (($row->Key_name == 'PRIMARY') AND ($row->Index_type == 'BTREE')) {
                            $index['PRIMARY'][$row->Key_name][] = $row->Column_name;
                            /*
                              if($index['PRIMARY'][$row->Key_name])
                              $index['PRIMARY'][$row->Key_name] = array($index['PRIMARY'][$row->Key_name],$row->Column_name);
                              else
                              $index['PRIMARY'][$row->Key_name] = $row->Column_name;
                             */
                        }

                        if (($row->Key_name != 'PRIMARY') AND ($row->Non_unique == '0') AND ($row->Index_type == 'BTREE')) {
                            $index['UNIQUE'][$row->Key_name] = $row->Column_name;
                        }

                        if (($row->Key_name != 'PRIMARY') AND ($row->Non_unique == '1') AND ($row->Index_type == 'BTREE')) {
                            $index['INDEX'][$row->Key_name] = $row->Column_name;
                        }

                        if (($row->Key_name != 'PRIMARY') AND ($row->Non_unique == '1') AND ($row->Index_type == 'FULLTEXT')) {
                            $index['FULLTEXT'][$row->Key_name] = $row->Column_name;
                        }
                    }


                    // Return all Column Indexes of array
                    if (is_array($index)) {
                        foreach ($index as $xy => $columns) {

                            $structure .= ",\n";

                            $c = 0;
                            foreach ($columns as $column_key => $column_name) {

                                $c++;

                                if (is_array($column_name)) {
                                    $column_name = implode(',', $column_name);
                                }
                                $AA .= $structure .= ($xy == "PRIMARY") ? "  PRIMARY KEY  (" . $column_name . ")" : false;
                                $structure .= ($xy == "UNIQUE") ? "  UNIQUE KEY `{$column_key}` (`{$column_name}`)" : false;
                                $structure .= ($xy == "INDEX") ? "  KEY `{$column_key}` (`{$column_name}`)" : false;
                                $structure .= ($xy == "FULLTEXT") ? "  FULLTEXT `{$column_key}` (`{$column_name}`)" : false;

                                $structure .= ($c < (count($index[$xy]))) ? ",\n" : false;
                            }
                        }
                    }

                    $structure .= "\n);\n\n";
                    $structure .= "-- Table structure for table `{$table}` finished <<< \n";
                    $structure .= "-- ------------------------------------------------- \n";
                }


                // Dump data
                if ($nodata == false && !in_array($table, $this->omitDataTables)) {

                    $structure .= " \n\n";

                    $result = mysql_query("SELECT * FROM `$table`");
                    $num_rows = mysql_num_rows($result);
                    $num_fields = mysql_num_fields($result);

                    $data .= "-- -------------------------------------------- \n";
                    $data .= "-- Dumping data for table `$table` started >>> \n";

                    for ($i = 0; $i < $num_rows; $i++) {

                        $row = mysql_fetch_object($result);
                        $data .= "INSERT INTO `$table` (";

                        // Field names
                        for ($x = 0; $x < $num_fields; $x++) {

                            $field_name = mysql_field_name($result, $x);

                            $data .= "`{$field_name}`";
                            $data .= ($x < ($num_fields - 1)) ? ", " : false;
                        }

                        $data .= ") VALUES (";

                        // Values
                        for ($x = 0; $x < $num_fields; $x++) {
                            $field_name = mysql_field_name($result, $x);

                            if ($NULLS[$field_name] && $row->$field_name == "") {
                                $data .= "NULL";
                            } else {
                                $data .= "'" . str_replace('\"', '"', mysql_escape_string($row->$field_name)) . "'";
                            }
                            $data .= ($x < ($num_fields - 1)) ? ", " : false;
                        }

                        $data .= ");\n";
                    }
                    $data .= "-- Dumping data for table `$table` finished <<< \n";
                    $data .= "-- -------------------------------------------- \n\n";

                    $data .= "\n";
                }
            }
            $dump .= $structure . $data;
        }

        return $dump;
    }

    function sendAttachFile($data, $contenttype = 'text/html', $filename = 'mysqldump.sql', $write = false)
    {
        if ($write) {
            $path = getcwd();
            $handle = fopen($path . '/' . date('mdY') . "$filename", 'w');
            fwrite($handle, $data);
            fclose($handle);
        }
        header("Content-type: $contenttype");
        header("Content-Disposition: attachment; filename=" . date('mdY') . $filename);
        header('Content-length:' . mb_strlen($data));
        echo($data);
    }

    function sendAttachFileGzip($data, $filename = 'mysqldump.sql.gz')
    {
        $path = getcwd();
        $data = gzencode($data, 9);
        $handle = fopen($path . '/' . date('mdY') . "$filename", 'w');
        fwrite($handle, $data);
        fclose($handle);
        header("Content-type: application/x-gzip");
        header("Content-Disposition: attachment; filename=" . date('mdY') . $filename);
        echo($data);
    }

}

/**
 * Remet tous les champs de langue à la meilleure valeur trouvée
 *
 */
function setAllUrls()
{
    global $_Gconfig;

    if ($_GET['langue_source']) {

        foreach ($_Gconfig['LANGUAGES'] as $v) {
            if ($v != $_GET['langue_source']) {
                $sql = 'UPDATE s_rubrique SET rubrique_url_' . $v . ' = rubrique_url_' . $_GET['langue_source'] . ' WHERE rubrique_url_' . $v . ' = "" ';
                $res = DoSql($sql);
                echo "<h1>" . $v . ' : ' . $sql . '</h1>';
            }
        }
    }

    $f = new simpleForm('', 'get', '');
    $f->add('hidden', 'setAllUrls', '', 'globalAction');

    $langues_sources = array();
    foreach ($_Gconfig['LANGUAGES'] as $v) {
        $langues_sources[$v] = $v;
    }
    $f->add('select', $langues_sources, t('langue_source'), 'langue_source');
    //$f->add('select', $_Gconfig['LANGUAGES'], t('langue_source'), 'langue_source');

    $f->add('submit', t('submit'));

    echo $f->gen();
}

function getPicto($nom, $taille = "32x32")
{
    global $tabForms;
    if (!$nom) {
        return;
    }
    $p = '';
    if (!empty($tabForms[$nom]['picto'])) {
        $p =  $tabForms[$nom]['picto'];
    } else if (tradExists('cp_picto_'.$nom)) {
        $p = t('cp_picto_'.$nom);
    } else if (tradExists($nom)) {
        $p = t($nom);
    }
    
    $folder = ADMIN_PICTOS_FOLDER;
    $pos = strpos($p, ADMIN_PICTOS_FOLDER);
    if ($pos !== false) {
        $p = substr($p, $pos + strlen(ADMIN_PICTOS_FOLDER) + 5);
    } else if( $pos = strpos($p, ADMIN_PICTOS_FOLDER2) !== false) {
         $t = explode('/',substr($p, $pos + strlen(ADMIN_PICTOS_FOLDER2)-1))[0];
         
         $p = substr($p, $pos + strlen(ADMIN_PICTOS_FOLDER2) + 2);
         $folder = ADMIN_PICTOS_FOLDER2;
         $taille = explode('x',$taille);
         $taille = $taille[0];
         $p = str_replace('-'.$t.'.png','-'.$taille.'.png',$p);
    } else {
        $p = $nom;
    }

    if ($p == $nom) {
        $p = 'mimetypes/text-x-generic-template.png';
    }

    $a = path_concat($folder, $taille, $p);

    return $a;
}

function cleanFiles()
{

    global $uploadRep, $specialUpload;


    if (isset($_POST['todelfi'])) {
        foreach ($_POST['todelfi'] as $v) {
            unlink($v);
        }
        return;
    }

    $tbs = getTables();

    echo '<form id="todels" method="post" action="index.php" >
			<input type="hidden" name="globalAction" value="cleanFiles" />';
    $useless = 0;
    $totf = 0;
    $delGet = akev($_REQUEST,'del');
    if ($tables = opendir('../' . $uploadRep)) {

        while (false !== ($table = readdir($tables))) {

            if ($table != '.' && $table != '..' && is_dir('../' . $uploadRep . '/' . $table) && empty($specialUpload[$table]) && in_array($table, $tbs)) {

                $handle = opendir('../' . $uploadRep . '/' . $table);
                while (false !== ($file = readdir($handle))) {
                    if ($file != "." && $file != "..") {
                        if (is_dir('../' . $uploadRep . '/' . $table . '/' . $file)) {
                            $res = getSingle('SELECT * FROM ' . $table . ' WHERE ' . getPrimaryKey($table) . ' = ' . sql($file) . '');

                            if ($res && count($res)) {

                                //echo "<font color=green>$table/$file</font><br/>";
                            } else {
                                $useless++;
                                $r = @rmdir('../' . $uploadRep . '/' . $table . '/' . $file);
                                if ($r) {
                                    //$poidsuseful += filesize();
                                    echo "<font color=orange>$table/$file</font><br/>";
                                } else {
                                    echo "<font color=red>$table/$file</font><br/>";
                                    $subs = opendir('../' . $uploadRep . '/' . $table . '/' . $file);
                                    while (false !== ($sub = readdir($subs))) {
                                        $fi = '../' . $uploadRep . '/' . $table . '/' . $file . '/' . $sub;
                                        if (is_file($fi)) {
                                            $f = filesize($fi);

                                            $m = md5($fi);
                                            if ($m == $delGet) {
                                                unlink($fi);
                                            } else {
                                                $totf += $f;
                                                echo ' - <a target="_blank" href="' . $table . '/' . $file . '/' . $sub . '">' . $sub . ' [' . pretty_bytes($f) . '] </a> [<a href="?globalAction=cleanFiles&amp;del=' . $m . '">X</a>] <input type="checkbox" name="todelfi[]" value="' . $fi . '" /><br/>';
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            //echo $file;
                        }
                    }
                }
                closedir($handle);
            }
        }
        closedir($tables);
    }
    echo '<input type="submit" value="Delete unused files" /></form><hr/>';
    echo 'Fichiers inutilisés : ' . $useless . ' / Totalisants : ';
    echo '  ' . pretty_bytes($totf);
    echo '<hr/><a class="button" onclick="$(\'#todels input\').attr(\'checked\',\'checked\')" >Tout sélectionner</a> | <a class="button" onclick="$(\'#todels input\').attr(\'checked\',false)">Tout déselectionner</a>';
}

function autoGeocodeAllFields()
{
    global $_Gconfig, $co;

    if ($_REQUEST['table'] && count($_REQUEST['table'])) {

        foreach ($_REQUEST['table'] as $table) {

            $code = $_Gconfig['mapsFields'][$table];
            foreach ($code as $chps) {

                if (!$chps) {
                    continue;
                }
                $chp_lat = array_shift($chps);
                $chp_lng = array_shift($chps);

                $chps = $chps[0];

                $sql = 'SELECT * FROM ' . $table . ' WHERE  ';
                if ($_REQUEST['geocode_mode'] == 'empty') {
                    $sql .= '  ' . $chp_lat . ' = 0 AND ' . $chp_lng . ' = 0 ';
                } else {
                    $sql .= ' 1 ';
                }
                $sql .= ' ORDER BY RAND()  ';
                $res = GetAll($sql);

                echo '<table class="genform_table"><caption>' . t($table) . '</caption>';
                foreach ($res as $row) {
                    $r = new row($table, $row);
                    echo '<tbody><tr><th><a target="_blank" href="?curTable=' . $table . '&curId=' . $r->id . '">' . $r->id . '</a></th>';
                    $v = '';
                    foreach ($chps as $chp) {
                        $val = $r->{$chp};
                        if ($val === false) {
                            $val = $chp;
                        }
                        $v .= $val . ' ';
                        echo '<td>' . $val . '</td>';
                    }

                    usleep(250000);
                    $res = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=' . urlencode($v));
                    $res = json_decode($res);
                    if ($res->status == 'OK') {
                        $lat = $res->results[0]->geometry->location->lat;
                        $lng = $res->results[0]->geometry->location->lng;
                        echo '<td>' . $res->status . '</td><td><a target="_blank" href="http://maps.google.com/?q=(' . $lat . ',' . $lng . ')">' . $lat . ',' . $lng . '</a></td>';
                        DoSql('UPDATE ' . $table . ' SET ' . $chp_lat . ' = ' . sql($lat) . ' , ' . $chp_lng . ' = ' . sql($lng) . ' WHERE ' . getPrimaryKey($table) . ' = ' . $r->id);
                    } else {
                        echo '<td style="background:red;color:white;font-weight:bold">' . $res->status . '</td>';
                    }
                    echo '</tr></tbody>';
                    ob_flush();
                    flush();
                }
                echo '</table>';
            }
        }
    } else {
        echo '<h2>' . ta('choisissez_la_table_a_geocoder') . '</h2>';
        echo '<form method="get" action="index.php" ><input type="hidden" name="globalAction" value="autoGeocodeAllFields" />';
        echo '<label for="geocode_mode">
                <input type="radio" id="geocode_mode_all" name="geocode_mode" value="all" />
                ' . ta('geocode_mode_all') . '</label><br/>';
        echo '<label for="geocode_mode">
                <input type="radio" id="geocode_mode_empty" checked="checked" name="geocode_mode" value="empty" />
                ' . ta('geocode_mode_empty') . '</label>';
        echo '<ul>';
        foreach ($_Gconfig['mapsFields'] as $k => $v) {
            echo '<li><label for="table_' . $k . '" >
                        <input type="checkbox" name="table[]" id="table_' . $k . '" value="' . $k . '" /> ' . t($k) . '</label>
                         </li>';
        }
        echo '</ul>
                <input type="submit" />
';
    }
}

function updateDatabase($cli=false)
{

    $plugins = GetPlugins();
    global $co;
    if($cli) {
        echo "\n"."# Updates"."\n";
    }
    $nbTot = $nbDone = 0;
    foreach ($plugins as $plugin) {
        $upPl = path_concat(INCLUDE_PATH, 'plugins', $plugin, '_updates');
        if (file_exists($upPl)) {
            $row = getRowFromId('s_plugin', $plugin);
            $updates = explode(',', $row['plugin_updates']);
            $res = glob(path_concat($upPl, '*.{php,sql}'), GLOB_BRACE);

            if(!$cli) {
                echo '<h2>' . $plugin . '</h2>';
            }
            foreach ($res as $file) {
                $filename = basename($file);
                $parse = explode('_', $filename);
                $nbTot++;

                if (!in_array($parse[0], $updates)  || $file == akev($_GET,'redo') ) {
                    $nbDone++;
                    if(!$cli) {
                        echo '<div class="alert alert-info">A FAIRE : ' . $file . '<br/>';
                    } else {
                        echo "\n".' * '.$plugin.' : '.$file.''."\n";
                    }
                    if (strstr($file, '.sql') !== false) {
                        $res = importSqlFile($file);
                        foreach ($res as $sql) {
                            DoSql($sql);
                        }
                    } else {
                        include($file);
                    }
                    if(!$cli) {
                        echo '</div>';
                    }
                    $updates[] = $parse[0];
                } else {
                    
                    if(!$cli) {
                        echo '<div class="alert alert-success">FAIT ' . $file . ' <a href="?globalAction=updateDatabase&redo=' . $file . '">🔄</a><br/>';
                        echo '</div>';
                    }
                }
            }
            $co->autoExecute('s_plugin', array('plugin_updates' => implode(',', $updates)), 'UPDATE', 'plugin_nom=' . sql($plugin));
        }
    }
    if($cli) {
        echo "\n"." * *  ".$nbDone." updates to be done / ".$nbTot;
    }
}

function getAdminLink($menu)
{
    $tables = getTables();
    if (in_array($menu, $tables)) {
        return 'index.php?curTable=' . $menu;
    } else if (tradExists('cp_link_' . $menu)) {
        return ta('cp_link_' . $menu);
    } else if (strstr($menu, '/')) {
        $m = explode('/', $menu);
        return '?curTable=' . $m[0] . '&relOne=' . $m[1];
    } else {

        return 'index.php?userAction=' . $menu;
    }
}


function createAndInstallPlugin($nom)
{
    $p = array('plugin_nom' => $nom);
    global $co;
    $co->autoExecute('s_plugin', $p, 'INSERT');
    $a = new genAction('installPlugin', 's_plugin', $nom);
    $a->doit();
}
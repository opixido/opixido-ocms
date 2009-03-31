<?



/* AS USUAL ... IE NEEDS A FIX ....*/
if(count($_POST)) {
reset($_POST);
while(list($k,$v) = each($_POST)) {
        if(substr($k,-2) == "_x" || substr($k,-2) == "_y") {
                $_POST[substr($k,0,-2)] = $v;
        }
}
reset($_POST);
}



function getNomForOrder($titre) {
	

	if ( is_array( $titre ) ) {
            reset( $titre );

            $nomSee = $titre[0];
        } else {
            $nomSee = $titre;
        }

        return $nomSee;
}



function updateParam($nom,$val) {
	$sql = 'REPLACE s_param SET param_valeur = "'.mes($val).'",  param_id = "'.$nom.'" ';
	DoSql($sql);
	
}

if(array_key_exists('editTrads',$_GET)) {
	$_SESSION['editTrads'] = $_GET['editTrads'];
}

function getEditTrad($nom) {
	
	if($_SESSION['editTrads']) {
		$html .= '<a href="javascript:;" onclick="gid(\'ET_'.$nom.'\').style.display=\'inline\'" >+</a> <input id="ET_'.$nom.'" type="text" name="ET_'.$nom.'" value='.alt(t($nom)).' style="display:none" onchange="XHR_editTrad(this)" />';
		return $html;
	}
	
	
}



function getTableListing($table) {
	
	global $_Gconfig;
	
	$liste = $_Gconfig['specialListing'][$table];
	
	if($liste) {
		
		return $_Gconfig['specialListing'][$table]();
		
	}
	
	else {
		
		$sql = 'SELECT G.* FROM '.$table.' AS G WHERE 1 ';
		
		$nomSql = GetTitleFromTable($table,' , ');
		
		if(in_array($table,$_Gconfig['versionedTable'])) {
			
			$sql .= ' AND(  '.VERSION_FIELD.' = ""  OR  '.VERSION_FIELD.' IS NULL  )';
			
		}
		
		$sql .= 'ORDER BY ' . $nomSql;
		$result = GetAll( $sql );
		
		return $result;
		
	}
	
	
}

/**
 * Retourne la liste des TABFORMS de TITRE pour les placer dans une VALUE
 *
 * @param unknown_type $titre
 * @param unknown_type $row
 * @return unknown
 */
function getNomForValue( $titre, $row )
{
	
	
    if ( is_array( $titre ) ) {
        reset( $titre );
        
        while ( list( , $chp ) = each( $titre ) ) {
        	
            $nomSee .= " " . $row[$chp] . "";
        }
        // $nomSee = substr($nomSee,0,-2);
    } else {
        $nomSee = $row[$titre];
    }

    return $nomSee;
}


function tradAdmin($txt,$rel,$table='') {
		/* Real name forced : TABLE_NAME.FIELD_NAME */

       // $t1 = t( $this->table_name . '.' . $txt );
        $prefix = str_replace('t_','',$table);

        $txt_sans_table = str_replace($prefix.'_','',$txt);

        $intt = strpos( $txt, '_' );

        $t3 = $intt ? str_replace( '_', ' ', ucfirst( t( substr( $txt, $intt + 1 ) ) ) ) : $txt;

        if(strstr($txt,$table.'_p_')) {
        	if(!tradExists($txt)) {
        		$txt = str_replace($table.'_p_','A_page_',$txt);
        	}
        }

		$tradsInOrder = array($table . '.' . $txt ,
					$txt . '.' . $table ,
					$txt,
					$txt_sans_table,
					$t3
					);
	
		foreach($tradsInOrder as $v) {
			if(tradExists($v))
				return t($v);
		}
		return t($txt);

        return $t3;
        
}
    
    
    
function rubriqueIsAPage($rubtype) {
	if(is_object($rubtype))
		$rubtype = $rubtype->tab_default_field['rubrique_type'];
	return in_array($rubtype,array('siteroot','page'));
}



function GetTitleFromTableOLD($table,$separator=" ") {
   global $tabForms;
	$fields = getTabField($table);
	
	/**
	 * Si on a plusieurs champs titre
	 */
   if(!is_array($tabForms[$table]['titre'])) {
   		$tabForms[$table]['titre'] = array($tabForms[$table]['titre']);
   }
   	
   		/**
   		 * On parcourt tous les champs
   		 */
		foreach($tabForms[$table]['titre'] as $k=>$v) {
			
			/**
			 * On ne met le séparateur qu'à partir du second
			 */
			$sep = $k ==0 ? '' :$separator;
			
			/**
			 * Si le champ existe c'est un champ normal
			 */
			if($fields[$v]) {
				$titre .= $sep.''.$v;
				
			/**
			 * sinon c'est un champ de langue
			 */
			} else {
				/**
				 * On parcourt toutes les langues
				 */
				global $_Gconfig;
				foreach($_Gconfig['LANGUAGES'] as $lg) {
					$titre .= $sep.''.$v.'_'.$lg;
					$sep = $separator;
				}				
			}
		}
    //$titre = implode($separator,$tabForms[$table]['titre']);
    /*
    } else {
    	$titre = $row[$tabForms[$table]['titre']];
    	$v = $titre;
    	if($fields[$v]) {
			$titre .= $separator.''.$v;
		} else {
			$titre .= $separator.''.$v.'_'.ADMIN_LG_DEF;
			if(ADMIN_LG_DEF != LG) {
				$titre .= $separator.''.$v.'_'.LG;	
			}
		}
            
    }
    */

    return $titre;
}


function truncate($str,$len=100) {
    $sstr = strip_tags($str);
    if(strlen($sstr) > $len)
        return substr($sstr,0,$len)." ...";
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



function GetOnlyEditableVersion($table, $aliase='') {

	global $multiVersionField,$_Gconfig;
	if(strlen($aliase))
			$aliase = $aliase.".";
	if(in_array($table,$_Gconfig['versionedTable'])) {
		
		return ' AND '.$aliase.VERSION_FIELD.' IS NOT NULL ';
	} 
	else if(in_array($table,$_Gconfig['multiVersionTable'])) {
		
		return ' AND '.$aliase.MULTIVERSION_FIELD.' = '.getPrimaryKey($table);
				
	}

}

function mymail($to,$sujet='',$text='',$headers='') {
	mail($to,$sujet,$text,$headers);
}
function sendMails($mails,$mail_tpl,$vars) {

	foreach($mails as $mail) {
		if(is_array($mail)) {
			$mail = $mail['admin_email'];
		}
		mymail($mail,$mail_tpl,implode("\n->",$vars));
	}

}



function isMultiVersion($table) {
	global $_Gconfig;
	return (in_array($table,$_Gconfig['multiVersionTable']));	

}

function tradExists($str,$lg= false) {
	global $admin_trads;
	if($lg) {
		return (array_key_exists($str,$admin_trads) && $admin_trads[$str][$lg] != '');
	} else {
		return array_key_exists($str,$admin_trads);
	}
}


function GetRubTitle($row) {

	return GetTitleFromRow('s_rubrique',$row);

}

function GetRubUrl($row) {
	//return 'index.php?r='.$row['rubrique_id'];
	$gurl = new GenUrl();
	$url = $gurl->buildUrlFromId($row['rubrique_id']).GetParam('fake_folder_action') . '/'.GetParam('action_editer ');
	return $url;
}


function GetCurrentLogin() {
	global $gs_obj;

	return $gs_obj->adminnom;
}


if(!function_exists("ta")) {
	function ta($nom) {
	
	        global $frontAdminTrads,$admin_trads;
	
	       // if(!is_array($atrads)) {
        	$trads = $admin_trads;
	        //}
	
	        
	      //  $admin = '[<a href=?curId=new&curTable=s_admin_trad&genform_default__admin_trad_id='.$nom.'>+</a>]';
	        if($trads[$nom][LG]) {
	                return $trads[$nom][LG];
	        } 
	        else if($trads[$nom][LG_DEF]) {
	        	 return $trads[$nom][LG_DEF];
	
	        } else if(!strstr($nom,".")) {
	
	            $t = explode("_",$nom);
	            $text = "";
	            $GLOBALS['MISSINGS'][$nom] = true;
	            if(count($t) > 1) {
	                for($p=1;$p<count($t);$p++) {
	                    $text .= ucfirst($t[$p])." ";
	                }
	                return $admin.$text;
	            } else {
	                return $admin.ucfirst($nom);
	            }
	       }
	       else {
	       		//$GLOBALS['MISSINGS'][] = $nom;
	       	
	            return $nom;
	       }
	
	}
}

function decodePassword($str) {
	global $gs_obj;
	return $gs_obj->decrypt($str);
	return $str;
}



function encodePassword($str) {
	global $gs_obj;
	return $gs_obj->encrypt($str);
	return $str;
}

function importSqlFile($FILENAME) {
					
					
			$linespersession = 300000000;   // Lines to be executed per one import session
			$delaypersession = 0;      // You can specify a sleep time in milliseconds after each session
			                           // Works only if JavaScript is activated. Use to reduce server overrun
			
			// Allowed comment delimiters: lines starting with these strings will be dropped by BigDump
			
			$comment[]='#';           // Standard comment lines are dropped by default
			$comment[]='-- ';
			// $comment[]='---';      // Uncomment this line if using proprietary dump created by outdated mysqldump
			// $comment[]='/*!';         // Or add your own string to leave out other proprietary things
			
			
			// Connection character set should be the same as the dump file character set (utf8, latin1, cp1251, koi8r etc.)
			// See http://dev.mysql.com/doc/refman/5.0/en/charset-charsets.html for the full list
			
			$db_connection_charset = '';
			
			
			// *******************************************************************************************
			// If not familiar with PHP please don't change anything below this line
			// *******************************************************************************************
			
			define ('VERSION','0.27b');
			define ('DATA_CHUNK_LENGTH',16384);  // How many chars are read per time
			define ('MAX_QUERY_LINES',300);      // How many lines may be considered to be one query (except text lines)
			define ('TESTMODE',false);           // Set to true to process the file without actually accessing the database
			
			$file = fopen($FILENAME,'r');
			
			
			@ini_set('auto_detect_line_endings', true);
			@set_time_limit(0);
			
			
			
			// ****************************************************
			// START IMPORT SESSION HERE
			// ****************************************************
			$_REQUEST["start"] = 0;
			$_REQUEST["foffset"] = 0;
			$_REQUEST["fn"] = $FILENAME;
			if (!$error && isset($_REQUEST["start"]) && isset($_REQUEST["foffset"]) && eregi("(\.(sql|gz))$",$_REQUEST["fn"]))
			{
			
			
			
			$gzipmode = false;
			
			// Start processing queries from $file
			
			  if (!$error)
			  { $query="";
			    $queries=0;
			    $totalqueries=$_REQUEST["totalqueries"];
			    $linenumber=$_REQUEST["start"];
			    $querylines=0;
			    $inparents=false;
			
			// Stay processing as long as the $linespersession is not reached or the query is still incomplete
			
			    while ($linenumber<$_REQUEST["start"]+$linespersession || $query!="")
			    { 
			
			// Read the whole next line
			
			      $dumpline = "";
			      while (!feof($file) && substr ($dumpline, -1) != "\n") 
			      { if (!$gzipmode)
			          $dumpline .= fgets($file, DATA_CHUNK_LENGTH);
			        else
			          $dumpline .= gzgets($file, DATA_CHUNK_LENGTH);
			      }
			      if ($dumpline==="") break;
			      
			// Handle DOS and Mac encoded linebreaks (I don't know if it will work on Win32 or Mac Servers)
			
			      $dumpline=ereg_replace("\r\n$", "\n", $dumpline);
			      $dumpline=ereg_replace("\r$", "\n", $dumpline);
			      
			// DIAGNOSTIC
			// echo ("<p>Line $linenumber: $dumpline</p>\n");
			
			// Skip comments and blank lines only if NOT in parents
			
			      if (!$inparents)
			      { $skipline=false;
			        reset($comment);
			        foreach ($comment as $comment_value)
			        { if (!$inparents && (trim($dumpline)=="" || strpos ($dumpline, $comment_value) === 0))
			          { $skipline=true;
			            break;
			          }
			        }
			        if ($skipline)
			        { $linenumber++;
			          continue;
			        }
			      }
			
			// Remove double back-slashes from the dumpline prior to count the quotes ('\\' can only be within strings)
			      
			      $dumpline_deslashed = str_replace ("\\\\","",$dumpline);
			
			// Count ' and \' in the dumpline to avoid query break within a text field ending by ;
			// Please don't use double quotes ('"')to surround strings, it wont work
			
			      $parents=substr_count ($dumpline_deslashed, "'")-substr_count ($dumpline_deslashed, "\\'");
			      if ($parents % 2 != 0)
			        $inparents=!$inparents;
			
			// Add the line to query
			
			      $query .= $dumpline;
			
			// Don't count the line if in parents (text fields may include unlimited linebreaks)
			      
			      if (!$inparents)
			        $querylines++;
			      
			// Stop if query contains more lines as defined by MAX_QUERY_LINES
			
			      if ($querylines>MAX_QUERY_LINES)
			      {
			        echo ("<p class=\"error\">Stopped at the line $linenumber. </p>");
			        echo ("<p>At this place the current query includes more than ".MAX_QUERY_LINES." dump lines. That can happen if your dump file was ");
			        echo ("created by some tool which doesn't place a semicolon followed by a linebreak at the end of each query, or if your dump contains ");
			        echo ("extended inserts. Please read the BigDump FAQs for more infos.</p>\n");
			        $error=true;
			        break;
			      }
			
			// Execute query if end of query detected (; as last character) AND NOT in parents
			
			      if (ereg(";$",trim($dumpline)) && !$inparents)
			      { 
			      	
			      	$QUERIESTODO[] = str_replace('[LG]',(defined('LG_TEMP') ? LG_TEMP : LG_DEF),$query);
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
			        $query="";
			        $querylines=0;
			      }
			      $linenumber++;
			    }
			  }
			
			// Get the current file position
			
			  if (!$error)
			  { if (!$gzipmode) 
			      $foffset = ftell($file);
			    else
			      $foffset = gztell($file);
			    if (!$foffset)
			    { echo ("<p class=\"error\">UNEXPECTED: Can't read the file pointer offset</p>\n");
			      $error=true;
			    }
			  }
			}
			
			return $QUERIESTODO;
		
	}
	
	
	
	
	 function getArboOrdered($start='NULL',$maxlevel=99999,$curlevel=0,$tab=array()) {
		
		if($curlevel == 0) {
			$r = getRowFromId('s_rubrique',$start);
			if(isRealRubrique($r)) {
				$tab[] = addRowToTab($r,$curlevel);
				$curlevel++;
			}
		}
		
		$sql = 'SELECT * FROM s_rubrique WHERE fk_rubrique_id '.sqlParam($start).' '.sqlRubriqueOnlyReal().' ORDER BY rubrique_ordre ASC ';
		$res = GetAll($sql);
		
		foreach($res as $row) {
			//if($row['rubrique_id'] != $this->row['fk_rubrique_version_id']) { 
				$tab[] = array_merge($row,array('level'=>$curlevel));//addRowToTab($row,$curlevel);
				//$tab['sub']  = $this->getArboOrdered($row['rubrique_id'],$maxlevel,$curlevel+1);
				$tab = getArboOrdered($row['rubrique_id'],$maxlevel,$curlevel+1,$tab);
			//}
		}		
		return $tab;
	}
	
	 function addRowToTab($row,$curlevel) {
		return array('id'=>$row['rubrique_id'],'titre'=>$row['rubrique_titre_'.LG_DEF],'type'=>$row['rubrique_type'],'level'=>$curlevel);
	}	
	
	
	function getListingRubrique() {
		
		$tab = getArboOrdered();
		
		foreach($tab as $k=>$v) {
			$tab[$k]['rubrique_titre_'.LG] = str_repeat('&nbsp;&nbsp;&nbsp;',$v['level']-1).' '.$v['rubrique_titre_'.LG];
		}
		
		return $tab;
		
	}
	
	
	
	
function backupDbOld () {
	$backup = new MySQLDump();
	$backup->connect('localhost','user','lasergun','hercules'); 
	if (!$backup->connected) { die('Error: '.$backup->mysql_error); } 
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


function backupDbOld2() {
	
	global $_bdd_user,$_bdd_host,$_bdd_pwd, $_bdd_bdd;
    //MySQL connection parameters
    $dbhost = $_bdd_host;
    $dbuser = $_bdd_user;
    $dbpsw = $_bdd_pwd;
    $dbname = $_bdd_bdd;
    
    //Connects to mysql server
    $connessione = @mysql_connect($dbhost,$dbuser,$dbpsw);

    //Includes class
 
ob_clean();
//Creates a new instance of MySQLDump: it exports a compressed and base-16 file
$dumper = new MySQLDump($dbname,'filename.sql',false,false);

//Use this for plain text and not compressed file
//$dumper = new MySQLDump($dbname,'filename.sql',false,false);

//Dumps all the database
echo $dumper->doDump(); 
die();

}


function backupDb() {
	
	global $_bdd_user,$_bdd_host,$_bdd_pwd, $_bdd_bdd;
    //MySQL connection parameters
    $dbhost = $_bdd_host;
    $dbuser = $_bdd_user;
    $dbpsw = $_bdd_pwd;
    $dbname = $_bdd_bdd;
	
	$nodata   = false;      #!DO NOT DUMP TABLES DATA
	$nostruct = false;      #!DO NOT DUMP TABLES STRUCTURE
	$gzip     = false;      #!DO GZIP OUTPUT
	ob_clean();
	$link = mysql_connect("$_bdd_host", $dbuser, "$_bdd_pwd",false,MYSQL_CLIENT_COMPRESS);
	//require_once(getcwd()."/class_mysqldump.php");
	
	$dump = new MySQLDump();
	$dbdata =  $dump->dumpDatabase($_bdd_bdd,$nodata,$nostruct);
	mysql_close($link);
	if($gzip == false){
	$dump->sendAttachFile($dbdata,'text/html','sql_dump.sql');}else{
	$dump->sendAttachFileGzip($dbdata,'sql_dump.sql.gz');} 
	
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

class MySQLDump {



    /**
     * Dump data and structure from MySQL database
     *
     * @param string $database
     * @return string
     */
    function dumpDatabase($database,$nodata = false,$nostruct = false) {

        // Set content-type and charset
        #header ('Content-Type: text/html; charset=iso-8859-1');

        // Connect to database
        $db = @mysql_select_db($database);

        if (!empty($db)) {

            // Get all table names from database
            $c = 0;
            $result = mysql_list_tables($database);
            for($x = 0; $x < mysql_num_rows($result); $x++) {
                $table = mysql_tablename($result, $x);
                if (!empty($table)) {
                    $arr_tables[$c] = mysql_tablename($result, $x);
                    $c++;
                }
            }
            // List tables
            $dump = '';
            
            $dump .= "-- \n";
            $dump .= '-- MySQL DATABASE DUMPER. Copyright Sergey Shilko &reg;\n\n 2007'."\n";
            $dump .= "-- \n\n";
            
            for ($y = 0; $y < count($arr_tables); $y++){

                // DB Table name
                $table = $arr_tables[$y];
                if($nostruct == false){

                    // Structure Header
                    $structure .= "-- ------------------------------------------------ \n";
                    $structure .= "-- Table structure for table `{$table}` started >>> \n";

                    // Dump Structure
                    $structure .= "DROP TABLE IF EXISTS `{$table}`; \n";
                    $structure .= "CREATE TABLE `{$table}` (\n";
                    $result = mysql_db_query($database, "SHOW FIELDS FROM `{$table}`");
                    while($row = mysql_fetch_object($result)) {

                        $structure .= "  `{$row->Field}` {$row->Type}";
                        if($row->Default != 'CURRENT_TIMESTAMP'){
                            $structure .= (!empty($row->Default)) ? " DEFAULT '{$row->Default}'" : false;
                        }else{
                            $structure .= (!empty($row->Default)) ? " DEFAULT {$row->Default}" : false;
                        }
                        $structure .= ($row->Null != "YES") ? " NOT NULL" : false;
                        if($row->Null == "YES") {
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
                    while($row = mysql_fetch_object($result)) {

                        if (($row->Key_name == 'PRIMARY') AND ($row->Index_type == 'BTREE')) {
                        	$index['PRIMARY'][$row->Key_name][] =  $row->Column_name;
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

                                if(is_array($column_name)) {
                                	$column_name = implode(',',$column_name);
                                }
                                $AA .= $structure .= ($xy == "PRIMARY") ? "  PRIMARY KEY  (".$column_name.")" : false;
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
                if( $nodata == false) {

                $structure .= " \n\n";
                
                    $result     = mysql_query("SELECT * FROM `$table`");
                    $num_rows   = mysql_num_rows($result);
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

                            if($NULLS[$field_name] && $row->$field_name == "")  {
                            	$data .= "NULL";
                            } else {
                            	$data .= "'" . str_replace('\"', '"', mysql_escape_string($row->$field_name)) . "'";
                            }
                            $data .= ($x < ($num_fields - 1)) ? ", " : false;

                        }

                        $data.= ");\n";
                        
                    }
                    $data .= "-- Dumping data for table `$table` finished <<< \n";
                    $data .= "-- -------------------------------------------- \n\n";
                    
                    $data.= "\n";
                }

                
                
            }
            $dump .= $structure . $data;

        }
        
            return $dump;

    }




    function sendAttachFile($data, $contenttype = 'text/html',$filename = 'mysqldump.sql'){
	    $path = getcwd();
	    $handle = fopen($path.'/'.date('mdY')."$filename", 'w');
	    fwrite($handle,$data);
	    fclose($handle);
	
	    header("Content-type: $contenttype");
	    header("Content-Disposition: attachment; filename=".date('mdY').$filename);
	    echo ($data);
    }

    function sendAttachFileGzip($data, $filename = 'mysqldump.sql.gz'){
	    $path = getcwd();
	    $data = gzencode($data, 9);
	    $handle = fopen($path.'/'.date('mdY')."$filename", 'w');
	    fwrite($handle,$data);
	    fclose($handle);
	    header("Content-type: application/x-gzip");
	    header("Content-Disposition: attachment; filename=".date('mdY').$filename);
	    echo($data);
    }

} 

/**
 * Remet tous les champs de langue à la meilleure valeur trouvée
 *
 */
function setAllUrls() {
	global $_Gconfig;
	
	if($_GET['langue_source']) {
		
		foreach($_Gconfig['LANGUAGES'] as $v) {
			if($v != $_GET['langue_source']) {
				$sql = 'UPDATE s_rubrique SET rubrique_url_'.$v.' = rubrique_url_'.$_GET['langue_source'].' WHERE rubrique_url_'.$v.' = "" ';
				$res = DoSql($sql);
				echo "<h1>".$v.' : '.$sql.'</h1>';
			}
		}
		
	}
	
	$f = new simpleForm('','get','');
	$f->add('hidden','setAllUrls','','globalAction');
	
	$f->add('select',$_Gconfig['LANGUAGES'],t('langue_source'),'langue_source');
	
	$f->add('submit',t('submit'));
	
	echo $f->gen();
	
}

?>
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



$GLOBALS['isWindows'] = array_key_exists('OS',$_SERVER) && strpos(strtolower($_SERVER['OS']),'windows') !== false ? true : false;
//$_SESSION['lg'] = ake('lg',$_REQUEST) ? $_REQUEST['lg'] : (ake('lg',$_SESSION) ? $_SESSION['lg'] : 'fr');

/*
 * Variables globales de profiling
 */
$_SESSION['debug'] = ake('debug',$_REQUEST) != "" ? $_REQUEST['debug'] : akev($_SESSION,'debug');
$GLOBALS['timeBDD'] = 0;
$GLOBALS['nbCacheUsed'] = 0;
$GLOBALS['nbCacheTotal'] = 0;


/**
 * Retourne le temps en microsecondes
 *
 * @return float
 */
function getmicrotime(){
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}


/**
 * Debut de génération de la page 
 * A quelques milisecondes pres ...
 */
global $startTime;
$startTime = getmicrotime();


/**
 * Retourne une variable pour inclusion en JS
 *
 * @param unknown_type $str
 * @return unknown
 */
function js($str) {
	return "'".str_replace("'","\\'",$str)."'";
}



/**
 * Configure une table automatiquement
 * 
 * La table doit respecter une nomenclature précise :
 * 
 * - les noms de tables doivent commencer par un préfixe ( s_table, projet_table, plug_table, ...)
 * - Le champ de "label" doit etre le second de la table
 * - Les clefs étrangères ( 1<>N ) doivent commencer par fk_ et finir par le nom de la clef primaire de la table distante
 *   ex : fk_autretable_id pour la table prefixe_autretable avec autretable_id comme PK
 * - Les tables de relations N<>N doivent avoir uniquement deux clefs etrangères formatées comme ci-dessus
 * - idem pour les tables N<>1 
 * 
 * 
 * @param unknown_type $table
 */
function autoConfig($table) {
	
	global $tabForms,$relations,$_Gconfig,$tablerel,$relinv,$tablerel_reverse;
	
	if($tabForms[$table]) {
		return;
	}
	
	$fields = getTabField($table);
	
	
	
	/**
	 * Le titre est le second champ de la table
	 */
	$label = next($fields);
	$tabForms[$table]['titre'] = array(fieldWithoutLg($label->name));
	return;
	
	/**
	 * Prefixe de la table (s_, i_ , blabla_ ...)
	 */
	$prefixe = substr($table,0,strpos($table,'_'));
	
	
	reset($fields);
	$tables = getTables();
	
	/**
	 * Recherche des champs FK
	 */
	foreach($fields as $k=>$v) {
		
		if(substr($k,0,3) == 'fk_') {
			$distTable = $prefixe.'_'.substr($k,3,-3);
			if(in_array($distTable,$tables)) {				
				$relations[$table][$k] = $distTable;				
				autoConfig($distTable);
			}
		}
	}
	
	/**
	 * Nom de la clef etrangère qui pointerait vers la table en cours
	 */
	$myfk = 'fk_'.substr($table,2).'_id';

	foreach($tables as $v) {
		$tabs = getTabField($v);
		if(ake($tabs,$myfk)) {
			/**
			 * Table de relation
			 */
			if(count($tabs) == 2 && !$tablerel[$v]) {
				
				foreach($tabs as $k=>$vv) {
					if($vv->name != $myfk) {
						$distField = $vv->name;
						$distTable = $prefixe.'_'.substr($k,3,-3);						
					}
				}			
				
				
				$tablerel[$v] = array($myfk=>$table,$distField=>$distTable);
				autoConfig($distTable);
				
			/**
			 * Table Relinv
			 */
			} else {
				$relinv[$table]['RELINV_'.$v] = array($v,$myfk);
			}
		}
	}
	
	//$_Gconfig['adminMenus']['auto'][] = $table;	
	
}


/**
 * Alias
 *
 * @param unknown_type $str
 */
function error($str) {
	debug($str);
}

/**
 * Retourne une image via le genfile (alias)
 *
 * @param string $table
 * @param string $champ
 * @param mixed $id
 * @param array $row
 * @return string
 */
function getImg($table,$champ,$id,$row=array()) {

	$f = new GenFile($table,$champ,$id,$row);
	return $f->getWeburl();

}


function getThumb($table,$champ,$id,$row=array(),$w,$h) {

	$f = new GenFile($table,$champ,$id,$row);
	return $f->getThumbUrl($w,$h);

}


function getCrop($table,$champ,$id,$row=array(),$w,$h) {

	$f = new GenFile($table,$champ,$id,$row);
	return $f->getCropUrl($w,$h);

}


/**
 * Retourne $nbwords mots de la chaine $str et concatène "..." si il y en avait encore
 *
 * @param str $str
 * @param int $nbwords
 * @return str
 */
function limitWords($str,$nbwords=30,$tpp = ' ...') {
	$words = explode(' ',$str);
	if(count($words) <= $nbwords) {
		return $str;
	} else {
		return implode(' ',array_slice($words,0,$nbwords)).$tpp;
	}
}

/**
 * Limite le nombre de caractères d'une chaine et rajoute "..."
 *
 * @param string $str
 * @param int $chars
 * @return string
 */
function limit($str,$chars=30) {

	if(mb_strlen($str) <= $chars) {
		return $str;
	} else {
		return mb_substr($str,0,$chars,'UTF8').' ...';
	}
}

/**
 * Permet de savoir où on perd du temps ...
 *
 * @param unknown_type $info
 */
function profile($info='') {
	global $profileTime,$profileSTR;

	
	/*if(!$profileTime)
		$profileTime = getmicrotime();
		*/
	$t = getmicrotime();

	$ar = debug_backtrace();
	$profileSTR .= ($info.' '.basename($ar[0]['file']).' : '.$ar[0]['line'].' :: '.number_format(($t-$profileTime),3).' s :: '.pretty_bytes(memory_get_usage()).'<br/>');

	$profileTime = getmicrotime();
}

global $profileTime;
$profileTime = getmicrotime();


/**
 * Affiche un backtrace pour voir ce qui se passe
 *
 */
function debugtrace() {
	debug(debug_backtrace());
}

/**
 * Verifie la validité d'un email
 *
 * @param string $Email
 * @return boolean
 */
function CheckEmail($Email = "") {

  if(ereg("^[0-9a-z]([-_.~]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,4}$",strtolower($Email)) ){
    return true;
  } else {
    return false;
  }
}

function isEmail($email) {
	return CheckEmail($email);
}

/**
 * Determine si le fichier est une image
 *
 * @param str $str Chemin du fichier
 * @return bool
 */
function isImage($str) {
		global $_Gconfig;
        $s = explode(".",$str);
        $ext = $s[count($s)-1];
        if(in_array(mb_strtolower($ext),$_Gconfig['imageExtensions']))
            return True;
        else
            return False;
}

/**
 * 		Alias pour l'envoi de mail
*/
function sendMail($to,$subject,$message, $headers='') {
	$m = includeMail();
	$m->AddAddress($to);
	$m->Subject = $subject;
	$m->Body = $message;
	$m->HeaderLine($headers);
	
	$m->Send();
	//return mail($to,$subject,$message, $headers);
}


/**
	* 		Transforme un nom de fichier  : nom.du.fichier.pdf
	* 		en : Nom du fichier [PDF]
*/

/**
 * Transforme un nom de fichier en nom un peu plus propre
 * 
 * @example  nom.du.fichier.pdf =>  Nom du fichier [PDF]
 *
 * @param string $str
 * @return string
 */
function systemToNiceName($str) {

	$ext = substr($str,strrpos($str,'.')+1,strlen($str));
	$str = substr($str,0,strrpos($str,'.'));

	$str = ucfirst(str_replace(array(' ','_','.'),array(' ', ' ',' '),$str));

	return $str.' ['.strtoupper($ext).']';
}

$_SESSION['cache_'.UNIQUE_SITE]['adm']['cacheIsAdmin'] = "notChecked";


/**
 * Est-on connecté en tant qu'admin
 *
 * @return unknown
 */
function isLoggedAsAdmin() {

    global $gs_obj;
    if(!is_object($gs_obj)) {
        return false;
    }

    return $gs_obj->checkAuth();

}



function getRealRubId($row) {
	if($row['fk_rubrique_version_id'] != 'NULL' && $row['fk_rubrique_version_id'] != '' ) {
		return $row['fk_rubrique_version_id'];
	} else {
		return $row['fk_rubrique_id'];
	}
}

function getOnlineRubId($row) {
	if($row['fk_rubrique_version_id'] != 'NULL' && $row['fk_rubrique_version_id'] != '' ) {
		return $row['fk_rubrique_version_id'];
	} else {
		return $row['rubrique_id'];
	}
}



/**
 * 	Transforme un nom de fichier de : "Fichier Célio [carte de Françe].pdf"
 * 	En : fichier_celio_carte_de_france.pdf
 * @param str string Nom actuel du fichier
*/
function niceName($str) {
		
		$str = trim(mb_strtolower($str,'utf-8'));
		
		
		$string    = htmlentities($str,ENT_NOQUOTES,'utf-8');
		$string = str_replace('&rsquo;','-',$string);
		$string    = preg_replace("/&(.)(acute|cedil|circ|ring|tilde|uml|grave);/", "$1", $string);
		
		$string    = preg_replace("/([^a-z0-9_]+)/", "-", html_entity_decode($string));
	
		$string    = trim($string);
		$str = str_replace("---","-",$string);
		$str = str_replace("--","-",$str);
		
		return $str;
		
		/*
	$str = strtolower($str);
	$str = str_replace("/","-",$str);
        $str = str_replace(" ","-",$str);
        $str = str_replace("'","-",$str);
        $str = str_replace("\"","-",$str);
        $str = str_replace("Ã©","e",$str);
        $str = str_replace("Ã¨","e",$str);
        $str = str_replace("Ã ","a",$str);
        $str = str_replace("&","-",$str);
        $str = str_replace("é","e",$str);
        $str = str_replace("/","-",$str);
        $str = str_replace(":","-",$str);
        $str = str_replace("è","e",$str);
        $str = str_replace("É","e",$str);
        $str = str_replace("ê","e",$str);
        $str = str_replace("ë","e",$str);
        $str = str_replace("à","a",$str);
        $str = str_replace("â","a",$str);
        $str = str_replace("ä","a",$str);
        $str = str_replace("©","-",$str);
        $str = str_replace("ô","o",$str);
        $str = str_replace("ö","o",$str);
        $str = ereg_replace("[^A-Za-z0-9.]", "-", $str);
        $str = str_replace("---","-",$str);
		$str = str_replace("--","-",$str);
        return($str);
        */
}

/**
*	Fonction inutile
* @deprecated 
*/
function fieldval($str) {
	return $str;
}


/**
 * Verifie si l'utilisateur est connecte comme administrateur
 *
 * @return bool True ou False  selon si l'utilisateur est connecté
 */
function doAdminStuffs  () {
    if(isLoggedAsAdmin() && $_SESSION['cache_'.UNIQUE_SITE]['adm']['frontModif']) {
        return true;
    } else {
        return false;
    }
}



/**
 * Retourne si la variable passée en paramètre existe et possède un contenu
 *
 * @param mixed $s Variable a tester
 * @return bool
 */

function has($s)
{
  return isset($s) && (strlen(trim($s)) > 0) && $s != '0000-00-00' && $s != 'null';
}


/**
 * Formate une date
 *
 * @param string $d Date au format informatique yyyy-mm-dd
 * @param bool $show_year Definit si l'on garde l'année
 * @return string Date formatée dd/mm[/yyyy]
 */
function nicedate($d,$show_year=true, $separator = '/') {

		/**
		 * Si $d est un date time xxxx-xx-xx 00:00:00
		 * on supprime le tmeps
		 */
		$datetime = strpos($d,' ');

		if($datetime) {

			$d = substr($d,0,$datetime);
		

		/**
		 * Puis on remet dans l'ordre
		 */
        
        }
		$t = explode("-",$d);
        if(LG == 'uk' || LG == 'us' || LG == 'en') {
        	 if($show_year)
	         return $t[1].$separator.$t[2].$separator.$t[0];
	        else
	         return $t[1].$separator.$t[2];
        } else {
	        if($show_year)
	         return $t[2].$separator.$t[1].$separator.$t[0];
	        else
	         return $t[2].$separator.$t[1];
        }
}

/**
 * Formatte une valeur Date + Time au format date JJ-MM-YY
 * sauf dates anglaises au format date MM-JJ-YY
 *
 * @param string  $d Valeur au format yyyy-mm-dd hh:mm:ss
 * @return string 31/12[/01]
 */

function nicedateyear2char($d) {

		/**
		 * Si $d est un date time xxxx-xx-xx 00:00:00
		 * on supprime le tmeps
		 */
		
		$d = explode(" ",$d);

		/**
		 * Puis on remet dans l'ordre
		 */
        $t = explode("-",$d[0]);
        
        if(LG == 'uk' || LG == 'us' || LG == 'en')
           	return $t[1].'.'.$t[2].'.'.substr($t[0],2,2);
        else 
            return $t[2].'.'.$t[1].'.'.substr($t[0],2,2);
}

/**
 * Formatte une valeur Date + Time
 *
 * @param string  $d Valeur au format yyyy-mm-dd hh:mm:ss
 * @param bool $show_year On retourne l'année ou non
 * @return string 31/12[/2001] 12h59m59s
 */
function niceDateTime( $d,$show_year=true,$showSec=false) {
	$t = explode(" ",$d);
	$mydate = $t[0];
	$mytime = $t[1];
	
    $t = explode("-",$mydate);
    $tim  = explode(":",$mytime);
    if($show_year)
     $date = $t[2].'/'.$t[1].'/'.$t[0];
    else
     $date = $t[2].'/'.$t[1];

     $tim = $tim[0].'h'.$tim[1];
     
     if($showSec) {
     	$tim .= 'm'.$tim[2].'s';
     }
     return $date.' '.$tim;
}


function niceTime($d) {
	
	$t = explode(" ",$d);
	$mydate = $t[0];
	$mytime = $t[1];
	$tim = explode(':',$mytime);

    $tim = $tim[0].':'.$tim[1].'';

    if($tim[0] == 0 && $tim[1] == 0){
    	return '';
    }
    
    return $tim;
         
}

/**
 * Formate une date au format textuel
 *
 * @param string $date Date au format yyyy-mm-dd
 * @return string Ex : 24 Octobre 1980
 */
function niceTextDate( $date , $jour = false) {
    
	global $lg;
	$d = explode(" ",$date);
	$d = $d[0];
	if(!$d[0]){
		return;
	}
	$d = explode("-",$date);
	
	$type = '%e %B %Y';
	if($jour) {
		$type= '%A '.$type;	
	}
	@setlocale($GLOBALS['CURLOCALE']);
	if($lg == 'uk')
		$s = strftimeloc($type,mktime(0,0,0,$d[1],$d[2],$d[0]));
	else 
		$s = strftimeloc($type,mktime(0,0,0,$d[1],$d[2],$d[0]));
		
	if($GLOBALS['isWindows']) {
		$s = utf8_encode($s);
	}
	
	return $s;
	

}

/**
 * Formatte un interval de date en fonction des deux dates
 * Si la meme année, l'année n'est pas répétée : Du 2 juin au 3 juillet 2006
 * Si date identique : Le 2 juin 2006
 * Sinon : Du 24 décembre 2005 au 2 janvier 2006
 *
 *
 * @param string  $date1 Date de début
 * @param string $date2 Date de fin
 * @return string Date formatée
 */
function nicedate_interval( $date1, $date2='', $separator = '/'){
	global $lg,$lglocale;
	
	if($nb = strpos($date1,' ')) {
		$date1 = substr($date1,0,$nb);
	}
	$d_deb = explode('-', $date1);
	$d_fin = explode('-', $date2);
	
	@setlocale($GLOBALS['CURLOCALE']);
	
	if($date1 == $date2 || $date2 == '0000-00-00' || $date2 == '' || $date2 == '0000-00-00 00:00:00'){
		return t('le').' ' .nicetextdate($date1, true,$separator);
	}
	elseif($d_deb[1] == $d_fin[1] && $d_deb[0] == $d_fin[0]){
		if($d_deb[2] == "01") {
			$d_deb[2] = '1er';
		}
		//setLocale(LC_TIME, $lglocale);
		//return t('du').' ' .$d_deb[2] .' '.t('au').' ' .$d_fin[2] .' ' .(ucfirst(strftime("%B", strtotime($date1)))) .' ' .$d_deb[0];
		return t('du').' ' .$d_deb[2] .' '.t('au').' ' .nicetextdate($date2, false, $separator);	
	}	
	else{
		return t('du').' ' .nicedate($date1,true,$separator) .' '.t('au').' '.nicedate($date2, true, $separator);
	}
}

/**
 * Alias pour nicedate_interval() ...
 *
 * @param unknown_type $date1
 * @param unknown_type $date2
 * @param unknown_type $separator
 * @return unknown
 */
function niceDateInterval($date1,$date2='',$separator= '/') {
	return nicedate_interval($date1,$date2,$separator);
}


/**
 * Formate une date proprement
 *
 * @param string $date Date yyyy-mm-dd
 * @return date
 */
function nicedate_str($date,$showYear=1){
	global $lg,$lglocale;
	
	$d = strtotime($date);

	$ee = ((mystrftime("%A", $d)));
	
	$s = $ee .' ' .(int)date('d',$d) .' ' .((mystrftime("%B", $d)));
	
	if($showYear) {
		$s .= ' ' .date('Y',$d);
	}
	
		
	if($GLOBALS['isWindows']) {
		$s = utf8_encode($s);
	}
	
	return $s;
}


/**
 * Alias pour la fonction print(); avec un retour chariot à la fin (\n)
 *
 * @param mixed $txt
 */
function p($txt) {
        print($txt."\n");
}


/**
 * Ajoute le message $txt à la liste des messages de débug à afficher
 * Le stock comment niveau "INFO"
 *
 * @param mixed $txt
 */
function dinfo($txt) {
global $genMessages;

$genMessages->add($txt,'info');

}


/**
 * Ajoute le message $txt à la liste des messages de débug à afficher
 * Le stock comment niveau "INFO"
 *
 * @param mixed $txt
 */
function dinfo_instant($txt) {
	global $genMessages;

	$genMessages->addInstant($txt,'info');

}




/**
 * Ajoute le message $txt à la liste des messages de débug à afficher
 * Le stock comment niveau "DEBUG"
 *
 * @param mixed $txt
 */
function derror($txt) {
	global $genMessages;
	debug_print_backtrace();
	$genMessages->add($txt,'error');

}


/**
 * Comme la fonction derror()
 * * @param mixed $txt
 */
function debug() {
	global $genMessages;

	$vars = func_get_args();
	
	if(count($vars) == 1 ) {
		$txt = $vars[0];
	} else {
		$txt = str_replace("\n","<br/>",var_export($vars,true));
	}

	
	$backt = debug_backtrace();
	if(!is_array($txt) && !is_object($txt)) {
		$txt .= '<br/><span style="font-weight:normal !important">'.basename($backt[1]['file']).' : '.$backt[1]['line'].' : '.$backt[1]['function'].'</span>';
		$txt .= '<br/><span style="font-weight:normal !important">'.basename($backt[2]['file']).' : '.$backt[2]['line'].' : '.$backt[2]['function'].'</span>';
		$txt .= '<br/><span style="font-weight:normal !important">'.basename($backt[3]['file']).' : '.$backt[3]['line'].' : '.$backt[3]['function'].'</span>';
		$txt .= '<br/><span style="font-weight:normal !important">'.basename($backt[4]['file']).' : '.$backt[4]['line'].' : '.$backt[3]['function'].'</span>';
		
		//$txt .= var_export($backt,true);
	}

	if(is_object($genMessages))  {
		$genMessages->add($txt,'error');
	} else {
		die($txt);
	}
	

}


/**
 * Lors de l'utilisation des gabarits, on split les paramètres sur la virgule puis sur
 * le signe égal :
 * param1=valeur1,param2=valeur2,...
 *
 * @param string $params
 * @return Tableau associatif nom de variable = clef
 */
function SplitGabaritParams($params) {
	$a = explode(',',$params);
	$para = array();
	foreach($a as $p) {
		$pa = explode('=',$p);
		$para[$pa[0]] = $pa[1];
	}
	return $para;
}


/**
 * Pour utiliser un string dans du javascript, on escape
 * les guillemets simples et doubles et on les remplaces
 * par leurs équivalents HTML
 *
 * @param string  $str
 * @return string La chaine nettoyée
 */
function altify($str) {
        return str_replace(array('"',"'"),array("&quot;","&#39;"),(strip_tags($str)));
}


/**
 * Stocke la liste des requetes SQL executées
 *
 * @param unknown_type $str
 */
function debugEvent($str) {
	global $_Gconfig;
	$GLOBALS['curSQL']= $str;
	$GLOBALS['curSQLStart'] = getmicrotime();
	if($_Gconfig['debugSql']) {
		global $h_sqls;
		$ar = debug_backtrace();
		if((basename($ar[5]['file'])))
		$profileSTR .= '<hr/>'.(basename($ar[5]['file']).' : '.$ar[5]['function'].'');
		if((basename($ar[4]['file'])))
		$profileSTR .= '<br/>'.(basename($ar[4]['file']).' : '.$ar[4]['function'].'');
		if((basename($ar[3]['file'])))
		$profileSTR .= '<br/>'.(basename($ar[3]['file']).' : '.$ar[3]['function'].'');
		if((basename($ar[2]['file'])))
		$profileSTR .= '<br/>'.(basename($ar[2]['file']).' : '.$ar[2]['function'].'');

		$GLOBALS['curProfile'] = $profileSTR;
		//$h_sqls[] = $profileSTR;
		//$h_sqls[] = $str;

	}
    return;
    /*if(strstr($str,"event"))
        debug($str);*/
}


function debugEnd() {
	global $_Gconfig;

	if($_Gconfig['debugSql']) {
		global $h_sqls,$h_sqlsI;
		$t = getmicrotime()-$GLOBALS['curSQLStart'];
		$h_sqls[] = array('sql'=>$GLOBALS['curSQL'],'time'=>($t),'profile'=>$GLOBALS['curProfile']);
		$t = str_replace('0,0','',$t);
		$t = (int)substr($t,0,8);
		$h_sqlsI[$t] = $GLOBALS['curSQL'];
	}
}


/**
 * Retourne une taille formatee proprement
 * ex: 15911859 bytes devient 1,5Mo
 *
 * @param string $bytes
 * @param int $precision
 * @return string
 */
function pretty_bytes ($bytes, $precision = 1)
{
   $suffix = array ('<abbr title="octets">oct</abbr>','<acronym title="Kilo octets">K.o.</acronym>', '<acronym title="Mega octets">M.o.</acronym>','<acronym title="Giga octets">G.o.</acronym>');

   $index = floor (@log($bytes + 1, 1024)); // + 1 to prevent -INF
   if($index == 0)
                return (substr(($bytes/1024),0,3))." " .$suffix[1];
   return sprintf ("%0.{$precision}f %s", $bytes / pow (1024, $index), $suffix[$index]);
}


/**
 * Retourne un nombre de secondes formaté proprement en secondes, minutes ou heures
 * @example 35 => 35s   65 => 1min5s  3665 => 1h1min
 * @param int $seconds
 * @return string
 */
function niceSeconds($seconds) {

	if($seconds < 60) {
		return $seconds.'s';
	}
	else if($seconds < 3600 ) {
		$m = floor($seconds / 60);
		return $m.'min'.floor(($seconds - (60*$m))).'s';
	}
	else {
		$h = floor($seconds / 3600);
		return $h.'h'.floor(($seconds - 3600*$h)/60).'min';
	}
	
}

/**
 * Fonction permettant d'afficher les messages de debugs juste pour l'IP d'opixido
 * ou bien si la session a le paramètre debug
 *
 * @param mixed $s String a afficher
 */
function debugOpix($s) {
	if(strstr($_SERVER['REMOTE_ADDR'],'192.168.1.') || strstr($_SERVER['REMOTE_ADDR'],'82.67.200.175') || $_REQUEST['debug']) {
		debug($s);
	}
}

/**
 * Warnings pour les développeurs
 *
 * @param string $str
 */
function devbug($str) {
	$GLOBALS['ocms_warnings'][] = $str;
	debug($str,'dev');
}


/**
 * retourne une erreur à afficher à l'internaute
 *
 * @param string $str
 * @return string
 */
function showError($str) {
	
	return  '<div class="ocms_error">'.$str.'</div>';
}

/**
 * Met en majuscule les premiers mots après $impexp
 * 
 * @example phrase 1. phrase 2    => ". " => Phrase 1. Phrase 2
 *
 * @param string $impexp Délimiteur de majuscule
 * @param string $sentence_split phrase
 * @return string
 */
function ucSentence( $sentence_split, $impexpA = array(". ","! ","? ")) {
	if(!is_array($impexpA)) {
		$impexpA = array($impexpA);
	}
	$sentence_split = trim($sentence_split);
	foreach($impexpA as $impexp) {
	    $textbad=explode($impexp, $sentence_split);
	    $newtext = array();
	    foreach ($textbad as $sentence) {
	        $sentencegood=ucfirst(strtolower($sentence));
	        $newtext[] = $sentencegood;
	    }
	    $textgood = implode($impexp, $newtext);
	    $sentence_split = $textgood;
	}
	return $sentence_split;
}




/**
 * Fonction d'affichage du profiling
 * Temps d'execution, ...
 *
 */
function GetStats() {
        //return;

        if(strstr($_SERVER['REMOTE_ADDR'],'192.168.1.') || 
        		strstr($_SERVER['REMOTE_ADDR'],'82.67.200.175') 
        		|| $_REQUEST['debugmode']) {
            global $sqlTime,$startTime,$nbRSql,$nbRetSql;
            
            p('<div onclick="this.style.display=\'none\'" style="z-index:0;_display:none;position:fixed;bottom:0px;right:0px;width:200px;text-align:right;background-color:#fff;opacity:0.8;font-family:arial;font-size:11px;">' ) ; //onclick="this.style.zIndex=50;">');
            p('Temps d\'execution total : '.number_format(getmicrotime()-$startTime,3).' sec<br/>');
            p('Temps d\'execution Externe : '.number_format($GLOBALS['times']['Plugins'],3).' sec<br/>');
            p('Temps d\'execution <a href="'.$_SERVER['REQUEST_URI'].'?debugSql=1'.'">SQL</a> : '.number_format(($sqlTime),3).' sec<br/>');

            if(!CACHE_IS_ON)
            	p('Cache d&eacute;sactiv&eacute;<br/>');
            else {
            	p('Cache utilis&eacute; : '.$GLOBALS['nbCacheUsed'].' / '.$GLOBALS['nbCacheTotal'].'<br/>');
            	//print_r($GLOBALS['cacheUsed']);
				@ksort($GLOBALS['cacheUsed']);
				@ksort($GLOBALS['cacheDeclared']);

	            $tab = @array_diff($GLOBALS['cacheDeclared'],$GLOBALS['cacheUsed']);


				if(count($tab)) {
					foreach($tab as $v) {
						p('Cache inutilis&eacute; : '.$v.'<br/>');
					}
				}
            }
            p('Nombre de requetes SQL : '.($nbRSql).'<br/>');
            p('Nombre de lignes retournees : '.($nbRetSql).'<br/>');
            p('Memoire : '.pretty_bytes(memory_get_usage()).'<br/>');
            p('</div>');
        }
}

/**
 * Addslashes uniquement si on n'a pas les magic_quotes
 *
 * @param string $str
 * @return string Apres addslashes (ou rien)
 */
function addmyslashes($str) {
		return $str;
        if(ini_get('magic_quotes_gpc'))
			return $str;
        else
			return addslashes($str);
}







/**
 * Retourne la liste des champs de langue
 *
 * @param unknown_type $field
 * @param unknown_type $sep
 * @return unknown
 */
function getLgFields ($field,$sep) {

	$i=1;
	global $_Gconfig;
	reset($_Gconfig['LANGUAGES']);
	$nbLg = count($_Gconfig['LANGUAGES']);
	foreach($_Gconfig['LANGUAGES'] as $lg)	 {
		$str .= $field.'_'.$lg;
		if($i < $nbLg)
			$str .= $sep;
		$i++;
	}
	return $str;

}




/**
 * Alias pour Array_key_exists permettant d'oublier l'ordre des parametres
 * Selon quel parametre est un array, on chan ge l'ordre correctement
 *
 * @param string|array $val1
 * @param string|array $val2
 * @return bool
 */
function ake($val1,$val2) {
	if(is_array($val1)) {
		return @array_key_exists($val2,$val1);
	} else if(is_array($val2)){
		$res = @array_key_exists($val1,$val2) ;
		/*if(!$res) {

		}*/
		return $res;
	} else {
		//debug(debug_backtrace());
		return false;
	}
}

/**
 * Array key exists and return value
 *
 * @param <array> $val1
 * @param <key> $val2
 * @return <mixed>
 */
function akev($val1,$val2) {
/*	if(!is_array($val1)) {
		debug_print_backtrace();
	}
	*/

	if(@array_key_exists($val2,$val1)) {
	//	if(is_array($val1)) {
			return $val1[$val2];
	/*	} else if(is_array($val2)){			
			return $val2[$val1];
		}
		*/
	} 
	return false;
}


/**
 * Construction d'un mot de passe aléatoire et prononcable
 *
 * @return string mot de passe
 */
function mkPasswd() {

        $consts='bcdfgkhijklmnpqrstvwxz';
        $vowels='aeiouy';

        for ($x=0; $x < 6; $x++) {
                mt_srand ((double) microtime() * 1000000);
                $const[$x] = substr($consts,mt_rand(0,strlen($consts)-1),1);
                $vow[$x] = substr($vowels,mt_rand(0,strlen($vowels)-1),1);
        }
        return $const[0] . $vow[0] .$const[2] . $const[1] . $vow[1] . $const[3] . $vow[3] . $const[4];

}


function mkPasswdLen ($length = 8)
{

  // start with a blank password
  $password = "";

  // define possible characters
  $possible = "23456789abcdfghjkmnpqrstvwxyz"; 
    
  // set up a counter
  $i = 0; 
    
  // add random characters to $password until $length is reached
  while ($i < $length) { 

    // pick a random character from the possible ones
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
        
    // we don't want this character if it's already in the password
    if (!strstr($password, $char)) { 
      $password .= $char;
      $i++;
    }

  }

  // done!
  return $password;

}





/**
 * Fonction decryptant les entités HTML
 * A partir de la table de correspondance PHP
 *
 * @param unknown_type $string
 * @return unknown
 */
function unhtmlentities ($string)  {
   $trans_tbl = get_html_translation_table (HTML_ENTITIES);
   $trans_tbl = array_flip ($trans_tbl);
   $ret = strtr ($string, $trans_tbl);
   return  preg_replace('/\&\#([0-9]+)\;/me',
       "chr('\\1')",$ret);
}

/**
 * Encode le string
 *
 * @param unknown_type $str
 * @return unknown
 */
function enc($str) {

    return unhtmlentities(htmlEncodeText($str));//,ENT_QUOTES,'cp1252'));
    //return htmlentities($str,ENT_QUOTES,'cp1252');


}
function htmlEncodeText ($string)
{

$string = str_replace("&","#_#",$string);
  $pattern = '<([a-zA-Z0-9\. "\'_\/-=;\(\)?&#%]+)>';
  preg_match_all ('/' . $pattern . '/', $string, $tagMatches, PREG_SET_ORDER);
  $textMatches = preg_split ('/' . $pattern . '/', $string);

  foreach ($textMatches as $key => $value) {
   $textMatches [$key] = htmlentities ($value,ENT_QUOTES,'cp1252');
  }

  for ($i = 0; $i < count ($textMatches); $i ++) {
   $textMatches [$i] = $textMatches [$i] . $tagMatches [$i] [0];
  }
$string = implode (" ",$textMatches);
$string = str_replace("#_#","&",$string);
  return $string;
}


/* TRADUCTIONS */


function loadTrads($lge) {
    global $_trads,$atrads,$lg,$admin_trads;


	$lg = $lge;
	if(!$lg) {
		$lg = LG;	
	}
	
	if(ake('lgLoaded',$GLOBALS) && $GLOBALS['lgLoaded'][$lg]) {
		return;
	}
	
	$GLOBALS['lgLoaded'][$lg] = true;
	
    $sql = 'SELECT trad_id,trad_'.LG.'';
	if(LG != LG_DEF) {
		$sql .= ' ,trad_'.LG_DEF.'';
	}
    $sql .= ' FROM s_trad ';
 
    $res = GetAll($sql);
	
    foreach($res as $row) {

    	$_trads[$row['trad_id']][$lg] = $row['trad_'.LG_DEF];        
    	if($row['trad_'.$lg]) {    		
        	$_trads[$row['trad_id']][$lg] = $row['trad_'.$lg];
    	}
	
    }

    if(!is_array($atrads) ) {
	    	    
				
		if(!is_array($atrads)) {
			$atrads = $_trads;
	
		}
    }
    
    

}


if ( !function_exists("t")) {


	function t($t,$doAdmin = false) {

        global $frontAdminTrads,$trads,$_trads,$admin_trads,$otherLg,$atrads;

		$otherLg = LG_DEF;
		$lg = LG;
		
		if(akev($GLOBALS,'forceLG')) {
			$lg = $GLOBALS['forceLG'];		
			$otherLg = LG;
			loadTrads($lg);	
			$atrads = $_trads;			
			//debug($atrads);
		}		
		
		$v = false;
		
		if(ake($atrads,$t)) {
		
	        if(akev($atrads[$t],$lg)) {
	        	/* La traduction existe t'elle dans la langue courante ? */        
	            $v = $atrads[$t][$lg];
	            $id=$t;
	            
	        } else if(akev($atrads[$t],$otherLg))  {
	        	/* Sinon dans l'autre langue */
	        	$v = $atrads[$t][$otherLg]; // '<span lang="'.$otherLg.'">'..'</span>'
	            $id=$t;
	
	        } 
		} 
		
		
		if(!$v) {
			if(function_exists('ta')) {
	        	$v = ta($t);
	        	$id = $t;
	        } else {
	            $id = $v = $t;
	        }
		}
		/*
        if($doAdmin) {
            $ad = new genFrontAdmin("s_trad",$id,false);

            $v = $ad->startField("trad_fr").$v.$ad->endField();
        } 
        */
		

        return apost($v);
    }
}

function tf($t,$rep=array()) {
	
	global $_trads,$otherLg;

	$v = t($t);
	if(!$v) {
		$v = ta($t);
	}
	
    if(!$v) {
    	foreach($rep as $k=>$vv) {
    		$v .= $k.' : ['.$k.']'."\n";
    	}
	}
    if(count($rep)) {
    	foreach($rep as $k=>$vv) {
    		$v = str_replace('['.$k.']',$vv,$v);
    	}
    }

    return $v;
}


function roundMille($nb,$prec) {
	return number_format($nb,$prec,',',' ');

}


function loadParams() {
    global $_params;

   /* if($_SESSION['_params'])  {
    	$_params = $_SESSION['_params'];
    	return;
    }
	*/

    $sql = 'SELECT param_id,param_valeur FROM s_param';
    $res = GetAll($sql);
    $_params = array();
    foreach($res as $row) {
            $_params[$row['param_id']] = $row['param_valeur'];
    }
	
    //$_SESSION['_params'] = $_params;

}


    if ( !function_exists("getParam")) {
    	
        function getParam($t) {
        	
            global $_params;
            $v = ake($_params,$t) ? $_params[$t]  : '';
            if($v == "")
                return $t;
            else
                return $v;
        }
    }


function removeaccents( $string )
{
   $string = htmlentities($string,ENT_QUOTES,'utf-8');

   return preg_replace("/&([a-z])[a-z]+;/i","$1",$string);
}


/* Deporter aussi */
function getBrowserLang() {
        $langs=explode(",",$_SERVER["HTTP_ACCEPT_LANGUAGE"]);

        //start going through each one
        foreach ($langs as $value) {

                //select only the first two letters
                $choice=substr($value,0,2);

                if($choice == "fr")
                        return "fr";
                else if($choice == "en")
                        return "en";
        }
        return "fr";
}

function arraySplit($array_with_elements, $key_name) {
   $key_index = array_keys(array_keys($array_with_elements), $key_name);
     if (count($key_index) != '') {

	array_splice($array_with_elements, $key_index[0], 1);
     }
     return $array_with_elements;
}

function arrayInWord($arr,$word) {
        while(list(,$v) = @each($arr)) {
                if(strstr($word,$v) !== false)
                        return true;
        }
        return false;
}



function path_concat() {
	/*
		Concatene deux bouts de chemins en un seul
	*/
	$u1 = '';
    $arg_list = func_get_args();
   	$numargs = count($arg_list);

    for ($i = 0; $i < $numargs; $i++) {
        $u2 = $arg_list[$i];

        if(mb_substr($u1,-1) == '/' && mb_substr($u2,0,1) == '/')
			$u1 = substr($u1,0,-1).$u2;
		else if(mb_substr($u1,-1) == '/' ||  mb_substr($u2,0,1) == '/')
			$u1 = $u1.$u2;
		else if($u1 != '')
			$u1 = $u1.'/'.$u2;
		else
			$u1 = $u1.$u2;
    }

    return $u1;

}


//print('XXX'.path_concat('test','test2','/test3'));

function path($u1,$u2) {
	return path_concat($u1,$u2);

}

function urlconcat($u1,$u2) {
	return path_concat($u1,$u2);
}

function SplitParams($p,$first=';',$second="=") {
	$params = explode($first,$p);
	$retPar = array();
	
	foreach($params as $param) {
		$t = explode($second,$param);
		
		
		$retPar[$t[0]] = $t[1];
	}
	
	return $retPar;
}


function numpad($nb,$cpt) {
		while(strlen($nb) < $cpt)
			$nb = '0'.$nb;
		return $nb;

}

/**
 * @deprecated Now in base.js
 *
 * @return false
 */
function getFlashDetection() {


	return false;
}

function getFlashDetectionSo ( ) {

	$html = '<script type="text/javascript" src="'.BU.'/js/swfobject.js"></script>' ;

	return $html;
}

function getFlash($url,$w=290,$h=240,$alt="Flash",$tag='',$params=array()) {

	global $_flashTestPrinted;

	if(!$_flashTestPrinted) {
		$html .= getFlashDetection();
		$_flashTestPrinted = True;
	}
	if(!$tag) {
		$tag = 'flash'.str_replace(',','',getmicrotime()).rand(0,1000);
	}

	$html .= ('<object id="'.$tag.'" type="application/x-shockwave-flash" data="'.$url.'" width="'.$w.'" height="'.$h.'">');
	$html .= ('<param name="play" value="true" />');
	$html .= ('<param name="movie" value="'.$url.'" />');
	$html .= ('<param name="menu" value="false" />');
	$html .= ('<param name="wmode" value="transparent" />');
	$html .= ('<param name="quality" value="high" />');
	$html .= ('<param name="allowFullScreen" value="true" />');
	$html .= ('<param name="allowScriptAccess" value="always" />');
	$html .= ('<param name="scalemode" value="showall" />');

	if(is_array($params)) {
		foreach($params as $k=>$v) {
			$html .= ('<param name="'.$k.'" value="'.$v.'" />');
		}
	}
	$html .= ('<p>'.$alt.'</p>');
	$html .= ('</object>');

	return $html;

}

function getFlashSo ( $url , $w = 290 , $h = 240 , $alt = "Flash" , $id_tag = '' , $wmode = true ) {

	global $_flashTestPrintedSo;

	if ( !$_flashTestPrintedSo ) {

		$html .= getFlashDetectionSo ( ) ;

		$_flashTestPrintedSo = True ;

	}

	$html .= '<script type="text/javascript">' ;
	$html .= '// <![CDATA[' . "\n" ;
   	$html .= 'var so = new SWFObject("' . $url . '", "' . $id_tag . '", "' . $w . '", "' . $h . '" , "9", "#FCFCFC") ;' ;
   	if ( $wmode ) $html .= 'so.addParam( "wmode" , "transparent" ) ;' ;
   	$html .= 'so.addParam( "scale" , "noscale" ) ;' ;
   	
   	$html .= 'so.write("' . $id_tag . '") ;' ;
  	$html .= '// ]]>' . "\n" ;
	$html .= '</script>' ;

	return $html ;

}

/**
 * Genere le tag qui contiendra le flash passé en url
 *
 * @param string $url
 * @param int $w width
 * @param int $h height
 * @param int $alt alternatif
 * @param string $tag id de la balise qui reçoit le flash
 */

function printFlash ( $url , $w = 290 , $h = 240 , $alt = "Flash" , $tag = '',$params=array() ) {

	if ( $tag != '' ) {

		print getFlashSo ( $url , $w , $h , $alt , $tag ) ;

	}

	else {

		print getFlash ( $url , $w , $h , $alt , $tag, $params) ;

	}

}


function printFlvplayer($url,$w=290,$h=240,$alt="Flash") {

	printFlash('/flvplayer.swf?file='.$url.'&amp;txt_clicktoplay='.t('clicktoplay').'&amp;txt_buffering='.t('buffering'),$w,$h,$alt,' class="flvplayer" ');

}

function getFlvPlayerSo($url,$w=440,$h=340,$alt="Flash",$idbloc="div_flv") {
	return getFlashSo('/flvplayer.swf?file='.$url.'&amp;txt_clicktoplay='.t('clicktoplay').'&amp;txt_buffering='.t('buffering'),$w,$h,$alt,$idbloc);
}


function printMp3player($url,$w=290,$h=20,$alt="Flash") {

	printFlash('/mp3player.swf?file='.$url,$w,$h,$alt,' class="mp3player" ');
}

function getMp3playerSo($url,$w=290,$h=20,$alt="Flash",$idbloc="") {

	return getFlashSo('/mp3player.swf?autostart=0&file='.$url,$w,$h,$alt,$idbloc);
}


function printMedia($url,$alt="") {
	$ext = strtolower(substr($url,strrpos($url,'.')+1));

	switch ($ext) {
		case 'flv':
			printFlvplayer($url);
			break;
		case 'mp3':
			printMp3player($url);
			break;
		case 'swf':
			printFlash($url);
			break;
		case 'mov':
			print('MOV FILE NOT SUPPORTED YET');
			break;
		case 'jpg':
			printImage($url,$alt);
			break;
		case 'jpeg':
			printImage($url,$alt);
			break;
		case 'png':
			printImage($url,$alt);
			break;
		case 'gif':
			printImage($url,$alt);
			break;

	}
}


function printImage($src,$alt="",$tag='') {
	echo getImageTag($src,$alt,$tag);
}	

function getImageTag($src,$alt="",$tag='') {
	return ('<img src="'.$src.'" alt='.alt($alt).' '.$tag.' />');
}


/**
 * Remplace le sigle & par son equivalent HTML &#38;
 *
 * @param unknown_type $str
 * @return unknown
 */
function etcom($str) {
	return str_replace(' & ',' &#38; ',$str);
}



/**
 * Termine le script en affichant l'erreur $str
 *
 * @param string $str Erreur a afficher
 */
function diebug($str) {
	global $genMessages;

	derror($str);
	derror(debug_backtrace());
	$genMessages->gen();
	exit(1);

}


/**
 * Charge la liste des plugins disponibles selon la table s_plugin
 *
 *
 * @return liste des noms des plugins
 */
function GetPlugins() {
	
	

	if(!akev($_SESSION['cache'][UNIQUE_SITE],'activePlugins')) {
		$sql = 'SELECT * FROM s_plugin AS P WHERE plugin_actif = 1 ORDER BY plugin_ordre ASC';
		$res = GetAll($sql);
		$ret = array();
		if(!$res) {
			return array();
		}
		foreach($res as $v) {
			$ret[] = $v['plugin_nom'];
		}
		$_SESSION['cache'][UNIQUE_SITE]['activePlugins'] = $ret;
		
	}
	return $_SESSION['cache'][UNIQUE_SITE]['activePlugins'];

}



/**
 * Cr�� un tableau avec les diff�rents gabarits en ligne sur le site qui ont le champ "index_table" de remplit
 *
 * @return array
 */
function getGabaritsToIndex() {

	if(!$_SESSION['cache'][UNIQUE_SITE]['gabaritsToIndex']) {

		$_SESSION['cache'][UNIQUE_SITE]['gabaritsToIndex'] = array();

		$sql = 'SELECT G.*, R.rubrique_id
		FROM s_gabarit AS G, s_rubrique AS R
		WHERE
		G.gabarit_index_table != ""
		AND G.gabarit_id = R.fk_gabarit_id
		 '.sqlRubriqueOnlyOnline('R');
		$res = GetAll($sql);
		foreach($res as $row) {

			$_SESSION['cache'][UNIQUE_SITE]['gabaritsToIndex'][$row['gabarit_index_table']] = $row;

		}
	}

	return $_SESSION['cache'][UNIQUE_SITE]['gabaritsToIndex'];

}




/**
 * Retourne l'URL de l'objet index� par le moteur de recherche
 *
 * @param array $row Ligne de la table is_obj
 * @param array $r Ligne de la table en question index�e
 * @return unknown
 */
function getUrlFromSearchOLD($obj,$row) {

	if(!is_array($obj)) {
		$obj['obj'] = $obj;
	}
	if($obj['obj'] == 's_rubrique') {
		$rubid = $obj['fkid'];
		$mp = array();
	} else {
		$gabs = getGabaritsToIndex();
		//debug($gabs);
		if(!$gabs[$obj['obj']]) {
			debug('NO GABARIT DEFINED FOR OBJECT : '.$obj['obj']);
			debug($obj);
			return '';
		}

		$rubid = $gabs[$obj['obj']]['rubrique_id'];
		$params = $gabs[$obj['obj']]['gabarit_index_url'];
		$params = splitParams($params);
		$mp = array();
		foreach($params as $param=>$value) {
			$mp[$param] = $row[$value];
		}
	}
//	debug($gabs[$obj['obj']]['gabarit_classe']);
	return getUrlFromId(getRubFromGabarit($gabs[$obj['obj']]['gabarit_classe']),LG,$mp);
	return $GLOBALS['site']->g_url->buildUrlFromId($rubid,LG,$mp);
}



function getUrlFromSearch($obj,$row) {

	if(!is_array($obj)) {
		$obj['obj'] = $obj;
	}
	if($obj['obj'] == 's_rubrique') {
		$rubid = $obj['fkid'];
		$mp = array();
	} else {
		$gabs = getGabaritsToIndex();
		//debug($gabs);
		
		if(is_array($obj['obj'])) {
			return '';
		}
		
		if(!$gabs[$obj['obj']]) {
			debug('NO GABARIT DEFINED FOR OBJECT : '.$obj['obj']);
			debug($obj);
			return '';
		}

		$rubid = $gabs[$obj['obj']]['rubrique_id'];
		$params = $gabs[$obj['obj']]['gabarit_index_url'];
		if(strpos($params,'php:') !== false ) {
	    	$code = substr($params,4);		    	
	    	return eval($code);    			    	
    	}
		$params = splitParams($params);
		$mp = array();
		foreach($params as $param=>$value) {
			$mp[$param] = $row[$value];
		}
		$rubid = getRubFromGabarit($gabs[$obj['obj']]['gabarit_classe']);
		
	}
//	debug($gabs[$obj['obj']]['gabarit_classe']);
	return getUrlFromId($rubid,LG,$mp);
	return $GLOBALS['site']->g_url->buildUrlFromId($rubid,LG,$mp);
}


/**
 * Retourne le titre d'un élément d'une table a partir d'un row
 *
 * @param string $table
 * @param array $row
 * @param string $separator
 * @return string
 */
function GetTitleFromRow($table,$row,$separator=" ",$html=false) {
   global $tabForms,$relations,$uploadFields;
   $fields = getTabField($table);

   if(!is_array($tabForms[$table]['titre'])) {
   	$tabForms[$table]['titre'] = array($tabForms[$table]['titre']);
   }
    reset($tabForms[$table]['titre']);
    $tab = getTabField($table);
    $titre = '';
    while(list($k,$v) = each($tabForms[$table]['titre'])) {
			            		
		if(akev($relations,$table) && akev($relations[$table],$v)) {
			$re = GetRowFromId($relations[$table][$v],$row[$v]);
			$row[$v] = GetTitleFromRow($relations[$table][$v],$re);
		}else if($tab[$v]->type == 'date' ) {
			$row[$v] = nicetextdate($row[$v]);
		}else if($tab[$v]->type == 'datetime' ) {
			$row[$v] = nicetextdate($row[$v]).' '.nicetime($row[$v]);
		}
	
		if($html && arrayInWord($uploadFields,$v)) {
			
			$gf = new genFile($table,$v,$row);
			if($gf->isImage()) {
				
				$titre .= $gf->getThumbImgtag(40,40).$separator;
				
			}
		}
		else 		
		if(!$fields[$v]) {
			$titre .= getLgValue($v,$row).$separator;
			
		} else {
			$titre .= akev($row,$v).$separator;
		}
    }
    
    


    return substr($titre,0,-strlen($separator));
}

/**
 * Retourne la description d'un élément
 *
 * @param string $table
 * @param array $row
 * @param string $separator
 * @return string
 */
function getDescFromRow($table,$row,$limit=60,$separator=" ") {
   global $tabForms;
   if(is_array($tabForms[$table]['desc'])) {
            reset($tabForms[$table]['desc']);
            while(list($k,$v) = each($tabForms[$table]['desc'])) {
            		if(isBaseLgField($v,$table)) {
                    	$titre .= limitWords(getLgValue($v,$row),$limit).$separator;
            		} else {
            			$titre .= limitWords($row[$v],$limit).$separator;
            		}
            }
            //$titre = substr($titre,0,-strlen($separator));
            reset($tabForms[$table]['desc']);
    } else {
    		if(isBaseLgField($tabForms[$table]['desc'],$table)) {
    			$titre .= limitWords(getLgValue($tabForms[$table]['desc'],$limit),$row);
    		} else {
            	$titre = limitWords($row[$tabForms[$table]['desc']],$limit);
    		}
    }

     return $titre;
}


function getImgFromRow($table,$row,$w=100,$h=100) {
	global $tabForms;

	if(($tabForms[$table]['img'])) {
		$gf = new genFile($table,$tabForms[$table]['img'],$row);
		return $gf->getCropImgtag($w,$h);	
	}
}

/**
 * Retourne un tableau des tables � indexer
 *
 * @return array
 */
function GetTablesToIndex() {

	if(!$_SESSION['cache_'.UNIQUE_SITE]['tablesToIndex']) {
	$g = getGabaritsToIndex();
	$ar = array_keys($g);
	$ar[] = 's_rubrique';
	$_SESSION['cache_'.UNIQUE_SITE]['tablesToIndex'] = $ar;
	}
	return $_SESSION['cache_'.UNIQUE_SITE]['tablesToIndex'];


}


/**
 * cleanly debug an array as HTML
 *
 * @param unknown_type $tab
 * @return unknown
 */
function htmlArray($tab) {

	foreach($tab as $k=>$v) {
		$html .= '<h2>'.t($k).'</h2>';
		$html .= '<p>'.$v.'</p>';
	}
	return $html;
}


/**
* Ajoute des simple quotes autour de chaque élément d'un tableau
*
*
*/
function addSimpleQuotes($tab) {

	$newTab = array();

	for($i = 0; $i < count($tab); $i++) {
		$newTab[$i] = "'".$tab[$i]."'";
	}

	return $newTab;

}


if(!ake($_SESSION['cache'][UNIQUE_SITE],'cache_rubgab')) {
	$_SESSION['cache'][UNIQUE_SITE]['cache_rubgab'] = array();
}

/**
 * Retourne l'IDENTIFIANT de la rubrique du gabarit correspondant
 *
 * @param string $gab
 * @param string $param
 * @return int
 */
function getRubFromGabarit($gab,$param='') {

	if(!akev($_SESSION['cache'][UNIQUE_SITE]['cache_rubgab'],$gab.$param) ) {
		
		$sql = 'SELECT rubrique_id , G.* FROM s_rubrique AS R, s_gabarit AS G 
					WHERE G.gabarit_id = R.fk_gabarit_id AND 
					G.gabarit_classe LIKE "'.mes($gab).'" 
					'.sqlRubriqueOnlyOnline('R').' AND 
					( G.fk_default_rubrique_id = R.rubrique_id  OR 
					G.fk_default_rubrique_id = R.fk_rubrique_version_id ) ';
		
		if($param) {
			$sql .= 'AND rubrique_gabarit_param LIKE '.sql('%'.$param.'%').' ';
		}
		$row = GetSingle($sql);
	
		if(!count($row)) {
			$sql = 'SELECT rubrique_id FROM s_rubrique AS R, s_gabarit AS G 
						WHERE G.gabarit_id = R.fk_gabarit_id AND 
						G.gabarit_classe LIKE "'.mes($gab).'" 
						'.sqlRubriqueOnlyOnline('R').' AND 
						rubrique_gabarit_param LIKE '.sql('%'.$param.'%').'  ';
			$row = GetSingle($sql);
		}		
		if($row) {
			$_SESSION['cache'][UNIQUE_SITE]['cache_rubgab'][$gab.$param] = $row['rubrique_id'];
		} else {
			$_SESSION['cache'][UNIQUE_SITE]['cache_rubgab'][$gab.$param] = $GLOBALS['site']->g_url->rootHomeId;
		}
	}
	
	return $_SESSION['cache'][UNIQUE_SITE]['cache_rubgab'][$gab.$param];

}


/**
 * Inclu les fichiers config des plugins
 *
 */
function initPlugins() {

	$plugs = GetPlugins();
	
	foreach($plugs as $v ) {
		$GLOBALS['gb_obj']->includeFile('config.php',PLUGINS_FOLDER.''.$v.'/');
	}

}

/**
 * Retourne la valeur d'une clef dans un tableau si elle existe
 *
 * @param array $array
 * @param string $clef
 * @return mixed
 */
function geta($array,$clef) {
	if(array_key_exists($clef,$array)) {
		return $array[$clef];
	}
	return '';
}


/**
 * Rajoute les guillemets pour mettre dans un atirbut HTML (alt, title, ...)
 *
 * @param unknown_type $texte
 * @return unknown
 */
function alt($texte) {

	return '"'.str_replace(array('"',"\n","\r"),array('&quot;'," "," "),$texte).'"';

}


if(ini_get('magic_quotes_gpc')) {

	foreach($_GET as $k=> $v) {
		if(!is_array($v))
		$_GET[$k] = stripslashes($v);
	}

	foreach($_POST as $k=> $v) {
		if(!is_array($v))
		$_POST[$k] = stripslashes($v);
	}
	foreach($_REQUEST as $k=> $v) {
		if(!is_array($v))
		$_REQUEST[$k] = stripslashes($v);
	}

}


/**
 * Remplace les & par des &amp;
 *
 * @todo FAIRE UNE EXPRESSION REGULIERE
 *
 * @param string $str
 * @return string
 */
function etamp($str) {

	$str = str_replace('&amp;','**ET^AMP**',$str);
	$str = str_replace('&','&amp;',$str);
	return  str_replace('**ET^AMP**','&amp;',$str);

}




/**
 * Alias du genUrl :)
 *
 * @param unknown_type $params
 */
function getUrlWithParams($params=array()) {
	
	if(!is_object($GLOBALS['site'])) {
		$u = new genUrl(LG);
		return $u->getUrlWithParams($params);
	}

	return $GLOBALS['site']->g_url->getUrlWithParams($params);

}


function addParamsToUrl($params=array()) {
	
	return $GLOBALS['site']->g_url->getUrlWithParams(array_merge( $GLOBALS['site']->g_url->paramsUrl,$params));
	
}

/**
 * Alias du genUrl
 *
 * @param int $id
 * @param string $lg
 * @param array $params
 * @return string URL
 */
function getUrlFromId($id, $lg='', $params=array(),$action='') {

	if(!is_object($GLOBALS['site'])) {
		$u = new genUrl($lg);
		
		return $u->buildUrlFromId($id,$lg,$params,$action);
	}
	return $GLOBALS['site']->g_url->buildUrlFromId($id, $lg, $params,$action);

}

/**
 *
 * @param string $chaine
 * @param int $debut
 * @param int $longueurMax
 * @return string
 */

function substrWithNoCutWord($chaine, $debut, $longueurMax){

	//return mb_substr($chaine,0,mb_strpos($chaine,' ',$longueurMax));
	
		if(strlen($chaine) > $longueurMax){
				$chaine = substr($chaine, $debut, $longueurMax);
				$tab = explode(' ',$chaine);
				$result = '';
				for($i = 0;$i < count($tab)-1; $i++){
						$result .= $tab[$i].' ';
				}
		}
		else
				$result = $chaine;

		return $result;
}


/**
 * Retourne la premiere rubrique (identifiant) qui a comme gabarit,
 * la classe passée en paramètre
 *
 * @param string $gab
 * @return int
 */

function getRubriqueByGabarit ($gab) {

	$sql = 'SELECT R.rubrique_id
					FROM s_rubrique AS R , s_gabarit AS G
					WHERE G.gabarit_classe LIKE '.sql($gab).'
					AND R.fk_gabarit_id = G.gabarit_id
					AND G.fk_default_rubrique_id = R.rubrique_id
					ORDER BY rubrique_id ASC
					LIMIT 0,1
					';

	$row = GetSingle($sql);
	
	if(!count($row)) {
		$sql = 'SELECT R.rubrique_id
					FROM s_rubrique AS R , s_gabarit AS G
					WHERE G.gabarit_classe LIKE '.sql($gab).'
					AND R.fk_gabarit_id = G.gabarit_id
					ORDER BY rubrique_id ASC
					LIMIT 0,1
					';
		$row = GetSingle($sql);
	}
	
	return $row['rubrique_id'];
}


/**
 * Retourne le texte en remplacant les espaces sécables par des espaces insécables
 *
 * @param string $texte
 * @return string
 */
function nbsp($texte) {
	return str_replace(' ','&nbsp;',$texte);
}



/**
 * Autant d'arguments voulus possibles, 
 * on les parcourt dans l'ordre 
 * des qu'un n'est pas nul on le retourne
 *
 * @param unknown_type $a
 * @return unknown
 */
function choose($a) {
    $argc = func_num_args();
    for ($i = 0; $i < $argc; $i++) {
        $arg = func_get_arg($i);
        if (($arg) && $arg != "0000-00-00" && $arg != "0000-00-00 00:00:00") {
            return $arg;
        }
    }

    return null;
}


/**
 * Vérifie si la valeur correspond à une valeur de validation :
 * 1 oui yes o y on
 *
 * @param unknown_type $val
 * @return unknown
 */
function isTrue($val) {
	$val = strtolower($val);
	$arrayTrue = array('1','oui','yes','o','y','on', 'true');
	return in_array($val,$arrayTrue);
	
}

/**
 * Retourne l'adresse absolue du serveur
 * @return string
 */
function getServerUrl () {
	return 'http'.($_SERVER['HTTPS'] == 'on' ? 's' : '').'://'.$_SERVER['HTTP_HOST'];
}


/**
 * Inclu les classes nécessaires
 * pour l'envoi de mails via PHPMAILER
 * et retourne un phpmailer configuré
 * 
 * @return PHPMailer PHPMailer
 */

function includeMail() {
	
	/**
	 * Inclusion
	 */
	if(!akev($GLOBALS,'mailIncluded')) {
		$GLOBALS['mailIncluded'] = true;
		$GLOBALS['gb_obj']->includeFile('class.phpmailer.php','classes/mail');		
	}
	
	/**
	 * Objet
	 */
	$m = new PHPMailer();
	
	/**
	 * Si on est en SMTP
	 */
	if(getParam('mail_type') == 'smtp') {
		$GLOBALS['gb_obj']->includeFile('class.smtp.php','classes/mail');		
		$m->IsSMTP();
		$m->Host = getParam('mail_host');
	
	} else {
		$m->IsMail();
	}
	
	/**
	 * Valeurs par défaut
	 */
	$m->From = t('mail_from');
	$m->FromName = t('mail_from_name');
	$m->CharSet = 'UTF-8';
	//print_r($m);
	return $m;
	
}


/**
 * Check if a plugin exists and is installed
 *
 * @param nom $nom
 * @return bool
 */
function pluginExists($nom) {
	return ake($GLOBALS['site']->plugins,$nom);	
}


function url($url) {
	
	if(strpos($url,'http') !== false) {
		return $url;
	}
	else {
		return 'http://'.$url;
	}
}

function printDebugs() {
	/**********
		DEBUG DES REQUETES SQL
		util uniquement en préprod
	 ***/
	global $_Gconfig,$profileSTR,$h_sqls,$h_sqlsI;
	
	if($_Gconfig['debugSql']) {
		
		p('<div id="debugsql" style="border:1px solid;background:#fff;color:#000;position:fixed;bottom:0;left:0;width:800px;height:500px;overflow:auto;text-align:left;padding:5px;font-family:Courier new;monospace;font-size:1.3em;">');
		p('<a href="#" onclick="javascript:gid(\'debugsql\').style.height= gid(\'debugsql\').style.height == \'20px\' ? \'500px\' : \'20px\' ;" style="float:right;">CLOSE</a>');
		ksort($h_sqlsI);
			foreach($h_sqlsI as $k=>$sql) {
				print('<div style="padding:5px;">'.$k.' - '.$sql.'</div>');				
			}
			
			print('<hr/>');
			
			foreach($h_sqls as $k=>$sql) {
				$bg = $k%2?'white':'lightgray';
				if(round($sql['time'],2) >= 0.009) {
					$bg = 'red;color:white';
				}
				$info = '';
				if(ake($sql['sql'],$donesSql)) {
					$info = '[<strong style="background:yellow">DEJA FAITE LIGNE :'.$donesSql[$sql['sql']].'</strong>]';
				}
				print('<div style="background:'.$bg.';padding:5px;">'.$k.' - '.$info.' '.$sql['time'].' : '.$sql['sql'] .''.$sql['profile'].'</div>');
				$donesSql[$sql['sql']] = $k;
	
			}
		p('</div>');
	
	}
	
	if(ake($GLOBALS,'ocms_warnings')) {
		foreach($GLOBALS['ocms_warnings'] as $v) {
			echo '<div class="ocms_warnings">'.$v.'</div>';
		}
	}
	
	/**
	 * profiling des temps d'execution des scripts
	 */
	print($profileSTR);
	
	/**
	 * Stats de temps PHP, SQL, ...
	 * Affichés uniquement pour OPIXIDO
	 */
	GetStats();
}



function GetTitleFromTable($table,$separator=" ") {
   global $tabForms;
	$fields = getTabField($table);
	
	/**
	 * Si on a plusieurs champs titre
	 */
   if(!is_array($tabForms[$table]['titre'])) {
   		$tabForms[$table]['titre'] = array($tabForms[$table]['titre']);
   }
   	
   $titre = '';
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


    return $titre;
}


/**
 * Fonctionne à l'inverse de striptags, à savoir ne supprime QUE les tags passés en paramètre
 *
 * @param string $text
 * @param array $tags
 * @return string
 */
function strip_selected_tags($str, $tags = "", $stripContent = false)
{
    preg_match_all("/<([^>]+)>/i", $tags, $allTags, PREG_PATTERN_ORDER);
    foreach ($allTags[1] as $tag) {
        $replace = "%(<$tag.*?>)(.*?)(<\/$tag.*?>)%is";
        $replace2 = "%(<$tag.*?>)%is";
        echo $replace;
        if ($stripContent) {
            $str = preg_replace($replace,'',$str);
            $str = preg_replace($replace2,'',$str);
        }
            $str = preg_replace($replace,'${2}',$str);
            $str = preg_replace($replace2,'${2}',$str);
    }
    return $str;
} 


function isNull($str) {
	
	if($str == "NULL")
		return true;	
	if($str == false)
		return true;
	if($str == "")
		return true;
	if($str == "0")
		return true;
	if($str == "0000-00-00")
		return true;
	if($str == "0000-00-00 00:00:00")
		return true;
		
	return false;
}

function xss($str) {

	return htmlentities($str,ENT_QUOTES,'utf-8');

}



function __autoload($classe) {
	$GLOBALS['gb_obj']->includeFile($classe.'.php','autoload');
}
/*
function getRubArticles($rub_id){
	
	$sql = 'SELECT article_id
			FROM motif_article AS A, motif_r_rubrique_article AS R
			WHERE A.article_id = R.fk_article_id
			AND R.fk_rubrique_id = '.sql($rub_id);
	
	$res = GetAll($sql);
	
	$return = array();
	
	foreach ($res as $r)
		$return[] = $r['article_id'];
		
	return $return;
	
}*/


function getAllPictos($size="32x32") {
	$dir = str_replace(ADMIN_URL,'',ADMIN_PICTOS_FOLDER);
	$pictosDir = array(
	$dir.''.$size.'/actions/',
			$dir.$size.'/apps/',
			$dir.$size.'/categories/',
			$dir.$size.'/devices/',
			$dir.$size.'/emblems/',
			$dir.$size.'/emotes/',
			$dir.$size.'/mimetypes/',
			$dir.$size.'/places/',
			$dir.$size.'/status/'
			
			);
			foreach($pictosDir as $v) {
				$res = dir($v);
				//echo '<p>'.$v.'</p>';
				if($res) {
				while (false !== ($entry = $res->read())) {					
					if(is_file($v.$entry) && substr($entry,-3) == 'png' && filesize($v.$entry) > 0) {
						$tab[] = $v.$entry;
					}
				}
				}
				
			}
			return $tab;
}



function getGabaritClass($gab,$param='',$instanciate=true) {
	
	$className = $gab['gabarit_classe'];

	ob_start();
	
	$dossier = ($gab['gabarit_plugin']) ? path_concat('plugins',$gab['gabarit_plugin']) :'bdd';
	
	$GLOBALS['gb_obj']->includeFile($className . '.php', $dossier);


	if(class_exists($className)) {
		
		if($instanciate) {
			
			if ($gab['gabarit_classe_param']) {
				$param .= ','.$gab['gabarit_classe_param'];
			}
			
			$bddClasse = new $className($GLOBALS['site'],$param. ','.$gab['gabarit_classe_param'], $this);
		} else {
			return $className;
		}

	} else {
		derror('La classe associee n\'existe pas : '.$className);
	}

	$htTemp = ob_get_contents();

	ob_end_clean();
	
	return $bddClasse;
}


function getObjUrl() {

	$t = $_GET['curTable'];
	$i = $_GET['curId'];
	
	if(!$t ||!$i) {
		return false;
	}
	
	if($t == 's_rubrique') {
		$r = getRealForRubrique($i);
		return getUrlFromId($r['rubrique_id'],LG,array(),('editer'));
	}
	
	else {
		global $tabForms;
		if($tabForms[$t]['view']) {
			$id = getRubFromGabarit($tabForms[$t]['view']['gabarit'],$tabForms[$t]['view']['gabaritparam']);
			return getUrlFromId($id,LG,array($tabForms[$t]['view']['clef']=>$i));
		}
		
	}
	
}


function br2nl($string){
  $return=eregi_replace('<br[[:space:]]*/?'.
    '[[:space:]]*>',"\n ",$string);
  $return=str_replace('<p'," \n <p",$return);    
  $return=str_replace('<div'," \n <div",$return);   

  return $return;
} 

function myStripTags($str,$allow='') {
	$str = br2nl($str);
	$str = strip_tags($str,$allow);
	return $str;
}


function xmlencode($str) {
	$str = str_replace('&amp;','[ETAMP]',$str);	
	$str = str_replace(array('&','[ETAMP]'),'&amp;',$str);
	return $str;	

}
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
 * Insere des traductions pour tous les champs traductibles d'un enregistrement
 *
 * @param unknown_type $table
 * @param unknown_type $id
 * @param unknown_type $lg
 */
function addTranslations($table, $id, $lg) {

    $fields = getTranslatedFields($table);

    foreach ($fields as $field) {
        TrySql('INSERT INTO `s_traduction`
						( `fk_table` , `fk_id` , `fk_champ` , `fk_langue_id` , `traduction_texte` )
						VALUES
						("' . $table . '","' . $id . '","' . $field . '","' . addmyslashes($lg) . '","")');
    }
}

/**
 * Retourne tous les champs traductibles d'une table
 *
 * @param unknown_type $table
 * @return unknown
 */
function getTranslatedFields($table) {

    $fields = getTabField($table);
    $lgfields = array();
    foreach ($fields as $k => $v) {
        if (substr($k, strrpos($k, '_'), strlen('_' . LG_DEF)) == '_' . LG_DEF) {
            $lgfields[] = substr($k, 0, strrpos($k, '_'));
        }
    }

    return $lgfields;
}

/**
 * Retourne le nom de la langue du champ passé en parametre
 *
 * @param unknown_type $champ
 * @return unknown
 */
function getLgFromField($champ) {

    return substr($champ, strrpos($champ, '_') + 1);
}

function getBaseLgField($champ) {

    if (isLgField($champ)) {
        return substr($champ, 0, -3);
    } else {
        return $champ;
    }
}

/**
 * Met a jour la traduction d'un texte
 *
 * @param unknown_type $table
 * @param unknown_type $id
 * @param unknown_type $champ
 * @param unknown_type $valeur
 * @return unknown
 */
function updateLgField($table, $id, $champ, $valeur) {
    $name = $champ;

    $tab_field = getTabField($table);
    if (!ake($tab_field, $name) && strlen($table) && strlen($id) && strlen($champ)) {


        //debug('ok');
        $lg = getLgFromField($name);
        $nname = substr($name, 0, strrpos($name, '_'));

        $valeur = $valeur == 'NULL' ? '' : $valeur;

        $sql = 'UPDATE s_traduction SET traduction_texte = "' . addmyslashes($valeur) . '" WHERE fk_table = "' . $table . '" AND fk_champ =  "' . $nname . '" and fk_id = "' . $id . '" AND fk_langue_id = "' . $lg . '" ';
        DoSql(($sql));



        if (!Affected_Rows()) {
            TrySql('INSERT INTO s_traduction VALUES ( "' . $table . '", "' . $id . '","' . $nname . '",  "' . $lg . '" ,"")');
            DoSql($sql);
        }

        //debug($sql);
        $name = '';
        return true;
    }
    return false;
}

/**
 * Retourne la traduction d'un texte dans une langue donnée
 *
 * @param unknown_type $table
 * @param unknown_type $id
 * @param unknown_type $champ
 * @param unknown_type $lg
 * @return unknown
 */
function getTradValue($table, $id, $champ, $lg = '') {
    global $_Gconfig;
    if (!strlen($lg)) {
        $lg = substr($champ, strrpos($champ, '_') + 1);
        $champ = substr($champ, 0, strrpos($champ, '_'));
    }

    if (in_array($lg, $_Gconfig['LANGUAGES'])) {
        $sql = 'SELECT ' . $champ . '_' . $lg . ' AS traduction_texte FROM ' . $table . ' WHERE ' . getPrimaryKey($table) . ' = ?';
        $res = GetAllArr($sql, array($id));
    } else {
        $sql = 'SELECT traduction_texte FROM s_traduction WHERE fk_table = ? AND fk_id = ? AND fk_champ = ? AND fk_langue_id = ?';
        $res = GetAllArr($sql, array($table, $id, $champ, $lg));
        if (!count($res))
            return false;
    }

    return $res[0]['traduction_texte'];
}

/**
 * Definit si le nom du champ passé en paramètre est le champ de la langue par défaut pour cette information
 *
 * @param string $champ
 * @return bool
 */
function isDefaultLgField($champ) {

    if (substr($champ, -strlen('_' . LG_DEF)) == '_' . LG_DEF)
        return true;
    else
        return false;
}

function isLgField($champ) {
    global $_Gconfig;
    $lastpos = strrpos($champ, '_');
    $lpos = substr($champ, $lastpos + 1);
    if (in_array($lpos, $_Gconfig['LANGUAGES'])) {
        return $lpos;
    } else {
        return false;
    }
}

/**
 * Retournes les langues pour  esquels il y a des enregistreement
 * dans la table s_traduction pour un table et un champ donné
 *
 * @param string $table
 * @param string $champ
 * @return array
 */
function getLanguages($table, $id, $champ = '') {

    $sql = 'SELECT DISTINCT(fk_langue_id) FROM s_traduction WHERE fk_table = "' . $table . '" AND fk_id = "' . $id . '" ';
    if (strlen($champ)) {
        $sql .= ' AND fk_champ = "' . $champ . '" ';
    }
    $res = GetAll($sql);
    $lgs = array();
    foreach ($res as $row) {
        $lgs[] = $row['fk_langue_id'];
    }

    return $lgs;
}

/**
 * Retourne un tableau de toutes les langues disponibles pour le champ donné
 *
 * @param str $champ
 * @param array $row
 * @param str $table
 */
function getLgsValues($champ, $row, $table) {
    global $_Gconfig;

    $values = array();
    if (isLgField($champ)) {
        if (isDefaultLgField($champ)) {
            $champ = fieldWithoutLg($champ);
            reset($_Gconfig['LANGUAGES']);
            $values = array();
            foreach ($_Gconfig['LANGUAGES'] as $v) {
                $values[$v] = $row[$champ . '_' . $v];
            }

            reset($_Gconfig['LANGUAGES']);
            $id = akev($row, getPrimaryKey($table));
            $otherlgs = getLanguages($table, $id, $champ);
            foreach ($otherlgs as $v) {
                $values[$v] = getTradValue($table, $id, $champ, $v);
            }
        }
    } else {
        return array($row[$champ]);
    }

    return $values;
}

function fieldWithoutLg($champ) {
    if (isLgField($champ)) {
        return substr($champ, 0, strrpos($champ, '_'));
    } else {
        return $champ;
    }
}

/**
 * Retourne la traduction dans la seconde langue acceptable
 *
 * @param string $k
 * @param array $tab
 * @return string
 */
function getOtherLgValue($k, $tab) {
    return akev($tab, $k . '_' . getOtherLg());
}

/**
 * retourne la seconde langue acceptable
 *
 */
function getOtherLg() {

    global $_Gconfig;
    if (!defined('LG'))
        return $_Gconfig['LANGUAGES'][0];
    if (LG != LG_DEF)
        return LG_DEF;
    else if (count($_Gconfig['LANGUAGES']) > 1)
        return $_Gconfig['LANGUAGES'][1];
    else
        return LG;
}

$GLOBALS['otherLg'] = getOtherLg();

/**
 * Concatene tous les champs de langue pour le champ donnÃ© et crÃ©Ã© un
 * string SQL de requete sur ces champs pour la valeur $value
 *
 * @param string $field
 * @param string $value
 * @return string Partie de la requete SQL ex: AND rubrique_url_fr LIKE "XXX" AND rubrique_url_en LIKE "XXX"
 */
function lgFieldsLike($field, $value, $type = ' AND ') {
    global $_Gconfig;
    $lgs = $_Gconfig['LANGUAGES'];
    $str = ' AND ( ';
    foreach ($lgs as $k => $lg) {
        if ($k > 0) {
            $str.= ' ' . $type . ' ';
        }
        $str .= ' 	' . $field . '_' . $lg . ' LIKE "' . $value . '"  ';
    }
    return $str . ' ) ';
}

/**
 * Retourne la traduction du champ $k dans le tableau $tab en fonction de la langue courante
 *
 * @param string $k
 * @param array $tab
 * @param unknown_type $addspan
 * @return unknown
 */
function getLgValue($k, $tab, $addspan = '') {

    global $_Gconfig;

    $curLg = LG;

    if (!empty($GLOBALS['forceLG'])) {
        $curLg = $GLOBALS['forceLG'];
    }

    /**
     * Arguments inversés ... on devrait afficher une notice au moins ...
     */
    if (is_array($k) && !is_array($tab)) {
        $kk = $k;
        $k = $tab;
        $tab = $k;
    }

    /**
     * 
     */
    if (isUrlField($k)) {
        $addspan = false;
    }


    if ($k == 'rubrique_titre') {
        $ret = getDynamicTitle($tab);
        if ($ret)
            return $ret;
    }

    /**
     * Si on est dans la seconde langue /fr-SECONDELANGUE/
     */
    if (defined('TRADLG') && TRADLG) {
        $tables = getTables();
        $tableT = explode('_', $k);
        $found = false;
        /**
         * On cherche é quelle table appartient ce champ
         */
        for ($p = count($tableT); $p > 0; $p--) {
            $table = 't_' . implode('_', $tableT);
            //debug($tables);
            if (in_array($table, $tables)) {
                $found = true;
                break;
            } else {
                array_pop($tableT);
            }
        }

        /**
         * Normalement on a trouvé
         */
        if (!$found) {
            debug('NO MATCH FOUND FOR TRAD : ' . $k . ' / ' . $tab);
        } else {
            /**
             *
             */
            $id = $tab[getPrimaryKey($table)];
            $t = getTradValue($table, $id, $k, TRADLG);

            if ($t)
                return checkLgUrl($k, $t);
        }
    }


    /**
     * Si on l'a dans la langue normale
     */
    if (ake($tab, $k . '_' . $curLg) && strlen(trim(strip_tags($tab[$k . '_' . $curLg], '<img><iframe><embed><object><picture>')))) {

        if ($addspan) {

            return etcom($tab[$k . '_' . $curLg]);
        } else {

            return apost(checkLgUrl($k, $tab[$k . '_' . $curLg]));
        }
    }

    //if(is_object($GLOBALS['_gensite'])) {
    $olgv = getOtherLgValue($k, $tab);
    //debug($k.' : '.$olgv);
    //debug($tab);*/
    //}

    if (strlen($olgv)) {
        if ($addspan) {
            return checkLgUrl($k, $olgv);
            return '<span lang="' . $GLOBALS['_gensite']->getOtherLg() . '">' . etcom($olgv) . "</span>";
        } else {
            return checkLgUrl($k, $olgv);
        }
    }
}

/**
 * Vérifie si le titre de cette rubrique est censé etre dynamique
 *
 * @param unknown_type $res
 */
function getDynamicTitle($res) {

    if (rubHasOption(akev($res, 'rubrique_option'), 'dynTitle')) {
        $rgab = getGabarit($res['fk_gabarit_id']);
        $gab = $rgab['gabarit_classe'];
        if (!class_exists($gab))
            return false;
        $gab = new $gab($GLOBALS['site'], array());
        $t = $gab->genTitle();

        /* 	$classe = $gab['gabarit_classe'];
          $GLOBALS['gb_obj']->includeFile($classe.'.php','bdd');
          $t = eval('return ( '.$classe.'::genTitle("'.$res['rubrique_gabarit_param'].'"));');
         */
        return $t;
    } else
        return false;
}

/**
 * Retourne un row de gabarit en fonction de son identificant
 *
 * @param int $id
 * @return array
 */
function getGabarit($id) {
    return getRowFromId('s_gabarit', $id);
}

/**
 * Retourne un ROW de gabarit en fonction de sa classe
 *
 * @param str $classe
 * @return array
 */
function getGabaritByClass($classe) {
    $sql = 'SELECT * FROM s_gabarit WHERE gabarit_classe = ' . sql($classe);

    return GetSingle($sql);
}

/**
 * Cree l'url voulue si jamais le champ URL en question est du type @rubrique_id=XXX
 *
 * @param array $k tableau des valeurs
 * @param string $v Nom du champ
 * @return unknown
 */
function checkLgUrl($k, $v) {

    $k = $k . '_' . LG;

    if (isUrlField($k) || $k == 'link_' . LG) {
        return getLgUrl($v);
    } else {

        return $v;
    }
}

function isUrlField($field) {
    if (isset($GLOBALS['cache']['isUrlField'][$field])) {
        return $GLOBALS['cache']['isUrlField'][$field];
    }
    global $_Gconfig;
    $GLOBALS['cache']['isUrlField'][$field] = arrayInWord($_Gconfig['urlFields'], $field);
    return $GLOBALS['cache']['isUrlField'][$field];
}

function getLgUrl($v) {

    if (strstr($v, '@rubrique_id=') !== false) {

        $id = (int) str_replace('@rubrique_id=', '', $v);

        return getUrlFromId($id);
    } else {
        return $v;
    }
}

/**
 * Verifie le champ en question est le champ dans la langue par défaut
 *
 * @param unknown_type $field
 * @param unknown_type $table
 * @return unknown
 */
function isBaseLgField($field, $table, $tab = array()) {
    if (isset($GLOBALS['cache']['isBaseLgField'][$table . $field])) {
        return $GLOBALS['cache']['isBaseLgField'][$table . $field];
    }
    if (!$tab) {
        $tab = GetTabField($table);
    }
    $GLOBALS['cache']['isBaseLgField'][$table . $field] = false;
    if (ake($tab, $field . '_' . LG_DEF) && !ake($tab, $field)) {
        $GLOBALS['cache']['isBaseLgField'][$table . $field] = true;
    }
    return $GLOBALS['cache']['isBaseLgField'][$table . $field];
    ;
}

function myLocale($lg) {

    $add = '';
    if ($lg == 'de')
        $add = 'deu';
    else if ($lg == 'uk' || $lg == 'us') {
        $lg = 'en';
        $add = 'gb';
    }
    if ($lg == 'it') {
        $add = 'ita';
    }



    if (!is_array($lg)) {
        $lgu = strtoupper($lg);
        $locale = setlocale(LC_ALL, $lg . '_' . $lgu . '.UTF8', '' . $lg . '_' . $lgu . '.UTF-8@euro', '' . $lg . '_' . $lgu . '', $lg, $add, 'en');
        if ($locale) {
            $GLOBALS['CURLOCALE'] = $locale;
        }
    }
}

/**
 * Remplace tous les caratères par leur equivalent en majuscule y compris les accents
 *
 * @param string $texte
 * @return string
 */
function majuscules($texte) {
    $suite = htmlentities($texte, ENT_NOQUOTES, 'UTF-8');
    $suite = ereg_replace('&amp;', '&', $suite);
    $suite = ereg_replace('&lt;', '<', $suite);
    $suite = ereg_replace('&gt;', '>', $suite);
    $texte = '';
    if (ereg('^(.*)&([A-Za-z])([a-zA-Z]*);(.*)$', $suite, $regs)) {
        $texte .= majuscules($regs[1]);
        $suite = $regs[4];
        $carspe = $regs[2];
        $accent = $regs[3];
        if (ereg('^(acute|grave|circ|uml|cedil|slash|caron|ring|tilde|elig)$', $accent))
            $carspe = strtoupper($carspe);
        if ($accent == 'elig')
            $accent = 'Elig';
        $texte .= '&' . $carspe . $accent . ';';
    }
    $texte .= strtoupper($suite);

    $texte = html_entity_decode($texte, ENT_NOQUOTES, 'UTF-8');

    return $texte;
}

function apost($str) {
    return str_replace("'", "’", $str); //str_replace("","’",
}

global $_locale;
$_locale = array(
    'en' => array(
        'weekdays_short' => array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'),
        'weekdays_long' => array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
        'months_short' => array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'),
        'months_long' => array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')
    ), 'fr' => array(
        'weekdays_short' => array('Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'),
        'weekdays_long' => array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'),
        'months_short' => array('Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec'),
        'months_long' => array('Janvier', 'F&#xe9;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&#xfb;t', 'Septembre', 'Octobre', 'Novembre', 'D&#xe9;cembre')
    ), 'nl' => array(
        'weekdays_short' => array(),
        'weekdays_long' => array('Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'vrijdag', 'Samedi', 'Zondag'),
        'months_short' => array(),
        'months_long' => array('Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December')
    ), 'de' => array(
        'weekdays_short' => array(),
        'weekdays_long' => array('Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag'),
        'months_short' => array(),
        'months_long' => array('Januar', 'Februar', 'März', 'April', 'Mag', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember')
    ), 'es' => array(
        'weekdays_short' => array('Dom', 'Lun', 'Mar', 'Mi&#xe9;', 'Jue', 'Vie', 'S&#xe1;b'),
        'weekdays_long' => array('Domingo', 'Lunes', 'Martes', 'Mi&#xe9;rcoles', 'Jueves', 'Viernes', 'S&#xe1;bado', 'Domingo'),
        'months_short' => array('Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'),
        'months_long' => array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre')
        ));

$_locale = array(
    'en' => array(
        'weekdays_short' => array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'),
        'weekdays_long' => array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'),
        'months_short' => array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'),
        'months_long' => array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')
    ),
    'de' => array(
        'weekdays_short' => array('So', 'Mon', 'Di', 'Mi', 'Do', 'Fr', 'Sa'),
        'weekdays_long' => array('Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'),
        'months_short' => array('Jan', 'Feb', 'M&#xe4;rz', 'April', 'Mai', 'Juni', 'Juli', 'Aug', 'Sept', 'Okt', 'Nov', 'Dez'),
        'months_long' => array('Januar', 'Februar', 'M&#xe4;rz', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember')
    ),
    'fr' => array(
        'weekdays_short' => array('Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'),
        'weekdays_long' => array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'),
        'months_short' => array('Jan', 'F&#xe9;v', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Ao&#xfb;t', 'Sep', 'Oct', 'Nov', 'D&#xe9;c'),
        'months_long' => array('Janvier', 'F&#xe9;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&#xfb;t', 'Septembre', 'Octobre', 'Novembre', 'D&#xe9;cembre')
    ),
    'hu' => array(
        'weekdays_short' => array('V', 'H', 'K', 'Sze', 'Cs', 'P', 'Szo'),
        'weekdays_long' => array('vas&#xe1;rnap', 'h&#xe9;tf&#x151;', 'kedd', 'szerda', 'cs&#xfc;t&#xf6;rt&#xf6;k', 'p&#xe9;ntek', 'szombat'),
        'months_short' => array('jan', 'feb', 'm&#xe1;rc', '&#xe1;pr', 'm&#xe1;j', 'j&#xfa;n', 'j&#xfa;l', 'aug', 'szept', 'okt', 'nov', 'dec'),
        'months_long' => array('janu&#xe1;r', 'febru&#xe1;r', 'm&#xe1;rcius', '&#xe1;prilis', 'm&#xe1;jus', 'j&#xfa;nius', 'j&#xfa;lius', 'augusztus', 'szeptember', 'okt&#xf3;ber', 'november', 'december')
    ),
    'pl' => array(
        'weekdays_short' => array('Nie', 'Pn', 'Wt', '&#x15a;r', 'Czw', 'Pt', 'Sob'),
        'weekdays_long' => array('Niedziela', 'Poniedzia&#x142;ek', 'Wtorek', '&#x15a;roda', 'Czwartek', 'Pi&#x105;tek', 'Sobota'),
        'months_short' => array('Sty', 'Lut', 'Mar', 'Kwi', 'Maj', 'Cze', 'Lip', 'Sie', 'Wrz', 'Pa&#x17a;', 'Lis', 'Gru'),
        'months_long' => array('Stycze&#x144;', 'Luty', 'Marzec', 'Kwiecie&#x144;', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpie&#x144;', 'Wrzesie&#x144;', 'Pa&#x17a;dziernik', 'Listopad', 'Grudzie&#x144;')
    ),
    'sl' => array(
        'weekdays_short' => array('Ned', 'Pon', 'Tor', 'Sre', 'Cet', 'Pet', 'Sob'),
        'weekdays_long' => array('Nedelja', 'Ponedeljek', 'Torek', 'Sreda', 'Cetrtek', 'Petek', 'Sobota'),
        'months_short' => array('Jan', 'Feb', 'Mar', 'Apr', 'Maj', 'Jun', 'Jul', 'Avg', 'Sep', 'Okt', 'Nov', 'Dec'),
        'months_long' => array('Januar', 'Februar', 'Marec', 'April', 'Maj', 'Junij', 'Julij', 'Avgust', 'September', 'Oktober', 'November', 'December')
    ),
    'ru' => array(
        'weekdays_short' => array('&#x412;&#x441;', '&#x41f;&#x43d;', '&#x412;&#x442;', '&#x421;&#x440;', '&#x427;&#x442;', '&#x41f;&#x442;', '&#x421;&#x431;'),
        'weekdays_long' => array('&#x412;&#x43e;&#x441;&#x43a;&#x440;&#x435;&#x441;&#x435;&#x43d;&#x44c;&#x435;', '&#x41f;&#x43e;&#x43d;&#x435;&#x434;&#x435;&#x43b;&#x44c;&#x43d;&#x438;&#x43a;', '&#x412;&#x442;&#x43e;&#x440;&#x43d;&#x438;&#x43a;', '&#x421;&#x440;&#x435;&#x434;&#x430;', '&#x427;&#x435;&#x442;&#x432;&#x435;&#x440;&#x433;', '&#x41f;&#x44f;&#x442;&#x43d;&#x438;&#x446;&#x430;', '&#x421;&#x443;&#x431;&#x431;&#x43e;&#x442;&#x430;'),
        'months_short' => array('&#x42f;&#x43d;&#x432;', '&#x424;&#x435;&#x432;', '&#x41c;&#x430;&#x440;', '&#x410;&#x43f;&#x440;', '&#x41c;&#x430;&#x439;', '&#x418;&#x44e;&#x43d;', '&#x418;&#x44e;&#x43b;', '&#x410;&#x432;&#x433;', '&#x421;&#x435;&#x43d;', '&#x41e;&#x43a;&#x442;', '&#x41d;&#x43e;&#x44f;', '&#x414;&#x435;&#x43a;'),
        'months_long' => array('&#x42f;&#x43d;&#x432;&#x430;&#x440;&#x44c;', '&#x424;&#x435;&#x432;&#x440;&#x430;&#x43b;&#x44c;', '&#x41c;&#x430;&#x440;&#x442;', '&#x410;&#x43f;&#x440;&#x435;&#x43b;&#x44c;', '&#x41c;&#x430;&#x439;', '&#x418;&#x44e;&#x43d;&#x44c;', '&#x418;&#x44e;&#x43b;&#x44c;', '&#x410;&#x432;&#x433;&#x443;&#x441;&#x442;', '&#x421;&#x435;&#x43d;&#x442;&#x44f;&#x431;&#x440;&#x44c;', '&#x41e;&#x43a;&#x442;&#x44f;&#x431;&#x440;&#x44c;', '&#x41d;&#x43e;&#x44f;&#x431;&#x440;&#x44c;', '&#x414;&#x435;&#x43a;&#x430;&#x431;&#x440;&#x44c;')
    ),
    'es' => array(
        'weekdays_short' => array('Dom', 'Lun', 'Mar', 'Mi&#xe9;', 'Jue', 'Vie', 'S&#xe1;b'),
        'weekdays_long' => array('Domingo', 'Lunes', 'Martes', 'Mi&#xe9;rcoles', 'Jueves', 'Viernes', 'S&#xe1;bado'),
        'months_short' => array('Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'),
        'months_long' => array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre')
    ),
    'da' => array(
        'weekdays_short' => array('S&#xf8;n', 'Man', 'Tir', 'Ons', 'Tor', 'Fre', 'L&#xf8;r'),
        'weekdays_long' => array('S&#xf8;ndag', 'Mandag', 'Tirsdag', 'Onsdag', 'Torsdag', 'Fredag', 'L&#xf8;rdag'),
        'months_short' => array('Jan', 'Feb', 'Mar', 'Apr', 'Maj', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'),
        'months_long' => array('Januar', 'Februar', 'Marts', 'April', 'Maj', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'December')
    ),
    'is' => array(
        'weekdays_short' => array('Sun', 'M&#xe1;n', '&#xde;ri', 'Mi&#xf0;', 'Fim', 'F&#xf6;s', 'Lau'),
        'weekdays_long' => array('Sunnudagur', 'M&#xe1;nudagur', '&#xde;ri&#xf0;judagur', 'Mi&#xf0;vikudagur', 'Fimmtudagur', 'F&#xf6;studagur', 'Laugardagur'),
        'months_short' => array('Jan', 'Feb', 'Mar', 'Apr', 'Ma&#xed;', 'J&#xfa;n', 'J&#xfa;l', '&#xc1;g&#xfa;', 'Sep', 'Okt', 'N&#xf3;v', 'Des'),
        'months_long' => array('Jan&#xfa;ar', 'Febr&#xfa;ar', 'Mars', 'Apr&#xed;l', 'Ma&#xed;', 'J&#xfa;n&#xed;', 'J&#xfa;l&#xed;', '&#xc1;g&#xfa;st', 'September', 'Okt&#xf3;ber', 'N&#xf3;vember', 'Desember')
    ),
    'it' => array(
        'weekdays_short' => array('Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'),
        'weekdays_long' => array('Domenica', 'Luned&#xec;', 'Marted&#xec;', 'Mercoled&#xec;', 'Gioved&#xec;', 'Venerd&#xec;', 'Sabato'),
        'months_short' => array('Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'),
        'months_long' => array('Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre')
    ),
    'sk' => array(
        'weekdays_short' => array('Ned', 'Pon', 'Uto', 'Str', '&#x8a;tv', 'Pia', 'Sob'),
        'weekdays_long' => array('Nede&#x17e;a', 'Pondelok', 'Utorok', 'Streda', '&#x8a;tvrtok', 'Piatok', 'Sobota'),
        'months_short' => array('Jan', 'Feb', 'Mar', 'Apr', 'M&#xe1;j', 'J&#xfa;n', 'J&#xfa;l', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'),
        'months_long' => array('Janu&#xe1;r', 'Febru&#xe1;r', 'Marec', 'Apr&#xed;l', 'M&#xe1;j', 'J&#xfa;n', 'J&#xfa;l', 'August', 'September', 'Okt&#xf3;ber', 'November', 'December')
    ),
    'cs' => array(
        'weekdays_short' => array('Ne', 'Po', '&#xda;t', 'St', '&#x10c;t', 'P&#xe1;', 'So'),
        'weekdays_long' => array('Ned&#x11b;le', 'Pond&#x11b;l&#xed;', '&#xda;ter&#xfd;', 'St&#x159;eda', '&#x10c;tvrtek', 'P&#xe1;tek', 'Sobota'),
        'months_short' => array('Led', '&#xda;no', 'B&#x159;e', 'Dub', 'Kv&#x11b;', '&#x10c;en', '&#x10c;ec', 'Srp', 'Z&#xe1;&#x159;', '&#x158;&#xed;j', 'Lis', 'Pro'),
        'months_long' => array('Leden', '&#xda;nor', 'B&#x159;ezen', 'Duben', 'Kv&#x11b;ten', '&#x10c;erven', '&#x10c;ervenec', 'Srpen', 'Z&#xe1;&#x159;&#xed;', '&#x158;&#xed;jen', 'Listopad', 'Prosinec')
    ),
    'hy' => array(
        'weekdays_short' => array('&#x53f;&#x580;&#x56f;', '&#x535;&#x580;&#x56f;', '&#x535;&#x580;&#x584;', '&#x549;&#x580;&#x584;', '&#x540;&#x576;&#x563;', '&#x548;&#x582;&#x580;', '&#x547;&#x562;&#x569;'),
        'weekdays_long' => array('&#x53f;&#x56b;&#x580;&#x561;&#x56f;&#x56b;', '&#x535;&#x580;&#x56f;&#x578;&#x582;&#x577;&#x561;&#x562;&#x569;&#x56b;', '&#x535;&#x580;&#x565;&#x584;&#x577;&#x561;&#x562;&#x569;&#x56b;', '&#x549;&#x578;&#x580;&#x565;&#x584;&#x577;&#x561;&#x562;&#x569;&#x56b;', '&#x540;&#x56b;&#x576;&#x563;&#x577;&#x561;&#x562;&#x569;&#x56b;', '&#x548;&#x582;&#x580;&#x562;&#x561;&#x569;', '&#x547;&#x561;&#x562;&#x561;&#x569;'),
        'months_short' => array('&#x540;&#x576;&#x57e;', '&#x553;&#x57f;&#x580;', '&#x544;&#x580;&#x57f;', '&#x531;&#x57a;&#x580;', '&#x544;&#x575;&#x57d;', '&#x540;&#x576;&#x57d;', '&#x540;&#x56c;&#x57d;', '&#x555;&#x563;&#x57d;', '&#x54d;&#x57a;&#x57f;', '&#x540;&#x56f;&#x57f;', '&#x546;&#x575;&#x574;', '&#x534;&#x56f;&#x57f;'),
        'months_long' => array('&#x540;&#x578;&#x582;&#x576;&#x57e;&#x561;&#x580;', '&#x553;&#x565;&#x57f;&#x580;&#x57e;&#x561;&#x580;', '&#x544;&#x561;&#x580;&#x57f;', '&#x531;&#x57a;&#x580;&#x56b;&#x56c;', '&#x544;&#x561;&#x575;&#x56b;&#x57d;', '&#x540;&#x578;&#x582;&#x576;&#x56b;&#x57d;', '&#x540;&#x578;&#x582;&#x56c;&#x56b;&#x57d;', '&#x555;&#x563;&#x578;&#x57d;&#x57f;&#x578;&#x57d;', '&#x54d;&#x565;&#x57a;&#x57f;&#x565;&#x574;&#x562;&#x565;&#x580;', '&#x540;&#x578;&#x56f;&#x57f;&#x565;&#x574;&#x562;&#x565;&#x580;', '&#x546;&#x578;&#x575;&#x565;&#x574;&#x562;&#x565;&#x580;', '&#x534;&#x565;&#x56f;&#x57f;&#x565;&#x574;&#x562;&#x565;&#x580;')
    ),
    'nl' => array(
        'weekdays_short' => array('Zo', 'Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za'),
        'weekdays_long' => array('Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag'),
        'months_short' => array('Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'),
        'months_long' => array('Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December')
    ),
    'et' => array(
        'weekdays_short' => array('P', 'E', 'T', 'K', 'N', 'R', 'L'),
        'weekdays_long' => array('P&#xfc;hap&#xe4;ev', 'Esmasp&#xe4;ev', 'Teisip&#xe4;ev', 'Kolmap&#xe4;ev', 'Neljap&#xe4;ev', 'Reede', 'Laup&#xe4;ev'),
        'months_short' => array('Jaan', 'Veebr', 'M&#xe4;rts', 'Aprill', 'Mai', 'Juuni', 'Juuli', 'Aug', 'Sept', 'Okt', 'Nov', 'Dets'),
        'months_long' => array('Jaanuar', 'Veebruar', 'M&#xe4;rts', 'Aprill', 'Mai', 'Juuni', 'Juuli', 'August', 'September', 'Oktoober', 'November', 'Detsember')
    ),
    'tr' => array(
        'weekdays_short' => array('Paz', 'Pzt', 'Sal', '&#xc7;ar', 'Per', 'Cum', 'Cts'),
        'weekdays_long' => array('Pazar', 'Pazartesi', 'Sal&#x131;', '&#xc7;ar&#x15f;amba', 'Per&#x15f;embe', 'Cuma', 'Cumartesi'),
        'months_short' => array('Ock', '&#x15e;bt', 'Mrt', 'Nsn', 'Mys', 'Hzrn', 'Tmmz', 'A&#x11f;st', 'Eyl', 'Ekm', 'Ksm', 'Arlk'),
        'months_long' => array('Ocak', '&#x15e;ubat', 'Mart', 'Nisan', 'May&#x131;s', 'Haziran', 'Temmuz', 'A&#x11f;ustos', 'Eyl&#xfc;l', 'Ekim', 'Kas&#x131;m', 'Aral&#x131;k')
    ),
    'no' => array(
        'weekdays_short' => array('S&#xf8;n', 'Man', 'Tir', 'Ons', 'Tor', 'Fre', 'L&#xf8;r'),
        'weekdays_long' => array('S&#xf8;ndag', 'Mandag', 'Tirsdag', 'Onsdag', 'Torsdag', 'Fredag', 'L&#xf8;rdag'),
        'months_short' => array('Jan', 'Feb', 'Mar', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Des'),
        'months_long' => array('Januar', 'Februar', 'Mars', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Desember')
    ),
    'eo' => array(
        'weekdays_short' => array('Dim', 'Lun', 'Mar', 'Mer', '&#x134;a&#x16D;', 'Ven', 'Sab'),
        'weekdays_long' => array('Diman&#x109;o', 'Lundo', 'Mardo', 'Merkredo', '&#x134;a&#x16D;do', 'Vendredo', 'Sabato'),
        'months_short' => array('Jan', 'Feb', 'Mar', 'Apr', 'Maj', 'Jun', 'Jul', 'A&#x16D;g', 'Sep', 'Okt', 'Nov', 'Dec'),
        'months_long' => array('Januaro', 'Februaro', 'Marto', 'Aprilo', 'Majo', 'Junio', 'Julio', 'A&#x16D;gusto', 'Septembro', 'Oktobro', 'Novembro', 'Decembro')
    ),
    'ua' => array(
        'weekdays_short' => array('&#x41d;&#x434;&#x43b;', '&#x41f;&#x43d;&#x434;', '&#x412;&#x442;&#x440;', '&#x421;&#x440;&#x434;', '&#x427;&#x442;&#x432;', '&#x41f;&#x442;&#x43d;', '&#x421;&#x431;&#x442;'),
        'weekdays_long' => array('&#x41d;&#x435;&#x434;&#x456;&#x43b;&#x44f;', '&#x41f;&#x43e;&#x43d;&#x435;&#x434;&#x456;&#x43b;&#x43e;&#x43a;', '&#x412;&#x456;&#x432;&#x442;&#x43e;&#x440;&#x43e;&#x43a;', '&#x421;&#x435;&#x440;&#x435;&#x434;&#x430;', '&#x427;&#x435;&#x442;&#x432;&#x435;&#x440;', '&#x41f;\'&#x44f;&#x442;&#x43d;&#x438;&#x446;&#x44f;', '&#x421;&#x443;&#x431;&#x43e;&#x442;&#x430;'),
        'months_short' => array('&#x421;&#x456;&#x447;', '&#x41b;&#x44e;&#x442;', '&#x411;&#x435;&#x440;', '&#x41a;&#x432;&#x456;', '&#x422;&#x440;&#x430;', '&#x427;&#x435;&#x440;', '&#x41b;&#x438;&#x43f;', '&#x421;&#x435;&#x440;', '&#x412;&#x435;&#x440;', '&#x416;&#x43e;&#x432;', '&#x41b;&#x438;&#x441;', '&#x413;&#x440;&#x443;'),
        'months_long' => array('&#x421;&#x456;&#x447;&#x435;&#x43d;&#x44c;', '&#x41b;&#x44e;&#x442;&#x438;&#x439;', '&#x411;&#x435;&#x440;&#x435;&#x437;&#x435;&#x43d;&#x44c;', '&#x41a;&#x432;&#x456;&#x442;&#x435;&#x43d;&#x44c;', '&#x422;&#x440;&#x430;&#x432;&#x435;&#x43d;&#x44c;', '&#x427;&#x435;&#x440;&#x432;&#x435;&#x43d;&#x44c;', '&#x41b;&#x438;&#x43f;&#x435;&#x43d;&#x44c;', '&#x421;&#x435;&#x440;&#x43f;&#x435;&#x43d;&#x44c;', '&#x412;&#x435;&#x440;&#x435;&#x441;&#x435;&#x43d;&#x44c;', '&#x416;&#x43e;&#x432;&#x442;&#x435;&#x43d;&#x44c;', '&#x41b;&#x438;&#x441;&#x442;&#x43e;&#x43f;&#x430;&#x434;', '&#x413;&#x440;&#x443;&#x434;&#x435;&#x43d;&#x44c;')
    ),
    'ro' => array(
        'weekdays_short' => array('Dum', 'Lun', 'Mar', 'Mie', 'Joi', 'Vin', 'Sam'),
        'weekdays_long' => array('Duminica', 'Luni', 'Marti', 'Miercuri', 'Joi', 'Vineri', 'Sambata'),
        'months_short' => array('Ian', 'Feb', 'Mar', 'Apr', 'Mai', 'Iun', 'Iul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'),
        'months_long' => array('Ianuarie', 'Februarie', 'Martie', 'Aprilie', 'Mai', 'Iunie', 'Iulie', 'August', 'Septembrie', 'Octombrie', 'Noiembrie', 'Decembrie')
    ),
    'he' => array(
        'weekdays_short' => array('&#1512;&#1488;&#1513;&#1493;&#1503;', '&#1513;&#1504;&#1497;', '&#1513;&#1500;&#1497;&#1513;&#1497;', '&#1512;&#1489;&#1497;&#1506;&#1497;', '&#1495;&#1502;&#1497;&#1513;&#1497;', '&#1513;&#1497;&#1513;&#1497;', '&#1513;&#1489;&#1514;'),
        'weekdays_long' => array('&#1497;&#1493;&#1501; &#1512;&#1488;&#1513;&#1493;&#1503;', '&#1497;&#1493;&#1501; &#1513;&#1504;&#1497;', '&#1497;&#1493;&#1501; &#1513;&#1500;&#1497;&#1513;&#1497;', '&#1497;&#1493;&#1501; &#1512;&#1489;&#1497;&#1506;&#1497;', '&#1497;&#1493;&#1501; &#1495;&#1502;&#1497;&#1513;&#1497;', '&#1497;&#1493;&#1501; &#1513;&#1497;&#1513;&#1497;', '&#1513;&#1489;&#1514;'),
        'months_short' => array('&#1497;&#1504;&#1493;&#1488;&#1512;', '&#1508;&#1489;&#1512;&#1493;&#1488;&#1512;', '&#1502;&#1512;&#1509;', '&#1488;&#1508;&#1512;&#1497;&#1500;', '&#1502;&#1488;&#1497;', '&#1497;&#1493;&#1504;&#1497;', '&#1497;&#1493;&#1500;&#1497;', '&#1488;&#1493;&#1490;&#1493;&#1505;&#1496;', '&#1505;&#1508;&#1496;&#1502;&#1489;&#1512;', '&#1488;&#1493;&#1511;&#1496;&#1493;&#1489;&#1512;', '&#1504;&#1493;&#1489;&#1502;&#1489;&#1512;', '&#1491;&#1510;&#1502;&#1489;&#1512;'),
        'months_long' => array('&#1497;&#1504;&#1493;&#1488;&#1512;', '&#1508;&#1489;&#1512;&#1493;&#1488;&#1512;', '&#1502;&#1512;&#1509;', '&#1488;&#1508;&#1512;&#1497;&#1500;', '&#1502;&#1488;&#1497;', '&#1497;&#1493;&#1504;&#1497;', '&#1497;&#1493;&#1500;&#1497;', '&#1488;&#1493;&#1490;&#1493;&#1505;&#1496;', '&#1505;&#1508;&#1496;&#1502;&#1489;&#1512;', '&#1488;&#1493;&#1511;&#1496;&#1493;&#1489;&#1512;', '&#1504;&#1493;&#1489;&#1502;&#1489;&#1512;', '&#1491;&#1510;&#1502;&#1489;&#1512;')
    ),
    'sv' => array(
        'weekdays_short' => array('S&#xf6;n', 'M&#xe5;n', 'Tis', 'Ons', 'Tor', 'Fre', 'L&#xf6;r'),
        'weekdays_long' => array('S&#xf6;ndag', 'M&#xe5;ndag', 'Tisdag', 'Onsdag', 'Torsdag', 'Fredag', 'L&#xf6;rdag'),
        'months_short' => array('Jan', 'Feb', 'Mar', 'Apr', 'Maj', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'),
        'months_long' => array('Januari', 'Februari', 'Mars', 'April', 'Maj', 'Juni', 'Juli', 'Augusti', 'September', 'Oktober', 'November', 'December')
    ),
    'pt' => array(
        'weekdays_short' => array('Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'S&aacute;b'),
        'weekdays_long' => array('Domingo', 'Segunda-feira', 'Ter&ccedil;a-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'S&aacute;bado'),
        'months_short' => array('Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'),
        'months_long' => array('Janeiro', 'Fevereiro', 'Mar&ccedil;o', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro')
    )
);

$_locale['us'] = $_locale['uk'] = $_locale['en'];

function mystrftime($format, $timestamp = 0) {
    global $_locale;
    if ($timestamp == 0)
        $timestamp = time();

    @setlocale($GLOBALS['CURLOCALE']);

    $tab = $_locale[LG] ? $_locale[LG] : $_locale[LG_DEF];

    if ($format == '%B') {

        $moisnum = ((int) date('m', $timestamp)) - 1;

        return $tab['months_long'][$moisnum];
    } else if ($format == '%A') {

        $weekday = ((int) date('N', $timestamp));
        if ($weekday == 7) {
            $weekday = 0;
        }

        return $tab['weekdays_long'][$weekday];
    } else {
        return strftime($format, $timestamp);
    }
}

function strftimeloc($format, $timestamp = 0) {
    global $_locale;
    if ($timestamp == 0)
        $timestamp = time();

    @setlocale($GLOBALS['CURLOCALE']);

    $tab = $_locale[LG] ? $_locale[LG] : $_locale[LG_DEF];

    $moisnum = ((int) date('m', $timestamp)) - 1;
    $format = str_replace('%B', $tab['months_long'][$moisnum], $format);
    $weekday = ((int) date('N', $timestamp));
    if ($weekday == 7) {
        $weekday = 0;
    }
    $format = str_replace('%A', $tab['weekdays_long'][$weekday], $format);

    return strftime($format, $timestamp);
    //}
}

function compressCSS($buffer) {
    /* remove comments */
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    /* remove tabs, spaces, newlines, etc. */
    $buffer = str_replace(array("\r\n", "\r", "\n", "\t"), '', $buffer);
    $buffer = str_replace(array('  ', '    ', '    '), ' ', $buffer);
    $buffer = str_replace(array(' : ', ' :', ': '), ':', $buffer);
    $buffer = str_replace(array(' ; ', ' ;', '; '), ';', $buffer);
    $buffer = str_replace(array(' {'), '{', $buffer);
    $buffer = str_replace(array(' {'), '{', $buffer);
    $buffer = str_replace(array(' {'), '{', $buffer);
    $buffer = str_replace(array('{ '), '{', $buffer);
    $buffer = str_replace(array('{ '), '{', $buffer);
    $buffer = str_replace(array('{ '), '{', $buffer);
    $buffer = str_replace(array('{ '), '{', $buffer);
    $buffer = str_replace(array('} '), '}', $buffer);
    $buffer = str_replace(array('} '), '}', $buffer);
    $buffer = str_replace(array('} '), '}', $buffer);
    $buffer = str_replace(array(';}'), '}', $buffer);

    return $buffer;
}

function compressJs($buffer) {
    /* remove comments */


    // $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);

    /* remove tabs, spaces, newlines, etc. */
    //$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
    $buffer = str_replace(array(' : ', ' :', ': '), ':', $buffer);
    $buffer = str_replace(array(' ; ', ' ;', '; '), ';', $buffer);
    $buffer = str_replace(array(' {'), '{', $buffer);


    return $buffer;
}

/**
 * Insere une trad pour la valeur et la langue donnée
 * @global type $co
 * @param type $id
 * @param type $val
 * @param type $plugin
 * @param string $lg
 */
function insertTrad($id, $val, $plugin = '', $lg = false) {
    if ($lg === false) {
        $lg = LG_DEF;
    }
    global $co;
    $co->autoExecute('s_trad', array('trad_id' => $id, 'trad_' . $lg => $val, 'fk_plugin_id' => $plugin), 'INSERT');
}

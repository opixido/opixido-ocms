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

class GenForm {

    /**
     * **** variables ****
     */
    var $table;
    public $id;
    public $genInserter;
    var $table_name; // nom de la table
    var $nb_field; // nombre de champs de la table
    var $tab_field; // tableau regroupant le nom, la taille et le type de tout les champs de la table
    var $tab_default_field; // tableau regroupant la valeur par default de tout les champs de la table
    var $no_used_fields; // tableau regroupant le nom des champs qui ne sont pas utlis�
    var $form_method; // methode du formulaire ( POST | GET )
    var $language; // langue du formulaire
    var $dateFields = array(); // Liste des champs date pour initialisation du JS
    var $primary_key;
    var $neededFields = array();
    var $multiFields = array();
    var $separator;
    var $smallThumbWidth = 200;
    var $smallThumbHeight = 200;
    public $restricted = false;

    /**
     * genSecurity
     *
     * @var genSecurity
     */
    var $gs;

    /**
     * **** methodes ****
     */
    // initialise tout les champs de la classe
    // '_name' contient l'initilisation de 'table_name'
    // '_method' contient l'initilisation de 'form_method'
    // 'where_query' contient la clause where pour l'initilisation de 'tab_default_field'
    // 'language' contient la langue du formulaire
    function GenForm($_name, $_method, $_id, $_row = array()) {

        if (isset($_REQUEST['onlyLg'])) {
            $_SESSION['onlyLg'] = $_REQUEST['onlyLg'];
        }
        global $editMode;
        global $onlyData;

        global $gs_obj;
        global $_Gconfig;

        $this->gs = &$gs_obj;


        $this->table = $this->table_name = $_name;

        if (empty($_REQUEST['curTable'])) {
            $_REQUEST['curTable'] = $_name;
        }
        // debugtrace();

        $action = !$editMode ? 'edit' : 'view';

        if (!$this->gs->can($action, $this->table_name, '', $this->id)) {
            /* $this->gs->showError();
              die(); */
            // debug('Accès refusé : '.$_name.' : '.$_id);
            //        $editMode = true;
        }

        $this->separator = ', ';

        $this->useImageEditor = $_Gconfig['useImageEditor'];

        $this->useThumbs = $_Gconfig['useThumbs'];
        $this->thumbWidth = 570;
        $this->thumbHeight = 200;
        $this->pathAdminToSite = "../";
        $this->larg = "510";

        $this->showHelp = true;
        $this->showInserter = true;

        $this->imgExt = $_Gconfig['imageExtensions'];



        if ($onlyData)
            $editMode = 1;
        // * initialisation de 'table_name' *

        $this->id = $_id;
        $this->editMode = $editMode;
        $this->onlyData = $onlyData;

        $this->cachePK = array();

        $this->pk = $this->primary_key = $this->getPrimaryKey($this->table_name);


        $this->tab_field = $this->getTabField($this->table_name);



        // * initialisation de 'nb_field' *
        $this->nb_field = count($this->tab_field);
        // * initialisation de 'tab_default_field' *   On remplit le tableau des valeurs actuelles de l'enregistrement
        if (is_array($this->id))
            $this->setTabFromRow();
        //if ( $this->id && $this->id != "new" )
        if (count($_row) > 1)
            $this->tab_default_field = $_row;
        else
            $this->setTabDefaultField(' ' . $this->pk . ' = "' . $this->id . '"');

        if (isset($_SESSION[gfuid()]['nbLevels']) && (isset($_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]) &&
                $_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]['insertOtherField'] )
                || isset($_SESSION[gfuid()]['genform__add_sub_table'])) {

            global $relinv;
            reset($relinv);

            $otherTable = '';
            if (!empty($_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']])) {
                $otherTable = !empty($_SESSION[gfuid()]['genform__add_sub_table']) ? $_SESSION[gfuid()]['genform__add_sub_table'] : akev($_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']], 'curTable');
                $fk_id = !empty($_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]) && !empty($_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]['curId']) ? $fk_id = $_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']]['curId'] : $_SESSION[gfuid()]['genform__add_sub_id'];
            }
            //debug($otherTable);
            if (!empty($relinv[$otherTable])) {
                foreach ($relinv[$otherTable] as $v) {
                    if ($v[0] == $this->table)
                        $chp = $v[1];
                }
            }
            if (!empty($chp))
                $this->tab_default_field[$chp] = $fk_id;
        }



        // * initialisation de 'form_method' *
        $this->form_method = 'post';
        // * initialisation de 'language' *
        $this->language = LG;



        $this->getLgs();
    }

    /**
     * Retourne la liste des langues nécessaires à cet enregistrement
     * et créé les FAUX CHAMPS nécessaires
     *
     */
    function getLgs() {

        global $_Gconfig;


        if (!empty($_SESSION['onlyLg']) && $_SESSION['onlyLg'] != 'ALL') {
            $this->lgs = array($_SESSION['onlyLg']);
            return;
        }

        reset($_Gconfig['LANGUAGES']);

        $lgs = $_Gconfig['LANGUAGES'];

        if (!empty($_SESSION[gfuid()]['nbLevels'])) {
            $inf = $_SESSION[gfuid()]['levels'][$_SESSION[gfuid()]['nbLevels']];
            $sql = 'SELECT DISTINCT(fk_langue_id) 
						FROM s_traduction 
						WHERE fk_table LIKE "' . $inf['curTable'] . '" 
						AND fk_id = "' . $inf['curId'] . '"';
            $res = GetAll($sql);
            $lgfields = getTranslatedFields($this->table_name);

            foreach ($res as $row) {
                reset($lgfields);
                $lgs[] = $row['fk_langue_id'];
                foreach ($lgfields as $fi) {
                    $this->tab_field[$fi . '_' . $row['fk_langue_id']] = $this->tab_field[$fi . '_' . $lgs[0]];
                    $this->tab_default_field[$fi . '_' . $row['fk_langue_id']] = "";
                }
            }
        }

        $sql = 'SELECT traduction_texte, fk_langue_id, fk_champ FROM s_traduction WHERE fk_table = "' . $this->table_name . '" AND fk_id = "' . $this->id . '" ';
        $res = GetAll($sql);

        foreach ($res as $row) {
            $name = $row['fk_champ'];
            $lgs[] = $row['fk_langue_id'];
            $this->tab_field[$name . '_' . $row['fk_langue_id']] = $this->tab_field[$name . '_' . $lgs[0]];
            $this->tab_default_field[$name . '_' . $row['fk_langue_id']] = $row['traduction_texte'];
        }



        $this->lgs = array_unique($lgs);
    }

    function getTabField($table) {
        return getTabField($table);
    }

    function setTabFromRow() {
        $this->tab_default_field = $this->id;
        $this->id = $this->tab_default_field[$this->pk];
    }

    // rempli le tableau "tab_default_field" avec un enregistrement de la table
    function setTabDefaultField($where_query) {
        $row = GetSingle('SELECT * FROM ' . $this->table_name . ' WHERE ' . $where_query);

        if ($row) {
            $this->tab_default_field = $row;
        } else {
            foreach ($this->tab_field as $k => $v) {
                $this->tab_default_field[$k] = '';
            }
        }
        while (list($k, $v) = each($_REQUEST)) {
            if (strstr($k, 'genform_default__')) {
                //debug($k);
                $this->tab_default_field[str_replace('genform_default__', '', $k)] = $_REQUEST[$k];
            }
        }
    }

    // initialise le tableau "no_used_fields"
    function setNoUsedFields($tab) {
        $this->$no_used_fields = $tab;
    }

    /**
     * Retourne la traduction d'un champ
     * essai diff�rentes formes de traductions 
     * 
     *
     * @param unknown_type $txt
     * @param unknown_type $rel
     * @return unknown
     */
    function trad($txt, $rel = "") {

        return tradAdmin($txt, $rel, $this->table_name);
    }

    /**
     * Alias  de GetPrimaryKey
     *
     * @param unknown_type $table
     * @return unknown
     */
    function getPrimaryKey($table) {
        return getPrimaryKey($table);
    }

    /**
     * Concatene au buffer
     *
     * @param unknown_type $str
     */
    function addBuffer($str) {
        if ($str)
            $this->bufferPrint .= $str;

        if (!$this->onlyData)
            $this->bufferPrint .= "\n";
    }

    /**
     * Retourne le buffer
     *
     * @return unknown
     */
    function getBuffer() {
        return $this->bufferPrint;
    }

    /**
     * Alias de IsImage() 
     * definit si l'extension du fichier est de type image
     *
     * @param unknown_type $str
     * @return unknown
     */
    function isImage($str) {

        return isImage($str);
    }

    /**
     * Géneres les onglets de pages
     *
     */
    function genPages() {
        global $tabForms, $formsRep, $form, $_Gconfig;

        if (empty($tabForms[$this->table_name]["pages"])) {
            while (list($k, $v) = each($this->tab_field)) {
                if (strlen($k) > 2 && $k != $this->pk && $k != $_Gconfig['field_date_maj']) {
                    //debug($k.' : '.isBaseLgField($k,$this->table).' : '.getBaseLgField($k));
                    if (isLgField($k) && isDefaultLgField($k, $this->table)) {
                        $this->genlg(getBaseLgField($k));
                    } else if (!isLgField($k)) {
                        $this->gen($k);
                    }
                }
            }            
        } else {

            $i = 0;
            p('<div class="tab-content">');
            foreach ($tabForms[$this->table_name]["pages"] as $k => $page) {

                $this->fieldsDone = 0;
                $i++;
                $this->curPageLooping = $i;
                if (!$this->editMode) {
                    p('<div class="tab-pane ' . ($i == 1 || $i == akev($_REQUEST, 'curPage') + 1 ? 'active' : '') . '" id="genform_page_' . $i . '">');
                }

                if (!is_array($page)) {
                    $page = array($page);
                }

                foreach ($page as $p) {
                    if (!file_exists($formsRep . $p) || !is_file($formsRep . $p)) {
                        if (count($page) == 1) {
                            debug("Missing page : " . $formsRep . $p);
                        }
                    } else {
                        include($formsRep . $p);
                    }
                }

                if (!$this->editMode) {
                    p('</div>');
                }
            }
            p('</div>');
        }
    }

    /**
     * Retourne la liste des tabForms de TITRE pour les placer dans une clause ORDER
     *
     * @param unknown_type $titre
     * @return unknown
     */
    function getNomForOrder($titre) {
        return getNomForOrder($titre);
    }

    /**
     * Retourne la liste des TABFORMS de TITRE pour les placer dans une VALUE
     *
     * @param unknown_type $titre
     * @param unknown_type $row
     * @return unknown
     */
    function getNomForValue($titre, $row) {
        return getNomForValue($titre, $row);
    }

    /**
     * G�n�re la petite image d'aide
     *
     * @param unknown_type $idH
     */
    function genHelpImage($idH, $idt = '') {
        if ($this->showHelp) {
            if (tradExists('help_' . $idt) && $idt) {
                $idH = 'help_' . $idt;
            }
            $this->addBuffer('<div class="help"><i class="icon-question-sign" title=' . alt(t($idH)) . '></i>');
            $this->addBuffer(getEditTrad('help_' . $idt) . '</div>');
        }
    }

    /**
     * Comme GEN mais utilise tous les champs de langue applicables � cet enregistrement
     *
     * @param unknown_type $tab_name
     * @param unknown_type $fk_table
     * @param unknown_type $traduction
     * @param unknown_type $attributs
     * @param unknown_type $preValues
     */
    function genlg($tab_name, $fk_table = "", $traduction = "", $attributs = "", $preValues = array()) {


        global $_Gconfig;
        global $rteFields, $uploadRep, $neededFields, $neededSymbol, $fieldError, $uploadFields, $mailFields, $restrictedMode, $tabForms, $relations, $arbos, $tablerel, $relinv, $previewField, $orderFields, $specialUpload, $editMode, $functionField, $_Gconfig;


        $action = !$editMode ? 'edit' : 'view';
        $toHide = '';
        //debugopix("DOIT TESTER CHAMP $action $this->table_name $this->id $tab_name ");
        if (!$this->gs->can($action, $this->table_name, '', $this->id, $tab_name . '_' . LG_DEF) && !$this->gs->can($action, $this->table_name, '', $this->id, $tab_name)) {
            //debug($tab_name.'_'.LG_DEF);
            return false;
        }

        $this->bufferPrint = "";
        $name = $tab_name;
        $lgs = $this->lgs;


        $this->addBuffer('<div class="well genform_champ_out" id="genform_div_' . $tab_name . '">');

        if ($this->editMode && !$this->onlyData)
            $this->addBuffer('<div class="row-fluid"><div class="span3 label">'); //<label class="genform_txtres"><span >
        else if (!$this->onlyData && !$fieldError[$name])
            $this->addBuffer('<label class="genform_txt label">');
        else if (!$this->onlyData && $fieldError[$name])
            $this->addBuffer('<label class="genform_txt_error label label-important">');

        $this->printLabel($tab_name, $fk_table = "", $traduction = "", $attributs = "", $preValues = array());



        if (!$this->editMode && count($lgs) > 0) {
            reset($lgs);
            $this->addBuffer('&nbsp; &nbsp; ');

            foreach ($lgs as $lg) {
                $this->addBuffer('<a class="lgbtn btn btn-mini"  id="lgbtn_' . $name . '_' . $lg . '" onclick="showLgField(\'' . $name . '\',\'' . $lg . '\');"><img src="' . ADMIN_URL . 'img/flags/' . $lg . '.gif" alt="" /></a>');
            }
        }




        if (!$this->editMode) {
            $this->addBuffer('</label>');

            //$this->addBuffer('</div>');	

            reset($lgs);
            $lgdef = $lgs[0];

            foreach ($lgs as $lg) {
                $this->addBuffer('<div class="genform_champ lg_' . $lg . '" id="lgfield_' . $name . '_' . $lg . '">');
                $this->genFields($tab_name . '_' . $lg, $fk_table = "", $traduction = "", $attributs = "", $preValues = array());
                $this->addBuffer('</div>');
                if ($lg != $lgdef) {
                    $toHide .= '$("#lgfield_' . $name . '_' . $lg . '").hide();';
                }

                $_SESSION[gfuid()]['curFields'][] = $tab_name . '_' . $lg;
            }

            $lg = !empty($_SESSION['onlyLg']) && $_SESSION['onlyLg'] != 'ALL' ? $_SESSION['onlyLg'] : LG_DEF;

            $this->addBuffer('
			<script type="text/javascript">		
				' . $toHide . '	
				lgfieldcur["' . $name . '"] = "";
				showLgField("' . $name . '","' . $lg . '");
			</script>	
			');
        } else {
            $this->addBuffer('</div>');

            $this->addBuffer('<div class="span9">');

            foreach ($lgs as $lg) {

                $this->addBuffer('<div class="genform_champ"><img src="' . ADMIN_URL . 'img/flags/' . $lg . '.gif" alt="' . $lg . '" /> ');
                $this->genFields($tab_name . '_' . $lg, $fk_table = "", $traduction = "", $attributs = "", $preValues = array());
                $this->addBuffer('</div>');
            }

            $this->addBuffer('</div></div>');
        }

        $this->addBuffer('</div>');

        p($this->getbuffer());
    }

    function printEditTrad($nom) {


        $this->addBuffer(getEditTrad($nom));
    }

    function printLabel($tab_name, $fk_table = "", $traduction = "", $attributs = "", $preValues = array()) {


        global $_Gconfig, $rteFields, $uploadRep, $neededFields, $neededSymbol, $fieldError, $uploadFields, $mailFields, $restrictedMode, $tabForms, $relations, $arbos, $tablerel, $relinv, $previewField, $orderFields, $specialUpload, $editMode, $functionField, $_Gconfig;


        $action = !$editMode ? 'edit' : 'view';

        $name = $tab_name;

        $img = $alt = '';
        if (!$this->onlyData)


        /* Si on a des traductions ... On récupère le nom du champ dans les traductions */
            if (!$this->onlyData) {

                /* Image correspondante */

                $bas = getBaseLgField($name);
                if (tradExists('field_img_' . $name)) {
                    if (tradExists('field_help_' . $name))
                        $alt = t('field_help_' . $name);
                    else
                        $alt = "";

                    $img = t('field_img_' . $name);
                }


                if ($img) {
                    $this->addBuffer('<img style="vertical-align:middle" src="' . $img . '" alt="' . $alt . '" />&nbsp;');
                }

                if (isset($_SESSION['editTrads'])) {
                    if (!$img) {
                        $this->addBuffer('<img style="vertical-align:middle" src="pictos/media-playback-stop.png" alt="' . $alt . '" />&nbsp;');
                    }
                    $h = '';
                    $this->addBuffer('<input type="text" name="ET_field_img_' . $bas . '" style="display:none" onclick="window.fieldToUpdate=this;$(\'#divImgPicto\').css(\'top\',mouseY+\'px\').css(\'left\',mouseX+\'px\').slideToggle()" onchange=""  value="' . $img . '"/>');
                }

                if ($traduction != "")
                    $T = ( $this->trad($traduction) );
                else if ($fk_table)
                    $T = ( $this->trad($fk_table) );
                else
                    $T = $this->trad($name, $tab_name);


                $T = str_replace(array('(', ')'), array('<span class="petit">(', ')</span>'), $T);

                $this->addBuffer($T);
            }

        if (isNeeded($this->table, $name) && (!$this->editMode ))
            $this->addBuffer("" . $neededSymbol);

        $this->addBuffer('&nbsp;' . getEditTrad($tab_name));
    }

    // cree une ligne du formulaire en fonction du nom, de la taille et du type des champs
    // si c'est une cle etrangere, rempli un liste deroulante
    // "tab_name" contient la liste des champs present sur la ligne
    // "fk_table" contient le nom de la table ou sont stocker les choix de la liste deroulante
    // "fk_champ" contient le nom du champs
    // "fk_trad" contient le nom du champs de la table "fk_table" qui doit permetre de remplir la liste deroulante

    /**
     * GEN
     *
     * @param unknown_type $tab_name
     * @param unknown_type $fk_table
     * @param unknown_type $traduction
     * @param unknown_type $attributs
     * @param unknown_type $preValues
     * @return unknown
     */
    function gen($tab_name, $fk_table = "", $traduction = "", $attributs = "", $preValues = array()) {
        global $rteFields, $uploadRep, $neededFields, $neededSymbol, $fieldError, $uploadFields, $mailFields, $restrictedMode, $tabForms, $relations, $arbos, $tablerel, $relinv, $previewField, $orderFields, $specialUpload, $editMode, $functionField, $_Gconfig;

        $lastBuffer = '';
        $action = !$editMode ? 'edit' : 'view';

        $name = $tab_name;

        if (!$this->gs->can($action, $this->table_name, '', $this->id, $tab_name)) {

            return false;
        }


        $jsColor = '';



        $this->bufferPrint = "";
        // $new_key = substr($name, 3, strlen($name));

        $this->addBuffer('<div class="well genform_champ_out" id="genform_div_' . $tab_name . '">');



        if ($this->editMode && !$this->onlyData)
            $this->addBuffer('<div class="row-fluid"><div class="span3 label">'); //<label class="genform_txtres"><span >
        else if (!$this->onlyData && empty($fieldError[$name]))
            $this->addBuffer('<label class="genform_txt label">');
        else if (!$this->onlyData && !empty($fieldError[$name]))
            $this->addBuffer('<label class="genform_txt_error label label-important">');

        $this->printLabel($tab_name, $fk_table, $traduction, $attributs, $preValues);


        if ($this->editMode && (!$this->onlyData )) {
            $this->addBuffer('</div><div class="span9">'); //</span><div class="genform_champres">
        } else if (!$this->onlyData) {
            $this->addBuffer('</label>');
        }
        //	


        if (!$this->onlyData) {

            $this->addBuffer('<div class="genform_champ">');
        }


        $this->genFields($tab_name, $fk_table, $traduction, $attributs, $preValues);


        if (!$this->onlyData) {


            $this->addBuffer('</div>');
            if ($this->editMode) {
                if (trim(strip_tags($lastBuffer)) == trim(strip_tags($this->getBuffer()))) {
                    $this->addBuffer('<span class="resume_empty">' . t('empty_field') . '</span>');
                }
                $this->addBuffer('</div></div>');
            } else {

                //$this->addBuffer('<br />');
            }
            $this->addBuffer('</div>');


            p($this->getBuffer());
        } else {


            return $this->getBuffer();
        }
    }

    /**
     * GENFIELDS
     * 
     *
     * @param unknown_type $tab_name
     * @param unknown_type $fk_table
     * @param unknown_type $traduction
     * @param unknown_type $attributs
     * @param unknown_type $preValues
     * @return unknown
     */
    function genFields($tab_name, $fk_table = "", $traduction = "", $attributs = "", $preValues = array()) {


        global $rteFields, $uploadRep, $neededFields, $neededSymbol, $fieldError, $uploadFields, $mailFields, $restrictedMode, $tabForms, $relations, $arbos, $tablerel, $relinv, $previewField, $orderFields, $specialUpload, $editMode, $functionField, $_Gconfig;
        global $gb_obj;

        $found = false;

        /**
         * Si c'est un champ de langue mis en version de base
         */
        if (!isset($this->tab_field[$tab_name]) && isset($this->tab_field[$tab_name . '_' . ADMIN_LG_DEF])) {

            /**
             * L'a t'on dans la langue courante ?
             */
            if ($this->tab_default_field[$tab_name . '_' . LG]) {
                $tab_name = $tab_name . '_' . LG;
                $found = true;
                /**
                 * Dans la langue par défaut ?
                 */
            } else if ($this->tab_default_field[$tab_name . '_' . ADMIN_LG_DEF]) {
                $tab_name = $tab_name . '_' . ADMIN_LG_DEF;
                $found = true;

                /**
                 * Dans une quelconque autre langue ?
                 */
            } else {


                $lgs = $this->lgs;

                foreach ($lgs as $k => $v) {
                    if ($this->tab_default_field[$tab_name . '_' . $v]) {
                        $tab_name = $tab_name . '_' . $v;
                        $found = true;
                    }
                }
            }
            if (!$found) {
                $tab_name = $tab_name . '_' . ADMIN_LG_DEF;
            }
        }

        $jsColor = '';





        $action = !$editMode ? 'edit' : 'view';

        $name = $tab_name;

        /*
          if(!$this->gs->can($action,$this->table_name,'',$this->id,$tab_name)) {

          return false;

          } else {
         */
        $this->fieldsDone++;
        //}
        //  debug($name);

        /* Function Specific for this field */
        if (array_key_exists($name, $functionField)) {
            if (array_key_exists('before', $functionField[$name])) {
                $this->tab_default_field[$name] = call_user_func($functionField[$name]['before'], $this->tab_default_field[$name]);
            }
        }

        //debug($name.' : '.isLgField($name).' : '.getBaseLgField($name).' : '.in_array(getBaseLgField($name), $uploadFields  ));
        //( isLgField($name) && in_array(getBaseLgField($name), $uploadFields  ))

        $lastBuffer = $this->getBuffer();

        if (isset($_Gconfig['fullArbo'][$this->table_name]) && ake($_Gconfig['fullArbo'][$this->table_name], $name)) {


            include_once($gb_obj->getIncludePath('genform.fullarbo.php', 'admin/genform_modules'));

            $vals = $_Gconfig['fullArbo'][$this->table_name][$name];
            $a = new fullArbo($this->table_name, $this->id, $vals, $name);
            if ($this->editMode) {
                $this->addBuffer($a->getValue());
            } else {
                $this->addBuffer($a->getForm($vals));
            }
        } else

        if (isset($_Gconfig['ajaxRelinv'][$this->table_name]) && ake($_Gconfig['ajaxRelinv'][$this->table_name], $name)) {


            include_once($gb_obj->getIncludePath('genform.ajaxrelinv.php', 'admin/genform_modules'));

            $vals = $_Gconfig['ajaxRelinv'][$this->table_name][$name];
            $a = new ajaxRelinv($this->table_name, $this->id, $vals[0], $vals[1], $name);
            if ($this->editMode) {
                $this->addBuffer($a->getValue());
            } else {
                $this->addBuffer($a->getForm(akev($vals, 2)));
            }
        } else

        if (isset($tablerel[$tab_name])) {
            /**
             * CHAMPS MULTIPLES
             */
            include_once($gb_obj->getIncludePath('genform.tablerel.php', 'admin/genform_modules'));

            $f = new genform_tablerel($this->table_name, $this->id, $tab_name, $this);

            $f->attributs = $attributs;

            $this->addBuffer($f->gen());
        } else if (@array_key_exists($name, $relinv[$this->table_name])) {
            /**
             * CLEF ETRANGERE
             */
            include($gb_obj->getIncludePath('genform.relinv.php', 'admin/genform_modules'));
        } else if (@array_key_exists($name, $relations[$this->table_name]) || @array_key_exists(getBaseLgField($name), $relations[$this->table_name])) {

            /**
             * 	Relation simple
             * * */
            include($gb_obj->getIncludePath('genform.relation.php', 'admin/genform_modules'));
        } else if (!empty($_Gconfig['mapsFields'][$this->table][$name])) {
            /**
             * Champ latitude/longitude avec carto
             */
            include($gb_obj->getIncludePath('genform.maps.php', 'admin/genform_modules'));
        } else if (in_array($name, $uploadFields) || in_array(getBaseLgField($name), $uploadFields)) {
            /**
             * UPLOAD DE FICHIERS
             */
            include($gb_obj->getIncludePath('genform.upload.php', 'admin/genform_modules'));
        } else if (in_array($this->tab_field[$name]->type, array('int', 'smallint', 'tinyint', 'bigint', 'float'))) {

            /**
             * INTEGER
             */
            include($gb_obj->getIncludePath('genform.integer.php', 'admin/genform_modules'));
        } else if ($this->tab_field[$name]->type == 'year') {
            /**
             * YEAR
             */
            include($gb_obj->getIncludePath('genform.year.php', 'admin/genform_modules'));
        } else if (in_array($this->tab_field[$name]->type, array('string', 'varchar', 'char'))) {
            /**
             * VARCHAR
             */
            include($gb_obj->getIncludePath('genform.varchar.php', 'admin/genform_modules'));
        } else if (in_array($this->tab_field[$name]->type, array('text', 'blob', 'longtext', 'tinytext', 'mediumtext'))) {

            /**
             * TEXTAREA
             */
            include($gb_obj->getIncludePath('genform.text.php', 'admin/genform_modules'));
        } else if ($this->tab_field[$name]->type == 'date') {

            /**
             * DATE
             */
            include($gb_obj->getIncludePath('genform.date.php', 'admin/genform_modules'));
        } else if ($this->tab_field[$name]->type == 'datetime') {

            /**
             * DATE
             */
            include($gb_obj->getIncludePath('genform.datetime.php', 'admin/genform_modules'));
        } else if ($this->tab_field[$name]->type == 'time') {

            /**
             * DECIMAL
             */
            include($gb_obj->getIncludePath('genform.time.php', 'admin/genform_modules'));
        } else if ($this->tab_field[$name]->type == 'enum') {

            /**
             * DECIMAL
             */
            include($gb_obj->getIncludePath('genform.enum.php', 'admin/genform_modules'));
        } else if (substr($this->tab_field[$name]->type, 0, 4) == 'set(') {

            /**
             * DECIMAL
             */
            include($gb_obj->getIncludePath('genform.set.php', 'admin/genform_modules'));
        } else {
            debug("Error - $name item inexistant - " . $this->tab_field[$name]->type . "");
            unset($_SESSION['cache']);
        }



        if (!$this->editMode) {
            $_SESSION[gfuid()]['curFields'][] = $name;
        }
        if (trim($this->getBuffer()) == trim($lastBuffer))
            $this->addBuffer('<span class="light">' . t('empty_field') . '</span>');
    }

    // cree l'entete du formulaire
    function genHeader() {
        global $page, $fieldError;
        p('<link rel="StyleSheet" href="genform/css/genform.css" type="text/css" />');

        $h = '';
        if (!$this->editMode) {
            p('');
            /*
              p( '<script language="JavaScript1.2" src="genform/js/calendar.js"></script>' );
              p( '<script language="JavaScript1.2" src="genform/js/initcal.js"></script>' );
             */
            if (isset($_SESSION['editTrads']) && !isset($GLOBALS['divImgPictoPrinted'])) {
                $imgs = getAllPictos('16x16');
                foreach ($imgs as $v) {
                    $h .= '<img rel="' . $v . '" src="' . str_replace('16x16', '32x32', $v) . '"/> ';
                }

                echo '<div id="divImgPicto" style="display:none;border:1px solid;background:#eee;padding:5px;width:600px;height:250px;overflow:auto;position:absolute;z-index:10000;" >' . $h . '</div>
           	
           	<script type="text/javascript">
           	$("#divImgPicto img").click(function() {
           		window.fieldToUpdate.value = $(this).attr("rel");
           		$("#divImgPicto").slideUp();
           		
           		$(window.fieldToUpdate).prev("img").attr("src",$(this).attr("rel"));
           		XHR_editTrad(window.fieldToUpdate);
           		
           	});
           	</script>
          ';

                $GLOBALS['divImgPictoPrinted'] = true;
            }


            if (is_array($fieldError) && empty($_POST['genform_stay']) && (!empty($GLOBALS['fieldErrorTable']) && $GLOBALS['fieldErrorTable'] == $this->table)) {

                reset($fieldError);
                p("<div class='genform_error'><h3>" . t('mal_remplit') . "</h3>");
                while (list( $k, $v ) = each($fieldError)) {
                    p("<span > - " . t($k) . "</span><br/>");
                }
                p('</div>');
                reset($fieldError);
            }

            $this->genHeaderForm();


            p('<div id="genform_navi">');
            $this->genButtons();
            p('</div>');
        } else {
            
        }
        print ("<div id='zegenform' ><br/>");
        // p('<div id="genform_allForm">');
    }

    function genHeaderForm() {
        p("<form method='" . $this->form_method . "' name='genform_formulaire' class='span8' action='' enctype='multipart/form-data' onSubmit='return doSubmitForm();' id='genform_formulaire' >");

        p('
		<script type="text/javascript">
			
			function saveAndReloadForm() {
                            var d = new Date();
				gid("genform_stay").value = d.getTime();
				gid("genform_ok").click();
			}
			
		</script>
		');
        $this->genHiddenItem('genform_stay', '');
        $this->genHiddenItem('genform_fromForm', '1');

        $this->genHiddenItem('gfuid', gfuid());
        if (isset($_REQUEST['gfa'])) {
            $this->genHiddenItem('gfa', '1');
        }
        $this->genHiddenItem('curTable', $_REQUEST['curTable']);
        $this->genHiddenItem('curPage', akev($_REQUEST, 'curPage'), " id='genform_curPage' ");
        $this->genHiddenItem('curId', $_REQUEST['curId']);
        $this->genHiddenItem('insertOtherField', akev($_REQUEST, 'insertOtherField'));
        $this->genHiddenItem('curTableKey', $this->primary_key);
        $this->genHiddenItem('maxfilesize', '800000000000');
        $this->genHiddenItem('genform_currentTime', time());
    }

    function str_makerand($minlength, $maxlength, $useupper, $usespecial, $usenumbers) {
        /*         * ****************************************
          G��ation de mot de passe al�toire
         * *************************************** */
        $charset = 'abcdefghijklmnopqrstuvwxyz';
        if ($useupper)
            $charset .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ($usenumbers)
            $charset .= '0123456789';
        if ($usespecial)
            $charset .= '~@#$%^*()_+-={}|]['; // Note: using all special characters this reads: "~!@#$%^&*()_+`-={}|\\]?[\":;'><,./";
        if ($minlength > $maxlength)
            $length = mt_rand($maxlength, $minlength);
        else
            $length = mt_rand($minlength, $maxlength);
        for ($i = 0; $i < $length; $i++)
            $key .= $charset[( mt_rand(0, ( strlen($charset) - 1)) )];
        return $key;
    }

    // cree la fin du formulaire
    function genFooter($submit_value = "") {
        /*         * ****************************
         *   FIN DU FORMULAIRE
         *   Diff�entes variables JS sont imprim�s
         * ***************************** */
        print ('</div>');
        // print('</td></tr></table>');


        global $tabForms;
        // p('</div>');
        if (!$this->editMode) {
            // fixPositions() puts everything back in the right place after a resize.
            // Et il permet de g�er pas mal de trucs ...
            p('<script type="text/javascript">');
            /*
              if ( count( $this->dateFields ) ) {
              p( 'function fixPositions() ' );
              p( '{' );
              // add a fixPosition call here for every element
              // you think might get stranded in a resize/reflow.
              while ( list( , $v ) = each( $this->dateFields ) )
              p( "fixPosition('" . $v . "');" );

              p( '}' );
              }
             */
            p('multiField = new Array();');

            if (count($this->multiFields)) {
                $i = 0;

                while (list(, $v ) = each($this->multiFields)) {
                    p('multiField[' . $i . '] = gid("genform_formulaire").' . $v . ';');
                    $i++;
                }
            }
            $_REQUEST["curPage"] = $_REQUEST["curPage"] ? $_REQUEST["curPage"] : 0;

            //p('genform_activatePage(' . $_REQUEST["curPage"] . ');');


            p('
					
                function doSaveAllAndStay(func) {
                
                        $("#genform_stay").val("ajaxsave");
                        $.post("index.php", $("#genform_formulaire").serialize(),function(data) {
                           // alert(data);
                            $("#curId").val(data);
                            if(func) {
                                func();
                            }
                        });
                        $("#genform_stay").val("");

                        return;
                        
                       $("#genform_formulaire")[0].onsubmit = false;
                        $("#genform_stay").val("autosave");
                        if(func) {
                            $("#autosave_frame").load(func);
                        }
                        $("#genform_formulaire").attr("target","autosave_frame").submit().attr("target","");
                        $("#genform_stay").val("");                                
                        
                        return false;
                        
                }
            ');

            p('</script>');

            //$this->genButtons();

            $this->genFooterForm();
            // debug($_REQUEST);
        }
        // p($this->divsToAdd);
    }

    function genFooterForm() {
        global $formFooters, $champsRTE;

        $formFooters = str_replace('@@CHAMPS@@', substr($champsRTE, 0, -2), $formFooters);
        p($formFooters);
        p("</form>");
    }

    function tradExists($str) {

        return false;
    }

    function tradOnglet($t, $i) {

        if (!$this->tradExists($t . '_p_' . $i)) {

            return str_replace("Page ", ' ', $this->trad($t . '_p_' . $i));
        } else {
            return $this->trad($t);
        }
    }

    function genButtons() {
        global $tabForms, $_Gconfig;


        if (!empty($tabForms[$this->table]['pages'])) {

            $i = 0;
            $alt = '';
            p('<ul class="nav nav-tabs">');
            foreach ($tabForms[$this->table]['pages'] as $k => $page) {

                if ($i == $_REQUEST['curPage']) {
                    $cl = 'active';
                } else {
                    $cl = '';
                }

                p('<li class="' . $cl . '" id="genform_btn_page_' . $i . '" >');

                $imgu = '';
                if (tradExists('imgonglet_' . $_REQUEST['curTable'] . '_p_' . $k)) {
                    $imgu = t('imgonglet_' . $_REQUEST['curTable'] . '_p_' . $k);
                }
                if (!$imgu) {
                    $imgu = ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_ARBO_SIZE . '/actions/media-playback-stop.png';
                }

                $oc = '';
                $bef = '';

                if (isset($_SESSION['editTrads'])) {
                    $oc = 'onclick=""';
                    $bef = '<input type="text" name="ET_imgonglet_' . $_REQUEST['curTable'] . '_p_' . $k . '" style="width:10px;" onclick="window.fieldToUpdate=this;$(\'#divImgPicto\').css(\'top\',mouseY+\'px\').css(\'left\',mouseX+\'px\').slideToggle()" onchange=""  value="' . $imgu . '" />';
                }

                $img = ('<img ' . $oc . ' style="vertical-align:middle" src="' . $imgu . '" alt="' . $alt . '" />&nbsp;' . $bef);

                //$this->genButton ( "prevPage",  t($_REQUEST['curTable']."_p_".$i) ,$prev." onclick='genform_activatePage(".$i.")'");
                p('<a href="#genform_page_' . ($i + 1) . '" id="aongl' . $i . '"  data-toggle="tab">');
                p($img);
                p($this->tradOnglet($_REQUEST['curTable'], $k) . "</a>");
                p(getEditTrad($_REQUEST['curTable'] . '_p_' . $k));

                p('</a>
			</li>');

                //p('</td>');
                $i++;
            }
            p('
		<script type="text/javascript">
			genform_totalPages = ' . count($tabForms[$_REQUEST['curTable']]['pages']) . ';
                        window.filesUploading = 0;
                        function beforeUnloadUploading() {
                            return ' . alt(t('fichiers_en_cours_dupload')) . ';
                        }
		</script>
		');
        }

        //p( '<td align="right">');

        if (count($_Gconfig['LANGUAGES']) > 1) {

            p('<div id="genform_header_btn_lgs" class="genform_header_btn" style="margin-top:100px;padding:2px;" >');
            p('<label>');
            if (empty($_SESSION['onlyLg'])) {
                $_SESSION['onlyLg'] = false;
            }
            if (($_SESSION['onlyLg'] && $_SESSION['onlyLg'] != 'ALL')) {
                p('<img src="img/flags/' . $_SESSION['onlyLg'] . '.gif" alt="' . $_SESSION['onlyLg'] . '" />');
            }
            p(t('langue') . '</label>');

            p('<select name="onlyLg" onchange="saveAndReloadForm()">');

            global $_Gconfig;
            p('<option ' . ($_SESSION['onlyLg'] == 'ALL' ? 'selected="selected"' : '') . ' value="ALL">' . t('all') . '</option>');
            foreach ($_Gconfig['LANGUAGES'] as $v) {
                p('<option  ' . ($_SESSION['onlyLg'] == $v ? 'selected="selected"' : '') . '  value="' . $v . '">' . strtoupper($v) . '</option>');
            }

            p('</select>');

            p('</div>');
        }

        //p('<div class="clearer">&nbsp;</div>');
        //p('</td>' );
        //p( '</tr><tr><td colspan="'.($i+1).'">' );
    }

    function genSubmit($submit_name, $submit_value, $params = "") {
        /* Genere un bouton SUBMIT avec les parametres */
        if (!strstr($params, 'type=')) {
            $params .= " type='submit' ";
        }

        print"<input " . $params . "  name='" . $submit_name . "' id='" . $submit_name . "' value='" . $submit_value . "' />\n";
    }

    function genButton($submit_name, $submit_value, $params = "") {
        /* Genere un bouton classique */
        if (!strstr($params, 'type=')) {
            $params .= " type='button' ";
        }
        print"<input " . $params . " name='" . $submit_name . "' value='" . $submit_value . "' />\n";
    }

    // cree les champs cache du formulaire
    function genHiddenItem($name, $hidden_value, $params = "") {
        /* Genere un champ caché */
        if (!strstr($params, 'id='))
            $params .= ' id="' . $name . '" ';
        print"<input type='hidden' name='" . $name . "' value='" . $hidden_value . "'  " . $params . " />\n";
    }

    function genHiddenField($klef) {
        /* Genere un champ cach�a la mode genform */
        //debug($this);
        $_SESSION[gfuid()]['curFields'][] = $klef;

        p("<input type='hidden' name='genform_" . $klef . "' value='" . akev($this->tab_default_field, $klef) . "' />");
    }

    function genInsertButtons($champ) {

        if (!$this->genInserter)
            return '';

        return ('<div class="genform_inserter"  id="genform_inserter_' . $champ . '" >
		<a onclick="insertLorem(gid(\'' . $champ . '\'));gid(\'genform_inserter_' . $champ . '\').style.display = \'none\'">' . t('insert_fake_text') . '</a>
		<a onclick="insertDate(gid(\'' . $champ . '\'));gid(\'genform_inserter_' . $champ . '\').style.display = \'none\'">' . t('insert_date') . '</a>
		<a onclick="insertUnixTime(gid(\'' . $champ . '\'));gid(\'genform_inserter_' . $champ . '\').style.display = \'none\'">' . t('insert_time') . '</a>
		<a onclick="insertPassword(gid(\'' . $champ . '\'));gid(\'genform_inserter_' . $champ . '\').style.display = \'none\'">' . t('insert_password') . '</a>
		</div><a onclick="sdisp = gid(\'genform_inserter_' . $champ . '\');if(sdisp.style.display==\'block\')sdisp.style.display=\'none\';else sdisp.style.display=\'block\'">+</a>');
    }

    function genActions() {




        global $gs_obj, $_Gconfig;


        /**
         * On récupère les actions attribuées à cette table
         */
        $actions = $this->gs->getActions($this->table_name, $this->id);

        $this->genHeaderForm();

        if ($GLOBALS['inScreen'] == 'form') {

            p('<div id="gen_actions">');


            //onclick="validInsideSubmit(this)"
            p('<button class="btn btn-warning" name="genform_cancel" >');
            p('<img src="' . t('src_cancel') . '"  alt="" />');
            p(t('cancel'));
            p('</button>');

            if ($this->gs->can('edit', $this->table, $this->tab_default_field)) {
                p(' <button class="btn btn-primary" name="genform_ok"  >');
                p('<img src="' . ADMIN_PICTOS_FOLDER . '' . ADMIN_PICTOS_FORM_SIZE . '/actions/document-save.png"  alt="" />');
                p(t('save') . '</button>');
            }


            p('</div>');
            return;
        }


        p('<div id="gen_actions" >');


        /**
         * GenUrl pour accéder aux URLs Des rubriques
         */
        global $gurl;

        if (!is_object($gurl)) {

            $gurl = new $_Gconfig['URL_MANAGER']();

            $gurl->getRubId();
        }

        /**
         * Gestion des LOCKS
         * Personne d'autre ne modifie cet élément ?
         */
        $gl = new GenLocks();
        $lt = $gl->getLock($this->table, $this->id);
        if (is_array($lt)) {
            p('<p>' . t('erreur_lock_existe') . '</p>');
        }


        /**
         * On parcourt toutes les actions définies pour cette table
         */
        foreach ($actions as $action) {
            if ($action != 'view') {
                if ($GLOBALS['gs_obj']->can($action, $this->table_name, $this->id)) {
                    $ga = new GenAction($action, $this->table_name, $this->id, $this->tab_default_field);

                    if ($ga->checkCondition()) {

                        if (method_exists($ga->obj, 'getForm')) {
                            $ga->obj->getForm();
                        } else {

                            if (tradExists(('src_' . $action))) {

                                $srcBtn = t(('src_' . $action));
                            } else {
                                $srcBtn = ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_FORM_SIZE . '/emblems/emblem-system.png';
                            }

                            p('<a class="btn '.($action == 'edit' ? 'btn-primary':'').'" href="?genform_action%5B' . $action . '%5D=1&amp;curTable=' . $this->table . '&amp;curId=' . $this->id . '"><img src="' . $srcBtn . '" /> ' . t($action) . ' </a>');
                        }
                    }
                }
            }
        }


        /**
         * Pour la table s_rubrique
         * Affichage des liens de visualisation en ligne
         */
        if ($this->table == 's_rubrique') {

            if (isRubriqueOnline($this->tab_default_field)) {

                //p('<p>' . t('version_masquee') . '</p><br/>');

                $urlView = path_concat($gurl->BuildUrlFromId($this->tab_default_field['fk_rubrique_version_id']));
                $action = 'voir_version_en_ligne';
                p('<a target="_blank" class="btn" href="' . $urlView . '" >');


                p('<img class="inputimage" src="' . t('src_' . $action) . '" alt="" />');
                p(t($action));
                p('</a>');
            } else {
                p('<p>' . t('version_masquee') . '</p><br/>');
            }


            $urlView = path_concat($gurl->BuildUrlFromId($this->tab_default_field['fk_rubrique_version_id'], '', array(), 'editer'));
            $action = 'voir_version_modifiable';
            p('<a class="btn" href="' . $urlView . '" target="_blank">');


            p('<img src="' . t('src_' . $action) . '" alt="" />');
            p(t($action));
            p('</a>');


            p('<p>&nbsp;</p>');
        }
        /**/


        p('');





        p('</div>');

        //if($this->editMode)
        $this->genFooterForm();
    }

    function startFieldset($nom, $open = true) {

        if ($this->editMode) {
            return;
        }
        echo '<fieldset  id="fieldset_' . $nom . '" class="' . ($open ? 'fieldopen' : 'fieldclosed') . '" ><legend>
				<a href="#" title=' . alt(t('deplier') . ' ' . t('fieldset_' . $nom)) . ' onclick="return toggleFieldset(this);" class="btn" >
				<span></span>' . t('fieldset_' . $nom) . getEditTrad('fieldset_' . $nom) . '</a></legend>';

        $this->fieldsetOpen = $open;
        $this->fieldsetId = $nom;

        echo '<div id="fieldsetd_' . $nom . '" >';
    }

    function endFieldset() {
        if ($this->editMode) {
            return;
        }
        echo '</div>';

        if (!$this->fieldsetOpen) {
            echo '<script>$("#fieldsetd_' . $this->fieldsetId . '").hide();</script>';
        }

        echo '</fieldset><br/>';
    }

}

function isNeeded($table, $champ) {
    global $neededFields;
    if (!is_array($neededFields)) {
        return false;
    }
    if (in_array($table . '.' . $champ, $neededFields)) {
        return true;
    } else if (in_array($champ, $neededFields)) {
        return true;
    }
    return false;
}
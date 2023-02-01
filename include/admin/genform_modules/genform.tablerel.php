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
if (!is_object($this->gs))
    die();

/**
 * Genform Module
 *
 * Gère les tables de relations N<=>N
 *
 */
class genform_tablerel extends genform_base
{

    public $fk_champ = '';
    public $valuesSelect = '';

    public $tab_name, $editMode, $fk2, $fk_table, $pk1, $ordered, $pk2, $optionsSelected, $nomSql, $sqlLeftInit, $sqlLeft, $attributs;

    function init()
    {

        global $orderFields, $tablerel, $tabForms, $_Gconfig;

        /**
         *  On recupere les informations du tableau tablerel (qui est mal foutu )
         * On récupere $this->fk_table et les clefs externes
         * */
        reset($tablerel[$this->champ]);
        $found = false;

        foreach ($tablerel[$this->champ] as $k => $v) {

            if ($v == $this->table && !$found) {
                $found = true;
                $this->pk1 = $k;
            } else {
                $this->fk2 = $k;
                $this->fk_table = $v;
            }
        }


        /**
         * On récupére les champs sur lesquels trier la requete
         * DANS LA TABLE DE RELATION
         * SI ON A UN ORDERFIELDS DE DEFINIT
         */
        $this->ordered = 0;
        if (array_key_exists($this->champ, $orderFields)) {
            $this->ordered = 1;
            $this->orderField = $orderFields[$this->champ][0];
        }


        /**
         *  Clef primaire de la seconde table
         * */
        reset($tablerel[$this->champ]);
        $this->pk2 = getPrimaryKey($this->fk_table);


        /**
         * Variables
         */
        $this->optionsSelected = "";
        $i = 0;


        /**
         * Sur quel champ on trie le resultat de la table distante ?
         */
        $this->nomSql = getTitleFromTable($this->fk_table, ' , ');


        /**
         * Preparation de la requete
         */
        if (in_array($this->fk_table, $_Gconfig['versionedTable']) || in_array($this->fk_table, $_Gconfig['multiVersionTable'])) {
            $sqlversioned = sqlOnlyReal($this->fk_table);
        } else {
            $sqlversioned = '';
        }

        if (!empty($_Gconfig['relationToRelOne'][$this->table . '.' . $this->champ])) {
            $sqlversioned .= '';
        }


        if (!empty($_Gconfig['specialListingWhere'][$this->champ])) {

            $this->sqlLeft = $this->sqlLeftInit = 'SELECT T2.*
							  FROM ' . $this->fk_table . ' AS T2  
							  WHERE 1 ' . ($_Gconfig['specialListingWhere'][$this->champ]($this->gf)) . '
							  ' . $sqlversioned . '
							  ORDER BY ' . $this->nomSql;
        } else {

            $this->sqlLeft = $this->sqlLeftInit = 'SELECT T2.*
							  FROM ' . $this->fk_table . ' AS T2  ' . '  
							  WHERE 1 ' . $sqlversioned . '
							  ORDER BY ' . $this->nomSql;
        }
    }


    public function genSimpleArbo()
    {

        $this->sel = $this->getSelectedItems();
        $this->sel = explode(',', @str_replace(" ", "", $this->sel));

        global $_Gconfig;

        list($otherTable, $otherTableFk) = $_Gconfig['tablerelAsSimpleArbo'][$this->table][$this->champ];

        $this->addBuffer('<ul class="tablerelfullarbo" id="' . $this->champ . '">');
        $sqlAdd = '';
        if ($otherTable == 's_rubrique') {
            $sqlAdd = ' AND ocms_version = rubrique_id ';
        }
        $this->getSubsWhere(array($otherTable, '', $otherTableFk), ' (' . $otherTableFk . ' = 0 OR ' . $otherTableFk . ' IS NULL ) ', $sqlAdd);


        $this->addBuffer('</ul>');
        $this->addBuffer('<input type="hidden" name="genform_rel__' . $this->champ . '__' . $this->pk2 . '_temoin" value="1" />');
        $this->addBuffer('<script>$(document).ready(function(){
            $("#' . $this->champ . ' input").attr("name","genform_rel__' . $this->champ . '__' . $this->pk2 . '[]");
         //   $("#' . $this->champ . '").collapsibleCheckboxTree({checkParents : true,uncheckChildren: true});
            });</script>');
    }

    function genFullarbo()
    {

        $this->sel = $this->getSelectedItems();
        $this->sel = explode(',', str_replace(" ", "", $this->sel));

        global $_Gconfig;

        list($parentTable, $parentField) = $_Gconfig['tablerelAsFullarbo'][$this->champ];


        $nomSql = getTitleFromTable($parentTable, ' , ');
        if (!empty($_Gconfig['specialListingWhereFullArbo'][$this->champ])) {
            $sql = 'SELECT * FROM ' . $parentTable . ' WHERE 1 ' . ($_Gconfig['specialListingWhereFullArbo'][$this->champ]($this->gf)) . ' ORDER BY ' . $nomSql;
        } else {
            $sql = 'SELECT * FROM ' . $parentTable . ' WHERE 1  ORDER BY ' . $nomSql;
        }

        $res = DoSql($sql);


        $this->addBuffer('<ul class="tablerelfullarbo" id="' . $this->champ . '">');

        foreach ($res as $row) {
            $this->addBuffer('<li> ' . GetTitleFromRow($parentTable, $row) . ''); //id="fullarbo_'.$this->champ.'_'.$row[getPrimaryKey($parentTable)].'"
            $this->getSubsWhere($_Gconfig['fullArbo'][$parentTable][$parentField], $_Gconfig['fullArbo'][$parentTable][$parentField][1] . ' = ' . $row[getPrimaryKey($parentTable)] . '  ');
            $this->addBuffer('</li>');
            $this->addBuffer("\n");
        }

        $this->addBuffer('</ul>');
        $this->addBuffer('<input type="hidden" name="genform_rel__' . $this->champ . '__' . $this->pk2 . '_temoin" value="1" />');
        $this->addBuffer('<script>$(document).ready(function(){
            $("#' . $this->champ . ' input").attr("name","genform_rel__' . $this->champ . '__' . $this->pk2 . '[]");
            $("#' . $this->champ . '").collapsibleCheckboxTree({checkParents : false,uncheckChildren : false, checkChildren:false});
            });</script>');
    }

    function getSubsWhere($config, $where, $whereAll = '')
    {

        $sql = 'SELECT * FROM ' . $config[0] . ' WHERE ' . $where . $whereAll;
        $res = DoSql($sql);
        $pk = $this->pk2;

        if (!$res) {
            global $co;
            debug($config);
            debug($where);
            debug($sql);
            debug($co->errormsg());
            return;
        }
        if ($res->RecordCount() > 0) {
            $this->addBuffer('<ul>');
            foreach ($res as $row) {
                $s = '';
                if (in_array($row[getPrimaryKey($config[0])], $this->sel)) {
                    $s = ' checked ';
                }
                $this->addBuffer('<li><label><input type="checkbox" ' . $s . ' value="' . $row[getPrimaryKey($config[0])] . '" /> ' . GetTitleFromRow($config[0], $row) . '</label>');
                $this->getSubsWhere($config, $config[2] . ' = ' . $row[$pk] . '', $whereAll);
                $this->addBuffer('</li>');
                $this->addBuffer("\n");
            }
            $this->addBuffer('</ul>');
        }
    }

    function genCheckBoxField()
    {

        $r = DoSql($this->sqlLeftInit);

        $sel = $this->getSelectedItems();
        $sel = explode(',', str_replace(" ", "", $sel));


        $pk = getPrimaryKey($this->fk_table);
        foreach ($r as $v) {
            $h = '';
            $id = $this->champ . '_' . $v[$pk];
            $s = in_array($v[$pk], $sel) ? 'checked="checked"' : '';

            $h .= '<label class="tablerelcheckbox" for="' . $id . '" >
                    <input ' . $s . ' type="checkbox" name="genform_rel__' . $this->champ . '__' . $this->pk2 . '[]"
                        id="' . $id . '" value="' . $v[$pk] . '" /> <span>' . GetTitleFromRow($this->fk_table, $v) . '</span></label>';
            $this->addBuffer($h);
        }

        $this->addBuffer('<div class="clearer"></div>');
        $this->addBuffer('<input type="hidden" name="genform_rel__' . $this->champ . '__' . $this->pk2 . '_temoin" value="1" />');
    }

    function genAjaxField()
    {

        $h = '';

        $sel = $this->getSelectedItems();
        global $_Gconfig;
        $fields = $_Gconfig['tablerelAsTags'][$this->champ]['label'];

        $sel = choose($sel, "''");
        $vals = 'SELECT * FROM ' . $this->fk_table . ' WHERE ' . $this->pk2 . ' IN (' . $sel . ') ';
        $res = DoSql($vals);


        $assi = '';
        foreach ($res as $row) {
            debug($row);
            $t = array();
            foreach ($fields as $v) {
                $t[] = $row[$v];
            }
            $assi .= '<input class="tag_' . $this->champ . '" type="text" name="genform_tagrel__' . $this->champ . '__' . $this->pk2 . '[' . $row[$this->pk2] . ']" value=' . alt(implode($t, ' - ')) . ' />';
        }
        if (!$res->RowCount()) {
            $assi = $assi .= '<input class="tag_' . $this->champ . '" type="text" name="genform_tagrel__' . $this->champ . '__' . $this->pk2 . '[]" value="" />';
        }

        $h .= '<p><input type="hidden" name="genform_tagrel__' . $this->champ . '__' . $this->pk2 . '_temoin" value="1" />' . $assi . '</p>';

        $allowAdd = $_Gconfig['tablerelAsTags'][$this->champ]['allowAdd'] ? "true" : "false";

        $h .= '<script type="text/javascript">

           $("input.tag_' . $this->champ . '" ).tagedit({
                autocompleteURL: "?xhr=tablerelAsTags&table=' . $this->fk_table . '&tablerel=' . $this->champ . '",
                allowEdit: false,
                addedPostfix : "",
                allowAdd:' . $allowAdd . '
	   });
            </script>';

        $this->addBuffer($h);
    }

    function genForm()
    {

        global $previewField, $tabForms, $_Gconfig;

        if (isset($_Gconfig['tablerelAsTags'][$this->champ])) {
            return $this->genAjaxField();
        } else
            if (isset($_Gconfig['tablerelAsCheckbox'][$this->champ])) {
                return $this->genCheckBoxField();
            } else
                if (isset($_Gconfig['tablerelAsFullarbo'][$this->champ])) {
                    return $this->genFullarbo();
                }
        if (isset($_Gconfig['tablerelAsSimpleArbo'][$this->table][$this->champ])) {
            return $this->genSimpleArbo();
        }


        $chps = '';
        /**
         * Image d'aide
         */
        $this->gf->genHelpImage('help_tablerel');


        $this->addBuffer('<table ><tr>');
        $this->addBuffer('<td width="10">&nbsp;');


        $this->addBuffer('</td>');
        $this->addBuffer('<td>');

        /**
         * Rechercher dans la liste
         */
        if (true) {
//            $this->addBuffer('<div class="control-group"><div class="controls"><div class="input-append">
//				<input class="input-mini"
//						type="text"
//						id="qxhr_' . $this->champ . '"
//						value=""
//						onkeyup="XHR_tablerel(\'' . $this->table . '\',\'' . $this->id . '\',\'' . $this->champ . '\',this);"
//
//						/>
//				<img  class="btn btn-mini"
//						alt="' . t('search') . '"
//						src="' . t('src_go') . '"
//						style="vertical-align:middle"
//						class="inputimage"
//						onclick="XHR_tablerel(\'' . $this->table . '\',\'' . $this->id . '\',\'' . $this->champ . '\',gid(\'qxhr_' . $this->champ . '\'));"
//						 />
//
//				<img  class="btn btn-mini"
//						alt="' . t('cancel') . '"
//						src="' . t('src_undo') . '"
//						style="vertical-align:middle"
//						class="inputimage"
//
//						onclick="gid(\'qxhr_' . $this->champ . '\').value=\'\';XHR_tablerel(\'' . $this->table . '\',\'' . $this->id . '\',\'' . $this->champ . '\',gid(\'qxhr_' . $this->champ . '\'));"
//						 />
//                                        </div></div></div>
//						 ');

            $this->addBuffer('
            <div class="input-prepend ">
                <span class="add-on add-on-mini">
                <i class="icon-search"></i></span><input
                class="input-mini selectMSearch" type="text" id="qxhr_' . $this->champ . '" value="" >
            </div>');
        }


        $this->addBuffer('</td>');


        $this->addBuffer('</tr><tr>');
        $this->addBuffer('<td width="10">&nbsp;</td><td style="text-align:center;background:#ccc;color:#555">');


        $this->addBuffer('' . t('selectable_items') . '');

        $this->addBuffer('</td><td>');
        $this->addBuffer('</td><td style="text-align:center;background:#ccc;color:#555">');
        $this->addBuffer('' . t('selected_items') . '');
        $this->addBuffer('</td>');
        $this->addBuffer('</tr><tr>');

        /**
         * AJOUTER UN ELEMENT
         */
        $this->addBuffer('<td width="10">');
        if ($this->gf->gs->can('add', $this->fk_table) && !$this->gf->restricted) {
            $this->addBuffer('<input type="image"  class="inputimage" src="' . t('src_new') . '" title="' . tradAdmin("ajouter", '', $this->table) . '" name="genform_addrel__' . $this->champ . '__' . $this->pk2 . '__' . $this->fk_table . '" />');
        }

        if ($this->gf->gs->can('edit', $this->fk_table) && !$this->gf->restricted) {

            /**
             * Bouton de MODIFICATION
             */
            $this->addBuffer('
			<input
			type="image"
			src="' . t('src_editer') . '"
			class="inputimage"
			name="genform_btneditrel__' . $this->champ . '__' . $this->pk2 . '__' . $this->fk_table . '"
			title="' . t("modifier") . '"
			onclick="if(gid(\'' . $this->champ . '\').selectedIndex < 0) {
			alert(\'Veuillez choisir un element a modifier\');
			return false;}
			else {
			gid(\'genform_editrel__' . $this->champ . '__' . $this->pk2 . '__' . $this->fk_table . '\').value = gid(\'' . $this->champ . '\').options[gid(\'' . $this->champ . '\').selectedIndex].value;}" />');

            $this->addBuffer('<input type="hidden" name="genform_editrel__' . $this->champ . '__' . $this->pk2 . '__' . $this->fk_table . '" id="genform_editrel__' . $this->champ . '__' . $this->pk2 . '__' . $this->fk_table . '"  value="0" />');

            /**
             * Bouton de PREVISUALISATION
             */
            if (!empty($previewField[$this->table][$this->champ])) {
                $chps = is_array($previewField[$this->table][$this->champ]) ? implode(';', $previewField[$this->table][$this->champ]) : $previewField[$this->table][$this->champ];
                $this->addBuffer('<br/><input title="' . t('preview') . '" class="inputimage" id="genform_preview_' . $this->champ . '_' . $this->fk_champ . '_btn" src="' . t('src_preview') . '" type="image" name="genform_preview" value="' . t('preview') . '" onclick="genformPreviewFk(\'' . $this->fk_table . '\',\'' . $this->champ . '_' . $this->fk_champ . '\',\'' . $chps . '\');return false;" />');
            }

            $this->addBuffer('</td>');
        }

        $this->addBuffer('<td>');


        /**
         * SELECT DE GAUCHE
         */
        $this->addBuffer('<select multiple size="10" name="' . $this->champ . '" id="' . $this->champ . '"  style="width:200px" onchange="genformPreviewFk(\'' . $this->fk_table . '\',\'' . $this->champ . '_' . $this->fk_champ . '\',\'' . $chps . '\');return false;" >');


        /**
         * Selection des elements selectionnables
         */
        $res2 = GetAll($this->sqlLeft);
        global $relations;
        /* debug($relations[$this->fk_table]);
          debug($tabForms[$this->fk_table]['titre']); */
        foreach ($res2 as $row) {
            $thisValue = "";


            /* if($relations[$this->fk_table][$tabForms[$this->fk_table]['titre'][0]]) {

              $gff = new GenForm($this->fk_table,'get',$row[getPrimaryKey($this->fk_table)],$row);
              $gff->editMode = true;
              $gff->onlyData = true;
              if(is_array($tabForms[$this->fk_table]['titre'])) {
              foreach($tabForms[$this->fk_table]['titre'] as $v) {
              $thisValue = $gff->gen($v);

              }
              } else {
              $thisValue = $gff->gen($tabForms[$this->fk_table]['titre']);
              }
              } else {
             */
            $thisValue = getTitleFromRow($this->fk_table, $row, ' ');
            /*
              }
             */
            if (!strlen(trim($thisValue))) {
                $thisValue = '[TITRE VIDE]';
            }


            $this->addBuffer('<option value="' . $row[$this->pk2] . '">' . $thisValue . '</option>');
        }

        $this->addBuffer('</select>');
        $this->addBuffer('</td>');


        /**
         * Boutons GAUCHE DROITE
         */
        $this->addBuffer('<td>');
        $this->addBuffer('<input type="image"  name="b1" src="' . t('src_gauche') . '" ');
        $this->addBuffer(' onClick="moveMultiBox(this.form.genform_rel__' . $this->champ . '__' . $this->pk2 . ',this.form.' . $this->champ . "" . ',' . $this->ordered . ');return false;" class="inputimage" value="<<" />');
        $this->addBuffer('<input type="image" name="b2" src="' . t('src_droite') . '" ');
        $this->addBuffer(' onClick="moveMultiBox(this.form.' . $this->champ . "" . ',this.form.genform_rel__' . $this->champ . '__' . $this->pk2 . ',' . $this->ordered . ');return false;" class="inputimage"   value=">>" />');
        $this->addBuffer('</td>');


        /**
         * SELECT DE DROITE
         */
        $this->addBuffer('<td>');
        $this->addBuffer('<select ' . $this->attributs . ' multiple="multiple" size="10" name="genform_rel__' . $this->champ . '__' . $this->pk2 . '" id="genform_rel__' . $this->champ . '__' . $this->pk2 . '" style="width:200px" onchange="genformPreviewFk(\'' . $this->fk_table . '\',\'genform_rel__' . $this->champ . '__' . $this->pk2 . '\',\'' . $chps . '\',\'' . $this->champ . '_' . $this->fk_champ . '\');return false;">');
        //$this->addBuffer( '<option value=""  disabled> -------- ' . t( 'selected_items' ) . ' -------- </option>' );

        $this->addBuffer($this->optionsSelected);

        $this->addBuffer('</select>');

        $this->addBuffer('</td>');


        /**
         * BOUTONS HAUT BAS
         */
        if ($this->ordered) {
            $this->addBuffer('<td>');
            $this->addBuffer('<input type="image" onclick="return moveInsideMulti(this.form.genform_rel__' . $this->champ . '__' . $this->pk2 . ',-1);return false;" src="' . t('src_up') . '" alt="up" /><br/>');
            $this->addBuffer('<input type="image" onclick="return moveInsideMulti(this.form.genform_rel__' . $this->champ . '__' . $this->pk2 . ',1);return false;" src="' . t('src_down') . '" alt="down" />');
            $this->addBuffer('</td>');
        }


        $this->addBuffer('</tr></table>');


        /**
         * Champ HIDDEN TEMOIN pour savoir si on doit bien modifier ce champ
         * (Pour le cas ou on ne selectionne rien, sinon on ne recoit rien ...)
         */
        $this->addBuffer('<input type="hidden" name="genform_rel__' . $this->champ . '__' . $this->pk2 . '_temoin"  value="1" />');


        /**
         * Liste des champs Multifields
         */
        if (is_object($this->gf)) {
            $this->gf->multiFields[] = "genform_rel__" . $this->champ . "__" . $this->pk2;
        }


        /**
         * IFRAME POUR AFFICHER LA PREVIEW
         */
        if (!empty($previewField[$this->table][$this->champ])) {
            /*
             * Si c'est un preview on rajoute l'IFRAME correspondante
             */

            $this->addBuffer('<iframe
		    width="' . $this->gf->larg . '"
		    height="250"
		    frameborder="0"
		    id="genform_preview_' . $this->champ . '_' . $fk_champ . '"
		    src="about:blank"
		    style="display:none;border:1px solid #aeaeae;overflow-y:auto;overflow-x:hidden;"></iframe>');
        }
    }

    function genValue()
    {

        $this->addBuffer($this->valuesSelect);
    }

    function gen()
    {

        $this->getSelectedItems();
        /**
         * Si on est en modification
         */
        if (!$this->gf->editMode) {
            $this->genForm();
        } else {
            /**
             * Sinon affichage seulement et pas de modification
             */
            $this->genValue();
        }

        return $this->getBuffer();
    }

    function getSelectedItems()
    {

        global $tabForms, $_Gconfig;
        $arraySelected = "";

        /**
         * On sélectionne les elements deja selectionnés
         * (Uniquement si on est pas en mode creation, auquel cas il ne peut y en avoir)
         */
        if ($this->id && $this->id != "new") {

            /* Requetes des elements deja selectionnes */
            $Startsql = 'SELECT T2.* ';

            /* On selectionne les infos */
            $sql = $Startsql . ' FROM ' . $this->champ . ' AS T1,
						' . $this->fk_table . ' AS T2 WHERE 
						T1.' . $this->pk1 . ' = "' . $this->id . '"
						AND T1.' . $this->fk2 . ' = T2.' . $this->pk2;

            if (!empty($_Gconfig['specialListingWhere'][$this->champ])) {
                $sql .= $_Gconfig['specialListingWhere'][$this->champ]($this->gf);
            }

            /**
             * Si on est dans un ORDERFIELDS
             */
            if ($this->ordered) {
                $sql .= ' ORDER BY T1.' . $this->orderField;
            }


            $res = GetAll($sql);

            $i = 0;
            $nbRes = count($res);

            /**
             * On construit la liste des OPTIONS deja selectionnées
             */
            global $relations;
            foreach ($res as $row) {

                $thisValue = "";

                if ($i > 0) {
                    $this->valuesSelect .= $this->gf->separator;
                }

                $i++;
                /*
                  if($relations[$this->fk_table][$tabForms[$this->fk_table]['titre'][0]]) {

                  $gff = new GenForm($this->fk_table,'get',$row[getPrimaryKey($this->fk_table)],$row);
                  $gff->editMode = true;
                  $gff->onlyData = true;
                  if(is_array($tabForms[$this->fk_table]['titre'])) {
                  foreach($tabForms[$this->fk_table]['titre'] as $v) {
                  $thisValue = $gff->gen($v);

                  }
                  } else {
                  $thisValue = $gff->gen($tabForms[$this->fk_table]['titre']);
                  }
                  } else {
                  $thisValue = getNomForValue( $tabForms[$this->fk_table]['titre'], $row );
                  }

                 */

                $thisValue = getTitleFromRow($this->fk_table, $row);


                if (!strlen(trim($thisValue))) {
                    $thisValue = '[TITRE VIDE]';
                }

                $this->valuesSelect .= $thisValue;

                $this->optionsSelected .= ('<option selected="selected" value="' . $row[$this->pk2] . '">' . $thisValue . '</option>');

                /**
                 * Liste des elements deja selectionnés é mettre dans la clause NOT IN
                 */
                $arraySelected .= ' ' . $row[$this->pk2] . ' ';

                if ($i < $nbRes) {
                    $arraySelected .= ' , ';
                }
            }

            if ($arraySelected) {

                $this->sqlLeft = $Startsql . '  FROM ' . $this->fk_table . ' AS T2 WHERE
								' . ' T2.' . $this->pk2 . '
								 NOT IN (' . $arraySelected . ') ';


                if (!empty($_Gconfig['specialListingWhere'][$this->champ])) {
                    $this->sqlLeft .= $_Gconfig['specialListingWhere'][$this->champ]($this->gf);
                }

                if (in_array($this->fk_table, $_Gconfig['versionedTable']))
                    $this->sqlLeft .= ' ' . sqlOnlyRealAndOnline($this->fk_table);

                $this->sqlLeft .= ' ORDER BY T2.' . $this->nomSql;
            }

            return $arraySelected;
        }
    }

}


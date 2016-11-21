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


/* * ******
 *  CLEF EXTERNE
 *  La clef se trouve dans cette table
 */


/*
 *
 * On utilise le tableau RELATIONS
 *
 * */

$fk_table = $relations[ $this->table_name ][ $name ];

if (!$fk_table) {
    $fk_table = $relations[ $this->table_name ][ getBaseLgField($name) ];
}
$fk_champ = $tabForms[ $fk_table ]['titre'];


$cantBeEmpty = in_array($this->table . "." . $name, $_Gconfig['genform']['nonEmptyForeignKey']);

$doReload = in_array($this->table . "." . $name, $_Gconfig['reloadOnChange']);

if ($doReload) {
    //debug($name);
    $attributs .= ' onchange="saveAndReloadForm();" ';
}


$nomSql = GetTitleFromTable($fk_table, " , ");
$nomSee = GetTitleFromTable($fk_table, " ");

$clef = $this->GetPrimaryKey($fk_table);

if (!$this->editMode) {
    /*
     *
     * On est en MODIFICATION
     *
     * */

    $this->genHelpImage('help_relation', $name);

    /*
     * On selectionne les enregsitrements de la table externe
     *
     */

    if (is_array($preValues) && count($preValues)) {
        $result = $preValues;
    } else {
var_dump($fk_table);
        if (isset($_Gconfig['specialListing'][ $fk_table ]) && $_Gconfig['specialListing'][ $fk_table ][ $this->table_name . '.' . $name ]) {
            $result = $_Gconfig['specialListing'][ $fk_table ][ $this->table_name . '.' . $name ]($this);
        } else
            if (isset($_Gconfig['specialListing'][ $fk_table ]) && $_Gconfig['specialListing'][ $fk_table ][ $this->table_name ]) {
                $result = $_Gconfig['specialListing'][ $fk_table ][ $this->table_name ]($this);
            } else {
                $sql = 'SELECT G.* FROM ' . $fk_table . ' AS G WHERE 1';

                global $_Gconfig;

                if (in_array($fk_table, $_Gconfig['versionedTable'])) {
                    $sql .= ' AND ( G.' . VERSION_FIELD . ' IS NULL  ) ';
                } else if (in_array($fk_table, $_Gconfig['multiVersionTable'])) {
                    $sql .= ' AND G.' . MULTIVERSION_FIELD . ' = ' . getPrimaryKey($fk_table);
                }

                if (!empty($GLOBALS['gs_obj']->myroles[ $fk_table ]['rows'])) {
                    $sql .= ' AND ' . getPrimaryKey($fk_table) . ' IN("' . implode('","', $GLOBALS['gs_obj']->myroles[ $fk_table ]['rows']) . '") ';
                }

                if (!empty($_Gconfig['specialListingWhere'][ $this->table_name . '.' . $name ])) {
                    $sql .= $_Gconfig['specialListingWhere'][ $this->table_name . '.' . $name ];
                }


                $sql .= ' ORDER BY G.' . $nomSql . ' ';
                $result = DoSql($sql);
            }
    }


    /* Debut du select */

    $this->addBuffer('<div class="ajaxselect"><select  onkeydown="smartOptionFinder(this,event)"  ' . $attributs . ' ');

    /* Si c'est une clef avec un champ preview, on rajoute un peu de javascript */
    if (isset($previewField[ $this->table_name ][ $name ]) && strlen($previewField[ $this->table_name ][ $name ]))
        $this->addBuffer(' onchange="genformPreviewFk(\'' . $fk_table . '\',\'' . $name . '\',\'' . $previewField[ $this->table_name ][ $name ] . '\');"  ');

    /* Fin du select */
    $this->addBuffer(' id="genform_' . $name . '" name="genform_' . $name . '">');

    /* Valeur vide */

    if (!$cantBeEmpty) {
        $this->addBuffer('<option value=""> </option>');
    }
    $_Gconfig['relationToAjaxMinimum'] = 500;
    $asAjax = false;
    if (!empty($_Gconfig['relationToAjaxMinimum']) && ($result->RowCount()) > $_Gconfig['relationToAjaxMinimum']) {
        $asAjax = true;
    }

    foreach ($result as $row) {
        /*
         * On parcourt les resultats pour la liste de la table externe
         * */

        $thisValue = '';

        $thisValue = truncate(GetTitleFromRow($fk_table, $row, " , ", false), 100);

        if (strcmp($this->tab_default_field[ $name ], $row[ $clef ]) == 0)
            $this->addBuffer('<option selected="selected" value="' . $row[ $clef ] . '">' . ($thisValue) . '</option>');
        else if (!$asAjax)
            $this->addBuffer('<option  value="' . $row[ $clef ] . '"> ' . ($thisValue) . '</option>');
    }

    /* FIN DU SELECT */
    $this->addBuffer('</select>');

    if ($asAjax) {
        $this->addBuffer('
			<script type="text/javascript">
				selectToSearch("genform_' . $name . '");
			</script>
			');
    }


    /* On peut modifier cet element */

    if ($this->gs->can('edit', $fk_table) && !$restrictedMode) {

        $this->addBuffer('<button name="genform_modfk__' . $fk_table . '__' . $name . '"  onclick="if($(\'#genform_' . $name . '\').val() == \'\') { return false;}"  class="btn btn-mini" title="' . $this->trad("modifier") . '">
            <img src="' . t('src_editer') . '" class="inputimage"            
             />
                 </button>
            ');

        //if(gid(\'genform_'.$name.'\').options[gid(\'genform_'.$name.'\').selectedIndex] == \'\' ) { alert(\'Veuillez choisir un element a modifier\');return false;}"
    }

    /* On a le droit d'ajouter */
    if ($this->gs->can('add', $fk_table) && !$restrictedMode) {
        $this->addBuffer('<button class="btn btn-mini"  title="' . $this->trad("ajouter") . $this->trad($fk_table) . '"  name="genform_add_-_' . $fk_table . '_-_' . $name . '"><img src="' . t('src_new') . '"  alt="' . $this->trad("ajouter") . $this->trad($fk_table) . '" /></button>');
    }


    if (isset($previewField[ $this->table_name ][ $name ])) {
        /*
         * Si c'est un preview on rajoute le bouton et l'IFRAME correspondante
         */

        /* Image preview cliquable */
        $this->addBuffer('<input class="inputimage" id="genform_preview_' . $name . '_btn" src="' . t('src_preview') . '" type="image" name="genform_preview" value="' . $this->trad('preview') . '" onclick="genformPreviewFk(\'' . $fk_table . '\',\'' . $name . '\',\'' . $previewField[ $this->table_name ][ $name ] . '\');return false;" />');

        /* Iframe pour afficher le contenu */
        $this->addBuffer('<iframe
            width="' . $this->larg . '"
            height="250"
            frameborder="0"
            id="genform_preview_' . $name . '"
            src="about:blank"
            style="display:none;border:1px
            solid #aeaeae;"></iframe>');
    }
    $this->addBuffer('</div>');
} else {
    /*
     * On est pas en modification, on affiche juste l'element selectionne
     * */

    if ($this->tab_default_field[ $tab_name ]) {
        /* Uniquement si on a dï¿½ï¿½une valeur */

        if (!isset($_SESSION['cache'][ UNIQUE_SITE ][ $fk_table ]) || !isset($_SESSION['cache'][ UNIQUE_SITE ][ $fk_table ][ $this->tab_default_field[ $tab_name ] ])) {
            $sql = 'SELECT ' . $nomSql . ' FROM ' . $fk_table . ' AS G WHERE ' . $clef . ' = "' . $this->tab_default_field[ $tab_name ] . '" ';
            //ORDER BY G.' . $nomSql;
            $result = GetSingle($sql);

            $GLOBALS['cache'][ UNIQUE_SITE ][ $fk_table ][ $this->tab_default_field[ $tab_name ] ] = GetTitleFromRow($fk_table, $result);
        }

        $this->addBuffer($GLOBALS['cache'][ UNIQUE_SITE ][ $fk_table ][ $this->tab_default_field[ $tab_name ] ]);
    }
}


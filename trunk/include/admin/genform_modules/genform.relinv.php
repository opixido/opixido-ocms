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

/* La clef se trouve dans l'autre table */

/*
 * On utilise le tableau des relations inversees
 *
 * */
$fname = $name;
$fk_table = $relinv[$this->table_name][$name][0];
$name = $relinv[$this->table_name][$name][1];
$ofield = '';
if ($_REQUEST['curId'] != 'new') {
    /**
     * L'enregistrement de la table actuel existe deja              
     * */
    if (isset($thirdtable)) {
        $clefthird = getPrimaryKey($tab_name);
        $sql = 'SELECT * FROM ' . $fk_table . ' AS T1, ' . $tab_name . ' AS T2 WHERE T1.' . $name . ' = "' . $this->id . '" AND T1.' . $thirdtable . ' = T2.' . $clefthird . ' ORDER BY ' . $tabForms[$tab_name]['titre'];
    } else {

        $sql = 'SELECT * FROM ' . $fk_table . ' WHERE ' . $name . ' = "' . $this->id . '" ORDER BY ';

        if (!empty($orderFields[$fk_table])) {
            $sql .= $orderFields[$fk_table][0] . " ,  ";
            $ofield = $orderFields[$fk_table][0];
        }

        //$sql .= $this->getNomForOrder( $tabForms[$fk_table]['titre'] );
        $sql .= GetTitleFromTable($fk_table, ' , ');

// debug("--->".$fk_table );
    }
    $res = GetAll($sql);
} else {

    $res = array();
}


$clef = getPrimaryKey($fk_table);

if (!$this->editMode) {



    if (count($res) <= 4000) {

        $this->genHelpImage('help_relinv_table', $fname);

        /*
         * SI on en a moins de 40 on affiche le tableau directement
         * */

        $this->addBuffer('');
        $this->addBuffer('');

        if (count($res) > 5) {
            $this->addBuffer('<div >'); //style="height:200px;overflow:auto;"
        }

        $sortable = array_key_exists($fk_table, $orderFields);
        $sortable = 1;

        $this->addBuffer('<table class="sortable table table-striped table-bordered table-condensed" rel="' . $fk_table . '__' . $ofield . '" border="0" width="' . ($this->larg - 25) . '" class="genform_table ' . ($sortable ? 'sortable' : '') . ' relinv" ><thead>');

        $ml = 1;
        $cs = $sortable ? 3 : 2;
        if (count($res) > 1 && $sortable)
            $cs = 3;
        else
            $cs = 2;

        $this->addBuffer('<tr>');

        /* ---------------------------
         * Modif Timothée Octobre 2013
         * ---------------------------
         * On ajoute le bouton d'ajout à la relinv s'il possède les droits
         */
        if ($this->gs->can('add', $fk_table)) {
            $this->addBuffer('<th width="20" colspan="' . $cs . '">');

            $this->addBuffer('<button class="btn" title="' . $this->trad('ajouter') . $this->trad($fk_table) . '" name="genform_addfk__' . $fk_table . '__' . $name . '"><img src="' . t('src_new') . '" alt=""  />' . t('Nouveau') . '</button>');


            $this->addBuffer('</th>');
        } else
            $this->addBuffer('<th colspan="' . $cs . '"></th>');

        /**
         * Case TH vide pour chaque action supplémentaire
         */
        if (array_key_exists($fk_table, $_Gconfig['rowActions'])) {

            foreach ($_Gconfig['rowActions'][$fk_table] as $actionName => $v) {

                /* ---------------------------
                 * Modif Timothée Octobre 2013
                 * ---------------------------
                 * On cherche s'il y a au moins un row qui peut faire l'action
                 * 
                 */
                foreach ($res as $row) {
                    $ga = new GenAction($actionName, $fk_table, $row[$clef], $row);
                    $can = (int) $this->gs->can($actionName, $fk_table, $row, $row[$clef]);
                    $checkCondition = (int) $ga->checkCondition();
                    if ($ga && $checkCondition)
                        continue;
                }

                //Si au moins un peut
                if ($ga && $checkCondition) {
                    //	debug($actionName);
                    $this->addBuffer('<th width="20">&nbsp;</th>');
                }
            }
        }


        /* Collones pour les boutons up down */
        if ($sortable) {

            $this->addBuffer('<th width="20" class="order">&nbsp;</th>');
        }


        reset($tabForms[$fk_table]['titre']);
        foreach ($tabForms[$fk_table]['titre'] as $titre) {
            $this->addBuffer('<th>' . preg_replace("/\([^\)]+\)/", "", $this->trad($titre)) . '</th>');
        }

        $this->addBuffer('</tr></thead><tbody>');
        reset($tabForms[$fk_table]['titre']);

        $nbTotIt = count($res);
        $nbIt = 1;
        $ch = ' checked="checked" ';
        foreach ($res as $row) {
            $ml = $row[$clef];
            $this->addBuffer('<tr rel="' . $row[$clef] . '" >');
            $ch = "";
            reset($tabForms[$fk_table]['titre']);


            /*             * *********
              On ajoute le bouton editer
             * *********** */

            $canedit = $this->gs->can('edit', $fk_table, $row, $row[$clef]);
            //debug("**".$fk_table.'-'.$row[$clef].'-'.$canedit);

            if ($canedit) {
                $this->addBuffer('<td>');

                $this->addBuffer('<input
		
								type="image"
								src="' . t('src_editer') . '"
								class="inputimage"
								rel="edit"
								name="genform_modfk__' . $fk_table . '"
								title="' . $this->trad("edit") . '"
								onclick="document.getElementById(\'genform_modfk__' . $fk_table . '_value_' . $ml . '\').checked = \'checked\'" /><input ' . $ch . '
								style="display:none;"
								name="genform_modfk__' . $fk_table . '_value"
								type="radio"
								id="genform_modfk__' . $fk_table . '_value_' . $ml . '"
								value="' . $row[$clef] . '" />
		
								');
                $this->addBuffer('</td>');
            }


            if ($this->gs->can('del', $fk_table, $row, $row[$clef])) {
                $this->addBuffer('<td>');

                $this->addBuffer('
                                    <input

                                    type="image"
                                    src="' . t('src_delete') . '"
                                    class="inputimage"
                                    name="genform_delfk__' . $fk_table . '"
                                    title="' . $this->trad("delete") . '"
                                    onclick="if(confirm(\'' . altify($this->trad('confirm_suppr')) . '\')) {document.getElementById(\'genform_delfk__' . $fk_table . '_value_' . $ml . '\').checked = \'checked\'} else { return false;
                                    }" /><input ' . $ch . '
                                    style="display:none;"
                                    name="genform_delfk__' . $fk_table . '_value"
                                    type="radio"
                                    id="genform_delfk__' . $fk_table . '_value_' . $ml . '"
                                    value="' . $row[$clef] . '" />

                            ');
                $this->addBuffer('</td>');
            }


            /**
             * Actions supplémentaires
             */
            if (array_key_exists($fk_table, $_Gconfig['rowActions'])) {

                foreach ($_Gconfig['rowActions'][$fk_table] as $actionName => $v) {

                    $ga = new GenAction($actionName, $fk_table, $row[$clef], $row);

                    if ($this->gs->can($actionName, $fk_table, $row, $row[$clef]) && $ga->checkCondition()) {
                        $this->addBuffer('<td>');


                        $this->addBuffer('
					<button	style="background:none;padding:0;border:0;cursor:pointer"
						class="inputimage"
						name="genform_relinvaction[' . $actionName . '][' . $fk_table . ']"
						title="' . $this->trad($actionName) . '"
						value="' . $ml . '"
    					 ><img src="' . t('src_' . $actionName) . '" /></button>
			');
                        // pour le faire en ajax
                        // onclick="ajaxAction(\''.$actionName.'\',\''.$fk_table.'\',\''.$ml.'\',\'\');return false;"

                        /*
                          onclick="document.getElementById(\'genform_'.$actionName.'fk__'.$fk_table.'_value_'.$ml.'\').checked=\'checked\'
                          <input '.$ch.'
                          style="display:none;"
                          name="genform_'.$actionName.'fk__' . $fk_table . '_value"
                          type="radio"
                          id="genform_'.$actionName.'fk__' . $fk_table . '_value_'.$ml.'"
                          value="' . $row[$clef] . '" />
                         */
                        $this->addBuffer('</td>');
                    }
                }
            }



            /*             * ***********
              On ajoute les boutons pour la gestion de l'ordre ?
             * ************ */
            if ($sortable) {

                $this->addBuffer('<td class="order">');

                if ($nbIt > 1) {

                    $this->addBuffer('<input
                                        type="image"
                                        src="' . t('src_up') . '"
                                        class="inputimage"
                                        onclick="gid(\'genform_upfk__' . $fk_table . '__' . $ml . '__' . $name . '\').checked = \'checked\'"  name="genform_stay"
                                        title="' . $this->trad("getup") . '"/>');

                    $this->addBuffer('<input
                                        style="display:none;"
                                        name="genform_upfk"
                                        type="radio"
                                        id="genform_upfk__' . $fk_table . '__' . $ml . '__' . $name . '"
                                        value="' . $fk_table . '__' . $row[$clef] . '__' . $name . '" /> ');
                } else {
                    //$this->addBuffer('<img src="pictos/up_off.gif" alt="up" />' );
                }
                $this->addBuffer('</td>');

                $this->addBuffer('<td class="order">');

                if ($nbIt < $nbTotIt) {

                    $this->addBuffer('<input type="image"
                                        			src="' . t('src_down') . '" 
                                        			class="inputimage" 
                                        			onclick="gid(\'genform_downfk__' . $fk_table . '__' . $ml . '__' . $name . '\').checked = \'checked\'" 
                                        			 name="genform_stay"  title="' . $this->trad("getdown") . '"/>');

                    $this->addBuffer('<input  style="display:none;"
                                         name="genform_downfk" type="radio"
                                          id="genform_downfk__' . $fk_table . '__' . $ml . '__' . $name . '" value="' . $fk_table . '__' . $row[$clef] . '__' . $name . '" /> ');
                } else {
                    // $this->addBuffer('<img src="pictos/down_off.gif" alt="up" />' );
                }
                $this->addBuffer('</td>');
            }



            $t = new GenForm($fk_table, "", $row[$clef], $row);
            $t->editMode = true;
            $t->onlyData = true;
            foreach ($tabForms[$fk_table]['titre'] as $titre) {

                $this->addBuffer('<td>&nbsp;' . limitWords(($t->gen($titre)), 30) . '</td>');
            }
            $editMode = false;
            reset($tabForms[$fk_table]['titre']);

            $this->addBuffer('</tr>');
            $ml++;
            $nbIt++;
        }
        if (!count($res)) {
            $this->addBuffer('<tr><td colspan="10" style="text-align:center;">' . $this->trad('aucun_element') . '</td></tr>');
        }

        $this->addBuffer('</tbody></table><br /> ');

        if (count($res) > 5) {
            $this->addBuffer('</div>');
        }


        $this->addBuffer('');
        //$this->addBuffer( '<br />&nbsp;<br />&nbsp;<br/>' );
    } else {


        $this->genHelpImage('help_relinv_menu', $fname);
        /*
         * Si on a plus de 40 elements on affiche un menu deroulant
         * */

        $this->addBuffer('<div id="genform_floatext">');
        if (( $fk_table != 't_page' ) || (!count($res) && $fk_table == 't_page' ))
            $this->addBuffer('<input type="submit"  class="input_btn"  value="' . $this->trad("ajouter") . '" name="genform_addfk__' . $fk_table . '__' . $name . '" > ');

        if (count($res)) {

            $this->addBuffer('ou <select name="genform_modfk__' . $fk_table . '_value" >');
            //if ( $this->table_name != "s_rubrique" && $fk_table != "t_page" )
            $this->addBuffer('<option value=""> ' . $this->trad("modify_item") . '</option>');
            // while($row = mysql_fetch_array($res)) {
            //$this->addBuffer( '<option value="' . $row[$clef] . '" > --> Liste <-- </option>' );
            foreach ($res as $row) {
                $this->addBuffer('<option value="' . $row[$clef] . '" > --> ' . str_replace('"', '&quot;', $this->getNomForValue($tabForms[$fk_table]['titre'], $row)) . '</option>');
            }

            $this->addBuffer('</select>');
            if (!$restrictedMode) {

                if ($this->gs->can('edit', $fk_table)) {
                    $this->addBuffer('<input type="image"  class="inputimage" src="' . t('src_editer') . '" name="genform_modfk__' . $fk_table . '" value="' . $this->trad("modifier") . '" onclick="if(document.forms[0].genform_modfk__' . $fk_table . '_value.selectedIndex < 1) return false;" />');
                }
            }
        }


        $this->addBuffer('</div>');
        $this->addBuffer('<br />&nbsp;<br />&nbsp;<br/>');
    }
} else {
    $i = 0;
    // while($row = mysql_fetch_array($res)) {
    foreach ($res as $row) {
        if ($i > 0) {
            $this->addBuffer($this->separator);
        }
        $this->addBuffer(GetTitleFromRow($fk_table, $row, ' - '));
        $i++;
    }
}


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




if (!$this->editMode) {
    /*
     *
     * On est en MODIFICATION
     *
     * */

    $this->genHelpImage('help_set', $name);


    $doReload = in_array($this->table . "." . $name, $_Gconfig['reloadOnChange']);

    if ($doReload) {
        $attributs .= ' onchange="saveAndReloadForm();" ';
    }

    //debug($this->tab_field[$name]);
    /* Debut du select */
    $this->addBuffer('<select multiple ' . $attributs . ' ');

    /* Si c'est une clef avec un champ preview, on rajoute un peu de javascript */

    /* Fin du select */
    $this->addBuffer(' id="genform_' . $name . '" name="genform_' . $name . '[]">');



    $sets = getsetValues($this->tab_field, $name);

    $curSet = explode(',', $this->tab_default_field[$name]);

    /* foreach($curSet as $k=>$v) {
      $curSet[$k] = substr($v,1,-1);
      } */

    foreach ($sets as $set) {
        /*
         * On parcourt les resultats pour la liste de la table externe
         * */

        $thisValue = $this->trad('set_' . $set);



        if (in_array($set, $curSet))
            $this->addBuffer('<option selected="selected" value="' . $set . '">' . ( $thisValue ) . '</option>');

        else
            $this->addBuffer('<option  value="' . $set . '"> ' . ( $thisValue ) . '</option>');
    }

    /* FIN DU SELECT */
    $this->addBuffer('</select>');



    /* On peut modifier cet element */
} else {
    /*
     * On est pas en modification, on affiche juste l'ï¿½ï¿½ent sï¿½ectionnï¿½
     * */

    if ($this->tab_default_field[$tab_name]) {
        /* Uniquement si on a dï¿½ï¿½une valeur */


        $this->addBuffer($this->trad('set_' . $this->tab_default_field[$tab_name]));
    }
}
?>
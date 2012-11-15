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
 * On utilise le tableau RELATIONS
 *
 * */



if (!$this->editMode) {
    /*
     *
     * On est en MODIFICATION
     *
     * */

    $this->genHelpImage('help_enum', $name);


    $doReload = in_array($this->table . "." . $name, $_Gconfig['reloadOnChange']);

    if ($doReload) {
        $attributs .= ' onchange="saveAndReloadForm();" ';
    }

    //debug($this->tab_field[$name]);
    /* Debut du select */
    $this->addBuffer('<select  ' . $attributs . ' ');

    /* Si c'est une clef avec un champ preview, on rajoute un peu de javascript */

    /* Fin du select */
    $this->addBuffer(' id="genform_' . $name . '" name="genform_' . $name . '">');



    $enums = getEnumValues($this->table_name, $name);


    foreach ($enums as $enum) {
        /*
         * On parcourt les resultats pour la liste de la table externe
         * */
        if (tradExists('enum_' . $enum)) {
            $thisValue = $this->trad('enum_' . $enum);
        } else if (tradExists($name . '_' . $enum)) {
            $thisValue = $this->trad($name . '_' . $enum);
        } else {
            $thisValue = $enum;
        }



        if (strcmp($this->tab_default_field[$name], $enum) == 0)
            $this->addBuffer('<option selected="selected" value="' . $enum . '">' . ( $thisValue ) . '</option>');

        else
            $this->addBuffer('<option  value="' . $enum . '"> ' . ( $thisValue ) . '</option>');
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


        $this->addBuffer($this->trad('enum_' . $this->tab_default_field[$tab_name]));
    }
}
?>
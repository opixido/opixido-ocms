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

/*
 * Le champ est de type NUMERIC
 * */
if ($this->tab_field[$name]->max_length == 1) {
    /**
     * Taille 1 => Donc boolean Oui / non
     */
    $sel0 = '';
    $sel1 = '';
    $sel2 = '';
    $this->addBuffer('<div class="radio">');
    if ($this->tab_default_field[$name] == "-1") {
        $sel0 = 'checked="checked"';
        $valeur = $this->trad('non_renseigne');
    } else if ($this->tab_default_field[$name] > 0) {
        $sel1 = 'checked="checked"';
        $valeur = $this->trad('t_oui');
    } else {
        $sel2 = 'checked="checked"';
        $valeur = $this->trad('t_non');
    }

    if (!$this->editMode) {

        /**
         * Mode edition donc champ input
         */

        global $_Gconfig;

        /**
         * Attributs par défaut
         */
        $attributs = ' type="radio" name="genform_' . $name . '" ';

        /**
         * Avec un reload sur le change ?
         */
        $doReload = in_array($this->table . "." . $name, $_Gconfig['reloadOnChange']) || in_array($name, $_Gconfig['reloadOnChange']);
        if ($doReload) {
            $attributs .= ' onchange="saveAndReloadForm();" ';
        }

        $this->addBuffer('<input ' . $attributs . '  ' . $sel1 . ' " value="1" id="genform_' . $name . '_1" />
                          <label for="genform_' . $name . '_1">' . t('oui') . '</label>');
        $this->addBuffer('
                        <label for="genform_' . $name . '_0">' . t('non') . '</label>
                        <input ' . $attributs . '  ' . $sel2 . ' value="0" id="genform_' . $name . '_0" />');

        $this->addBuffer('</div>');

    } else {

        /**
         * Valeur seullement
         */
        $this->addBuffer(ta('genform_boolean_' . $this->tab_default_field[$name]));
        $this->addBuffer('</div>');

    }
} else {
    /**
     * Champ numeric simple
     */
    if (!$this->editMode) {
        $scale = $this->tab_field[$name]->scale;
        if (!$scale) {
            $scale = 4;
        }
        $step = '0.' . str_repeat('0', $scale) . '1';
        $this->addBuffer('<input
            ' . $jsColor . '
            type="text"
            name="genform_' . $name . '"
            id="genform_' . $name . '"
            size="8"
            value="' . $this->tab_default_field[$name] . '"
             />');
    } else {
        $this->addBuffer($this->tab_default_field[$name]);
    }
}


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
/**
 * Champ type Text
 */
if (@in_array($name, $rteFields) || in_array(getBaseLgField($name), $rteFields)) {

    /**
     * Champs WYSIWYG
     */
    if (!$this->editMode) {

        /**
         * Mode edition
         */
        $this->genHelpImage('help_rte', $name);

        /**
         * Dans une popup ou iframe 
         * on affiche tout
         */
        $r = new genRteInline('genform_' . $name, $this->tab_default_field[$name]);
        $this->addBuffer($r->createRte());
    } else {

        /**
         * Visualisation seulement
         */
        $this->addBuffer(limitWords(stripslashes(strip_tags($this->tab_default_field[$name])), 10));
    }
} else {

    /**
     * Champ texte NON wysiwyg
     */
    if (!$this->editMode) {

        /**
         * Mode edition
         */
        $this->genHelpImage('help_texte', $name);


        if (in_array($name, $_Gconfig['codeFields'])) {

            /**
             * Champ de code
             */
            if (!$GLOBALS['codeFieldPrinted']) {
                $this->addBuffer('<script language="javascript" type="text/javascript" src="edit_area/edit_area_full.js"></script>');
            }
            $this->addBuffer('<textarea wrap="virtual"  class="genform_codefieldv2"  ' . $attributs . ' id="genform_' . $name . '" name="genform_' . $name . '">' . str_replace('&amp;', '&amp;amp;', $this->tab_default_field[$name]) . '</textarea>');
            $this->addBuffer('
			<script type="text/javascript">
						editAreaLoader.init({
							id : "genform_' . $name . '"		// textarea id
							,syntax: "php"			// syntax to be uses for highgliting
							,start_highlight: true		// to display with highlight mode on start-up
							,min_height :400
							,min_width :580
						});
			</script>
			');
        } else {

            /**
             * Textarea normal
             */
            $this->addBuffer('<textarea ' . $jsColor . ' class="resizable" rows="5" ' . $attributs . ' id="genform_' . $name . '" name="genform_' . $name . '">' . akev($this->tab_default_field, $name) . '</textarea>');
        }

        /**
         * Boutons d'insertion automatique
         */
        $this->addBuffer($this->genInsertButtons('genform_' . $name . ''));
    } else if ($this->tab_default_field[$name]) {

        /**
         * Mode visualisation
         */
        if (in_array($name, $_Gconfig['codeFields'])) {

            /**
             * Champ de code
             */
            $this->addBuffer('<div class="genform_codefield" style="color:#555;overflow:hidden">' . nl2br(htmlentities($this->tab_default_field[$name])) . '</div>');
        } else {

            /**
             * Champ texte
             */
            $this->addBuffer(nl2br($this->tab_default_field[$name]));
        }
    }
}
?>
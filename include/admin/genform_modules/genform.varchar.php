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

if (!$this->editMode) {

    /**
     * Champ de couleur Hexa
     * 
     */
    if (is_array($_Gconfig['colorFields']) && arrayInWord($_Gconfig['colorFields'], $name)) {


        $this->genHelpImage('help_texte', $name);

        $fl = $this->tab_field[$name]->max_length;

        /**
         * Couleur par défaut
         */
        if ($this->tab_default_field[$name]) {
            $style = 'style="background-color:#' . $this->tab_default_field[$name] . '"';
        }

        /**
         * Emplacement de couleur
         */
        $this->addBuffer('<span class="colorField" id="colorField_' . $name . '" ' . $style . '>
    						&nbsp;   &nbsp;   						
    					 </span> ');

        /**
         * Champ de saisie Couleur
         */
        $this->addBuffer(' &nbsp;<input
									size="6" 
									onchange="gid(\'colorField_' . $name . '\').style.backgroundColor=\'#\'+this.value;" 
									id="genform_' . $name . '" ' . $jsColor . ' ' . $attributs . '  
									name="genform_' . $name . '" maxlength="' . $fl . '" 
									class="genform_varchar" 
									value=' . alt($this->tab_default_field[$name]) . ' />');

        /**
         * Lien pour ouverture Popup
         */
        $this->addBuffer(' <a class="btn_spectre"
								href="#" 
								onclick="popup(\'./colorPicker/colorSelector.html?id=' . $name . '\',360,240);return false;" >
    					 	<img src="./colorPicker/spectre.jpg" alt="" style="vertical-align:middle"  />
    					 </a>');
    } else
    /**
     * Varchar de type URL
     */
    if (is_array($_Gconfig['urlFields']) && arrayInWord($_Gconfig['urlFields'], $name)) {


        $this->genHelpImage('help_url', $name);

        /**
         * Valeur par defaut si le champ est vide
         */
        if (!strlen(trim($this->tab_default_field[$name]))) {
            $this->tab_default_field[$name] = DEFAULT_URL_VALUE;
        }

        /**
         * Champ de saisie
         */
        $this->addBuffer('
		    <input 
		    		id="genform_' . $name . '" ' . $jsColor . ' 
		    		type="text" ' . $attributs . ' 
		    		name="genform_' . $name . '" 
		    		size="80" 
		    		maxlength="' . $this->tab_field[$name]->max_length . '" 
		    		value=' . alt($this->tab_default_field[$name]) . ' /> ');

        /**
         * Bouton pour tester le lien
         */
        $this->addBuffer('
		    <img src="' . ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_ARBO_SIZE . '/categories/applications-internet.png" 
		    	onclick="smallPopup(gid(\'genform_' . $name . '\').value)" 
		    	alt="' . t('tester_le_lien') . '" />');

        /**
         * Bouton pour choisir une rubrique du site
         */
        /*
          $this->addBuffer('
          <img src="' . ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_ARBO_SIZE . '/status/folder-open.png"
          onclick="XHR_links(\'genform_' . $name . '\')"
          alt="' . t('choisir_rubrique') . '" />');
         */
        /**
         * Conteneur pour l'arbo de sélection
         */
        $this->addBuffer('<div id="genform_' . $name . '_links" class="xhr_links"></div>');


        /**
         * VARCHAR DE TYPE EMAIL
         *
         */
    } else if (arrayInWord($mailFields, $name)) {

        $this->genHelpImage('help_email', $name);

        /**
         * Champ de saisie Email
         */
        $this->addBuffer('<input
							id="genform_' . $name . '"  ' . $jsColor . ' ' . $attributs . ' 
							type="email"
							name="genform_' . $name . '"
                                                            pattern="[^ @]*@[^ @]*"
							size="60"  
							value=' . alt($this->tab_default_field[$name]) . '
							/>  ');



        /**
         * Varchar de type Mot de passe
         */
    } else if (is_array($_Gconfig['passwordFields']) && in_array($name, $_Gconfig['passwordFields'])) {

        /**
         * Champ du mot de passe
         */
        $this->addBuffer('<input
							id="genform_' . $name . '" ' . $jsColor . '
							type="text" ' . $attributs . '
							name="genform_' . $name . '"
							size="12"
							maxlength="' . $this->tab_field[$name]->max_length . '"
							value="" />');


        if (!$this->editMode) {
            /**
             * Lien pour génération auto
             */
            $this->addBuffer('

				<a href="#"
					class="btn"
					style="clear:both;"
					id="generatepassword_' . $name . '" >
					<img src="' . t('src_random_password') . '"
						class="inputimage"
						type="image"
						/>' . t('generate_random_password') . '</a>');

            $this->addBuffer('
					<script type="text/javascript">
						$("#generatepassword_' . $name . '").click(
						function() {
							$("#genform_' . $name . '").val(generatepass(8));
							return false;
						});
					</script>
				');
        }




        /**
         * VARCHAR NORMAL
         */
    } else if (in_array($name, $_Gconfig['passwordFieldsMd5'])) {

        /**
         * Champ du mot de passe
         */
        $this->addBuffer('<input
							id="genform_' . $name . '" ' . $jsColor . '
							type="text" ' . $attributs . '
							name="genform_' . $name . '"
							size="12"
							maxlength="' . $this->tab_field[$name]->max_length . '"
							value="" />');


        if (!$this->editMode) {
            /**
             * Lien pour génération auto
             */
            $this->addBuffer('

				<a href="#"
					class="titreListe"
					style="clear:both;"
					id="generatepassword_' . $name . '" >
					<img src="' . t('src_random_password') . '"
						class="inputimage"
						type="image"
						/>' . t('generate_random_password') . '</a>');

            $this->addBuffer('
					<script type="text/javascript">
						$("#generatepassword_' . $name . '").click(
						function() {
							$("#genform_' . $name . '").val(generatepass(10));
							return false;
						});
					</script>
				');
        }




        /**
         * VARCHAR NORMAL
         */
    } else {

        $this->genHelpImage('help_texte', $name);

        /**
         * Longueur du Varchar
         */
        $fl = $this->tab_field[$name]->max_length;


        if ($fl >= 100) {
            /**
             * Si supérieur à 100 => Textarea multiligne
             */
            $this->addBuffer('<textarea
							class="resizable" 
							id="genform_' . $name . '" ' . $jsColor . ' ' . $attributs . '  
							name="genform_' . $name . '" 
							rows="2"  
							maxlength="' . $fl . '" 
							class="genform_varchar" >' . ( $this->tab_default_field[$name] ) . '</textarea>');
        } else {
            /**
             * Si inférieur à 100 => input=type@text
             */
            $this->addBuffer('<input
								size="' . $fl . '" 
								id="genform_' . $name . '" 
								' . $jsColor . ' 
								' . $attributs . '  
								name="genform_' . $name . '" 
								maxlength="' . $fl . '" 
								class="genform_varchar" 
								value=' . alt(akev($this->tab_default_field, $name)) . '"
								/>');

            $this->addBuffer($this->genInsertButtons('genform_' . $name . ''));
        }
    }
} else {
    /**
     * Mode visualisation
     */
    if (arrayInWord($_Gconfig['urlFields'], $name)) {
        /**
         * Pour les URLs on créé un lien
         */
        if ($this->tab_default_field[$name]) {
            $vall = strlen($this->tab_default_field[$name]) > 50 ?
                    substr($this->tab_default_field[$name], 0, 50) . '...' :
                    $this->tab_default_field[$name];
            $this->addBuffer('<a href="' . $this->tab_default_field[$name] . '" target="_blank">' . ( $vall ) . '</a>');
        }
    } else if (arrayInWord($mailFields, $name)) {
        /**
         * Pour les mails => Mailto
         */
        if ($this->tab_default_field[$name]) {
            $this->addBuffer('<a
									href="mailto:' . $this->tab_default_field[$name] . '" 
									target="_blank">' .
                    ( $this->tab_default_field[$name] ) . '
								</a>');
        }
    }else if (in_array($name, $_Gconfig['passwordFields'])) {
        $this->addBuffer(ta('password_encrypted'));
    }else {
    
        /**
         * Pour les autres on affiche
         */
        $this->addBuffer(( $this->tab_default_field[$name]));
    }
}

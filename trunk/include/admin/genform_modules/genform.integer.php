<?php

/*
 * Le champ est de type INTEGER
 * */
if ($this->tab_field[$name]->max_length < 2) {

    /*
     * Si sa longueur est infï¿½ieur ï¿½2 alors c'set un boolï¿½n OUI / NON
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
        /* $this->addBuffer( '<select ' . $jsColor . ' name="genform_' . $name . '"  '.$attributs.' >' );
          $this->addBuffer( '<option value="-1" ' . $sel0 . ' > '.$valeur.' </option>' );
          $this->addBuffer( '<option value="1" ' . $sel1 . ' >' . $this->trad( 't_oui' ) . '</option>' );
          $this->addBuffer( '<option value="0" ' . $sel2 . ' >' . $this->trad( 't_non' ) . '</option>' );
          $this->addBuffer( '</select>' );
         */
        global $_Gconfig;
        $doReload = in_array($this->table . "." . $name, $_Gconfig['reloadOnChange']) || in_array($name, $_Gconfig['reloadOnChange']);

        if ($doReload) {
            //debug($name);
            $attributs .= ' onchange="saveAndReloadForm();" ';
        }

        $this->addBuffer('<input ' . $attributs . ' type="radio" ' . $sel1 . ' name="genform_' . $name . '" value="1" id="genform_' . $name . '_1" />
                        <label for="genform_' . $name . '_1">' . t('oui') . '</label>');
        $this->addBuffer('
                        <label for="genform_' . $name . '_0">' . t('non') . '</label><input ' . $attributs . '  type="radio"  ' . $sel2 . ' name="genform_' . $name . '" value="0" id="genform_' . $name . '_0" />');

        $this->addBuffer('</div>');
    } else {

        $this->addBuffer(ta('genform_boolean_' . $this->tab_default_field[$name]));
    }
} else {
    if (!$this->editMode)
        $this->addBuffer('<input ' . $jsColor . ' type="text" name="genform_' . $name . '"  id="genform_' . $name . '" size="8" maxlength="' . $this->tab_field[$name]->max_length . '" value="' . $this->tab_default_field[$name] . '" />');
    else
        $this->addBuffer($this->tab_default_field[$name]);
}


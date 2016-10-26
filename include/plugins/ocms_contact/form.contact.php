<?php

$isContact = false;

global $_Gconfig;
if ($form->tab_default_field['fk_gabarit_id'] > 0) {
    $r = new row('s_gabarit', $form->tab_default_field['fk_gabarit_id']);

    if ($r->id && $r->gabarit_classe == 'genContact' || in_array($r->gabarit_classe, $_Gconfig['ocms_contact']['subClass'])) {
        $isContact = true;
    }
}


/**
 * On affiche les infos de contact uniquement si on est sur un
 * gabarit de contact
 */
if ($isContact) {
    $row = new row($this->table, $this->id);
    if (!count($row->fk_contact_field_id) && count($row->fk_contact_id)) {
        /**
         * Ajout automatique des champs email et commentaire si aucun champ de déjà créé
         * mais qu'un contact est créé
         */
        global $co;

        $co->autoExecute('plug_contact_field', array(
            'contact_field_id'        => '',
            'fk_rubrique_id'          => $this->id,
            'contact_field_ordre'     => 1,
            'contact_field_nom_' . LG => t('c_email'),
            'contact_field_type'      => 'email',
            'contact_field_needed'    => '1',
            'contact_field_name'      => 'c_email'
        ), 'INSERT');

        $co->autoExecute('plug_contact_field', array(
            'contact_field_id'        => '',
            'fk_rubrique_id'          => $this->id,
            'contact_field_ordre'     => 2,
            'contact_field_nom_' . LG => t('c_comment'),
            'contact_field_type'      => 'textarea',
            'contact_field_needed'    => '1',
            'contact_field_name'      => 'c_comment'
        ), 'INSERT');
    }

    $form->gen('fk_contact_id');
    $form->gen('fk_contact_field_id');
}



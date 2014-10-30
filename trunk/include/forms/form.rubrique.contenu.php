<?php


$form->genlg('rubrique_titre');

if ($form->tab_default_field['rubrique_type'] == 'page' || $form->tab_default_field['rubrique_type'] == 'siteroot') {
    $form->gen('fk_paragraphe_id');
}
                    

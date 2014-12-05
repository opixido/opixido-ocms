<?php


$form->genlg("rubrique_picto");

$form->startFieldset('referencement', false);


if ($form->tab_default_field['rubrique_type'] == 'page' || $form->tab_default_field['rubrique_type'] == "siteroot" || $form->tab_default_field['rubrique_type'] == "folder") {

    if (!$this->editMode) {
        p('<div class="alert alert-info"><h3>' . t('meta_informations') . '</h3>');

        p('<p>' . t('desc_meta') . '</p></div>');
    }

    $form->genlg("rubrique_keywords");

    $form->genlg("rubrique_desc");
}

if ($form->tab_default_field['rubrique_type'] != 'link') {

    $form->genlg("rubrique_url");
}

$form->endFieldset();
$form->startFieldset('programmation', false);
$form->gen($_Gconfig['field_date_online']);
$form->gen($_Gconfig['field_date_offline']);


$form->endFieldset();

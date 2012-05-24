<?php

if ($_REQUEST['curId'] != "new") {

    if ($form->tab_default_field['rubrique_type'] == 'page' || $form->tab_default_field['rubrique_type'] == "siteroot") {

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

}

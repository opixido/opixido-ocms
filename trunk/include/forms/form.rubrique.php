<?php
global $_Gconfig;

/* Si on ajoute une rubrique on affiche un formulaire un peu spécial */

if ($_REQUEST['curId'] == "new") {

    /**
     * On selectionne les rubriques de meme niveau deja existantes
     * Afin de creer un tableau des URLs existantes et donc non atribuables
     */
    $sql = 'SELECT ' . getLgFields('rubrique_url', ' , ') . ' FROM s_rubrique 
                        WHERE fk_rubrique_id = "' . mes($_REQUEST['genform__add_sub_id']) . '"';
    $resNot = GetAll($sql);

    /**
     * Tableau construit des URLs
     */
    $notUrl = array();
    foreach ($resNot as $row) {
        reset($_Gconfig['LANGUAGES']);
        foreach ($_Gconfig['LANGUAGES'] as $lg) {
            $notUrl[$lg][$row['rubrique_url_' . $lg]] = true;
        }
    }
    /**
     * Tableau des champs lier
     */
    reset($_Gconfig['LANGUAGES']);
    $mfields = array();
    foreach ($_Gconfig['LANGUAGES'] as $lg) {
        $mfields[] = 'mygen_rubrique_url_' . $lg;
        $mfields[] = 'genform_rubrique_titre_' . $lg;
    }
    
    /**
     * On envoit les variables au JS
     */
    ?>
    <script type="text/javascript">
       
        <?php
        p('var LGs = ' . json_encode($_Gconfig['LANGUAGES']) . ';');
        p('var notUrl = ' . json_encode($notUrl) . ';');
        p('var mfields = ' . json_encode($mfields) . ';');
        ?>

        /**
         * On supprime les champs inutiles lors de la creation
         */
        $(document).ready(function() {
            $('#genform_formulaire').unbind('submit').submit(saveAndReloadForm);
            $('#genform_header_btn_lgs,.genform_onglet').remove();
            $('#genform_cancel').parent().remove();
            checkFields();
        });
        
    </script>

    <?php
    
    p('<h1>' . t('info_crea_rub') . '</h1>');

    if ($_REQUEST['genform__add_sub_id'] == getParam('rub_publication_id')) {
        p('<input type="hidden" value="yes" name="is_publi_rub" id="is_publi_rub" />');
    }

    reset($_Gconfig['LANGUAGES']);
    foreach ($_Gconfig['LANGUAGES'] as $lg) {
        $form->genHiddenField('rubrique_url_' . $lg);
            
        global $_Gconfig;
        if($_Gconfig['URL_MANAGER'] == "genUrlV3"){
        	
			 $supRub = getUrlFromId($_REQUEST['genform__add_sub_id'], $lg);
				
			$parents_url = str_replace(BU.'/'.$lg, '', $supRub);
        	$parents_url = trim($parents_url, '/');
			
            $form->gen('rubrique_titre_' . $lg, '', '', 'onkeyup="updateChampUrl(\'mygen_rubrique_url_' . $lg . '\',this.value,\''.$parents_url.'/\')"');
		?>
        	<label for="mygen_rubrique_url_<?= $lg ?>"><?= t('url_' . $lg . '_will_be') ?></label><span style="font-family:verdana;padding:5px;display:block;background:#eee;border:1px solid #999">
            <?php
	           echo str_replace($parents_url.'/', '', $supRub); 
            ?>
            <input type="text" style="border:0;font-family:verdana;" value="<?php echo $parents_url."/"?>" name="genform_rubrique_url_<?= $lg ?>" id="mygen_rubrique_url_<?= $lg ?>" onchange="checkFields()" />
       	<?php
        }
        else{
        	$form->gen('rubrique_titre_' . $lg, '', '', 'onkeyup="updateChampUrl(\'mygen_rubrique_url_' . $lg . '\',this.value)"');
		?>
        	<label for="mygen_rubrique_url_<?= $lg ?>"><?= t('url_' . $lg . '_will_be') ?></label><span style="font-family:verdana;padding:5px;display:block;background:#eee;border:1px solid #999">
	        <?php			
	        	echo getUrlFromId($_REQUEST['genform__add_sub_id'], $lg);
	        ?>
            <input type="text" style="border:0;font-family:verdana;" value="" name="genform_rubrique_url_<?= $lg ?>" id="mygen_rubrique_url_<?= $lg ?>" onchange="checkFields()" />
        <?php
		}
        ?>
        </span>
        <hr/>
        <?php
    }

    $form->gen('rubrique_type');
} else {

    /**
     * La page existe déjà ... on ne fait qu'afficher les champs ...
     */
    $form->genlg('rubrique_titre');

    if ($form->tab_default_field['rubrique_type'] == 'page' || $form->tab_default_field['rubrique_type'] == 'siteroot') {
        $form->gen('fk_paragraphe_id');
    }

}

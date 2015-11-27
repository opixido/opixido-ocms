<?php

/**
 * Liste déroulante de types de para
 */
$form->gen('fk_para_type_id');

/**
 * On récupère les paramètres pour les différents types de paragraphes
 */
$sql = 'SELECT * FROM s_para_type';
$res = DoSql($sql);


$paraTypes = array();
foreach ($res as $row) {
    /**
     * On a les champs supplémentaires
     */
    $fields = explode(',', $row['para_type_champs']);

    /**
     * Et tous les champs par défaut
     */

    if ($row['para_type_use_img']) {
        /**
         * Les images ont besoin de ces champs
         */
        $fields[] = 'paragraphe_img_1';
        $fields[] = 'paragraphe_img_1_alt';
        $fields[] = 'paragraphe_img_1_legend';
    }

    if ($row['para_type_use_file']) {
        /**
         * Les fichiers du fichier et de sa légende
         */
        $fields[] = 'paragraphe_file_1';
        $fields[] = 'paragraphe_file_1_legend';
    }

    if ($row['para_type_use_txt']) {
        /**
         * Le contenu se suffit à lui même
         */
        $fields[] = 'paragraphe_contenu';
    }

    if ($row['para_type_use_link']) {
        /**
         * Ainsi que le lien
         */
        $fields[] = 'paragraphe_link_1';
    }

    /**
     * Et on met tout ça dans notre tableau global qu'on enverra bientôt à JS
     */
    $paraTypes[$row['para_type_id']] = $fields;
}

?>

<script>

    (function () {

        'use strict';

        /**
         * Le tableau Json de tous les types de paragraphe et leurs paramètres
         */
        var paraTypes = <?=json_encode($paraTypes)?>;

        /**
         * Mise à jour de l'affichage des champs de paragraphe en fonction du type de para choisi
         */
        function updateParagraphes() {
            /**
             * Valeur du seleect de type de paragraphe
             * @type {*|jQuery}
             */
            var currentVal = $('#genform_fk_para_type_id').val();

            /**
             * Liste des champs à afficher pour ce type de para
             */
            var currentFields = paraTypes[currentVal];

            /**
             * On masque tous les champs du formulaire
             * Sauf le champ de selection des types de para
             */
            $('.genform_champ_out:not(#genform_div_fk_para_type_id,#genform_div_paragraphe_titre)').hide();

            /**
             * Et on affiche tous les champs nécessaires à ce type de para
             */
            $(currentFields).each(function () {
                $('#genform_div_' + this).css('display', 'block');
            });
        }

        /**
         * On met à jour les pargraphes des qu'on change de type de para
         */
        $('#genform_fk_para_type_id').on('change blur', updateParagraphes);

        /**
         * Ainsi qu'a l'affichage du formulaire
         */
        $(document).ready(updateParagraphes);
    })();


</script>

<?php
global $_Gconfig;


/**
 * Visible uniquement lors de la création d'une page
 */
if ($_REQUEST['curId'] == "new") {
    ?>
    <script>
        mfields = [];
        function checkAlertUrl() {
            if (window.alertUrl) {
                var res = confirm(<?= alt(ta('attention_url_manquante')) ?>);
                window.alertUrl = false;
                if (res) {
                    return saveAndReloadForm();
                } else {
                    return false;
                }
            }
            return saveAndReloadForm();
        }

        $(document).ready(function () {
            setTimeout(function () {
                $('#genform_navi li').hide();
                $('#genform_btn_page_<?= ($this->curPageLooping - 1) ?>').show().addClass('active');
                $('#genform_formulaire').addClass('form-inline');
                $('.tab-pane.active').removeClass('active');

                $('#genform_page_<?= $this->curPageLooping ?>').addClass('active');
                $('.tab-pane').not('.active').remove();
                $('#genform_formulaire').unbind('submit').submit(checkAlertUrl);
                $('#genform_header_btn_lgs,.genform_onglet').remove();
                $('#genform_cancel').parent().remove();
                checkFields();
            }, 10);
        });
    </script>
    <?php
    $gabarit_id = false;


    if (!empty($_REQUEST['genform__add_sub_id'])) {
        /**
         * On a déjà l'ID à insérer ... cool !
         */
        $parent = $_REQUEST['genform__add_sub_id'];
    } else if (!empty($_REQUEST['relOne'])) {

        if (empty($_Gconfig['relOneParent']['s_rubrique'][ $_REQUEST['relOne'] ])) {
            $this->fieldsDone++;
            echo '<div class="alert alert-debug">' . ta('ajouter_manque_parent') . '</div>';
            return;
        }

        $parent = $_Gconfig['relOneParent']['s_rubrique'][ $_REQUEST['relOne'] ];
        if (!is_int($parent)) {

            $sql = 'SELECT * FROM s_rubrique AS R, s_gabarit AS G WHERE R.fk_gabarit_id = G.gabarit_id AND G.gabarit_classe LIKE ' . sql($parent) . ' ' . sqlOnlyReal('s_rubrique');
            $gabarit = DoSql($sql);


            /**
             * On est dans une relOne
             * Alors on cherche à quel gabarit le racorder
             */
            if ($gabarit->NumRows() == 1) {
                /**
                 * On en a un seul ..
                 * Cool !
                 */
                $res = $gabarit->FetchRow();
                $parent = $_REQUEST['genform__add_sub_id'] = $res['rubrique_id'];
            } else if ($gabarit->NumRows() > 1) {
                /**
                 * On en a plusieurs ... ça va devenir compliqué
                 * Il va falloir choisir ...
                 */
                echo '<div class="genform_champ only-new" >'
                    . '         <div class="genform_label"><label for="genform__add_sub_id">' . ta('ajouter_' . $_REQUEST['relOne']) . '</label></div>';
                echo '<select class="select" id="genform__add_sub_id" name="genform__add_sub_id">';
                foreach ($gabarit as $row) {
                    echo '<option value="' . $row['rubrique_id'] . '">' . $row[ 'rubrique_titre_' . LG ] . '</option>';
                }
                echo '</select> ';
                echo '<button class="btn primary"><i class="icon-ok" ></i> ' . t('continuer') . '</button></div>'
                    . '<script>$("#genform_fromForm").remove();</script>';
                $this->fieldsDone++;
                return;
            } else {
                echo '<div class="alert alert-debug">' . ta('ajouter_manque_gabarit') . '</div>';
                $this->fieldsDone++;
                return;
            }
        }
    }

    /**
     * On associe automatiquement un gabarit pour les relOne
     */
    if (!empty($_REQUEST['relOne'])) {
        $type = $_Gconfig['relOneGabarit']['s_rubrique'][ $_REQUEST['relOne'] ];
        if ($type) {
            $gabarit = getGabaritByClass($type);
            if ($gabarit) {
                $form->tab_default_field['fk_gabarit_id'] = $gabarit['gabarit_id'];
                echo '<div style="display:none">';
                $form->gen('fk_gabarit_id');
                echo '</div>';
            }
        }
    }


    /**
     * Aucun parent ... on ne peut pas créer la rubrique
     */
    if (empty($_REQUEST['genform__add_sub_id'])) {
        echo '<div class="alert alert-debug">' . ta('ajouter_manque_parent') . '</div>';
        $this->fieldsDone++;
        return;
    }
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
            $notUrl[ $lg ][ $row[ 'rubrique_url_' . $lg ] ] = true;
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
    <input type="hidden" name="genform__add_sub_id" value=<?= alt($_REQUEST['genform__add_sub_id']) ?>/>
    <input type="hidden" name="genform__add_sub_table" value=<?= alt($this->table) ?>/>
    <script>

        <?php
        p('var LGs = ' . json_encode($_Gconfig['LANGUAGES']) . ';');
        p('var notUrl = ' . json_encode($notUrl) . ';');
        p('mfields = ' . json_encode($mfields) . ';');
        ?>

        /**
         * On supprime les champs inutiles lors de la creation
         */
        $(document).ready(function () {

        });

    </script>

    <?php
    reset($_Gconfig['LANGUAGES']);
    foreach ($_Gconfig['LANGUAGES'] as $lg) {
        $_SESSION[ gfuid() ]['curFields'][] = ('rubrique_url_' . $lg);

        global $_Gconfig;
        if ($_Gconfig['URL_MANAGER'] == "genUrlV3") {

            //$supRub = getUrlFromId($_REQUEST['genform__add_sub_id'], $lg);
            $u = new genUrlV3('', false);
            $supRub = $u->buildUrlFromId($_REQUEST['genform__add_sub_id'], $lg);

            if ($_Gconfig['onlyOneLgForever']) {
                if (BU && BU != "") {
                    $parents_url = str_replace(BU . '/', '', $supRub);
                } else {
                    $parents_url = $supRub;
                }
            } else {
                if (BU && BU != "") {
                    $parents_url = str_replace(BU . '/' . $lg, '', $supRub);
                } else {
                    $parents_url = $supRub;
                }
            }

            $parents_url = trim($parents_url, '/') . "/";
            if ($parents_url == "/") {
                $parents_url = "";
            }

            $form->gen('rubrique_titre_' . $lg, '', '', '');
            ?>
            <script>
                $('#genform_rubrique_titre_<?=$lg?>').on('keyup change', function () {
                    //updateChampUrl('mygen_rubrique_url_<?=$lg?>', this.value, <?=alt($parents_url)?>);
                });
            </script>
            <label
                for="mygen_rubrique_url_<?= $lg ?>"><?= t('url_' . $lg . '_will_be') ?></label><span style="font-family:verdana;padding:5px;display:block;background:#eee;border:1px solid #999">
                <?php
            echo str_replace($parents_url, '', $supRub);
            ?>
                <input type="text" style="border:0;font-family:verdana;" value="<?php echo $parents_url ?>"
                       name="genform_rubrique_url_<?= $lg ?>" id="mygen_rubrique_url_<?= $lg ?>"
                       onchange="checkFields()"/>
        <?php
        } elseif ($_Gconfig['URL_MANAGER'] == "genUrlV4") {

            $u = new genUrlV4('', false);
            $supRub = $u->buildUrlFromId($_REQUEST['genform__add_sub_id'], $lg);

            echo $supRub . '<br/>';
            if (BU && BU != "") {
                $parents_url = str_replace(BU . '/', '', $supRub);
            } else {
                $parents_url = $supRub;
            }

            $parents_url = trim($parents_url, '/') . "/";
            if ($parents_url == "/") {
                $parents_url = "";
            }

            $form->gen('rubrique_titre_' . $lg, '', '', 'onkeyup="updateChampUrl(\'mygen_rubrique_url_' . $lg . '\',this.value,\'' . $parents_url . '\')"');
            ?>
            <label
                for="mygen_rubrique_url_<?= $lg ?>"><?= t('url_' . $lg . '_will_be') ?></label><span style="font-family:verdana;padding:5px;display:block;background:#eee;border:1px solid #999">
                    <?php
            echo str_replace($parents_url, '', $supRub);
            ?>
                    <input type="text" style="border:0;font-family:verdana;" value="<?php echo $parents_url ?>"
                           name="genform_rubrique_url_<?= $lg ?>" id="mygen_rubrique_url_<?= $lg ?>"
                           onchange="checkFields()"/>
        <?php
        } else {
            $form->gen('rubrique_titre_' . $lg, '', '', '');
            ?>
            <script>
                $('[id=genform_rubrique_titre_<?=$lg?>]').on('keyup change', function () {
                    updateChampUrl('mygen_rubrique_url_<?=$lg?>', this.value, <?=alt($parents_url)?>);
                });
            </script>
            <label for="mygen_rubrique_url_<?= $lg ?>"><?= t('url_' . $lg . '_will_be') ?></label><span class="new_url">
            <?php
            echo getUrlFromId($_REQUEST['genform__add_sub_id'], $lg);
            ?><b id="smygen_rubrique_url_<?= $lg ?>"></b>
            <input type="hidden" value="" name="genform_rubrique_url_<?= $lg ?>" id="mygen_rubrique_url_<?= $lg ?>"
                   onchange="checkFields()"/>
        <?php
        }
        ?>
        </span>
        <hr/>
    <?php
    }
}
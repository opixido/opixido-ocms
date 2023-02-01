<?php


if ($_REQUEST['curId'] != "new") {

    if (rubriqueIsAPage($form) || true) {


        global $restrictedMode;
        $restrictedMode = true;
        if ($form->tab_default_field['rubrique_type'] != RTYPE_MENUROOT) {
            $form->gen("fk_gabarit_id"); 
        }
        $restrictedMode = false;


        $rPlugin = array();

        /**
         * Recherche des parametres des plugins
         */
        $plugs = GetPlugins();
        foreach ($plugs as $v) {
            if (class_exists($v . 'Admin') && method_exists($v . 'Admin', 'ocms_getParams')) {
                $className = $v . 'Admin';
                $res = call_user_func(array($className, 'ocms_getParams'), $this->tab_default_field);

                $rPlugin = array_merge($rPlugin, $res);
            }
        }

        /**
         * Si on a un gabarit particulier
         */
        if ($form->tab_default_field['fk_gabarit_id'] || count($rPlugin)) {

            /**
             * Quel gabarit
             */
            $gab = getGabarit($form->tab_default_field['fk_gabarit_id']);
            $gabNom = akev($gab, 'gabarit_classe');
            $gabFold = !empty($gab['gabarit_plugin']) ? PLUGINS_FOLDER . '/' . $gab['gabarit_plugin'] : 'bdd';

            /**
             * On l'inclu
             */
            $GLOBALS['gb_obj']->includeFile($gabNom . '.php', $gabFold);

            /**
             * Si il a une methode pour connaitre ses paramètres
             */
            if ($gabNom && method_exists($gabNom, 'ocms_getParams')) {
                $r = call_user_func(array($gabNom, 'ocms_getParams'), $this->tab_default_field);
                $r = array_merge($r, $rPlugin);
            } else {
                $r = $rPlugin;
            }


            if (!$this->editMode) {
                if (method_exists($gabNom, 'ocms_getSubRubs')) {
                    p('
				<script type="text/javascript">
				$(document).ready(function() {
					a = $("#genform_rubrique_option option[value=\'dynSubRubs\']").attr("selected", "selected");
				});
				</script>
				');
                }

                echo '<div style="display:inline;" class="genform_txt">' . t($gabNom . '_params') . getEditTrad($gabNom . '_params') . '</div>
			<div class="genform_champ">';
                $sf = new simpleForm();

                $defVals = SplitParams($form->tab_default_field['rubrique_gabarit_param'], ";", "=");

                $defV = $defVals;


                foreach ($r as $nom => $type) {

                    echo $sf->getLabel(array('label' => t($nom)));
                    echo getEditTrad($nom);

                    if (is_array($type)) {
                        $vals = akev($type, 1);
                        $type = akev($type, 0);
                    }


                    if ($type == 'selectm') {
                        echo $sf->getSelect(array('id' => $nom, 'value' => $vals, 'selected' => $defV[$nom]), true);
                    } else
                    if ($type == 'select') {

                        echo $sf->getSelect(array('id' => $nom, 'value' => $vals, 'selected' => akev($defV, $nom)));
                    } else {
                        echo $sf->getInputText(array('id' => $nom, "value" => akev($defV, $nom)));
                    }

                    echo '<br/>';
                }

                echo '</div><br/>';

                $GLOBALS['nomsTech'] = $noms = implode('","', array_keys($r));
                ?>
                <script type="text/javascript">

                    window.FieldsToTech = Array("<?= $GLOBALS['nomsTech'] ?>");

                    function updateFieldsToTech() {
                        texte = '';

                        for (p in window.FieldsToTech) {
                            ob = gid(window.FieldsToTech[p]);
                            val = ob.value;
                            if (ob.multiple) {
                                val = "";
                                for (var i = 0; i < ob.options.length; i++) {
                                    if (ob.options[ i ].selected && ob.options[ i ].value != "") {
                                        val += (ob.options[ i ].value) + ",";
                                    }

                                }
                                val = val.substring(0, val.length - 1);
                            }
                            if (val) {
                                texte += window.FieldsToTech[p] + "=" + escape(val) + ";";
                            }
                        }

                        gid("genform_rubrique_gabarit_param").value = texte;

                    }

                    for (p in window.FieldsToTech) {
                        o = gid(window.FieldsToTech[p]);
                        if (o) {
                            o.onchange = updateFieldsToTech;
                        }
                    }

                </script>

                <?php
            }




            $form->gen("rubrique_gabarit_param");


            $form->gen("rubrique_option");
        }
    }

    if ($form->tab_default_field['rubrique_type'] == RTYPE_SITEROOT) {
        $form->gen("rubrique_template");
    }


    $form->gen("rubrique_type");
    if ($form->tab_default_field['rubrique_type'] == 'link')
        $form->genlg("rubrique_link");
}

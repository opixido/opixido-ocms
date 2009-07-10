<?php

$form->gen("reaction_date");

echo '<br/><label class="genform_txt">'.t('t_reaction_sur').'</label>';
$r = getRowFromId($form->tab_default_field['fk_obj'],$form->tab_default_field['fk_id']);
echo '<div class="genform_champ">
	<a href="?curTable='.$form->tab_default_field['fk_obj'].'&curId='.$form->tab_default_field['fk_id'].'&genform_action[view]=1">
	'.GetTitleFromRow($form->tab_default_field['fk_obj'],$r, ' - ').'</a></div><br/>';

$form->gen("reaction_nom");
$form->gen("reaction_email");
$form->gen("reaction_comment");



<?php

$form->gen("message_date");
$form->gen("fk_utilisateur_id");

echo '<div class="genform_champ">
<a href="?curTable=e_utilisateur&curId='.$form->tab_default_field['fk_utilisateur_id'].'&genform_action[view]=1">&raquo; '.t('forum_view_user').'</a>
</div><br/>';

$form->gen("fk_rubrique_id");
//$form->gen("fk_message_id");
$form->gen("fk_root_id");
$form->gen("message_titre");
$form->gen("message_texte");
$form->gen("message_ip");
$form->gen("message_type");
$form->gen("message_clos");


//$form->gen("message_pj");

?>
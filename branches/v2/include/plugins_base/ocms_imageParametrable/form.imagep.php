<?php

if($_REQUEST['curId'] == 'new') {
	$form->gen('imagep_label');
} else {

	echo '<div class="genform_champ">ID : <strong>'.$form->tab_default_field['imagep_label'].'</strong></div><br/>';
	
}
$form->genlg('imagep_img');
$form->genlg('imagep_alt');

?>
<?php


$form->gen('admin_nom');

$form->gen('admin_login');
$form->gen('admin_pwd');


$form->gen('admin_email');
//$form->gen('admin_type');
$form->gen('s_admin_role');

if(!$form->editMode) {
	p('<input type="submit" class="button" value="'.t('actualiser_les_droits').'" name="stay_on_form" />');
}

//$form->gen('admin_last_cx');

//include('./form.admin2.php');

?>
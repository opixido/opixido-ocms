<?php


$form->gen('utilisateur_login');
$form->gen('utilisateur_email');
$form->gen('utilisateur_pwd');



if($form->editMode) {
	echo laGetUserResume($_REQUEST['curTable'],$_REQUEST['curId']);
} else {
	echo laGetUserForm($_REQUEST['curTable'],$_REQUEST['curId']);
}

$form->gen('utilisateur_valide');

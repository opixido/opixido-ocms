<?php


$row = getGabaritByClass('genContact');
/**
 * On affiche les infos de contact uniquement si on est sur un 
 * gabarit de contact
 */
if($form->tab_default_field['fk_gabarit_id'] == $row['gabarit_id']) {
	$form->gen('fk_contact_id');
	$form->gen('fk_contact_field_id');
}

?>
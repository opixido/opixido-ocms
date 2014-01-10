<?php

$gab = getGabarit($form->tab_default_field['fk_gabarit_id']);
if($gab['gabarit_classe'] == 'genRss') {

	$form->gen('FLUX_RSS');

}
?>
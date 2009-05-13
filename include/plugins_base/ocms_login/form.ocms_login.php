<?php


if($form->editMode) {
	if(ake($form->tab_field,'privee')) {
		echo laGetResume($_REQUEST['curTable'],$_REQUEST['curId']);
	}
} else {

	

	if(ake($form->tab_field,'privee')) {
		$form->gen('privee');
		$form->fieldsDone = 2;
	}
		
	if($form->tab_default_field['privee']) {		
		
		echo laGetForm($_REQUEST['curTable'],$_REQUEST['curId']);
		
	}
	
	
	
	
	
}

?>
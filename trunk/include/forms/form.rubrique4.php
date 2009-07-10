<?php

if($_REQUEST['curId'] != "new")  {

	    if($form->tab_default_field['rubrique_type'] == 'link') {
        
	       // $form->genlg("rubrique_link");
	
	     //   $form->gen("rubrique_link_en");     
	        
        } else if($form->tab_default_field['rubrique_type'] == 'page' ||  $form->tab_default_field['rubrique_type'] == "siteroot") {
        	
			p('<h1>'.t('meta_informations').'</h1>');
		
	        p('<p>'.t('desc_meta').'</p>');
	
	        $form->genlg("rubrique_keywords");
	
	        $form->genlg("rubrique_desc");
	
	   /*     $form->gen("rubrique_keywords_en");
	
	        $form->gen("rubrique_desc_en");*/
	
	        
	
	    //    $form->gen("rubrique_url_en");
        
        } 
		
		if($form->tab_default_field['rubrique_type'] != 'link') {
		
			$form->genlg("rubrique_url");
		
		}
		
		
        
       

}

?>
<?php

if ( !$this->editMode ) {

	/**
	 * VARCHAR DE TYPE COLOR
	 * 
	 */
	 
	if ( is_array($_Gconfig['colorFields']) && arrayInWord( $_Gconfig['colorFields'], $name ) ) {
		
		$this->genHelpImage('help_texte',$name);
    	
		$fl = $this->tab_field[$name]->max_length;
	
    	
    	
    	if($this->tab_default_field[$name]) {
    		$style = 'style="background-color:#'.$this->tab_default_field[$name].'"';
    	}
		
    	$this->addBuffer('<span class="colorField" id="colorField_'.$name.'" '.$style.'>
    						&nbsp;   &nbsp;   						
    					 </span> ');
    	
    	$this->addBuffer(' &nbsp;<input 
									size="6" 
									onchange="gid(\'colorField_'.$name.'\').style.backgroundColor=\'#\'+this.value;" 
									id="genform_'.$name.'" ' . $jsColor . ' '.$attributs.'  
									name="genform_' . $name . '" maxlength="'.$fl.'" 
									class="genform_varchar" 
									value=' . alt( $this->tab_default_field[$name] ) . ' />');
    
    	$this->addBuffer(' <a class="btn_spectre" 
								href="#" 
								onclick="popup(\'./colorPicker/colorSelector.html?id='.$name.'\',360,240);return false;"
							>
    					 	<img src="./colorPicker/spectre.jpg" alt="" style="vertical-align:middle"  />
    					 </a>');
    	
		
	}
	else
	if ( arrayInWord( $_Gconfig['urlFields'], $name ) ) {
		
	
	    	
	    	$this->genHelpImage('help_url',$name);
	    	
	    	
	    	if(!strlen(trim($this->tab_default_field[$name]))) {
	    		$this->tab_default_field[$name] = DEFAULT_URL_VALUE;
	    	}
	    	
		    $this->addBuffer( '
		    <input 
		    		id="genform_'.$name.'" ' . $jsColor . ' 
		    		type="text" '.$attributs.' 
		    		name="genform_' . $name . '" 
		    		size="80" 
		    		maxlength="' . $this->tab_field[$name]->max_length . '" 
		    		value=' . alt($this->tab_default_field[$name]) . ' /> ' );
		    
		
		        
		   
		    
		    $this->addBuffer('
		    <img src="'.ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_ARBO_SIZE.'/categories/applications-internet.png" 
		    	onclick="smallPopup(gid(\'genform_'.$name.'\').value)" 
		    	alt="'.t('tester_le_lien').'" />');
		    
				$this->addBuffer('
		    <img src="'.ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_ARBO_SIZE.'/status/folder-open.png" 
		    	onclick="XHR_links(\'genform_'.$name.'\')" 
		    	alt="'.t('choisir_rubrique').'" />');       
	
				  
	        $this->addBuffer('<div id="genform_'.$name.'_links" class="xhr_links"></div>');
	        //$this->addBuffer('<iframe id="genform_'.$name.'_test" src="" ></iframe>');

	/**
	 * VARCHAR DE TYPE MAIL
	 * 
	 */
    } else if ( arrayInWord( $mailFields, $name ) ) {
    	
    	$this->genHelpImage('help_email',$name);

        $this->addBuffer( '<input 
							id="genform_'.$name.'"  ' . $jsColor . ' '.$attributs.' 
							type="text" 
							name="genform_' . $name . '" 
							size="60"  
							value=' . alt($this->tab_default_field[$name]) . '
							/>  ' );

        
        
     /**
      * Varchar de type Mot de passe
      */
    } else if(in_array($name,$_Gconfig['passwordFields']) ){
    	
		/* Mot de passe avec génération auto */
		$this->addBuffer( '<input 
							id="genform_'.$name.'" ' . $jsColor . ' 
							type="text" '.$attributs.' 
							name="genform_' . $name . '" 
							size="12" 
							maxlength="' . $this->tab_field[$name]->max_length . '" 
							value=' . alt($this->tab_default_field[$name]) . ' />' );

		if(!$this->editMode) {



//<label  style="float:none;width:200px;" for="generatepassword_'.$name.'" class="abutton"><input src="'.t('src_random_password').'" class="inputimage" type="image" id="generatepassword_'.$name.'"  />'.t('generate_random_password').'</label>');
		$this->addBuffer( '		
		
			<a href="#" 
				class="titreListe" 
				style="clear:both;" 
				id="generatepassword_'.$name.'" >
				<img src="'.t('src_random_password').'" 
					class="inputimage" 
					type="image"  
					/>'.t('generate_random_password').'</a>');
		
			$this->addBuffer( '
				<script type="text/javascript">
					$("#generatepassword_'.$name.'").click(
					function() {
						$("#genform_'.$name.'").val(generatepass(8));
						return false;
					});
				</script>
			');
		}
		
	


	/**
	 * VARCHAR NORMAL
	 */
    } else {
    	
    	$this->genHelpImage('help_texte',$name);
    	
		$fl = $this->tab_field[$name]->max_length;
		
    	if( $fl >= 100) {
			$this->addBuffer( '<textarea 
							class="resizable" 
							id="genform_'.$name.'" ' . $jsColor . ' '.$attributs.'  
							name="genform_' . $name . '" 
							rows="2"  
							maxlength="'.$fl.'" 
							class="genform_varchar" >' . ( $this->tab_default_field[$name] ) . '</textarea>' );
    	} else  {
        	$this->addBuffer('<input
								size="'.$fl.'" 
								id="genform_'.$name.'" 
								' . $jsColor . ' 
								'.$attributs.'  
								name="genform_' . $name . '" 
								maxlength="'.$fl.'" 
								class="genform_varchar" 
								value=' . alt( $this->tab_default_field[$name] ) . '"
								/>');
								
   			$this->addBuffer($this->genInsertButtons('genform_'.$name.''));
    	}
    	
    	//style="width:'.$this->larg.'px" 
    }
} else {
    /* URL */
    if (arrayInWord( $_Gconfig['urlFields'], $name ) ) {
        if ( $this->tab_default_field[$name] ) {
            	$vall = strlen($this->tab_default_field[$name]) > 50 ? 
            			substr($this->tab_default_field[$name],0,50).'...' : 
            			$this->tab_default_field[$name];
            	
            	$this->addBuffer( '<a href="' . $this->tab_default_field[$name] . '" target="_blank">'. ( $vall ) . '</a>' );

            }
    
    /* MAIL */        
    } else if ( arrayInWord( $mailFields, $name ) ) {
        if ( $this->tab_default_field[$name] )
            $this->addBuffer( '<a href="mailto:' . $this->tab_default_field[$name] . '" target="_blank">' . 
            					( $this->tab_default_field[$name] ) . '</a>' );
    } else {
	
        $this->addBuffer( ( $this->tab_default_field[$name] ) );
    }

}

?>
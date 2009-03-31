<?php

if ( !$this->editMode ) {

	/**
	 * VARCHAR DE TYPE URL
	 * 
	 */
	 
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
		    		value="' . $this->tab_default_field[$name] . '" /> ' );
		    
		
		        
		   
		    
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

        $this->addBuffer( '<input id="genform_'.$name.'"  ' . $jsColor . ' '.$attributs.' type="text" name="genform_' . $name . '" size="60"  value="' . $this->tab_default_field[$name] . '" />  ' );

        
        
     /**
      * Varchar de type Mot de passe
      */
    } else if(in_array($name,$_Gconfig['passwordFields']) ){
    	
		/* Mot de passe avec génération auto */
		$this->addBuffer( '<input id="genform_'.$name.'" ' . $jsColor . ' type="text" '.$attributs.' name="genform_' . $name . '" size="12" maxlength="' . $this->tab_field[$name]->max_length . '" value="' . $this->tab_default_field[$name] . '" />' );

		if(!$this->editMode) {


		$this->addBuffer( '
			<script>

			var keylist="abcdefghijklmnopqrstuvwxyz123456789"
			var temp="";

			function generatepass(plength){
			temp="";
			for (i=0;i<plength;i++)
			temp+=keylist.charAt(Math.floor(Math.random()*keylist.length))
			return temp
			}

			function populateform(enterlength){
				gid("genform_admin_pwd").value=generatepass(enterlength);
			}

			</script>
		');

		$this->addBuffer( '
			<label  style="float:none;width:200px;" for="generatepassword" class="abutton"><input src="'.t('src_random_password').'" class="inputimage" type="image" id="generatepassword" onclick="populateform(8);return false;" />'.t('generate_random_password').'</label>');

		}


	/**
	 * VARCHAR NORMAL
	 */
    } else {
    	
    	$this->genHelpImage('help_texte',$name);
    	
		$fl = $this->tab_field[$name]->max_length;
		
    	if( $fl >= 100) {
			$this->addBuffer( '<textarea id="genform_'.$name.'" ' . $jsColor . ' '.$attributs.'  name="genform_' . $name . '" rows="2"  maxlength="'.$fl.'" class="genform_varchar" >' . ( $this->tab_default_field[$name] ) . '</textarea>' );
    	} else  {
        	$this->addBuffer('<input size="'.$fl.'" id="genform_'.$name.'" ' . $jsColor . ' '.$attributs.'  name="genform_' . $name . '" maxlength="'.$fl.'" class="genform_varchar" value="' . ( $this->tab_default_field[$name] ) . '" />');
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
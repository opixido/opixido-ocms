<?php


/**
 * Champ type Text
 */

if ( @in_array( $name, $rteFields ) || in_array(getBaseLgField($name),$rteFields) ) {
	
	/**
	 * Champs WYSIWYG
	 */

	if ( !$this->editMode ) {	
		
		/**
		 * Mode edition
		 */	

		$this->genHelpImage('help_rte',$name);

		 	
 		if($_REQUEST['gfa'] || true) {			 		
 			
 			/**
 			 * Dans une popup ou iframe 
 			 * on affiche tout
 			 */
				 	
	 		$r = new genRteInline('genform_'.$name,$this->tab_default_field[$name]);
			$this->addBuffer($r->createRte());
				 		
	 	} else {
	 		
	 		/**
	 		 * Sinon juste un résumé et un lien d'édition
	 		 */
	 		
            $this->addBuffer( ' &nbsp; <a title="'.$this->trad('editer_wysiwyg').'" class="linkimage" href="?table=' . $this->table_name . '&champ=' . $name . '&pk=' . $this->primary_key . '&id=' . $this->id . '&popup=true&doRte=true"  ' );
			$this->addBuffer( ' onclick="window.open(this.href,\'rte\', \'width=714, height=600\');return false;" ' );
            $this->addBuffer( '>'.t( 'editer' ) . '</a> &nbsp; &nbsp;' );

			$ctxt = trim(strip_tags($this->tab_default_field[$name]));
			$this->addbuffer('<div class="extrait" id="prevrte_'.$name.'" >'.substr($ctxt,0,200));
			
			if(strlen($ctxt) > 200) {
				$this->addBuffer(' ...');
			} else if(strlen($ctxt) <1) {
				$this->addBuffer(t('champ_vide'));
			}
			$this->addBuffer('</div>');
			
			
			if($_Gconfig['uploadCsvInRte']){
				$this->addBuffer('<div id="genform_'.$name.'_upload_table">');
				$this->addBuffer( ' &nbsp; <a title="'.$this->trad('upload_tableau').'" class="abutton" style="float:none;width:200px;height:19px;padding-top:5px;" href="?table=' . $this->table_name . '&champ=' . $name . '&pk=' . $this->primary_key . '&id=' . $this->id . '&popup=true&formCsv=true"  ' );
				$this->addBuffer( ' onclick="window.open(this.href,\'rte\', \'width=900, height=600\');return false;" ' );
				$this->addBuffer( '><img class="inputimage" style="float:left;" src="'.t('src_upload').'" alt="'.t('help_upload_table').'"> '.t( 'upload_table' ) . '</a></div>' );
			}
	 	}            

                                      
                   
    } else {
    	
    	/**
    	 * Visualisation seulement
    	 */
    	
        $this->addBuffer( limitWords(stripslashes( strip_tags($this->tab_default_field[$name] )),10) );
    }
    
} else {

	/**
	 * Champ texte NON wysiwyg
	 */
	if ( !$this->editMode ) {
		
		/**
		 * Mode edition
		 */
		
		$this->genHelpImage('help_texte',$name);
		

		if(in_array($name,$_Gconfig['codeFields'])) {
			
			/**
			 * Champ de code
			 */
			if(!$GLOBALS['codeFieldPrinted']) {
				$this->addBuffer('<script language="javascript" type="text/javascript" src="edit_area/edit_area_full.js"></script>');
			}
			$this->addBuffer( '<textarea wrap="virtual"  class="genform_codefieldv2"  '.$attributs.' id="genform_'.$name.'" name="genform_' . $name . '">' . str_replace('&amp;','&amp;amp;',$this->tab_default_field[$name]) . '</textarea>' );
			$this->addBuffer( '
			<script type="text/javascript">
						editAreaLoader.init({
							id : "genform_'.$name.'"		// textarea id
							,syntax: "php"			// syntax to be uses for highgliting
							,start_highlight: true		// to display with highlight mode on start-up
							,min_height :400
							,min_width :580
						});
			</script>
			' );
			
		} else {
			
			/**
			 * Textarea normal
			 */
						
			$this->addBuffer( '<textarea style="height:20px" ' . $jsColor . ' class="resizable" rows="5" '.$attributs.' id="genform_'.$name.'" name="genform_' . $name . '">' . $this->tab_default_field[$name] . '</textarea>' );
			
		}
		
		/**
		 * Boutons d'insertion automatique
		 */
		$this->addBuffer($this->genInsertButtons('genform_'.$name.''));
	
	} else if ( $this->tab_default_field[$name] ) {
		
		/**
		 * Mode visualisation
		 */
		
		if(in_array($name,$_Gconfig['codeFields'])) {
			
			/**
			 * Champ de code
			 */
			$this->addBuffer('<div class="genform_codefield" style="color:#555;overflow:hidden">'.nl2br( htmlentities( $this->tab_default_field[$name] )) .'</div>');
			
		} else {
			
			/**
			 * Champ texte
			 */
			
	    	$this->addBuffer( nl2br( $this->tab_default_field[$name] ) );
	    	
		}
	}
	
}

?>
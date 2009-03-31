<?php


/********
	*  CLEF EXTERNE
	*  La clef se trouve dans cette table
	*/
	



if ( !$this->editMode ) {
        /*
         *
         * On est en MODIFICATION
         *
         * */

				$this->genHelpImage('help_set',$name);
				
				
				$doReload = in_array($this->table.".".$name,$_Gconfig['reloadOnChange']);
				
				if($doReload) {
		   				$attributs .= ' onchange="saveAndReloadForm();" ';
				}

				//debug($this->tab_field[$name]);
                            /* Debut du select */
                            $this->addBuffer( '<select multiple '.$attributs.' ');

                            /* Si c'est une clef avec un champ preview, on rajoute un peu de javascript */

                            /* Fin du select */
                            $this->addBuffer(' id="genform_'.$name.'" name="genform_' . $name . '[]">' );

		
							
						$sets = getsetValues($this->table_name,$name);

						$curSet = explode(',',$this->tab_default_field[$name]);
						
						/*foreach($curSet as $k=>$v) {
							$curSet[$k] = substr($v,1,-1);
						}*/
						
                        foreach( $sets as $set ) {
                            /*
                             * On parcourt les resultats pour la liste de la table externe
                             * */

                            $thisValue = $this->trad('set_'.$set);



                            if ( in_array($set,$curSet)  )
                                $this->addBuffer( '<option selected="selected" value="' . $set . '">' . ( $thisValue ) . '</option>' );

                            else
                                $this->addBuffer( '<option  value="' . $set . '"> ' . ( $thisValue ) . '</option>' );
                        }

                        /* FIN DU SELECT */
                        $this->addBuffer( '</select>' );



                        /* On peut modifier cet element */



                    } else {
                        /*
                         * On est pas en modification, on affiche juste l'ï¿½ï¿½ent sï¿½ectionnï¿½
                         * */

                        if ( $this->tab_default_field[$tab_name] ) {
                            /* Uniquement si on a dï¿½ï¿½une valeur */


                            $this->addBuffer($this->trad('set_'.$this->tab_default_field[$tab_name]));

                        }
                    }
?>
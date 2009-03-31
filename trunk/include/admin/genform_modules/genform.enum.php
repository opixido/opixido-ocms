<?php


/********
                  *  CLEF EXTERNE
                  *  La clef se trouve dans cette table
                  */


                    /*
                     * On utilise le tableau RELATIONS
                     *
                     * */



                    if ( !$this->editMode ) {
                            /*
                             *
                             * On est en MODIFICATION
                             *
                             * */

				$this->genHelpImage('help_enum',$name);
				
				
				$doReload = in_array($this->table.".".$name,$_Gconfig['reloadOnChange']);
				
				if($doReload) {
		   				$attributs .= ' onchange="saveAndReloadForm();" ';
				}

				//debug($this->tab_field[$name]);
                            /* Debut du select */
                            $this->addBuffer( '<select  '.$attributs.' ');

                            /* Si c'est une clef avec un champ preview, on rajoute un peu de javascript */

                            /* Fin du select */
                            $this->addBuffer(' id="genform_'.$name.'" name="genform_' . $name . '">' );

		
							
							$enums = getEnumValues($this->table_name,$name);


                        foreach( $enums as $enum ) {
                            /*
                             * On parcourt les resultats pour la liste de la table externe
                             * */

                            $thisValue = $this->trad('enum_'.$enum);



                            if ( strcmp( $this->tab_default_field[$name], $enum ) == 0  )
                                $this->addBuffer( '<option selected="selected" value="' . $enum . '">' . ( $thisValue ) . '</option>' );

                            else
                                $this->addBuffer( '<option  value="' . $enum . '"> ' . ( $thisValue ) . '</option>' );
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


                            $this->addBuffer($this->trad('enum_'.$this->tab_default_field[$tab_name]));

                        }
                    }
?>
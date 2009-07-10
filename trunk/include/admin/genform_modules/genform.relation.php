<?php


/********
  *  CLEF EXTERNE
  *  La clef se trouve dans cette table
  */


    /*
     *
     * On utilise le tableau RELATIONS
     *
     * */

    $fk_table = $relations[$this->table_name][$name];
    
    if(!$fk_table) {
    	$fk_table = $relations[$this->table_name][getBaseLgField($name)];
    }
    $fk_champ = $tabForms[$fk_table]['titre'];


		$cantBeEmpty = in_array($this->table.".".$name,$_Gconfig['genform']['nonEmptyForeignKey']);
	
		$doReload = in_array($this->table.".".$name,$_Gconfig['reloadOnChange']);
	
		if($doReload) {
			//debug($name);
			$attributs .= ' onchange="saveAndReloadForm();" ';
		
		}
		
		
    $nomSql = GetTitleFromTable($fk_table," , ");
    $nomSee = GetTitleFromTable($fk_table," ");

    $clef = $this->GetPrimaryKey( $fk_table );

    if ( !$this->editMode ) {
            /*
             *
             * On est en MODIFICATION
             *
             * */

			$this->genHelpImage('help_relation',$name);
             /* On a le droit d'ajouter */
            if($this->gs->can('add',$fk_table) && !$restrictedMode) {
               	 $this->addBuffer( '<input type="image" src="'.t('src_new').'" class="inputimage"  name="genform_add_-_' . $fk_table . '_-_' . $name . '" title="' . $this->trad("ajouter").$this->trad($fk_table) . '" /><br/>' );

			}
            /*
             * On selectionne les enregsitrements de la table externe
             *
             */

             if(is_array($preValues) && count($preValues)) {
					$result = $preValues;
					
			}

			else {
				
				if($_Gconfig['specialListing'][$fk_table][$this->table_name]) {
					$result = $_Gconfig['specialListing'][$fk_table][$this->table_name]($this);
				} else {
					$sql = 'SELECT G.* FROM '.$fk_table.' AS G WHERE 1 ORDER BY G.' . $nomSql;
					$result = GetAll( $sql );
				}
			}


            /* Debut du select */
            $this->addBuffer( '<div class="ajaxselect"><select  onkeydown="smartOptionFinder(this,event)"  '.$attributs.' ');

            /* Si c'est une clef avec un champ preview, on rajoute un peu de javascript */
            if(strlen($previewField[$this->table_name][$name]))
                $this->addBuffer(' onchange="genformPreviewFk(\''.$fk_table.'\',\''.$name.'\',\''.$previewField[$this->table_name][$name].'\');"  ');

            /* Fin du select */
            $this->addBuffer(' id="genform_'.$name.'" name="genform_' . $name . '">' );

            /* Valeur vide */

            if(!$cantBeEmpty) {
            	$this->addBuffer( '<option value=""> </option>' );
			}

       		foreach( $result as $row ) {
                /*
                 * On parcourt les resultats pour la liste de la table externe
                 * */

                $thisValue = '';

                $thisValue = truncate(GetTitleFromRow($fk_table,$row," "),100);
				
                if ( strcmp( $this->tab_default_field[$name], $row[$clef] ) == 0  )
                    $this->addBuffer( '<option selected="selected" value="' . $row[$clef] . '">' . ( $thisValue ) . '</option>' );
                else
                    $this->addBuffer( '<option  value="' . $row[$clef] . '"> ' . ( $thisValue ) . '</option>' );
       		}

        /* FIN DU SELECT */
        $this->addBuffer( '</select>' );

		if(count($result) > 20) {
			$this->addBuffer('
			<script type="text/javascript">
				selectToSearch("genform_'.$name.'");
			</script>
			');
		}
        
        

        /* On peut modifier cet element */

        if($this->gs->can('edit',$fk_table) && !$restrictedMode) {

            $this->addBuffer( '<input
            type="image" src="'.t('src_editer').'" class="inputimage"
            name="genform_modfk__' . $fk_table . '__' . $name . '"
            title="' . $this->trad( "modifier" ) . '" onclick="if($(\'#genform_'.$name.'\').val() == \'\') { return false;}"  />

            ' );
            
            //if(gid(\'genform_'.$name.'\').options[gid(\'genform_'.$name.'\').selectedIndex] == \'\' ) { alert(\'Veuillez choisir un element a modifier\');return false;}"

        }

        

        if(strlen($previewField[$this->table_name][$name])) {
            /*
             * Si c'est un preview on rajoute le bouton et l'IFRAME correspondante
             */

             /* Image preview cliquable */
            $this->addBuffer('<input class="inputimage" id="genform_preview_'.$name.'_btn" src="'.t('src_preview').'" type="image" name="genform_preview" value="'.$this->trad('preview').'" onclick="genformPreviewFk(\''.$fk_table.'\',\''.$name.'\',\''.$previewField[$this->table_name][$name].'\');return false;" />');

            /* Iframe pour afficher le contenu */
            $this->addBuffer('<iframe
            width="'.$this->larg.'"
            height="250"
            frameborder="0"
            id="genform_preview_'.$name.'"
            src="about:blank"
            style="display:none;border:1px
            solid #aeaeae;"></iframe>');


        }
        $this->addBuffer('</div>');
        
    } else {
        /*
         * On est pas en modification, on affiche juste l'element selectionne
         * */

        if ( $this->tab_default_field[$tab_name] ) {
            /* Uniquement si on a dï¿½ï¿½une valeur */
            
			if(!$GLOBALS['cache'][$fk_table][$this->tab_default_field[$tab_name] ]) {
                $sql = 'SELECT '.$nomSql.' FROM '.$fk_table.' AS G WHERE ' . $clef . ' = "' . $this->tab_default_field[$tab_name] . '" ';
                //ORDER BY G.' . $nomSql;
                $result = GetSingle( $sql );
				
                $GLOBALS['cache'][$fk_table][$this->tab_default_field[$tab_name] ] = GetTitleFromRow($fk_table,$result);
			} 
			
			$this->addBuffer($GLOBALS['cache'][$fk_table][$this->tab_default_field[$tab_name] ]);
		

        }
    }
?>
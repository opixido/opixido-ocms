<?php

/* La clef se trouve dans l'autre table */

/*
* On utilise le tableau des relations inversees
*
* */
$fname = $name;
$fk_table = $relinv[$this->table_name][$name][0];
$name = $relinv[$this->table_name][$name][1];

if ( $_REQUEST['curId'] != 'new' ) {
    /**
     * L'enregistrement de la table actuel existe deja              
     **/
    if ( $thirdtable ) {

        $clefthird = getPrimaryKey( $tab_name );
        $sql = 'SELECT * FROM ' . $fk_table . ' AS T1, ' . $tab_name . ' AS T2 WHERE T1.' . $name . ' = "' . $this->id . '" AND T1.' . $thirdtable . ' = T2.' . $clefthird . ' ORDER BY ' . $tabForms[$tab_name]['titre'];
		
    } else {

        $sql = 'SELECT * FROM ' . $fk_table . ' WHERE ' . $name . ' = "' . $this->id . '" ORDER BY ';

        
        if(count($orderFields[$fk_table])) {
                $sql .= $orderFields[$fk_table][0]." ,  ";
        }

        //$sql .= $this->getNomForOrder( $tabForms[$fk_table]['titre'] );
        $sql .= GetTitleFromTable($fk_table,' , ');
        
// debug("--->".$fk_table );
    }
    $res = GetAll( $sql );

} else {

    $res = array();

}


$clef = getPrimaryKey( $fk_table );

if ( !$this->editMode ) {



if(count($res) <= 4000 ) {

	$this->genHelpImage('help_relinv_table',$fname);

    /*
     * SI on en a moins de 40 on affiche le tableau directement
     * */

    $this->addBuffer( '' );

			if(count($res) > 5) {
				$this->addBuffer('<div >'); //style="height:200px;overflow:auto;"
			}

	        $this->addBuffer('<table border="0" width="'.($this->larg-25).'" class="genform_table" >');
	        $ml = 1;
	        $this->addBuffer('<tr><th width="20">');
	
	        $this->addBuffer( '<input class="inputimage" type="image" src="'.t('src_new').'" title="' . $this->trad('ajouter').$this->trad($fk_table) . '" name="genform_addfk__' . $fk_table . '__'.$name.'" /> ' );


 			$this->addBuffer('</th>');
 			$this->addBuffer('<th width="20">&nbsp;</th>');

 			/**
 			 * Case TH vide pour chaque action supplémentaire
 			 */
	 			foreach ($_Gconfig['rowActions'][$fk_table] as $actionName => $v){
	 			
	 				$ga = new GenAction($actionName,$fk_table,$row[$clef],$row);
							
					if ($this->gs->can($actionName,$fk_table,$row,$row[$clef]) && $ga->checkCondition()) {
					//	debug($actionName);
	 					$this->addBuffer('<th width="20">&nbsp;</th>');
					}
	 			}
 			
                /* Collones pour les boutons up down */
                if(array_key_exists($fk_table,$orderFields)) {
                    $this->addBuffer('<th width="20" >&nbsp;</th><th width="20" >&nbsp;</th>');

                }


                reset($tabForms[$fk_table]['titre']);
                foreach($tabForms[$fk_table]['titre'] as $titre) {
                        $this->addBuffer('<th>'.$this->trad($titre).'</th>');
                }

                $this->addBuffer('</tr>');
                reset($tabForms[$fk_table]['titre']);

                $nbTotIt = count($res);
                $nbIt = 1;
                $ch = ' checked="checked" ' ;
                foreach( $res as $row ) {
                        	$ml = $row[$clef];
                            $this->addBuffer( '<tr>');
                            $ch = "";
                             reset($tabForms[$fk_table]['titre']);


                             /***********
                                On ajoute le bouton editer
                                *************/
                                $this->addBuffer( '<td>');
                                
                                $canedit = $this->gs->can('edit',$fk_table,$row,$row[$clef]);
					//debug("**".$fk_table.'-'.$row[$clef].'-'.$canedit);
                                
                    if($canedit) {


							$this->addBuffer( '<input
		
								type="image"
								src="'.t('src_editer').'"
								class="inputimage"
								name="genform_modfk__' . $fk_table . '"
								title="' . $this->trad( "edit" ) . '"
								onclick="document.getElementById(\'genform_modfk__' . $fk_table . '_value_'.$ml.'\').checked = \'checked\'" /><input '.$ch.'
								style="display:none;"
								name="genform_modfk__' . $fk_table . '_value"
								type="radio"
								id="genform_modfk__' . $fk_table . '_value_'.$ml.'"
								value="' . $row[$clef] . '" />
		
								' );



					}

					$this->addBuffer('</td>');
					  $this->addBuffer( '<td>');
					 
					if($this->gs->can('del',$fk_table,$row,$row[$clef])) {

						$this->addBuffer( '
							<input

							type="image"
							src="'.t('src_delete').'"
							class="inputimage"
							name="genform_delfk__' . $fk_table . '"
							title="' . $this->trad( "delete" ) . '"
							onclick="if(confirm(\''.altify($this->trad('confirm_suppr')).'\')) {document.getElementById(\'genform_delfk__' . $fk_table . '_value_'.$ml.'\').checked = \'checked\'} else { return false;
							}" /><input '.$ch.'
							style="display:none;"
							name="genform_delfk__' . $fk_table . '_value"
							type="radio"
							id="genform_delfk__' . $fk_table . '_value_'.$ml.'"
							value="' . $row[$clef] . '" />

						' );

					}

					$this->addBuffer('</td>');
					
					/**
					 * Actions supplémentaires
					 */
					
					foreach ($_Gconfig['rowActions'][$fk_table] as $actionName => $v){
						
						$ga = new GenAction($actionName,$fk_table,$row[$clef],$row);
						
						if ($this->gs->can($actionName,$fk_table,$row,$row[$clef]) && $ga->checkCondition()) {
							
							$this->addBuffer( '<td>');
							$this->addBuffer( '
							<input

							type="image"
							src="'.t('src_'.$actionName).'"
							class="inputimage"
							name="genform_relinvaction['.$actionName.'][' .$fk_table. ']"
							title="' . $this->trad( $actionName ) . '"
							value="'.$ml.'"
							
							
							 />

						' );
							// pour le faire en ajax
							// onclick="ajaxAction(\''.$actionName.'\',\''.$fk_table.'\',\''.$ml.'\',\'\');return false;"
							
							/*
							onclick="document.getElementById(\'genform_'.$actionName.'fk__'.$fk_table.'_value_'.$ml.'\').checked=\'checked\'
							<input '.$ch.'
							style="display:none;"
							name="genform_'.$actionName.'fk__' . $fk_table . '_value"
							type="radio"
							id="genform_'.$actionName.'fk__' . $fk_table . '_value_'.$ml.'"
							value="' . $row[$clef] . '" />
							*/
							$this->addBuffer('</td>');
						}
						
					}
					

                                  /*************
                                    On ajoute les boutons pour la gestion de l'ordre ?
                                    **************/
                                  if(array_key_exists($fk_table,$orderFields)) {

                                        $this->addBuffer( '<td>');

                                        if($nbIt > 1) {
                                            $this->addBuffer( '<input
                                            type="image"
                                            src="'.t('src_up').'"
                                            class="inputimage"
                                            onclick="gid(\'genform_upfk__' . $fk_table . '__'.$ml.'__'.$name.'\').checked = \'checked\'"  name="genform_stay"
                                            title="' . $this->trad( "getup" ) . '"/>');

                                            $this->addBuffer( '<input
                                            style="display:none;"
                                            name="genform_upfk"
                                            type="radio"
                                            id="genform_upfk__' . $fk_table . '__'.$ml.'__'.$name.'"
                                            value="' .$fk_table . '__'.$row[$clef] . '__'.$name.'" /> ');
                                        }
                                        else {
                                            //$this->addBuffer('<img src="pictos/up_off.gif" alt="up" />' );
                                        }
                                        $this->addBuffer( '</td>' );

                                        $this->addBuffer( '<td>');

                                        if($nbIt < $nbTotIt) {
                                        	
                                            $this->addBuffer( '<input type="image" 
                                            			src="'.t('src_down').'" 
                                            			class="inputimage" 
                                            			onclick="gid(\'genform_downfk__' . $fk_table . '__'.$ml.'__'.$name.'\').checked = \'checked\'" 
                                            			 name="genform_stay"  title="' . $this->trad( "getdown" ) . '"/>');

                                            $this->addBuffer( '<input  style="display:none;" 
                                             name="genform_downfk" type="radio"
                                              id="genform_downfk__' . $fk_table . '__'.$ml.'__'.$name.'" value="' .$fk_table . '__'.$row[$clef] . '__'.$name.'" /> ');
                                         }else {
                                           // $this->addBuffer('<img src="pictos/down_off.gif" alt="up" />' );
                                        }
                                        $this->addBuffer( '</td>' );

                                  }



                                  $t = new GenForm($fk_table,"",$row[$clef],$row);
                                  $t->editMode = true;
                                  $t->onlyData = true;
                                 foreach($tabForms[$fk_table]['titre'] as $titre) {

                                        $this->addBuffer('<td>&nbsp;'.limitWords(($t->gen($titre)),30).'</td>');
                                 }
                                 $editMode=false;
                                 reset($tabForms[$fk_table]['titre']);

                                 $this->addBuffer('</tr>');
                                $ml++;
                                $nbIt++;
                        }
                        if(!count($res)) {
                        	$this->addBuffer('<tr><td colspan="10" style="text-align:center;">'.$this->trad('aucun_element').'</td></tr>');
                        }

                        $this->addBuffer('</table><br /> ');

			if(count($res) > 5) {
				$this->addBuffer('</div>');
			}


                        $this->addBuffer( '' );
                        //$this->addBuffer( '<br />&nbsp;<br />&nbsp;<br/>' );

                     } else {


						$this->genHelpImage('help_relinv_menu',$fname);
                        /*
                         * Si on a plus de 40 elements on affiche un menu deroulant
                         * */

                        $this->addBuffer( '<div id="genform_floatext">' );
                        if ( ( $fk_table != 't_page' ) || ( !count( $res ) && $fk_table == 't_page' ) )
                            $this->addBuffer( '<input type="submit"  class="input_btn"  value="' . $this->trad( "ajouter" ) . '" name="genform_addfk__' . $fk_table . '__'.$name.'" > ' );

                        if(count($res)) {

                                $this->addBuffer( 'ou <select name="genform_modfk__' . $fk_table . '_value" >' );
                                //if ( $this->table_name != "s_rubrique" && $fk_table != "t_page" )
                                $this->addBuffer( '<option value=""> ' . $this->trad( "modify_item" ) . '</option>' );
                                // while($row = mysql_fetch_array($res)) {

                                //$this->addBuffer( '<option value="' . $row[$clef] . '" > --> Liste <-- </option>' );
                                foreach( $res as $row ) {
                                $this->addBuffer( '<option value="' . $row[$clef] . '" > --> ' . str_replace( '"', '&quot;', $this->getNomForValue( $tabForms[$fk_table]['titre'], $row ) ) . '</option>' );
                                }

                                $this->addBuffer( '</select>' );
                                if ( !$restrictedMode ) {

                                if($this->gs->can('edit',$fk_table)) {
									$this->addBuffer( '<input type="image"  class="inputimage" src="'.t('src_editer').'" name="genform_modfk__' . $fk_table . '" value="' . $this->trad( "modifier" ) . '" onclick="if(document.forms[0].genform_modfk__' . $fk_table . '_value.selectedIndex < 1) return false;" />' );
                                }
                                }

                        }


                                $this->addBuffer( '</div>' );
                                $this->addBuffer( '<br />&nbsp;<br />&nbsp;<br/>' );

    		}
} else {
    $i = 0;
    // while($row = mysql_fetch_array($res)) {
    foreach( $res as $row ) {
        if ( $i > 0 )
            $this->addBuffer($this->separator);
        $this->addBuffer( str_replace( '"', '&quot;', GetTitleFromRow($fk_table,$row,' - ') ) );
        $i++;
    }
}

?>
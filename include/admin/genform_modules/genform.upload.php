<?php

				global $_Gconfig;
                /* Upload particulier */
                $gf = new GenFile($this->table_name,$name,$this->id,$this->tab_default_field[$name]);

				$chemin = $gf->GetWebUrl();
				$systemCh = $gf->GetSystemPath();



				
                if ( $this->tab_default_field[$name] ) {
                	
                	
                    if ( !$this->onlyData ) {
                        if($this->editMode) {
                            /*
                             *
                             * On est en visualisation  seulement
                             *
                                */
                            if($this->isImage($systemCh)) {

                                /*
                                 *
                                 * C'est une image , donc on affiche le thumbnail
                                 *
                                 */
                                $this->addBuffer( '<a href="' . $chemin . '" target="_blank">');

                                /*
                                 * AVEC THUMBS / GD ?
                                 */
                                if($this->useThumbs) {
                                    $this->addBuffer( '<img src="'.$gf->getThumbUrl($this->thumbWidth,$this->thumbHeight).'" />' );
                                } else {
                                    /*
                                     * Sans gd : resize via le navigateur
                                     */
                                    $this->addBuffer( '<img src="'.$chemin.'" width="'.$this->thumbWidth.'" />' );
								}
                                $this->addBuffer( '</a> ');
                            } else 
                            if( $gf->getExtension() == 'flv') {
                            	
                            	$this->addBuffer( ' <a href="' . $chemin . '" target="_blank">' . $this->trad( 'voir' ) . '</a> ' );
                            } else 
                            {

                                    /*
                                     *  Ce n'est pas une image donc on affiche le lien uniquement
                                     */
                                     $this->addBuffer( ' <a href="' . $chemin . '" target="_blank">' . $this->trad( 'voir' ) . '</a> ' );
                            }
                        } else {

                            /*
                             *
                             * MODIFICATION
                             *
                             */


							$this->genHelpImage('help_file',$name);

                             $this->addBuffer('<div class="genform_uploadfile">');
                             /* Nom du fichier */

                            $this->addBuffer( t('deja_fichier').' : ' );
                            $this->addBuffer( '<span style="font-weight:bold;"><img style="vertical-align:middle" src="'.$gf->getIcon().'" alt="'.strtoupper($gf->getExtension()).'"/> [ '.$gf->getNiceSize() .' ]</span>' );

                            /* Lien vers le fichier */
                            $this->addBuffer( ' <a href="' .$chemin . '" target="_blank" style="width:'.$this->thumWidth.'px">');

                            $this->addBuffer(str_replace($name."_","",basename($chemin)));


                            /*
                             * Si c'est une image , vignette + lien
                             */
                            if($this->isImage($chemin)) {



                                if($this->useThumbs)
                                    //$this->addBuffer( ' <br/><img src="thumb/?w='.$this->thumbWidth.'&amp;h='.$this->thumbHeight.'&amp;src='.$systemCh.'" alt="'.t($name).'" id="imgprev_'.$name.'" /><br/>');
                                    $this->addBuffer( ' <br/><img src="'.$gf->getThumbUrl($this->thumbWidth,$this->thumbHeight).'" alt="" id="imgprev_'.$name.'"  /><br/>' );
                                else
                                    $this->addBuffer( ' <br/><img src="'.$chemin.'" alt="'.t($name).'" width="'.$this->thumbWidth.'" />');

                            }
                            else if($gf->getExtension() == 'flv') {
                            	
                            	$this->addBuffer('<object type="application/x-shockwave-flash" data="flv.swf" width="300" height="200">
								    <param name="movie" value="flv.swf" />
								    <param name="allowFullScreen" value="true" />
								    <param name="FlashVars" value="flv='.$gf->getWebUrl().'&amp;margin=2&amp;bgcolor1=000000&amp;bgcolor2=000000&amp;showstop=1&amp;loadingcolor=555555&amp;showvolume=1&amp;showtime=1&amp;showfullscreen=1&amp;playercolor=ffffff&amp;buttoncolor=000000&amp;showiconplay=1&amp;iconplaybgcolor=ffffff&amp;videobgcolor=ffffff&amp;loadonstop=0" />
								</object>');
                            	
                            }
                            else {
                            /* Sinon juste lien */
                                $this->addBuffer( '<br/>'.$this->trad('voir'));
                            }

                            $this->addBuffer('</a>' );

							
                            /* Edition via l'ImageManager */
                            //$ssch = str_replace(BU,'',$gf->getWebUrl(),$count);
                            $ssch = substr($gf->getWebUrl(),strlen(BU));
                            //echo $ssch;
                           
                            // $ssch = $gf->getWebUrl();
                            if($this->isImage($chemin) && $this->useImageEditor) {
                                $this->addBuffer('<a href="ImageManager/editor.php?img='.$ssch.'&update=imgprev_'.$name.'" onclick="window.open(this.href);return false" >'.$this->trad('edis_image').'</a>' );
                            }
                            /* Pour supprimer le fichier !
                            $this->addBuffer( '<input type="checkbox" ');
                            $this->addBuffer( ' onclick="if(this.checked) {if(!confirm(\''.t('supprimer_fichier').'\')) this.checked = false; }" ');

                            $this->addBuffer(' name="genform_' . $name . '_del" id="genform_' . $name . '_del" value="" />');

                            $this->addBuffer(' <label for="genform_' . $name . '_del">' . $this->trad( 'supprimer' ) . '</label><br/><br/>' );

			*/

							$this->addBuffer('<label class="abutton"  style="float:none;width:120px;"
							><input onclick="return  confirm(\''.t('supprimer_fichier').'\')" class="inputimage" type="image" 
							value="" src="'.t('src_delete').'"  name="genform_' . $name . '_del" 
							 /> '.$this->trad('supprimer').'</label>' );

                             $this->addBuffer('</div>');
                             
                            $_SESSION[gfuid()]['curFields'][] = $name . "_del";
                            
                        }
                    } else {
                        /* Si only Data : on retourne juste l'url */

                       if($this->isImage($systemCh)) {

                                /*
                                 *
                                 * C'est une image , donc on affiche le thumbnail
                                 *
                                 */
                                $this->addBuffer( '<a href="' . $chemin . '" target="_blank">');

                                /*
                                 * AVEC THUMBS / GD ?
                                 */
                                if($this->useThumbs) {
                                    $this->addBuffer( '<img alt="" src="'.$gf->getThumbUrl($this->smallThumbWidth,$this->smallThumbHeight).'"/>' );
                                } else {
                                    /*
                                     * Sans gd : resize via le navigateur
                                     */
                                    $this->addBuffer( '<img src="'.$chemin.'" width="'.$this->thumbWidth.'" />' );
								}
                                $this->addBuffer( '</a> ');
                            } else {

                                    /*
                                     *  Ce n'est pas une image donc on affiche le lien uniquement
                                     */
                                     $this->addBuffer( ' <a href="' . $chemin . '" target="_blank">' . $this->trad( 'voir' ) . '</a> ' );
                            }
                    }
                }
                if ( !$this->editMode ) {

                    /* Bouton parcourir pour uploader le fichier */

                    $this->addBuffer("<label for='genform_" . $name . "'>");
                    if($this->tab_default_field[$name])
                        $this->addBuffer( t('fichier_remplacer'));
                    else
                        $this->addBuffer( t('fichier_uploader'));
                        
                       // debug($name .' ' .getBaseLgField($name));

                  	if(@ake($_Gconfig['imageAutoResize'],getBaseLgField($name))) {
						$sizes = $_Gconfig['imageAutoResize'][getBaseLgField($name)];
						$this->addBuffer(' <span class="light">['.t('max_size').' '.$sizes[0].' x '.$sizes[1].' px]</span>');
					} else
                  	if(@ake($_Gconfig['imageAutoResizeExact'],getBaseLgField($name))) {
						$sizes = $_Gconfig['imageAutoResizeExact'][getBaseLgField($name)];
						$this->addBuffer(' <span class="light">['.t('exact_size').' '.$sizes[0].' x '.$sizes[1].' px]</span>');
					}	
					
									
                    $this->addBuffer(' </label> <br/>');
                    $this->addBuffer('<input type="file" id="genform_' . $name . '" name="genform_' . $name . '"  /> 

                    <label class="abutton"  style="float:none;width:120px;">
                    <input class="inputimage" type="image" value="" src="'.t('src_upload').'"  name="genform_stay"  />
                     '.$this->trad('mettre_en_ligne').'</label>' );
                    
                    if(is_dir($_Gconfig['ftpUpload_path'])) {
                    	
                    	//debug(scandir($_Gconfig['ftpUpload_path']));
                    	$liste = $GLOBALS['gb_obj']->getFileListing($_Gconfig['ftpUpload_path'],false);
                    	
                    	
                    	if(count($liste)) {
                    		$ij=0;
                    		
                    		$_SESSION[gfuid()]['curFields'][] = $name . "_importftp";
							$_SESSION[gfuid()]['curFields'][] = $name . "_importftp_x";
							 
                    		$this->addBuffer(' 
				                    <img class="inputimage" onclick="showHide(\'filelisting_'.$name.'\')" type="image" src="'.t('src_importftp').'"  alt="'.t('import_from_ftp').'" />
				                                      
				                     <div class="filelisting" style="display:none;" id="filelisting_'.$name.'">');
                    		
                    		
                    			$this->addBuffer('<label for="genform_' . $name . '_import_'.$ij.'"  ><input type="radio" checked="checked" id="genform_' . $name . '_import_'.$ij.'" name="genform_' . $name . '_importftp" value="0" />'.t('aucun').'</label>');	
							foreach($liste as $f) {
								$ij++;
									$this->addBuffer('<label for="genform_' . $name . '_import_'.$ij.'"><input type="radio" id="genform_' . $name . '_import_'.$ij.'" name="genform_' . $name . '_importftp" value="'.$f.'" />'.$f.'</label>');		
							}
								
							

                    $this->addBuffer('
                    <label class="abutton"  style="float:none;width:70px;">
                    <input class="inputimage" type="image" value="" src="'.t('src_copy').'"  name="genform_stay"  />
                     '.$this->trad('copier').'</label>' );	
                    
                    						
							$this->addBuffer('</div>');
							
                    	}
                    	
                    	
                    	
                    	
                    }
                    
                    
                }

?>
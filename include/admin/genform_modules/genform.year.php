<?php



                    if($this->editMode)
                        $this->addBuffer(  $this->tab_default_field[$name] );
                    else
                        $this->addBuffer( '<input ' . $jsColor . ' type="text" name="genform_' . $name . '" size="4" maxlength="4" value="' . $this->tab_default_field[$name] . '" />' );


?>
<?php

 /**
                                 * TIME
                                /** ICI YA LE REAL ---
 
          if ( !$this->editMode )
                                    $this->addBuffer( "<input  " . $jsColor . " type='text' name='genform_" . $name . "' size='8' maxlength='$len' value='" . abs( $this->tab_default_field[$name] ) . "' />" );
                                else
                                    $this->addBuffer( $this->tab_default_field[$name] );

                           
                            } else if ( $this->tab_field[$name]->type == "time" ) {
                                */ 
    if ( !$this->editMode ) {
        if ( $this->tab_default_field[$name] == "00:00:00" || $this->tab_default_field[$name] == "" ) {
            // $this->tab_default_field[$name] = date("H:m:s");
        }
        $timeTab = split( ":", $this->tab_default_field[$name] );

        $sec = $timeTab[2];
        $min = $timeTab[1];
        $hour = $timeTab[0];
        $this->addBuffer( '<input ' . $jsColor . ' type="text" name="genform_' . $name . '_hour" size="2" class="genform_champ_centered" maxlength="2" value="'. $hour . '" />&nbsp;h&nbsp;' );
        $this->addBuffer( '<input ' . $jsColor . ' type="text" name="genform_' . $name . '_min" size="2" class="genform_champ_centered" maxlength="2" value="'. $min . '" />&nbsp;m&nbsp;' );
        $this->addBuffer( '<input ' . $jsColor . ' type="text" name="genform_' . $name . '_sec" size="2" class="genform_champ_centered" maxlength="2" value="'. $sec . '" />&nbsp;s&nbsp;' );

        $this->genHiddenItem( 'genform_' . $name, '');
    } else {
        $timeTab = explode( ":", $this->tab_default_field[$name] );
        $heure = "";

        if ( $timeTab[0] > 0 )
            $heure .= $timeTab[0] . "h";
        if ( $timeTab[1] > 0 )
            $heure .= $timeTab[1] . "m";
        if ( $timeTab[2] > 0 )
            $heure .= $timeTab[2] . "s";
        $this->addBuffer( $heure );
    }

?>
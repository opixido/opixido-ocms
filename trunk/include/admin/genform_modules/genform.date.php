<?php
	if ( ( $this->tab_default_field[$name] == '0000-00-00' || $this->tab_default_field[$name] == '' ) ) {
	/*if ( !$this->editMode )
		$this->tab_default_field[$name] = date( 'Y-m-d' );*/
	if($this->editMode) return '';
	}
	$dateTab = split( '-', $this->tab_default_field[$name] );
	$day = $dateTab[2];
	$month = $dateTab[1];
	$year = $dateTab[0];

	if ( !$this->editMode ) {
		/*
		$this->addBuffer( '<input type="text" ' . $jsColor . ' name="genform_' . $name . '_day" size="2" class="genform_champ_centered" maxlength="2" value="' . $day . '" />' );
		$this->addBuffer( '<input type="text" ' . $jsColor . ' name="genform_' . $name . '_month" size="2" class="genform_champ_centered" maxlength="2" value="' . $month . '" />' );
		$this->addBuffer( '<input type="text" ' . $jsColor . ' name="genform_' . $name . '_year" size="4" class="genform_champ_centered" maxlength="4" value="' . $year . '" />' );
	
		$this->genHiddenItem( 'genform_' . $name, "" );
	
		$this->addBuffer( '<img onmouseup="toggleDatePicker(\'genform_date_' . $name . '\',\'genform_formu.genform_' . $name . '\')" id="genform_date_' . $name . 'Pos" name="genform_date_' . $name . 'Pos" ' );
	
		$this->megaZ += 10;
		$this->addBuffer( '  src="'.t('src_calendrier').'" align="absmiddle"  border="0" alt="' . $this->trad( 'date_picker' ) . '"  />' );
		$this->addBuffer( '<div id="genform_date_' . $name . '" style="position:absolute;"></div>' );
	
		$this->dateFields[] = 'genform_date_'.$name;
		
		*/
		
		require_once ('jscalendar/calendar.php');
		$calendar = new DHTML_Calendar(ADMIN_URL.'/jscalendar/', LG, 'calendar-system', false);
		
		if(!$GLOBALS['calendarIncluded']) {
			$calendar->load_files();
			$GLOBALS['calendarIncluded'] = true;
		}
		
		ob_start();
		$calendar->make_input_field(
           // calendar options go here; see the documentation and/or calendar-setup.js
           array('firstDay'       => 1, // show Monday first
                 'showsTime'      => false,
                 'showOthers'     => false,
                 'ifFormat'       => '%Y-%m-%d',
                 'timeFormat'     => '24'),
           // field attributes go here
           array('style'       => 'font-family:courier, courier new, monospace;font-size:9px!important;width:85px',
                 'name'        => 'genform_'.$name,
                 'value'       => $this->tab_default_field[$name])); //strftime('%Y-%m-%d %I:%M %P', strtotime('now'))
                 
		$this->addBuffer(ob_get_clean());
		
	} else {
		$this->addBuffer( $day . '/' . $month . '/' . $year );
	}

?>

<?php
	if ( ( $this->tab_default_field[$name] == '0000-00-00' || $this->tab_default_field[$name] == '' ) ) {
	/*if ( !$this->editMode )
		$this->tab_default_field[$name] = date( 'Y-m-d' );*/
	if($this->editMode) return '';
	}

	$dateTime = split( ' ', $this->tab_default_field[$name] );
	$dateTab = split( '-', $dateTime[0] );
	$timeTab = split( ':', $dateTime[1] );
	//debug($dateTab);
	$day = $dateTab[2];
	$month = $dateTab[1];
	$year = $dateTab[0];
	$hh = $timeTab[0];
	$mm = $timeTab[1];
	$ss = $timeTab[2];


	if ( !$this->editMode ) {
		
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
                 'value'       => $dateTime[0])); //strftime('%Y-%m-%d %I:%M %P', strtotime('now'))
                 
		$this->addBuffer(ob_get_clean());

		/*
		$this->addBuffer( '<input type="text" ' . $jsColor . ' name="genform_' . $name . '_day" size="2" style="text-align:center;" maxlength="2" value="' . $day . '" />' );
		$this->addBuffer( '<input type="text" ' . $jsColor . ' name="genform_' . $name . '_month" size="2" style="text-align:center;" maxlength="2" value="' . $month . '" />' );
		$this->addBuffer( '<input type="text" ' . $jsColor . ' name="genform_' . $name . '_year" size="4" style="text-align:center;" maxlength="4" value="' . $year . '" />' );

		$this->genHiddenItem( 'genform_' . $name, "" );

		$this->addBuffer( '<img onmouseup="toggleDatePicker(\'genform_date_' . $name . '\',\'genform_formu.genform_' . $name . '\')" id="genform_date_' . $name . 'Pos" name="genform_date_' . $name . 'Pos" ' );

		$this->megaZ += 10;
		$this->addBuffer( ' width="19" height="19" src="'.t('src_calendrier').'" align="absmiddle"  border="0" alt="' . $this->trad( 'date_picker' ) . '"  />' );
		$this->addBuffer( '<div id="genform_date_' . $name . '" style="position:absolute;"></div>' );

		*/
		$this->addBuffer( '<input type="text" ' . $jsColor . ' name="genform_' . $name . '_hh" size="2"  style="text-align:center;width:20px" maxlength="2" value="' . $hh . '" /> h' );
		$this->addBuffer( '<input type="text" ' . $jsColor . ' name="genform_' . $name . '_mm" size="2" style="text-align:center;width:20px" maxlength="2" value="' . $mm . '" /> m' );
		$this->addBuffer( '<input type="text" ' . $jsColor . ' name="genform_' . $name . '_ss" size="2" style="text-align:center;width:20px" maxlength="2" value="' . $ss . '" /> s' );

		
		
		//$this->dateFields[] = 'genform_date_'.$name;
		
	} else {
		$this->addBuffer( $day . '/' . $month . '/' . $year .' '.$timeTab[0].'h'.$timeTab[1].'m'.$timeTab[2].'s');
	}

?>

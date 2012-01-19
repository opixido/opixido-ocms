<?php


$date = $this->tab_default_field[$name] ;
$t = strtotime($this->tab_default_field[$name] );

if($t > 0) {
	
	$hh = date('H',$t);
	$mm = date('i',$t);
	$ss = date('s',$t);
	
	$dat = $t <= 10000 ? '' : date('Y-m-d',$t);
} else {
	$hh = $mm = $ss = $dat = '';
}

if ( !$this->editMode ) {
	
	$this->addBuffer('<input type="text" maxsize="10" size="7" name="genform_'.$name.'" id="genform_'.$name.'" value="'.$dat.'" />');
	
	$this->addBuffer( '<input type="text" ' . $jsColor . ' name="genform_' . $name . '_hh" size="2"  style="text-align:center;width:20px" maxlength="2" value="' . $hh . '" /> h' );
	$this->addBuffer( '<input type="text" ' . $jsColor . ' name="genform_' . $name . '_mm" size="2" style="text-align:center;width:20px" maxlength="2" value="' . $mm . '" /> m' );
	$this->addBuffer( '<input type="text" ' . $jsColor . ' name="genform_' . $name . '_ss" size="2" style="text-align:center;width:20px" maxlength="2" value="' . $ss . '" /> s' );

			
	$this->addBuffer('<script type="text/javascript">
	$(function() {
		$("#genform_'.$name.'").datepicker({
				showOn: "button",
				buttonImage: "img/calendar.gif", 
				buttonImageOnly: true,
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true,
				dateFormat:"yy-mm-dd",
				showAnim:"slideDown",
				buttonText:'.alt(t('calendar')).'
		});
	});
	</script>
	');
	
	
} else {
	
	$this->addBuffer( niceDateTime($date) );
}

	
	

	
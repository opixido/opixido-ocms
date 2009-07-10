<?php


$date = $this->tab_default_field[$name] ;
$t = strtotime($this->tab_default_field[$name] );
/*

$day = date('d',$t);
$month = date('m',$t);
$year = date('Y',$t);
*/

if ( !$this->editMode ) {
	
	$this->addBuffer('<input type="text" maxsize="10" size="7" name="genform_'.$name.'" id="genform_'.$name.'" value="'.$date.'" />');
	
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
	
	$this->addBuffer( niceTextDate($date) );
}

	
	
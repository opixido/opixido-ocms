<?php


class ajaxDate {
	
	
	function __construct($af,$champ) {
		
		$this->af = $af;
		$this->champ = $champ;
		$this->row = $af->row;
		$this->table = $af->table;
		$this->id = $af->id;		
		
		$this->champ_id = $this->table.'-'.$champ.'-'.$this->id;
		
	}
	
	
	function gen() {
		
	
		$d = strtotime($this->getCurrent());
		if(!isNull($this->getCurrent())) {
			$date = date('Y-m-d',$d);
		}
		
	
		$html .= ('<input type="text" maxsize="10" size="7" name="'.$this->champ_id.'" id="'.$this->champ_id.'" value="'.$date.'" />');
		
				
		$html .= ('<script type="text/javascript">
		$(function() {
			$("#'.$this->champ_id.'").datepicker({
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
		
	
		return $html;
		
	}
	
	function getCurrent() {

		return $this->row[$this->champ];
		
	}
	
	
}


?>
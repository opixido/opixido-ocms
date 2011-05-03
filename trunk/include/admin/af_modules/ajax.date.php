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
		if(isNull($this->getCurrent())) {
			
		} else {
			$date = date('Y-m-d',$d);
//			$h = date('H',$d);
//			$m = date('i',$d);
		}
		
	
	$html .= ('<input type="text" maxsize="10" size="7" name="'.$this->champ_id.'" id="'.$this->champ_id.'" value="'.$date.'" />');
		
//	$html .= ( '<input type="text" ' . $jsColor . ' id="' . $this->champ_id . '_h" size="2" maxlength="2" value="' . $h . '" /> H ' );
//	$html .= ( '<input type="text" ' . $jsColor . ' id="' . $this->champ_id . '_m" size="2" maxlength="2" value="' . $m . '" /> M ' );
//	$html .= ( '<input type="text" ' . $jsColor . ' id="' . $this->champ_id . '_s" size="2" maxlength="2" value="' . $ss . '" /> s' );

	
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
	
		
		$html .= '<script type="text/javascript">
		
		gid("'.$this->champ_id.'_m").onchange = gid("'.$this->champ_id.'_h").onchange =  gid("'.$this->champ_id.'").onchange = function() {
		
			var ob = gid("'.$this->champ_id.'");
			var v = ob.value+" "+gid(ob.name+"_h").value+":"+gid(ob.name+"_m").value+":00";
			ajaxSaveValue(v,'.js($this->table).','.js($this->champ).','.js($this->id).');
		};
		
		</script>';
		
		/*
		
		$html = '<input type="text" class="ajax_varchar" id="'.$this->champ_id.'" ';
		$html .= ' onchange="ajaxSaveValue(this,'.js($this->table).','.js($this->champ).','.js($this->id).')" ';
			
		$html .= ' value='.alt($this->getCurrent());
		
		$html .= ' >'."\n";
		*/
		
		return $html;
		
	}
	
	function getCurrent() {

		return $this->row[$this->champ];
		
	}
	
	
	
	
}


?>
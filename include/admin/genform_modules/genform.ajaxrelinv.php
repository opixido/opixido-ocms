<?php

class ajaxRelinv {
	
	
	var $cur_table;
	var $cur_id;
	var $fk_field;
	var $fk_table;
	var $fake_name;
	var $nb_line = 0;
	
	
	function __construct($cur_table,$cur_id,$fk_table,$fk_field,$fake_name) {
		
		$this->cur_table = $cur_table;
		$this->cur_id = $cur_id;
		$this->fk_table = $fk_table;
		$this->fk_field = $fk_field;
		$this->fake_name = $fake_name;
		
	}
	
	
	function getCurrent() {
		global $orderFields;
		if($this->cur_id == 'new') {
			return array();
		}
		$sql = 'SELECT * FROM '.$this->fk_table.' WHERE '.$this->fk_field.' = '.sql($this->cur_id).' ORDER BY ';
        if(count($orderFields[$this->fk_table])) {
                $sql .= $orderFields[$this->fk_table][0]." ,  ";
        }

        //$sql .= $this->getNomForOrder( $tabForms[$fk_table]['titre'] );
        $sql .= GetTitleFromTable($this->fk_table,' , ');
		$res = GetAll($sql);
		
		return $res;
		
	}
	
	
	function addOne() {
		
		
	}
	
	
	function getValue() {
		
		$cur = $this->getCurrent();
		
		foreach($cur as $k=>$v) {
			$html .= GetTitleFromRow($this->fk_table,$v, " / ").'<br/>';
		}
		
		return $html;
		
	}
	
	
	function getForm($fields) {
		
		$liste = $this->getCurrent();
		
		
		$html = '<div class="ajaxRelinv">
		
		<script src="js/ajaxForm.js" type="text/javascript">		
			
		</script>
		
		<a href="" onclick="';
		
		$html .= 'arAddValue(this,\''.$this->cur_table.'\',\''.$this->fake_name.'\',\''.$this->cur_id.'\');return false;">
		<img src="'.ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/list-add.png" alt="'.t('ajouter').'" /></a>
		
		<table class="genform_table ajax_table" id="ar_'.$this->cur_table.'-'.''.$this->fake_name.'">';
		
		global $restrictedMode;
		$restrictedMode = true;
		//$this->nbLines = count($liste);
		foreach( $liste as $row) {
			$html .= '<tbody>'."\n";
			$html .= $this->getLine($row,$fields)."\n";
			$html .= '</tbody>'."\n";
		}
		
		$html .= '</table>
		</div>';
		
		return $html;
		
	}
	
	
	function getLine($row,$fields) {
		
		global $restrictedMode,$orderFields;
		$restrictedMode = true;
		
		$this->nb_line++;
		$idd = $row[getPrimaryKey($this->fk_table)];
		
		/**
		 * TR
		 */
		$html .= "\n".'<tr id="ar_'.$idd.'">';
			
		/**
		 * Cellule Delete
		 */
		
		$html .= '
				<td> 
					<a href=""
					onclick="arDelete(this,\''.$this->fk_table.'\','.$idd.');return false;"
					>
					<img src="'.ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/list-remove.png" 
							alt="'.t('delete').'" />
					</a> 
				</td>';
		
		if(ake($this->fk_table,$orderFields)) {
			$html .= '<td>';
			
			$html .= '<img onclick="arGoUp(this,'.js($this->fk_table).','.js($idd).')" 
							src="'.ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/go-up.png" 
							alt="'.t('go_up').'" />';
			
			$html .= '</td>';
			$html .= '<td><img onclick="arGoDown(this,'.js($this->fk_table).','.js($idd).')" 
							src="'.ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/go-down.png" 
							alt="'.t('go_down').'" /></td>';
			
		}

		
		/*
		$gf = new GenForm($this->fk_table,'get',$row[getPrimaryKey($this->fk_table)],$row);
		$gf->showHelp =false;
		foreach($fields as $v) {
			$gf->genFields($v,'','',
			' onchange="arSaveValue(this,\''.$this->fk_table.'\',\''.$v.'\',\''.$row[getPrimaryKey($this->fk_table)].'\',\''.$this->cur_table.'\')" ');
			$html .= '<td>'.$gf->getBuffer(true).'</td>';
		}
		*/
		//error_reporting(E_ALL);
		
		$af = new ajaxForm($this->fk_table,$idd);
		foreach($fields as $v) {
			$html .= '<td><label>'.t($v).'</label>'.$af->genField($v).'</td>';			
		}
		$html .= "\n".'</tr>';
		
		return $html;
			
	}
	
	
}

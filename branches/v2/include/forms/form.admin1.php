<?php


print('<!--');
	$form->gen('admin_last_cx');
print('-->');


global $gs_obj;
$sql = 'SELECT * FROM r_admin_rubrique WHERE fk_admin_id = "'.$this->tab_default_field['admin_id'].'"';
//debug($sql);
$res = GetAll($sql);

if($this->editMode) {
	$this->gen('r_admin_rubrique');
} else{
	
	$this->admin_curdroits = array();
	
	foreach($res as $row) {
		$this->admin_curdroits[$row['fk_rubrique_id']] = true;
	}



	
	function getRubsArbored($fk_rub=0,$lev=1,$droits) {
		
		if($lev > 4)
			return;
		
		if($fk_rub == 0) {
			$like_fk_rub = ' IS NULL ';
		} else {
			$like_fk_rub = ' = '.$fk_rub.' ';
		}
		
		$sql = 'SELECT * FROM s_rubrique AS R WHERE 1 '.sqlRubriqueOnlyReal('R').' AND R.fk_rubrique_id  '.$like_fk_rub.' ';
		//debug($sql);
		$res = GetAll($sql);
		
		p('<ul >');
		foreach($res as $row) {
			p('<li >');
			
			$checked = array_key_exists($row['rubrique_id'] ,$droits) ? 'checked="checked"' : '';
			
			p('<input type="checkbox" '.$checked.' name="genform_rel__r_admin_rubrique__rubrique_id[]" value="'.$row['rubrique_id'].'" /><label>'.$row['rubrique_titre_fr']);
			p('<ul>');
			getRubsArbored($row['rubrique_id'],$lev+1,$droits);
			p('</ul>');
			
			}
			p('</ul>');
	
	}

	p('<input type="hidden" name="genform_rel__r_admin_rubrique__rubrique_id_temoin" value="1" />');
	
	$_SESSION[gfuid()]['curFields'][] = 'r_rubrique_id';

	getRubsArbored(0,1,$this->admin_curdroits);
	
}

?>
<script type="text/javascript">

oldclick = gid('aongl1').onclick;
gid('aongl1').onclick = function() {
	gid('genform_curPage').value = 1;
	gid('reloadpage').click();	
}

</script>
<?php

$sql = 'SELECT * FROM s_admin_role AS AR, s_role AS R, s_role_table AS RT WHERE RT.fk_role_id = R.role_id AND AR.fk_role_id = R.role_id AND AR.fk_admin_id = "'.$form->id.'" ORDER BY R.role_nom, RT.role_table_table ';
$res = GetAll($sql);



if(!$form->editMode) {
	/*p('<strong>Si vous venez de faire des changements dans les roles attribués, cliquez ci-dessous pour mettre à jour les droits</strong><br/>');*/
	p('<input type="submit" id="reloadpage" class="button" value="'.t('actualiser_les_droits').'" name="stay_on_form" />');
}


p('<table class="table_resume">');

foreach($res as $row) {
	
    if($row['role_table_table'] == 'c_programme') {
        continue;
    }
	p('<tr><td class="table_resume_label">');
	print('<h4>'.$row['role_nom'].'</h4>');
	
	p('</td><td  class="genform_champ">');
	p('<h5> - '.t($row['role_table_table']).' : '.t($row['role_table_type']).'</h5>');
	
	
	if($row['role_table_type'] == 'per_user') {
		
		$sql = 'SELECT * FROM s_admin_rows 
				WHERE fk_admin_id = "'.$form->id.'" 
				AND fk_table = "'.$row['role_table_table'].'"';
		
		$resA = GetAll($sql);
		
		$selecteds = array();
		
		foreach($resA as $rowA) {
			$selecteds[] = $rowA['fk_row_id'];
			$droits[$rowA['fk_row_id']] = true;
		}

		
		if($row['role_table_table'] == 's_rubrique') {
			
			
			function getRubsArbored($fk_rub=0,$lev=1,$droits,$form) {
		
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
				
				p('<ul style="padding:0;margin:0;list-style-type:none">');
				foreach($res as $row) {
					p('<li style="padding:0;margin:0">');
					
					$checked = array_key_exists($row['rubrique_id'] ,$droits) ? 'checked="checked"' : '';
					
					if($form->editMode && $checked) { 
						p($row['rubrique_titre_fr'].'<br/>');
					} else if(!$form->editMode) {
						p('<input type="checkbox" '.$checked.' name="s_admin_rows[s_rubrique][]" value="'.$row['rubrique_id'].'" /><label>'.$row['rubrique_titre_fr']);
					}
					p('<ul>');
					getRubsArbored($row['rubrique_id'],$lev+1,$droits,$form);
					p('</ul></li>');
					
					}
					p('</ul>');
			
			}
			
			getRubsArbored(0,1,$droits,$form);
			
			
		} else {
			
			
			$ppk = getPrimaryKey($row['role_table_table']);			
			
			$sql = 'SELECT '.$ppk.' , '.getTitleFromTable($row['role_table_table'],' , ').' 
						FROM '.$row['role_table_table'].' WHERE 1 ORDER BY '.getTitleFromTable($row['role_table_table'],' , ');
			
			$rese = GetAll($sql);
			
			
			if($form->editMode) {
				p('<div class="genform_champ">');
			} else {
				p('<div class="scrollbox">');
			}
			foreach($rese as $rowe) {
				
				if($form->editMode) {
					if((in_array($rowe[$ppk],$selecteds))) {
						p(' &nbsp; '.GetTitleFromRow($row['role_table_table'],$rowe).'<br/>');
					}
				}  else {
			
					p('<input type="checkbox" '.(in_array($rowe[$ppk],$selecteds) ? 'checked="checked"': '').' name="s_admin_rows['.$row['role_table_table'].'][]" value="'.$rowe[$ppk].'" id="'.$row['role_table_table'].'__'.$rowe[$ppk].'" /> ');	
					p('<label for="'.$row['role_table_table'].'__'.$rowe[$ppk].'">'.GetTitleFromRow($row['role_table_table'],$rowe).'</label><br/>');	
				}
			}
			
			p('</div >');
			
		}
		
	}
	
	
	p('</td></tr>');
	
	
}


p('</table>');

//$form->gen('admin_id');
p('<!--');
$form->gen('admin_last_cx');
p('-->');
?>
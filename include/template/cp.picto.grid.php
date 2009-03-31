<?php


global $_Gconfig;

$tables = getTables();
foreach ($_Gconfig['adminMenus'] as $k=>$menus ) {
	
	p('<div id="mm_'.$k.'" class="picto_section" style="padding-top:0px;">');
	
	if(strlen($k)> 1) {
		p('<h2  style="padding:5px;padding-top:15px;display:block;width:auto;margin:0;text-transform:uppercase;">'.t($k).'</h2>');
	}
	
	$dones=0;
	foreach ($menus as $menu) {
	?>

	<?php  if($GLOBALS[gs_obj]->can('edit', $menu)){ 
		
		$dones++;
		
		$url = in_array($menu,$tables) ? 'index.php?curTable='.$menu : ta('cp_link_'.$menu);
		
		?> 
	
		<a class="fond2" href="<?=$url?>"   >
		<?php
		global $tabForms;
		if($tabForms[$menu]['picto']) {
			$src = $tabForms[$menu]['picto'];
		} else 
		if(file_exists('./img/picto_'.$menu.'.gif')) {
			$src = './img/picto_'.$menu.'.gif';
		} else {
			$src = './img/picto_default.gif';	
		}
		?> 
		<img src="<?=$src?>" alt=""  />
		<?= t('cp_txt_'.$menu); ?></a>
		
		<? }	
		
	}
	
	p('</div>');
	
	if(!$dones) {
		p('<style type="text/css">#mm_'.$k.' {display:none;}</style>');
	}
	
	
	}
	
	p('<div id="mm_'.$k.'" class="picto_section" style="padding-top:20px;">');
	
	


?>
<div class="home">
<?php

$tables = getTables();

global $_Gconfig;

p('<div class="bigmenus">');
//p('<h2>'.t('main').'</h2>');
ksort($_Gconfig['bigMenus']);
foreach ($_Gconfig['bigMenus'] as $k=>$menus ) {

    if(!count($menus)) {
	continue;
    }
   p('<div style="float:left"><h2>'.t($k).'</h2>');
	foreach ($menus as $menu) {
		
		if($GLOBALS['gs_obj']->can('edit', $menu)){ 
		
			$url = in_array($menu,$tables) ? 'index.php?curTable='.$menu : ta('cp_link_'.$menu);
			
			p('
			<a  href="'.$url.'">
				<img src="'.getPicto($menu,'48x48').'" alt="" />
				<span>'. t('cp_txt_'.$menu).'</span>
			</a>
			');
				
		}
		
	}
	p('</div>');
	
}

p('</div>');

p('<div class="clearer" style="margin-bottom:50px;"></div>');
p('<table style="background:#ddd;"><tr>');

foreach ($_Gconfig['adminMenus'] as $k=>$menus ) {
	
	p('<td id="mm_'.$k.'" style="vertical-align:top;background:#fff;">');
	p('<div  class="picto_section" style="padding-top:0px;">');
	
	if(strlen($k)> 1) {
		p('<h2 >'.t($k).'</h2><hr/>');
	}
	
	$dones=0;
	foreach ($menus as $menu) {
	?>

	<?php  if($GLOBALS['gs_obj']->can('edit', $menu)){ 
		
		$dones++;
		
		$url = in_array($menu,$tables) ? 'index.php?curTable='.$menu : ta('cp_link_'.$menu);
		
		?> 
	
		<a class="fond2" style="display:block;float:none;" href="<?=$url?>"   >
		<?php
		global $tabForms;
		if(isset($tabForms[$menu]) && isset($tabForms[$menu]['picto'])) {
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
	p('</td>');
	
	
	}
p('</tr></table>');	
	p('<div id="mm_'.$k.'" class="picto_section" style="padding-top:20px;">');
	
	


?>
</div>
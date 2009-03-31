<?php







if($_REQUEST['curId'] != 'new') {
	$this->gen('plugin_nom');
    // on vérifie que le plugin est installé
    $sql ='SELECT plugin_installe FROM s_plugin WHERE plugin_nom='.sql($_REQUEST['curId']);
    $res = GetSingle($sql);
    $isInstalled = $res['plugin_installe'];
    
    if($isInstalled)
        $this->gen('plugin_actif'); 
        
        
    $this->gen('fk_param_id');
	$this->gen('fk_trad_id');
	$this->gen('fk_admin_trad_id');
	$this->gen('plugin_ordre');
    
} else {
	
	global $co;
	$res = $co->GetAssoc('SELECT plugin_nom,plugin_installe FROM s_plugin WHERE 1 ');
	
	echo '<!--';
	$this->gen('plugin_nom');
	
	echo '-->';
	$plugs = ($GLOBALS['gb_obj']->getFolderListing('plugins'));
	echo '<label for="plugin_nom">'.ta('plugin_nom_install').'</label>';
	echo '<select name="genform_plugin_nom">';
	foreach($plugs as $v) {
		if(!ake($v,$res)) {
			echo '<option value="'.$v.'">'.$v.'</option>';
		}
	}
	echo '</select>';
	
}
    





?>

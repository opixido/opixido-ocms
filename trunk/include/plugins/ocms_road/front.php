<?php

class ocms_roadFront extends ocmsPlugin  {
	
	function genBeforePara () {
		
		$road = $this->site->g_url->buildRoad();
		
		$tpl = new genTemplate(true);
		$tpl->loadTemplate('template','plugins/ocms_road');
		$tpl->defineBlocks('PAGE');
		$tpl->set('vous_etes_ici',t('road_vous_etes_ici'));
		
		$nbR = count($road);
		$nb = 0;
		foreach($road as $k=>$v) {
			$nb++;
			$page = $tpl->addBlock('PAGE');
			$page->set('titre' , $v['titre']);
			$page->set('url' , $v['url']);
			if($nb < $nbR) {
				$page->set('raquo',' > ');	
			} else {
				$page->set('raquo','');
			}
		}
		
		return $tpl->gen();
		
	}
	
}


?>
<?php

class ocms_downloadFront extends ocmsPlugin {
	
	/**
	 * Box template location
	 * @var string
	 */
	public $template = "download_box";
	public $template_folder = "plugins/ocms_download/tpl";
	
	/**
	 * List of downloads
	 * @var array
	 */
	public $downloads = array();
	
	
	function afterInit() {
		
		/**
		 * Selecting all the downloads for current page
		 * @var array
		 */
		$this->downloads = getAll('SELECT * FROM p_download 
									WHERE fk_rubrique_id = '.sql($this->site->getCurId()).'
									 ORDER BY download_ordre ');
		
		if($this->downloads) {
			$this->site->plugins['o_blocs']->blocs['jaune']->add('downloads',$this->genDownloads());
		}
	}
	
	
	/**
	 * Génère le tout
	 * 
	 * @return unknown_type
	 */
	function genDownloads() {
		
		/**
		 * No downloads => nothing to return
		 */
		if(!$this->downloads) {
			return;
		}
		
		/**
		 * Template
		 * @var genTemplate
		 */
		$tpl = new genTemplate();				
		$tpl->loadTemplate($this->template,$this->template_folder);		
		$tpl->defineBlocks('DOWNLOAD');		
		$tpl->titre = getImgText(t('download_titre'),'titre_jaune');
		/**
		 * Looping
		 */
		foreach($this->downloads as $k=>$v) {
			
			/**
			 * Download Block
			 * @var genTemplate
			 */
			$d = $tpl->addBlock('DOWNLOAD');
			
			/**
			 * Download row
			 * @var row
			 */
			$dw = new row('p_download',$v);
			
			$d->titre = $dw->download_titre;
			$d->url = $dw->download_fichier->getWebUrl();
			$d->size = $dw->download_fichier->getNiceSize();			
			$d->type = $dw->download_fichier->getExtension();
			
		}
		
		return $tpl->gen();//.'<p>a</p><p>a</p><p>a</p><p>a</p><p>a</p><p>a</p><p>a</p><p>a</p><p>a</p><p>a</p>';
		
	}
}
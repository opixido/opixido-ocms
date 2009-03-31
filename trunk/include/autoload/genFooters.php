<?php


class genFooters {

	private $title;

	private $html_Footers;
	public $has_hdpage;


	function genFooters($site) {

		$this->site = $site;
		$this->has_hdpage = true;
		$this->footer_right = t('footer_copyright');
	}


	function gen() {


		/*$cache = new GenCache('cache_footer_arbo',GetParam('date_update_arbo'));

		if($cache->cacheExists()) {

			$footer = $cache->getCache();

		} else {
		*/
			//$tpl2 = $this->site->g_menu->getTabFooter();
			//$footer = $tpl2;
		/*
			$cache->saveCache($tpl2);

		}
		*/
		$tpl = new genTemplate();

		$tpl->loadTemplate('footers.html');

		if(!$this->has_hdpage)
			$tpl->set('class_hdpage', 'invisible');

		$tpl->set('html_footers',$this->getHtmlFooters());
		$tpl->set('footer_right',$this->footer_right);
		//$tpl->set('footer',$footer);

		$html = $tpl->gen();

		return $html;

	}



	function addHtmlFooters($str) {
		$this->html_Footers .= "\n".$str."\n";
	}

	function getHtmlFooters() {
		return $this->html_Footers;
	}

}



?>
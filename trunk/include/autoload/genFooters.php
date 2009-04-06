<?php
#
# This file is part of oCMS.
#
# oCMS is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# oCMS is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with oCMS. If not, see <http://www.gnu.org/licenses/>.
#
# @author Celio Conort / Opixido 
# @copyright opixido 2009
# @link http://code.google.com/p/opixido-ocms/
# @package ocms
#


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


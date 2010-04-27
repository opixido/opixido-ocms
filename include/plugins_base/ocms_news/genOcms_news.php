<?php
		
class genOcms_news extends baseGen {

	public $table = "p_news";
	

	function afterInit() {
		
		$sql ='SELECT DISTINCT(YEAR(news_date)) AS ANNEE FROM p_news 
					WHERE 1 '.sqlOnlyOnline('p_news').'
					ORDER BY news_date DESC';
		$this->years = (GetAll($sql));
		

		if($_REQUEST['y'] && in_array(array('ANNEE'=>$_REQUEST['y']),$this->years)) {
			$this->year = $_REQUEST['y'];
		} else {
			$this->year = $this->years[0]['ANNEE'];
		}
		
		$this->site->g_headers->addcss('news.css');
		
		$sql = 'SELECT DISTINCT(MONTH(news_date)) AS MOIS 
					FROM p_news WHERE 1 '.sqlOnlyOnline('p_news').' 
					AND YEAR(news_date) = '.sql($this->year).'
					ORDER BY news_date DESC';
		
		$this->mois = GetAll($sql);
		debug($_GET);
		if($_REQUEST['m'] && in_array(array('MOIS'=>$_REQUEST['m']),$this->mois)) {
			$this->month = $_REQUEST['m'];
		} else {
			$this->month = $this->mois[0]['MOIS'];
		}
		
		global $_locale;

		$html .= '<h2>'.(t('news_archives')).'</h2><ul class="mois">';
		foreach($this->mois as $v) {
			$html .= '<li><a  '.($v['MOIS'] == $this->month ? 'class="selected"' :'').' href="'.getUrlWithParams(array('y'=>$this->year,'m'=>$v['MOIS'])).'">'.$_locale['fr']['months_long'][$v['MOIS']-1].' '.$this->year.'</a></li>';
		}
		
		$html .= '</ul><ul class="annees">';
		$c = count($this->years) -1;
		foreach($this->years as $k=>$v) {
			$html .= '<li '.($k==$c?'class="dernier"':'').'><a '.($v['ANNEE'] == $this->year ? 'class="selected"' :'').' href="'.getUrlWithParams(array('y'=>$v['ANNEE'])).'">'.$v['ANNEE'].'</a></li>';						
		}
		$html .= '</ul>';
		
		debug(getUrlWithParams(array('actu'=>1,'toto'=>'')));
		
	//	$this->site->plugins['o_blocs']->blocs['jaune']->add('archives',$html);
		
	}
	
	
	
	function gen() {
		
		$sql = 'SELECT * FROM p_news WHERE 
							YEAR(news_date) = '.sql($this->year).' 
							AND MONTH(news_date) = '.sql($this->month).' 
							'.sqlOnlyOnline('p_news').' ORDER BY news_date DESC ';
		
		$res= getall($sql);
		
		$tpl = new genTemplate();
		$tpl->loadTemplate('news.liste','plugins/ocms_news/tpl');
		
		$tpl->defineBlocks('NEWS');
		
		foreach ($res as $k=>$row) {
			$t = $tpl->addBlock('NEWS');
			$n = new row('p_news',$row);
			
			$t->row = $k%2;
			$t->titre = $n->news_titre;
			$t->desc = $n->news_desc;
			$t->date = nicedate($n->row['news_date']);
			
			$t->media = '<a href="'.$n->news_img->getWebUrl().'" rel="prettyPhoto[paras]">'.$n->news_img->getThumbImgTag(170,80,$n->news_img_alt,'',array(ARRONDI)).'</a>';
		}
		
		return $tpl->gen();
		
	}

}

		
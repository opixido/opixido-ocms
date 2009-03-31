<?php



class genParagraphes {
	
	
	var $paragraphes;
	var $site;
	var $rubrique;
	
	function __construct($site,$paragraphes) {
		$this->site = $site;
		$this->rubrique = $this->site->g_rubrique;
		
		$this->paragraphes = $paragraphes;
		
		
	}
	

	
	function getHtmlParagraphes() {
	
	    $curpara = 0;
		$nbpara = count($this->paragraphes);
		foreach($this->paragraphes as $nbparaK=>$para) {
						
			$curpara++;
			
			$html = '';



			/**
			 *  Creation du template 
			 **/
			$tpl = new genTemplate(true);
			
			if(ake($_REQUEST,'ocms_mode') && $para['para_type_template_'.$_REQUEST['ocms_mode']])
				$tpl->setTemplate($para['para_type_template_'.$_REQUEST['ocms_mode']]);
			else
				$tpl->setTemplate(''.$para['para_type_template']);
				
			/**
			 * Contenu
			 */
			$conte = GetLgValue('paragraphe_contenu', $para);		
			

			$tpl->setVar('titre', GetLgValue('paragraphe_titre', $para));
			$tpl->setVar('texte', ($conte));

			/**
			 *  Images associees 
			 **/
	
			$img = new GenFile('s_paragraphe', 'paragraphe_img_1', $para['paragraphe_id'], $para,true,true);
	


			$img2 = new GenFile('s_paragraphe', 'paragraphe_img_2', $para['paragraphe_id'], $para,true,true);

			
			$tpl->setImg(1, $img->getWebUrl(), GetLgValue('paragraphe_img_1_alt', $para,false));
			$tpl->setImg(2, $img2->getWebUrl(), GetLgValue('paragraphe_img_2_alt', $para,false));
			
			$tpl->setGFImg('img1','s_paragraphe', 'paragraphe_img_1', $para);
			
			$tpl->setVar('legend_1' , GetLgValue('paragraphe_img_1_legend' , $para ) ) ;
			$tpl->setVar('copyright_1' , $para['paragraphe_img_1_copyright'] ) ;
			$tpl->setVar('legend_2' , GetLgValue('paragraphe_img_2_legend' , $para ) ) ;
			$tpl->setVar('copyright_2' , $para['paragraphe_img_2_copyright'] ) ;

			/**
			 * Fichier joint
			 */
		
			$fichier = new GenFile('s_paragraphe', 'paragraphe_file_1', $para['paragraphe_id'], $para,true,true);

			
			$tpl->setVar('file1_url',$fichier->getWebUrl());
			$tpl->setVar('file1_size',$fichier->getNiceSize());
			$tpl->setVar('file1_type',$fichier->getExtension());
			$tpl->setVar('file1_name',$fichier->getRealName());
			$tpl->setVar('file1_legend',getLgValue('paragraphe_file_1_legend',$para));
			$tpl->setVar('link1',getLgValue('paragraphe_link_1',$para));

				
			if(akev($_REQUEST,'pdf')) {
				$tpl->setVar('img', $img->getWebUrl());
			}
			/**
			 *  Si c'est un paragraphe tableau  
			 * 
			if( $para['para_type_use_table'] == 1  && strlen(trim(GetLgValue('paragraphe_contenu_csv',$para,false))))
			{
				$params = $_GET;
				$params['export'] = 1;
				$params['para'] = $para['paragraphe_id'];
				$tpl->setVar('dwl_csv',
							 '<a class="exporter_tableau"
							 	 href="'.$GLOBALS['site']->g_url->getUrlWithParams($params).'" 
							 	 onclick="return doblank(this)">'
							 	.t('exporter_tableau').'
							 	</a>');
			}
			
			*/



			$tpl->setVar('lien_swf', GetLgValue('paragraphe_params', $para,false));

			$tpl->setVar('lien_popup', $this->site->g_url->getUrlWithParams(array('ocms_mode'=>'popup','para'=>$para['paragraphe_id'])));
			
			$html .= '<a name="para_'.nicename(GetLgValue('paragraphe_titre', $para)).'"></a>';
			
			
			$html .= '<div id="para_nb_'.$curpara.'" class="paragraphe_simple">';

			//$html .= $gfa->startField('all', $actions);

			$html .= $tpl->gen();

			//$html .= $gfa->endField();

			$html .= '</div>';
			

			
			$this->paragraphes[$nbparaK]['html'] = $html;
			$this->paragraphes[$nbparaK]['titre'] = getLgValue('paragraphe_titre',$para);
					
					
					
			}
				
		return $this->paragraphes;
	}
	
	function gen() {
		
		$this->getHtmlParagraphes();
		
		$html = '';
		
		foreach($this->paragraphes as $para) {
			if($_REQUEST['para']) {
					if($para['paragraphe_id'] == $_REQUEST['para']){
						$html .= $para['html'];
					}
			} else {
					$html .= $para['html'];
			}
				
			
		}
		return '<div id="paragraphes">'.$html.'</div>';
	}
	
}			
				
?>
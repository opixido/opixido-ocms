<?php


class ocms_sendtofriendFront extends ocmsPlugin  {
	
	
	function afterInit() {
		
		
		
	}
	
	
	function getForm($titre='',$url='') {
		
		if(!$titre) {
			$titre = $this->site->g_url->buildRoad();
			$titre = $titre[0];
		}
		
		if(!$url) {
			$url = $_SERVER['REQUEST_URI'];
		}
		
		$f = new simpleForm(addParamsToUrl(),'post','sendtofriend');
		
		$f->add('text','',t('stf_yourname'),'stf_yourname','stf_yourname',true);
		$f->add('email','',t('stf_yourmail'),'stf_yourmail','stf_yourmail',true);
		
		$f->add('text','',t('stf_friendname'),'stf_friendname','stf_friendname',true);
		$f->add('email','',t('stf_friendmail'),'stf_friendmail','stf_friendmail',true);		
		
		$f->add('textarea','',t('stf_comment'),'stf_comment','stf_comment',true);
		
		$f->add('submit',t('stf_submit'),'stf_submit','stf_submit','stf_submit');
		
		
		
		if($f->isSubmited() && $f->isValid()) {
			$this->sendMail($titre,$url);
			
			$content = '<a href="#" class="stf_ok" onclick="hide(\'stf_form\');return false;">'.t('stf_ok').'</a>';
			$content .= '<script type="text/javascript">show(\'stf_form\');</script>';
			
		} else if($f->isSubmited()) {
			
			$content = '<div class="error">'.t('stf_error').'</div>'.$f->gen();
			$content .= '<script type="text/javascript">show(\'stf_form\');</script>';
			
		} else {
			$content = $f->gen();
		}
		
		$html = '
		<div class="stf_bloc">
			<a class="stf_link" href="#" onclick="showhidejq((\'stf_form\'));return false;">'.t('stf_link').'</a>
			<div id="stf_form">'.$content.'</div>
		</div>
		';
		
		return $html;
		
	}
	
	
	function sendMail($titre,$url) {
		
		$m = includeMail();
		//$m = new PHPMailer();
		$m->AddAddress($_POST['stf_friendmail']);
		$m->Subject = t('stf_subject');
		
		$m->ReplyTo = $_POST['stf_youremail'];
		$m->FromName = $_POST['stf_yourname'];
		
		$m->Body = tf('stf_body',
						array(
							'YOURNAME'=>$_POST['stf_yourname'],
							'FRIENDNAME'=>$_POST['stf_friendname'],
							'COMMENT'=>$_POST['stf_comment'],
							'URL'=>$url,
							'TITRE'=>$titre							
							));
		
		$m->send();
		
	}
}



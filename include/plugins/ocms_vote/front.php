<?php


class ocms_voteFront  extends ocmsPlugin {
	
	/**
	 * Note max
	 *
	 * @var unknown_type
	 */
	public $maxNote = 5;
	
	/**
	 * Note min
	 *
	 * @var unknown_type
	 */
	public $minNote = 1;
	
	/**
	 * Combien de votes avant d'afficher les résultats
	 *
	 * @var unknown_type
	 */
	public $minVoteToShow = 3;
	
	
	function __construct($site) {
		
		
		
		/**
		 * Si on demande à voter
		 */
		if($_GET['jsvote']) {
				
			/**
			 * On doit avoir le cookie jsvote pour pouvoir voter
			 * Rajouter l'IP ?
			 */
			if(!$_COOKIE['ocms_can_vote']) {
				
				echo t('vote_cookies');
				die();					
			
			/**
			 * Si il a pas dejà voté pour cette ressource
			 * Rajouter l'IP ?
			 */		
			} else if( !$this->hasVoted($_GET['obj'],$_GET['id']) ) {				
				
				if($_GET['vote'] < $this->minNote) {
					die('vote_error');
				}
				if($_GET['vote'] > $this->maxNote) {
					die('vote_error');
				}
				/**
				 * On met le cookie comme quoi il a voté
				 */
				$row = GetSingle('SELECT * FROM plug_vote LEFT JOIN plug_vote_log ON 
										(
											ressource_table = vote_log_table 
											AND vote_log_id = fk_ressource_id 
											AND vote_log_ip = '.sql($_SERVER['REMOTE_ADDR']).'
										) 
									WHERE fk_ressource_id = '.sql($_GET['id']).' 
									AND ressource_table = '.sql($_GET['obj']).'');
				
				
				if($row['vote_log_ip']) {
					$this->setCookie();
					die(t('vote_deja'));				
				}
				
				if(!$row) {
					$moyenne = $_GET['vote'];					
					$nb = 0;
				} else {
					$moyenne = $row['vote_moyenne'];
					$nb = $row['vote_nb'];
				}
				
				$moyenne = str_replace(',','.',( ( $moyenne * $nb ) + $_GET['vote'] ) / ( $nb + 1 ));
				
				DoSql('REPLACE INTO plug_vote SET ressource_table = '.sql($_GET['obj']).' , fk_ressource_id = '.sql($_GET['id']).' , vote_moyenne = '.sql($moyenne).' , vote_nb = '.sql($nb+1));
				
				$sqlLog = 'REPLACE INTO plug_vote_log SET 
								vote_log_table = '.sql($_GET['obj']).' , 
								vote_log_id = '.sql($_GET['id']).' , 
								vote_log_ip = '.sql($_SERVER['REMOTE_ADDR']).' , 
								vote_log_time = NOW(),
								vote_log_note = '.sql($_GET['vote']).'
								';			
					
				if($_SESSION['ocms_login']['utilisateur_id']) {
					$sqlLog .= ' , fk_utilisateur_id = '.$_SESSION['ocms_login']['utilisateur_id'];
				}
				
				DoSql($sqlLog);
				
				$this->setCookie();
											
				echo t('vote_merci').'';
							
				die();
			/**
			 * Sinon c'est qu'il a déjà voté
			 */
			} else {
				echo t('vote_deja');
				die();
			}
		}
		
		
		parent::__construct($site);
		
		/**
		 * On définit qu'il peut voter
		 */
		setcookie('ocms_can_vote','1');
		
	}
	
	
	function setCookie() {
		
		setcookie('ocms_voted_'.$_GET['obj'],'-'.$_GET['id'].'-'.$_COOKIE['ocms_voted_'.$_GET['obj']]);	
		
	}
	
	/**
	 * Javascript de vote en AJAX
	 *
	 */
	function afterInit() {		
		$this->site->g_headers->addScript('vote.js');		
	}
	
	
	/**
	 * Retourne si il a déjà voté ou non
	 *
	 * @param string $obj
	 * @param mixed $id
	 * @return boolean
	 */
	function hasVoted($obj,$id,$row=array()) {
		
		return false;
		
		if($row) {
			if($row['fk_utilisateur_id']) {
				return true;
			}
		}
		return (strpos($_COOKIE['ocms_voted_'.$obj],'-'.$id.'-') !== false);
		
	}
	
	
	/**
	 * Retourne la liste des liens pour voter
	 *
	 * @param string $obj
	 * @param mixed $id
	 * @param array $curRow
	 * @return string html
	 */
	function getVoteLink($obj,$id,$curRow=array()) {
		
		if(!count($curRow)) {
			$curRow = $this->getRow($obj,$id);
		}
		
		if($this->hasVoted($obj,$id,$curRow)) {
			
		} else {
			
			$html .= '<div class="notes"><a href="#" class="voter" onclick="return openVote(this)">'.t('voter').'</a><div class="voter">';
			for($p = $this->minNote;$p<=$this->maxNote;$p++) {				
				$html .= '<a onclick="return vote(this)" href="?vote='.$p.'&amp;obj='.$obj.'&amp;id='.$id.'">'.$p.'</a> ';				
			}
			$html .= '</div></div>';
		}
		return $html;
		
	}
	
	
	function getFullLink($obj,$id,$curRow=array()) {
		
		return $this->getVoteLink($obj,$id,$curRow).$this->getNoteLink($obj,$id,$curRow);
		
	}
	
	function getNoteLink($obj,$id,$curRow=array()) {
		
		if(!count($curRow)) {
			$curRow = $this->getRow($obj,$id);
		}
		
		$html = '<div class="note">'.$this->getNote($curRow).'</div>';
		//$html = '<div class="note">'.$this->getNote($curRow['vote_moyenne']).'</div>';
		
		return $html;
	}
	
	
	
	function getRow($obj,$id) {
		$sql = ('SELECT * FROM plug_vote 
						LEFT JOIN plug_vote_log ON vote_log_table = ressource_table AND fk_ressource_id = vote_log_id
						WHERE ressource_table = '.sql($obj).' AND fk_ressource_id = '.sql($id));
		
		if($_SESSION['ocms_login']['utilisateur_id']) {
			$sql .= ' AND fk_utilisateur_id = '.sql($_SESSION['ocms_login']['utilisateur_id']);
		}
		
		$curRow = GetSingle('SELECT * FROM plug_vote WHERE ressource_table = '.sql($obj).' AND fk_ressource_id = '.sql($id));
		return $curRow;
	}
	
	/**
	 * Formate la note proprement
	 * et retourne 5.0 si la note est vide
	 *
	 * @param float $note
	 * @return float
	 */
	function getNote($note) {
		
		if(is_array($note)) {
			/**
			 * On attend 3 votes pour que ce soit significatif
			 */
			if($note['vote_nb'] < $this->minVoteToShow) {
				return '';
			}
			
			$note = $note['vote_moyenne'];
		}
		
		if($note == 0) {
			return '';
			$note =  5;
		}
		
		$note = round($note);
		$noteH = '';
		
		for($p=$this->minNote;$p<=$this->maxNote;$p++) {
			$noteH .= ' <img src="'.BU.'/img/notes/'.($note>=$p ? 'full' : 'empty').'.gif" alt="" /> ';
		}
		
		return '<div class="label">'.t('note').' : '.$note.'</div>'.'<span class="noteval note_'.$note.'">'.$noteH.'</span>';
		
	}
	
}


?>
<?php


class genActionBanishForumUser extends ocms_action   {

	public $canReturnToList = true;
	
	function checkCondition() {
		if(!$this->row['utilisateur_id']) {
			$this->row = GetSingle('SELECT * FROM e_utilisateur, forum_user WHERE fk_utilisateur_id = utilisateur_id AND forum_user_id = '.sql($this->id));
		}
		if($this->row['utilisateur_valide'] != -1) {

			return true;
		} else {
			return false;
		}
		
	}
	
	function doIt() {
		
		(DoSql('UPDATE e_utilisateur SET utilisateur_valide = -1 WHERE utilisateur_id = '.sql($this->row['fk_utilisateur_id'])));
		
	}
	
}



class genActionUnBanishForumUser extends ocms_action   {

	public $canReturnToList = true;
	
	function checkCondition() {
		
		if(!$this->row['utilisateur_id']) {
			$this->row = GetSingle('SELECT * FROM e_utilisateur, forum_user WHERE fk_utilisateur_id = utilisateur_id AND forum_user_id = '.sql($this->id));
		}
		if($this->row['utilisateur_valide'] == -1) {
			return true;
		} else {
			return false;
		}
		
	}
	
	function doIt() {
		
		(DoSql('UPDATE e_utilisateur SET utilisateur_valide = 1 WHERE utilisateur_id = '.sql($this->row['fk_utilisateur_id'])));
		
	}
	
}

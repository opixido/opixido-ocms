<?php

/**
 * This file is part of oCMS.
 *
 * oCMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * oCMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with oCMS. If not, see <http://www.gnu.org/licenses/>.
 * 
 * @author Celio Conort / Opixido 
 * @copyright opixido 2008
 * @link http://opixido.com
 * @package ocms
 */

class ocms_loginFront {

	/**
	 * gensite
	 *
	 * @var gensite
	 */
	public $site;
	public $rubrique;

	
	public $Groupes = array();

	public $Utilisateurs = array();
	
	public $canCreate = false;
	
	public $createType = 'laSimpleSignUp';
	
	public $minPasswordLength = 4;
	
	public $createGroup = false;
	
	public $privee = false;
	public $logged = false;
	
	public $erreur = false;
	
	function __construct(gensite $site,$params=array()) {

		
		$this->sentParams = $params;

		/**
		 * Domain for cookies
		 */
		$domain = explode('.',$_SERVER['HTTP_HOST']);
		$dl = count($domain);
		$this->domain = $domain[$dl-2].'.'.$domain[$dl-1];
			
		
		/**
		 * Logout Delete all cookies
		 */
		 
		if(akev($_GET,'laLogout')) {			
			$this->doLogout();
		}
		
		if(akev($_GET,'signup')) {			
			$_SESSION['ocms_login'] = array();		
		}
	
		$this->site = &$site;
		$this->rubrique = $rub = $this->site->g_rubrique;
			
		$this->allowed = false ;

		$this->crypto = new crypto(crypto_cipher,crypto_mode,crypto_key_ocms_login);
		
		$this->isRubriquePrivee();			
		

				
		if(akev($_GET,'laCode') && akev($_GET,'laUid')) {
			$this->checkCode();
		}
			
		
		if(!$this->privee) {
			$this->allowed = true ;
			return;
		}
		
		$this->site->g_headers->addCss('ocms_login.css');

		/**
		 * Si il n'est pas connecte, on gere le formulaire
		 */
		if($this->privee && !$this->isLogged()) {
			if($_POST['login'] && $_POST['password']) {
				$log = $this->isPasswordOk($_POST['login'],$_POST['password']);
			} 
			else if($_COOKIE['ocms_login_remember'] && $_COOKIE['ocms_login_key']) {
				$dec = explode('--#--',decodePasswordLA($_COOKIE['ocms_login_key']));				
				$_POST['password'] = decodePasswordLA($dec[0]);
				$log = $this->isPasswordOk(($dec[1]),$_POST['password']);
			}
			
		}
		
		
		/**
		 * If not logged and private
		 * Then we hide the paragraphes and the Gabarit informations 
		 * Have to this differently for informations issued of plugins
		 */
		if(!$this->forceAllowed  && !$this->isLogged() && $this->privee) {
			$this->hideAll();	
		}
		
		if(akev($_GET,'laEdit')) {
				$this->hideAll();	
				
		}

		if(
				$this->forceAllowed 
			 || count(@array_intersect($_SESSION['ocms_login']['laValidGroupes'] , $this->Groupes)) 
			 || in_array($_SESSION['ocms_login']['laSqlUtilisateur'],$this->Utilisateurs)
			 ) {
			 	
			$this->allowed = true ;
			
		} else {			
					$this->hideAll();				
		}

		
		
	}
	
	function doLogout() {
	
		//session_destroy();
		$_SESSION['ocms_login'] = array();
		
		setcookie('ocms_login_remember','',time()-3600*24,'/',$this->domain);
		setcookie('ocms_login_key','',time()-3600*24,'/',$this->domain);
		$_COOKIE['ocms_login_remember'] = '';
		$_COOKIE['ocms_login_key'] ='';
		
		header('location:'.getUrlWithParams().'');
	
	}
	
	
	function genOutside() {
		
		if($this->isLogged() && akev($_REQUEST,'laEdit')) {
			$html .= '<div class="retour"><a href="'.geturlWithParams().'">'.t('la_retour').'</a></div>';
		}
	
		
		if($this->isLogged() && $this->privee) {
		

			return $html;
		}
		
	}
	
	
	/**
	 * Hide everything
	 *
	 */
	function hideAll() {	

		$this->allowed = false ;
		$this->rubrique->showParagraphes = false;
		$this->rubrique->hasBddInfo = false;		
	}
	
	
	/**
	 * Change le mot de passe de l'utilisateur
	 *
	 * @param string $pass
	 */
	function changePassword($pass) {
		
		if($this->isLogged()) {
			DoSql('UPDATE e_utilisateur SET utilisateur_pwd = "'.encodePasswordLA($pass).'" WHERE utilisateur_id = '.$_SESSION['ocms_login']['utilisateur_id']);
			$_SESSION['ocms_login']['utilisateur_pwd'] = encodePasswordLA($pass);
		}
		
	}
	
	
	/**
	 * Checks if current page can be accessed or not
	 *
	 */
	function isRubriquePrivee(){
		
		if($this->priveeChecked) {
			return $this->privee;
		}
		$rcrub = $this->rubrique->rubrique;	
		
		/**
		 * On regarde recursivement si on est dans une rubrique privee
		 */
		$i = 0;
		$tabIds = array();
		while( $i < 40) {
			
			$tabIds[] = $rcrub['rubrique_id'];
			
			if($rcrub['privee']) {
								
				$this->privee = true;
				
				if(strpos($rcrub['rubrique_gabarit_param'],"\n")) {
					$par = splitParams($rcrub['rubrique_gabarit_param'],"\n",'=');
				} else {
					$par = splitParams($rcrub['rubrique_gabarit_param'],";",'=');
				}
				
				$this->params = $par = array_merge($par,$this->sentParams);
				
				if(isTrue($par['canCreate'])) {
					$this->canCreate =true;
				}
				if($par['createType']) {
					$this->createType = trim($par['createType']);
					$f = new $this->createType($this->site,$this);
				}
				
				if($par['condition']) {
					$this->condition = $par['condition'];
				}
				
				
				if($par['createGroup']) {
					$this->createGroup = trim($par['createGroup']);
					$sql = 'SELECT * FROM e_groupe WHERE groupe_type LIKE '.sql($this->createGroup);
					$row = GetSingle($sql);
					if($row) {
						$this->Groupes[] = $row['groupe_id'];
					}
				}
				
				$sql = 'SELECT GC.* FROM e_groupe_contenu  AS GC
								WHERE 
									
									(
										( 
											fk_table = "s_rubrique" 
											AND fk_id = '.$rcrub['rubrique_id'].' 
										)
										
									)
									';
				
				$res = GetAll($sql);
				
				foreach($res as $row) {
					$this->Groupes[] = $row['fk_groupe_id'];
				}
				
				
				
				
				$sql = 'SELECT * FROM e_utilisateur_contenu WHERE fk_table = "s_rubrique" AND fk_id = '.$rcrub['rubrique_id'];
				$res = GetAll($sql);
				
				foreach($res as $row) {
					$this->Utilisateurs[] = $row['fk_utilisateur_id'];
				}
				
				if($this->condition) {

					if(!call_user_func($this->condition) && !$_REQUEST['signup']) {
						$this->allowed = true ;
						$this->forceAllowed = true ;
					}
				}
				
				break;
				
			}
			
			if($rcrub['fk_rubrique_id'] == '' || $rcrub['fk_rubrique_id'] == 'NULL') {
				break;
			}
			
			
			$sql = 'SELECT privee, fk_rubrique_id, rubrique_id, rubrique_gabarit_param FROM s_rubrique AS R WHERE rubrique_id = "'.$rcrub['fk_rubrique_id'].'"  LIMIT 0,1';
			$rcrub	= GetSingle($sql);
			
			$i++;
		}
		
		$this->priveeChecked = true;
		
		return $this->privee;
		
	}

	/**
	 * Verifie le code passé en paramètre
	 * Valide automatiquement !! pas d'admin !
	 * 
	 * @todo Gérer le cas Validation par email + admin
	 */
	function checkCode() {
		
		$code =	$this->getCode(getRowFromId('e_utilisateur',$_GET['laUid']));
		
	
		if($_GET['laCode'] == $code) {
			$sql = 'UPDATE e_utilisateur SET utilisateur_valide = 1 WHERE utilisateur_valide = 0 AND utilisateur_id = '.sql($_GET['laUid']);
			$res = DoSql($sql);
			//debug($sql.' - ');
		//	debug($res);

			if(Affected_Rows()) {
				addMessageInfo(t('la_valide_ok'));
				$f = new $this->createType($this->site,$this);
				if(method_exists($f,'doValid')) {
					$f->doValid($_GET['laUid']);
				}
			}
			
			// doValid
		} else {
			addMessageError(t('la_valide_error'));
		}
		
	}
	
	
	/**
	 * If you need to do something here ...
	 *
	 */
	function afterInit() {
		
		
		if($this->isLogged()) {
			
			if(false && $this->privee && @in_array($this->createGroup,$_SESSION['ocms_login']['laValidGroupes'])) {
				
				$this->site->g_headers->addCssText('
				#sousmenu {
					position:absolute;
					left:430px;top:82px;;
				}
				.modipro {
					left:250px !important;
				}
				
				');
				$this->site->plugins['topNav']->addAtEnd('menu2','<ul id="sousmenu" class="modipro"><li class="nb_1"><a href="'.getUrlWithparams(array('laEdit'=>1)).'">'.t('la_modifier_fiche').'</a></li><li class="nb_0"><a href="'.getUrlWithparams(array('laLogout'=>1)).'">'.t('la_deconnexion').'</a></li><ul>');
				
				if($this->createType == 'laSignUpDev') {
					$GLOBALS['gb_obj']->includeFile('genDeveloppeurs.php','plugins/developpeurs');
					$d = new genDeveloppeurs($this->site,array());
					$d->afterInit();
				}
			}
			
			//$this->site->plugins['leftCol']->addAfter('newsletter_link','connexion','<a href="'.getUrlWithparams(array('laLogout'=>1)).'">'.t('la_deconnexion').'</a>');
			
			// Do something			
			
			
			
		} else {
			
		 
			// Do Something
			
			
		}
		
	}


	/**
	 * Generating message and form if not logged
	 *
	 * @return unknown
	 */
	function gen() {
		

		$html = '&nbsp;';
		
		//	$html .= 'logged : '.$this->isLogged();
		
		if($this->erreur) {

			$html .= '<div class="erreur">'.$this->erreur.'</div>';

		}

	
		
		if(!$this->allowed && !$this->forceAllowed) {
			
			
			if($_REQUEST['signup'] && ($this->canCreate)) {
				
				//$f = new laSimpleSignUp($this->site);
				$f = new $this->createType($this->site,$this);
				$html .= $f->genForm();

				
			} else if($_REQUEST['oubli']) {
				
				$f = new simpleForm('','post','oubli_form');
				$f->add('text','',t('la_oubli_saisir'),'oubli_email','oubli_email',true);
				
				
				if($f->isSubmited() && $f->isValid()) {

					
					$fa = new $this->createType($this->site,$this);
					
					$row = $fa->getRowMdp($_POST['oubli_email']);
					
					
					if($row['utilisateur_pwd'] && $row['utilisateur_valide']) {
						
						$m = includeMail();
						
						$m->AddAddress($row['utilisateur_email']);
						
						$m->Subject = t('la_oubli_subject');
						$m->Body = tf('la_oubli_body',array('EMAIL'=>$row['utilisateur_login'],'PWD'=>decodePasswordLA($row['utilisateur_pwd'])));

						
						$m->Send();
						
						addMessageInfo(t('la_mdp_sent'));
						
					} else {
						addMessageError(t('la_oubli_bad'));
					}			
					
				}
				
				$f->add('submit',t('la_oubli_submit'));
				
				$html .= $f->gen();
				
				//la_oubli_saisir
				
			} else {
				

				$fa = new $this->createType($this->site,$this);
				
				if(method_exists($fa,'getScreen')) {
					
					return $fa->getScreen();
					
				} 
				else  {
					$html .= $this->getScreen();
				}
				

			}
			
		} else if( akev($_REQUEST,'laEdit') ) {
				
				
				$f = new $this->createType($this->site,$this);
				$html .= $f->genForm();
				
		}

		return $html;


	}

	function getScreen() {
	
	
		$f = new simpleForm('','post','login_form');
		//$f->add('html','');
		$f->add('text','',t('la_login'),'login','la_login',true);
		$f->add('password','',t('la_password'),'password','la_password',true);
		$f->add('checkbox','1',t('la_remember'),'la_remember','la_remember');
		$f->add('submit',t('la_submit'));

		/*
		
			$f->add('html','<a href="'.getUrlWithParams(array('²'=>1)).'">'.t('la_signup').'</a>');
		}
		*/
		$html .= '<div id="signup_bloc">'.t('la_login_'.$this->createType).'<br/><br />'.$f->gen();
		
		if($this->canCreate) {				
			
			$html .= '	<div class=" clearer"></div>
						<a href="'.getUrlWithParams(array('oubli'=>1)).'" class="oubli">'.t('la_oubli_mdp').'</a>
						<div class="sep"></div>
						<div id="signup_div">
						<p>'.t('la_signup_'.$this->createType).'</p>
						
						<a class="submit" href="'.getUrlWithParams(array('signup'=>1)).'" >'.t('la_signup').'</a>
						</div>
						</div>
						';
		} else {
			$html .= '</div>';
		}
		
		return $html;
				
	}

	/**
	 * Verifie dans la base de donnée si le login et le mot de passe sont corrects
	 *
	 * @param string $email
	 * @param string $pass
	 */
	function isPasswordOk($email,$pass) {


		$sql = 'SELECT * FROM e_utilisateur WHERE utilisateur_login LIKE '.sql($email).'';
		$res = GetSingle($sql);
		
		
		if(count($res)) {
			/**
			 * If user exists
			 */
			
			if(!$res['utilisateur_valide']) {		
				/**
				 * Account has not yet been validated
				 */
				addMessageError(t('la_not_valid'));
				return false;
			}
			
			

			/**
			 * Decrypting Password
			 */
			$pwd = $this->crypto->decrypt($res['utilisateur_pwd']);

			
			if($pass == $pwd ) {
				/**
				 * Password is OK
				 */
				$this->logged = true;
				$this->infos = $res;
				
			}
			
			else {
				
				
				$_SESSION = array();		
				/**
				 * Bad password
				 */
				if(!$GLOBALS['passwordPrinted']) {
					addMessageError(t('la_mauvais_pwd').'</p>');
					$GLOBALS['passwordPrinted'] = true;
				}
				return false;
				
			}
			
			if(!$_REQUEST['c_email']) {
				$_REQUEST['c_email'] = $_SESSION['ocms_login']['utilisateur_email'];
			}
			

			$this->row = $res;
			$this->utilisateur_id =  $res['utilisateur_id'];
			
			
			if($_POST['password'] ) {
			
				if($_POST['la_remember']) {
					setcookie('ocms_login_default_email',$email,time()+3600*24*30,'/',$this->domain);
					setcookie('ocms_login_remember','1',time()+3600*24*30,'/',$this->domain);	
					setcookie('ocms_login_key',encodePasswordLA(encodePasswordLA($_POST['password']).'--#--'.$email),time()+3600*24*30,'/',$this->domain);
				}
				/**
				 * Updating Last connection time
				 */
				DoSql('UPDATE e_utilisateur SET utilisateur_date_connexion = NOW() WHERE utilisateur_id = '.sql($res['utilisateur_id']));
				
				
				/**
				 * Setting session data
				 */
				
				$_SESSION['ocms_login']['utilisateur_id'] = $res['utilisateur_id'];
				$_SESSION['ocms_login']['utilisateur_login'] = $res['utilisateur_login'];
				$_SESSION['ocms_login']['utilisateur_email'] = $res['utilisateur_email'];
				$_SESSION['ocms_login']['utilisateur_pwd'] = $this->crypto->encrypt($_POST['password']);
				$_SESSION['ocms_login']['laSqlUtilisateur'] =  $res['utilisateur_id'];
				
		
				
				/**
				 * Selecting linked groups
				 */
				$sql = 'SELECT * FROM e_groupe AS G, e_utilisateur_groupe AS R
						WHERE R.fk_groupe_id = G.groupe_id
						AND R.fk_utilisateur_id = '.sql($res['utilisateur_id']).'
				';
				$res = GetAll($sql);
				
				
				
				/**
				 * Selecting recursivly groups
				 */
				foreach($res as $row) {
					$this->validGroups[] = $row['groupe_id'];
					$this->validTypes[] = $row['groupe_type'];
					while($row['fk_groupe_id'] != 0) {
						$row = GetSingle('SELECT * FROM e_groupe WHERE groupe_id = '.sql($row['fk_groupe_id']));
						$this->validGroups[] = $row['groupe_id'];
						$this->validTypes[] = $row['groupe_type'];
					}					
				}
				
				/**
				 * List of groups
				 */
				
				$_SESSION['ocms_login']['laSqlGroupes'] = implode(',',$this->validGroups);
				$_SESSION['ocms_login']['laValidTypes'] = $this->validTypes;
				$_SESSION['ocms_login']['laValidGroupes'] = $this->validGroups;
				
				return true;
			}

			return $this->logged;

		} else {
			
			/**
			 * User does no exist
			 */
			if(count($_POST)) {
				addMessageError( '<p>'.t('la_utilisateur_inexistant').'</p>');
			}
			return false;
		}


	}

	/**
	 * Verifie si l'utilisateur courrant appartient au groupe du
	 * type : $type
	 *
	 * @param string $type
	 * @return boolean
	 */
	function hasType($type) {
		
		if($this->isLogged() && @in_array($type,$_SESSION['ocms_login']['laValidTypes'])) {
			return true;			
		}
		return false;
		
	}

	/**
	 * Verifie si l'utilisateur est déjà connecté via la session
	 *
	 * @return unknown
	 */
	function isLogged() {

		if(!$this->logged && akev($_SESSION['ocms_login'],'utilisateur_login')) {
			
			$this->logged = $this->isPasswordOk($_SESSION['ocms_login']['utilisateur_login'],$this->crypto->decrypt($_SESSION['ocms_login']['utilisateur_pwd']));
		}		
		return $this->logged;

	}


	
	/**
	 * Insert a user into the database
	 *
	 * @param string $email
	 * @param string $pass
	 * @param int $autovalide (0 or 1)
	 * @return boolean
	 */
	function addUser($login,$email,$pass,$validtype=0) {	
				
		/**
		 * Validation automatique ?
		 */
		if($this->isLogged()) {
			$autovalide = 1;
		} else 
		if($validtype == 'none' || $validtype == 'auto'  ) {
			$autovalide = 1;
		}
		else {
			$autovalide=0;			
		}
		
		
		$utilisateur_id = '';
		
		/**
		 * Récupération des informations de connexion si déjà connecté
		 */		
		if($this->isLogged()) {
			$utilisateur_id =  $_SESSION['ocms_login']['utilisateur_id'];
			$login = $_SESSION['ocms_login']['utilisateur_login'];
		} 
		
		/**
		 * Mot de passe
		 */
		$pass = $this->crypto->encrypt($pass);
		
		/**
		 * Mise à jour
		 */
		$sql = 'REPLACE INTO e_utilisateur 
							(utilisateur_id,utilisateur_login,utilisateur_email,utilisateur_pwd,utilisateur_valide,utilisateur_lg) 
							VALUES 
							('.sql($utilisateur_id).','.sql($login).','.sql($email).','.sql($pass).','.sql($autovalide).','.sql(LG).')';
		$res = DoSql($sql);
		
		$_SESSION['ocms_login']['utilisateur_email'] = $email;


		/**
		 * utilisateur_id
		 */
		$uid = choose($utilisateur_id,InsertId());		
		
		/**
		 * Ajout automatique des groupes
		 */
		if($this->createGroup) {
			$this->addGroupForUser($this->createGroup,$uid);
		}
		
		if($this->isLogged()) {
			return $uid;
		}
		
		/**
		 * Validation par email (vérification de l'adresse)
		 */
		if($validtype == 'email' || $validtype == 'both') {
			addMessageInfo(t('la_validation_email'));
			$this->sendMailConfirm($uid);
		} else if($validtype == 'admin') {
			/**
			 * Validation par un admin
			 */
			addMessageInfo(t('la_validation_admin'));
			$this->sendMailAdmin($uid);
		}
		
		return $uid;
		
		
	}
	
	
	/**
	 * Envoi le mail de confirmation à un utilisateur pour validation de son inscription
	 *
	 * @param int $uid
	 */
	function sendMailConfirm($uid) {		
		
		$row = getRowFromId('e_utilisateur',$uid);
		
		$m = includeMail();
		$m->addAddress($row['utilisateur_email']);
		
		$m->Subject = t('la_subject_confirm');

		$url = getUrlWithParams(array('laUid'=>$uid,'laCode'=>$this->getCode($row)));
		
		if(strpos($url,'http:') === false ) {
			$url = getServerUrl().$url;
		}
		
		$m->Body = tf('la_body_confirm',array('URL'=>$url,'LOGIN'=>$row['utilisateur_login'],'PWD'=>decodePasswordLA($row['utilisateur_pwd'])));
		
		//debug($m->Send());
		$m->send();
		//print_r($m);
		
	}
	
	/**
	 * Retourne le code de validation pour un utilisateur
	 *
	 * @param array $row
	 * @return unknown
	 */
	function getCode($row) {
		return md5(($row['utilisateur_id']).'-'.crypto_key_ocms_login.'-'.$row['utilisateur_pwd']);
	}
	
	
	/**
	 * Envoi un mail à un admin pour qu'il valide ou non
	 * 
	 * @todo
	 *
	 */
	function sendMailAdmin() {
		
		
	}
	
	
	function addGroupForUser($group,$user) {

		if(!is_int($group)) {
			$group = getSingle('SELECT * FROM e_groupe WHERE groupe_type = '.sql($group));
			$group = $group['groupe_id'];
		}
		
		return DoSql('REPLACE INTO e_utilisateur_groupe (fk_groupe_id,fk_utilisateur_id) VALUES ('.$group.','.$user.') ');
	
	}

}


class laSimpleSignUp {
	
	
	/**
	 * simpleform
	 *
	 * @var simpleform
	 */
	var $f;
	
	/**
	 * gensite
	 *
	 * @var gensite
	 */
	var $site;
	
	/**
	 * ocms_loginfront
	 *
	 * @var ocms_loginfront
	 */
	var $la;
	
	
	function __construct($site,$la) {
		
		$this->la = $la;
		$this->site = $site;
		
		if($this->la->isLogged() && $this->table) {			
			$this->curRow = getSingle('SELECT * FROM '.$this->table.' WHERE fk_utilisateur_id = '.sql($this->la->infos['utilisateur_id']));
			$this->curId = $this->curRow[getPrimaryKey($this->table)];
			global $tablerel_reverse;
			
			genTableRelReverse();
			
			if(is_array($tablerel_reverse[$this->table])) {	
					
				foreach($tablerel_reverse[$this->table] as $v) {
					
					$t = new tablerel($v['tablerel'],$this->table,$this->curId);
					$this->curRow[$v['tablerel']] = $t->getSelectedIds();
				
				}
			}
			
		} else {
			$this->curRow = $_REQUEST;		
		}
		
		$this->prepareForm();		
		
		$this->showform = true;
		$this->isSubmitted = $this->f->isSubmited();
		$this->isValid = $this->f->isValid();
		
		if($this->f->isSubmited() && $this->isValid() && $this->isValid  ) {
			$this->showform = false;
			$this->record();
		}
						
	}
	
	
	/**
	 * Creates the simple form object
	 *
	 */
	function prepareForm() {
		
		$this->f = new simpleForm('','post','signup');
		
		$this->f->add('email_conf','',t('la_email'),'la_email','la_email',true);
				
		$this->f->add('submit',t('la_signup'));
		
		
	}
	
	/**
	 * Returns HTML code o the form
	 *
	 * @return unknown
	 */
	function genForm() {
		
		
		if($this->showform ) {

			return $html .= $this->f->gen();
		}
		
	}
	

	/**
	 * Records entry into Database
	 *
	 */
	function record ($login=false,$email=false,$password=false,$autoValide=0) {
		
		// Insert into other table

		// Insert user
		if(!$email) {
			$email = $_POST['la_email'] ;
		}
		
		if(!$login) {
			$login = $_POST['la_login'];
		}
		
		
		if(!$password) {
			if($_POST['la_password']) {
				$password = $_POST['la_password'];
			} else if($this->la->isLogged()) {
				$password = decodePasswordLA($_SESSION['ocms_login']['utilisateur_pwd']);
			} else {
				$password = mkPasswdLen();
			}
		}
		
		//$autoValide = $this->la->params['validation'] == 'auto' ? 1 : 0;
		
		
		return $this->la->addUser($login,$email,$password,$this->la->params['validation']);
		
	}
	
	/**
	 * Returns login available
	 *
	 * @param string $base
	 */
	function getLogin($base) {
		
		$baseLog = $base = strtolower(substr($base,0,strpos($base,'@')));
		$nb=1;
		while(true) {			
			$sql = 'SELECT * FROM e_utilisateur WHERE utilisateur_login LIKE "'.$baseLog.'"';
			$row = GetAll($sql);
			if(!count($row)) {
				break;				
			} else {
				$baseLog = $base.'-'.$nb;
			}
			$nb++;
			
		}
		return $baseLog;
		
	}
	
	
	function isValid() {
		
		if($_POST['la_password'] ) {
			if($_POST['la_password'] != $_POST['la_password_conf']) {
				addMessageError(t('la_password_error'));
				return false;
			}
		}
		
		if($this->la->isLogged()) {
			return true;
		}
		
		$sql = 'SELECT * FROM e_utilisateur WHERE utilisateur_login LIKE '.sql($_REQUEST['la_login']);
		$row = GetSingle($sql);
		
		if(count($row)) {
			$this->addError(t('la_login_taken'));
			return false;
		} else {
			return true;
		}
		
	}
	
	
	function addError($str) {
		addMessageError($str);
	}
	
	
	/**
	 * Enter description here...
	 *
	 * @param simpleform $f
	 */
	function addBaseField($f) {
		
		$f->add('fieldset',t('la_base'));
		if($this->la->isLogged()) {
			$f->add('html','<strong class="la_login">'.t('la_login').' : '.$_SESSION['ocms_login']['utilisateur_login'].'</strong>');
			$f->add('email',$_SESSION['ocms_login']['utilisateur_email'],t('la_email'),'la_email','la_email',true);			
			$f->add('html',t('la_to_change_pwd'));
			$f->add('password','',t('la_password'),'la_password','la_password',true);
			$f->add('password','',t('la_password_conf'),'la_password_conf','la_password_conf',true);
		} else {
			$f->add('text','',t('la_login_form'),'la_login','la_login',true);
			$f->add('email','',t('la_email'),'la_email','la_email',true);
			
			
			$f->add('password','',t('la_password'),'la_password','la_password',true);
			$f->add('password','',t('la_password_conf'),'la_password_conf','la_password_conf',true);
		}
		$f->add('endfieldset');

	}
	
	
	function sendMailConf() {
		
		$m = includeMail();
		
		$m->AddAddress($_POST['utilisateur_email']);
		$m->Subject = t('la_email_conf_subject');
		$m->Body = tf('la_email_conf_body',array('PASSWORD'=>$password,'LOGIN'=>$login,'URL'=>getServerUrl().getUrlWithParams()));
		$m->Send();
		
	}
	
	
}






function laFrom() {
	
	return ' , e_groupe_contenu AS laEGC, e_utilisateur_contenu AS laEUC ';
	
}




function laWhereId($table,$id) {

	return ' AND
	         ( 
			 ( 
				laEGC.fk_table = '.sql($table).' 
				AND laEGC.fk_id = '.sql($id).' 
				AND laEGC.fk_groupe_id IN ('.$_SESSION['ocms_login']['laSqlGroupes'].')
			 ) 
			 OR
			 (
			 	laEUC.fk_table = '.sql($table).' 
				AND laEUC.fk_id = '.sql($id).' 
				AND laEUC.fk_groupe_id IN ('.$_SESSION['ocms_login']['laSqlGroupes'].')
			 )
			 )
			 
			 ';
	
}

function laWhere($table,$alias = '') {

	if($alias) {
		$alias = $alias.'.';
	}
	
	return ' AND
	         ( 
	         
	         '.$alias.'privee = 0
	         OR 
			 ( 
				laEGC.fk_table = '.sql($table).' 
				AND laEGC.fk_id = '.$alias.getPrimaryKey($table).'  
				AND laEGC.fk_groupe_id IN ('.$_SESSION['ocms_login']['laSqlGroupes'].')
			 ) 
			 OR
			 (
			 	laEUC.fk_table = '.sql($table).' 
				AND laEUC.fk_id = '.$alias.getPrimaryKey($table).'  
				AND laEUC.fk_utilisateur_id = ('.$_SESSION['ocms_login']['laSqlUtilisateur'].')
			 )
			 )
			 
			 ';
	
}




function laJoin($table,$alias='') {
	
	if($alias) {
		$alias = $alias.'.';
	}

	return ' 
			LEFT JOIN e_groupe_contenu AS laEGC 
				ON 
	         ( 
				 ( 
					laEGC.fk_table = '.sql($table).' 
					AND laEGC.fk_id = '.$alias.getPrimaryKey($table).'  
					AND laEGC.fk_groupe_id IN ('.$_SESSION['ocms_login']['laSqlGroupes'].')
				 ) 
			 )
			 LEFT JOIN e_utilisateur_contenu AS laEUC ON
		     (
				 (
				 	laEUC.fk_table = '.sql($table).' 
					AND laEUC.fk_id = '.$alias.getPrimaryKey($table).'  
					AND laEUC.fk_utilisateur_id = ('.$_SESSION['ocms_login']['laSqlUtilisateur'].')
				 )
			 )
			 
			 ';
}


function laWhere2($alias='') {
	if($alias) {
		$alias = $alias.'.';
	}
	
	return ' AND '.$alias.'privee = 0 ';
}


if(! akev($_REQUEST,'ocms_login') ||  !$_SESSION['ocms_login']['laSqlUtilisateur'] ) {
	$_SESSION['ocms_login']['laSqlGroupes'] = "0";
	$_SESSION['ocms_login']['laSqlUtilisateur'] = "0";
}




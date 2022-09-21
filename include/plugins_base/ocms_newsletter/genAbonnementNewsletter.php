<?php

class genAbonnementNewsletter extends ocmsGen {

    private $prg, $email, $code, $action, $form, $sql, $result, $rand, $to, $from, $subject, $content, $headers, $liste_diffusion;

    function afterInit() {

        $this->site->g_rubrique->showParagraphes = false;
        $this->site->g_headers->addCss('newsletter.css');
        $this->site->g_headers->addCss('contact.css');

        // Initialisation du type de demande (abonnement ou désabonnement)
        $this->initTypeForm();
        
        // Feuille de style
        $this->site->g_headers->addCss('css/ocms_newsletter/ocms_newsletter.css');

        //On récupère les valeurs des champs le cas échéant.
        $this->email = mb_strtolower($_REQUEST['email'], 'UTF-8');
        $this->liste = array($_REQUEST['sel_liste']);
        $this->code = $_REQUEST['code'];
        $this->id_user = $_REQUEST['iduser'];
        $this->action = $_REQUEST['action'];

        //Construction du formulaire
        $this->buildForm();

        // On valide le formulaire 
        $this->form->isValid();
    }
    
    /**
     * Initialise le type de formulaire
     */
    function initTypeForm(){
        
        $this->formType = choose($_GET['action'],$_GET['form'],'abonnement');
        
    }

    function htmlToNewsletter($str, $color) {

        $return = str_replace("<p", '<p style="margin: 0px; padding: 0px;" ', $str);
        $return = str_replace("<a", '<a style="color: #' . $color . '; text-decoration: none;" ', $return);

        return $return;
    }

    function niceTextDateNewsletter($date, $jour = false) {

        global $lg;
        $d = explode(" ", $date);
        $d = $d[0];
        if (!$d[0]) {
            return;
        }
        $d = explode("-", $date);

        $type = '%B/%Y';
        if ($jour) {
            $type = '%A %e' . $type;
        }
        @setlocale($GLOBALS['CURLOCALE']);
        if ($lg == 'uk')
            $s = strftimeloc($type, mktime(0, 0, 0, $d[1], $d[2], $d[0]));
        else
            $s = strftimeloc($type, mktime(0, 0, 0, $d[1], $d[2], $d[0]));

        if ($GLOBALS['isWindows']) {
            $s = utf8_encode($s);
        }

        return $s;
    }

    function gen() {

        global $_Gconfig;

        $tpl = new genTemplate();
        $tpl->loadTemplate('tpl.abonnement', 'plugins/ocms_newsletter/tpl');
       
        // Titre
        $tpl->titre = t('ocms_newsletter_titre_form_'.$this->formType);

        //Menu des actions possibles.
        /*         * *********************************************** */
        $tpl->newsletter_abonnement_url = getUrlWithParams(array('form' => 'abonnement'));
        $tpl->newsletter_abonnement_lbl = t('ocms_newsletter_abonnement_lbl');

        $tpl->newsletter_desabonnement_url = getUrlWithParams(array('form' => 'desabonnement'));
        $tpl->newsletter_desabonnement_lbl = t('ocms_newsletter_desabonnement_lbl');

        /*
          $tpl->newsletter_modification_url = getUrlWithParams(array('form' => 'modification'));
          $tpl->newsletter_modification_lbl = t('ocms_newsletter_modification_lbl');
         */

        //On récupère le titre du formulaire.
        /*         * *********************************************** */
        if ($this->formType) {
            $tpl->form_titre = t('ocms_newsletter_' . $this->formType . '_lbl');

            if ($this->formType == 'desabonnement') {
                $tpl->nav_class_desabonnement = 'action_selected';
                $tpl->nav_class_abonnement = '';
            } else {
                $tpl->nav_class_abonnement = 'action_selected';
                $tpl->nav_class_deabonnement = '';
            }
        } else {
            $tpl->form_titre = t('ocms_newsletter_abonnement_lbl');
            $tpl->nav_class_abonnement = 'action_selected';
            $tpl->nav_class_deabonnement = '';
        }


        //En fonction du type d'action à effectuer.
        switch ($_REQUEST['action']) {

            /*             * *********************************************************************************** */
            /* Abonnement (Inscription à la newsletter) */
            /*             * *********************************************************************************** */
            case 'abonnement':

                // Demande de validation d'inscription.
                if ($_REQUEST['code'] && $_REQUEST['iduser']) {

                    //On verifie la correspondance CODE <---> ID_USER et on valide l'utilisateur.
                    $this->validUserMail();
                    $tpl->form_newsletter = '<span id="messageNewsletterOK">' . t('ocms_newsletter_validation_ok') . '</span>';

                    $sql = 'SELECT * FROM ocms_newsletter_user WHERE user_id = ' . sql($this->id_user);
                    $user = GetSingle($sql);
                } 
                
                elseif ($_REQUEST['email']) {

                    if ((!$_Gconfig['newsletter']['use_list'] && CheckEmail($this->email)) || ($_Gconfig['newsletter']['use_list'] && CheckEmail($this->email) && is_array($this->liste))) {

                        //On verifie que l'e-mail de l'utilisateur n'est pas deja enregistrer.
                        if ($this->isAlreadyRegistered() == 0) {

                            $this->insertUser();
                            $this->sendConfirmationEmail();
                            $tpl->form_newsletter = '<span id="messageNewsletterOK">' . t('ocms_newsletter_enregistrement_ok') . '</span>';
                        } elseif ($this->isAlreadyRegistered(true) == 0) {

                            //On lui envoie l'e-mail de <confirmation></confirmation>.
                            $this->sendConfirmationEmail();
                            $tpl->form_newsletter = '<span id="messageNewsletterOK">' . t('ocms_newsletter_enregistrement_ok') . '</span>';
                        } else {

                            //get user id 
                            $sql = 'SELECT user_id
                                    FROM ocms_newsletter_user
                                    WHERE user_email = ' . sql($this->email);

                            $user = GetSingle($sql);




                            $html = '<span id="messageNewsletterErreur">' . t('ocms_newsletter_already_registred') . '</span>';
                            $html.= $this->genForm();
                            $tpl->form_newsletter = $html;
                        }
                    } else {

                        $msg = t('ocms_newsletter_mail_pas_valide');

                        if ($_Gconfig['newsletter']['use_list'] && !is_array($this->liste)) {
                            $msg = t('choisir_liste');
                        }


                        $html = '<span id="messageNewsletterErreur">' . $msg . '</span>';
                        $html.= $this->genForm();
                        $tpl->form_newsletter = $html;
                    }
                } else {

                    //p(genMessage(t('remplir_champ_obligatoire')));
                    $html = '<span id="messageNewsletterErreur">' . t('remplir_champ_obligatoire') . '</span>';
                    $html.= $this->genForm();
                    $tpl->form_newsletter = $html;
                }
                break;


            /*             * *********************************************************************************** */
            /* Désabonnement (désinscription à la newsletter) */
            /*             * *********************************************************************************** */
            case 'desabonnement':

                if ($_REQUEST['code'] && $_REQUEST['iduser']) {

                    // On verifie que l'e-mail et le code corresponde et on supprime l'utilisateur.
                    $this->deleteUser();
                    $tpl->form_newsletter = '<span id="messageNewsletterOK">' . t('ocms_newsletter_delete_ok') . '</span>';

                    $sql = "SELECT * FROM ocms_newsletter_user WHERE user_id = " . sql($this->id_user);
                    $user = GetSingle($sql);
                } else if ($_REQUEST['email']) {

                    if (CheckEmail($this->email)) {

                        // On lui envoie l'e-mail de confirmation.
                        if ($this->isAlreadyRegistered() != 0) {
                            $this->sendConfirmationEmail('desabonnement');
                            $tpl->form_newsletter = '<span id="messageNewsletterOK">' . t('ocms_newsletter_desenregistrement_ok') . '</span>';
                        } else {
                            //p(genMessage(t('ocms_newsletter_unknowocms_newsletter_user')));
                            $tpl->form_newsletter = '<span id="messageNewsletterErreur">' . t('ocms_newsletter_unknowocms_newsletter_user') . '</span>';
                            $tpl->form_newsletter .= $this->genForm();
                        }
                    }
                } else {
                    //p(genMessage(t('mail_pas_valide')));
                    $tpl->form_newsletter = '<span id="messageNewsletterErreur">' . t('ocms_newsletter_mail_pas_valide') . '</span>';
                    $tpl->form_newsletter .= $this->genForm();
                }

                break;


            /*             * *********************************************************************************** */
            /* Modification du compte. */
            /*             * *********************************************************************************** */
            case 'modification':

                if ($_REQUEST['code'] && $_REQUEST['user_infos']) {

                    // On verifie que l'e-mail et le code corresponde et on modifie l'utilisateur.
                    $this->updateUser();
                    $tpl->form_newsletter = '<span id="messageNewsletterOK">' . t('ocms_newsletter_modif_ok') . '</span>';
                } else if ($_REQUEST['emailmodif'] && $_REQUEST['email']) {
                    $this->sendConfirmationEmail('modification');
                    $tpl->form_newsletter = '<span id="messageNewsletterOK">' . t('ocms_newsletter_send_confirm_modif') . '</span>';
                } else {

                    // On vérifie que l'email existe bien.
                    if ($this->isAlreadyRegistered() != 0) {
                        $tpl->form_newsletter = '<span id="messageNewsletterCompte">' . $this->email . '</span>';
                    } else {
                        $tpl->form_newsletter = '<span id="messageNewsletterErreur">' . t('ocms_newsletter_unknowocms_newsletter_user') . '</span>';
                    }
                    $tpl->form_newsletter .= $this->genForm();
                }

                break;


            /*             * *********************************************************************************** */
            /* Par défaut, on affiche le formulaire */
            /*             * *********************************************************************************** */
            default:
                $tpl->form_newsletter = $this->genForm();
                break;
        }

        return $tpl->gen();
    }

    private function validUserMail() {
        
        $sql = 'SELECT * 
                FROM ocms_newsletter_user 
                WHERE user_code = "' . $this->code . '" 
                AND user_id = ' . $this->id_user;
        
        $result = GetAll($sql);

        if (count($result) != 0) {

            $sql = 'UPDATE ocms_newsletter_user
                    SET user_checked = 1
                    WHERE user_id = ' . $this->id_user;
            
            doSQL($sql);

            $html = t('ocms_newsletter_confirm_ok');
            return $html;
        } else {
            $html = t('ocms_newsletter_link_not_valid');
            return $html;
        }
    }

    /**
     * Renvoi l'ID d'un utilisateur à partir de son email
     *
     * @return unknown
     */
    private function getUserId() {

        $sql = 'SELECT user_id
                FROM ocms_newsletter_user
                WHERE user_email = ' . sql($this->email);

        $idUser = GetSingle($sql);
        return $idUser['user_id'];
    }

    /**
     * Envoie un email de confirmation.
     *
     * @param unknown_type $action
     */
    public function sendConfirmationEmail($action = 'abonnement') {

        $baseurl = 'http://' . $_SERVER['SERVER_NAME'];
        $mail = includeMail();

        $mail->From = t('ocms_newsletter_mail_from');
        $mail->FromName = t('ocms_newsletter_mail_fromname');

        $mail->CharSet = "UTF-8";
        $mail->addAddress($this->email);


        /* Abonnement */
        if ($action == 'abonnement') {

            $texte = 'ocms_newsletter_confirm_mail_abo';
            $mail->Subject = t('ocms_newsletter_mail_title_abo');
            $this->code = md5(mt_rand());

            $sql = 'UPDATE ocms_newsletter_user
                    SET user_code = "' . $this->code . '"
                    WHERE user_email = "' . $this->email . '"';

            doSQL($sql);
            $html = t('ocms_newsletter_send_confirm_abo');

            //debug('ID USER :'. $this->getUserId( ));return ;
            $content = tf($texte, array('URL' => getServerUrl() . $this->site->g_url->getUrlWithParams(array('form' => $this->formType,
                    'code' => $this->code,
                    'iduser' => $this->getUserId(),
                    'action' => $action))));
        }

        /* Désabonnement */ else if ($action == 'desabonnement' && $this->getUserId()) {

            $texte = 'ocms_newsletter_confirm_mail_desabo';
            $mail->Subject = t('ocms_newsletter_mail_title_desabo');
            $this->code = md5(mt_rand());

            $sql = 'UPDATE ocms_newsletter_user
				SET user_code = "' . $this->code . '"
				WHERE user_email = "' . $this->email . '"';

            doSQL($sql);
            $html = t('ocms_newsletter_send_confirm_desabo');

            //debug('ID USER :'. $this->getUserId( ));return ;
            $content = tf($texte, array('URL' => getServerUrl() . $this->site->g_url->getUrlWithParams(array('form' => $this->formType,
                    'code' => $this->code,
                    'iduser' => $this->getUserId(),
                    'action' => $action))));
        }

        /* Modification */ else if ($action == 'modification' && $this->getUserId()) {

            $texte = 'ocms_newsletter_confirm_mail_modif';
            $mail->Subject = t('ocms_newsletter_mail_title_modif');
            $this->code = md5(mt_rand());

            $sql = 'UPDATE ocms_newsletter_user
				SET user_code = "' . $this->code . '"
				WHERE user_email = "' . $this->email . '"';

            doSQL($sql);
            $html = t('ocms_newsletter_send_confirm_modif');

            //On récupère l'ensemble des éléments à modifier.
            $user_infos = array();

            $user_infos['user_id'] = $this->getUserId();
            $user_infos['user_nom'] = $this->nom;
            $user_infos['user_prenom'] = $this->prenom;
            $user_infos['liste'] = $this->liste;


            //debug('ID USER :'. $this->getUserId( ));return ;
            $content = tf($texte, array('URL' => getServerUrl() . $this->site->g_url->getUrlWithParams(array('form' => $this->formType,
                    'code' => $this->code,
                    'user_infos' => urlencode(serialize($user_infos)),
                    'action' => $action))));
        }

        $html .= $content;

        $mail->Body = $content;
        $mail->IsHTML(true);
        $mail->AltBody = '';
        $mail->Send();

        //print $mail->ErrorInfo;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $active
     * @return unknown
     */
    private function isAlreadyRegistered($active = false) {

        if ($active == true) {
            $active = ' AND user_checked = 1';
        } else {
            $active = '';
        }

        $sql = 'SELECT user_email
                    FROM ocms_newsletter_user
                WHERE user_email = "' . $this->email . '"' . $active;
        $result = GetAll($sql);

        $res = count($result);

        return $res;
    }

    /**
     * Supprime un utilisateur.
     *
     * @return unknown
     */
    private function deleteUser() {

        $sql = 'SELECT *
				FROM ocms_newsletter_user 
				WHERE user_code = ' . sql($this->code) . ' 
				AND user_id = ' . sql($this->id_user);
        $result = GetAll($sql);

        if (count($result) != 0) {
            
            $row = new row('ocms_newsletter_user', $this->id_user);
            
            // Email à l'admin
            $mail = includeMail();
            $mail->From = t('ocms_newsletter_mail_from');
            $mail->FromName = t('ocms_newsletter_mail_fromname');
            $mail->CharSet = "UTF-8";
            $mail->addAddress(t('ocms_newsletter_admin_email'));
            $mail->Subject = 'Utilisateur désinscrit de la newsletter : '.$row->user_email;
            $mail->Body = 'Utilisateur désinscrit de la newsletter : '.$row->user_email;
            $mail->IsHTML(true);
            $mail->AltBody = '';
            $res = $mail->Send();

            //On supprime l'abonnement si il existe.
            $sql = 'DELETE FROM ocms_newsletter_user WHERE user_id = ' . sql($this->id_user);
            doSQL($sql);

            //On supprime également les relations qui peuvent exister avec une liste de diffusion.
            $sql = "DELETE FROM ocms_newsletter_r_user_liste WHERE fk_user_id = " . sql($this->id_user);
            doSQL($sql);

            $html = t('ocms_newsletter_delete_ok');

            return $html;
        } else {
            $html = t('ocms_newsletter_link_not_valid');
            return $html;
        }
    }

    /**
     * Insère un utilisateur.
     *
     * @return unknown
     */
    private function insertUser() {
        global $_Gconfig;
        //On genere un code de validation aleatoire.
        $this->code = md5(mt_rand());

        //On ajoute l'utilisateur dans la base.
        $sql = 'INSERT INTO ocms_newsletter_user(
		
					user_email ,
					user_code 
			
				)VALUES(

					' . sql($this->email) . ', 
					"' . $this->code . '" 
				);
		';

        $res[] = doSQL($sql);
        $user_id = InsertId();


        //On ajoute les relations avec les groupes auxquels on inscrit la personne.
        if ($_Gconfig['newsletter']['use_list']) {

            foreach ($this->liste as $liste) {



                $sqlListe = 'INSERT INTO ocms_newsletter_r_user_liste(
						fk_liste_id ,
						fk_user_id
					)VALUES(
						' . sql($liste) . ', 
						' . sql($user_id) . '
					);
				';
                $res[] = doSQL($sqlListe);
            }
        } else {
            $res = array();
        }

        $row = new row('ocms_newsletter_user', $user_id);

        // Email à l'admin
        $mail = includeMail();
        $mail->From = t('ocms_newsletter_mail_from');
        $mail->FromName = t('ocms_newsletter_mail_fromname');
        $mail->CharSet = "UTF-8";
        $mail->addAddress(t('ocms_newsletter_admin_email'));
        $mail->Subject = 'Utilisateur inscrit de la newsletter : '.$row->user_email;
        $mail->Body = 'Utilisateur inscrit de la newsletter : '.$row->user_email;
        $mail->IsHTML(true);
        $mail->AltBody = '';
        $res = $mail->Send();

        return in_array(0, $res);
    }

    /*
      private function linkUserToDiffList($user_id){

      foreach($this->dif)

      $sqlListe = 'INSERT INTO ocms_newsletter_r_user_liste(
      fk_liste_id ,
      fk_user_id
      )VALUES(
      '.sql(1).',
      '.sql($user_id).'
      );
      ';
      $res[] = doSQL($sqlListe);
      }
     */


    /**
     * On modifie un utilisateur.
     *
     * @return unknown

      private function updateUser(){

      //on récupère les nouvelles informations sur l'utilisateur.
      $user_infos = unserialize(urldecode($_GET['user_infos']));

      $sql = 'SELECT *
      FROM ocms_newsletter_user
      WHERE user_code = ' .sql($this->code).'
      AND user_id = ' .sql($user_infos['user_id']);
      $result = GetAll($sql);

      if(count($result) != 0){

      //On modifie les infos de l'utilisateur.
      $sql = 'UPDATE ocms_newsletter_user SET

      user_nom = '.sql($user_infos['user_nom']).',
      user_prenom = '.sql($user_infos['user_prenom']).',

      WHERE user_id = '.sql($user_infos['user_id']);
      doSQL($sql);


      //On supprime les anciennes relations qui peuvent exister.
      $sql = "DELETE FROM ocms_newsletter_r_user_liste WHERE fk_user_id = ".sql($user_infos['user_id']);
      doSQL($sql);

      //On ajoute les nouvelles relations.
      foreach($user_infos['liste'] as $liste){

      $sqlListe = 'INSERT INTO ocms_newsletter_r_user_liste(
      fk_liste_id ,
      fk_user_id
      )VALUES(
      '.sql($liste).',
      '.sql($user_infos['user_id']).'
      );
      ';
      doSQL($sqlListe);
      }

      $html = t('ocms_newsletter_modif_ok');
      return $html;

      }else{
      $html = t('ocms_newsletter_link_not_valid');
      return $html ;
      }
      }

     */

    /**
     * Génère le formulaire
     *
     * @return unknown
     */
    private function genForm() {

        $html = '';
        $html .= $this->form->gen();
        return $html;
    }

    /**
     * Construit le formulaire d'inscription
     */
    private function buildForm() {

        global $_Gconfig;

        $params = array('form' => $this->formType);
        if($this->action) $params[] = array('action' => $this->action);

        $this->form = new simpleForm(getUrlWithParams($params), 'post', 'newsletter_form');
        $this->form->postLabel = '';
        $this->form->neededSymbol = '*';
        $this->form->showNeed = false;

        // On récupère les types d'actions qui peuvent avoir lieu lors de la validation du formulaire. UTILE ???
        $actions = array(
            'abonnement' => t('ocms_newsletter_sinscrire'),
            'desabonnement' => t('ocms_newsletter_se_desinscrire')
        );

        /*         * ***************************************************************** */
        //Si on est en mode édition.
        /*         * ***************************************************************** */

        //Après avoir indiqué une adresse mail à modifier.
        if ($this->action == 'modification' && $this->isAlreadyRegistered() == 0) {
            $this->action = '';
        } else if ($this->action == 'modification') {

            $this->form->add('hidden', 1, '', 'emailmodif', 'emailmodif', true);
            $this->form->add('hidden', $this->email, t('ocms_newsletter_email'), 'email', 'email', true);

            //on commence par récupérer toutes les informations concernant le compte à modifier.
            $sql = "SELECT * FROM ocms_newsletter_user WHERE user_email LIKE " . sql($this->email) . " LIMIT 1";
            $user = GetSingle($sql);
            $user = new row('ocms_newsletter_user', $user);

            $user_nom = $user->user_nom;
            $user_prenom = $user->user_prenom;


            //On récupère les listes de diffusions associées.
            $sqlListe = "SELECT * FROM ocms_newsletter_r_user_liste WHERE fk_user_id = " . sql($user->user_id);
            $resListe = GetAll($sqlListe);

            $select_listes_sel = array();
            foreach ($resListe as $liste) {
                $liste = new row('ocms_newsletter_r_user_liste', $liste);
                $select_listes_sel[] = $liste->fk_liste_id;
            }
        }


        /************************************************************* */
        /* Champs du formulaire.
        /************************************************************* */
                
        if ((!$this->formType || $this->formType == 'abonnement' || $this->action == 'modification') && $_Gconfig['newsletter']['use_list']) {
            
            // Listes de diffusion
            $this->form->add('select', $this->getTabListes(), t('ocms_newsletter_txt_vous_etes'), 'sel_liste', 'sel_liste', true, $_REQUEST['sel_liste'], false, 'test2');
        }

        if ($this->action != 'modification') {

            // Champs email
            $this->form->add('email', '', t('ocms_newsletter_email'), 'email', 'email', true);
        }

        //En fonction du formulaire à envoyer, on passe l'action a effectuer en paramètre.
        if ($this->formType) {
            $this->form->add('hidden', $this->formType, t('ocms_newsletter_action'), 'action', 'action', true);
        } else {
            $this->form->add('hidden', 'abonnement', t('ocms_newsletter_action'), 'action', 'action', true);
        }

        // Bouton de soumission du formulaire
        $this->form->add('submit', t('ocms_newsletter_submit'), '', 'newsletter_submit', 'newsletter_submit', true);
    }

    //Un petit picto pour l'arborescence.
    function ocms_getPicto() {
        return ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_ARBO_SIZE . '/apps/internet-mail.png';
    }

    /**
     * Retourne le tableau des listes de diffusion
     */
    function getTabListes() {

        // Tableau résultat
        $tabListes = array();

        // Sélection des listes de diffusion
        $listes = $this->getListesDiffusion();

        if (count($listes)) {
            foreach ($listes as $liste) {
                
                $objList = new row('ocms_newsletter_liste', $liste);

                $tabListes[] = array(
                    'label' => $objList->liste_titre,
                    'value' => $objList->liste_id
                );
            }
        }
        
        return $tabListes;
    }

    /**
     * Sélection des listes de diffusion publiques
     */
    function getListesDiffusion() {

        $sqlListe = "SELECT * "
                . " FROM ocms_newsletter_liste "
                . " WHERE liste_visible_front = 1";

        return GetAll($sqlListe);
    }

}

?>
<?php


class ocms_sharedloginFront extends ocmsPlugin {
    
    /**
     * Mot de passe de la rubrique (en clair)
     * */
    private $password = false;
    
    /**
     * ID de la Rubrique qui définit le mot de passe (peut etre un parent)
     * */
    private $rubIdWithPassword;
    
    /**
     * Est-ce que l'on doit afficher le formulaire ?
     * */
    private $showForm = false;
    
    /**
     * Est-ce que l'utilisateur a soumis un mauvais mot de passe
     * via le formulaire ?
     * */
    private $badPassword = false;
    
    public function afterInit() {
        
        $this->rubrique = $this->site->g_rubrique;
        
        /**
         *  Si la rubrique est protégée
         * */
        if($this->isCurrentRubriquePasswordProtected()) {
            /**
             * On désactive le cache agressif que ce soit dans un cas
             * ou dans l'autre car tout dépend de la session
             * */
            global $agressiveUseCache;
            $agressiveUseCache = false;
            
            /**
             * Et s'il n'a pas le mot de passe ...
             * */
            if(!$this->userHasPassword()) {
                $this->showForm = true;
                $this->hideAll();
            }
           
        }
        
    }
    
    /**
     * Le formulaire si nécessaire
     * */
    public function gen() {
        if($this->showForm) {
            return $this->genForm();   
        }
    }
    
    /**
     * Création du formulaire
     * */
    public function genForm() {
        
        $f = new simpleForm('','post','ocms_sharedlogin_form');
        
        /**
         * Ne pas afficher le blabla intuile sur les champs obligatoires ...
         * */
        $f->simpleformNeeded ='';
        
        $f->add('html','<h1>'.t('ocms_sharedlogin_title').'</h1>');
        
        /**
         * Si l'utilisateur vient de soummettre un mauvais mot de passe
         * */
        if($this->badPassword) {
            $f->add('html','<div class="alert danger">'.t('ocms_sharedlogin_bad_password').'</div>');
        }
        
        $f->add('password','',t('ocms_sharedlogin_input'),'ocms_sharedlogin_input','ocms_sharedlogin_input',true);
        
        $f->add('submit',t('ocms_sharedlogin_submit'));
        
        return $f->gen();
    }
    
    /**
     * Détermine si l'utilsateur courant a donné le bon mot de passe
     * Soit par session
     * Soit par le formulaire
     * */
    public function userHasPassword() {
        /**
         * Cette rubrique est-elle protégée ?
         * Normalement on appelle pas cette méthode sinon mais bon ...
         * */
        if($this->password) {
            /**
             * Est-il présent dans $_POST si on vient du formulaire ?
             * */
             if(!empty($_POST['ocms_sharedlogin_input'])) {
                if($_POST['ocms_sharedlogin_input'] == $this->password) {
                    /**
                     * S'il est correct on le stock dans la session
                     * Dans une clef dédiée à la rubrique sur laquelle 
                     * il est défini pour pouvoir avoir plusieurs 
                     * rubriques protégées différentes sur le site
                     * */
                    $_SESSION['ocms_sharedlogin'][$this->rubIdWithPassword] = $_POST['ocms_sharedlogin_input'];
                    return true;
                } else {
                    $this->badPassword = true;
                    return false;
                }
            } else 
            /**
             * Le mot de passe est il présent dans la session ?
             * */
            if(!empty($_SESSION['ocms_sharedlogin']) && !empty($_SESSION['ocms_sharedlogin'][$this->rubIdWithPassword]) ) {
                if($_SESSION['ocms_sharedlogin'][$this->rubIdWithPassword] == $this->password) {
                    return true;
                } else {
                    return false;
                }
            
            } 
        } else {
            return true;
        }
    }
    
    
    /**
     * On masque tout ce qui est généré par les autres plugins
     * */
    public function hideAll() {
        
        global $_Gconfig;
        /**
         * paragraphe et Gabarit
         * */
        $this->rubrique->showParagraphes = false;
        $this->rubrique->hasBddInfo = false;
        $this->rubrique->bddClasse = false;
        
        
        /**
         * On supprime tous les blocs définis dans la conf
         * */
        foreach($_Gconfig['ocms_sharedlogin']['blocs_to_hide'] as $bloc) {
            $this->site->plugins['o_blocs']->blocs[$bloc]->hide();
        }
        
    }
    
    /**
     * Remonte l'arborescence pour déterminer si cette rubrique est privée
     * ou non
     * */
    public function isCurrentRubriquePasswordProtected() {
        
        $rcrub = $this->rubrique->rubrique;
        
        /**
         * Une limite pour être certain de ne pas tomber dans une récursion
         * */
        while ($i < 40) {

            $tabIds[] = $rcrub['rubrique_id'];

            /**
             * Mot de passe défini ?
             * */
            if ($rcrub['rubrique_password']) {
                $this->setPassword($rcrub['rubrique_password'], $rcrub['rubrique_id'] );
                break;
            }

            /**
             * On est arrivés à la racine
             * */
            if ($rcrub['fk_rubrique_id'] == '' || $rcrub['fk_rubrique_id'] == 'NULL') {
                break;
            }
    
            /**
             * On sélectionne le parent et on recommence
             * */
            $sql = 'SELECT rubrique_password, fk_rubrique_id, rubrique_id 
                        FROM s_rubrique AS R 
                        WHERE rubrique_id = "' . $rcrub['fk_rubrique_id'] . '" 
                        LIMIT 0,1';
            $rcrub = GetSingle($sql);

            $i++;
        }    
        
        return $this->password;
    }
    
    
    /**
     * Définit le mot de passe de la rubrique en cours
     **/
    public function setPassword($password,$rubId=false) {
        $this->password = $password;
        if(!$rubId) {
            $rubId = $this->site->getCurId();
        }
        $this->rubIdWithPassword = $rubId;
    }
    
}
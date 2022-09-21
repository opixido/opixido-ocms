<?php

class mailChimp {

    private static $table = 'ocms_newsletter_newsletter';
    
    /**
     *
     * @global array $_Gconfig
     * @return MCAPI 
     */
    public static function getApi() {

        if ($GLOBALS['mailChimpApi']) {
            return $GLOBALS['mailChimpApi'];
        }

        global $_Gconfig;
        $GLOBALS['gb_obj']->includeFile('MCAPI.class.php', 'plugins/ocms_newsletter/class');
        $GLOBALS['mailChimpApi'] = new MCAPI($_Gconfig['mailchimp_key']);
        return $GLOBALS['mailChimpApi'];
    }

    /**
     * Retourne la liste des listes de diffusion dans mailchimp
     */
    public static function getLists() {
        $lists = self::getApi()->lists();
        return $lists;
    }

    /**
     * Retourne la première liste de diffusion dans mailchimp
     * @return type 
     */
    public static function getListId() {
        $lists = self::getLists();
        $list_id = $lists['data'][0]['id'];
        return $list_id;
    }

    /**
     * Retourne la liste des groupes d'une liste donnée
     *
     * @return type 
     */
    public static function getGroups($list_id = false) {
        if (!$list_id) {
            $list_id = self::getListId();
        }
        $groups = self::getApi()->listInterestGroupings($list_id);
        //$groups = $groups[0];
        return $groups;
    }
    
    /**
     * Retourne la liste des variables d'une liste donnée
     * @param type $list_id
     * @return type
     */
    public static function getMergeFields($list_id=false){
        if (!$list_id) {
            $list_id = self::getListId();
        }
        $fields = self::getApi()->listMergeVars($list_id);
     
        return $fields;
    }

    /**
     * Créé la newsletter dans Mailchimp
     *
     * @param type $id
     * @param type $html 
     */
    public static function createCampaignFromNewsletter($id, $html='') {

        // Création de l'objet newsletter
        $objNewsletter = new newsletter($id);
        
        $options = array(
            'list_id' => self::getListId(),
            'subject' => $objNewsletter->getSubject(),
            'from_email' => t('ocms_newsletter_mail_from'),
            'from_name' => t('ocms_newsletter_base_title'),
            'to_name' => t('ocms_newsletter_base_title'),
            'generate_text' => true
        );

        $content = array(
            'html' => $html
        );

        $cid = self::getApi()->campaignCreate('regular', $options, $content);
        
        if (self::getApi()->errorCode) {
            dinfo((mailChimp::getApi()->errorCode . ' ' . mailChimp::getApi()->errorMessage));
        }
        dinfo('Identifiant mailchimp : '.$cid);

        // On met à jour l'identifiant mailchimp de notre newsletter
        $res = DoSql('UPDATE '.self::$table.' SET newsletter_mailchimp_id = ' . sql($cid) . ' WHERE newsletter_id = ' . sql($id));
        
    }

    /**
     * Met à jour le contenu HTML de la newsletter dans mailchimp
     * 
     * @param type $id 
     */
    public static function updateCampaignHtml($id) {
        
        // Création de l'objet newsletter
        $objNewsletter = new newsletter($id);

        $res = self::getApi()->campaignUpdate($objNewsletter->newsletter_mailchimp_id, 'subject', $objNewsletter->getSubject());
        $res = self::getApi()->campaignUpdate($objNewsletter->newsletter_mailchimp_id, 'title', $objNewsletter->getTitle());

        if (self::getApi()->errorCode) {
            dinfo((mailChimp::getApi()->errorCode . ' ' . mailChimp::getApi()->errorMessage));
        }
        
        $res = self::getApi()->campaignUpdate($objNewsletter->newsletter_mailchimp_id, 'content', array('html' => $objNewsletter->gen()));

        if (self::getApi()->errorCode) {
            dinfo((mailChimp::getApi()->errorCode . ' ' . mailChimp::getApi()->errorMessage));
        }
    }

    /**
     * ???
     * @param type $id
     * @param type $segment
     */
    public static function updateCampaignSegment($id, $segment) {
        
        // Création de l'objet newsletter
        $objNewsletter = new newsletter($id);
        
        $res = self::getApi()->campaignUpdate($objNewsletter->newsletter_mailchimp_id, 'segment_opts', $segment);
        
        if (self::getApi()->errorCode) {
            dinfo((mailChimp::getApi()->errorCode . ' ' . mailChimp::getApi()->errorMessage));
        }
    }

    /**
     * Envoi une newsletter de test à l'admin actuellement connecté
     *
     * @param type $id
     * @return type 
     */
    public static function sendTestNewsletter($id) {

        if (!$GLOBALS['gs_obj']->adminemail) {
            dinfo('Vous email doit-être saisi sur la fiche "administrateur"');
            return;
        }

        // Emails destinataires pour l'envoi test
        $test_emails = array($GLOBALS['gs_obj']->adminemail);

        // Mise à jour de la campagne mailchimp
        self::updateCampaignHtml($id);
        
        // Création de l'objet newsletter
        $objNewsletter = new newsletter($id);
        
        // Envoi test
        $res = self::getApi()->campaignSendTest($objNewsletter->newsletter_mailchimp_id, $test_emails);

        if ($res) {
            dinfo('Test envoyé à l\'adresse : ' . $GLOBALS['gs_obj']->adminemail);
        }
        if (self::getApi()->errorCode) {
            dinfo((mailChimp::getApi()->errorCode . ' ' . mailChimp::getApi()->errorMessage));
        }
    }

    /**
     * Envoi de la newsletter
     * @param type $id
     * @return type
     */
    public static function sendNewsletter($id) {
        
        // Mise à jour de la campagne mailchimp
        self::updateCampaignHtml($id);
        
        // Création de l'objet newsletter
        $objNewsletter = new newsletter($id);
        
        // Envoi de la newsletter
        $res = self::getApi()->campaignSendNow($objNewsletter->newsletter_mailchimp_id);
        
        if (self::getApi()->errorCode) {
            dinfo((mailChimp::getApi()->errorCode . ' ' . mailChimp::getApi()->errorMessage));
        }
        
        else {
            
            $res = DoSql('UPDATE '.self::$table.' SET newsletter_sent = ' . sql(1) . ', newsletter_sent_time = NOW() WHERE newsletter_id = ' . sql($id));
        }

        return $res;
    }

}
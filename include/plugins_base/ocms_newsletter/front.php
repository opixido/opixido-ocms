<?php

class ocms_newsletterFront {
    
    var $site;
    
    /**
     * Constructeur
     */
    public function __construct($site) {
        
        $this->site = $site;        
    }
    
    function genSubscriptionBox() {

        $tpl = new genTemplate();
        $tpl->loadTemplate('tpl.inscriptionbox', 'plugins/ocms_newsletter/tpl');

        $tpl->form_action = getUrlFromId(getRubFromGabarit('genAbonnementNewsletter'), '', array('action' => 'abonnement'));

        return $tpl->gen();
    }
    
} 
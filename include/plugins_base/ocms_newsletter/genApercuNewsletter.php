<?php

// Inclusion des classes
$GLOBALS['gb_obj']->includeFile('class.newsletter.php', 'plugins/ocms_newsletter/class');

class genApercuNewsletter extends ocmsGen {

    var $newsletter;
    
    public function __construct($site, $params = "") {
        
        parent::__construct($site, $params);
        
        if($_REQUEST['newsletter_id']){
        
            $this->newsletter = new newsletter($_REQUEST['newsletter_id']);
            
            echo $this->newsletter->gen();
            die();
        }
        
        else {
            debug('AUCUNE NEWSLETTER SPECIFIEE');
        }
    }
    

}
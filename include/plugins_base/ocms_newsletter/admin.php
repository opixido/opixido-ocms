<?php

// Inclusion de classes
$GLOBALS['gb_obj']->includeFile('class.mailchimp.php', 'plugins/ocms_newsletter/class');
$GLOBALS['gb_obj']->includeFile('class.genActionPreviewNewsletter.php', 'plugins/ocms_newsletter/class');
$GLOBALS['gb_obj']->includeFile('class.genActionSendMailchimpNewsletter.php', 'plugins/ocms_newsletter/class');
$GLOBALS['gb_obj']->includeFile('class.genActionSendTestMailchimpNewsletter.php', 'plugins/ocms_newsletter/class');
$GLOBALS['gb_obj']->includeFile('class.newsletter.php', 'plugins/ocms_newsletter/class');

// Prévisualisation de la newsletter
if (!empty($_REQUEST['doPreviewNews'])) {
    
    $objNewsletter = new newsletter($_REQUEST['curId']);
    
    echo $objNewsletter->gen();
    
    die();
}
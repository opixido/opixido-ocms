<?php

global $gs_obj, $tabForms, $_Gconfig, $gs_actions, $gs_roles, $admin_trads, $rteFields, $relinv, $relations, $uploadFields, $tablerel, $orderFields, $gr_on;

// Menu admin
$_Gconfig['adminMenus']['Newsletter'][] = 'ocms_newsletter_user';

// Configuration mailchimp
$_Gconfig['mailchimp_key'] = 'c6d358de6055b89fdad8af123d16fae0-us11';
$GLOBALS['mailChimpApi'] = false;

// Table newsletter
$tabForms['ocms_newsletter_newsletter']['titre'] = array('newsletter_titre', 'newsletter_sent', 'newsletter_sent_time', 'newsletter_mailchimp_id');
$tabForms['ocms_newsletter_newsletter']['picto'] = ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_FORM_SIZE . "/apps/internet-mail.png";
$tabForms['ocms_newsletter_newsletter']['pages'] = array('../plugins/ocms_newsletter/forms/form.newsletter.php');

// Tables de relation
$tablerel['ocms_newsletter_r_newsletter_spectacle'] = array('fk_newsletter_id' => 'ocms_newsletter_newsletter', 'fk_rubrique_id' => 's_rubrique');

// Special Listing Where
$_Gconfig['specialListingWhere']['ocms_newsletter_r_newsletter_spectacle'] = 'getSpectacles';

// Special Listing From
$_Gconfig['specialListingFrom']['ocms_newsletter_r_newsletter_spectacle'] = ', t_spectacle AS S';

// Special Listing Group by
$_Gconfig['specialListingGroupBy']['ocms_newsletter_r_newsletter_spectacle'] = ' GROUP BY(S.spectacle_id) ';

// Champs d'ordre
$orderFields['ocms_newsletter_r_newsletter_spectacle'] = array('ordre');

// Actions
$_Gconfig['rowActions']['ocms_newsletter_newsletter']['previewNewsletter'] = true;
//$_Gconfig['rowActions']['ocms_newsletter_newsletter']['sendTestMailchimpNewsletter'] = true;
//$_Gconfig['rowActions']['ocms_newsletter_newsletter']['SendMailchimpNewsletter'] = true;

// Traductions admin
$admin_trads['cp_txt_ocms_newsletter_newsletter']['fr'] = 'Newsletters';
$admin_trads['ocms_newsletter_newsletter']['fr'] = 'Newsletter';
$admin_trads['ocms_newsletter_newsletter_p_0']['fr'] = 'Newsletter';
$admin_trads['ocms_newsletter_r_newsletter_spectacle']['fr'] = 'Spectacles associés';
$admin_trads['newsletter_sent']['fr'] = 'Newsletter envoyée';
$admin_trads['newsletter_sent_time']['fr'] = 'Date d\'envoi';
$admin_trads['newsletter_titre']['fr'] = 'Titre de la newsletter';
$admin_trads['previewNewsletter']['fr'] = 'Prévisualiser la newsletter';
$admin_trads['sendTestMailchimpNewsletter']['fr'] = 'Envoyer une newsletter test';
$admin_trads['SendMailchimpNewsletter']['fr'] = 'Envoyer la newsletter';
$admin_trads['newsletter_mailchimp_id']['fr'] = 'Identifiant mailchimp';
$admin_trads['newsletter_date']['fr'] = 'Date';

$admin_trads["src_sendTestMailchimpNewsletter"]["fr"] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/mail-reply-sender.png';
$admin_trads["src_SendMailchimpNewsletter"]["fr"] = ADMIN_PICTOS_FOLDER.ADMIN_PICTOS_FORM_SIZE.'/actions/mail-reply-all.png';

function getSpectacles($genform){
    
    // Sélection des spectacles à venir ou en cours
    return ' AND (S.spectacle_date_deb >= CURDATE() OR S.spectacle_date_fin >= CURDATE()) '
    . ' AND S.spectacle_id = T2.rubrique_id '.sqlOnlyOnline('s_rubrique');
    
}

// Action lors de la création d'une newsletter => création d'une campagne mailchimp
//$gr_on['insert']['ocms_newsletter_newsletter'][] = 'createMailchimpNewsletter';

function createMailchimpNewsletter($id) {
    mailChimp::createCampaignFromNewsletter($id);
}

function updateMailchimpNewsletter($id) {
    mailChimp::updateCampaignHtml($id);
}

if (!defined('TRADLG')) {
    define('TRADLG', false);
}

<?php

// Inclusion des classes
$GLOBALS['gb_obj']->includeFile('class.mailchimp.php', 'plugins/ocms_newsletter/class');

class genNewsletterMailchimpForm extends ocmsGen {

    /**
     * Message utilisateur
     * @var string  
     */
    public $message = '';

    /**
     * Groupes d'utilisateurs mailchimp
     * @var Array  
     */
    var $groups;

    /**
     * Initialisation
     */
    function afterInit() {

        // Feuille de style
        $this->site->g_headers->addCss('ocms_newsletter.css');

        // Sélection des groupes d'utilisateurs créés dans mailchimp
        $this->groups = mailChimp::getGroups();

        // Création du formulaire
        $this->initForm();

        // Le formulaire est soumis et valide
        if ($this->f->isSubmited() && $this->f->isValid()) {
            if (empty($_POST['newsletter_groups'])) {
                $_POST['newsletter_groups'] = array();
            }

            $this->message = $this->handleAction();
        }
    }

    /**
     * Initialisation du formulaire
     */
    private function initForm() {

        $this->f = new simpleForm('', 'post', 'newsletter');
        $this->f->add('text', '', t('ocms_newsletter_nom'), 'newsletter_nom', 'newsletter_nom', false);
        $this->f->add('text', '', t('ocms_newsletter_prenom'), 'newsletter_prenom', 'newsletter_prenom', false);
        $this->f->add('email_conf', '', t('ocms_newsletter_email'), 'newsletter_email', 'newsletter_email', true);
        $this->f->add('text', '', t('ocms_newsletter_codepostal'), 'newsletter_codepostal', 'newsletter_codepostal', true);

        // On ajoute au formulaire chaque groupe défini dans mailchimp
        foreach ($this->groups as $group) {

            $this->addGroupForm($group);
        }

        $action = array('abo' => t('ocms_newsletter_abo'), 'desabo' => t('ocms_newsletter_desabo'));
        $this->f->add('select', $action, t('ocms_newsletter_action'), 'newsletter_action', 'newsletter_action', true);
        $this->f->add('submit', t('ocms_newsletter_submit'));
    }

    /**
     * Ajoute au formulaire les options relatives à un groupe mailchimp
     * @param type $mailchimpGroupArray
     */
    private function addGroupForm($mailchimpGroupArray) {

        switch ($mailchimpGroupArray['form_field']) {

            // Un seul choix possible => liste déroulante
            case 'radio':

                // Liste des items proposés pour ce groupe
                foreach ($mailchimpGroupArray['groups'] as $groupItem) {

                    $items[($groupItem['name'])] = $groupItem['name'];
                }

                $this->f->add('select', $items, $mailchimpGroupArray['name'], niceName($mailchimpGroupArray['name']), niceName($mailchimpGroupArray['name']), true);
                break;

            // Plusieurs choix possibles
            case 'checkboxes':

                // Liste des items proposés pour ce groupe
                foreach ($mailchimpGroupArray['groups'] as $groupItem) {

                    $items[] = array('label' => $groupItem['name'], 'value' => ($groupItem['name']));
                }

                $this->f->add('checkbox', $items, $mailchimpGroupArray['name'], niceName($mailchimpGroupArray['name']), niceName($mailchimpGroupArray['name']), false, array(), false, 'class="label_check"');
                break;

            default :
                break;
        }
    }

    /**
     * Génération du contenu
     * @return XHTML
     */
    function gen() {

        // Création du template
        $tpl = new genTemplate(true);
        $tpl->loadTemplate('tpl.formulaire', 'plugins/ocms_newsletter/tpl');

        // Message utilisateur
        $tpl->message = $this->message;

        // Formulaire
        $tpl->form = $this->f->gen();

        // Génération du template
        return $tpl->gen();
    }

    /**
     * Gestion des données du formulaire
     * @return type
     */
    function handleAction() {

        switch ($_POST['newsletter_action']) {

            // Inscription  
            case 'abo':

                // On récupère les variables à envoyer à mailchimp
                $merge_vars = $this->getMergeVars();

                $res = mailchimp::getApi()->listSubscribe(mailChimp::getListId(), $_POST['newsletter_email'], $merge_vars);

                if (mailChimp::getApi()->errorCode) {
                    return (mailChimp::getApi()->errorCode . ' ' . mailChimp::getApi()->errorMessage);
                } else {
                    return t('ocms_newsletter_abo_ok');
                }
                break;

            // Désinscription
            case 'desabo':
                $res = mailchimp::getApi()->listUnSubscribe(mailChimp::getListId(), $_POST['newsletter_email']);

                if (mailChimp::getApi()->errorCode) {
                    return (mailChimp::getApi()->errorCode . ' ' . mailChimp::getApi()->errorMessage);
                } else {
                    return t('ocms_newsletter_desabo_ok');
                }
                break;

            default:
                break;
        }
    }

    /**
     * Regroupe les variables envoyées à mailchimp
     */
    private function getMergeVars() {

        $merge_vars = array();

        // Nom, prénom, code postal et catégorie (Etudiants, salariés, retraités)
        $merge_vars['FNAME'] = $_POST['newsletter_nom'];
        $merge_vars['LNAME'] = $_POST['newsletter_prenom'];
        $merge_vars['CODEPOSTAL'] = $_POST['newsletter_codepostal'];

        // Groupes
        $merge_vars['GROUPINGS'] = array();

        foreach ($this->groups as $group) {

            if (is_array($_POST[niceName($group['name'])])) {

                $merge_vars['GROUPINGS'][] = array('name' => $group['name'], 'groups' => implode(',', $_POST[niceName($group['name'])]));
            } else {

                $merge_vars['GROUPINGS'][] = array('name' => $group['name'], 'groups' => $_POST[niceName($group['name'])]);
            }
        }

        return $merge_vars;
    }

}

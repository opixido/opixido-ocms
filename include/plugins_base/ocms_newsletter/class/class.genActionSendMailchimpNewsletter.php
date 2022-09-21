<?php

class genActionSendMailchimpNewsletter extends baseAction {

    function checkCondition() {
        return true;
    }

    function doIt() {
        
        // Création du template
        $tpl = new genTemplate(true);
        $tpl->loadTemplate('tpl.adminMailchimpBox', 'plugins/ocms_newsletter/tpl');
        $tpl->defineBlocks('LIST_CATEGORIES', 'ITEM_CATEGORIE', 'NB_ABONNES', 'NEWSLETTER_SENT');

        // Demande de confirmation de l'envoi
        if (!empty($_POST['mailchimp_groups'])) {

            $tplLocal = $tpl->addBlock('NB_ABONNES');
            
            $conditions[] = array('field' => 'interests-' . $_POST['grouping_id'], 'op' => 'one', 'value' => implode($_POST['mailchimp_groups'], ','));
            $opts = array('match' => 'all', 'conditions' => $conditions);
            
            $res = mailChimp::getApi()->campaignSegmentTest(mailChimp::getListId(), $opts);
            
            if (mailChimp::getApi()->errorCode) {
                dinfo((mailChimp::getApi()->errorCode . ' ' . mailChimp::getApi()->errorMessage));
            }
            
            // Nombre d'abonnés ciblés
            $tplLocal->n = $res;
            
            $_SESSION['mailChimpOpts'] = $opts;

            mailChimp::updateCampaignSegment($this->id, $opts);

        }

        // L'envoi est validé, c'est parti !
        else if (!empty($_POST['validsendmailchimp'])) {

            $tplLocal = $tpl->addBlock('NEWSLETTER_SENT');
            
            $res = mailChimp::sendNewsletter($this->id);
            
            if ($res) {
                
                $tplLocal->message = 'Newsletter en cours d\'envoi';                
            } 
            
            else {
                $tplLocal->message = 'Erreur lors de l\'envoi <br/>'.mailChimp::getApi()->errorCode . ' ' . mailChimp::getApi()->errorMessage;
            }
        }

        // Sélection des groupes auxquels envoyer la newsletter
        else {

            $tplLocal = $tpl->addBlock('LIST_CATEGORIES');

            // Sélection des items du groupe "Centres d'intérêts"
            $groups = mailChimp::getGroups();

            // On prend les items du groupes "Centres d'intérêt"
            foreach ($groups as $group) {

                if ($group['id'] == 4345) {

                    $items = $group['groups'];
                    break;
                }
            }

            if ($items) {

                foreach ($items as $item) {

                    $tplLocalItem = $tplLocal->addBlock('ITEM_CATEGORIE');
                    $tplLocalItem->name = $item['name'];
                    $tplLocalItem->subscribers = $item['subscribers'];
                }
            }
        }

        // Génération du template
        echo $tpl->gen();
    }

}

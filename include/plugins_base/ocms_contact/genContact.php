<?php

#
# This file is part of oCMS.
#
# oCMS is free software: you cgan redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# oCMS is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with oCMS. If not, see <http://www.gnu.org/licenses/>.
#
# @author Celio Conort / Opixido 
# @copyright opixido 2012
# @link http://code.google.com/p/opixido-ocms/
# @package ocms
#

class genContact extends ocmsGen {

    /**
     * Gensite
     *
     * @var Gensite
     */
    public $site;

    /**
     * simpleform
     *
     * @var simpleform
     */
    private $form;
    private $isValid = false;

    public function __construct($site, $params) {

        parent::__construct($site, $params);

        /**
         * Default CSS
         */
        $this->site->g_headers->addCss('contact.css');

        /**
         * New Form
         */
        $this->form = new simpleForm('./', 'post', 'contact_form');

        /**
         * On selectionne les champs supplémentaires à afficher
         */
        $sql = 'SELECT * FROM plug_contact_field 
					WHERE fk_rubrique_id = ' . $this->site->getCurId() . '
						ORDER BY contact_field_ordre ASC';

        $this->champs = GetAll($sql);

        $this->addFields($this->champs, 'top');

        /**
         * Champ email obligatoire
         */
        if (isTrue($this->params['contact_show_email']) && $this->params['contact_email_position'] == 'top') {
            $this->form->add('email', akev($_REQUEST, 'c_email'), t('c_email'), 'c_email', false, true);
        }
        /**
         * Liste de destinataires possibles
         */
        $this->addContactList();

        $this->addFields($this->champs, 'middle');

        /**
         * Champ email obligatoire
         */
        if (isTrue($this->params['contact_show_email']) && $this->params['contact_email_position'] != 'top') {
            $this->form->add('email', akev($_REQUEST, 'c_email'), t('c_email'), 'c_email', false, true);
        }



        /**
         * Champ de commentaire, obligatoire
         */
        if (isTrue($this->params['contact_show_comment'])) {
            $this->form->add('textarea', akev($_REQUEST, 'c_comment'), t('c_comment'), 'c_comment', false, true);
        }

        /**
         * Si on utilise le captcha on le rajoute ...
         */
        if (isTrue(getParam('formulaireContact_useCaptcha')) && pluginExists('captcha')) {

            $this->form->add('captcha', '', t('c_captcha'));
        }

        $this->addFields($this->champs, 'bottom');

        /**
         * Si on utilise le captcha on le rajoute ...
         */
        if (isTrue(getParam('formulaireContact_useCaptchaQuestion'))) {

            $this->form->add('captcha_question', '', t('c_captcha_question'), '', '', true);
        }

        /**
         * Boutons Submit 
         */
        $s = $this->form->add('submit', t('c_submit'), '', 'contact_submit', 'contact_submit');
        /**
         * Pour un submit image : 
         * $this->form->fields['contact_submit']['image'] = BU . '/img/envoyer_' . LG . '.gif';
         */
        /**
         * Si le formulaire a été soumis sans erreurs
         * Il est valide et on n'affiche plus les paragraphes
         */
        if ($this->form->isSubmited() && $this->form->isValid() && $_POST['c_email'] != t('c_mail')) {
            $this->isValid = true;
            $this->site->g_rubrique->showParagraphes = false;
        } else {
            
        }
    }

    /* 	function gen() {


      } */

    function addContactList() {

        /**
         * Par défaut on cherche dans les contacts
         */
        $sql = 'SELECT * FROM plug_contact WHERE fk_rubrique_id = "' . (int) $this->site->getCurId() . '"  ORDER BY contact_ordre , contact_titre_' . LG . ', contact_titre_' . LG_DEF;
        $res = GetAll($sql);


        /**
         * Si c'est dans les contacts
         * La liste des contacts
         */
        $tabC = Array();
        $tabC[] = array('value' => '', 'label' => '-------------------', 'selected' => true);
        if (count($res)) {
            foreach ($res as $row) {
                $tabC[] = array('value' => ($row['contact_email'] != '') ? $row['contact_id'] : '', 'label' => getLgValue('contact_titre', $row), 'selected' => true);
            }
        }
        if (empty($tabC[1])) {
            debug('Aucun contact');
            return;
        }


        /**
         * Si un seul contact on n'affiche pas le menu déroulant
         * Sinon on l'affiche
         */
        if (count($tabC) > 2) {
            $this->form->add('select', $tabC, t('c_qui'), 'c_qui', false, true, array(akev($_REQUEST, 'c_qui') ? $_REQUEST['c_qui'] : 'null'));
        } else {
            $this->form->add('hidden', $tabC[1]['value'], '', 'c_qui');
        }
    }

    function addFields($arrayOfFields, $position) {

        /**
         * Et on les affiche
         */
        foreach ($arrayOfFields as $row) {

            /*
              $displayed = ($row['contact_field_top'] && $position == 'top')
              || ($row['contact_field_bottom'] && $position == 'bottom')
              || (!$row['contact_field_top'] && !$row['contact_field_bottom'] && $position == 'middle');
             */
            $displayed = true;

            if ($displayed) {

                $var = getLgValue('contact_field_values', $row);

                /*
                  if(strpos($var,";") === false)
                  $field_value = $var;
                  else
                  $field_value = explode(";",$var);
                 */

                if ($row['contact_field_type'] == 'select' || $row['contact_field_type'] == 'selectm') {
                    if (strpos(';', $var)) {
                        $field_value = explode(";", $var);
                    } else {
                        $field_value = explode(",", $var);
                    }

                    $var = array();
                    foreach ($field_value as $v) {
                        $v = explode('=', $v);

                        if (count($v) > 1) {

                            $var[] = array('label' => $v[1], 'value' => $v[0]);
                        } else {
                            $var[] = array('label' => $v[0], 'value' => $v[0]);
                        }
                    }
                    $this->form->add(
                            $row['contact_field_type'], $var, getLgValue('contact_field_nom', $row), $row['contact_field_name'], $row['contact_field_name'], $row['contact_field_needed'], array($_REQUEST[$row['contact_field_name']])
                    );
                } else {

                    $this->form->add(
                            $row['contact_field_type'], getLgValue('contact_field_values', $row), getLgValue('contact_field_nom', $row), $row['contact_field_name'], $row['contact_field_name'], $row['contact_field_needed']
                    );
                }
            }
        }
    }

    /**
     * On génère tout juste avant les paragraphes de la rubrique
     * 
     *
     * @return unknown
     */
    public function gen() {

        $html = '<div id="contact_rub" >';

        /**
         * Si il est valide
         */
        if ($this->isValid) {
            /**
             * On masque les paragraphes
             */
            $this->site->g_rubrique->showParagraphes = false;
            /**
             * On envoit le mail de contact
             */
            $res1 = $this->sendMailContact();

            if ($res1 == 1) {
                /**
                 * On envoit la réponse automatique
                 */
                $res2 = $this->sendAutoResponse();

                if ($res2 != 1) {
                    /**
                     * Erreur d'envoi
                     */
                    dinfo($res2);
                }

                $html .= '<div id="mail_ok"><p class="para">' . t('confirm_send_contact') . '</p></div>';
            } else {
                /**
                 * Erreur d'envoi
                 */
                dinfo($res1);
                $html .= '<div id="mail_ok"><p class="para">' . t('contact_error') . '</p></div>';
            }
        } else {
            /**
             * Si pas valide on masque le formulaire
             */
            $html .= $this->showForm();
        }

        $html .= '</div>';
        $html .= '<div class="clearer" style="clear:both:">&nbsp;<br/></div>';
        return ( $html );
    }

    /**
     * Envoi la réponse automatique à l'utilisateur
     * qui a envoyé le message
     *
     * @return mixed status de l'envoi
     */
    private function sendAutoResponse() {


        return true;

        $to = $_REQUEST['c_email'];

        $subject = '[' . t('base_title') . '] ' . t('contact_auto_subject');

        $content = t('contact_auto_response') . '';

        $m = includeMail();



        $m->Body = ($content);
        $m->Subject = ($subject);

        $m->AddAddress($to);

        return $m->Send() . $m->ErrorInfo;
    }

    /**
     * Envoi le message à la personne 
     * concernée par le contact
     *
     */
    private function sendMailContact() {

        /**
         * On sélectionne la personne
         */
        $sql = 'SELECT * FROM plug_contact WHERE contact_id = "' . (int) $_REQUEST['c_qui'] . '"';
        $row = GetSingle($sql);

        /**
         * Aucun contact ?
         * bizarre mais passons ...
         */
        if (!count($row)) {
            dinfo($row);
            dinfo('ERROR WHILE SENDING NO EMAIL FOUND ' . $_REQUEST['c_qui']);
            return false;
        }

        /**
         * Destinataire
         */
        $to = $row['contact_email'];

        if (!$to) {
            dinfo('ERROR WHILE SENDING NO EMAIL DEFINED');
            return false;
        }

        /**
         * Plusieurs emails séparés par des virgules
         */
        if (strpos($to, ',') || strpos($to, ';')) {
            $tos = split('[,; ]', $to);
        } else {
            $tos = array($to);
        }

        /**
         * Envoyeur
         */
        $from = $_REQUEST['c_email'];

        /**
         * Sujet du mail
         */
        $subject = '[' . t('base_title') . '] ' . t('contact_subject') . ' - ' . getLgValue('contact_titre', $row);

        /**
         * Contenu
         */
        $content = t('contact_body') . '' . "\n\n" . $from . ' ';

        /**
         * PhpMailer
         */
        $m = includeMail();
        $m->isHtml(true);
        /**
         * PhpMailer Settings
         */
        //$m = new PHPMailer();

        /**
         * On rajoute les champs
         */
        $content .= '<table style="border-collapse:collapse;">';
        foreach ($this->champs as $champ) {
            if ($champ['contact_field_type'] == 'file') {
                if ($_FILES[$champ['contact_field_name']]) {
                    $m->AddAttachment($_FILES[$champ['contact_field_name']]['tmp_name'], $champ['contact_field_name'] . '_' . $_FILES[$champ['contact_field_name']]['name']);
                }
            } else if ($champ['contact_field_type'] == 'captcha_question') {
                // nothing to do
            } else {
                $content .= "<tr><th style='border:1px solid;text-align:right;padding:3px'>" . getLgValue('contact_field_nom', $champ) . '</th><td style="border:1px solid;padding:3px">' . "" . $_REQUEST[$champ['contact_field_name']] . '</td></tr>';
            }
        }

        $content .= "</table>" . $_REQUEST['c_comment'];




        $m->ReplyTo = $_REQUEST['c_email'];

        $m->Body = ($content);
        $m->Subject = ($subject);


        /**
         * Adding recipients
         */
        foreach ($tos as $to) {
            if (CheckEmail($to)) {
                $m->AddAddress($to);
            }
        }

        return $m->Send() . $m->ErrorInfo;
    }

    private function showForm() {

        return $this->form->gen();
    }

    public static function ocms_getPicto() {

        return ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_ARBO_SIZE . '/apps/internet-mail.png';
    }

    public static function ocms_getParams() {

        $params = array();

        $params['contact_show_email'] = array('select', array(1 => 'yes', 0 => 'no'));
        $params['contact_show_comment'] = array('select', array(1 => 'yes', 0 => 'no'));
        $params['contact_email_position'] = array('select', array('top' => 'top', 'bottom' => 'bottom'));

        return $params;
    }

}

?>

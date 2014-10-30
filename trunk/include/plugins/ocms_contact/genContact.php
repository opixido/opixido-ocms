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

class genContact extends ocmsGen
{

    /**
     * Gensite
     *
     * @var Gensite
     */
    public $site;

    /**
     * public send message
     *
     * @String sendMessage
     */
    public $sendMessage;

    /**
     * public error message
     *
     * @String errorMessage
     */
    public $errorMessage;

    /**
     * private isVisible
     *
     * @bool isVisible
     */
    private $isVisible;

    /**
     * simpleform
     *
     * @var simpleform
     */
    private $form;
    private $isValid = false;

    public function __construct($site, $params)
    {

        parent::__construct($site, $params);

        $this->sendMessage = t('confirm_send_contact');
        $this->errorMessage = t('contact_error');

        /**
         * check les contacts
         */
        $this->checkContact();

        /**
         * Default CSS
         */
        //$this->site->g_headers->addCss('contact.css');

        if (!$this->isVisible) {
            return;
        }

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


        /**
         * Liste de destinataires possibles
         */
        $this->addContactList();

        $this->addFields($this->champs);


        /**
         * Boutons Submit
         */
        $this->form->add('submit', t('c_submit'), '', 'contact_submit', 'contact_submit');

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

    /**
     * verfifie si il y a des contacts
     * sur la rubrique
     *
     */
    private function checkContact()
    {
        $sql = 'SELECT * FROM plug_contact WHERE fk_rubrique_id = ' . sql($this->site->getCurId()) . ' ';
        $row = GetAll($sql);
        if (empty($row)) {
            $this->isVisible = false;
        } else
            $this->isVisible = true;
    }

    function addContactList()
    {

        /**
         * Par défaut on cherche dans les contacts
         */
        $sql = 'SELECT * FROM plug_contact WHERE fk_rubrique_id = "' . (int)$this->site->getCurId() . '"  ORDER BY contact_ordre , contact_titre_' . LG . ', contact_titre_' . LG_DEF;
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

    function addFields($arrayOfFields)
    {

        /**
         * Et on les affiche
         */
        foreach ($arrayOfFields as $row) {


            $var = getLgValue('contact_field_values', $row);
            if (empty($row['contact_field_name'])) {
                $row['contact_field_name'] = nicename($row[ 'contact_field_nom_' . LG_DEF ]);
            }

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
                    $row['contact_field_type'], $var, getLgValue('contact_field_nom', $row), $row['contact_field_name'], $row['contact_field_name'], $row['contact_field_needed'], array(akev($_REQUEST, $row['contact_field_name']))
                );
            } else {

                $this->form->add(
                    $row['contact_field_type'], getLgValue('contact_field_values', $row), getLgValue('contact_field_nom', $row), $row['contact_field_name'], $row['contact_field_name'], $row['contact_field_needed']
                );
            }
        }

    }

    public static function ocms_getPicto()
    {

        return ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_ARBO_SIZE . '/apps/internet-mail.png';
    }

    public static function ocms_getParams()
    {

        $params = array();

        return $params;
    }

    /**
     * On génère tout juste avant les paragraphes de la rubrique
     *
     *
     * @return unknown
     */
    public function gen()
    {

        if (!$this->isVisible) {
            return '';
        }

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
                $this->saveForm();
                $html .= '<div id="mail_ok"><p class="secondary alert">' . $this->sendMessage . '</p></div>';
            } else {
                /**
                 * Erreur d'envoi
                 */
                dinfo($res1);
                $html .= '<div id="mail_ok"><p class="danger alert">' . $this->errorMessage . '</p></div>';
            }
        } else {
            /**
             * Si pas valide on masque le formulaire
             */
            $html .= $this->showForm();
        }

        $html .= '</div>';
        $html .= '<div class="clearer" style="clear:both:">&nbsp;<br/></div>';
        return ($this->isVisible) ? ($html) : false;
    }

    /**
     * Envoi le message à la personne
     * concernée par le contact
     *
     */
    private function sendMailContact()
    {

        /**
         * On sélectionne la personne
         */
        $sql = 'SELECT * FROM plug_contact WHERE contact_id = "' . (int)$_REQUEST['c_qui'] . '"';
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
        $from = akev($_REQUEST, 'c_email');

        /**
         * Sujet du mail
         */
        $subject = '[' . $this->site->g_rubrique->rObj->getTitle() . '] ' . getLgValue('contact_titre', $row);

        /**
         * Contenu
         */

        $content = t('contact_body') . ' ' . $this->site->g_rubrique->rObj->getTitle() . "\n\n" . $from . ' ';

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
        $content .= '<table>';
        foreach ($this->champs as $champ) {
            if (empty($champ['contact_field_name'])) {
                $champ['contact_field_name'] = nicename($champ[ 'contact_field_nom_' . LG_DEF ]);
            }
            if ($champ['contact_field_type'] == 'file') {
                if ($_FILES[ $champ['contact_field_name'] ]) {
                    $m->AddAttachment($_FILES[ $champ['contact_field_name'] ]['tmp_name'], $champ['contact_field_name'] . '_' . $_FILES[ $champ['contact_field_name'] ]['name']);
                }
            } else if ($champ['contact_field_type'] == 'captcha_question') {
                // nothing to do
            } else {
                $content .= "<tr><th style='text-align:left;padding:3px'>" . getLgValue('contact_field_nom', $champ) . ' : </th><td style="padding:3px">' . "" . nl2br(htmlentities($_REQUEST[ $champ['contact_field_name'] ], ENT_QUOTES, 'utf-8')) . '</td></tr>';
            }
        }

        $content .= "</table>" .
            akev($_REQUEST, 'c_comment');


        //$m->ReplyTo = akev($_REQUEST, 'c_email');

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

    /**
     * Envoi la réponse automatique à l'utilisateur
     * qui a envoyé le message
     *
     * @return mixed status de l'envoi
     */
    private function sendAutoResponse()
    {


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
     * sauvgarde les infos du formulaire
     * @return type
     */
    public function saveForm()
    {

    }

    private function showForm()
    {

        return $this->form->gen();
    }

    public function ocms_defaultParams()
    {

        $params = array();
        return $params;
    }

}

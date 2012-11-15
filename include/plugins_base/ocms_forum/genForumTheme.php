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

/**
 * This file is part of oCMS.
 *
 * oCMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * oCMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with oCMS. If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright opixido 2008
 * @link http://opixido.com
 * @package ocms
 */
class genForumTheme extends ocmsGen {

    /**
     * Theme
     *
     * @var forumTheme
     */
    public $theme;

    /**
     * Message
     *
     * @var forumMessage
     */
    public $msg;
    public $user_table = 'forum_user';

    function __construct($site, $params) {



        parent::__construct($site, $params);
        $this->site->plugins['colonneGauche']->visible = false;

        getRootForum();

        $this->user_table = $GLOBALS['forum_user_table'];
    }

    function afterInit() {

        $GLOBALS['BLOCS']['jaune']->add('FORUM', genForumTools());

        $this->site->plugins['ocms_title']->omitNb = 2;

        $this->site->g_headers->addCss('forum.css');

        /**
         * Objet theme
         */
        $this->theme = new forumTheme($this->site->g_rubrique->rubrique);

        /**
         * Objet message si on est sur la page d'un message
         */
        if ($_GET['m'] || $_REQUEST['forum_reply']) {

            $row = getRowFromId('forum_message', choose($_GET['m'], $_REQUEST['forum_reply']));
            $this->msg = new forumMessage($row);



            /**
             * Chemin de fer
             */
            $this->site->g_url->addRoad($this->msg->getTitle(), '');

            /**
             * Cookie pour définir qu'on a lu le message
             */
            $this->setCookieMessage($this->msg->id);

            if ($_REQUEST['do']) {

                if ($_REQUEST['do'] == 'watch') {
                    $this->msg->watch();
                } else if ($_REQUEST['do'] == 'unwatch') {
                    $this->msg->unwatch();
                }
            }
        }

        if ($_REQUEST['forum_report']) {
            $msg = new forumMessage($_REQUEST['forum_report']);
            $msg->report();
        }

        if ($_REQUEST['moveMessage']) {
            $m = new forumMessage($_REQUEST['moveMessage']);
            if ($m->canBeEdited()) {
                $m->moveTo($_REQUEST['moveTo']);
            }
        }
        if ($_REQUEST['deleteMessage']) {
            $m = new forumMessage($_REQUEST['deleteMessage']);
            if ($m->canBeEdited()) {
                $m->delete();
            }
        }

        global $co;
        $sql = 'SELECT  fk_message_id ,fk_utilisateur_id
						FROM forum_report 
						WHERE fk_utilisateur_id = ' . sql($_SESSION['ocms_login']['utilisateur_id']);
        $res = $co->getAssoc($sql);

        $GLOBALS['message_reported'] = $res;
    }

    /**
     * Ajoute un cookie pour définir qu'on a lu le message
     * et à quel timestamp
     *
     * @param int $id
     */
    function setCookieMessage($id) {
        setcookie('forum_v_' . (int) $id, time(), time() + 3600 * 24 * 3650, '/');
    }

    /**
     * Retourne la pagination des themes
     *
     * @param unknown_type $nbPages
     * @return unknown
     */
    function getPagination($nbPages) {

        return getPagination($nbPages);
    }

    function gen() {

        $html = genForumHead();

        if ($_GET['m']) {
            return $html . $this->genMessage();
        } else if ($_REQUEST['forum_post'] && !$_REQUEST['forum_cancel']) {

            $return = $html . $this->genForm();

            /**
             * Si l'objet messag existe, on affiche la page du sujet
             */
            if ($this->msg)
                $return .= $this->genMessage();

            return $return;
        }

        else if ($_GET['forum_search']) {
            return $html . $this->genSearch();
        } else if ($_REQUEST['forum_sendmail']) {
            return $html . $this->genMailToFriend();
        } else {
            return $html . $this->genListe();
        }
    }

    function genSearch() {


        return genForumSearch();
    }

    /**
     * Page d'un message
     *
     * @return unknown
     */
    function genMessage($tplName = 'message', $order = 'ASC') {

        /*
          $this->addAdminAction('edit','forum_message',$this->msg->row);
          $this->addAdminAction('set_sticky','forum_message',$this->msg->row);
          $this->addAdminAction('set_important','forum_message',$this->msg->row);
          $this->addAdminAction('set_clos','forum_message',$this->msg->row);
         */

        /**
         * On incrémente
         */
        $this->msg->ajouteVue();

        /**
         * On récupère tous les messages
         */
        $res = $this->msg->getAll($order);

        /**
         * Template
         */
        $tpl = new genTemplate(true);

        $tpl->set('forum_retour_themes', t('forum_retour_messages'));
        $tpl->set('forum_reply', t('forum_reply'));
        $tpl->set('url_reply', getUrlWithParams(array('forum_post' => 1, 'forum_reply' => $this->msg->id)));
        $tpl->set('url_retour', getUrlWithParams());

        $canreply = $this->msg->row['message_clos'] ? false : true;



        $tpl->setCondition('canreply', $canreply);
        $tpl->setCondition('canreply2', $canreply);

        $islogged = $GLOBALS['site']->plugins['ocms_login']->isLogged();

        $tpl->setCondition('islogged', $islogged);

        if ($islogged) {

            if ($this->msg->isWatched()) {
                $tpl->set('forum_watch', t('forum_unwatch'));
                $tpl->set('url_watch', getUrlWithParams(array('m' => $this->msg->id, 'do' => 'unwatch')));
            } else {
                $tpl->set('forum_watch', t('forum_watch'));
                $tpl->set('url_watch', getUrlWithParams(array('m' => $this->msg->id, 'do' => 'watch')));
            }
        }


        $tpl->loadTemplate($tplName, 'plugins/ocms_forum/tpl');

        $f = new forum();
        $s = $f->getArboThemes($this->getRootForum());

        foreach ($s as $v) {

            $select .= '<option disabled="disabled">' . getLgValue('rubrique_titre', $v['row']) . '</option>';
            if (!is_array($v['subs'])) {
                continue;
            }
            foreach ($v['subs'] as $vv) {
                $select .= '<option ' . ($vv['row']['rubrique_id'] == $this->site->getCurId() ? 'selected' : '') . ' value="' . $vv['row']['rubrique_id'] . '">&nbsp; &nbsp; ' . getLgValue('rubrique_titre', $vv['row']) . '</option>';
            }
        }

        $select = '<select onchange="window.location=\'' . getUrlWithParams(array('moveMessage' => $this->msg->id, 'moveTo' => '')) . '\'+getSelectedOption(this);">' . $select . '</select>';

        foreach ($res as $k => $row) {


            $t = $tpl->addBlock('MESSAGE');

            $m = new forumMessage($row);

            $t->set('id', $m->id);
            $t->set('titre', $m->getTitle());
            $t->set('texte', $m->getText());
            $t->set('date', $m->getDate());
            $t->setCondition('canreply', $canreply);
            $t->setCondition('islogged', $islogged);
            if (ake($GLOBALS['message_reported'], $m->id)) {
                $t->set('report', t('forum_reported'));
                $t->set('url_report', '#');
            } else {
                $t->set('report', t('forum_report'));
                $t->set('url_report', getUrlWithParams(array('m' => $this->msg->id, 'forum_report' => $m->id)));
            }

            $t->set('quote', t('forum_quote'));
            $t->set('url_quote', getUrlWithParams(array('forum_post' => 1, 'forum_reply' => $this->msg->id, 'forum_quote' => $m->id)));

            $t->setCondition('CANEDIT', $m->canBeEdited());
            $t->setCondition('ISMODO', $GLOBALS['site']->plugins['ocms_forum']->rowUser['forum_modo']);


            $t->set('url_admin', BU . '/admin/?curTable=forum_message&curId=' . $m->id);
            $t->set('forum_admin', t('forum_admin'));

            $t->set('edit', t('forum_edit'));
            $t->set('url_edit', getUrlWithParams(array('forum_post' => 1, 'forum_edit' => $m->id)));


            if ($k == 0)
                $t->set('select', $select);
            else
                $t->set('select', '');

            $t->set('delete', t('forum_delete'));
            $t->set('url_delete', getUrlWithParams(array('deleteMessage' => $m->id)));


            /* $user = getRowFromId('e_utilisateur',$m->getCreator()); */
            $t->set('username', $row['utilisateur_login']);

            $sign = $row['forum_user_signature'] ? '<div class="forum_sign">' . nl2br(strip_tags($row['forum_user_signature'])) . '</div>' : '';

            $t->set('signature', $sign);
            $t->set('usermessages', $row['TOTUSER'] . ' ' . t('forum_usermessages'));

            $av = $this->getAvatar($row);
            $t->set('useravatar', $av);
        }

        return $tpl->gen();
    }

    /**
     * Retourne le tag image de l'avatar
     *
     * @param unknown_type $row
     * @return unknown
     */
    function getAvatar($row) {


        if (!is_array($row)) {
            $row = getSingle('SELECT * FROM ' . $this->user_table . ' WHERE fk_utilisateur_id = ' . sql($row));
        }

        $srcav = BU . '/img/forum/avatar.gif';

        $gf = new genFile($this->user_table, 'forum_user_avatar', $row);

        if ($gf->isImage() && $gf->getWebUrl()) {
            $srcav = $gf->getWebUrl();
        }

        if (is_array($row) && $row['forum_modo']) {
            $html .= '<img src="' . BU . '/img/forum/modo.gif" alt=' . alt(t('forum_modo')) . ' title=' . alt(t('forum_modo')) . ' class="picto" />';
        }

        if ($GLOBALS['site']->plugins['ocms_login']->isLogged()) {
            $html .= '<a class="mail" title=' . alt(t('forum_send_mail')) . ' href="' . getUrlWithParams(array('forum_sendmail' => $row['fk_utilisateur_id'])) . '"><img src="' . BU . '/img/icons/email.png" alt="" /></a>';
        }

        return '<img src="' . $srcav . '" alt="" />' . $html;
    }

    /**
     * Genere le formulaire d'envoi d'un message à un membre
     *
     * @return unknown
     */
    function genMailToFriend() {


        $f = new simpleForm(getUrlWithParams(array('forum_sendmail' => $_REQUEST['forum_sendmail'])), 'post', 'forum_post');

        $row = getRowFromId('e_utilisateur', $_REQUEST['forum_sendmail']);


        $f->add('html', t('forum_mail_to') . ' ' . $row['utilisateur_login'], '', '', 'forum_mailinfo');

        $f->add('text', '', t('forum_mail_subject'), 'forum_mail_subject', 'forum_mail_subject', true);
        $f->add('textarea', '', t('forum_mail_body'), 'forum_mail_body', 'forum_mail_body', true);
        $f->add('submit', t('forum_mail_send'));

        if ($f->isSubmited() && $f->isValid()) {

            $rowMe = getRowFromId('e_utilisateur', $_SESSION['ocms_login']['utilisateur_id']);

            $m = includeMail();

            $m->From = $rowMe['utilisateur_email'];
            $m->FromName = $rowMe['utilisateur_login'] . ' ' . t('forum_mail_fromvia');

            $m->Subject = t('forum_email_subject') . ' ' . $_POST['forum_mail_subject'];


            $m->Body = t('forum_email_body') . ' : ' . "\n----------\n" .
                    $rowMe['utilisateur_login'] . ' / ' . $rowMe['utilisateur_email'] . "\n----------\n" .
                    $_POST['forum_mail_body'];

            $m->addAddress($row['utilisateur_email']);

            $res = $m->Send();

            if ($res) {
                addMessageInfo(t('forum_mail_ok'));
            } else {
                addMessageError(t('forum_mail_error') . ' : ' . $res);
            }
        } else {

            return $f->gen();
        }
    }

    /**
     * Génère la liste des messages
     *
     * @return unknown
     */
    function genListe() {


        /**
         * Nombre de messages par page
         */
        $nbPerPage = $this->params['subjects_per_page'] ? $this->params['subjects_per_page'] : 10;

        /**
         * Nombre de messages au total
         */
        $tot = ($this->theme->getNbMessages());

        /**
         * Nombre de pages à afficher
         */
        $nbPages = ceil($tot / $nbPerPage);

        /**
         * Page en cours
         * Passée en paramètre ou 1
         */
        $curPage = $_GET['page'] ? $_GET['page'] : 1;

        /**
         * Liste des messages
         */
        $msgs = ($this->theme->getMessages((($curPage - 1) * $nbPerPage), $nbPerPage));


        /**
         * Aucun message dans ce thème
         */
        if (!count($msgs)) {
            addMessageInfo(t('forum_aucun_message'));
            //return;
        }

        /**
         * Template
         */
        $tpl = new genTemplate(true);
        $tpl->loadTemplate('theme.liste', 'plugins/ocms_forum/tpl');

        $tpl->defineBlocks('LIGNE');
        /**
         * NAV
         * On cherche le forum racine
         */
        $root = $this->getRootForum();


        /**
         * Si aucun on est dans un forum seul et on n'affiche
         * pas ces boutons
         */
        if ($root) {
            $tpl->set('url_retour', getUrlFromId($root));
            $tpl->set('forum_retour', t('forum_retour_themes'));
        } else {
            $tpl->set('forum_retour_themes', '');
            $tpl->set('url_retour', '');
        }

        /**
         * Lien d'envoi de messages
         */
        $tpl->set('forum_post', t('forum_post'));
        $tpl->set('url_post', getUrlWithParams(array('forum_post' => 1)));

        /**
         * Liste des pages
         */
        $tpl->set('footer', $this->getPagination($nbPages));

        /**
         * Header Tableau
         */
        $tpl->set('summary', $this->params['summary']);
        $tpl->set('caption', $this->params['caption']);

        $tpl->set('reponses', t('forum_reponses'));
        $tpl->set('vues', t('forum_vues'));
        $tpl->set('dernier', t('forum_dernier'));

        /**
         * Titre du forum en cours
         */
        $tpl->set('titre', $this->theme->getTitle());

        foreach ($msgs as $k => $v) {

            /**
             * Ligne de message
             */
            $t = $tpl->addBlock('LIGNE');
            $m = new forumMessage($v);

            $t->set('titre', $m->getTitle());
            $t->set('desc', $m->getDesc());
            $t->set('reponses', $v['NBM']);
            $t->set('vues', $v['message_vues']);



            /**
             * Date de modification du thread
             */
            $time = strtotime($v['dernier_date']);


            $classe = $m->isRead($time) ? 'read' : 'unread';

            if ($m->row['message_type'] == 'sticky') {
                $classe .= ' sticky';
            } else if ($m->row['message_type'] == 'important') {
                $classe .= ' important';
            }

            $t->set('class', 'ligne_' . ($k % 2) . ' ' . $classe);


            $t->set('url_dernier', getUrlWithParams(array('m' => $v['dernier_id'])) . '#m' . $v['dernier_id']);
            //$t->set('url_dernier','');
            //$t->set('dernier',$v['dernier_titre']);	
            $t->set('dernier', '');

            /**
             * @todo Img
             */
            $t->set('img', '');

            $t->set('liens', '');
            $t->set('url', $m->getUrl());

            $t->set('poste_le', t('forum_le') . ' ' . niceDateTime($v['dernier_date']));
            $t->set('poste_par', '<a>' . t('forum_par') . '</a> ' . choose($v['utilisateur_login'], $v['first_login']));
        }


        return $tpl->gen();
    }

    function getRootForum() {

        return getRootForum();
    }

    function genForm() {

        $f = new simpleForm(getUrlWithParams(array()), 'post', 'forum_post');


        if ($_POST['forum_preview']) {

            $htmlPre = '<div class="forum_preview"><div class="forum_message"><div class="message">
		
				<div class="content">
				
					<div class="top">
					
						<div class="user">
						
							' . $_SESSION['ocms_login']['utilisateur_login'] . '<br/>
							
						
						</div>
						
						
						<div class="titre">
						
							' . $_POST['forum_titre'] . '
							<span class="date">
								' . date('d/m/Y h:i:s') . '
							</span>
						
						</div>
		
					
					</div>
					
					<div class="clearer"></div>
					
					<div class="avatar">
						' . $this->getAvatar($_SESSION['ocms_login']['utilisateur_id']) . '
					</div>
				
					<div class="texte">			
					
						' . $_POST['forum_texte'] . '
					
					</div>
					
					
				</div>
			
			</div>
			</div>
			
			<div class="clearer">&nbsp;</div>
			</div>';
        }

        if ($_POST['forum_texte']) {
            $t = $_POST['forum_texte'];
        } else if ($_GET['forum_quote']) {
            $m = new forumMessage($_GET['forum_quote']);
            $t = '<p>&nbsp;</p><blockquote>' . $m->getText() . '</blockquote>';
        }

        if ($_POST['forum_titre']) {
            $titre = $_POST['forum_titre'];
        } else if ($_GET['forum_reply']) {
            $m = new forumMessage($_GET['forum_reply']);
            $titre = 'Re : ' . $m->getTitle();
        }


        if ($_REQUEST['forum_edit']) {

            $m = new forumMessage($_REQUEST['forum_edit']);

            if (!$m->canBeEdited()) {
                return;
            }

            $f->add('hidden', $m->id, 'forum_edit', 'forum_edit');

            $titre = $m->getTitle();
            $t = $m->getText();
        }

        $f->add('text', $titre, t('forum_titre'), 'forum_titre', 'forum_titre', true);
        $f->add('wysiwyg', $t, t('forum_texte'), 'forum_texte', 'forum_texte', true);

        $f->add('submit', t('forum_submit'), '', 'forum_submit', 'forum_submit');
        $f->add('submit', t('forum_cancel'), '', 'forum_cancel', 'forum_cancel');
        $f->add('submit', t('forum_preview'), '', 'forum_preview', 'forum_preview');

        $f->add('hidden', '1', 'forum_post', 'forum_post');
        $f->add('hidden', $_REQUEST['forum_reply'], 'forum_reply', 'forum_reply');

        if ($_REQUEST['forum_reply']) {

            $m = new forumMessage($_REQUEST['forum_reply']);
            $f->add('html', $m->getResume());
        }
        if (!$_POST['forum_preview'] && $f->isSubmited() && $f->isValid()) {

            $this->recordPost();
        } else {
            return $htmlPre . $f->gen();
        }
    }

    function recordPost() {



        if ($_POST['forum_reply']) {
            $m = new forumMessage($_POST['forum_reply']);
            if (!$m->row || $m->row['message_clos'] || !$m->row['en_ligne']) {
                addMessageError(t('forum_clos'));
                return;
            }
        }

        $badId = $_POST['forum_reply'];
        do {
            $sql = 'SELECT * FROM forum_message WHERE message_id = ' . sql($badId);
            $row = GetSingle($sql);
            $badId = $row['fk_root_id'];
        } while ($badId > 0);

        $_POST['forum_reply'] = $row['message_id'];

        $m = array(
            'message_titre' => $this->cleanTitre($_POST['forum_titre']),
            'message_texte' => $this->cleanTexte($_POST['forum_texte']),
            'fk_rubrique_id' => $this->site->getCurId(),
            'message_date' => time(),
            'fk_utilisateur_id' => $_SESSION['ocms_login']['utilisateur_id'],
            'en_ligne' => 1,
            'message_ip' => $_SERVER['REMOTE_ADDR']
        );

        if ($_POST['forum_reply']) {
            $m['fk_root_id'] = $_POST['forum_reply'];
            $m['fk_message_id'] = $_POST['forum_reply'];
        }


        global $co;

        if ($_POST['forum_edit']) {
            $m = array(
                'message_titre' => $this->cleanTitre($_POST['forum_titre']),
                'message_texte' => $this->cleanTexte($_POST['forum_texte']),
                'message_ip' => $_SERVER['REMOTE_ADDR']
            );
            $mes = new forumMessage($_POST['forum_edit']);
            if ($mes->canBeEdited()) {
                //$m['message_id'] = $mes->id;
                $res = $co->autoExecute('forum_message', $m, 'UPDATE', ' message_id = ' . sql($mes->id));
            }
        } else {
            $res = $co->autoExecute('forum_message', $m, 'INSERT');
        }


        if (!$res) {

            addMessageError(t('forum_insert_error'));
            addMessageError($co->ErrorMsg());
        } else {

            $id = choose($_POST['forum_reply'], $_POST['forum_edit'], InsertId());

            $m = new forumMessage($id);

            /**
             * Création de l'objet message
             */
            $this->msg = $m;

            addMessageInfo(t('forum_insert_ok') . ' <a href="' . $m->getUrl() . '">' . t('forum_voir_message') . '</a>');

            $m->mailWatched();
        }
    }

    function cleanTitre($str) {

        return strip_tags($str);
    }

    function cleanTexte($str) {

        $str = strip_tags($str, '<br><p><img><span><a><b><strong><i><u><ol><li><ul><blockquote><br/><div>');

        return $str;
    }

    function ocms_getParams() {

        return array(
            'summary' => 'text',
            'caption' => 'text',
            'subjects_per_page' => 'text',
            'messages_per_page' => 'text'
        );
    }

    function ocms_getPicto() {

        return ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_ARBO_SIZE . '/apps/internet-group-chat.png';
    }

}


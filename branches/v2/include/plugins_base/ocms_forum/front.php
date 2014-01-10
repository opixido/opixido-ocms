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

class ocms_forumFront extends ocmsPlugin {

    public $rowUser;

    function afterInit() {

        if ($this->site->plugins['ocms_login']->isLogged()) {
            $this->rowUser = getSingle('SELECT * FROM ' . $GLOBALS['forum_user_table'] . ' WHERE fk_utilisateur_id = ' . sql($_SESSION['ocms_login']['utilisateur_id']));
        }
    }

    function doA() {
        
    }

}

/**
 * Theme du forum
 *
 */
class forumTheme {

    /**
     * $row de la rubrique
     *
     * @var array
     */
    public $row;

    /**
     * Id de la rubrique
     *
     * @var int
     */
    public $id;

    /**
     * Passer en parametre le $row de la rubrique du theme
     *
     * @param array $row
     */
    public function __construct($row) {
        $this->row = $row;
        $this->id = $row['rubrique_id'];
    }

    /**
     * retourne l'URL du theme en cours
     *
     * @return unknown
     */
    public function getUrl() {

        return getUrlFromId($this->row['rubrique_id']);
    }

    /**
     * Retourne le titre du theme
     *
     * @return string
     */
    function getTitle() {

        return getLgValue('rubrique_titre', $this->row);
    }

    /**
     * Retourne la description du theme
     *
     * @return string
     */
    function getDesc() {

        return getLgValue('rubrique_desc', $this->row);
    }

    public function getLastMessage() {
        
    }

    /**
     * Retourne la liste des messages pour la page en cours
     *
     * @return array
     */
    public function getMessages($start = 0, $nb = 10) {

        $sql = 'SELECT 	M.*, U.*, U2.utilisateur_login AS first_login , 
						
						M2.message_titre AS dernier_titre, 
						M2.message_id AS dernier_id,
						IF(M2.message_date,M2.message_date,M.message_date) AS dernier_date,
						COUNT(M2.message_id) AS NBM
						
						FROM forum_message AS M
						LEFT JOIN (SELECT * FROM forum_message AS M WHERE 1 ' . sqlOnlyOnline('forum_message', 'M') . ' 
						ORDER BY message_date DESC) AS M2
						ON ( M2.fk_root_id = M.message_id) 
						LEFT JOIN e_utilisateur AS U ON ( M2.fk_utilisateur_id = U.utilisateur_id ) 
						LEFT JOIN e_utilisateur AS U2 ON ( M.fk_utilisateur_id = U2.utilisateur_id ) 
						
					WHERE
					M.fk_message_id = "" 
					AND M.fk_rubrique_id = ' . sql($this->id) . '
					' . sqlOnlyOnline('forum_message', 'M') . '
					GROUP BY  M.message_id DESC					
					ORDER BY FIELD(M.message_type,"important","sticky","message"), dernier_date DESC , M2.message_date DESC
					LIMIT ' . $start . ',' . $nb . '
		';


        $res = getAll($sql);

        return $res;
    }

    function getNbMessages() {

        $sql = 'SELECT COUNT(*) AS TOT FROM forum_message AS M 
						WHERE fk_rubrique_id = ' . sql($this->id) . ' 
						AND fk_root_id  = ""
						' . sqlOnlyOnline('forum_message', 'M') . '';

        $r = getSingle($sql);

        return $r['TOT'];
    }

}

class forumMessage {

    /**
     * Message
     *
     * @var array
     */
    public $row;

    function __construct($row) {

        if (!is_array($row)) {
            $row = getRowFromId('forum_message', $row);
        }

        $this->row = $row;
        $this->id = $row['message_id'];
        if (!$this->id) {

            return false;
        }
    }

    function report() {
        if (!$this->id) {

            return false;
        }
        DoSql('REPLACE INTO forum_report VALUES (' . sql($_SESSION['ocms_login']['utilisateur_id']) . ',' . sql($this->id) . ') ');

        $m = includeMail();

        $m->Subject = t('forum_report_subject');
        $m->Body = t('forum_report_body');
        $m->Body .= "\n\n" . $this->getTitle();
        $m->Body .= "\n------------------\n" . strip_tags($this->getText());
        $m->Body .= "\n\n" . $this->getUrl();
        $m->Body .= "\n" . getServerUrl() . BU . '/admin/index.php?curTable=forum_message&curId=' . $this->id;
        $sql = 'SELECT * FROM s_admin , s_admin_role WHERE fk_role_id = 2 AND fk_admin_id = admin_id';
        $res = GetAll($sql);

        foreach ($res as $row) {
            if (CheckEmail($row['admin_email'])) {
                $m->addAddress($row['admin_email']);
//				debug($row['admin_email']);
//				debug($row);
            }
        }
        ($m->Send());
    }

    /**
     * Retour true si on est MODERATEUR 
     * ou bien si on a écrit ce message
     *
     * @return unknown
     */
    function canBeEdited() {
        if (!$this->id) {

            return false;
        }
        $id = choose($this->row['fk_utilisateur_id'], $this->row['utilisateur_id']);
        if ($id && $id == $_SESSION['ocms_login']['utilisateur_id']) {
            return true;
        }


        if ($GLOBALS['site']->plugins['ocms_forum']->rowUser['forum_modo']) {
            return true;
        }

        return false;
    }

    /**
     * Incrémente la valeur du nombre de fois que le mesasge a été vue
     *
     */
    function ajouteVue() {
        if (!$_COOKIE['forum_v_' . $this->id]) {
            TrySql('UPDATE forum_message SET message_vues = message_vues + 1 WHERE message_id = ' . sql($this->id));
        }
    }

    function moveTo($rubrique_id) {
        if ($this->canBeEdited()) {
            DoSql('UPDATE forum_message SET fk_rubrique_id = ' . sql($rubrique_id) . ' WHERE message_id = ' . sql($this->id));
        }
    }

    /**
     * Supprime le message et tous les sous messages
     *
     */
    function delete() {
        if ($this->canBeEdited()) {
            dosql('DELETE FROM forum_message WHERE fk_message_id = ' . sql($this->id) . ' OR message_id = ' . sql($this->id));
            addMessageInfo(t('forum_message_deleted'));
        }
    }

    /**
     * Affiche les derniers messages par ordre inverse
     *
     */
    function getResume() {

        //$res = $this->getAll('DESC');

        return $GLOBALS['site']->g_rubrique->bddClasse->genMessage('message.light', 'DESC');
    }

    /**
     * Retourne le titre du message
     *
     * @return string
     */
    function getTitle() {

        return $this->row['message_titre'];
    }

    function isRead($time) {
        return $_COOKIE['forum_v_' . $this->id] < $time ? false : true;
    }

    /**
     * Retourne le contenu textuel complet du message
     *
     * @return string
     */
    function getText() {

        return $this->row['message_texte'];
    }

    /**
     * Retourne une description limitée du texte
     *
     * @return string
     */
    function getDesc() {

        return limitWords(strip_tags($this->row['message_texte']), 10);
    }

    /**
     * Retourne l'URL
     *
     * @return string
     */
    function getUrl($row = array()) {

        $row = $this ? $this->row : $row;
        $title = $this ? $this->getTitle() : '';
        $anc = $row['message_id'] != $row['fk_root_id'] && $row['fk_root_id'] ? '#m' . $row['message_id'] : '';

        return getUrlFromId($row['fk_rubrique_id'], LG, array('m' => $row['message_id'], niceName($title) => '')) . $anc;
    }

    function isWatched() {

        $sql = 'SELECT * FROM forum_watch WHERE fk_utilisateur_id = ' . sql($_SESSION['ocms_login']['utilisateur_id']) . ' 
						AND fk_forum_message_id = ' . sql($this->id);
        $row = GetSingle($sql);

        return count($row);
    }

    function watch() {

        if ($_SESSION['ocms_login']['utilisateur_id']) {

            TrySql('INSERT INTO forum_watch VALUES (' . sql($_SESSION['ocms_login']['utilisateur_id']) . ',' . sql($this->id) . ')');
        }
    }

    function unwatch() {

        if ($_SESSION['ocms_login']['utilisateur_id']) {

            TrySql('DELETE FROM forum_watch WHERE 
							fk_utilisateur_id = ' . sql($_SESSION['ocms_login']['utilisateur_id']) . '
							AND fk_forum_message_id = ' . sql($this->id) . '');
        }
    }

    function mailWatched() {

        $m = includeMail();


        $m->Subject = t('forum_watch_subject') . ' ' . $this->getTitle();
        $m->Body = t('forum_watch_body') . ' ' . $this->getTitle() . "\n" . $this->getUrl();

        $sql = 'SELECT * FROM forum_watch , e_utilisateur 
					WHERE fk_utilisateur_id = utilisateur_id 
						AND	 fk_forum_message_id = ' . sql($this->id);

        $res = GetAll($sql);

        foreach ($res as $row) {

            $m->addAddress($row['utilisateur_email']);

            $m->Send();

            $m->ClearAddresses();
        }
    }

    /**
     * Retourne la date du message proprement formatée
     *
     * @return string
     */
    function getDate() {

        return niceDateTime($this->row['message_date']);
    }

    /**
     * Retourne l'identifiant du créateur du message
     *
     * @return int
     */
    function getCreator() {
        return $this->row['fk_utilisateur_id'];
    }

    /**
     * Retourne pour un message le fil complet
     *
     * @return unknown
     */
    function getAll($order = 'ASC') {

        /**
         * Si on est dans un sous message on affiche direct le message racine
         */
        if ($this->row['fk_root_id'] && $this->row['fk_root_id'] != $this->row['message_id']) {
            $id = $this->row['fk_root_id'];
        } else {
            $id = $this->id;
        }


        $sql = 'SELECT M.*, U.* , FU.* , COUNT(M2.message_id) AS TOTUSER FROM  
					forum_message AS M2 , forum_message AS M 
					LEFT JOIN e_utilisateur AS U 
					ON M.fk_utilisateur_id = U.utilisateur_id
					LEFT JOIN ' . $GLOBALS['forum_user_table'] . ' AS FU
					ON FU.fk_utilisateur_id = U.utilisateur_id
				WHERE ( 				
					(
						M.message_id = ' . sql($id) . ' 				
						OR M.fk_root_id = ' . sql($id) . '
					)
					AND ( M2.fk_utilisateur_id = U.utilisateur_id ) 
				)
				
				' . sqlOnlyOnline('forum_message', 'M') . '
				
				GROUP BY M.message_id
				ORDER BY message_date ' . $order . '		
				
		';

        return getAll($sql);
    }

}

class forum {

    function getArboThemes($rubrique_id) {

        $gab = getGabaritByClass('genForumTheme');

        $sql = 'SELECT *, 
					COUNT(if(!fk_message_id,message_id,null)) AS NBS ,	
					COUNT(message_id) AS NBM		
					
					FROM 
						s_rubrique AS R 
						LEFT JOIN (SELECT * FROM forum_message AS M WHERE 1 ' . sqlOnlyOnline('forum_message', 'M') . ' ORDER BY message_date DESC) AS M
							ON
							( M.fk_rubrique_id = R.rubrique_id 	 )	
						LEFT JOIN 
								e_utilisateur AS U ON fk_utilisateur_id = utilisateur_id					
						WHERE
							R.fk_rubrique_id = ' . sql($rubrique_id) . ' 							
							' . sqlRubriqueOnlyOnline('R') . '		
							AND R.fk_gabarit_id = ' . sql($gab['gabarit_id']) . '				
					GROUP BY rubrique_id												
					ORDER BY 
						rubrique_ordre ASC 
						,
						message_date DESC			
						
					';

        $res = GetAll($sql);

        foreach ($res as $row) {
            $subs = $this->getArboThemes($row['rubrique_id']);
            $themes[] = array('row' => $row, 'subs' => $subs);
        }

        return $themes;
    }

}

/**
 * retourne le moteur de recherche du forum 
 * et les résultats de recherche
 *
 * @return string HTML
 */
function genForumSearch() {

    /**
     * Formulaire
     */
    $f = new simpleForm(getUrlWithParams(), 'get', 'forum_search');

    $f->add('text', '', t('forum_q'), 'forum_q', 'forum_q', true);

    /**
     * Dates par semaine
     */
    $f->add('select', array(
        array(
            'value' => 0, 'label' => t('forum_date_all')
        ), array(
            'value' => 1, 'label' => t('forum_date_1week')
        ), array(
            'value' => 3, 'label' => t('forum_date_3week'))
            ), t('forum_date'), 'forum_date', 'forum_date', false);

    $f->add('submit', t('forum_submit'), 'forum_search', 'forum_search', true);

    /**
     * On lance la recherche
     */
    if ($f->isSubmited() && $f->isValid()) {


        /**
         * Classe de recherche
         */
        $GLOBALS['gb_obj']->includeFile('class.ocms_search.php', 'plugins/ocms_search');

        /**
         * Template
         */
        $tpl = new genTemplate(true);
        $tpl->loadTemplate('search', 'plugins/ocms_forum/tpl');
        $tpl->defineBlocks('MESSAGE');

        /**
         * Mots cherchés
         */
        $tpl->set('searchterms', xss($_GET['forum_search']));

        /**
         * Formulaire quand meme
         */
        $tpl->set('form', $f->gen());

        /**
         * Recherche
         */
        $s = new indexSearch('forum_message');


        /**
         * Si une date est envoyée
         */
        if ($_REQUEST['forum_date']) {

            /**
             * Temps en secondes depuis la semaine en question
             */
            $timeMin = time() - 3600 * 24 * ( 7 * $_REQUEST['forum_date'] );

            /**
             * Requete
             */
            $reqDate = ' AND UNIX_TIMESTAMP(O.message_date) >= ' . sql($timeMin) . ' ';
        }

        /**
         * Pour chercher par thème
         */
        if (false) {
            $reqPub = ' AND O.fk_rubrique_id = ' . sql($GLOBALS['site']->getCurId());
        }


        /**
         * On lance la recherche et on ajoute les infos utilisateurs et rubrique
         */
        $res = $s->search($_GET['forum_q'], ' , EU.* , RU.* ', ' , e_utilisateur AS EU , s_rubrique AS RU ', ' AND O.fk_rubrique_id = RU.rubrique_id 
						  AND fk_utilisateur_id = EU.utilisateur_id 
						' . $reqRub . ' 
						' . $reqDate
        );



        /**
         * Résultats de la recherche
         */
        foreach ($res as $row) {

            $t = $tpl->addBlock('MESSAGE');

            $m = new forumMessage($row);
            $t->set('titre', $m->getTitle());
            $t->set('desc', limit(strip_tags($m->getText()), 500));
            $t->set('username', $row['utilisateur_login']);
            $t->set('date', $m->getDate());
            $t->set('url', $m->getUrl());
            $t->set('urltheme', getUrlFromid($row['rubrique_id']));
            $t->set('titretheme', getLgvalue('rubrique_titre', $row));
        }


        return genForumHead() . $tpl->gen();
    } else {
        return genForumHead() . $f->gen();
    }
}

/**
 * Rubrique en haut du forum
 *
 * @return unknown
 */
function genForumHead() {

    return;
    $root = getRootForum();


    $html .= '<div class="forum_header">';
    $html .= '<a href="' . getUrlFromId($root) . '" class="forum_retour ' . (!$_REQUEST['forum_search'] && !$_REQUEST['forum_cgv'] && $GLOBALS['site']->getCurId() == $root ? 'selected' : '') . '">' . t('forum_themes') . '</a>';
    $html .= '<a href="' . getUrlFromId($root, LG, array('forum_search' => 1)) . '" class="forum_retour ' . ($_REQUEST['forum_search'] ? 'selected' : '') . '">' . t('forum_search') . '</a>';

    $gab = getGabaritByClass('genForumPage');

    $sql = 'SELECT * FROM s_rubrique AS R WHERE 
						fk_rubrique_id = ' . sql($root) . ' 
						' . sqlRubriqueOnlyOnline('R') . ' 
						AND fk_gabarit_id = ' . sql($gab['gabarit_id']) . '
						ORDER BY rubrique_ordre ASC';

    $res = GetAll($sql);

    foreach ($res as $row) {
        $html .= '<a href="' . getUrlFromId($row['rubrique_id']) . '" class="forum_retour ' . ($row['rubrique_id'] == $GLOBALS['site']->getCurId() ? 'selected' : '') . '">' . getLgValue('rubrique_titre', $row) . '</a>';
    }

    $sql = 'SELECT utilisateur_login FROM ' . $GLOBALS['forum_user_table'] . ' AS FU, e_utilisateur AS U
			WHERE fk_utilisateur_id = utilisateur_id 
			AND utilisateur_valide = 1
			ORDER BY utilisateur_id DESC
			
			
	';
    $res = (getAll($sql));

    $html .= '<div class="forum_header_droite">';
    $html .= t('forum_nb') . ' : <strong>' . count($res) . '</strong><br/>';
    $html .= t('forum_last') . ' : <strong>' . $res[0]['utilisateur_login'] . '</strong><br/>';
    $html .= '</div>';

    $html .= '</div><div class="clearer"></div>';

    return $html;
}

/**
 * Rubrique en haut du forum
 *
 * @return unknown
 */
function genForumTools() {

    $html = '<h3>' . getImgText(t('forum_infos'), 'titre_jaune') . '</h3>';

    $root = getRootForum();

    $html .= '<div>';

    //$html .= '<a href="'.getUrlFromId($root).'" class="forum_retour '.(!$_REQUEST['forum_search'] && !$_REQUEST['forum_cgv'] && $GLOBALS['site']->getCurId() == $root?'selected':'').'">'.t('forum_themes').'</a>';

    $html .= '<form id="forum_search_box" action="' . getUrlFromId($root) . '" method="get">
				<input type="hidden" name="forum_search" value="' . t('forum_search') . '" />
				<input type="hidden" name="forum_date" value="" />
				<input type="hidden" name="simpleform_submitted" value="forum_search" />
				<input type="hidden" name="fromSimpleForm" value="1" />
				<label for="forum_q_box" class="setinside">' . t('forum_q') . '</label>
				<input type="text" class="text" id="forum_q_box" name="forum_q" value=' . alt(($_REQUEST['forum_q'])) . ' /> 
				<input type="image" value="OK" src="' . BU . '/img/forum/ok.gif" />
			</form>';

    $html .= '</div>';


    if ($GLOBALS['site']->plugins['ocms_login']->isLogged()) {
        $login = $GLOBALS['site']->plugins['ocms_login']->row['utilisateur_login'];

        $html .= '<p>' . t('forum_vous_etes') . ' <strong>' . $login . '</strong>
					</p>
					<p>
						<a href="?laEdit=1">' . t('la_edit') . '</a>
						 <a href="?laLogout=1">' . t('la_logout') . '</a>
					</p>';
    } else {

        $html .= '
			<form action="" id="forum_login_box" method="post">
			<fieldset>
				<legend>' . t('forum_login_box') . '</legend>
				<input type="hidden" name="forum_search" value="' . t('forum_search') . '" />
				<input type="hidden" name="forum_date" value="" />
				<input type="hidden" name="simpleform_submitted" value="forum_search" />
				<input type="hidden" name="fromSimpleForm" value="1" />
				<label for="forum_lalogin_box">' . t('forum_la_login') . '</label>
				<input type="text" id="forum_lalogin_box" class="text" name="login" value="" /> <br/>
				<label for="forum_lapassword_box" >' . t('forum_la_password') . '</label>
				<input type="password" id="forum_lapassword_box" class="password" name="password" value="" /> <br/>
				<input type="image" value="OK" src="' . BU . '/img/forum/ok.gif" />
			</fieldset>
			</form>';
    }
    $sql = 'SELECT utilisateur_login FROM ' . $GLOBALS['forum_user_table'] . ' AS FU, e_utilisateur AS U
			WHERE fk_utilisateur_id = utilisateur_id 
			AND utilisateur_valide = 1
			ORDER BY utilisateur_id DESC
			
			
	';
    $res = (getAll($sql));

    $html .= '<p >';
    $html .= t('forum_nb') . ' : <strong>' . count($res) . '</strong><br/>';
    $html .= t('forum_last') . ' : <strong>' . $res[0]['utilisateur_login'] . '</strong><br/>';
    $html .= '</p>';



    $html .= '</div><div class="clearer"></div>';

    return $html;
}

/**
 * Retourne l'identifiant du forum racine
 * qui contient l'arbo des forums
 *
 * @return string HTML
 */
function getRootForum() {
    if ($GLOBALS['root_forum']) {
        return $GLOBALS['root_forum'];
    }

    $row = $GLOBALS['site']->g_rubrique->rubrique;
    $gab = getGabaritByClass('genForumListe');
    $gabId = $gab['gabarit_id'];
    $i = 0;
    while ($i < 100) {

        if ($row['fk_gabarit_id'] == $gabId) {
            $p = SplitParams($row['rubrique_gabarit_param'], ';', '=');
            if ($p['forum_user_table']) {
                $GLOBALS['forum_user_table'] = $p['forum_user_table'];
            }
            $GLOBALS['root_forum'] = $row['rubrique_id'];
            return $row['rubrique_id'];
        } else if ($row['fk_rubrique_id']) {
            $row = getRowFromId('s_rubrique', $row['fk_rubrique_id']);
        } else {
            return false;
        }

        $i++;
    }
}

function forumNeedLogin() {
    return $_REQUEST['forum_post'];
}

class laSignUpForum extends laSimpleSignUp {

    public $table = 'forum_user';
    public $champs = array(
        'forum_user_nom' => 'text',
        'forum_user_prenom' => 'text',
        'forum_user_avatar' => 'image',
        'forum_user_signature' => 'textarea'
    );
    public $noneed = array('forum_user_avatar', 'forum_user_comment', 'forum_user_signature');

    function isValid() {



        if (parent::isValid()) {
            return true;
            $naissance = date('U', @mktime(0, 0, 0, (int) $_POST['forum_user_age_m'], (int) $_POST['forum_user_age_d'], (int) $_POST['forum_user_age_y']));
            $seizeans = time() - (16 * 365 * 24 * 3600);

            if ($naissance < $seizeans) {
                return true;
            } else {
                addMessageError(t('forum_young'));
            }
        }

        return false;
    }

    function prepareform() {

        $f = new simpleForm('', 'post', 'signup');

        parent::addBaseField($f);

        parent::addSpecificField($f);

        if (!$this->la->isLogged()) {
            $f->add('html', '<div class="sep"></div><div class="cgv">' . nl2br(t('cgv')) . '</div>');
            $f->add('checkbox', array(array('value' => '1', 'label' => t('bug_cgv'))), '', 'cgv_legend', 'cgv_legend', true, ($_REQUEST['cgv_legend']));
        }
        //$f->add('html','<div class="sep"></div>');

        $f->add('submit', t('forum_submit'));

        if (!$this->la->isLogged()) {
            $f->add('html', '<div class="clearer"></div><div class="sep"></div><div class="mentions">' . nl2br(t('mentions')) . '</div>');
        }
    }

}


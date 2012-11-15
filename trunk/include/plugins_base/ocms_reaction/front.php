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

class reaction extends row {

    public $table = 'plug_reaction';

    function __construct($row) {

        parent::__construct($this->table, $row);
    }

}

class reactions {

    public $obj;
    public $id;
    public $hasBeenRecorded = false;

    function __construct($obj, $id) {

        $this->obj = $obj;
        $this->id = $id;
    }

    function getReactions($nb = 0) {

        $sql = 'SELECT * FROM plug_reaction WHERE 
					fk_obj = ' . sql($this->obj) . ' 
					AND fk_id = ' . sql($this->id) . ' 
					' . sqlOnline('plug_reaction') . '
					ORDER BY reaction_date DESC
					';
        if ($nb) {
            $sql .= ' LIMIT 0,' . $nb;
        }

        return getAll($sql);
    }

    function genReactions($nb = 0, $chars = 0, $showDate = true) {

        $res = $this->getReactions($nb);

        if (!count($res)) {

            return t('<p class="para">' . t('reaction_aucune') . '</p>');
        }

        $tpl = new genTemplate();
        $tpl->loadTemplate('reaction', 'plugins/ocms_reaction/tpl');

        foreach ($res as $row) {

            $t = $tpl->addBlock('REACTION');
            $r = new reaction($row);
            if ($chars) {
                $t->reaction = limit($r->reaction_comment, $chars);
            } else {
                $t->reaction = ($r->reaction_comment);
            }
            $t->nom = $r->reaction_nom;
            $t->date = nicedatetime($r->reaction_date);
            if (!$showDate) {
                $t->date = '';
            }
        }

        return $tpl->gen();
    }

    function getForm() {

        $f = new simpleForm('#div_reactions', 'post', 'plug_reaction');

        $f->add('text', '', t('reaction_nom'), 'reaction_nom', 'reaction_nom', true);
        $f->add('email', '', t('reaction_email'), 'reaction_email', 'reaction_email', true);
        $f->add('textarea', '', t('reaction_comment'), 'reaction_comment', 'reaction_comment', true);

        $f->add('captcha_question', '', t('reaction_captcha'), 'reaction_captcha', 'reaction_captcha', true);

        $f->add('submit', t('reaction_submit'), 'reaction_submit', 'reaction_submit', 'reaction_submit');


        $valid = $f->isValid();
        if ($f->isSubmited() && !$valid) {

            $GLOBALS['site']->g_headers->addTitle(t('form_error'));

            return $f->gen();
        } else
        if ($f->isSubmited() && $valid) {

            $res = $this->recordReaction($_POST['reaction_nom'], $_POST['reaction_email'], $_REQUEST['reaction_comment']);


            if ($res) {
                $GLOBALS['site']->g_headers->addTitle(t('form_ok'));
                $this->hasBeenRecorded = true;
                return ('<div class="info">' . t('reaction_ok') . '</div>');
            }
        } else {

            return $f->gen();
        }
    }

    function recordReaction($nom, $email, $commentaire) {

        global $co;
        $rs = 'plug_reaction';
        $_POST['reaction_date'] = date('Y-m-d H:i:s');
        $_POST['fk_obj'] = $this->obj;
        $_POST['fk_id'] = $this->id;

        $_POST['en_ligne'] = istrue(getParam('reaction_autovalide'));

        foreach ($_POST as $k => $v) {
            $_POST[$k] = strip_tags($v);
        }

        $res = $co->autoExecute($rs, $_POST, 'INSERT');

        $emails = t('reaction_emailconf');
        $emails = explode(';', $emails);

        $objTitle = GetTitleFromRow($this->obj, getRowFromId($this->obj, $this->id), ' ');

        $m = includeMail();
        $m->Subject = t('reaction_subject');
        $m->Body = tf('reaction_body', array('OBJ' => $objTitle, 'URL' => getServerUrl() . ADMIN_URL . '/?curTable=plug_reaction&curId=' . InsertId() . '&genform_action[view]=1'));

        foreach ($emails as $email) {
            if (CheckEmail(trim($email))) {
                $m->addAddress($email);
            }
        }
        $m->Send();

        return $res;
    }

}
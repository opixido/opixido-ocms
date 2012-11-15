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

class genActionBanishForumUser extends ocms_action {

    public $canReturnToList = true;

    function checkCondition() {
        if (!$this->row['utilisateur_id']) {
            $this->row = GetSingle('SELECT * FROM e_utilisateur, forum_user WHERE fk_utilisateur_id = utilisateur_id AND forum_user_id = ' . sql($this->id));
        }
        if ($this->row['utilisateur_valide'] != -1) {

            return true;
        } else {
            return false;
        }
    }

    function doIt() {

        (DoSql('UPDATE e_utilisateur SET utilisateur_valide = -1 WHERE utilisateur_id = ' . sql($this->row['fk_utilisateur_id'])));
    }

}

class genActionUnBanishForumUser extends ocms_action {

    public $canReturnToList = true;

    function checkCondition() {

        if (!$this->row['utilisateur_id']) {
            $this->row = GetSingle('SELECT * FROM e_utilisateur, forum_user WHERE fk_utilisateur_id = utilisateur_id AND forum_user_id = ' . sql($this->id));
        }
        if ($this->row['utilisateur_valide'] == -1) {
            return true;
        } else {
            return false;
        }
    }

    function doIt() {

        (DoSql('UPDATE e_utilisateur SET utilisateur_valide = 1 WHERE utilisateur_id = ' . sql($this->row['fk_utilisateur_id'])));
    }

}

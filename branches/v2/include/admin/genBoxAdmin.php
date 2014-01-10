<?php

#
# This file is part of oCMS.
#
# oCMS is free software: you can redistribute it and/or modify
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
# @copyright opixido 2009
# @link http://code.google.com/p/opixido-ocms/
# @package ocms
#

class genBoxAdmin {

    private $site;
    private $html;

    function __construct($site) {

        global $nbFrontAdmin;

        $this->site = &$site;
    }

    function gen() {

        /* onclick="if(gid(\'admin_box\').className==\'admin_box_open\' ) {gid(\'admin_box\').className=\'admin_box_close\'} else {gid(\'admin_box\').className=\'admin_box_open\'}" */

        return '<div id="admin_box" class="admin_box_open">
		<div id="admin_open_close" onclick="if(gid(\'admin_box\').className==\'admin_box_open\' ) {gid(\'admin_box\').className=\'admin_box_close\'} else {gid(\'admin_box\').className=\'admin_box_open\'}" >Menu</div>

		' . $this->html . '</div>';
    }

    function add($str) {
        $this->html .= '<div>' . $str . "</div>\n";
    }

}

?>
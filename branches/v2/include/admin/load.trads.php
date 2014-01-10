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

global $admin_trads, $_Gconfig;

$v = LG;

$sql = 'SELECT trad_id,trad_' . $v . ' FROM s_trad';
$res = GetAll($sql);

if ($res) {
    foreach ($res as $row) {
        $admin_trads[$row['trad_id']][$v] = $row['trad_' . $v];
    }
}

$sql = 'SELECT admin_trad_id,admin_trad_' . $v . ' FROM s_admin_trad';
$res = GetAll($sql);

if ($res) {
    foreach ($res as $row) {
        $row['admin_trad_' . $v] = str_replace(array('[ADMIN_PICTOS_FOLDER]',
            '[ADMIN_PICTOS_ARBO_SIZE]',
            '[ADMIN_PICTOS_FORM_SIZE]')
                , array(ADMIN_PICTOS_FOLDER,
            ADMIN_PICTOS_ARBO_SIZE,
            ADMIN_PICTOS_FORM_SIZE), $row['admin_trad_' . $v]);
        $admin_trads[$row['admin_trad_id']][$v] = $row['admin_trad_' . $v];
    }
}


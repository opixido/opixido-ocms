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

function getImgText($text, $profile = '', $params = '') {
    $ps = getImgTextSrc($text, $profile, $params);
    return '<img src="' . $ps . '" alt=' . alt($text) . ' />';
}

function getImgTextSrc($text, $profile = '', $params = '') {

    $u = BU . '/imgps.php?text=' . urlencode(htmlentities($text, ENT_QUOTES, 'utf-8'));
    if ($profile) {
        $u .= '&profile=' . $profile;
    }
    if ($params) {
        $u .= '&' . str_replace('&amp;', '&', $params);
    }
    $cPath = './imgc/';
    $m = md5($u) . '.png';

    if (CACHE_IS_ON && file_exists($cPath . $m)) {
        return BU . '/imgc/' . $m;
    }
    return $u;
}
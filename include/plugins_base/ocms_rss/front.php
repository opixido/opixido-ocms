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

function includeRss() {

    /* define('MAGPIE_OUTPUT_ENCODING', 'UTF-8');
      $GLOBALS['gb_obj']->includeFile('rss_fetch.inc','plugins/ocms_rss/magpierss');
     */


    /**
     * Utile pour la lecture des flux externes
     */
    $GLOBALS['gb_obj']->includeFile('simplepie.inc', 'plugins/ocms_rss/simplepie');
}

class rssFront extends ocmsPlugin {

    function afterInit() {
        
    }

    function getCurrentPageFlux() {


        $sql = 'SELECT * FROM plug_rss WHERE fk_rubrique_id = ' . sql($GLOBALS['site']->getCurId());
        $res = GetAll($sql);

        if (!count($res)) {
            $id = getRubFromGabarit('genRss');

            $sql = 'SELECT * FROM plug_rss WHERE fk_rubrique_id = ' . sql($id);
            $res = GetAll($sql);
        }

        return $res;
    }

    function genRssHeaders($res) {

        foreach ($res as $row) {

            $rss = '<link href="' . rssFront::getUrl($row) . '" rel="alternate" type="application/rss+xml" title=' . alt(getLgValue('rss_titre', $row)) . ' />
';
            $GLOBALS['site']->g_headers->addHtmlHeaders($rss);
        }
    }

    function getUrl($row, $format = 'RRS2.0') {

        $rubId = getRubFromGabarit('genRss');

        return getUrlFromId($rubId, LG, array('flux' => $row['rss_version'], 'format' => $format));
    }

}

?>
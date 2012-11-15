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

class ocms_roadFront extends ocmsPlugin {

    function genBeforePara() {

        $road = $this->site->g_url->buildRoad();

        $tpl = new genTemplate(true);
        $tpl->loadTemplate('template', 'plugins/ocms_road');
        $tpl->defineBlocks('PAGE');
        $tpl->set('vous_etes_ici', t('road_vous_etes_ici'));

        $nbR = count($road);
        $nb = 0;
        foreach ($road as $k => $v) {
            $nb++;
            $page = $tpl->addBlock('PAGE');
            $page->set('titre', $v['titre']);
            $page->set('url', $v['url']);
            if ($nb < $nbR) {
                $page->set('raquo', ' > ');
            } else {
                $page->set('raquo', '');
            }
        }

        return $tpl->gen();
    }

}

?>
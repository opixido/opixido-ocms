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
if (isset($_REQUEST['ajax_q']) && isset($_REQUEST['q'])) {

    $GLOBALS['gb_obj']->includeFile('class.ocms_search.php', 'plugins/ocms_search');
    $c = new indexSearch();
    $c->useWildCards();
    $res = $c->search($_REQUEST['q']);

    foreach ($res as $row) {
        $r = getRowFromId($row['obj'], $row['fkid']);
        echo '<a href="' . getUrlFromSearch($row, $r) . '">' . GetTitleFromRow($row['obj'], $r) . '</a>';
    }

    die();
}

class ocms_searchFront {

    /**
     * Gensite
     *
     * @var Gensite
     */
    public $site;

    function __construct($site) {

        $this->site = $site;
    }

    function afterInit() {
        $this->site->g_headers->addCssText('#autocomplete a:hover, #autocomplete a.selected {background:' . COULEUR_2 . '}');
    }

    function genRechercheForm() {

        $t = new genTemplate();
        return $t->loadTemplate('recherche_form', 'plugins/ocms_search/tpl')->gen();
    }

    function gen() {
        
    }

}

?>
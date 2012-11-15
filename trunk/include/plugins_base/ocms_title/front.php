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

class ocms_titleFront {

    /**
     * gensite
     *
     * @var gensite
     */
    public $site;

    /**
     * Visible ou non
     *
     * @var bool
     */
    public $visible = true;

    /**
     * Titre de la page a� afficher
     *
     * @var string
     */
    public $titre;
    public $forceTitre = '';
    public $className = '';

    function __construct($site) {

        $this->site = $site;

        //$this->titre = getLgValue('rubrique_titre',$this->site->g_rubrique->rubrique);
    }

    function genBeforePara() {

        $html = '';

        /**
         * Si on a un titre et qu'on veut etre vu 
         */
        if ($this->visible) {

            /**
             * On supprime le premier élément : "accueil"
             */
            if ($this->forceTitre) {
                $t = $this->forceTitre;
            } else {
                $r = new rubrique($this->site->g_rubrique->rubrique);
                $t = choose($r->rubrique_titre_page, $r->rubrique_titre);
                /*
                  $s = array_slice($this->site->g_url->buildRoad(),1);
                  $len = count($s);
                  if(!empty($s[$len-1])) {
                  $t = $s[$len-1]['titre'];
                  } else {
                  $t = false;
                  $this->visible = false;
                  }

                 */
            }


            if ($t)
                $html = '<h1 id="h1" class="misob ' . $this->className . '">' . $t . '</h1>';
        }
        return '<div id="titres" class="clearfix">' . $html . '</div><div class="clearer">&nbsp;</div>';
    }

    function hide() {
        $this->visible = false;
    }

    function show() {
        $this->visible = true;
    }

}


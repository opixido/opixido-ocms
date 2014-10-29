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

class ocms_titleFront
{

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
     * Titre de la page à afficher
     *
     * @var string
     */
    public $titre;
    public $forceTitre = '';
    public $className = '';

    function __construct($site)
    {

        $this->site = $site;

        //$this->titre = getLgValue('rubrique_titre',$this->site->g_rubrique->rubrique);
    }

    public function afterInit()
    {
        if ($this->visible) {
            $this->site->plugins['o_blocs']->blocs['main_before']->add('titre', $this->genTitre(), '', 10);
        }
    }

    function genTitre()
    {

        $html = '';

        /**
         * Si on a un titre et qu'on veut etre vu
         */
        if ($this->visible) {

            /**
             * Si un autre plugin veut remplacer notre titre
             */
            if ($this->forceTitre) {
                $t = $this->forceTitre;
            } else {

                $r = $this->site->g_rubrique->row;
                $t = choose($r->rubrique_titre_page, $r->rubrique_titre);
            }


            if ($t) {
                $html = '<h1 id="h1" class="' . $this->className . '">' . $t . '</h1>';
            }
        }
        return '<div id="titres" class="clearfix">' . $html . '</div>';
    }

    function hide()
    {
        $this->visible = false;
    }

    function show()
    {
        $this->visible = true;
    }

}

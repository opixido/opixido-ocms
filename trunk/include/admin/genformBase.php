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

class genform_base {

    var $gf;
    var $table;
    var $id;
    var $champ;
    var $buffer;

    function __construct($table, $id, $champ, $gf = false) {

        global $editMode;
        $this->table = $table;
        $this->id = $id;
        $this->tab_name = $this->champ = $champ;
        $this->editMode = $gf ? $gf->editMode : $editMode;
        $this->buffer = '';
        if ($gf)
            $this->gf = $gf;
        $this->init();
    }

    function gen() {


        /**
         * Si on est en modification 
         */
        if (!$this->editMode) {
            return $this->genForm();
        } else {
            /**
             * Sinon affichage seulement et pas de modification
             */
            return $this->genValue();
        }
    }

    function genForm() {

        $this->addBuffer('UNDEFINED FORM');
    }

    function genValue() {

        $this->addBuffer('UNDEFINED VALUE');
    }

    function addBuffer($v) {

        $this->buffer .= $v;
    }

    function getBuffer() {

        return $this->buffer;
    }

}

?>
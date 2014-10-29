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

class ajaxEnum {

    function __construct($af, $champ) {

        $this->af = $af;
        $this->champ = $champ;
        $this->row = $af->row;
        $this->table = $af->table;
        $this->id = $af->id;

        $this->champ_id = $this->table . '-' . $champ;
    }

    function gen() {

        $html .= ( '<select id="' . $this->champ_id . '"
						 onchange="ajaxSaveValue(this,' . js($this->table) . ',' . js($this->champ) . ',' . js($this->id) . ')"  >' );

        $enums = getEnumValues($this->table, $this->champ);


        foreach ($enums as $enum) {
            $thisValue = t('enum_' . $enum);
            if (strcmp($this->getCurrent(), $enum) == 0)
                $html .= ( '<option selected="selected" value="' . $enum . '">' . ( $thisValue ) . '</option>' );

            else
                $html .= ( '<option  value="' . $enum . '"> ' . ( $thisValue ) . '</option>' );
        }


        $html .= ( '</select>' );

        return $html;
    }

    function getCurrent() {

        return $this->row[$this->champ];
    }

}

?>
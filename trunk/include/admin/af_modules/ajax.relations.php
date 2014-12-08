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

class ajaxRelations
{

    function __construct($af, $champ, $fk_table)
    {

        $this->af = $af;
        $this->fk_table = $fk_table;
        $this->champ = $champ;
        $this->row = $af->row;
        $this->id = $af->id;
        $this->table = $af->table;

        $this->champ_id = $this->table . '-' . $champ;
    }

    function gen()
    {

        $cur = $this->getCurrent();
        $list = $this->getListing();

        $pk = getPrimaryKey($this->fk_table);
        global $_Gconfig;
        if (!empty($_Gconfig['relationAsAjax'][ $this->fk_table ])) {
            $html = '';
            $html = var_export($this->row, true);
            return $html;
        }


        /*         * */
        $html = '';

        $html .= '<select ';
        $html .= ' id="' . $this->champ_id . '" ';
        $html .= ' onchange="ajaxSaveValue(this,' . js($this->table) . ',' . js($this->champ) . ',' . js($this->id) . ')" >';
        $html .= "\n";

        $html .= '<option value=""></option>';

        foreach ($list as $v) {

            $html .= '<option ';
            if ($v[ $pk ] == $cur) {
                $html .= ' selected="selected" ';
            }
            $html .= ' value="' . $v[ $pk ] . '" ';
            $html .= ' > ';
            $html .= GetTitleFromRow($this->fk_table, $v);
            $html .= '</option>';
            $html .= "\n";
        }

        $html .= '</select>';

        $html .= "\n";

        return $html;
    }

    /**
     * Returns current selected ID
     *
     * @return unknown
     */
    function getCurrent()
    {
        return $this->row[ $this->champ ];
    }

    function getCurrentValue()
    {
        $row = getRowFromId($this->fk_table, $this->getCurrent());
        return GetTitleFromRow($this->fk_table, $row);
    }

    function getListing()

    {
        $res = getTableListing($this->fk_table, $this->table);
        return $res;
    }

}


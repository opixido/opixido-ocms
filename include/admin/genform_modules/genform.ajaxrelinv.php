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

class ajaxRelinv
{

    var $cur_table;
    var $cur_id;
    var $fk_field;
    var $fk_table;
    var $fake_name;
    var $nb_line = 0;

    function __construct($cur_table, $cur_id, $fk_table, $fk_field, $fake_name)
    {

        $this->cur_table = $cur_table;
        $this->cur_id = $cur_id;
        $this->fk_table = $fk_table;
        $this->fk_field = $fk_field;
        $this->fake_name = $fake_name;
    }

    function getCurrent()
    {
        global $orderFields;
        if ($this->cur_id == 'new') {
            return array();
        }
        $sql = 'SELECT * FROM ' . $this->fk_table . ' WHERE ' . $this->fk_field . ' = ' . sql($this->cur_id) . ' ORDER BY ';
        if (!empty($orderFields[ $this->fk_table ])) {
            $sql .= $orderFields[ $this->fk_table ][0] . " ,  ";
        }

        $sql .= GetTitleFromTable($this->fk_table, ' , ');

        $res = GetAll($sql);

        return $res;
    }

    function addOne()
    {

    }

    function getValue()
    {

        $cur = $this->getCurrent();
        $html = '';
        foreach ($cur as $k => $v) {
            $html .= GetTitleFromRow($this->fk_table, $v, " / ") . '<br/>';
        }

        return $html;
    }

    function getForm($fields)
    {

        global $orderFields;
        $liste = $this->getCurrent();


        $html = '<div class="ajaxRelinv">';

        $ofield = $sort = '';
        if (!empty($orderFields[ $this->fk_table ][0])) {
            $ofield = $orderFields[ $this->fk_table ][0];
            $sort = 'sortable';
        }
        $html .= '

		<table width="590" rel="' . $this->fk_table . '__' . $ofield . '" class="' . $sort . ' genform_table ajax_table table table-striped table-bordered table-condensed" id="ar_' . $this->cur_table . '-' . '' . $this->fake_name . '-' . $this->cur_id . '">';

        global $restrictedMode;
        $restrictedMode = true;
        //$this->nbLines = count($liste);
        $colspan = '1';
        if (ake($this->fk_table, $orderFields)) {
            $colspan = '2';
        }
        $html .= '<thead><tr><th colspan="' . $colspan . '"><a class="btn" href="" onclick="arAddValue(this,\'' . $this->cur_table . '\',\'' . $this->fake_name . '\',\'' . $this->cur_id . '\');$(this).closest(\'table\').tableDnDUpdate();return false;">
		<img src="' . ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_FORM_SIZE . '/actions/list-add.png" alt="" />' . t('nouveau') . '</a></th>';

        foreach ($fields as $v) {
            $html .= '<th scope="col">' . t($v) . '</th>';
        }
        $html .= '</tr></thead><tbody>';
        foreach ($liste as $row) {
            $html .= $this->getLine($row, $fields) . "\n";
        }

        $html .= '</tbody></table>
		</div>';

        return $html;
    }

    function getLine($row, $fields)
    {

        global $restrictedMode, $orderFields;
        $restrictedMode = true;
        $html = '';
        $this->nb_line++;
        $idd = $row[ getPrimaryKey($this->fk_table) ];

        /**
         * TR
         */
        $html .= "\n" . '<tr id="ar_' . $idd . '" rel="' . $idd . '">';

        /**
         * Cellule Delete
         */
        if (ake($this->fk_table, $orderFields)) {
            $html .= '<td class="dragHandle">&nbsp;</td>';
        }
        $html .= '
				<td width="20">
					<a class="btn" href="" title=' . alt(t('delete')) . '
					onclick="arDelete(this,\'' . $this->fk_table . '\',' . $idd . ');return false;"
					>
                                        <i class="icon icon-trash" >Supprimer</i>
					
					</a>
				</td>';


        $af = new ajaxForm($this->fk_table, $idd);
        foreach ($fields as $v) {
            $html .= '<td class="ajaxrelinv_' . $v . '">' . $af->genField($v) . '</td>';
        }
        $html .= "\n" . '</tr>';

        return $html;
    }

}

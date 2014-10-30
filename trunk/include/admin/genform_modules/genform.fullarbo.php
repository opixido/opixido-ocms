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

class fullArbo {

    function __construct($table, $id, $conf, $field) {

        $this->table = $table;
        $this->id = $id;
        $this->conf = $conf;
        $this->vtable = $conf[0];
        $this->vfk1 = $conf[1];
        $this->vfk2 = $conf[2];
        $this->order = $conf[3];
        $this->field = $field;
        $this->vpk = getPrimaryKey($this->vtable);



        $this->addlink = ' <a onclick="return FAadd(this,\'' . $this->field . '\',\'[ID]\')" class="FAadd" href=""><img alt="add" src="./pictos/list-add.png"/></a>';
        $this->remlink = ' <a onclick="return FAdel(this,\'' . $this->field . '\',\'[ID]\')" class="FAdel" href=""><img alt="remove" src="./pictos/process-stop.png"/></a>';
        $this->getup = ' <a onclick="return FAgoUp(this,\'' . $this->field . '\',\'[ID]\')" class="FAgoUp" href=""><img alt="up" src="./pictos/go-up.png"/></a>';
        $this->getdown = ' <a onclick="return FAgoDown(this,\'' . $this->field . '\',\'[ID]\')" class="FAgoDown" href=""><img alt="down" src="./pictos/go-down.png"/></a>';

        $this->links = $this->addlink . $this->remlink . $this->getup . $this->getdown;
        //print_r($this);

        $this->html = '
		<script src="js/ajaxForm.js" type="text/javascript"></script>
		<script type="text/javascript">
			if(!window.arboFull) 
			{
				window.arboFull = Array();
			}
			window.arboFull["' . $this->field . '"] = Array();
			window.arboFull["' . $this->field . '"]["table"] = "' . $this->table . '";
			window.arboFull["' . $this->field . '"]["vtable"] = "' . $this->vtable . '";
			window.arboFull["' . $this->field . '"]["id"] = "' . $this->id . '";
			window.arboFull["' . $this->field . '"]["vfk1"] = "' . $this->vfk1 . '";
			window.arboFull["' . $this->field . '"]["vfk2"] = "' . $this->vfk2 . '";
			window.arboFull["' . $this->field . '"]["order"] = "' . $this->order . '";
			window.arboFull["' . $this->field . '"]["field"] = "' . $this->field . '";
			
		</script>
		<div id="arborescence">
		
		';
    }

    function getForm($vals) {

        if ($this->id == 'new') {
            return '-';
        }
        $sql = 'SELECT * FROM ' . $this->vtable . ' WHERE
                    ' . $this->vfk1 . ' = ' . sql($this->id) . '
                    AND (' . $this->vfk2 . ' IS NULL )

                ORDER BY ' . $this->order;

        $res = GetAll($sql);

        $this->html .= '' . str_replace('[ID]', '', $this->addlink) . '<ul id="racine">
		
		';
        $go = new genOrder($this->vtable, 0, $this->id, $this->vfk1);
        $go->ReOrderRes($res);

        foreach ($res as $row) {

            $this->getLine($row);
        }
        $this->html .= '</ul><script>checkUpDown();</script></div>';

        return $this->html;
    }

    function getSubs($id) {

        $go = new genOrder($this->vtable, 0, $id, $this->vfk2);
        $go->ReOrder();

        $sql = 'SELECT * FROM ' . $this->vtable . ' WHERE ' . $this->vfk2 . ' = ' . sql($id) . ' ORDER BY ' . $this->order;
        $res = GetAll($sql);

        $this->html .= '<ul>';
        foreach ($res as $row) {
            $this->getLine($row);
        }
        $this->html .= '</ul>';
    }

    function getLine($row, $doli = true) {

        if (!empty($GLOBALS['donesFK'][$row[$this->vpk]])) {
            echo '<div style="background:red"> DEJA FAIT </div>';
        }
        $GLOBALS['donesFK'][$row[$this->vpk]] = true;

        if ($doli)
            $this->html .= '<li id="' . $this->field . '_' . $row[$this->vpk] . '"> ';
        $a = new ajaxForm($this->vtable, $row[$this->vpk]);
        $this->html .= $a->genField(GetTitleFromTable($this->vtable)) . str_replace("[ID]", $row[$this->vpk], $this->links);

        $this->getSubs($row[$this->vpk]);

        if ($doli)
            $this->html .='</li>';


        /* echo $this->html;
          $this->html = ""; */
    }

    function getValue() {
        
    }

}

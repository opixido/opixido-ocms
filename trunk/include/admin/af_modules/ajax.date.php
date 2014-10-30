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

class ajaxDate {

    function __construct($af, $champ) {

        $this->af = $af;
        $this->champ = $champ;
        $this->row = $af->row;
        $this->table = $af->table;
        $this->id = $af->id;

        $this->champ_id = $this->table . '-' . $champ . '-' . $this->id;
    }

    function gen() {


        $d = strtotime($this->getCurrent());
        if (!isNull($this->getCurrent())) {
            $date = date('Y-m-d', $d);
        }


        $html .= ('<input type="text" maxsize="10" size="7" name="' . $this->champ_id . '" id="' . $this->champ_id . '" value="' . $date . '" />');


        $html .= ('<script type="text/javascript">
		$(function() {
			$("#' . $this->champ_id . '").datepicker({
					showOn: "button",
					buttonImage: "img/calendar.gif", 
					buttonImageOnly: true,
					changeMonth: true,
					changeYear: true,
					showButtonPanel: true,
					dateFormat:"yy-mm-dd",
					showAnim:"slideDown",
					buttonText:' . alt(t('calendar')) . '
			});
		});
		</script>
		');


        return $html;
    }

    function getCurrent() {

        return $this->row[$this->champ];
    }

}

?>
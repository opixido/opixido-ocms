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

$date = $this->tab_default_field[$name];
$t = strtotime($this->tab_default_field[$name]);

if ($t > 0) {

    $hh = date('H', $t);
    $mm = date('i', $t);
    $ss = date('s', $t);

    $dat = $t <= 10000 ? '' : date('Y-m-d', $t);
} else {
    $hh = $mm = $ss = $dat = '';
}

if (!$this->editMode) {

    $this->addBuffer('<input type="text" maxsize="10" size="7" name="genform_' . $name . '" id="genform_' . $name . '" value="' . $dat . '" />');

    $this->addBuffer('<input type="text" ' . $jsColor . ' name="genform_' . $name . '_hh" size="2"  style="text-align:center;width:20px" maxlength="2" value="' . $hh . '" /> h');
    $this->addBuffer('<input type="text" ' . $jsColor . ' name="genform_' . $name . '_mm" size="2" style="text-align:center;width:20px" maxlength="2" value="' . $mm . '" /> m');
    $this->addBuffer('<input type="text" ' . $jsColor . ' name="genform_' . $name . '_ss" size="2" style="text-align:center;width:20px" maxlength="2" value="' . $ss . '" /> s');


    $this->addBuffer('<script type="text/javascript">
	$(function() {
		$("#genform_' . $name . '").datepicker({
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
} else {

    $this->addBuffer(niceDateTime($date));
}

	
	

	
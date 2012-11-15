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
/*

  $day = date('d',$t);
  $month = date('m',$t);
  $year = date('Y',$t);
 */

if (!$this->editMode) {

    $this->addBuffer('<input type="text" maxsize="10" size="7" name="genform_' . $name . '" id="genform_' . $name . '" value="' . $date . '" />');

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
    if (!isNull($date))
        $this->addBuffer(niceTextDate($date));
}


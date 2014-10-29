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
/**
 * TIME
  /** ICI YA LE REAL ---

  if ( !$this->editMode )
  $this->addBuffer( "<input  " . $jsColor . " type='text' name='genform_" . $name . "' size='8' maxlength='$len' value='" . abs( $this->tab_default_field[$name] ) . "' />" );
  else
  $this->addBuffer( $this->tab_default_field[$name] );


  } else if ( $this->tab_field[$name]->type == "time" ) {
 */
if (!$this->editMode) {
    if ($this->tab_default_field[$name] == "00:00:00" || $this->tab_default_field[$name] == "") {
        // $this->tab_default_field[$name] = date("H:m:s");
    }
    $timeTab = explode(":", $this->tab_default_field[$name]);

    $sec = akev($timeTab, 2);
    $min = akev($timeTab, 1);
    $hour = $timeTab[0];
    $this->addBuffer('<input ' . $jsColor . ' type="text" name="genform_' . $name . '_hour" size="2" class="genform_champ_centered" maxlength="2" value="' . $hour . '" />&nbsp;h&nbsp;');
    $this->addBuffer('<input ' . $jsColor . ' type="text" name="genform_' . $name . '_min" size="2" class="genform_champ_centered" maxlength="2" value="' . $min . '" />&nbsp;m&nbsp;');
    $this->addBuffer('<input ' . $jsColor . ' type="text" name="genform_' . $name . '_sec" size="2" class="genform_champ_centered" maxlength="2" value="' . $sec . '" />&nbsp;s&nbsp;');

    $this->genHiddenItem('genform_' . $name, '');
} else {
    $timeTab = explode(":", $this->tab_default_field[$name]);
    $heure = "";

    if ($timeTab[0] > 0)
        $heure .= $timeTab[0] . "h";
    if ($timeTab[1] > 0)
        $heure .= $timeTab[1] . "m";
    if ($timeTab[2] > 0)
        $heure .= $timeTab[2] . "s";
    $this->addBuffer($heure);
}
?>
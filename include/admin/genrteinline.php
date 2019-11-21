<?php

/**
 * This file is part of oCMS.
 *
 * oCMS is free software: you cgan redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * oCMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with oCMS. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Celio Conort / Opixido 
 * @copyright opixido 2012
 * @link http://code.google.com/p/opixido-ocms/
 * @package ocms
 * */
class genRteInline {

    var $toolbar;

    function __construct($champ, $valeur = "", $toolbar = 'Default', $tabContent = '') {
        $this->toolbar = $toolbar;
        $this->tabContent = $tabContent;
        $this->champ = $champ;
        $this->valeur = $valeur;
    }

    function gen() {
        return $this->createRte($content);
    }

    function createRte() {
        global $formFooters, $champsRTE;

        $formFooters = '';
        $GLOBALS['rteElements'] .= $this->champ . ', ';

        $html = $this->instanceRte();



//separator,insertdate,inserttime,print,help <script language="javascript" type="text/javascript"> //xhtmlxtras,accessilink,iespell,insertdatetime,searchreplace,print,contextmenu,paste,styleselect

        if (!$GLOBALS['rteIncluded']) {
            $formFooters .= ' 
		    <script src="../vendor/tinymce/tinymce/tinymce.min.js"></script>
			';
        }

        $GLOBALS['rteIncluded'] = true;


        return $html . $formFooters;
    }

    function instanceRte() {

        $html = ('<textarea  name="' . $this->champ . '" id="' . $this->champ . '" 
						style="height:300px;width:500px" > ' . $this->valeur . ' </textarea >
						
						<script type="text/javascript">
							toggleRteInline("' . $this->champ . '")
						</script>
						
						');

        return $html;
    }

}

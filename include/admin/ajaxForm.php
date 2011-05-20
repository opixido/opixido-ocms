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

class ajaxForm {

    public $table;
    public $id;
    public $row;

    function __construct($table, $id="new") {

	$this->table = $table;
	$this->id = $id;

	if ($this->id != 'new') {
	    $this->row = getRowFromId($this->table, $this->id);
	}

	$this->tab_field = getTabField($this->table);
    }

    function gen() {
	
    }

    function genLabel() {
	
    }

    function genField($champ) {

	global $_Gconfig;

	if (isBaseLgField($champ, $this->table)) {

	    $htmlLgs = '';
	    $nbLgs = count($_Gconfig['LANGUAGES']);
	    foreach ($_Gconfig['LANGUAGES'] as $v) {
		$htmlLgs .= '<option value="' . $v . '" style="background:url(img/flags/' . $v . '.gif) 2px 2px no-repeat;padding-left:20px">' . $v . '</option>';
		$this->genOneField($champ . '_' . $v, true);
		$html .= '<span class="lg_' . $v . '">' . $this->getBuffer(true) . '</span>';
	    }

	    $js = '		
			<script type="text/javascript">				
				//-TOEVAL-
				window.ajax_cur_lg["' . $this->table . '-' . $champ . '-' . $this->id . '"] = "' . LG_DEF . '";				
				ajaxLgs("' . $this->table . '-' . $champ . '-' . $this->id . '");
				//-ENDEVAL-
			</script>
			';
	    $htmlRet .= '<div id="' . $this->table . '-' . $champ . '-' . $this->id . '" class="ajax_lgs">';
	    if ($nbLgs > 1) {
		$htmlRet .= '<select class="ajax_lg_select">' . $htmlLgs . '</select>';
	    }
	    $htmlRet .= '' . $html . '</div>' . $js;

	    return $htmlRet;
	} else {

	    $this->genOneField($champ);
	    return $this->getBuffer(true);
	}
    }

    function genOneField($champ) {


	global $relations, $uploadFields;

	if (false && ( in_array($champ, $uploadFields) || in_array(getBaseLgField($champ), $uploadFields) )) {

	    $GLOBALS['gb_obj']->includeFile('ajax.upload.php', 'admin/af_modules');

	    $f = new ajaxUpload($this, $champ);

	    $this->addBuffer($f->gen());
	} else if (!empty($relations[$this->table][$champ])) {

	    $GLOBALS['gb_obj']->includeFile('ajax.relations.php', 'admin/af_modules');

	    $f = new ajaxRelations($this, $champ, $relations[$this->table][$champ]);

	    $this->addBuffer($f->gen());
	} else if ($this->tab_field[$champ]->type == 'enum') {

	    $GLOBALS['gb_obj']->includeFile('ajax.enum.php', 'admin/af_modules');

	    $f = new ajaxEnum($this, $champ);

	    $this->addBuffer($f->gen());
	} else if ($this->tab_field[$champ]->type == 'tinyint' && $this->tab_field[$champ]->size < 2) {

	    $GLOBALS['gb_obj']->includeFile('ajax.bool.php', 'admin/af_modules');

	    $f = new ajaxBool($this, $champ);

	    $this->addBuffer($f->gen());
	} else if ($this->tab_field[$champ]->type == 'datetime') {

	    $GLOBALS['gb_obj']->includeFile('ajax.datetime.php', 'admin/af_modules');

	    $f = new ajaxDateTime($this, $champ);

	    $this->addBuffer($f->gen());
	} else {

	    $GLOBALS['gb_obj']->includeFile('ajax.varchar.php', 'admin/af_modules');

	    $f = new ajaxVarchar($this, $champ);

	    $this->addBuffer($f->gen());
	}

	return $this->getBuffer();
    }

    function addBuffer($str) {

	$this->strBuffer .= $str;
    }

    function cleanBuffer() {

	$this->strBuffer = '';
    }

    function getBuffer($andClean=false) {
	$str = $this->strBuffer;
	if ($andClean) {
	    $this->cleanBuffer();
	}

	return $str;
    }

}

?>
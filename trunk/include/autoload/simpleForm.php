<?php

class simpleForm {

    public $badFields = array();
    public $form_attr = '';
    private $fieldsetStarted = false;
    public $postLabel = ' : ';
    public $radioBeforeLabel = false;
    public $submitAsImage = false;
    public $maxFileSize = 3000000;

    function __construct($action='', $method='get', $id='') {

	$this->action = $action;
	$this->method = $method;
	$this->id = $id ? $id : 'form_' . rand(1, 1000);

	$this->neededSymbol = '<span class="important">*</span>';

	$this->defaultField = array('type' => 'text', 'value' => '', 'label' => 'Champ Inconnu', 'needed' => false);

	$_REQUEST['fromSimpleForm'] = akev($_REQUEST, 'fromSimpleForm') ? $_REQUEST['fromSimpleForm'] : '';
	return $this;
    }

    /**
     * Genere le formulaire
     * en bouclant sur les champs
     *
     * @return Code HTML du formulaire
     */
    function gen($needEnd = false) {

	$s = '<form id="' . $this->id . '" ' . $this->form_attr . ' class="simpleform" enctype="multipart/form-data" method="' . strtolower($this->method) . '" action="' . $this->action . '">' . "\n";


	reset($this->fields);
	$atLeastOneNeeded = false;
	foreach ($this->fields as $field) {
	    if ($field['needed']) {
		$atLeastOneNeeded = true;
		break;
	    }
	}

	if ($atLeastOneNeeded && $needEnd === false) {

	    $s .= '<div class="important need ' . (count($this->badFields) && $this->isSubmited() ? 'formError' : '') . '">' . t('simpleform_info_needed') . '</div>';
	}

	$this->add('hidden', $this->id, '', 'simpleform_submitted');


	if (akev($_REQUEST, 'oID')) {
	    $this->add('hidden', $_REQUEST['oID'], '', 'oID');
	}
	if (akev($_REQUEST, 'oLG')) {
	    $this->add('hidden', $_REQUEST['oLG'], '', 'oLG');
	}

	reset($this->fields);
	foreach ($this->fields as $field) {


	    if ($field['type'] == 'fieldset') {
		$s .= $this->getFieldset($field);
	    } else if ($field['type'] == 'endfieldset') {

		if ($this->fieldsetStarted) {
		    $s .= '</fieldset>';
		    $this->fieldsetStarted = false;
		}
	    } else {

		if ($field['type'] != 'html') {
		    $s .= '<div id="div_' . $field['id'] . '" class="simpleform_field div_' . $field['type'] . ' ' . ($field['needed'] ? 'needed' : '') . '">';
		}
		switch ($field['type']) {


		    case 'password':

			$s .= $this->getLabel($field);
			$s .= $this->getPassword($field);
			break;

		    case 'text':

			$s .= $this->getLabel($field);
			$s .= $this->getInputText($field);
			break;

		    case 'textarea':
			$s .= $this->getLabel($field);
			$s .= $this->getTextarea($field);

			break;

		    case 'wysiwyg':
			$s .= $this->getLabel($field);
			$s .= $this->getWysiwyg($field);

			break;

		    case 'email':

			$s .= $this->getLabel($field);
			$s .= $this->getInputText($field);

			break;
		    case 'submit':
			$s .= $this->getSubmit($field);
			break;
		    case 'image':
			$s .= $this->getSubmitImage($field);
			break;
		    case 'hidden':

			$s .= $this->getHidden($field);
			break;
		    case 'select':
			$s .= $this->getLabel($field);
			$s .= $this->getSelect($field);
			break;
		    case 'selectm':
			$s .= $this->getLabel($field);
			$s .= $this->getSelect($field, true);
			break;
		    case 'codepostal':
			$s .= $this->getLabel($field);
			$s .= $this->getInputText($field);
			break;

		    case 'radio':
			if ($this->radioBeforeLabel) {
			    $s .= $this->getRadio($field);
			    $s .= $this->getLabel($field);
			} else {
			    $s .= $this->getLabel($field);
			    $s .= $this->getRadio($field);
			}
			break;

		    case 'captcha':
			$s .= $this->getLabel($field);
			$s .= $this->getCaptcha($field);
			break;

		    case 'captcha_question':
			$s .= $this->getLabel($field);
			$s .= $this->getCaptchaQuestion($field);
			break;

		    case 'html':
			$s .= $field['value'];
			break;

		    case 'file':
			$s .= $this->getLabel($field);
			$s .= $this->getFile($field);
			break;

		    case 'date':
			$s .= $this->getLabel($field);
			$s .= $this->getDate($field);
			break;

		    case 'checkbox':

			$s .= $this->getCheckbox($field);
			break;

		    case 'email_conf':

			$s .= $this->getLabel($field);
			$s .= $this->getInputText($field);

			if ($field['needed']) {
			    $field['name'] = $field['name'] . '_confirmation';
			    $field['label'] = $field['label'] . t('simpleform_confirmation');
			    if ($_REQUEST[$field['name']]) {
				$field['value'] = $_REQUEST[$field['name']];
			    }

			    $s .= $this->getLabel($field);
			    $s .= $this->getInputText($field);
			}
			break;
		}


		if ($field['type'] != 'html')
		    $s .= '</div>';
	    }
	}

	if ($atLeastOneNeeded && $needEnd === true) {
	    $s .= '<div class="important need">' . t('simpleform_info_needed') . '</div>';
	}

	$s .= '<div >' . $this->getHidden(array('name' => 'fromSimpleForm', 'value' => '1', 'id' => $this->getNextId())) . '</div>';

	if ($this->fieldsetStarted) {
	    $s .= '</fieldset>';
	    $this->fieldsetStarted = false;
	}

	$s .= '</form>' . "\n";

	$s .= '
				<script type="text/javascript">		
				$("#' . $this->id . '").submit(function(){
				
					var neededFields  = new Array(0	';

	foreach ($this->fields as $k => $v) {
	    if ($v['needed']) {
		$s .= ',' . alt($k);
	    }
	}
	$s .= ' );
	    
	    var errorFields = new Array();
	    var len = neededFields.length;
	    var message = '.alt(t('simpleform_check')).' +"\n\n";
	    var errorFound = false;
	    var validRegExp = /^[^@]+@[^@]+.[a-z]{2,}$/i;
	    for(p=0;p<=len;p++) {
		    ob = $("#"+neededFields[p]);

		    if(ob.val() == "") {
			    $("#div_"+neededFields[p]).addClass("formError");
			    errorFound = true;
			    message += "- "+$("#div_"+neededFields[p]+" label:first span:first").text()+"\n";
		    }
		    else if(ob.attr("rel") == "email" && ob.val().search(validRegExp) == -1) {
				    errorFound = true;
				    message += "- "+$("#div_"+neededFields[p]+" label:first span:first").text()+"\n";
		    } else {
			    $("#div_"+neededFields[p]).removeClass("formError");
			    $("#div_"+neededFields[p]+" label").removeClass("formError");
		    }
	    }				
	    if(errorFound) {
		    alert(message);
		    return false;
	    }
	    });
	    </script>
';


	return $s;
    }

    function getDate($field) {

	$s = '<input ' . akev($field,'tag') . ' class="date"' . $this->classError($field) . ' type="text" name="' . $field['name'] . '" id="' . $field['id'] . '" />' . "\n";

	$s .= '
		<script type="text/javascript">
		$(document).ready(
		function(){
			$("#' . $field['id'] . '").datepicker({
				showOn: "button",
				buttonImage: "' . BU . '/admin/img/calendar.gif", 
				buttonImageOnly: true,
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true,
				dateFormat:"dd/mm/yy",
				showAnim:"slideDown",
				buttonText:' . alt(t('calendar')) . '
			})
		}
		);
		
		</script>
		';

	return $s;
    }

    /**
     * Verifie si tous les champs obligratoires sont correctements renseignes
     *
     * @return boolean
     */
    function isValid() {

	$isvalid = True;
	reset($this->fields);
	foreach ($this->fields as $field) {
	    if ($field['needed']) {
		$v = akev($_REQUEST,$field['name']);



		if ($field['type'] == 'text' && !strlen(trim($v))) {
		    $this->badFields[$field['name']] = $field;
		} else if ($field['type'] == 'password' && !strlen(trim($v))) {
		    $this->badFields[$field['name']] = $field;
		} else if ($field['type'] == 'email' && !checkEmail($v)) {
		    $this->badFields[$field['name']] = $field;
		} else if ($field['type'] == 'textarea' && !strlen(trim($v))) {
		    $this->badFields[$field['name']] = $field;
		} else if (( $field['type'] == 'email_conf' ) && (!checkEmail($v) || $v != $_REQUEST[$field['name'] . '_confirmation'])) {
		    $this->badFields[$field['name']] = $field;
		} else if ($field['type'] == 'select' && !$v) {

		    $this->badFields[$field['name']] = $field;
		} else if ($field['type'] == 'checkbox' && count($v) == 0) {

		    $this->badFields[$field['name']] = $field;
		}
		if ($field['type'] == 'captcha') {
		    if (($v == "" || strtolower($v) != strtolower($_SESSION['CAPTCHAString'])) && !$GLOBALS['CAPTCHAOK']) {
			$this->badFields[$field['name']] = $field;
			$GLOBALS['CAPTCHAOK'] = true;
		    } else {
			$_SESSION['CAPTCHAString'] = '';
		    }
		} else if ($field['type'] == 'file') {

		    if (($_FILES[$field['name']]['error'])) {
			$this->badFields[$field['name']] = $field;
		    }
		}
	    }

	    if ($field['type'] == 'captcha_question') {
		if (!$_REQUEST['captchaq_uniq'] || !$_REQUEST['captchaq'] || $_REQUEST['captchaq'] != $_SESSION['captchaQuestion'][$_REQUEST['captchaq_uniq']]) {
		    $this->badFields[$field['name']] = $field;

		    $_SESSION['captchaQuestion'][$_REQUEST['captchaq_uniq']] = '';
		}
		$_SESSION['captchaQuestion'][$_REQUEST['captchaq_uniq']] = '';
	    }
	}

	if (count($this->badFields))
	    $isvalid = false;
	return $isvalid;
    }

    /**
     * Check if we are coming from this same form or not
     *
     * @return boolean
     */
    function isSubmited() {
	if ($_REQUEST['fromSimpleForm'] && $_REQUEST['simpleform_submitted'] == $this->id) {
	    return True;
	} else {
	    return False;
	}
    }

    /**
     * Erreurs
     *
     * @param array $field
     * @return string
     */
    function classError($field) {

	if ($this->isSubmited() && array_key_exists($field['name'], $this->badFields)) {
	    return ' class="formError" ';
	}
	return '';
    }

    /**
     * Retourne un champ select
     *
     * @param array $field
     */
    function getSelect($field, $ismultiple=false) {

	$s = '' . "\n";
	$multi = false;
	if ($ismultiple) {
	    $multi = ($ismultiple ? 'multiple="multiple"' : '');
	    $field['name'] = $field['name'] . '[]';
	}

	$s = '<select ' . akev($field, 'tag') . ' ' . $this->classError($field) . ' ' . $multi . ' name="' . akev($field, 'name') . '" id="' . akev($field, 'id') . '" >' . "\n";
	if (!akev($field, 'needed'))
	    $s .= '<option value="">-------------</option>';

	if ($field['selected'] && !is_array($field['selected'])) {
	    $field['selected'] = array($field['selected']);
	}

	if (is_array($field['value'])) {
	    foreach ($field['value'] as $k => $value) {

		if (!is_array($value)) {
		    $val = $value;
		    $value = array();
		    $value['label'] = $val;
		    $value['value'] = $k;
		}

		if (empty($value['value']) && !empty($value[1])) {
		    $value['value'] = $value[0];
		    $value['label'] = $value[1];
		}

		$sel = @in_array(trim($value['value']), $field['selected']) ? 'selected="selected"' : '';
		$s .= '<option ' . $sel . ' value="' . $value['value'] . '">' . $value['label'] . '</option>' . "\n";
	    }
	}




	//debug($field['selected']);

	$s .='</select>' . "\n";

	return $s;
    }

    /**
     * Génère une image Captcha
     *
     * @return unknown
     */
    function getCaptcha($field) {

	if (is_object($GLOBALS['site']->plugins['captcha'])) {

	    $html = '<div class="captcha">
						<img style="float:left;" id="captcha" src="' . $GLOBALS['site']->plugins['captcha']->getImg() . '" alt="" /> 
					 ' . t('simpleform_captcha') . ' 
					 <br/>
					 <input  ' . akev($field,'tag') . ' type="text" name="' . $field['name'] . '" value="" maxlength="6" size="6" />
					 <a class="reload_captcha" href="javascript:;" onclick="javasript:gid(\'captcha\').src = gid(\'captcha\').src+\'r\'">' . t('simpleform_reload_captcha') . '</a>
					 </div> 
					 <div class="clearer">&nbsp;</div>
					 ';
	} else {
	    $html = '<strong>Plugin captcha is missing</strong>';
	}
	return $html;
    }

    /**
     * Génère un captcha par addition
     *
     * @param unknown_type $field
     */
    function getCaptchaQuestion($field) {

	$chiffre1 = rand(1, 10);
	$chiffre2 = rand(1, 10);
	$unique = time() . rand(0, 1000);
	$_SESSION['captchaQuestion'][$unique] = $chiffre1 + $chiffre2;

	$html .= '<span class="captcha_q">' . t('simpleform_captchaq') . ' 
					<strong>' . $chiffre1 . ' + ' . $chiffre2 . ' = </strong>
					<input  ' . akev($field,'tag') . ' id="' . $field['id'] . '" type="text" name="captchaq" class="text captchaq" value="" size="2" />
					<input type="hidden" name="captchaq_uniq" class="hidden" value="' . $unique . '"/>
					</span>
					
					';

	return $html;
    }

    /**
     * Returns the start or end of a fieldset
     *
     * @param array $field
     */
    function getFieldset($field) {
	$html = '';
	if ($this->fieldsetStarted) {
	    $html .= '</fieldset>';
	}
	$html .= '<fieldset ' . ($field['id'] ? 'id="' . $field['id'] . '"' : '') . '>';

	if ($field['value']) {
	    $html .= '<legend>' . $field['value'] . '</legend>';
	}

	$this->fieldsetStarted = true;
	return $html;
    }

    /**
     * Retourne un champ <input type="text" ...
     *
     * @param array $field Tableau du champ
     * @return Code HTML du input
     */
    function getInputText($field) {

	$r = akev($field,'type') == 'email' ? 'rel="email"' : '';
	$s = '<input  ' . akev($field,'tag') . ' ' . $r . ' ' . (akev($field,'disabled') ? 'disabled="disabled"' : '' ) . ' class="text" ' . $this->classError($field) . ' type="text" name="' . akev($field,'name') . '" id="' . $field['id'] . '" value=' . alt($field['value']) . ' />' . "\n";

	return $s;
    }

    /**
     * Retourne un champ <input type="text" ...
     *
     * @param array $field Tableau du champ
     * @return Code HTML du input
     */
    function getCheckbox($field) {

	$s = '<div class="checkbox" >' . "\n";

	if (!is_array($field['value'])) {
	    return '';
	}
	if ($field['label']) {
	    $s .= '<fieldset ' . $this->classError($field) . '><legend>' . $field['label'] . ' ' . ($field['needed'] ? $this->neededSymbol : '') . '</legend>';
	    $end = '</fieldset>';
	} else {
	    $s .= '<div ' . $this->classError($field) . '>';
	    if ($field['needed']) {
		$field['value'][0]['label'] .=$this->neededSymbol;
	    }
	    $end = '</div>';
	}
	if ($ismultiple) {
	    $multi = ($ismultiple ? 'multiple="multiple"' : '');
	    $field['name'] = $field['name'] . '[]';
	}


	if ($field['selected'] && !is_array($field['selected'])) {
	    $field['selected'] = array($field['selected']);
	}

	foreach ($field['value'] as $k => $value) {
	    if (!is_array($value)) {
		$val = $value;
		$value = array();
		$value['label'] = $val;
		$value['value'] = $val;
	    }
	    $sel = @in_array(trim($value['value']), $field['selected']) ? 'checked="checked"' : '';
	    $s .= '<input type="checkbox" id="' . $field['name'] . '_' . $value['value'] . '" name="' . $field['name'] . '[]" ' . $sel . ' value="' . $value['value'] . '"><label id="label_' . $field['name'] . '_' . $value['value'] . '" for="' . $field['name'] . '_' . $value['value'] . '">' . $value['label'] . '</label>' . "\n";
	}

	$s .=$end;

	$s .= '</div>' . "\n";

	return $s;
    }

    function getFile($field) {

	$s = '<input type="hidden" name="MAX_FILE_SIZE" value="' . $this->maxFileSize . '" />
		<input  ' . akev($field,'tag') . '  ' . $this->classError($field) . ' type="file" name="' . $field['name'] . '" id="' . $field['id'] . '" />' . "\n";

	return $s;
    }

    /**
     * Retourne un champ <input type="radio" ...
     *
     * @param array $field Tableau du champ
     * @return Code HTML du input
     */
    function getRadio($field) {

	$disabled = ( $field ['disabled'] ) ? 'disabled="disabled"' : '';

	$checked = ( $field ['selected'] === array('checked') ) ? 'checked="checked"' : '';

	$s = '<input ' . akev($field,'tag') . ' class="radio_input" ' . $this->classError($field) . ' type="radio" name="' . $field['name'] . '" id="' . $field['id'] . '" value=' . alt($field['value']) . ' ' . $disabled . ' ' . $checked . ' />' . "\n";

	return $s;
    }

    /**
     * Retourne un champ <input type="password" ...
     *
     * @param array $field Tableau du champ
     * @return Code HTML du input
     */
    function getPassword($field) {

	$s = '<input  ' . akev($field,'tag') . ' class="password" autocomplete="off" ' . $this->classError($field) . ' type="password" name="' . $field['name'] . '" id="' . $field['id'] . '" />' . "\n";

	return $s;
    }

    /**
     * Retourne un champ <textarea ...
     *
     * @param array $field Tableau du champ
     * @return Code HTML du textarea
     */
    function getTextArea($field) {

	$s = '<textarea   ' . akev($field,'tag') . ' rows="5" cols="20" ' . $this->classError($field) . ' name="' . $field['name'] . '" id="' . $field['id'] . '" >' . $field['value'] . '</textarea>' . "\n";

	return $s;
    }

    /**
     * Retourne un champ wysiwyg ...
     *
     * @param array $field Tableau du champ
     * @return Code HTML du wysiwyg
     */
    function getWysiwyg($field) {

	$s = '<textarea   ' . akev($field,'tag') . ' rows="5" cols="60" ' . $this->classError($field) . ' name="' . $field['name'] . '" id="' . $field['id'] . '" >' . $field['value'] . '</textarea>' . "\n";
	//$s .= '<script type="text/javascript" src="'.BU.'/wyzz/wyzz.js"></script>';
	$s .= '<script type="text/javascript" src="' . BU . '/nicedit/nicEdit.js"></script>';
	//$s .= '<script type="text/javascript">make_wyzz("'.$field['id'].'");</script>';
	$s .= '<script type="text/javascript">new nicEditor({iconsPath : "' . BU . '/nicedit/nicEditorIcons.gif"}).panelInstance("' . $field['id'] . '"); </script>';
	return $s;
    }

    /**
     * Retourne un champ <input type="submit"
     *
     * @param unknown_type $field
     * @return unknown
     */
    function getSubmit($field) {

	if ($this->submitAsImage && function_exists('getImgText')) {
	    $s = '<input  ' . akev($field,'tag') . ' class="submitimg" src="' . getImgTextSrc($field['value'], 'submit') . '" type="image" name="' . $field['name'] . '" id="' . $field['id'] . '" alt=' . alt($field['value']) . ' />' . "\n";
	} else if (!empty($field['image'])) {
	    $s = '<input  ' . akev($field,'tag') . ' class="submit" type="image" name="' . $field['name'] . '" id="' . $field['id'] . '" value=' . alt($field['value']) . ' src=' . alt($field['image']) . '/>' . "\n";
	} else {
	    $s = '<input  ' . akev($field,'tag') . ' class="submit" type="submit" name="' . $field['name'] . '" id="' . $field['id'] . '" value=' . alt($field['value']) . ' />' . "\n";
	}
	return $s;
    }

    /**
     * Retourne un champ <input type="submit"
     *
     * @param unknown_type $field
     * @return unknown
     */
    function getSubmitImage($field) {

	$s = '<input  ' . akev($field,'tag') . ' class="submitimage" type="image" name="' . $field['name'] . '" id="' . $field['id'] . '" value=' . alt($field['label']) . ' src=' . alt($field['value']) . ' />' . "\n";

	return $s;
    }

    /**
     * Retourne un champ hidden
     *
     * @param array $field Tableau du champ
     * @return Code HTML du hidden
     */
    function getHidden($field) {
	$s = '<input  ' . akev($field, 'tag') . ' class="hidden" type="hidden" name="' . $field['name'] . '" id="' . $field['id'] . '" value=' . alt($field['value']) . ' />' . "\n";
	return $s;
    }

    /**
     * Retourne le label pour le champ donn?
     *
     * @param array $field Tableau du champ
     * @return Code HTML du label
     */
    function getLabel($field) {
        $s = '';
	if (strlen($field['label'])) {
	    $needed = !empty($field['needed']) ? $this->neededSymbol : '';
	    $s = '<label   ' . akev($field, 'tag') . '  ' . $this->classError($field) . ' id="label_' . akev($field, 'id') . '" for="' . akev($field, 'id') . '"><span>' . akev($field, 'label') . '</span> ' . $needed . '' . $this->postLabel . '</label>' . "\n";
	}
	return $s;
    }

    /**
     * Rajoute un champ
     *
     *
     * @param array $infos Doit etre un array avec les clef suivantes :
     * 				type,value,label,name,id,needed
     */
    function addfield($infos) {
	if (!$infos['id'] || !strlen($infos['id'])) {

	    $infos['id'] = $this->getNextId();
	}

	$this->fields[] = $infos;
    }

    /**
     * Comme addField mais avec les infos en parametres plutot que dans un tableau
     *
     * @param string $type text,textarea,hidden,select,submit,email
     * @param string|array $value Pour les selects : Array('value'=>,'label'=>)
     * @param string $label Nom du champ pour les internautes
     * @param string $name nom du champ pour le formulaire
     * @param mixed $id Identifiant pour javascript/css
     * @param boolean $needed Champ obligatoire ?
     */
    function add($type='text', $value='', $label='', $name='', $id=false, $needed=false, $selected=array(), $disabled=false) {
	if (!$id || !strlen($id)) {
	    $id = $this->getNextId();
	}
	if (!is_array($value) && !empty($_REQUEST[$name]) && $name && $this->isSubmited()) {
	    $value = $_REQUEST[$name];
	}
	if ($type == 'captcha') {
	    $needed = true;
	    $name = 'captcha_code';
	}

	if (is_array($value) && !empty($_REQUEST[$name]) && !is_array($_REQUEST[$name]) && !$selected) {
	    $selected = array($_REQUEST[$name]);
	}

	$this->fields[$id] = array(
	    'type' => $type,
	    'value' => $value,
	    'label' => $label,
	    'name' => $name,
	    'id' => $id,
	    'needed' => $needed,
	    'selected' => $selected,
	    'disabled' => $disabled,
	    'tag' => '');
	
	return $this;
    }

    /**
     * Pour les champs sans ID="" on en créé un automatiquement
     *
     */
    function getNextId() {

	$this->nbField++;
	return $this->id . '_field_' . $this->nbField;
    }

}


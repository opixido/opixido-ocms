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

/* * *********************
 *
 *   Popup d'administration via le front office
 *
 * ******************** */

class genPopupAdmin {

    function genPopupAdmin($table, $id) {

        $this->table = $table;
        $this->id = $id;
        $this->admin = new GenAdmin($table, $id);

        $this->table = $this->admin->table;
        $this->id = $this->admin->id;

        $this->field = strstr($_REQUEST['field'], "_-_") ? explode("_-_", $_REQUEST['field']) : $_REQUEST['field'];
        if (!$this->field) {
            $this->field = $_SESSION['lastUsedField'];
        } else {
            $_SESSION['lastUsedField'] = $this->field;
        }
    }

    /*
      Dispatcher des actions
     */

    function gen() {

        global $rteFields;
        //debug($_POST);
        //debug($_SESSION['nbLevels']);



        if ($_REQUEST['getUp'] || $_REQUEST['getDown']) {
            $this->doOrder();
        } else if (false && in_array($this->field, $rteFields)) {
            /* Champ RTE -> On inclue le RTE directement */
            $gl = new GenLocks();
            $gl->setLock($this->table, $this->id, $this->field);

            $this->doRte();
        } else


        /* Suppression */
        /* && count($_SESSION['levels']) < 1 */
        if ((count($_POST) && ( $_POST['genform_cancel'] != '' || $_POST['genform_ok_close'] != '' || $_POST['genform_cancel_x'] != '' || $_POST['genform_ok_close_x'] != '' )) && !array_key_exists('genform_stay', $_REQUEST) && $_SESSION['previousLevel'] == 0) {

            $this->reload();

            die();
        } else {
            //debug($_SESSION['nbLevels']);
            //debug($this->table);

            $gl = new GenLocks();
            $gl->setLock($this->table, $this->id, $this->field);

            if ($_SESSION['nbLevels'] > 0) {
                $this->doRealForm();
            } else {
                $this->doForm();
            }



            //debug($_SESSION['nbLevels']);
            //$this->admin->whichForm();
        }
        $_SESSION['previousLevel'] = $_SESSION['nbLevels'];
        p('<script type="text/javascript"> window.focus(); </script>');
    }

    function doRealForm() {
        global $form, $gb_obj;

        $gb_obj->includeFile('inc.header.php', 'admin_html');

        p('<style type="text/css">body {margin:5px;} #bandeau {display:none}');
        p('</style>');


        $form = new GenForm($this->admin->table, '', $this->admin->id);

        $form->GenHeader();
        $form->genHiddenItem('gfa', $_REQUEST['gfa']);
        $form->genPages();
        $form->GenFooter();
        $gb_obj->includeFile('inc.footer.php', 'admin_html');
    }

    function doForm() {
        global $form, $gb_obj;

        //include('header.popup.php');

        /*  p(' <link rel="stylesheet" type="text/css" href="css/style.css" />
          <link rel="StyleSheet" href="genform/css/genform.css" />
          <script language="JavaScript1.2" src="genform/js/tjmlib.js"></script>
          <script language="JavaScript1.2" src="js/script.js"></script>
          '); */

        if ($_POST['cancel']) {
            echo '<script type="text/javascript">top.location = top.location</script>';
            die();
        }

        $gb_obj->includeFile('inc.header.php', 'admin_html');

        $gl = new GenLocks();

        $gl->setLock($this->admin->table, $this->admin->id);


        $form = new GenForm($this->admin->table, '', $this->admin->id);

        $field = $this->field;

        p('<style type="text/css">body {margin:0px;} #bandeau {display:none}');
        if ($field != 'all') {
            p('
		.genform_onglet {display:none;} 
		#genform_navi , .helpimg{
			display:none;
		}
		body ,html {
			overflow:hidden;
			height:100%;
			width:100%;
			padding:0;margin:0;
		}
		#zegenform {
			padding:0px;margin:0px;
			padding-top:0px;
			background:none;
			border:0;
		}
		label, .genform_txt {
			border-top:1px solid red;
			border:0;
			padding:1px;
			marign:0;
			background:none;
			position:absolute;
			left:1px; top:2px;
		}
		
		.genform_champ {
			margin:0;padding:0;
			margin-top:4px;
			border:0;
			background:0;
		}
		
		#genform_div_' . $field . ' {
			
			left:2px;
			top:0px;
		}
		
		#genform_header_btn {
			position:absolute;
			right:0;
			top:0;
		}
		
		 .genform_champ textarea {
			position:absolute;
			width:auto;
			height:auto;
			top:25px;
			left:2px;
			right:2px;
			bottom:2px;
			
		}	
		
		#small_submit {
			position:absolute;
			right:0;
			top:0;
		}
		
		#small_submit input {
			border:0;
		}
		.lgbtn, .lgbtn_on , .lgbtn img{
			border:0 !important;
			padding:0!important;
			margin:0;
			background:none;
		}
		.lgbtn_on img{
			opacity:0.3;
			filter:alpha(opacity=30);
		}
		');
        }
        p('</style>');

        $form->GenHeader();
        if (is_array($field))
            $form->genHiddenItem('field[]', implode(',', $field));
        else
            $form->genHiddenItem('field', $field);

        if (is_array($field)) {
            foreach ($field as $f) {
                $form->gen($f);
            }
        } else if ($field == 'all') {

            $form->genPages();
        } else {
            if (isBaseLgField($field, $this->table)) {
                $form->genlg($field);
            } else {
                $form->gen($field);
            }
        }

        if ($_REQUEST['genhidden']) {

            $form->tab_default_field[$_REQUEST['genhidden']] = $_REQUEST[$_REQUEST['genhidden']];

            $form->genHiddenField($_REQUEST['genhidden']);
        }

        echo '
	<div id="small_submit">
	<input type="image" name="save" src="./pictos/document-save.png" />
	<input type="image" name="cancel" src="' . BU . '/admin/pictos/process-stop.png" />
	</div>
	';


        $form->GenFooter();


        $gb_obj->includeFile('inc.footer.php', 'admin_html');
    }

    function reload() {
        global $genMessages;

        $genMessages->gen();

        $gl = new GenLocks();

        $gl->unsetAllLocks();

        p('<script type="text/javascript">window.opener.location.reload(true);top.close();</script>');
    }

    function doRte() {
        global $gb_obj;
        /* Affichage du Wysiwyg */
        // p('<html><body style="padding:0;margin:0">');
        $gb_obj->includeFile('inc.header.php', 'admin_html');
        p('<style type="text/css">#bandeau {display:none}</style>');
        $_REQUEST['table'] = $this->table;
        $_REQUEST['id'] = $this->id;
        $_REQUEST['champ'] = $this->field;
        $_REQUEST['pk'] = GetPrimaryKey($this->table);

        //include('rte.php');

        $rte = new genRte();
        $rte->createRte();

        //p('</body></html>');
        $gb_obj->includeFile('inc.footer.php', 'admin_html');
        if (count($_POST)) {
            $this->reload();
        }



        die();
    }

    function doOrder() {

        $ord = new GenOrder($this->table, $this->id);
        if ($_REQUEST['getUp'])
            $ord->GetUp();
        else if ($_REQUEST['getDown'])
            $ord->GetDown();

        $ord->ReOrder();


        debug($ord);
        $this->reload();

        die();
    }

}

?>
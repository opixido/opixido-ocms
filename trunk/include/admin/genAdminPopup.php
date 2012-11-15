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

class genAdminPopup {

    function gen() {
        global $gb_obj;

        print '
		<html>
			<head>
			<link rel=stylesheet type="text/css" type="screen" href="css/style.css"/>
			<link rel="StyleSheet" href="genform/css/genform.css">
			<link rel="stylesheet" type="text/css" href="css/style_suite.css" />
			<script language="JavaScript1.2" src="genform/js/tjmlib.js"></script>
			<style>
			  #formCsv label{
				display: block;
				margin-bottom: 0;
			  }
			  .genform_txtres {
			  	visibility:hidden;
			  }
			  .genform_champres {
			  	width:auto;
			  }
			  .table_resume_label {
			  	display:none;
			  }
			</style>
			</head>
			<body>
			<div id="tooltip"></div>';


        if ($_REQUEST['preview']) {

            global $editMode, $onlyData;
            $editMode = true;
            //$onlyData = true;
            $form = new GenForm($_REQUEST['curTable'], "", $_REQUEST['curId'], "");

            //$form->genHeader();

            $ch = explode(";", $_REQUEST['champs']);

            foreach ($ch as $v) {

                if (isBaseLgField($v, $_REQUEST['curTable']))
                    $form->genlg($v);
                else
                    $form->gen($v);
            }
            die();
        } else
        if ($_REQUEST['doRte']) {
            $gRte = new genRte;
            $gRte->gen();
        } else
        if ($_REQUEST['doCsv']) {

            $gCsv = new Csv($_FILES['txt_csvFile']['tmp_name'], $_REQUEST['txt_summary'], $_REQUEST['txt_caption'], $_REQUEST['txt_delimiter'], $_REQUEST['txt_topHeader'], $_REQUEST['txt_leftHeader']);
            //print $gCsv->showCsvContent($gCsv->openCsvFile()) ;

            $langue = substr($_REQUEST['champ'], -3, 3);
            $champ_csv = substr($_REQUEST['champ'], 0, -3);
            $champ_csv .= '_csv' . $langue;

            if ($_REQUEST['id'] != 'new') {


                $sql = 'UPDATE ' . $_REQUEST['table'] . ' SET ' . $_REQUEST['champ'] . '="' . addslashes($gCsv->gen()) . '", ' . $champ_csv . '="' . addslashes($gCsv->showCsvContent($gCsv->openCsvFile())) . '" WHERE ' . $_REQUEST['pk'] . '=' . $_REQUEST['id'] . ' ';
                $exec = doSql($sql);

                $gRte = new genRte;
            } else {

                $_SESSION["genform_" . $_REQUEST['table']][$champ_csv] = addslashes($gCsv->showCsvContent($gCsv->openCsvFile()));
                $_SESSION[gfuid()]['curFields'][] = $_REQUEST['champ'];
                $gRte = new genRte('Default', $gCsv->gen());
            }

            return $gRte->gen();
        } else
        if ($_REQUEST['formCsv']) {
            $tpl = new genTemplate();
            $tpl->loadTemplate('csv.form.import');

            print $tpl->gen();
        }

        print '
		</body>
			</html>';
    }

}

?>
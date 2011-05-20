<?php

global $tabForms;

foreach ($tabForms as $table => $vals) {
    $t = getTabField($table);
    if(!empty($t['privee'])) {
	if (!empty($tabForms[$table]['pages']) && !is_array($tabForms[$table]['pages'])) {
	    $tabForms[$table]['pages'] = array($tabForms[$table]['pages']);
	}
	$tabForms[$table]['pages']['ocms_login'] = '../plugins/ocms_login/form.ocms_login.php';
    }
}

function laSaveFormRub($id) {

    $sql = 'SELECT * FROM s_rubrique WHERE rubrique_id = ' . sql($id);
    $r = GetSingle($sql);
    $r = getRowFromId('s_rubrique', $r['fk_rubrique_version_id']);


    DoSql('DELETE FROM e_groupe_contenu WHERE fk_id = ' . $r['rubrique_id'] . ' AND fk_table = "s_rubrique" ');
    DoSql('DELETE FROM e_utilisateur_contenu WHERE fk_id = ' . $r['rubrique_id'] . ' AND fk_table = "s_rubrique"  ');

    $sql = 'SELECT * FROM e_groupe_contenu WHERE fk_id = ' . sql($id) . ' AND fk_table = "s_rubrique"';
    $res = GetAll($sql);
    foreach ($res as $row) {
	DoSql('REPLACE INTO e_groupe_contenu SET fk_groupe_id = ' . sql($row['fk_groupe_id']) . ' , fk_table = "s_rubrique", fk_id = ' . sql($r['rubrique_id']));
    }

    $sql = 'SELECT * FROM e_utilisateur_contenu WHERE fk_id = ' . sql($id) . ' AND fk_table = "s_rubrique"';
    $res = GetAll($sql);
    foreach ($res as $row) {
	DoSql('REPLACE INTO e_utilisateur_contenu SET fk_utilisateur_id = ' . sql($row['fk_utilisateur_id']) . ' , fk_table = "s_rubrique", fk_id = ' . sql($r['rubrique_id']));
    }
}

class ocms_loginAdmin {

    function __construct() {
	$xhr = akev($_REQUEST, 'xhr');
	if ($xhr == 'laSearchUser') {


	    $sql = 'SELECT * FROM e_utilisateur_contenu WHERE 
						fk_id = ' . sql($_GET['id']) . ' 
						AND fk_table = ' . sql($_GET['table']);
	    $res = GetAll($sql);
	    $users = array();
	    foreach ($res as $row) {
		$users[] = $row['fk_utilisateur_id'];
	    }

	    if ($_REQUEST['laSearchText'] != "") {

		$sql = 'SELECT * FROM e_utilisateur WHERE utilisateur_nom 
							LIKE "%' . $_REQUEST['laSearchText'] . '%" OR 
							utilisateur_prenom LIKE "%' . $_REQUEST['laSearchText'] . '%"';

		$res = GetAll($sql);

		foreach ($res as $row) {

		    echo '<li 
							' . (!in_array($row['utilisateur_id'], $users) ?
			    'onclick="laAddUser(' . $row['utilisateur_id'] . ',this)" >
								<img src="./pictos/go-last.png" alt="Ajouter" />
								' : '><img src="./pictos/media-playback-stop.png" alt="Delete" />') . ' 
								 ' . $row['utilisateur_nom'] . ' ' . $row['utilisateur_prenom'] .
		    '</li>';
		}


		if (!count($res)) {
		    echo '<li>Aucun résultat</li>';
		}
	    } else {

		echo '<li>Saisissez un texte ci-dessus pour rechercher un utilisateur</li>';
	    }

	    die();
	} else if ($xhr == 'laAddUser') {

	    if ($_REQUEST['laUserId']) {

		$sql = 'REPLACE INTO e_utilisateur_contenu SET 
							fk_table = ' . sql($_REQUEST['table']) . ' , 
							fk_id = ' . sql($_REQUEST['id']) . ' , 
							fk_utilisateur_id = ' . sql($_REQUEST['laUserId']) . ' ';
		DoSql($sql);

		echo laGetSelectedUsers($_REQUEST['table'], $_REQUEST['id']);
		die();
	    }
	} else if ($xhr == 'laDelUser') {

	    if ($_REQUEST['laUserId']) {

		$sql = 'DELETE FROM e_utilisateur_contenu WHERE 
							fk_table = ' . sql($_REQUEST['table']) . ' AND
							fk_id = ' . sql($_REQUEST['id']) . ' AND
							fk_utilisateur_id = ' . sql($_REQUEST['laUserId']) . ' ';
		DoSql($sql);


		echo laGetSelectedUsers($_REQUEST['table'], $_REQUEST['id']);
		die();
	    }
	}
    }

    public static function ocms_getParams() {

	global $form;

	if (akev($form->tab_default_field, 'privee') < 1) {
	    return array();
	}

	$params = array();

	$params['canCreate'] = array('select', array(1 => 'yes', 0 => 'no'));
	$sql = 'SELECT groupe_type FROM e_groupe';

	$res = GetAll($sql);

	$tab = array();
	foreach ($res as $row) {
	    $tab[] = $row['groupe_type'];
	}

	$params['createGroup'] = array('select', $tab);
	$params['createType'] = array('text', 'simple');
	$params['validation'] = array('select', array('none', 'email', 'admin', 'both'));
	$params['condition'] = array('textarea');

	return $params;
    }

}

function laGetResume($table, $id) {



    $html = '
	<br/>
		<div>
			<table class="table_resume">
			
			<tr>
			<td class="table_resume_label">
			Groupes autorisés
			</td>
			<td >
				<div class="genform_champ">';
    $sql = 'SELECT * FROM e_groupe AS G, e_groupe_contenu AS R 
			WHERE fk_table = ' . sql($table) . ' AND fk_id = ' . sql($id) . '
			AND R.fk_groupe_id = groupe_id';
    $res = GetAll($sql);
    foreach ($res as $row) {
	$html .= $row['groupe_nom'] . ' - ';
    }

    $html .= '</div>
			</td>
			</tr>
			
			</table>
		</div>
	
	
	';

    $sql = 'SELECT * FROM e_utilisateur AS G, e_utilisateur_contenu AS R 
			WHERE fk_table = ' . sql($table) . ' AND fk_id = ' . sql($id) . '
			AND fk_utilisateur_id = utilisateur_id ';
    $res = GetAll($sql);
    $html .= '
		<div>
			<table class="table_resume">
			
			<tr>
			<td class="table_resume_label">
			Utilisateurs autorisés
			</td>
			<td >
				<div class="genform_champ">';

    foreach ($res as $row) {
	$html .= $row['utilisateur_nom'] . ' ' . $row['utilisateur_prenom'] . ' - ';
    }

    $html .= '</div>
			</td>
			</tr>
			
			</table>
		</div>
	
	
	';

    return $html;
}

function laGetUserResume($table, $id) {

    $html = '
	<br/>
		<div>
			<table class="table_resume">
			
			<tr>
			<td class="table_resume_label">
			Groupes autorisés
			</td>
			<td >
				<div class="genform_champ">';
    $sql = 'SELECT * FROM e_groupe AS G, e_utilisateur_groupe AS R 
			WHERE 1  AND fk_utilisateur_id = ' . sql($id) . '
			AND R.fk_groupe_id = groupe_id';
    $res = GetAll($sql);
    foreach ($res as $row) {
	$html .= $row['groupe_nom'] . ' - ';
    }

    $html .= '</div>
			</td>
			</tr>
			
			</table>
		</div>
	
	
	';
    return $html;
}

function laGetArboGroupes($fk_id = 0, $checked=array(), $level=1) {

    $sql = 'SELECT * FROM e_groupe WHERE fk_groupe_id = ' . sql($fk_id);
    $res = GetAll($sql);

    $html = '';
    foreach ($res as $row) {

	$html .= str_repeat('&nbsp;', $level * 5) . '
					<input ' . (@in_array($row['groupe_id'], $checked) ? 'checked="checked"' : '') . ' type="checkbox" name="laGroupes[]" value="' . $row['groupe_id'] . '" /> <label for="" >' . $row['groupe_nom'] . '</label> <br/>';

	$html .= laGetArboGroupes($row['groupe_id'], $checked, $level + 1);
    }


    return $html;
}

function laSearchUsers($table, $id) {

    $html .= '
	
	<style type="text/css">
	#laUsersList {
		list-style-type:none;
		width:300px;
		float:left;
		border:1px solid #999;
		padding:0;
		margin-right:10px;
	}
	
	
	#laUsersList li {
		background:#ddd;
		padding:3px;
		margin:0;
	}
	#laUsersList li:hover {
		background:#eee;
	}
	
	#laUsersList img {
		vertical-align:middle;
		padding-left:10px;
		float:right;
		
	}
	
	#laUserSelected {
		float:left;
		list-style-type:none;
		width:300px;
		padding:0;
		border:1px solid #999;
	}
	#laUserSelected li {
		background:#ddd;
		padding:3px;
		margin:0;
		
	}
	#laUserSelected li:hover {
		background:#eee;
	}
	
	#laUserSelected img {
		vertical-align:middle;
		

		
	}
	</style>
	<script type="text/javascript">
	
	function XHRSearchUser(champ) {
		XHR("index.php?xhr=laSearchUser&laSearchText="+champ.value+"&table=' . $table . '&id=' . $id . '","",champ,"handler_laSearchUser(http.responseText)");	
	}
	
	function handler_laSearchUser(val) 
	{	
		gid("laUsersList").innerHTML = (val);	
	}
	
	function laAddUser(id,obj) {
		/*
		obj.onclick= function () {
			laDelUser(id,obj);
		}
		*/
		XHR("index.php?xhr=laAddUser&laUserId="+id+"&table=' . $table . '&id=' . $id . '","","","handler_laAddUser(http.responseText)");	
		imag = obj.getElementsByTagName("img");
		//imag[0].src = "./pictos/edit-delete.png";
		//gid("laUserSelected").innerHTML += "<li onclick=\'laDelUser("+id+",this)\'>"+obj.innerHTML+"</li>";
		imag[0].src = "./pictos/media-playback-stop.png";
	}
	
	function handler_laAddUser(val) {
		gid("laUserSelected").innerHTML = val;
	}
	
	function laDelUser(id,obj) {
		XHR("index.php?xhr=laDelUser&laUserId="+id+"&table=' . $table . '&id=' . $id . '","","","handler_laDelUser(http.responseText)");		XHRSearchUser(gid("laSearchText"));
	}
	
	function handler_laDelUser(val) {
		
		gid("laUserSelected").innerHTML = val;	
		
	}
	</script>
	
	<input type="text" name="laSearchText" id="laSearchText" value="" onkeyup="XHRSearchUser(this)" />
	
	<ul id="laUsersList">
		<li>Saisissez un texte ci-dessus pour rechercher un utilisateur</li>
	</ul>
	';

    return $html;
}

function laGetSelectedUsers($table, $id) {

    $sql = 'SELECT * FROM e_utilisateur AS U, e_utilisateur_contenu AS R 
				WHERE R.fk_utilisateur_id = U.utilisateur_id
				AND R.fk_table = ' . sql($table) . ' 
				AND R.fk_id = ' . sql($id) . '
				ORDER BY U.utilisateur_nom, U.utilisateur_prenom';
    $res = GetAll($sql);
    foreach ($res as $row) {

	$html .= '<li onclick="laDelUser(' . $row['utilisateur_id'] . ',this)"><img src="./pictos/edit-delete.png" alt="Supprimer"/> ' . $row['utilisateur_nom'] . ' ' . $row['utilisateur_prenom'] . '</li>';
    }

    if (!count($res)) {
	$html .= '<li>Aucun utilisateur associé</li>';
    }

    return $html;
}

function laSaveForm($id, $row, $gr, $table) {


    if ($table == 'e_utilisateur') {

	DoSql('DELETE FROM e_utilisateur_groupe WHERE fk_utilisateur_id = ' . sql($id) . '');

	if (is_array($_POST['laGroupes'])) {
	    foreach ($_POST['laGroupes'] as $k => $v) {
		DoSql('REPLACE INTO e_utilisateur_groupe 
						SET fk_utilisateur_id = ' . sql($id) . ' ,  fk_groupe_id = ' . sql($v) . '');
	    }
	}
    } else {
	//DoSql('DELETE FROM e_utilisateur_contenu WHERE fk_table = '.sql($table).' AND fk_id = '.sql($id));
	DoSql('DELETE FROM e_groupe_contenu WHERE fk_table = ' . sql($table) . ' AND fk_id = ' . sql($id));




	if (!empty($_POST['laGroupes']) && is_array($_POST['laGroupes'])) {
	    foreach ($_POST['laGroupes'] as $k => $v) {
		DoSql('REPLACE INTO e_groupe_contenu 
						SET fk_table = ' . sql($table) . ' , fk_id = ' . sql($id) . ' ,  fk_groupe_id = ' . sql($v) . '');
	    }
	}


	/* if(is_array($_POST['laUsers'])) {
	  foreach($_POST['laUsers'] as $k=>$v) {
	  DoSql('REPLACE INTO e_utilisateur_contenu
	  SET fk_table = '.sql($table).' ,
	  fk_id = '.sql($id).' ,
	  fk_utilisateur_id = '.sql($v).'');
	  }
	  } */
    }
}

function laGetUserForm($table, $id) {


    $sql = 'SELECT * FROM e_utilisateur_groupe WHERE fk_utilisateur_id = ' . sql($id) . '';
    $res = GetAll($sql);
    $groupes = array();
    foreach ($res as $row) {
	$groupes[] = $row['fk_groupe_id'];
    }



    $html = '
	
	<div>
		<label class="genform_txt">Groupes</label>
		
		<div class="genform_champ">
		' . laGetArboGroupes(0, $groupes) . '
		</div>
	
	
	</div>
	
	<br/>
	';

    return $html;
}

function laGetForm($table, $id) {


    $sql = 'SELECT * FROM e_groupe_contenu WHERE fk_id = ' . sql($id) . ' AND fk_table = ' . sql($table);
    $res = GetAll($sql);
    $html = '';
    $groupes = array();
    foreach ($res as $row) {
	$groupes[] = $row['fk_groupe_id'];
    }

    $html .= '
	
	<div>
		<label class="genform_txt">Groupes</label>
		
		<div class="genform_champ">
		' . laGetArboGroupes(0, $groupes) . '
		</div>
	
	
	</div>
	
	<br/>
	';
    return $html;

    $html .= '
	
	<div>
		<label class="genform_txt">Utilisateurs</label>
		
		<div class="genform_champ" style="display:block;width:700px">

			' . laSearchUsers($table, $id) . '

		<ul id="laUserSelected">
		
			' . laGetSelectedUsers($table, $id) . '
			
		</ul>
		
		<div class="clearer">&nbsp;</div>
		</div>
	
	
	</div>
	
	';


    return $html;
}


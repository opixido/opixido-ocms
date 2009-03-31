<?php

global $_Gconfig;

/* Si on ajoute une rubrique on affiche un formulaire un peu spécial */

if($_REQUEST['curId'] == "new") {


	$sql = 'SELECT '.getLgFields('rubrique_url',' , ').' FROM s_rubrique WHERE fk_rubrique_id = "'.mes($_REQUEST['genform__add_sub_id']).'"';
	$resNot = GetAll($sql);


	?>

	<script type="text/javascript">
	<?php 
	reset($_Gconfig['LANGUAGES']);
		foreach($_Gconfig['LANGUAGES'] as $lg) {
			p('
			notUrl'.ucfirst($lg).' = new Array();			
			');
			
		}


	foreach($resNot as $row) {
		reset($_Gconfig['LANGUAGES']);
		foreach($_Gconfig['LANGUAGES'] as $lg) {
			p('notUrl'.ucfirst($lg).'["'.$row['rubrique_url_'.$lg].'"] = true;');
		}
		//p('notUrlEn["'.$row['rubrique_url_en'].'"] = true;');


	}
	?>

	function updateChampUrl(champ,valeur) {


		champ = gid(champ);
		valeur = valeur.toLowerCase();
		var re = /\$|,|@|#|~|`|\%|\*|\^|\&|\(|\)|\+|\=|\[|\-|\_|\]|\[|\}|\{|\;|\:|\'|\"|\<|\>|\?|\||\\|\!|\$|\.\£\°\§\//g;

		valeur = valeur.replace(re,"-");

		re = /é|è|ê|ë|€/g;
		valeur = valeur.replace(re,"e");

		re = /à|â|ä/g;
		valeur = valeur.replace(re,"a");

		re = /ò|ô|ö/g;
		valeur = valeur.replace(re,"o");

		re = /û|ü|ù|µ/g;
		valeur = valeur.replace(re,"u");

		re = /ç/g;
		valeur = valeur.replace(re,"c");

		valeur = valeur.replace(/ /g,"-");

		valeur = valeur.replace('.',"-");
		valeur = valeur.replace("/","-")


		var i=0;


		valeur = valeur.replace(/[^A-Za-z0-9]/g,"-");

		while( valeur.search("--") >= 0 && i<20) {
			valeur = valeur.replace(/__/g,"-");
			i++;
		}

		if(valeur.charAt(valeur.length-1) == "-") {
			valeur = valeur.substring(0,valeur.length-1);
		}

		
		<?php 
		reset($_Gconfig['LANGUAGES']);
		foreach($_Gconfig['LANGUAGES'] as $lg) {
			p('
				if(champ.name == "genform_rubrique_url_'.$lg.'") {
					checkTab = notUrl'.ucfirst($lg).';
				}
			');
			
		}		
		
		?>
		/*
		if(champ.name == "genform_rubrique_url_fr") {
			checkTab = notUrlFr;

		} else if (champ.name == "genform_rubrique_url_en") {
			checkTab = notUrlEn;
		}
		*/
		incRe = 1;
		newvaleur = valeur;
		while(checkTab[newvaleur]) {
			newvaleur = valeur+"-"+incRe;
			incRe++;
		}
		valeur = newvaleur;


		champ.value = valeur;
		checkFields();
	}


	function checkFields() {
		mfields = new Array(<?php
		
		reset($_Gconfig['LANGUAGES']);
		$nblg = count($_Gconfig['LANGUAGES']);
		$nb=0;
		foreach($_Gconfig['LANGUAGES'] as $lg) {
			$nb++;
			print('"mygen_rubrique_url_'.$lg.'", "genform_rubrique_titre_'.$lg.'"');			
			if($nb < $nblg)
				print(',');
		}
		
		//'mygen_rubrique_url_fr','mygen_rubrique_url_en','genform_rubrique_titre_fr','genform_rubrique_titre_en');
		?>);
		
		ml = mfields.length;

		isok = 0;
		for(p=0;p<ml;p++) {
			fi = mfields[p];
			//alert(gid(fi));
			if(gid(fi).value.length > 1) {
				isok++;
			}
		}
		if(isok == ml) {
				gid("genform_header_btn").innerHTML = '<label class="abutton" for="addeditrub"><input id="addeditrub" type="image" src="<?=(t('src_saveas'));?>" value="<?=(t('create_and_edit_rub'));?>" name="genform_stay" /><?=(t('create_and_edit_rub'));?></label>';
		} else {
			gid("genform_header_btn").innerHTML = '';
		}

	}

	gid("genform_btn_page_1").style.display = 'none';

	gid("genform_btn_page_2").style.display = 'none';
	gid("genform_btn_page_3").style.display = 'none';
	gid("genform_btn_page_4").style.display = 'none';
	//gid("genform_btn_page_5").style.display = 'none';
	//gid("genform_btn_page_6").style.display = 'none';



	gid('genform_header_btn') .innerHTML = '';

	</script>

	<?php

	p('<h1>'.t('info_crea_rub').'</h1>');

	if($_REQUEST['genform__add_sub_id'] == getParam('rub_publication_id')){
		p( '<input type="hidden" value="yes" name="is_publi_rub" id="is_publi_rub" />' );
	}
	
	reset($_Gconfig['LANGUAGES']);
	foreach($_Gconfig['LANGUAGES'] as $lg) {
		$form->genHiddenField('rubrique_url_'.$lg);
		$form->gen('rubrique_titre_'.$lg,'','', 'onkeyup="updateChampUrl(\'mygen_rubrique_url_'.$lg.'\',this.value)"' );
		?>
		<label for="mygen_rubrique_url_<?=$lg?>"><?=t('url_'.$lg.'_will_be')?></label>
		<input type="text" value="" name="genform_rubrique_url_<?=$lg?>" id="mygen_rubrique_url_<?=$lg?>" onchange="checkFields()" />
		<hr/>
	<?php
	}
	

/*

$sql = 'SELECT * FROM s_gabarit WHERE gabarit_classe = "" AND gabarit_titre != "" ORDER BY gabarit_titre';
$res = GetAll($sql);
	$form->gen('fk_gabarit_id','','','',$res);
	
*/

$form->gen('rubrique_type');
}
 else {

	$form->genlg('rubrique_titre');
	//$form->genlg('rubrique_sous_titre');
	if($form->tab_default_field['rubrique_type'] == 'page' || $form->tab_default_field['rubrique_type'] == 'siteroot') {
	$form->gen('fk_paragraphe_id');	
	}
	//$form->gen('rubrique_titre_en');

 }





?>



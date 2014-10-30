<?php


$form->gen('fk_para_type_id','','','onchange="updateParagraphes(this);" onblur="updateParagraphes(this);"');

?>

<script type="text/javascript">

paraVals = new Array();
<?php

$sql ='SELECT * FROM s_para_type';
$res = GetAll($sql);
foreach($res as $row) {
	p('paraVals['.$row['para_type_id'].'] = new Array('.$row['para_type_use_img'].','.$row['para_type_use_file'].','.$row['para_type_use_table'].','.$row['para_type_use_txt'].','.$row['para_type_use_link'].');');
}
?>


selectedParaType = gid('genform_fk_para_type_id');
function updateParagraphes() {
	var para_id = selectedParaType.options[selectedParaType.selectedIndex].value;
	var para_value = new Array('none','block');
	//alert('Para type : '+para_id);
	if(para_id) {

		var vals = paraVals[para_id];

		<?php 
		global $_Gconfig;
		reset($_Gconfig['LANGUAGES']);
		//foreach($_Gconfig['LANGUAGES'] as $lg) {
			
			p('
			gid("genform_div_paragraphe_img_1").style.display = para_value[vals[0]];
		    gid("genform_div_paragraphe_img_1_alt").style.display = para_value[vals[0]];
			//gid("genform_div_paragraphe_img_2").style.display = para_value[vals[0]];
		    //gid("genform_div_paragraphe_img_2_alt").style.display = para_value[vals[0]];	
		    
		    //gid("genform_div_paragraphe_img_2_legend").style.display = para_value[vals[0]];	
		    gid("genform_div_paragraphe_img_1_legend").style.display = para_value[vals[0]];		    
		    
		    // gid("genform_div_paragraphe_img_2_copyright").style.display = para_value[vals[0]];	
		    //gid("genform_div_paragraphe_img_1_copyright").style.display = para_value[vals[0]];	
		    
		    gid("genform_div_paragraphe_file_1_legend").style.display = para_value[vals[1]];
		    gid("genform_div_paragraphe_file_1").style.display = para_value[vals[1]];
		    gid("genform_div_paragraphe_contenu").style.display = para_value[vals[3]];		    
		    gid("genform_div_paragraphe_link_1").style.display = para_value[vals[4]];	
			
		    	');
			//gid("genform_paragraphe_contenu_upload_table").style.display = para_value[vals[2]];
	//	}
		
	/*
			gid('genform_div_paragraphe_img_1_fr').style.display = para_value[vals[0]];
			gid('genform_div_paragraphe_img_1_alt_fr').style.display = para_value[vals[0]];
			gid('genform_div_paragraphe_img_1_en').style.display = para_value[vals[0]];
			gid('genform_div_paragraphe_img_1_alt_en').style.display = para_value[vals[0]];
	
			gid('genform_div_paragraphe_params_fr').style.display = para_value[vals[1]];
			gid('genform_div_paragraphe_params_en').style.display = para_value[vals[1]];
			gid('genform_div_paragraphe_file_1_fr').style.display = para_value[vals[1]];
			gid('genform_div_paragraphe_file_1_en').style.display = para_value[vals[1]];
	
			gid('genform_div_paragraphe_contenu_fr').style.display = para_value[vals[3]];
			gid('genform_div_paragraphe_contenu_en').style.display = para_value[vals[3]];
	
			gid('genform_paragraphe_contenu_fr_upload_table').style.display = para_value[vals[2]];
			gid('genform_paragraphe_contenu_en_upload_table').style.display = para_value[vals[2]];
		*/

		/*	
			gid('genform_div_paragraphe_img_1_fr').style.display = para_value[vals[0]];
			gid('genform_div_paragraphe_img_1_fr').style.display = para_value[vals[0]];
		*/
		
		
		?>
		

	}
}

//window.attachEvent("onload", updateParagraphes);

</script>
<?php

$form->genlg('paragraphe_titre');

$form->genlg('paragraphe_contenu');


$form->genlg('paragraphe_img_1');
$form->genlg('paragraphe_img_1_alt');
$form->genlg('paragraphe_img_1_legend');
//$form->gen('paragraphe_img_1_copyright');
/*
$form->genlg('paragraphe_img_2');
$form->genlg('paragraphe_img_2_alt');
//$form->genlg('paragraphe_img_2_legend');
$form->gen('paragraphe_img_2_copyright');
*/
$form->genlg('paragraphe_file_1');
$form->genlg('paragraphe_file_1_legend');

$form->genlg('paragraphe_link_1');

?>

<script type="text/javascript">
updateParagraphes();
</script>
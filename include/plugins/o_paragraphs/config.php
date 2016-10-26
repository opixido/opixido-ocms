<?php


global $tabForms, $_Gconfig, $orderFields,$uploadFields,$relinv;

/**
 * Paragraphe embed externe responsive
 * */
$tabForms['s_paragraphe']['pages']['paragraphe']['embed'] = '../plugins/o_paragraphs/forms/form.paragraphe-embed.php';
$tabForms['s_paragraphe']['pages']['paragraphe']['images'] = '../plugins/o_paragraphs/forms/form.paragraphe-images.php';
$tabForms['s_paragraphe_image']['titre'] = array('paragraphe_image_img','paragraphe_image_desc');

$relinv['s_paragraphe']['IMAGES'] = array('s_paragraphe_image','fk_paragraphe_id');
$_Gconfig['ajaxRelinv']['s_paragraphe']['IMAGES'] = array('s_paragraphe_image','fk_paragraphe_id',array('paragraphe_image_img','paragraphe_image_titre','paragraphe_image_desc','paragraphe_image_link'));
$orderFields['s_paragraphe_image'] = array('paragraphe_image_ordre','fk_paragraphe_id');
$uploadFields[] = 'paragraphe_image_img';


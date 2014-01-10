<?php 

global $tabForms,$_Gconfig,$uploadFields,$rteFields,$neededFields,$relations,$urlFields,$emailFields,$admin_trads,$relinv,$tablerel;

$tabForms["p_news"]["titre"] = array (
  0 => 'news_titre_fr',
  1 => 'news_img_fr',
);

$tabForms["p_news"]["pages"] = array (
  'info' => '../plugins/ocms_news/forms/form.info.php',
);

$tabForms["p_news"]["picto"] = ADMIN_PICTOS_FOLDER."32x32/apps/office-calendar.png";


$uploadFields[] = "news_img";
$uploadFields[] = "news_media";

$_Gconfig["bigMenus"]["ocms_news"][] = "p_news";

$admin_trads["cp_txt_p_news"]["en"] = "News";
$admin_trads["p_news"]["en"] = "News";
$admin_trads["p_news.news_titre_fr"]["en"] = "News titre fr";
$admin_trads["p_news.news_desc_fr"]["en"] = "News desc fr";
$admin_trads["p_news.news_detail_fr"]["en"] = "News detail fr";
$admin_trads["p_news.news_img_fr"]["en"] = "News img fr";
$admin_trads["p_news.news_media_fr"]["en"] = "News media fr";
$admin_trads["p_news.news_date"]["en"] = "News date";
$admin_trads["p_news.date_online"]["en"] = "Date online";
$admin_trads["p_news.date_offline"]["en"] = "Date offline";



?>
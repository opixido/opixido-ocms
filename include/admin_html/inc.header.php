<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>


  <title>Administration :: <?php echo ta('base_title') ?> :: </title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <link rel="stylesheet" type="text/css" href="css/style.css" />
  <link rel="stylesheet" type="text/css" href="css/style_suite.css" />
  <link rel="stylesheet" type="text/css" href="css/arbo.css" />

<link rel="StyleSheet" type="text/css" href="genform/css/genform.css" />
<script type="text/javascript" src="genform/js/tjmlib.js"></script>
<script type="text/javascript" src="js/script.js"></script>
<script type="text/javascript" src="js/xhr.js"></script>
<script type="text/javascript" src="js/tooltip.js"></script>
<script type="text/javascript" src="js/ajaxForm.js"></script>

<!--JQUERY-->
		<link type="text/css" href="jq/css/cupertino/jquery-ui-1.7.1.custom.css" rel="stylesheet" />	
		<script type="text/javascript" src="jq/js/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="jq/js/jquery-ui-1.7.1.custom.min.js"></script>
		<script type="text/javascript" src="jq/js/jquery.textarearesizer.compressed.js"></script>
		<script type="text/javascript" src="jq/js/jquery.tablednd_0_5.js"></script>
<!--/JQUERY-->

<style type="text/css"/>
<?php
//global $_Gconfig;
// foreach($_Gconfig['LANGUAGES'] as $lg) {
//
// 	echo '.lg_'.$lg.' textarea {background-image:url(img/flags/'.$lg.'.gif);background-repeat:no-repeat;background-position:top right	}
// 	';
// 
// }
?>
</style>
<script type="text/javascript">
if (/Mozilla\/5\.0/.test(navigator.userAgent))
   document.write('<script type="text/javascript" src="mozInnerHTML.js"></sc' + 'ript>');

function winopen(url,w,h) {
        window.open(url,'popup'+Math.random(100),'width='+w+",height="+h+",scrollbars=no,status=no,location=no");
        return false;
}
// on construit le tableau des langues
        <? 
        
        global $_Gconfig;
        
        foreach($_Gconfig['LANGUAGES'] as $lg)
            $lgs[] = '"'.$lg.'"';
            
        $tab = 'var lgs = ['.implode(', ',$lgs).'];';
        
        echo $tab;

        ?>

</script>
	

    <?php
    
    
    
    if(strstr($_SERVER["HTTP_USER_AGENT"],'MSIE')) {
    	p('<link rel="StyleSheet" href="css/ie.css" />');
    }
    ?>
    
<!--[if lt IE 3.]>
<script defer type="text/javascript" src="./js/pngfix.js"></script>
<![endif]-->
    
</head>

<body onbeforeunload="showLoader()">


<div id="xhrloader"></div>

<div id="tooltip"></div>

<div id="info_picto">&nbsp;</div>

<div id="contenant">
	
	<?php
	
	if(!$_GET['simple'] ) {
		
	?>
	    <div id="bandeau">
	
	        <div id="logo">
	
				
		<?php
	
	if($GLOBALS['gs_obj']->isLogged() ) {
	?>
			<a href="index.php?logout=1" class="abutton" style="height:19px;float:right;padding-top:5px;"><img src="<?=t('src_logout')?>" alt="Logout" class="inputimage" /> <?=t('logout')?></a>
	
		<?php
	
	}
	?>
	
	            <a class="logoa" href="index.php?home=1"><h1><?php echo ta('base_title') ?></h1></a>
	
	        </div>
	
	    </div>
	<?php
	}
	?>

	<div id="bas">

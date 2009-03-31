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
<script type="text/javascript">
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
        
        /*
window.onunload  = function(){
	if(window.event) {
	if((window.event.clientX<0)||(window.event.clientY<0))
		alert('Closed.');//closeSession()
	} else if(self.screenTop>9000) {
		alert('Closed.');
	}
}

function closeSession() {
	winopen('index.php?logout=1',100,100);
}*/
        
        
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

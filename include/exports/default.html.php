<?php echo '<?xml version="1.0" encoding="utf-8" ?>';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LG?>" lang="<?=LG?>" >

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="generator" content="Opixido cms" />
    <meta http-equiv="Content-Script-Type" content="text/javascript"/>
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta name="robots" content="index,follow" />

    <link rel="icon" href="<?=BU?>/favicon.ico" type="image/gif" />
	<link rel="shortcut icon" type="image/gif" href="<?=BU?>/favicon.ico" />

	<link rel="Stylesheet" type="text/css" href="<?=BU?>/css/base.css" media="screen" />
	<link rel="Stylesheet" type="text/css" href="<?=BU?>/css/print.css" media="print" />
	<link rel="Stylesheet" type="text/css" href="<?=BU?>/css/position.css" media="screen" />


	<script type="text/javascript"  src="<?=BU?>/js/base.js" ></script>


    <?php echo $this->g_headers->gen() ?>

</head>

<body>



<div id="largeur">

	<h1 class="cacher"><?=t('base_title')?></h1>



	<div id="logo">

		<a href="<?=getUrlFromId($this->g_url->rootHomeId)?>"><img src="<?=BU?>/img/logo.jpg" alt=<?=alt(t('alt_logo'))?> /></a>
		
	</div>


	<div id="menus">
	<?php


		/**
		 * MENUS DE NAVIGATION
		 * Pour les generer separement :
		 * $this->menus['NOM_MENU']->getTab();
		 */
		$jsMenus = 'ocmsMenus = new Array(';

		foreach($this->menus as $k=>$v) {
			echo $v->getTab();
			$jsMenus .= ' "'.$k.'",';
		}

		$jsMenus  = substr($jsMenus,0,-1).');'."\n";
		
	?>
	</div>



	<?php 
		echo $this->g_rubrique->genOutside(); 
	?>


	<?php
		echo $this->g_rubrique->Execute('gen1');
	?>

	<div id="main">
	<?php 
		echo $this->g_rubrique->genMain() 
	?>
	</div>
	
	<?php
		echo $this->g_rubrique->Execute('gen2');
	?>

	<?php
		echo $this->g_rubrique->Execute('genLast');
	?>
	
</div>

</body>
</html>
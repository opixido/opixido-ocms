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

    <link rel="icon" href="/favicon.ico" type="image/gif" />
	<link rel="shortcut icon" type="image/gif" href="/favicon.ico" />

	<link rel="Stylesheet" type="text/css" href="<?=BU?>/css/print.css" media="print" />
	
    <?php /*echo $this->g_headers->gen()*/ ?>

</head>

<body>

<div id="largeur">

	<div id="include">
		
		
		<div id="top_section">
		
			<div id="logo">
	
				<a href="<?=getUrlFromId($this->g_url->rootHomeId)?>"><img src="<?=BU?>/img/logo.gif" alt=<?=alt(t('alt_logo'))?> /></a>
			
			</div>
			
		</div>
		
		<div id="middle_section">		
		
			<div id="border_color">&nbsp;</div>	

			
			<div id="main">
			
			
				<?php

					echo $this->g_rubrique->genMain() 
				?>

			
			</div>
			<div class="clearer">&nbsp;</div>
			

			
		</div>
	</div>
	
</div>
    


</body>
</html>
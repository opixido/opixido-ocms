<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
  <title>Administration :: <?php echo ta('base_title') ?> :: </title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  
  <?php 

  $css = array('/css/style.css',
  				'/css/style_suite.css',
  				'/css/arbo.css',
  				'/genform/css/genform.css',
  				'/jq/css/cupertino/jquery-ui-1.7.1.custom.css',
  				'/jq/css/tipsy.css',
  				'/jq/css/fg.menu.css'
  );
  $js = array(
  	'/genform/js/tjmlib.js',
  	'/js/script.js',
  	'/js/xhr.js',  	
  	'/js/ajaxForm.js',
  	'/jq/js/jquery.js',
  	'/jq/js/jquery-ui.js',
  	'/jq/js/jquery.textarearesizer.compressed.js',
  	'/jq/js/jquery.tablednd_0_5.js',
  	'/jq/js/jquery.autocomplete-min.js',
  	'/jq/js/jquery.tipsy.js'
  );

  //'/js/tooltip.js',

  $g = new genHeaders(false);
  $g->fCacheFolder = 'admin/c';
  $g->addFolder = '../';

  $css = $g->getCssPath($css);
  $js = $g->getJsPath($js);

  echo '<link rel="stylesheet" type="text/css" href="'.$css.'" />';  
  echo '<script type="text/javascript" src="'.$js.'"></script>	';  
  
  ?>
  <!-- 
  <link rel="stylesheet" type="text/css" href="css/style.css" />
  <link rel="stylesheet" type="text/css" href="css/style_suite.css" />
  <link rel="stylesheet" type="text/css" href="css/arbo.css" />
  <link rel="StyleSheet" type="text/css" href="genform/css/genform.css" />  
  <link type="text/css" href="jq/css/cupertino/jquery-ui-1.7.1.custom.css" rel="stylesheet" />	
	<script type="text/javascript" src="genform/js/tjmlib.js"></script>
	<script type="text/javascript" src="js/script.js"></script>
	<script type="text/javascript" src="js/xhr.js"></script>
	<script type="text/javascript" src="js/tooltip.js"></script>
	<script type="text/javascript" src="js/ajaxForm.js"></script>  
		<script type="text/javascript" src="jq/js/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="jq/js/jquery-ui-1.7.1.custom.min.js"></script>
		<script type="text/javascript" src="jq/js/jquery.textarearesizer.compressed.js"></script>
		<script type="text/javascript" src="jq/js/jquery.tablednd_0_5.js"></script>	
 -->



<!--JQUERY-->


<!--/JQUERY-->

<script type="text/javascript">
<? 
        
        global $_Gconfig;        
        foreach($_Gconfig['LANGUAGES'] as $lg) {
            $lgs[] = '"'.$lg.'"';
        }
        
        $tab = 'var lgs = ['.implode(', ',$lgs).'];';        
        echo $tab;
        ?>
</script>

<?php   
    if(strstr($_SERVER["HTTP_USER_AGENT"],'MSIE')) {
    	p('<link rel="StyleSheet" href="css/ie.css" />');
    }
?>   
</head>

<body onbeforeunload="showLoader()">

<div id="xhrloader"></div>

<div id="tooltip"></div>

<div id="info_picto">&nbsp;</div>

<div id="contenant">
	
<?php if(!$_GET['simple'] ) { ?>
	    <div id="bandeau">
	
	        <div id="logo">
	        				
			<? if($GLOBALS['gs_obj']->isLogged() ) { ?>
			<a href="index.php?logout=1" class="bloc2" id="logout" ><img src="<?=t('src_logout')?>" alt="" class="inputimage" /> <?=t('logout')?></a>
			<? } ?>
			
		<? if($GLOBALS['gs_obj']->isLogged() && $_REQUEST['curTable'] ) { ?>
	    <div id="rmenu" class="menu4 bloc2">
	    	<ul >
		    <?php 
		    	$tables = getTables();
		    	$nb = 1;
		    	foreach ($_Gconfig['bigMenus'] as $k=>$menus ) {
		    		
		    		$t = '<li ><a href="#" id="menu_'.$k.'" ><img src="'.getPicto($menus[0],'16x16').'" alt=""/> '.ta($k).'</a><ul class="bloc2 menu_'.$nb.'" id="content_'.$k.'" class="" >';
		    		$h = '';
					foreach($menus as $menu) {
						if($GLOBALS[gs_obj]->can('edit', $menu)){		
							$url = in_array($menu,$tables) ? 'index.php?curTable='.$menu : ta('cp_link_'.$menu);							
							$h .= '<li><a href="'.$url.'" ><img src="'.getPicto($menu,'16x16').'" alt=""/> <span>'.ta('cp_txt_'.$menu).'</span></a></li>';
						}
					}
					if($h) {
						$nb++;
						echo $t.$h.'</ul>';
					}		    		
		    	}		    
		    ?>
	   	 </ul>
	    </div>
	    <style type="text/css">
		<?php
		$nb--;
		echo '.menu_'.$nb.' , .menu_'.($nb-1).' {left:auto!important;right:-5px!important;} ';
		?>
		</style>
	    <div class="clearer"></div>
	    <?php  } ?>		
	        <a class="logoa" href="index.php?home=1"><h1><?php echo ta('base_title') ?></h1></a>
	        </div>	
	    </div>
	    
	    
	   
<?php } ?>

<div id="bas">

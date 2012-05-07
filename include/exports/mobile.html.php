<?php
    global $_Gconfig;
    //add home menu
    $this->menus['menu_top']->addMenu('tabPrincipal', t('cp_txt_home'), "/".BU);
    $currentMenu = $this->menus['menu_top']->tabPrincipal;
    
    $newMenu = array();
    
    //place home menu first
    while(count($currentMenu) > 0){
        end($currentMenu);
        $key = key($currentMenu);
        $value = $currentMenu[$key];
        $newMenu[$key] = $value;
        array_pop($currentMenu);
    }
    
    $this->menus['menu_top']->tabPrincipal = $newMenu;
    
?>
<!DOCTYPE html> 
<html>  
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="generator" content="Opixido cms" />
    <meta http-equiv="Content-Script-Type" content="text/javascript"/>
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta name="robots" content="index,follow" />
    <meta charset="utf-8" />
    <link rel="icon" href="<?= BU ?>/favicon.ico" type="image/gif" />
    <link rel="shortcut icon" type="image/gif" href="<?= BU ?>/favicon.ico" />
    <link rel="Stylesheet" type="text/css" href="<?= BU ?>/css/print.css" media="print" />
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <script src="<?php echo BU; ?>/js/jquery.js" ></script>
    
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
    
    <?php echo $this->g_headers->gen(); ?>

    <script type="text/javascript">
        window.bu = "<?= BU ?>";
        window.Trads = new Array();
    </script>
  </head>
  <body>
      
    <nav>
        <?=$this->menus['menu_top']->getTab()?>     
    </nav> 
      
    <header id="banner" role="banner">
        <h1><a href="/<?=BU?>"><img src="<?=BU?>/img/mobile/canalu_mobile_logo.png" alt="<?=t('base_title')?>" /></a></h1>
    </header>
    
    <section id="searchForm">
        <?=$this->plugins['mobile']->genSearchForm();?>
    </section>
    
    <section role="main" id="main">
        <?= $this->g_rubrique->genMain() ?>
    </section>
    
    <section id="back_site">
        <a href="<?=$_Gconfig['httpDwl']?>"><?=t('Retour_site_principal')?></a>
    </section>
    
  </body>
</html> 
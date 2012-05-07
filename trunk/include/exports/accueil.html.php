<?php
/**
 * A ajouter uniquement pour avoir le probleme des border-box avec IE
 * echo '<?xml version="1.0" encoding="utf-8" ?>';
 */
?>
<!DOCTYPE html>
<html lang="<?= LG ?>" >

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
        <script type="text/javascript" src="<?php echo BU; ?>/js/jquery.js" ></script>

        <?php
        
        if ($this->getCurId()==9) {
            echo '<script type="text/javascript" src="' . BU . '/js/perso.js" ></script>';
        }
        ?>

        <!--[if lt IE 4]>
              <script type="text/javascript" src="<?php echo BU; ?>/js/roundies.js" ></script>    
              <script type="text/javascript">
                 $(document).ready(function() {   
                       DD_roundies.addRule('.bloc-top-right-rounded','0 20px 0 0', true);
                       DD_roundies.addRule('#menu_menu li','0 20px 0px 0px', true);
                       DD_roundies.addRule('.bloc-bottom-left-rounded','0 0 0 20px', true); 
                       DD_roundies.addRule('#homeRight .bloc','0 20px 0 20px', true);
                       DD_roundies.addRule('#bloc-news .tabs a','10px 0 0 10px', true);
                       DD_roundies.addRule('.bloc-top-right-rounded','0 10px 0 0', true);
                       DD_roundies.addRule('#tabs li','10px 10px 0 0', true);
                       DD_roundies.addRule ('.corner-button-all','5px', true);
                   });
               </script> 
        <![endif]-->
        <!--[if lt IE 9]>
           <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
       <![endif]-->

        <!--[if lt IE 10]>
            <script type="text/javascript" src="<?php echo BU; ?>/js/PIE.js"></script>
            <script type="text/javascript">
                 $(document).ready(function() {
                    if (window.PIE) {

                        $('.bloc-top-right-left-rounded').each(function() {
                            PIE.attach(this);
                        });

                        $('#menu_menu li').each(function() {
                            PIE.attach(this);
                        });
/*
                        $('#tabs li').each(function() {
                            PIE.attach(this);
                        });
*/
                        $('.bottom-left-rounded').each(function() {
                            PIE.attach(this);
                        });

                        $('.bloc-bottom-left-rounded').each(function() {
                            PIE.attach(this);
                        });

                        $('.bottom-top-left-rounded').each(function() {
                            PIE.attach(this);
                        });

                        $('.bloc-top-right-rounded').each(function() {
                            PIE.attach(this);
                        });

                        $('.bloc-bottom-right-rounded').each(function() {
                            PIE.attach(this);
                        });


                        $('.top-left-rounded').each(function() {
                            PIE.attach(this);
                        });

                        $('.top-right-rounded').each(function() {
                            PIE.attach(this);
                        });

                        $('.bottom-right-rounded').each(function() {
                            PIE.attach(this);
                        });

                        $('.rounded-button').each(function() {
                            PIE.attach(this);
                        });
        
                        $('.div_submit').each(function() {
                            PIE.attach(this);
                        });

                    }
                });
            </script>
        <![endif]-->


        <?php echo $this->g_headers->gen(); ?>

        <script type="text/javascript">
            window.bu = "<?= BU ?>";
            window.Trads = new Array();
        </script>
    </head>

    <body >

        <div id="container" > 

            <?php echo $this->g_rubrique->Execute('getHeaderBackground'); ?>

            <div id="largeur">

                <h1 class="cacher"><?= t('base_title') ?></h1>


                <div id="header">


                    <div class="fright">

                        <?php
                            echo $this->menus['menutop']->getTab();
                        ?>

                    </div>

                    <div class="fleft">
                        <a href="<?= getUrlFromId($this->g_url->rootHomeId) ?>" id="logo-link" class="alink">
                            <img src="<?= BU ?>/img/logo.jpg" alt=<?= alt(t('alt_logo')) ?> />
                        </a>
                    </div>

                    <?php
                    $pForm = new Myform('genSolr', 'searchbar-form', 'get');
                    $pForm->add('html', '<div class="header-input">');
                    $pForm->addText('q', akev($_REQUEST, 'q'), 'textProgramSearch', ' class="input-corner-all" placeholder=' . alt(t('RechercherUnProgramme')));
                    $pForm->addSubmit("submitProgramSearch", 'Ok', 'button', ' class="corner-button-all" ');
                    $pForm->add('html', '<a href="'.getUrlFromId(61).'" class="alink" id="mon-canalu-link">
                                            <img alt="Mon canalu" src="' . BU . '/img/mon-canalu.jpg" />
                                         </a>');
                    $pForm->add('html', '</div>');
                    echo $pForm->gen();
                    ?>

                    <a target="_blank" href="http://www.enseignementsup-recherche.gouv.fr/" title="Ministère de l'Enseignement Supérieur et de la Recherche (nouvelle fenêtre)" class="alink" id="logo-ministere-link" >
                        <img alt="Ministère de l'Enseignement Supérieur et de la Recherche" src="<?= BU ?>/img/ministere.jpg" />
                    </a>
                    
                </div>

                <?php
                echo $this->menus['menu']->getTab();
                ?>

                <?php
                if ($this->g_url->rootHomeId != $this->getCurId()):
                    ?>
                    <div id="road">
                        <?php
                        echo $this->plugins['ocms_road']->genRoad();
                        ?>
                    </div>

                <?php else: ?>
                    <div id ="home-flux">
                        <?php
                        echo $this->g_rubrique->Execute('genTopBlock');
                        ?>
                    </div>
                <?php
                endif;
                ?>
                <div id="main">  

                    <?php
                    if ($this->plugins['colonneGauche']->visible === TRUE):
                        echo $this->plugins['colonneGauche']->genColumn();
                    endif;
                    ?>  
                    <?php
                    echo $this->g_rubrique->genMain()
                    ?>

                    <?php
                    echo $this->g_rubrique->Execute('genRightColumn');
                    ?>

                    <div class="clearer" >&nbsp;</div>

                    <?php
                    echo $this->g_rubrique->Execute('genHomeBottom');
                    ?>

                    <?php
                    echo $this->g_rubrique->Execute('genProgrammeBottom');
                    ?>

                    <?php
                    if ($this->plugins['bottom']->visible === TRUE):
                        echo $this->plugins['bottom']->genBottom();
                    endif;
                    ?>

                </div>

                <?php
                echo $this->menus['menubottom']->getTab();
                ?>

                <a id="logo_cerimes" href="http://www.cerimes.fr" target="_blank"><img src="<?=BU?>/img/logo-cerimes.png" alt="Cerimes"/></a>
                <?php
                echo $this->g_rubrique->Execute('genLast');
                ?>

                <?php
                if ($this->plugins['programme']->visible === TRUE):
                    echo $this->plugins['programme']->genScrollbar();
                endif;
                ?>  

            </div>
            <div class="clearer" >&nbsp;</div>
        </div>
        <div id="favoris" class="rnd">&nbsp;</div>
        <img class="page-bg" src="<?= BU . '/img/fantaisie-bg.jpg' ?>" />
        <script type="text/javascript">
            <!--
            xtnv = document;                //parent.document or top.document or document
            xtsd = "http://logc20";
            xtsite = "248546";
            xtn2 = "<?php echo $GLOBALS['xiticode'] ?>";        		// level 2 site
            xtpage = "<?php echo $GLOBALS['xitipage'] ?>";        //page name (with the use of :: to create chapters)
            xtdi = "";                      //implication degree
            //-->
        </script>
        <script type="text/javascript" src="<?= BU ?>/js/xiti.js"></script>

        <noscript>
        <img width="1" height="1" alt="" src="http://logc20.xiti.com/hit.xiti?s=248546&amp;s2=21&amp;p=&amp;di=&amp;" />
        </noscript>
    </body>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>Administration :: <?php echo ta('base_title') ?> :: </title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
       
        <?php
        $css = array(
            //'admin/css/bootstrap.css',
            'admin/css/style.css',
            'admin/css/style_suite.css',
            'admin/css/arbo.css',
            'admin/genform/css/genform.css',
            'admin/jq/css/ui-lightness/jquery-ui-1.8.16.custom.css',
            'admin/jq/css/tipsy.css',
            'admin/jq/css/fg.menu.css',
            'admin/jq/css/jquery.tagedit.css'

        );
        $js = array(
            'admin/jq/js/jquery.js',
            'admin/genform/js/tjmlib.js',
            'admin/js/script.js',
            'admin/js/xhr.js',
            'admin/js/ajaxForm.js',
            'admin/jq/js/jquery-ui.js',
            'admin/jq/js/jquery.textarearesizer.compressed.js',
            'admin/jq/js/jquery.tablednd_0_5.js',              
            'admin/jq/js/jquery.tipsy.js',
            'admin/jq/js/jquery.autoGrowInput.js',
            'admin/jq/js/jquery.tagedit.js',
            'admin/jq/js/jquery.tree.js',
            //'admin/js/bootstrap.min.js',
            'admin/plupload/js/plupload.js',
            'admin/plupload/js/plupload.full.js',
            'admin/plupload/js/jquery.ui.plupload/jquery.ui.plupload.js',
            'admin/plupload/js/plupload.gears.js',
            'admin/plupload/js/plupload.silverlight.js',
            'admin/plupload/js/plupload.flash.js',
            'admin/plupload/js/plupload.html5.js',
            'admin/plupload/js/plupload.html4.js'
            
        );
        /* 'admin/jq/js/jquery.autocomplete-min.js',         */

        //'/js/tooltip.js',

        $g = new genHeaders(false);
        $g->fCacheFolder = 'admin/c';
        $g->addFolder = 'admin';

        $css = $g->getCssPath($css);
        $js = $g->getJsPath($js);

        echo '<link rel="stylesheet" type="text/css" href="' . $css . '" />';
        echo '<script type="text/javascript" src="' . $js . '"></script>	';
        ?>

        <script type="text/javascript">
<?
global $_Gconfig;
echo 'var lgs = ' . json_encode($_Gconfig['LANGUAGES']) . ';';
?>
        </script>

        <?php
        if (strstr($_SERVER["HTTP_USER_AGENT"], 'MSIE')) {
            p('<link rel="StyleSheet" href="css/ie.css" />');
        }
        ?>   
    </head>

    <body onbeforeunload="showLoader()">

        <div id="xhrloader"></div>

        <div id="tooltip"></div>

        <div id="info_picto">&nbsp;</div>

        <div id="contenant">

            <?php if (!isset($_GET['simple'])) { ?>
                <div id="bandeau">

                    <div id="logo">

                        <? if ($GLOBALS['gs_obj']->isLogged()) { ?>
                            <a href="index.php?logout=1" class="bloc2" id="logout" ><img src="<?= t('src_logout') ?>" alt="" class="inputimage" /> <?= t('logout') ?></a>
                        <? } ?>

                        <? if ($GLOBALS['gs_obj']->isLogged() && !empty($_REQUEST['curTable'])) { ?>
                            <div id="rmenu" class="menu4 bloc2">
                                <ul >
                                    <?php
                                    $tables = getTables();
                                    $nb = 1;
                                    foreach ($_Gconfig['bigMenus'] as $k => $menus) {
                                        $men = current($menus);
                                        $t = '<li ><a href="#" id="menu_' . $k . '" ><img src="' . getPicto($men, '16x16') . '" alt=""/> ' . ta($k) . '</a><ul class="bloc2 menu_' . $nb . '" id="content_' . $k . '" class="" >';
                                        $h = '';
                                        foreach ($menus as $menu) {
                                            if ($GLOBALS['gs_obj']->can('edit', $menu)) {
                                                $url = in_array($menu, $tables) ? 'index.php?curTable=' . $menu : ta('cp_link_' . $menu);
                                                $h .= '<li><a href="' . $url . '" ><img src="' . getPicto($menu, '16x16') . '" alt=""/> <span>' . ta('cp_txt_' . $menu) . '</span></a></li>';
                                            }
                                        }
                                        if ($h) {
                                            $nb++;
                                            echo $t . $h . '</ul>';
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                            <style type="text/css">
        <?php
        $nb--;
        echo '.menu_' . $nb . ' , .menu_' . ($nb - 1) . ' {left:auto!important;right:-5px!important;} ';
        ?>
                            </style>
                            <div class="clearer"></div>
                        <?php } ?>
                        <a class="logoa" href="index.php?home=1" style="height:65px;background-image:url(img/logo.png)!important;background-repeat:no-repeat"><h1>&nbsp;</h1></a>
                    </div>
                </div>



            <?php } ?>

            <div id="bas">

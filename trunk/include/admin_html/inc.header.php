<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Administration :: <?php echo ta('base_title') ?> :: </title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <?php
        $css = array(
            /*    'admin/css/style.css',
              'admin/css/style_suite.css', */
            'admin/css/arbo.css',
            /* 'admin/genform/css/genform.css', */
            'admin/jq/css/bootstrap/jquery-ui-1.8.16.custom.css',
            'admin/css/bootstrap.css',
            /*'admin/css/bootstrap.ocms.css',*/
            'admin/jq/css/tipsy.css',
            'admin/jq/css/fg.menu.css',
            'admin/jq/css/jquery.tagedit.css',
            'admin/css/style_v2.css'
        );
        $js = array(
            'admin/jq/js/jquery.js',
            'admin/js/bootstrap.min.js',
            'admin/genform/js/tjmlib.js',
            'admin/js/script.js',
            'admin/js/xhr.js',
            'admin/js/ajaxForm.js',
            'admin/jq/js/jquery-ui.js',
            'admin/jq/js/jquery.textarearesizer.compressed.js',
            'admin/jq/js/jquery.tablednd_0_5.js',
            'admin/jq/js/jquery.ui.nestedSortable.js',
            'admin/jq/js/jquery.autoGrowInput.js',
            'admin/jq/js/jquery.tagedit.js',
            'admin/jq/js/jquery.tree.js',
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
                <div class="navbar navbar-fixed-top">
                    <div class="navbar-inner">
                        <div class="container" style="width:auto!important">
                            <a class="brand" style="padding-top:0;padding-bottom:5px;line-height:35px;color:white;position:relative;height:35px;" href="index.php?home=1" >
                                <img style="vertical-align:bottom" src="img/logo.png" alt="" height="30"/>
                                <span style="font-family:Times new roman;text-transform:lowercase;position:relative;top:11px;left:-49px;font-size:18px;"><?php echo ta('base_title') ?></span>
                            </a>
                            <? if ($GLOBALS['gs_obj']->isLogged()) { ?>


                                <div class="btn-group pull-right">
                                    <a class="btn dropdown-toggle" href="#" data-toggle="dropdown">
                                        <i class="icon-user"></i>
                                        <?= $GLOBALS['gs_obj']->adminnom ?>
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <span class="badge" style="margin:10px;display: block"><?= ta('derniere_connexion') ?><br/><?= niceDateTime(($_SESSION['last_cx'])) ?></span>
                                        </li>
                                        <li class="divider"></li>
                                        <li style="text-align:right">
                                            <a href="index.php?logout=1"><i class="icon-eject"></i> <?= t('logout') ?></a>
                                        </li>
                                    </ul>
                                </div>


                                <div class="nav-collapse pull-right">
                                    <ul class="nav" >
                                        <?php
                                        $tables = getTables();
                                        $nb = 1;
                                        $menus = array_merge($_Gconfig['bigMenus'], $_Gconfig['adminMenus']);
                                        foreach ($menus as $k => $menus) {
                                            $men = current($menus);
                                            $t = '<li class="dropdown"><a href="#" id="menu_' . $k . '" class="dropdown-toggle" data-toggle="dropdown"><img src="' . getPicto($men, '16x16') . '" alt=""/> ' . ta($k) . '</a><ul class="dropdown-menu" id="content_' . $k . '" class="" >';
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



                            <? } ?>
                        </div>
                    </div>
                </div>



            <?php } ?>

            <div id="bas" class="container-fluid">

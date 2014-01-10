<div class="home">
    <?php
    $tables = getTables();

    global $_Gconfig;

    p('<div class="bigmenus">');
//p('<h2>'.t('main').'</h2>');
    ksort($_Gconfig['bigMenus']);
    foreach ($_Gconfig['bigMenus'] as $k => $menus) {
        if (!count($menus)) {
            continue;
        }
        $head = ('<div><h2>' . t($k) . '</h2><ul class="row-fluid nav nav-tabs nav-stacked">');
        $in = '';
        foreach ($menus as $menu) {

            if ($GLOBALS['gs_obj']->can('edit', $menu)) {
                $url = in_array($menu, $tables) ? 'index.php?curTable=' . $menu : (tradExists('cp_link_' . $menu) ? ta('cp_link_' . $menu) : 'index.php?userAction=' . $menu);

                $in .= ('<li class="span4">
			<a   href="' . $url . '">
				<img src="' . getPicto($menu, '48x48') . '" alt="" />
				<span>' . t('cp_txt_' . $menu) . '</span>
			</a></li>
			');
            }
        }
        if (strlen($in)) {
            echo $head . $in;
            p('</ul></div>');
        }
    }

    p('</div>');


    p('<div class="row-fluid">');

    foreach ($_Gconfig['adminMenus'] as $k => $menus) {


        p('<div id="mm_' . $k . '" class="span3" >');

        if (strlen($k) > 1) {
            p('<h2 >' . t($k) . '</h2>');
        }

        $dones = 0;
        p('<div class="well"><ul class="nav nav-list">');
        foreach ($menus as $menu) {
            ?>

            <?php
            if ($GLOBALS['gs_obj']->can('edit', $menu)) {

                $dones++;

                $url = in_array($menu, $tables) ? 'index.php?curTable=' . $menu : ta('cp_link_' . $menu);
                ?>
                <li>
                    <a  href="<?= $url ?>"   >
                        <?php
                        /*
                        global $tabForms;
                        if (isset($tabForms[$menu]) && isset($tabForms[$menu]['picto'])) {
                            $src = $tabForms[$menu]['picto'];
                        } else
                        if (file_exists('./img/picto_' . $menu . '.gif')) {
                            $src = './img/picto_' . $menu . '.gif';
                        } else {
                            $src = './img/picto_default.gif';
                        }*/
                        $src = getPicto($menu,'16x16');
                        ?>
                        <img src="<?= $src ?>" alt=""  />
                        <?= t('cp_txt_' . $menu); ?></a>
                </li>
                <?php
            }
        }

        p('</ul></div></div>');

        if (!$dones) {
            p('<style type="text/css">#mm_' . $k . ' {display:none;}</style>');
        }
    }
    p('</div>');
    //p('<div id="mm_' . $k . '" class="picto_section" style="padding-top:20px;">');
    ?>
</div>
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
        $head = ('<div><h2>' . t($k) . '</h2><ul class="nav nav-pills">');
        $in = '';
        foreach ($menus as $menu) {

            if ($GLOBALS['gs_obj']->can('edit', $menu)) {
                $url = getAdminLink($menu);

                $in .= ('<li >
			<a class="well" style="margin-right:10px"  href="' . $url . '">
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


    p('<ul class="nav nav-pills">');

    foreach ($_Gconfig['adminMenus'] as $k => $menus) {


        p('<li id="mm_' . $k . '" class="" style="margin-right:10px;" >');

        if (strlen($k) > 1) {
            p('<h3 >' . t($k) . '</h3>');
        }

        $dones = 0;
        p('<div class="well"><ul class="nav nav-list">');
        foreach ($menus as $menu) {
            ?>

            <?php
            if ($GLOBALS['gs_obj']->can('edit', $menu)) {

                $dones++;

                $url =  getAdminLink($menu);
                ?>
                <li>
                    <a  href="<?= $url ?>"   >
                        <?php

                        $src = getPicto($menu, '16x16');
                        ?>
                        <img src="<?= $src ?>" alt=""  />
                        <?= t('cp_txt_' . $menu); ?></a>
                </li>
                <?php
            }
        }

        p('</ul></div></li>');

        if (!$dones) {
            p('<style type="text/css">#mm_' . $k . ' {display:none;}</style>');
        }
    }
    p('</ul>');
    ?>
</div>
<!DOCTYPE html>
<html lang="<?= LG ?>">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="generator" content="Opixido cms" />
        <meta name="robots" content="index,follow" />

        <link rel="icon" href="<?= BU ?>/favicon.ico" type="image/gif" />
        <link rel="shortcut icon" type="image/gif" href="<?= BU ?>/favicon.ico" />
        <link rel="Stylesheet" type="text/css" href="<?= BU ?>/css/print.css" media="print" />

        <script src="<?= BU ?>/js/html5shiv.js" ></script>
        <script src="<?= BU ?>/js/all.min.js" ></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" ></script>
        <script src="<?= BU ?>/js/CrashCTT_400.font.js"></script>
        <script src="<?= BU ?>/js/Permanent_Marker_400.font.js"></script>
        <!--<script src="<?= BU ?>/js/Permanent_Marker_400_400.font.js"></script>-->
        <link href='http://fonts.googleapis.com/css?family=Permanent+Marker' rel='stylesheet' type='text/css' />

        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>

        <?php echo $this->g_headers->gen() ?>

        <script type="text/javascript">
            window.bu = "<?= BU ?>";
            window.Trads = new Array();
            window.Trads['simpleform_check'] = <?= alt(t('simpleform_check')) ?>;
            window.Trads['terminer_confirm'] = <?= alt(t('terminer_confirm')) ?>;
        </script>
    </head>

    <body>
        <div id="loading"><input type="text" id="log" value="" /></div>

        <div id="largeur">

            <div id="logo">
                <?= $GLOBALS['logo'] ?>
            </div>

            <a id="retour_rmn" href="<?= t('url_rmngp_jeunepublic') ?>"><img src="<?= BU ?>/img/retour_rmn.png" alt=<?= alt(t('retour_site_rmn')) ?> title=<?= alt(t('retour_site_rmn')) ?> /></a>

            <div id="main">
                <?php
                echo $this->g_rubrique->genMain()
                ?>
            </div>

            <footer>
                <img src="<?= BU ?>/img/logo_rmn.png" alt="RMN Copyright 2012" />
                <span class="partenaires">
                    <?= $GLOBALS['partenaires'] ?>
                </span>
            </footer>

        </div>

    </body>
</html>
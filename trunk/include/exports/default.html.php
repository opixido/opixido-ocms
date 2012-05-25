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
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js " ></script>

        <?php echo $this->g_headers->gen() ?>

        <script type="text/javascript">
            window.bu = "<?= BU ?>";
            window.Trads = new Array();
        </script>
    </head>

    <body>

        r

        <div id="all">

            <section id="gauche">
                <div id="gauche_in">
                    <div id="logo">
                        <a href="<?= getUrlFromId($this->g_url->rootHomeId) ?>"><img src="<?= BU ?>/img/logo.png" alt=<?= alt(t('alt_logo')) ?> /></a>
                    </div>
                    <nav id="menus">
                        <?php
                        echo $this->g_rubrique->Execute('genMenuGauche');
                        ?>
                    </nav>

                    <nav id="tools">
                        <p>
                            <a id="lien_if" href="<?= t('http://www.institutfrancais.com') ?>" target="_blank" title=<?= alt(t('Site de l\'institut Français - nouvelle fenêtre')) ?>><?= t('www.institutfrancais.com') ?></a>
                        </p>
                        <p>
                            <a href="<?= getUrlFromId(getRubFromGabarit('genContact')) ?>"><img src="<?= BU ?>/img/contact.png" alt=<?= alt(t('Contact')) ?> /></a>
                            <a id="facebook_link" href=""><img src="<?= BU ?>/img/facebook.png" alt=<?= alt(t('Facebook')) ?> /></a>
                            <a href="<?= getUrlFromId(getRubFromGabarit('genCredits')) ?>"><img src="<?= BU ?>/img/credits.png" alt=<?= alt(t('Crédits')) ?> /></a>
                        </p>
                    </nav>
                </div>

            </section>

            <section id="droite">
                <div id="main">
                    <?php
                    echo $this->g_rubrique->genMain()
                    ?>
                </div>
            </section>

        </div>

    </body>
</html>
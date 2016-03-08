<!DOCTYPE html>
<html lang="<?= LG ?>" class="no-js" prefix="og: http://ogp.me/ns#">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="generator" content="Opixido cms"/>
    <meta name="robots" content="index,follow"/>

    <link rel="icon" href="<?= BU ?>/favicon.ico" type="image/gif"/>
    <link rel="shortcut icon" type="image/gif" href="<?= BU ?>/favicon.ico"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>


    <?php echo $this->g_headers->genHtmlHeaders() ?>
    <?php echo $this->g_headers->genCss() ?>

    <?php echo $this->g_headers->genJs('header_global') ?>
    <?php echo $this->g_headers->genJs('header_page') ?>

    <script>window.bu = "<?= BU ?>";</script>

</head>

<body class="">

    <header>
        <a href="<?= BU ?>/<?= LG ?>"><img src="<?= BU ?>/img/logo.png" alt="Opixido"/></a>
        <?php
        echo $this->plugins['o_blocs']->header->gen();
        ?>
    </header>
    <article id="largeur" class="wrapper">
        <?php
        echo $this->plugins['o_blocs']->main_before->gen();
        echo $this->g_rubrique->genMain();
        echo $this->plugins['o_blocs']->main_after->gen();
        ?>
    </article>

    <footer>
        <?php
        echo $this->plugins['o_blocs']->footer->gen();
        ?>
    </footer>


    <?php echo $this->g_headers->genJs('footer_global') ?>
    <?php echo $this->g_headers->genJs('footer_page') ?>
    <?php echo $this->g_rubrique->execute('footer_page') ?>


</body>
</html>

<!DOCTYPE html>
<html lang="<?= LG() ?>" class="no-js" prefix="og: http://ogp.me/ns#">
<head>

    <meta charset="utf-8"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="generator" content="Opixido cms"/>
    <meta name="robots" content="index,follow"/>

    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <script
            type="text/javascript"
            src="https://app.termly.io/embed.min.js"
            data-auto-block="on"
            data-website-uuid="1584b5f8-5a41-47ca-ac73-ffdcbe8bc5dd"
    ></script>

    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-39459995-11"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-39459995-11',  {
        cookie_expires:395*24*60*60,
        });
    </script>

    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <link rel="shortcut icon" href="/favicon.ico?v=rMBq0mNAnj">


    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=yes"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>



  <?php echo $this->g_headers->genHtmlHeaders() ?>
  <?php echo $this->g_headers->genCss() ?>

  <?php echo $this->g_headers->genJs('header_global') ?>
  <?php echo $this->g_headers->genJs('header_page') ?>

    <script>window.bu = "<?= BU ?>";</script>

</head>

<body class="">

<header>
  <?php
  echo $this->plugins['o_blocs']->header->gen();
  ?>
</header>
<article id="largeur" class="wrapper">

  <?php
  echo $this->plugins['o_blocs']->main_before->gen();
  ?>
    <div class="largeur-inside">
      <?php
      echo $this->g_rubrique->genMain();
      echo $this->plugins['o_blocs']->main_after->gen();
      ?>
    </div>
</article>

<footer>
  <?php
  echo $this->plugins['o_blocs']->footer->gen();
  ?>

    <div id="menu-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <nav>
                      <?php echo $this->menus['footer']->getTab() ?>
                    </nav>
                </div>
            </div>
        </div>
        <div class="btn-cookies">
              <button class="termly-cookie-preference-button" type="button" onclick="displayPreferenceModal()"><?= t('btn_cookies') ?></button>
        </div>
    </div>

</footer>


<?php echo $this->g_headers->genJs('footer_global') ?>
<?php echo $this->g_headers->genJs('footer_page') ?>
<?php echo $this->g_rubrique->execute('footer_page') ?>

</body>
</html>

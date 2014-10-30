

<div id="gs_box" style="text-align:left;position:absolute;left:50%;width:300px;margin-left:-150px;margin-top:50px;" >


    <h1 style="vertical-align: middle;text-transform: uppercase;font-size:110%">
        <i class="icon-home"></i> <?php print(t('identification')); ?></legend>
    </h1>

    <?php if (!empty($GLOBALS['errors'])) { ?>
        <div class="alert alert-error">
            <?= $GLOBALS['errors'] ?>
        </div>
    <?php } ?>


    <form class="form-vertical well"  action="<?php print($_SERVER['REQUEST_URI']) ?>" method="post" >


        <p style="margin-left:20px;">
            <label for="gs_adminuser" style="display:block;" >
                <i class="icon-user"></i>
                <?= t('username') ?> </label>
            <input id="gs_adminuser"  name="gs_adminuser" type="text" value=<?= alt(akev($_REQUEST, 'gs_adminuser')); ?> />
        </p>

        <p style="margin-left:20px;">
            <label for="gs_adminpassword" style="display:block;">
                <i class="icon-lock"></i>
                <?= t('password') ?></label>
            <input  id="gs_adminpassword" name="gs_adminpassword" type="password" value="" />
        </p>
        <p style="margin-left:20px;">


            <button class="btn btn-primary"> <i class="icon-ok icon-white"></i> &nbsp; <?= t('connexion') ?></button>

        </p>

        <input name="gs_fromForm" type="hidden" value="1" />
        <input name="gs_askedFor" type="hidden" value="<?php print($_SERVER['REQUEST_URI']); ?>" />



    </form>



</div>


<div id="ie6warning" style="text-align:center;position:absolute;left:50%;width:300px;margin-left:-150px;margin-top:350px;border:1px solid;background:#F5F6BE;padding:5px;display:none;" >
    Vous utilisez le navigateur INTERNET EXPLORER Version 6<br/><br/>
    Afin de profiter au mieux des fonctionnalités de l'interface d'administration, nous vous conseillons de passer à Internet Explorer 7 ou Firefox 2

</div>
<!--[if lt IE 7.]>
<script >
gid("ie6warning").style.display = "block";
</script>
<![endif]-->